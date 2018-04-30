package upec.projetandroid2017_2018.util;

import android.util.Log;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.Closeable;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.Reader;

/**
 * Created by Quentin on 27/02/2018.
 */

public class SocketUtils {
    private static final byte EOT = 4; // CTRL+D
    private static final int EOF_INT = -1; //DEPENDANT DU OS -1 defaut
    private static final String TAG = SocketUtils.class.getSimpleName();



    public static String readString(InputStream is, byte[] buffer) throws IOException {
        final StringBuilder sb = new StringBuilder();
        readString(is, buffer, sb);
        return sb.toString();
    }

    private static void readString(InputStream is, byte[] buffer, StringBuilder sb) throws IOException {
        ReadResult readResult = new ReadResult();
        while (readResult.moreToRead) {
            readResult = fillBuffer(is, buffer);
            if (readResult.nRead > 0) {
                sb.append(new String(buffer, 0, readResult.nRead));
            }
        }
    }

    private static ReadResult fillBuffer(InputStream is, byte[] buffer) throws IOException {
        int totalRead = 0;
        int nReadInStep = 0;
        boolean eotRead = false;
        boolean spaceLeftInBuffer = true;

        while (!eotRead && spaceLeftInBuffer && nReadInStep != EOF_INT) {
            nReadInStep = is.read(buffer, totalRead, buffer.length - totalRead);
            if (nReadInStep > 0) {
                totalRead += nReadInStep;
            }

            spaceLeftInBuffer = (totalRead < buffer.length);
            eotRead = (buffer[totalRead - 1] == EOT);
        }

        if (eotRead) {
            totalRead--; // ON ENLEVE LE DERNIER ELEM EOF
        }

        final boolean moreToRead = (!eotRead && nReadInStep != EOF_INT);
        return new ReadResult(totalRead, moreToRead);
    }

    public static final void writeString(String s, OutputStream os) throws IOException {
        os.write(s.getBytes());

        os.write(EOT); //FIN DU FICHIER
        os.flush();
    }

    public static void closeSilently(Closeable closeable) {
        if (closeable != null) {
            try {
                closeable.close();
            } catch (IOException ioe) {
                Log.e(TAG, "I/O error when closing resource.", ioe);
            }
        }
    }

    private SocketUtils() {}

    private static final class ReadResult {
        final int nRead;
        final boolean moreToRead;

        ReadResult() {
            this(0, true);
    }

        ReadResult(int nRead, boolean moreToRead) {
            this.nRead = nRead;
            this.moreToRead = moreToRead;
        }
    }
}
