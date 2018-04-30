package upec.projetandroid2017_2018.client;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothServerSocket;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.support.v4.app.NotificationManagerCompat;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Collection;
import java.util.regex.Pattern;

import upec.projetandroid2017_2018.R;
import upec.projetandroid2017_2018.util.Question;
import upec.projetandroid2017_2018.server.RemoteResponse;
import upec.projetandroid2017_2018.server.Student;
import upec.projetandroid2017_2018.util.Constante;
import upec.projetandroid2017_2018.util.BluetoothUtils;

/**
 * Created by Quentin on 27/02/2018.
 */

public class ClientActivity extends AppCompatActivity implements  AdapterView.OnItemSelectedListener {
    private Spinner serverList;
    private TextView responseTxt;
    private TextView responseErrorTxt;
    private TextView name;
    Student student = new Student(null,"d");
    ArrayList<Question> questionnaire = new ArrayList<>();
    private Client client;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_client);

        affView();
        addListeners();
    }

    /* AFFECTE LES VUES */

    private void affView() {
        serverList = (Spinner) findViewById(R.id.client_server_list);
        responseTxt = (TextView) findViewById(R.id.client_response);
        responseErrorTxt = (TextView) findViewById(R.id.client_response_error);
        BluetoothAdapter mBluetoothAdapter = BluetoothAdapter.getDefaultAdapter();
        name = (TextView) findViewById(R.id.name);
        name.setText(mBluetoothAdapter.getName());
    }

    /* AJOUTE AU SPINNER  */
    private void addListeners() {
        serverList.setOnItemSelectedListener(this);
    }

    int compteur = 0;

    @Override
    public void onResume() {
        super.onResume();
        /* CONDTION DE JEU SI LE QUESTIONNAIRE EST NON VIDE */
        if(questionnaire.isEmpty()) System.out.println("oui vide");
        else{
            removeAll();
            LinearLayout ly = (LinearLayout) findViewById(R.id.lY);

            final TextView mTimer = new TextView(this);
            mTimer.setText("Question " + (compteur +1) + " / " + questionnaire.size());
            ly.addView(mTimer);
            TextView t = new TextView(this);
            t.setText("Titre : " + questionnaire.get(compteur).getSujet());
            t.setTextSize(20);
            ly.addView(t);

            final String choix[] = questionnaire.get(compteur).getReponses();
            for( int i =0; i <choix.length ;i++){
                final int cmpt = i;
                TextView cx = new TextView(this);
                cx.setText("Choix "+i +" : "+ choix[i]);
                cx.setTextSize(20);
                ly.addView(cx);
                cx.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        ArrayList<String> grilleRep = student.getReponses();
                        grilleRep.add(choix[cmpt]);
                        student.setReponses(grilleRep);

                        if(compteur <questionnaire.size()-1){
                            compteur++;

                            onResume();
                        }
                        else{
                            removeAll();
                            StringBuilder buildStr = new StringBuilder();
                            for(int i =0; i<questionnaire.size();i++){
                                buildStr.append(grilleRep.get(i) +"&");
                            }
                            System.out.println(buildStr.toString());

                            TextView mTimer = new TextView(ClientActivity.this);
                            String affichage = buildStr.toString();
                            String[] reponse = affichage.split(("&"));
                            StringBuilder myRep = new StringBuilder();
                            for (int i = 0; i<reponse.length;i++){
                                myRep.append("Question  "+(i+1)+" : " + reponse[i] +  "( Reponse :" + questionnaire.get(i).getReponse() +" ) " + (reponse[i].equals(questionnaire.get(i).getReponse()))+ "\n");
                            }
                            notification(myRep.toString());
                            mTimer.setText(myRep.toString());
                            LinearLayout ly = (LinearLayout) findViewById(R.id.lY);
                            ly.addView(mTimer);
                            client.send(buildStr.toString(),name.getText().toString(), ClientActivity.this);

                        }
                    }
                });
            }

        }
        client = ClientFactory.instance();

        showPairedDevices();

        final BluetoothServerSocket serverSocket = buildServerSocket();
        if (serverSocket == null) {

        }
        else {
            ListeningSending listeningSending = new ListeningSending(this);
            listeningSending.startListening(serverSocket);
        }


    }

    /* SUPPRIME LE CONTENU DU LINEAR */

    void removeAll(){
        LinearLayout ll = (LinearLayout) findViewById(R.id.lY);
        ll.removeAllViews();
    }

    /* CREER LE SOCKET */

    private BluetoothServerSocket buildServerSocket() {
        final BluetoothAdapter adapter = BluetoothUtils.getBluetoothAdapter();
        if (adapter == null) {
            return null;
        }
        try {
            return adapter.listenUsingRfcommWithServiceRecord(Constante.NAME, Constante.PCUUID);
        } catch (IOException ioe) {
            return null;
        }
    }

    /* AFFICHE LA LISTE APPAREILS APPAREILLES */

    private void showPairedDevices() {
        final Collection<CharSequence> deviceNames = client.listServers();
        if (deviceNames != null && !deviceNames.isEmpty()) {
            final ArrayAdapter<CharSequence> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, new ArrayList<>(deviceNames));
            serverList.setAdapter(adapter);
        }
    }

    /* CONNEXION AU SERVEUR SUR LA SELECTION DU SPINNER */
    @Override
    public void onItemSelected(AdapterView<?> adapterView, View view, int index, long id) {
        final Object server = adapterView.getItemAtPosition(index);
        if (server != null) {
            connect(server.toString());
        }
    }

    /* SI RIEN N'EST SELECTIONNE */
    @Override
    public void onNothingSelected(AdapterView<?> adapterView) {}

    /* CONNEXION CLIENT PROF */
    private void connect(String serverDeviceName) {
        if (client.connect(serverDeviceName)) {
            setText(R.id.client_server_uuid, ("Client prof UUID: " + Constante.CPUUID.toString()));
        } else {
            client = null;
            Toast.makeText(this, "CONNEXION FAIL: " + serverDeviceName, Toast.LENGTH_SHORT).show();
        }
    }
    /* MODIFICATION D'UN TXTVIEW CAR PAS POSSIBLE DANS UN THREAD */
    private void setText(int resId, String text) {
        ((TextView) findViewById(resId)).setText(text);
    }

    /* ENVOIE DE LA REQUETE PRESENT+NOM */
    public void onSend(View view) {
        final String request = "Present";
        if (client != null && request != null) {
            client.send(request,name.getText().toString(), this);
        }
    }

    /* SEUL MOYEN D'UPDATE UNE VIEW */
    public void responseArrived(final RemoteResponse response) {
        final Runnable update = new Runnable() {
            @Override
            public void run() {
                if (response.success()) {
                    responseTxt.setText(response.getContent());
                    responseErrorTxt.setText("");
                } else {
                    responseTxt.setText("");
                    responseErrorTxt.setText(response.getErrorMessage());
                }
            }
        };

        runOnUiThread(update);
    }
    /* AFFICHE LA LISTE DES SERVEURS */

    public String process(String request) {
        updateUi( request);
        parseToQuestion(request,(int)(request.charAt(0)));
        return request;
    }

    /* PARSE LE CHAMPS TXTQUESTION RECU PAR LE PROF */
    void parseToQuestion(String parse,int n){
        for(int i = 0; i<n;i++) {
            String[] secretVal = parse.split("&");
            String sujet = parseSujet(parse);
            parse = parse.replace("|" + sujet, "");

            String choix = parseSujet(parse);
            String temp = choix;

            String[] monchoix = temp.split(Pattern.quote("+"));
            parse = parse.replace("|" + choix, "");
            System.out.println("sujet : " + sujet + " Choix " + choix);

            System.out.println("le secret : " + secretVal[1]);
            questionnaire.add(new Question(sujet,secretVal[1],monchoix[0],monchoix[1],monchoix[2],monchoix[3]));
        }

    }
    /* PARSE LE TITRE DE LA QUESTION */
   String parseSujet(String parse){

       String str = parse;
       StringBuilder sujet = new StringBuilder();
       boolean state = false;
       for (int i=0; i < str.length(); i++) {
           if(state == true && str.charAt(i) == '|') break;
           if(state ==true) sujet.append(str.charAt(i));
           if (str.charAt(i) == '|' && state ==false) {
               i++;
               sujet.append(str.charAt(i));
               state=true;
           }
       }
       return sujet.toString();
    }
    /* MODIFIE LE CHAMPS DE LA REQUETE */
    private void updateUi( final String request) {
        final Runnable update = new Runnable() {
            @Override
            public void run() {

                responseTxt.setText(request);
             onResume();
            }
        };

        runOnUiThread(update);
    }

    void notification(String answ){

        NotificationCompat.Builder mBuilder = new NotificationCompat.Builder(this, "ok")
                .setSmallIcon(R.drawable.logo)
                .setLargeIcon(BitmapFactory.decodeResource(this.getResources(),
                        R.drawable.logo))
                .setContentTitle("Resultat")
                .setContentText("Etudiant " + answ)
                .setStyle(new NotificationCompat.BigTextStyle()
                        .bigText("Etudiant " + answ))
                .setPriority(NotificationCompat.PRIORITY_DEFAULT);

        NotificationManagerCompat notificationManager = NotificationManagerCompat.from(this);

        notificationManager.notify(0, mBuilder.build());
    }

}
