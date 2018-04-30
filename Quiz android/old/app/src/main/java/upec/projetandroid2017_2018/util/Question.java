package upec.projetandroid2017_2018.util;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by Quentin on 03/03/2018.
 */

public class Question implements Parcelable{


    private String sujet;

    public String[] getReponses() {
        return reponses;
    }

    private String [] reponses ;

    public String getReponse( ) {
        return reponse;
    }

    private String reponse;

    public Question(String sujet ){
        this.sujet = sujet;
    }
    public Question(String titre, String a, String b, String c, String d) {
        this.sujet = titre;
        this.reponses = new String [] {a, b, c ,d};
    }

    public Question(String titre, String reponse, String a, String b, String c, String d) {
        this.sujet = titre;
        this.reponses = new String [] {a, b, c ,d};
        this.reponse = reponse;
    }

    public String getSujet() {
        return sujet;
    }

    protected Question(Parcel in) {
        sujet = in.readString();
        reponses = in.createStringArray();
        reponse = in.readString();
    }

    public String toString() {
        return sujet + " 1)" + reponses[0] + " 2) " +  reponses [1] + " 3) " + reponses[2] + " 4)" + reponses[3];
    }
    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(sujet);
        dest.writeStringArray(reponses);
        dest.writeString(reponse);
    }

    @Override
    public int describeContents() {
        return 0;
    }

    public static final Creator<Question> CREATOR = new Creator<Question>() {
        @Override
        public Question createFromParcel(Parcel in) {
            return new Question(in);
        }

        @Override
        public Question[] newArray(int size) {
            return new Question[size];
        }
    };



}
