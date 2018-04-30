package upec.projetandroid2017_2018.server;

import java.util.ArrayList;

/**
 * Created by Quentin on 27/02/2018.
 */

public class ServerFactory  {
    public static Server instance(ArrayList<Student> students) {
        return new Server(students);
    }

    public ServerFactory() {}

}
