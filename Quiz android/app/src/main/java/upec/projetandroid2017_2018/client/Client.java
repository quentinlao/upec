package upec.projetandroid2017_2018.client;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;

import java.util.Collection;

import upec.projetandroid2017_2018.util.BluetoothUtils;

/**
 * Created by Quentin on 27/02/2018.
 */

public class Client {
    private BluetoothDevice server;
    private BluetoothAdapter adapter;
    ClientActivity clientActivity;

    public Client() {}

    /* AFFICHE LA LISTE DES SERVEURS */

    public Collection<CharSequence> listServers() {
        if (init()) {
            return BluetoothUtils.getPairedDeviceNames(adapter);
        } else {
            return null;
        }
    }

    /* VERIFIE SI LE SERVEUR EXISTE EN */

    public boolean connect(String bluetoothServerDeviceName) {
        if (init()) {
            server = BluetoothUtils.findPairedDeviceByName(adapter, bluetoothServerDeviceName);
            return (server != null);
        } else {
            return false;
        }
    }

    /* RETOURNE SON APPAREIL */
    private boolean init() {
        if (adapter == null) {
            adapter = BluetoothUtils.getBluetoothAdapter();
        }
        return (adapter != null);
    }

    /* DEMARRE LA REQUETE (CLIENT) */
    public void send(String request,String clientName, ClientActivity clientActivity) {
        this.clientActivity = clientActivity;
        if (adapter != null && server != null) {
            final Request requestThread = new Request(adapter, server);
            requestThread.sendRequest(request,clientName, clientActivity);

        }
    }


}
