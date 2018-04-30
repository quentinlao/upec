package upec.projetandroid2017_2018.conf;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.Set;

/**
 * Created by Quentin on 16/02/2018.
 */

public class BluetoothConf {
    Context t;
    TextView t1,t2;

    BluetoothAdapter bluetoothAdapter = null;
    Set<BluetoothDevice> devices;


    public BluetoothConf(Context t, TextView t1, TextView t2){
        this.t = t;
        this.t1 = t1;
        this.t2 = t2;
        bluetoothAdapter = BluetoothAdapter.getDefaultAdapter();
        bluetoothDispo();
        devices = bluetoothAdapter.getBondedDevices();
    }
    void bluetoothDispo(){
        if (bluetoothAdapter == null)
            Toast.makeText(t, "Pas de Bluetooth",
                    Toast.LENGTH_SHORT).show();
        else
            Toast.makeText(t, "Avec Bluetooth",
                    Toast.LENGTH_SHORT).show();
    }
    public void showPaired(){
     StringBuilder str = new StringBuilder();
        for (BluetoothDevice blueDevice : devices) {
            str.append("Device = " + blueDevice.getName() + "\n");

        }
            t1.setText(str.toString());
    }
    public void showMe(){
        Intent discoverableIntent = new Intent(BluetoothAdapter.ACTION_REQUEST_DISCOVERABLE);
        discoverableIntent.putExtra(BluetoothAdapter.EXTRA_DISCOVERABLE_DURATION, 300);
        t.startActivity(discoverableIntent);
    }


    public void showDispo(){
        IntentFilter filter = new IntentFilter(BluetoothDevice.ACTION_FOUND);
        t.registerReceiver(mReceiver, filter);
        bluetoothAdapter.startDiscovery();
        StringBuilder str = new StringBuilder();
        for (String s: mArrayAdapter) {
            str.append(s + "\n");

        }
        t2.setText(str.toString());

    }
    ArrayList<String> mArrayAdapter = new ArrayList<>();
    private final BroadcastReceiver mReceiver = new BroadcastReceiver() {
        public void onReceive(Context context, Intent intent) {
            String action = intent.getAction();
            if (BluetoothDevice.ACTION_FOUND.equals(action)) {

                BluetoothDevice device = intent.getParcelableExtra(BluetoothDevice.EXTRA_DEVICE);
                String deviceName = device.getName();
                String deviceHardwareAddress = device.getAddress(); // MAC address
                mArrayAdapter.add(deviceName + " | " + deviceHardwareAddress);

            }
        }
    };



    public BluetoothAdapter getBlueAdapter(){
        return bluetoothAdapter;
    }

}
