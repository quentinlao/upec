package upec.projetandroid2017_2018.server;

import android.bluetooth.BluetoothServerSocket;
import android.bluetooth.BluetoothSocket;
import android.util.Log;

import java.io.IOException;
import java.util.ArrayList;

import upec.projetandroid2017_2018.util.Constante;
import upec.projetandroid2017_2018.util.SocketUtils;

/**
 * Created by Quentin on 27/02/2018.
 */

final class ListeningThread extends Thread{
    ArrayList<Student> students = new ArrayList<>();
    private static final String TAG = ListeningThread.class.getSimpleName();

    private final ServerActivity serverActivity;

    private BluetoothServerSocket serverSocket;

    ListeningThread(ServerActivity serverActivity,ArrayList<Student> students) {
        this.serverActivity = serverActivity;
        this.students = students;
    }

    void startListening(BluetoothServerSocket serverSocket) {

        this.serverSocket = serverSocket;
        start();
    }

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

    private void processRequest(BluetoothSocket socket) {
        final Thread processingThread = new ProcessingThread(socket, serverActivity,students);
        processingThread.start();
    }

    void stopListening() {
        final BluetoothServerSocket temp = serverSocket;
        serverSocket = null;

        SocketUtils.closeSilently(temp);
    }
}
