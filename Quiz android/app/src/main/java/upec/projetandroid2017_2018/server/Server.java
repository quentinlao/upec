package upec.projetandroid2017_2018.server;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothServerSocket;
import android.util.Log;

import java.io.IOException;
import java.util.ArrayList;

import upec.projetandroid2017_2018.util.Constante;
import upec.projetandroid2017_2018.util.BluetoothUtils;

/**
 * Created by Quentin on 27/02/2018.
 */

public class Server {
    private static final String TAG = Server.class.getSimpleName();

    private ListeningThread listeningThread;

    private ArrayList<Student> students = new ArrayList<>();
    Server(ArrayList<Student> students) {
        this.students= students;
    }


    public boolean init(ServerActivity serverActivity) {
        final BluetoothServerSocket serverSocket = buildServerSocket();
        if (serverSocket == null) {
            return false;
        }

        listeningThread = new ListeningThread(serverActivity,students);
        listeningThread.startListening(serverSocket);

        return true;
    }

    private BluetoothServerSocket buildServerSocket() {
        final BluetoothAdapter adapter = BluetoothUtils.getBluetoothAdapter();
        if (adapter == null) {
            return null;
        }

        try {
            return adapter.listenUsingRfcommWithServiceRecord(Constante.NAME, Constante.CPUUID);
        } catch (IOException ioe) {
            return null;
        }
    }


    public void close() {
        if (listeningThread != null) {
            listeningThread.stopListening();
            listeningThread = null;
        }
    }


}
