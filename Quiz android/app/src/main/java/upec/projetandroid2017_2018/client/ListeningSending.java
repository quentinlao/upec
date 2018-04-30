package upec.projetandroid2017_2018.client;

import android.bluetooth.BluetoothServerSocket;
import android.bluetooth.BluetoothSocket;
import android.util.Log;

import java.io.IOException;

import upec.projetandroid2017_2018.util.SocketUtils;

/**
 * Created by Quentin on 02/03/2018.
 */

/**
 * COTE SERVEUR QUI ECOUTE L'ENVOIE DES QUESTIONS
 */
public class ListeningSending extends Thread {

    private static final String TAG = ListeningSending.class.getSimpleName();
    private BluetoothServerSocket serverSocket;

    ClientActivity content;

    public ListeningSending(ClientActivity content) {
        this.content = content;
    }

    public void startListening(BluetoothServerSocket serverSocket) {

        this.serverSocket = serverSocket;
        start();
    }
    /* APPELER AVEC START() DEMARRAGE DU THREAD */
    @Override
    public void run() {
        while (serverSocket != null) {
            try {
                final BluetoothSocket socket = serverSocket.accept();
                processRequest(socket);
            } catch (IOException ioe) {
                stopListening();
            }
        }
    }

    /* DEMARRE LES EXECUTIONS DU MSG RECUS */
    private void processRequest(BluetoothSocket socket) {
        final Thread processingThread = new ProcesThread(socket, content);
        processingThread.start();
    }

    void stopListening() {
        final BluetoothServerSocket temp = serverSocket;
        serverSocket = null;

        SocketUtils.closeSilently(temp);
    }

}
