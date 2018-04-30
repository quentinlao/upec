package upec.projetandroid2017_2018.client;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.bluetooth.BluetoothSocket;
import android.util.Log;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

import upec.projetandroid2017_2018.server.RemoteResponse;
import upec.projetandroid2017_2018.util.Constante;
import upec.projetandroid2017_2018.util.SocketUtils;

/**
 * Created by Quentin on 27/02/2018.
 */

final class Request extends Thread {
    private static final String TAG = Request.class.getSimpleName();

    private final BluetoothAdapter adapter;
    private final BluetoothDevice server;

    private BluetoothSocket socket;
    private InputStream inputStream;
    private OutputStream outputStream;

    private String request;
    private String clientName;
    private ClientActivity clientActivity;

    private final byte[] buffer = new byte[Constante.BUFFER_SIZE];

    public Request(BluetoothAdapter adapter, BluetoothDevice server) {
        this.adapter = adapter;
        this.server = server;
    }

    void sendRequest(String request,String clientName, ClientActivity clientActivity) {
        this.request = request;
        this.clientName = clientName;
        this.clientActivity = clientActivity;
        socket = buildSocket();

        start();
    }

    private BluetoothSocket buildSocket() {
        try {

            return server.createRfcommSocketToServiceRecord(Constante.CPUUID);
        } catch (IOException ioe) {
            return null;
        }
    }

    @Override
    public void run() {
        try {
            adapter.cancelDiscovery();
            sendRequestAndReadResponse();
        } finally {
            close();
        }
    }
    /* ENVOIE LE MSG ET RECUPERE L'INFORMATION EN RETOUR DU MSG RECU */
    private void sendRequestAndReadResponse() {
        if (connect()) {
            try {
                request = request + "+" + clientName;
                SocketUtils.writeString(request, outputStream);
            } catch (IOException ioe) {
                logErrorAndRespond("I/O error ", ioe);
                return;
            }

            waitForResponse(Constante.RESPONSE_READ_DELAY_MILLIS);

            try {

                final String response = SocketUtils.readString(inputStream, buffer);
                final RemoteResponse remoteResponse = parseResponse(response);
                if (clientActivity != null) {
                    clientActivity.responseArrived(remoteResponse);
                }
            } catch (IOException ioe) {
                logErrorAndRespond("I/O error ", ioe);
        }
        } else {
            errorResponseCallback("Bluetooth FAIL");
        }
    }
    /* CONNEXION ET RECUPERATION/ENVOIE IO  */
    private boolean connect() {
        if (socket == null) {
            return false;
        }
        try {

            socket.connect();
            if (socket.isConnected()) {
                inputStream = socket.getInputStream();
                outputStream = socket.getOutputStream();
                return true;
            } else {
                 return false;
            }
        } catch (IOException ioe) {
             return false;
        }
    }

    private void waitForResponse(long millis) {
        try {
            sleep(millis);
        } catch (InterruptedException e) {}
    }

    private RemoteResponse parseResponse(String response) {
        if (response == null) {
            return new RemoteResponse(false, "Null response error.");
        } else if (Constante.NULL_RESPONSE.equals(response)) {
            return new RemoteResponse(true, null);
        } else if (response.contains(Constante.ERROR_RESPONSE_START)) {
            return parseErrorResponse(response);
        } else {
            return new RemoteResponse(true, response);
        }
    }

    private RemoteResponse parseErrorResponse(String response) {
        final int len = Constante.ERROR_RESPONSE_START.length();
        if (response.length() > len) {
            return new RemoteResponse(false, response.substring(len));
        } else {
            return new RemoteResponse(false, null);
        }
    }

    private void logErrorAndRespond(String errorMessage, Exception e) {
        Log.e(TAG, errorMessage, e);
        errorResponseCallback(errorMessage, e);
    }

    private void errorResponseCallback(String errorMessage) {
        errorResponseCallback(errorMessage, null);
    }

    private void errorResponseCallback(String errorMessage, Exception e) {
        if (clientActivity != null) {
            final String exceptionMessage = (e == null ? "" : (e.getClass() + ": " + e.getMessage()));
            final String fullErrorMessage = (errorMessage + ". " + exceptionMessage);
            final RemoteResponse errorResponse = new RemoteResponse(false, fullErrorMessage);
            clientActivity.responseArrived(errorResponse);
        }
    }

    private void close() {
        request = null;
        clientActivity = null;

        SocketUtils.closeSilently(inputStream);
        SocketUtils.closeSilently(outputStream);
        SocketUtils.closeSilently(socket);

        inputStream = null;
        outputStream = null;
        socket = null;
    }
}
