package upec.projetandroid2017_2018.util;

import android.content.Intent;
import android.os.Bundle;
import android.os.Parcel;
import android.os.Parcelable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import java.util.ArrayList;

import upec.projetandroid2017_2018.R;

/**
 * Created by Quentin on 08/03/2018.
 */

public class Creation extends AppCompatActivity implements Parcelable{

    ArrayList<Question> questions = new ArrayList<>();
    int i = 1;
    int n ;

    public Creation() {}

    public Creation(ArrayList<Question> questions) {
        this.questions = questions;
    }

    protected Creation(Parcel in) {
        questions = in.createTypedArrayList(Question.CREATOR);
    }

    public ArrayList<Question> getQuestions () {
        return questions;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeTypedList(questions);
    }

    @Override
    public int describeContents() {
        return 0;
    }

    public static final Creator<Creation> CREATOR = new Creator<Creation>() {
        @Override
        public Creation createFromParcel(Parcel in) {
            return new Creation(in);
        }

        @Override
        public Creation[] newArray(int size) {
            return new Creation[size];
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_creation);
        n = getIntent().getIntExtra("nombre",1);


        final TextView numero = (TextView) findViewById(R.id.qNumero);
        numero.setText("question " + i + "/" + n );
        final EditText titre = (EditText) findViewById(R.id.titre);
        final EditText reponse = (EditText) findViewById(R.id.reponse);
        final EditText a = (EditText) findViewById(R.id.a);
        final EditText b = (EditText) findViewById(R.id.b);
        final EditText c = (EditText) findViewById(R.id.c);
        final EditText d = (EditText) findViewById(R.id.d);
        final Button suivant = (Button) findViewById(R.id.next);
        suivant.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(i<=n) {
                    if (titre.getText().toString().length() != 0 && reponse.getText().toString().length() != 0 && a.getText().toString().length() != 0 && b.getText().toString().length() != 0 && c.getText().toString().length() != 0 && d.getText().toString().length() != 0) {
                        Question q = new Question(titre.getText().toString(), reponse.getText().toString(), a.getText().toString(), b.getText().toString(), c.getText().toString(), d.getText().toString());
                        questions.add(q);
                        if(i !=n)
                        clearInput();
                    }
                }
                if (i >= n) {
                    Creation creation = new Creation(questions);
                    Intent intent = new Intent();
                    intent.putExtra("questionnaire", creation);
                    setResult(RESULT_OK, intent);
                    finish();
                }
                i++;
            }
        });


    }
    public void clearInput () {
        final TextView numero = (TextView) findViewById(R.id.qNumero);
        numero.setText("Question " + (i+1) + "/" + n );
        final EditText titre = (EditText) findViewById(R.id.titre);
        titre.setText("Question "+ (i+1));
        final EditText reponse = (EditText) findViewById(R.id.reponse);
        reponse.setText("A"+ (i+1));
        final EditText a = (EditText) findViewById(R.id.a);
        a.setText("A"+ (i+1));
        final EditText b = (EditText) findViewById(R.id.b);
        b.setText("B"+ (i+1));
        final EditText c = (EditText) findViewById(R.id.c);
        c.setText("C"+ (i+1));
        final EditText d = (EditText) findViewById(R.id.d);
        d.setText("D"+ (i+1));
    }
}
