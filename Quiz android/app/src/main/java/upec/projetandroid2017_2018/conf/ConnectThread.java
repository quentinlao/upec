package upec.projetandroid2017_2018.conf;

import android.bluetooth.BluetoothDevice;
import android.bluetooth.BluetoothSocket;

import java.io.IOException;
import java.util.UUID;

/**
 * Created by Kevin on 25/03/2018.
 */

public class ConnectThread extends Thread {

    private BluetoothSocket btSocket;

    private boolean connect (BluetoothDevice btDevice, UUID mUUID) {
        BluetoothSocket temp = null;
        try {
            temp = btDevice.createRfcommSocketToServiceRecord(mUUID);
        } catch (IOException io) {
            io.printStackTrace();
            return false;
        }
        try {
            btSocket.connect();
        } catch (IOException io) {
            io.printStackTrace();
            try {
                btSocket.close();
            } catch(IOException e) {
                e.printStackTrace();
                return false;
            }

        }
        return true;
    }

    public boolean cancel () {
        try {
            btSocket.close();
        } catch (IOException io) {
            io.printStackTrace();
            return false;
        }
        return true;
    }
}
