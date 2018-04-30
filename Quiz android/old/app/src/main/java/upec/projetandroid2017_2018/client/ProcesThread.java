package upec.projetandroid2017_2018.client;

import android.bluetooth.BluetoothSocket;

import java.io.IOException;

import upec.projetandroid2017_2018.util.Constante;
import upec.projetandroid2017_2018.util.SocketUtils;

/**
 * Created by Quentin on 09/03/2018.
 */

public class ProcesThread  extends Thread{
    private static final String TAG = ProcesThread.class.getSimpleName();

    private final BluetoothSocket socket;
    private final ClientActivity clientActivity;

    private final byte[] buffer = new byte[Constante.BUFFER_SIZE];

    public ProcesThread(BluetoothSocket socket, ClientActivity clientActivity) {
        this.socket = socket;
        this.clientActivity = clientActivity;
    }
    /*
    RECUPERE L'INFORMATION INPUTSTREAM DU SOCKET
     */
    @Override
    public void run() {
        String request, response;

        try {
            request = SocketUtils.readString(socket.getInputStream(), buffer);

            response = getResponse(request);

        } catch (IOException ioe) {

            response = ( ioe.getMessage());
        }

    }
    /*
    SEUL MOYEN DE MODIF LE CHAMPS EN ENVOYANT A l'ACTIVITE QUI LUI CHANGE LE CHAMPS
     */
    private String getResponse(String request) {
        try {
            final String response = clientActivity.process(request);
            return response;
        } catch (Exception e) {
            return (e.getClass().getName() + ": " + e.getMessage());
        }
    }

}
