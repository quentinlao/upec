import java.io.ByteArrayOutputStream;
import java.io.UnsupportedEncodingException;
import java.net.DatagramPacket;


public class Decodage {
	DatagramPacket packet;
	public Decodage(DatagramPacket packet ) {
		this.packet = packet;
	}
	public String information() {
		byte[] info = packet.getData();
		StringBuilder str = new StringBuilder();
		str.append("<html>");
		str.append("Adresse IP : "+packet.getAddress() + "\n");
		str.append("Port : " + packet.getPort() + "\n");
		str.append("Version : ");
		if ( info[4] == 0x00) str.append("1\n");
		else if(info[4] == 0x01) str.append("2c\n");
		str.append("Nom de la communaute : ");
		byte[] myCom = new byte[(int) info[6]];
		for (int i = 0; i < (int) info[6]; i++) {
			myCom[i] = info[i + 7];
		}
		try {
			str.append((new String(myCom, "UTF-8")) + "\n");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		} 
		ByteArrayOutputStream in = new ByteArrayOutputStream();
		int commLength = (int) info[6];
		int oidLength =  (int) info[23 + commLength];
		int valueNull = 2;
		in.write(info,commLength + 26 + oidLength,
				commLength + 25 + oidLength +valueNull);
		String msg = null;
		try {
			 msg = new String(in.toByteArray(), "UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		str.append("Message : " + msg);
		str.append("</html>");
		

		return str.toString().replaceAll("\n","<br/>");
	}
}
