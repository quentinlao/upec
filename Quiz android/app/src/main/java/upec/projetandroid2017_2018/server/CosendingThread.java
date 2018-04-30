package upec.projetandroid2017_2018.server;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.bluetooth.BluetoothSocket;
import android.util.Log;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.ArrayList;

import upec.projetandroid2017_2018.util.Constante;
import upec.projetandroid2017_2018.util.Question;
import upec.projetandroid2017_2018.util.SocketUtils;

import static android.content.ContentValues.TAG;

/**
 * Created by Quentin on 02/03/2018.
 */
/* CONNEXION PROF AU ELEVE ET ENVOIE QUESTION */
public class CosendingThread extends Thread {
    private final BluetoothAdapter adapter;
    private final BluetoothDevice server;
    private ArrayList<Question> question;
    private ServerActivity serverActivity;
    private InputStream inputStream;
    private OutputStream outputStream;
    private BluetoothSocket socket;

    public CosendingThread(BluetoothAdapter blueAdapter, BluetoothDevice server) {
        this.adapter = blueAdapter;
        this.server = server;
    }
    public void sendRequest(ArrayList<Question> question, ServerActivity serverActivity) {
        this.question = question;
        this.serverActivity = serverActivity;
        socket = buildSocket();

        start();
    }
    private BluetoothSocket buildSocket() {
        try {

            return server.createRfcommSocketToServiceRecord(Constante.PCUUID);
        } catch (IOException ioe) {
            return null;
        }
    }

    @Override
    public void run() {
        try {
            adapter.cancelDiscovery();
            sendRequestAndReadResponse();
        } finally {
            close();
        }
    }

    private void sendRequestAndReadResponse() {
        if (connect()) {
            try {
                Log.d(TAG, "CONNEXION OK " );
                StringBuilder str = new StringBuilder();
                str.append(question.size()+"|");
                for(int i = 0;i < question.size();i++) {
                    str.append(question.get(i).getSujet() + "|" + question.get(i).getReponses()[0] + "+" + question.get(i).getReponses()[1] + "+" + question.get(i).getReponses()[2] + "+" + question.get(i).getReponses()[3]+"+" );
                    str.append("&"+question.get(i).getReponse()+"&");
                    str.append("|");
                }

                SocketUtils.writeString(str.toString(), outputStream);
            } catch (IOException ioe) {
                ioe.printStackTrace();
                return;
            }

            waitForResponse(Constante.RESPONSE_READ_DELAY_MILLIS);
        } else {
            Log.d(TAG, "Erreur " );
        }
    }

    private boolean connect() {
        if (socket == null) {
            return false;
        }

        try {

            socket.connect();
            if (socket.isConnected()) {
                inputStream = socket.getInputStream();
                outputStream = socket.getOutputStream();
                return true;
            } else {
                return false;
            }
        } catch (IOException ioe) {
            return false;
        }
    }

    private void waitForResponse(long millis) {
        try {
            sleep(millis);
        } catch (InterruptedException e) {}
    }



    private void close() {

        SocketUtils.closeSilently(inputStream);
        SocketUtils.closeSilently(outputStream);
        SocketUtils.closeSilently(socket);

        inputStream = null;
        outputStream = null;
        socket = null;
    }
}
