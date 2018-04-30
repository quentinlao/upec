package upec.projetandroid2017_2018.util;

import android.content.Intent;
import android.os.Bundle;
import android.os.Parcel;
import android.os.Parcelable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import java.util.ArrayList;

import upec.projetandroid2017_2018.R;

/**
 * Created by Quentin on 08/03/2018.
 */

public class FormulaireQ extends AppCompatActivity implements Parcelable {


    ArrayList<Question> questionnaires = new ArrayList<>();

   public FormulaireQ(){}
    public FormulaireQ (  ArrayList<Question> questionnaire) {this.questionnaires = questionnaire;}

    protected FormulaireQ(Parcel in) {
        questionnaires = in.createTypedArrayList(Question.CREATOR);
    }

    public static final Creator<FormulaireQ> CREATOR = new Creator<FormulaireQ>() {
        @Override
        public FormulaireQ createFromParcel(Parcel in) {
            return new FormulaireQ(in);
        }

        @Override
        public FormulaireQ[] newArray(int size) {
            return new FormulaireQ[size];
        }
    };
    /* ON ENVOIE  AVEC LE BON NOMBRE DE QUESTION */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_number);

        Button send = findViewById(R.id.create);
        send.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                EditText editText = findViewById(R.id.nombre);
                Intent i = new Intent (FormulaireQ.this, Creation.class);
                if (editText.getText().toString().length() == 0) {

                } else {
                    int t = Integer.parseInt(editText.getText().toString());
                    i.putExtra("nombre",t);
                }
                startActivityForResult(i,1);
            }
        });


    }
    /* ON RECUPERE LE TABLEAU DE QUESTION ET ON LE RENVOIE DIRECT A L'ACTIVITE POUR QU'IL PUISSE ICI REFAIRE DE NOUVELLE QUESTION SI IL A ENVIE */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        if (requestCode == 1) {
            if (resultCode == RESULT_OK) {
                Intent i = new Intent();
                Creation maCreation = data.getParcelableExtra("questionnaire");
                questionnaires = maCreation.getQuestions();
                FormulaireQ listeQ = new FormulaireQ(questionnaires);
                 i.putExtra("listeQ",listeQ);
                setResult(RESULT_OK,i);


            }
        } finish();


    }

    public ArrayList<Question> getQuestions () {
        return questionnaires;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeTypedList(questionnaires);
    }
}
