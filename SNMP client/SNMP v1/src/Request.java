import java.io.IOException;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.InetAddress;
import java.net.SocketException;

public class Request extends Thread{

	private InetAddress ipAdresse;
	private DatagramSocket socket;
	byte[] packetEncode;
	private Fenetre f;
	byte[] buff = new byte[512];
	private DatagramPacket monPacket= new DatagramPacket(buff, buff.length);
	
	
	public Request(InetAddress ipAdresse,byte[] packetEncode,Fenetre f){
		this.ipAdresse = ipAdresse;
		this.packetEncode =packetEncode;
		this.f = f;
	}
	public DatagramPacket getPack(){
		return monPacket;
	}
	public void sendRequest(){
		start();
	}
	private DatagramSocket buildSocket(){
		try{
			DatagramSocket socket = new DatagramSocket();
			return socket;
		}catch (IOException ioe){
			ioe.printStackTrace();
			return null;
		}
	}
	@Override
	public void run(){

		socket = buildSocket();
		try{
			sendPacketAndRead();
		}finally{
			close();
		}
	}
	private void sendPacketAndRead(){
		socket.connect(ipAdresse, 161);
		try {
			socket.send(new DatagramPacket(packetEncode,packetEncode.length, ipAdresse, 161));
		} catch (SocketException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		
		waitForResp(250);
		
		try {
			
			System.out.println("PAS ENCORE");
			socket.setSoTimeout(5000);
			socket.receive(monPacket);
			 try {
					Thread.sleep(250);
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				DatagramPacket packEntrant = monPacket;
				/*byte[] test2 = packEntrant.getData();
				
				for (int i = 0; i < test2.length; i++) {
					System.out.print(String.format("0x%02X", test2[i])+" ");
				}*/

				Decodage decode = new Decodage(packEntrant);
				System.out.println(decode.information());
				f.setTxt(decode.information());

			
		} catch (IOException e) {
			f.setTxt("MAUVAISE INFO");

			e.printStackTrace();
		}
		System.out.println("FINI");
	}
	public void waitForResp(long millis) {
        try {
            sleep(millis);
        } catch (InterruptedException e) {}
    }

	private void close(){
		socket.close();
		socket = null;
	}
}
