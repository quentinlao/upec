package upec.projetandroid2017_2018.util;

import java.util.UUID;

/**
 * Created by Quentin on 27/02/2018.
 */

public class Constante {
    public static final String NAME = "Quizz";
    public static final UUID CPUUID = UUID.fromString("ca7d1817-efaf-444e-8f46-4261d5a30b3f");
    public static final UUID PCUUID = UUID.fromString("00001101-0000-1000-8000-00805F9B34FB");
    public static final String NULL_RESPONSE = "__bluetooth.demo.null.response.code.123418052017__";
    public static final String ERROR_RESPONSE_START = "__bluetooth.demo.error.response.start.145118052017__>";

    public static final int BUFFER_SIZE = 4*1024;
    public static final long RESPONSE_READ_DELAY_MILLIS = 250;

    private Constante() {}
}
