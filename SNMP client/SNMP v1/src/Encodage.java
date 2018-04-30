
import java.net.InetAddress;
import java.net.SocketException;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.Arrays;

public class Encodage  {
	
	int tailleCommunaute;
	byte[] communaute;
	byte[] myoid;
	private InetAddress ipAdresse;

	public Encodage(String ip, String comm, String oid) throws SocketException{

	try {
		InetAddress ipAdresse = InetAddress.getByName(ip);
		this.ipAdresse = ipAdresse;
	} catch (UnknownHostException e) {
	
		e.printStackTrace();
	}//192.168.0.18
		
		int tailleCommunaute = comm.length();
		this.tailleCommunaute = tailleCommunaute;
		byte[] communaute = comm.getBytes();
		this.communaute = communaute;
		
		System.out.println("LA COMMUNAUTE ");
		for (int i = 0; i < communaute.length; i++) {
			System.out.print(String.format("0x%02X", communaute[i])+" ");
		}
		System.out.println("\nMon OID");
		byte[] myoid = convOid(oid);
		/*for (int i = 0; i < myoid.length; i++) {
			System.out.print(String.format("%02X ", myoid[i]));
		}*/
		this.myoid = myoid;
		System.out.println();
		
	
	
	}
	public void send(Fenetre f) throws SocketException{
		Request requestThread = new Request(ipAdresse,packetEncode(),f);
		/*for(int i = 0; i < packetEncode().length;i++){

			System.out.print(String.format("0x%02X", packetEncode()[i])+" ");
		}*/
		requestThread.sendRequest();
	}
	
	public InetAddress getIp(){
		return ipAdresse;
	}
	public byte[] packetEncode() throws SocketException{
		
		
		byte header[] = {(byte)0x30, 
				
				(byte)(tailleCommunaute+myoid.length+22), //taille du header
				
                (byte)0x02, (byte)0x01, (byte)0x01,
                
                (byte) 0x04,
                
                (byte)(tailleCommunaute)};
		byte[] packEncode = concat(header,communaute);
                
                // avant avec les valeurs (byte)0x70,(byte)0x75,(byte)0x62,(byte)0x6C,(byte)0x69,(byte)0x63,
         byte[] header2 = {       
                (byte)0xa0,
                
                (byte)(myoid.length ), //donnee
                
                (byte)0x02,(byte)0x01,(byte)0x01,
                (byte)0x02,(byte)0x01,(byte)0x00,
                (byte)0x02,(byte)0x01,(byte)0x00,
                (byte)0x30,
                
                (byte)(myoid.length), 
                
                (byte)0x30, 
                (byte)(myoid.length )
         };
         packEncode = concat(packEncode,header2,myoid);

         
                /*(byte)0x06,(byte)0x08,(byte)0x2B, (byte)0x06,
				(byte)0x01,(byte)0x02,
				(byte)0x01,(byte)0x01,
				(byte)0x05,(byte)0x00,*/
         byte[] header3 = {
				(byte)0x05,(byte)0x00
		};
         packEncode = concat(packEncode,header3);
         return packEncode;
	}
	static String avanceCurseur(String str) {
		if(str.length() > 1) {
		StringBuilder txt = new StringBuilder();
		for(int i = 1; i < str.toCharArray().length;i++) {
			txt.append(str.toCharArray()[i]);
		}
		
		return txt.toString();
		}
		else
			return str;
	}
	
	static int atoi(String str) {
		if (str == null || str.length() < 1)
			return 0;
	 
		str = str.trim();
	 
		char flag = '+';
	 
		int i = 0;
		if (str.charAt(0) == '-') {
			flag = '-';
			i++;
		} else if (str.charAt(0) == '+') {
			i++;
		}
		
		double result = 0;
	 
		while (str.length() > i && str.charAt(i) >= '0' && str.charAt(i) <= '9') {
			result = result * 10 + (str.charAt(i) - '0');
			i++;
		}
	 
		if (flag == '-')
			result = -result;
	 
		if (result > Integer.MAX_VALUE)
			return Integer.MAX_VALUE;
	 
		if (result < Integer.MIN_VALUE)
			return Integer.MIN_VALUE;
	 
		return (int) result;
	}
	
	 void MakeBase128(  long l, boolean first )
	{
		if( l > 127 )
		{
			MakeBase128( l / 128, false );
		}
		l %= 128;
		if( first )
		{

			abBinary[nBinary++] = ( char)l;

		}
		else
		{
			abBinary[nBinary++] = (char) (0x80 | ( char)l);
		}
	}
	
	static char	abBinary[]=new char[128];

	  int	nBinary = 0;
	
	 public byte[] convOid(String myoid) {
		int nMode = 0;	
		boolean nCHex = false;
		String abCommandLine =  myoid;
		int point = 0;
		while( nMode == 0 )	
		{
			String p = abCommandLine;
			 char cl = 0x00;
			String q = null;
			int nPieces = 1;
			int n = 0;
			 char b = 0;
			 int nn = 0;
			 long l = 0;
			 int compteur = 0;
			 while( compteur < p.length() )
				{
					if( p.charAt(compteur) == '.' )
					{
						nPieces++;
					}
					compteur++;
				}
			 point = nPieces;
			 n = 0;
			 b = 0;
			 p = abCommandLine;
			 while( n < nPieces )
				{
				 q = p;

				 int toto = 0;
				 while( toto  < p.length())
					{
						if( p.charAt(0) == '.' )
						{

							break;
						}
						p = avanceCurseur(p);
						toto++;
					}
				 l = 0;
					if( p.charAt(0) == '.')
					{
						
						l = ( long) atoi(q);
						q = avanceCurseur(p);
						p = q;
					}
					else
					{
						l = ( long) atoi( q );
						q = p;
					}
					if( n == 0 )
					{
						b = (char) (40 * (( char)l));
					}
					else if( n == 1 )
					{	
						b += (( char) l);

						abBinary[nBinary++] = b;
						
					}
					else
					{
						MakeBase128( l, true );
					}
				 n++;
				}
			 ArrayList<Byte> list = new ArrayList<>();
			 	list.add((byte)(cl | 6));
			 	list.add((byte)(nBinary));
				//System.out.print(String.format("%02X %02X ", cl | 6, nBinary));
			 
				for(int i =0;i<point-1;i++) {
					list.add((byte) abBinary[i]);
					//System.out.print(String.format("%02X ", (int)abBinary[i]));
				}
				
				byte[] myOid = new byte[list.size()];
				for (int i = 0; i < myOid.length; i++) {
					myOid[i] = list.get(i);
				}
				
				return myOid;
			 
		}
		return null;
	}
	 static public byte[] concat(byte[]... bufs) {
		    if (bufs.length == 0)
		        return null;
		    if (bufs.length == 1)
		        return bufs[0];
		    for (int i = 0; i < bufs.length - 1; i++) {
		        byte[] res = Arrays.copyOf(bufs[i], bufs[i].length+bufs[i + 1].length);
		        System.arraycopy(bufs[i + 1], 0, res, bufs[i].length, bufs[i + 1].length);
		        bufs[i + 1] = res;
		    }
		    return bufs[bufs.length - 1];
		}
}
