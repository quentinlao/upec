import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.net.SocketException;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JSpinner;
import javax.swing.SpinnerListModel;
import javax.swing.SpinnerModel;
import javax.swing.SwingConstants;
public class Fenetre extends JFrame {
	JLabel txt ;
	  public Fenetre(){
		    this.setTitle("SNMP CLIENT");
		    this.setSize(500, 500);
		    this.setLocationRelativeTo(null);
		    this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);      
		    
		    final JLabel txtIP = new JLabel("IP :");
		    txtIP.setBounds(50,5,50,50);    
		    this.add(txtIP);
		    SpinnerModel ipSP =
		            new SpinnerListModel(new String[]{"localhost","198.168.0.18"});        
			 JSpinner choixIP = new JSpinner(ipSP);   
			 choixIP.setBounds(150,20,150,30);    
			 this.add(choixIP);
			 
			 
		    final JLabel txtCom = new JLabel("Communaute :");
		    txtCom.setBounds(50,50,100,50);    
		    this.add(txtCom);
		    SpinnerModel commSp =
		            new SpinnerListModel(new String[]{"public","private"});        
			 JSpinner choixCom = new JSpinner(commSp);   
			 choixCom.setBounds(150,60,150,30);    
			 this.add(choixCom);
		    
		    final JLabel txt = new JLabel("resultat",SwingConstants.CENTER);
		    txt.setBounds(0,100,400,300);    
		    this.add(txt);
		    this.txt = txt;
		    
		    final JLabel txtOid = new JLabel("Oid :");
		    txtOid.setBounds(50,90,50,50);    
		    this.add(txtOid);
		    SpinnerModel model =
		            new SpinnerListModel(new String[]{"1.3.6.1.2.1.1.5.0","1.3.6.1.2.1.1.6.0","1.3.6.1.2.1.2.2.1.2.1","1.3.6.1.2.1.2.2.1.2.2","1.3.6.1.2.1.2.2.1.2.3","1.3.6.1.2.1.2.2.1.2.4","1.3.6.1.2.1.2.2.1.2.5","1.3.6.1.2.1.2.2.1.2.6","1.3.6.1.2.1.2.2.1.2.7",
		            		"1.3.6.1.2.1.2.2.1.2.8","1.3.6.1.2.1.2.2.1.2.9","1.3.6.1.2.1.2.2.1.2.10","1.3.6.1.2.1.2.2.1.2.24"});        
			 JSpinner spinner = new JSpinner(model);   
			  spinner.setBounds(150,100,150,30);    
			 this.add(spinner);
			 
			 JButton b=new JButton("Envoyer");  
			    b.setBounds(100,150,150,30);  
			   
			    b.addActionListener(new ActionListener(){  
			    	public void actionPerformed(ActionEvent e){  
				    		Encodage encode;
							try {

								String Ip =  (String) choixIP.getValue();
								String oid =  (String) spinner.getValue();
								String commu =  (String) choixCom.getValue();
								encode = new Encodage(Ip,commu,oid);
								encode.send(Fenetre.this);
							} catch (SocketException e2) {
								// TODO Auto-generated catch block
								e2.printStackTrace();
							}
				            	
				        	
				    		
			    		
			    	        }  
			    	    });  
			    this.add(b);  
			    
			    
			    final JLabel expli = new JLabel("<html>Comment avoir un serveur snmp :<br/> https://www.loriotpro.com/ServiceAndSupport/How_to/InstallWXPAgent_FR.php </html>");
			    expli.setBounds(0,400,500,50);    
			    this.add(expli);
			    
			 this.setLayout(null);    
	         
		    this.setVisible(true);
		   
		  }
	  void setTxt(String str){

			txt.setText(str);
		  
	  }
}
