package upec.projetandroid2017_2018.conf;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothServerSocket;
import android.bluetooth.BluetoothSocket;

import java.io.IOException;
import java.util.UUID;

/**
 * Created by Kevin on 25/03/2018.
 */

public class ServerConnectThread extends Thread{

    private BluetoothSocket btSocket;

    public ServerConnectThread() {}

    public void acceptConnect (BluetoothAdapter btAdapter, UUID mUUID) {
        BluetoothServerSocket temp = null;

        try {
            temp = btAdapter.listenUsingInsecureRfcommWithServiceRecord("BTConnect", mUUID);
        } catch (IOException e) {
            e.printStackTrace();
        }
        while(true) {
            try {
                btSocket = temp.accept();
            } catch (IOException e) {
                e.printStackTrace();
                break;
            }
            if(btSocket != null) {
                try {
                temp.close();
            } catch (IOException a) {

            }
            break;
        }
    }
}}
