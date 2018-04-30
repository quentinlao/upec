package upec.projetandroid2017_2018.server;

import android.bluetooth.BluetoothDevice;

import java.util.ArrayList;

import upec.projetandroid2017_2018.util.Question;

/**
 * Created by Quentin on 03/03/2018.
 */

public class Student {
    public ArrayList<String> getReponses() {
        return reponses;
    }

    public void setReponses(ArrayList<String> reponses) {
        this.reponses = reponses;
    }

    ArrayList<String> reponses = new ArrayList<String>();
    public BluetoothDevice getClient() {
        return client;
    }

    public String getPseudo() {
        return pseudo;
    }

    private BluetoothDevice client;
    private String pseudo;
    public Student(BluetoothDevice client, String pseudo){
        this.client = client;
        this.pseudo = pseudo;
    }
    String toStringRep(ArrayList<Question> questions){
        StringBuilder string = new StringBuilder();
        for(int i = 0; i < reponses.size();i++){
            string.append("Reponse " + (i+1) +" : "+ reponses.get(i) +" ( Reponse : " + questions.get(i).getReponse() + " ) " + (questions.get(i).getReponse().equals(reponses.get(i)) +" \n"));
        }
        return string.toString();
    }
}
