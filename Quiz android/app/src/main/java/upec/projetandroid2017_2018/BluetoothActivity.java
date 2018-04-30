package upec.projetandroid2017_2018;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import upec.projetandroid2017_2018.R;
import upec.projetandroid2017_2018.conf.BluetoothConf;

/**
 * Created by Quentin on 16/02/2018.
 */

public class BluetoothActivity extends AppCompatActivity  {
    private TextView paired, dispo;
    private final static int REQUEST_CODE_ENABLE_BLUETOOTH = 0;

    private final BroadcastReceiver mReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            String action = intent.getAction();
            if(BluetoothDevice.ACTION_FOUND.equals(action)) {
                BluetoothDevice device = intent.getParcelableExtra(BluetoothDevice.EXTRA_DEVICE);
                String deviceName = device.getName();
                String deviceAdress = device.getAddress();
            }
        }
    };

    BluetoothConf bluetoothConf = null;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        IntentFilter filter = new IntentFilter(BluetoothDevice.ACTION_FOUND);
        registerReceiver(mReceiver, filter);
        setContentView(R.layout.activity_configure);
        paired = (TextView) findViewById(R.id.paired);
        dispo = (TextView) findViewById(R.id.dispo);
         bluetoothConf = new BluetoothConf(this, paired, dispo);
        activateBlue(bluetoothConf.getBlueAdapter());
        gestionButton();
    }
    /*
    *Activation du bluetooth directement si possible
     */
    void activateBlue(BluetoothAdapter bluetoothAdapter){
        if (!bluetoothAdapter.isEnabled()) {
            Intent enableBlueTooth = new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE);
            startActivityForResult(enableBlueTooth, REQUEST_CODE_ENABLE_BLUETOOTH);
        }
    }
    void gestionButton(){
        Button b = (Button) findViewById(R.id.buttonPaired);
        b.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
              bluetoothConf.showPaired();
            }
        });
        Button b1 = (Button) findViewById(R.id.discov);
        b1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                bluetoothConf.showMe();
            }
        });
        Button b2 = (Button) findViewById(R.id.buttonDispo);
        b2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                bluetoothConf.showDispo();
            }
        });
    }

}
