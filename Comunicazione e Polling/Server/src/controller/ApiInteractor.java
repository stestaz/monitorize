package controller;

import java.net.URL;
import java.net.UnknownHostException;

import org.json.JSONArray;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.InetAddress;
import java.net.MalformedURLException;

/**
 * Questa Classe da alle classi che la utilizzano un accesso più ad alto livello per l'interazione con le REST API fornite, i metodi messi a disposizione sono due,
 * uno per le chiamate GET e uno per le POST
 * 
 * Per funzionare è necessario che il file /root/.settings.conf sia correttamente compilato
 * 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public class ApiInteractor {
	
	private String serverName;
	private String storedApi;
	private int instID;
	static String FILENAME = "/root/.settings.conf";
	
	public ApiInteractor() {
		try {
			BufferedReader br= new BufferedReader(new FileReader(FILENAME));
			String toAdd = br.readLine();;
			do{
				if(toAdd.indexOf("ApiKey=") !=-1){
					this.storedApi = toAdd.substring(toAdd.indexOf("ApiKey=")+7);
					System.out.println(this.storedApi);
				}
				if(toAdd.indexOf("ServerName=") !=-1){
					this.serverName = toAdd.substring(toAdd.indexOf("ServerName=")+11);
					System.out.println(this.serverName);
				}
				if(toAdd.indexOf("instID=") !=-1){
					this.instID = Integer.parseInt(toAdd.substring(toAdd.indexOf("instID=")+7));
					System.out.println(this.instID);
				}
				toAdd = br.readLine();
			}while(toAdd != null);
			br.close();
		} catch (IOException e) {
			// file non presente
			e.printStackTrace();
		}
	}
	public Boolean checkResolution(){
		try {
			InetAddress.getByName(new URL(serverName).getHost());
		} catch (UnknownHostException e) {
			e.printStackTrace();
			return false;
		} catch (MalformedURLException e) {
			e.printStackTrace();
			return false;
		}
		return true;
	}
	public int getInstID(){
		return this.instID;
	}
	public JSONArray getArray(String requestedApi, String params){
		  HttpURLConnection connection = null;
		  String targetURL = this.serverName+requestedApi+"/";
		  StringBuffer response = new StringBuffer();
		  
		  try {
			String urlParameters = "key="+this.storedApi + params;
		    URL url = new URL(targetURL+"?"+urlParameters);
		    connection = (HttpURLConnection)url.openConnection();
		    connection.setRequestMethod("GET");
		    connection.setRequestProperty("User-Agent", "Mozilla/5.0");
			BufferedReader in = new BufferedReader(
			        new InputStreamReader(connection.getInputStream()));
			String inputLine;
			

			while ((inputLine = in.readLine()) != null) {
				response.append(inputLine);
			}
			in.close();

		  }catch(Exception e){
			  e.printStackTrace();
		  }
		  return new JSONArray(response.toString());
	}
	
	public String setString(String requestedApi, String params){
		  HttpURLConnection connection = null;
		  String targetURL = this.serverName+requestedApi+"/";
		  
		  
		  try {
			String urlParameters = "key="+this.storedApi
	  			+params;
		    URL url = new URL(targetURL);
		    connection = (HttpURLConnection)url.openConnection();
		    connection.setRequestMethod("POST");
		    connection.setRequestProperty("Content-Type", 
		        "application/x-www-form-urlencoded");

		    connection.setRequestProperty("Content-Length", 
		        Integer.toString(urlParameters.getBytes().length));
		    connection.setRequestProperty("Content-Language", "it-IT");  

		    connection.setUseCaches(false);
		    connection.setDoOutput(true);

		    DataOutputStream wr = new DataOutputStream (
		        connection.getOutputStream());
		    wr.writeBytes(urlParameters);
		    wr.close();
  
		    InputStream is = connection.getInputStream();
		    BufferedReader rd = new BufferedReader(new InputStreamReader(is));
		    StringBuilder response = new StringBuilder(); 
		    String line;
		    while((line = rd.readLine()) != null) {
		      response.append(line);
		      response.append('\r');
		    }
		    rd.close();
		    return response.toString();
		  } catch (Exception e) {
		    e.printStackTrace();
		    return null;
		  } finally {
		    if(connection != null) {
		      connection.disconnect(); 
		    }
		  }
		}
}
