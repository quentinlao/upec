package upec.projetandroid2017_2018;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;

import upec.projetandroid2017_2018.client.ClientActivity;
import upec.projetandroid2017_2018.server.ServerActivity;

/**
 * Created by Quentin on 16/02/2018.
 */

public class PlayActivity extends AppCompatActivity{
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_play);

    }

    public void onClient(View view) {
        startActivity(new Intent(this, ClientActivity.class));
    }

    public void onServer(View view) {
        startActivity(new Intent(this, ServerActivity.class));
    }
}
