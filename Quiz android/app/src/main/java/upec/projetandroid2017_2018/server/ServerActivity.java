package upec.projetandroid2017_2018.server;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import java.io.IOException;
import java.util.ArrayList;

import upec.projetandroid2017_2018.R;
import upec.projetandroid2017_2018.util.Constante;
import upec.projetandroid2017_2018.util.FormulaireQ;
import upec.projetandroid2017_2018.util.Question;

/**
 * Created by Quentin on 27/02/2018.
 */

public class ServerActivity extends AppCompatActivity {

    private ArrayList<Student> students = new ArrayList<>();
    private TextView responseTxt;
    private TextView requestCounterTxt;
    private TextView studentsTxt;
    private int counter;
    private Server remoteServer;
    private ArrayList<Question> questions = new ArrayList<>();
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_server);

        resolveViewReferences();


        Button buttonSend = (Button) findViewById(R.id.send);
        buttonSend.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                try
                {
                    sendData(questions);
                }
                catch (IOException ex) { }

            }
        });


        Button question = (Button) findViewById(R.id.question);
        question.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
               Intent intent = new Intent(ServerActivity.this, FormulaireQ.class);
               startActivityForResult(intent,1);

            }
        });

    }

    private void resolveViewReferences() {
        responseTxt = (TextView) findViewById(R.id.server_response);
        requestCounterTxt = (TextView) findViewById(R.id.server_request_counter);
        studentsTxt =  (TextView) findViewById(R.id.students);
    }

    @Override
    public void onResume() {
        super.onResume();

        remoteServer = ServerFactory.instance(students);
        remoteServer.init(this);

        setText(R.id.server_server_name, ("Name: " + Constante.NAME));
        setText(R.id.server_server_uuid, ("CPUUID: " + Constante.CPUUID.toString()));
    }

    public void onPause() {
        super.onPause();

        if (remoteServer != null) {
            remoteServer.close();
            remoteServer = null;
        }
    }

    private void setText(int resId, String text) {
        ((TextView) findViewById(resId)).setText(text);
    }

    public String process(String request,ArrayList<Student> students) {
        updateUi(++counter,request,students);

        return request;
    }
    /* RENVOIE LE BON STUDENT */
    Student existStudent(String name){
        for(Student s  : students){
            if(s.getPseudo().equals(name))return s;
        }
        return null;

    }
    /* SUPPRIME LA PREMIERE LETTRE DU MOT*/
     String avanceCurseur(String str) {
        if(str.length() > 1) {
            StringBuilder txt = new StringBuilder();
            for(int i = 1; i < str.toCharArray().length;i++) {
                txt.append(str.toCharArray()[i]);
            }

            return txt.toString();
        }
        else
            return str;
    }
    /* PARSE LA REPONSE ET LE NOM DU STUDENT POUR L'AFFICHER DANS LA VUE */
    private void updateUi(final int counter,  final String response, final ArrayList<Student> students) {
        final Runnable update = new Runnable() {
            @Override
            public void run() {
                responseTxt.setText(response);

                requestCounterTxt.setText(Integer.toString(counter));
                String rep [] = response.split("&");
                System.out.println(rep.length);
                if(rep.length>1){
                    System.out.println(avanceCurseur(rep[rep.length-1]));
                    Student moi = existStudent(avanceCurseur(rep[rep.length-1]));
                    ArrayList<String> moiRep = moi.getReponses();
                    for (int i = 0; i < rep.length-1;i++){
                        moiRep.add(rep[i]);
                    }
                    StringBuilder build2 = new StringBuilder();
                    for(Student str : students){
                        build2.append(str.getPseudo()+" : \n " + str.toStringRep(questions) + "\n");
                    }
                    System.out.println(build2.toString());

                    studentsTxt.setText(build2.toString());
                }
                else {


                    StringBuilder build = new StringBuilder();
                    for (Student str : students) {
                        build.append(str.getPseudo() + "\n");
                    }
                    studentsTxt.setText(build.toString());
                }
            }
        };

        runOnUiThread(update);
    }
    /* ENVOIE A TOUS LES STUDENTS QUI ONT DIT PRESENT*/
    void sendData(ArrayList<Question> question) throws IOException
    {
        for(int i = 0; i < students.size(); i++) {


            BluetoothAdapter blueAdapter = BluetoothAdapter.getDefaultAdapter();
            BluetoothDevice server = students.get(i).getClient(); // BluetoothUtils.findPairedDeviceByName(blueAdapter,clientName.getText().toString());


            if (blueAdapter != null && server != null) {
                final CosendingThread cosendingThread = new CosendingThread(blueAdapter, server);
                cosendingThread.sendRequest(question, this);

            }
        }
    }
    /* RECUPERE LE PARCELABLE DES QUESTIONS */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        if (requestCode == 1) {
            if (resultCode == RESULT_OK) {
                FormulaireQ maCreation = data.getParcelableExtra("listeQ");
                questions = maCreation.getQuestions();
            }
        }

    }



}
