package upec.projetandroid2017_2018.client;

/**
 * Created by Quentin on 27/02/2018.
 */

public class ClientFactory  {
    public static Client instance() {
        return  new Client();
    }

    private ClientFactory() {}


}
