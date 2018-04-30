package upec.projetandroid2017_2018.server;

/**
 * Created by Quentin on 27/02/2018.
 */

public final class RemoteResponse {
    private static final String DEFAULT_ERROR_MESSAGE = "UNKNOWN ERROR";

    private final boolean success;
    private final String data;

    public RemoteResponse(boolean success, String data) {
        this.success = success;
        this.data = data;
    }

    public boolean success() {
        return success;
    }

    public String getContent() {
        return (success ? data : null);
    }

    public String getErrorMessage() {
        if (success) {
            return null;
        }

        return (data == null ? DEFAULT_ERROR_MESSAGE : data);
    }
}
