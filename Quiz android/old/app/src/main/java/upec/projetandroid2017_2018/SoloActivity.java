package upec.projetandroid2017_2018;

import android.app.PendingIntent;
import android.content.Intent;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.support.v4.app.NotificationManagerCompat;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import java.util.ArrayList;

import upec.projetandroid2017_2018.util.BarChartActivity;
import upec.projetandroid2017_2018.util.FormulaireQ;
import upec.projetandroid2017_2018.util.Question;
import upec.projetandroid2017_2018.server.Student;

/**
 * Created by Quentin on 09/03/2018.
 */

public class SoloActivity extends AppCompatActivity {
    ArrayList<Question> questionnaire = new ArrayList<>();
    ArrayList<Student> students = new ArrayList<>();
    @Override
    protected void onCreate( Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_solo);
        Button creation = findViewById(R.id.creation);
        creation.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent (SoloActivity.this, FormulaireQ.class);
                startActivityForResult(i,1);
            }
        });
        students.add(new Student(null,"SoloPlayer"));
    }
    int lol = 0;
    @Override
    protected void onResume() {
        super.onResume();
        if(questionnaire.isEmpty()) System.out.println("oui vide");
        else{
            removeAll();
            System.out.println("è_é");
            LinearLayout ly = (LinearLayout) findViewById(R.id.ly);

                final TextView mTimer = new TextView(this);
                mTimer.setText("Question " + (lol+1) + " / " + questionnaire.size());
                ly.addView(mTimer);
                TextView t = new TextView(this);
                t.setText("Titre : " + questionnaire.get(lol).getSujet());
                t.setTextSize(20);
                t.setPadding(10,30,10,30);
                ly.addView(t);

                final String choix[] = questionnaire.get(lol).getReponses();
                for( int i =0; i <choix.length ;i++){
                    final int cmpt = i;
                    TextView cx = new TextView(this);
                    cx.setText("Choix "+i +" : "+ choix[i]);
                    cx.setTextSize(20);
                    cx.setPadding(10,30,10,30);
                    ly.addView(cx);
                    cx.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            ArrayList<String> grilleRep = students.get(0).getReponses();
                            grilleRep.add(choix[cmpt]);
                            students.get(0).setReponses(grilleRep);

                            if(lol<questionnaire.size()-1){
                                lol++;

                                onResume();
                            }
                            else{
                                removeAll();
                                StringBuilder buildStr = new StringBuilder();
                                buildStr.append("Reponse de l'etudiant : \n");
                                for(int i =0; i<questionnaire.size();i++){
                                  buildStr.append(grilleRep.get(i) + " ( Reponse :" + questionnaire.get(i).getReponse() +" ) " + (grilleRep.get(i).equals(questionnaire.get(i).getReponse()))+" \n");
                                }
                                System.out.println(buildStr.toString());
                                notifIntent(buildStr.toString(),questionnaire,students.get(0));
                                TextView mTimer = new TextView(SoloActivity.this);
                                mTimer.setText(buildStr.toString());
                                LinearLayout ly = (LinearLayout) findViewById(R.id.ly);
                                ly.addView(mTimer);

                            }
                        }
                    });
                }

        }


    }
    void removeAll(){
        LinearLayout ll = (LinearLayout) findViewById(R.id.ly);
        ll.removeAllViews();
    }
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        if (requestCode == 1) {
            if (resultCode == RESULT_OK) {
                FormulaireQ maCreation = data.getParcelableExtra("listeQ");
                questionnaire = maCreation.getQuestions();
            }
        }

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
    void notifIntent(String winner,ArrayList<Question> q, Student s){
        int a = 0,b =0,c=0,d=0;
        for(int i = 0; i < q.size();i++){
            for(int j = 0 ; j < q.get(i).getReponses().length; j++){
                if(q.get(i).getReponses()[j].equals(s.getReponses().get(i))){
                    if(j ==0)  a++;
                    if(j== 1) b++;
                    if(j==2) c++;
                    if(j==3) d++;
                }
            }

        }
        Intent intent = new Intent(this, BarChartActivity.class);
        intent.putExtra("a",a);
        intent.putExtra("b",b);
        intent.putExtra("c",c);
        intent.putExtra("d",d);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, intent,PendingIntent.FLAG_UPDATE_CURRENT);

        NotificationCompat.Builder mBuilder = new NotificationCompat.Builder(this, "YO")
                .setSmallIcon(R.drawable.logo)
                .setLargeIcon(BitmapFactory.decodeResource(this.getResources(),
                        R.drawable.logo))
                .setContentTitle("Resultat")
                .setContentText("mes resultats" + winner)
                .setPriority(NotificationCompat.PRIORITY_DEFAULT)
                .setContentIntent(pendingIntent)
                .setAutoCancel(true);
        NotificationManagerCompat notificationManager = NotificationManagerCompat.from(this);

        notificationManager.notify(0, mBuilder.build());
    }


}
