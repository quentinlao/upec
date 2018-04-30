package upec.projetandroid2017_2018.server;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.bluetooth.BluetoothSocket;
import android.util.Log;

import java.io.IOException;
import java.util.ArrayList;

import upec.projetandroid2017_2018.util.Constante;
import upec.projetandroid2017_2018.util.BluetoothUtils;
import upec.projetandroid2017_2018.util.SocketUtils;

/**
 * Created by Quentin on 27/02/2018.
 */
/* EXECUTE LA REQUETE OBTENU AVEC L'ECOUTE */
public final class ProcessingThread extends Thread{
    private static final String TAG = ProcessingThread.class.getSimpleName();

    private final BluetoothSocket socket;
    private final ServerActivity serverActivity;
    ArrayList<Student> students = new ArrayList<>();

    private final byte[] buffer = new byte[Constante.BUFFER_SIZE];

    public ProcessingThread(BluetoothSocket socket, ServerActivity serverActivity,ArrayList<Student> students) {
        this.socket = socket;
        this.serverActivity = serverActivity;
        this.students = students;
    }
    boolean existStudent(String name){
        for(Student s  : students){
            if(s.getPseudo().equals(name))return false;
        }
        return true;

    }
    @Override
    public void run() {
        String request,clientName, response;

        try {
            request = SocketUtils.readString(socket.getInputStream(), buffer);
            clientName = readPlus(request);

            BluetoothAdapter blueAdapter = BluetoothAdapter.getDefaultAdapter();
            BluetoothDevice client = BluetoothUtils.findPairedDeviceByName(blueAdapter,clientName);
            if(existStudent(clientName))
            {
                students.add(new Student(client,clientName));
            }
            blueAdapter.cancelDiscovery();



            System.out.println("salut toi : "+clientName);
            response = getResponse(request,students);

        } catch (IOException ioe) {
            response = (Constante.ERROR_RESPONSE_START + ioe.getClass() + ": " + ioe.getMessage());
        }

        sendResponse("Message par defaut", socket);
    }
    String readPlus(String request){
        String str = request;
        StringBuilder clientName = new StringBuilder();
        boolean state = false;
        for (int i=0; i < str.length(); i++) {
            if (state) clientName.append(str.charAt(i));
            if (str.charAt(i) == '+') {
                i++;
                clientName.append(str.charAt(i));
                state = true;
            }
        }
        return clientName.toString();

    }
    private String getResponse(String request,ArrayList<Student> students) {
        try {
            final String response = serverActivity.process(request,students);
            return (response == null ? Constante.NULL_RESPONSE : response);
        } catch (Exception e) {
            return (e.getClass().getName() + ": " + e.getMessage());
        }
    }

    private void sendResponse(String response, BluetoothSocket socket) {
        try {


            SocketUtils.writeString(response, socket.getOutputStream());
        } catch (IOException ioe) {
            ioe.printStackTrace();
        }
    }
}
