package controller;

import twitter4j.Twitter;
import org.json.JSONArray;
import org.json.JSONObject;
import org.json.JSONException;
import twitter4j.DirectMessage;
import twitter4j.TwitterFactory;
import twitter4j.TwitterException;
import twitter4j.conf.ConfigurationBuilder;

/**
 * Questo oggetto permette l'interazione ad alto livello con le API messe a disposizione da twitter,
 * i suoi metodi permettono di gestire tutte le necessità che il progetto presenta 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public class TwitterInteractor {
	private static String consumerKey;
	private static String consumerSecret;
	private static String accessToken;
	private static String accessTokenSecret;
	private String contactName;
	private Twitter twitterMan;
	public TwitterInteractor(String consumerKey,String consumerSecret,String accessToken,String accessTokenSecret,String contactName) {
		TwitterInteractor.consumerKey = consumerKey;
		TwitterInteractor.consumerSecret = consumerSecret;
		TwitterInteractor.accessToken = accessToken;
		TwitterInteractor.accessTokenSecret = accessTokenSecret;
		this.contactName = contactName;
		ConfigurationBuilder cb = new ConfigurationBuilder();
		cb.setDebugEnabled(true)
		  .setOAuthConsumerKey(TwitterInteractor.consumerKey)
		  .setOAuthConsumerSecret(TwitterInteractor.consumerSecret)
		  .setOAuthAccessToken(TwitterInteractor.accessToken)
		  .setOAuthAccessTokenSecret(TwitterInteractor.accessTokenSecret);
		TwitterFactory tf = new TwitterFactory(cb.build());
		this.twitterMan = tf.getInstance();
	}
	public static String getConsumerKey() {
		return consumerKey;
	}
	public static void setConsumerKey(String consumerKey) {
		TwitterInteractor.consumerKey = consumerKey;
	}
	public static String getConsumerSecret() {
		return consumerSecret;
	}
	public static void setConsumerSecret(String consumerSecret) {
		TwitterInteractor.consumerSecret = consumerSecret;
	}
	public static String getAccessToken() {
		return accessToken;
	}
	public static void setAccessToken(String accessToken) {
		TwitterInteractor.accessToken = accessToken;
	}
	public static String getAccessTokenSecret() {
		return accessTokenSecret;
	}
	public static void setAccessTokenSecret(String accessTokenSecret) {
		TwitterInteractor.accessTokenSecret = accessTokenSecret;
	}
	public String getContactName() {
		return contactName;
	}
	public void setContactName(String contactName) {
		this.contactName = contactName;
	}
	
	public void sendErrorMessage(String message) throws TwitterException{
		
		DirectMessage directMessage = this.twitterMan.sendDirectMessage(this.contactName, message);
		System.out.println("Sent: " + directMessage.getText() 
		+ " to @" + directMessage.getRecipientScreenName());
	}
	
public void sendPrivateMessage(String receiver,String message) throws TwitterException{
		
		DirectMessage directMessage = this.twitterMan.sendDirectMessage(receiver, message);
		System.out.println("Sent: " + directMessage.getText() 
		+ " to @" + directMessage.getRecipientScreenName());
	}

	public void sendStatusUpdate(String message) throws TwitterException{
		this.twitterMan.updateStatus(message);
	}
	
	public void parseMessages(){
		try {
			
			for(DirectMessage dm : this.twitterMan.directMessages().getDirectMessages()){
				String receiver = dm.getSenderScreenName();
				
				if(!receiver.equals("RaspeinMonitor")){
					String messageText = dm.getText();
					try{
						System.out.println("Messaggio Ricevuto da:"+receiver);
						if(receiver.equals("puddleMatt")){
							this.sendPrivateMessage(receiver,"Matteo sei bello come il sole");
						}
						if(messageText.toLowerCase().startsWith("status")){
							String collectDate = null;
							ApiInteractor api = new ApiInteractor();
							JSONArray resArr = api.getArray("getRaspiFromName", "&name="+cleanMessage("status", messageText));
							JSONObject resObj = resArr.getJSONObject(0);
							if(resObj.getString("result").equals("success")){
								//un raspberry è stato trovato quindi posso richiedere delle informazioni
								JSONObject raspi = resArr.getJSONObject(1);
								String returnMessage = "Device " + cleanMessage("status", messageText) + " found!";
								int id = raspi.getInt("id");
								JSONArray sensorsArray = api.getArray("getSensors", "&id="+id);
								if(sensorsArray.getJSONObject(0).getString("result").equals("success")){
									returnMessage +=" On last check value was:";
									for (int i = 1;i<sensorsArray.length();i++) {
										JSONObject obj = sensorsArray.getJSONObject(i);
										String sensType="";
										switch (obj.getInt("type")){
											case 1:
												sensType = "CPU";
												break;
											case 2:
												sensType = "MEM";
												break;
											case 3:
												sensType = "TEMP";
												break;
											case 4:
												sensType = "DISK";
												break;
										}
										JSONArray sensorValue = api.getArray("getLastData", "&id="+obj.getString("id"));
										if(sensorValue.getJSONObject(0).getString("result").equals("success")){
											JSONObject sensRes = sensorValue.getJSONObject(1);
											collectDate = sensRes.getString("date");
											returnMessage += " "+ sensType + " value " + sensRes.getString("value");
											
										}
									}
								}
								sendPrivateMessage(receiver, returnMessage + " Collect Date:"+collectDate);
							}else{
								//nessun raspberry trovato
								sendPrivateMessage(receiver,"Device not found");
							}
						}else if(messageText.toLowerCase().startsWith("update")){
							if(receiver.equals(this.contactName)){
								sendStatusUpdate(cleanMessage("update", messageText));		
								sendPrivateMessage(receiver,"Update published!");
							}else{
								sendPrivateMessage(receiver, "Hi, you are not allowed to send status update");
							}
							
						}else{
							this.sendPrivateMessage(receiver,"Command not recognized");
						}
					}
					catch(JSONException e){	
					}
					twitterMan.destroyDirectMessage(dm.getId());
				}
			}
		} catch (TwitterException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	private String cleanMessage(String fact,String message){
		int pointer=0;
		int check =0;
		String updateMessage = message.substring(message.toLowerCase().indexOf(fact) + fact.length());
		for(int i = 0;i<updateMessage.length() && check==0;i++){
			String charAtI = updateMessage.substring(i, i+1);
			if (charAtI.matches("[A-Za-z0-9]+")){
				check=1;
			}else{
				pointer++;
			}
		}
		return updateMessage.substring(pointer);
	}
	
	
}
