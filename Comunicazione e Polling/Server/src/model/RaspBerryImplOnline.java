package model;

import java.util.List;
import org.json.JSONArray;
import org.json.JSONObject;
import java.util.ArrayList;
import org.json.JSONException;
import controller.ApiInteractor;
import twitter4j.TwitterException;
import controller.TwitterInteractor;

/**
 * Questo oggettò è l'implementazione dell'interfaccia Raspberry, modella quindi un dispositivo mappandolo completamente qon quello reso disponibile dalle API
 * 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public class RaspBerryImplOnline implements RaspBerry {
	private int id;
	private ApiInteractor apint;
	private TwitterInteractor twitterInt;
	private String name;
	private String position;
	private String description;
	private JSONObject raspiOnServer;
	private List<Sensor> sensors;
	
	public RaspBerryImplOnline(ApiInteractor apint, TwitterInteractor twitterInt, String name) {
		this.name = name;
		this.apint = apint;
		this.twitterInt = twitterInt;
		System.out.println("NAme:"+this.name);
		this.sensors = new ArrayList<Sensor>();
		if(existOnServer()){
			try{
				this.position = this.raspiOnServer.getString("position");
				try{
					this.description = this.raspiOnServer.getString("description");
				}catch (JSONException ex2){
				}
				
				JSONArray sensArray = this.apint.getArray("getSensors", "&id="+this.id);
				if(sensArray.getJSONObject(0).getString("result").equals("success")){
					for(int i = 1; i< sensArray.length();i++){
						JSONObject obj = sensArray.getJSONObject(i);
						Sensor tmpSens = new SensorImpl();
						tmpSens.setId(obj.getInt("id"));
						tmpSens.setType(obj.getInt("type"));
						try{
							tmpSens.setDescription(obj.getString("description"));
						}catch (JSONException ex){
						}
						tmpSens.setFrom(obj.getDouble("fromValue"));
						tmpSens.setTo(obj.getDouble("toValue"));
						this.sensors.add(tmpSens);
					}
				}
			}catch (JSONException e){
				System.out.println("Generata una eccezione");
				e.printStackTrace();
			}
		}else{
			try {
				twitterInt.sendErrorMessage("Il Raspberry con nome " + name 
						+ " non è presente in questa installazione,"
						+ " impossibile proseguire");
			} catch (TwitterException e) {
			}
		}
	}
	
	@Override
	public int getId() {
		return this.id;
	}

	@Override
	public String getName() {
		return this.name;
	}

	@Override
	public String getPosition() {
		return this.position;
	}

	@Override
	public String getDescription() {
		return this.description;
	}

	@Override
	public List<Sensor> getSensors() {
		return this.sensors;
	}

	@Override
	public void setName(String name) {
		this.name = name;
	}

	@Override
	public void addSensor(int type) {
		String request = "&raspId="+this.id;
		request +="&sensType="+type;
		request +="&from=0";
		request +="&to=0";
		float sensId = Float.parseFloat(this.apint.setString("addSensor",request));
		Sensor tmpSens = new SensorImpl();
		if (sensId != -1){
			tmpSens.setId((int) sensId);
			tmpSens.setType(type);
			tmpSens.setFrom(0);
			tmpSens.setTo(0);
			this.sensors.add(tmpSens);
		}else{
			System.out.println("Impossibile aggiungere il sensore");
		}
	}

	@Override
	public void setPosition(String position) {
		this.position = position;
	}

	@Override
	public void setDescription(String description) {
		this.description = description;
	}
	
	@Override
	public void addData(int sensType, double value) {
		for (Sensor sensor : this.sensors) {
			if (sensor.getType()==sensType){
				String res = this.apint.setString("addData", "&id="+sensor.getId()+"&value="+value);
				if(res.equals("-1")){
					System.out.println("Errore Nell'aggiunta del dato nel sensore");
				}else{
					checkRange(sensor, value);
				}
			}
		}	
	}
	
	private boolean existOnServer(){
		boolean exist = false;
		try{
			JSONArray jsonArr = this.apint.getArray("getRaspiFromName", "&name="+this.name);
			if (jsonArr.getJSONObject(0).getString("result").equals("success")){
				this.raspiOnServer = jsonArr.getJSONObject(1);
				this.id = this.raspiOnServer.getInt("id");
				if(this.id > 0){
					exist = true;
				}
			}
		}catch (JSONException e){
			exist = false;
		}
		return exist;
	}
	
	private void checkRange(Sensor sens,double value){
		int error=0;
		if(sens.getFrom() != sens.getTo()){
			if(value > sens.getTo() || value < sens.getFrom()){
				error ++;
				try {
					String sensType="";
					switch (sens.getType()){
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
					twitterInt.sendErrorMessage("Il dispositivo Raspberry avente nome "+this.name+
							" e posizione "+this.position
							+" ha rilevato un valore al di fuori del range impostato sulla risorsa " 
							+sensType + " il valore doveva essere compreso tra "+sens.getFrom()+ " e " + sens.getTo()
							+" ed invece è "+ value);
					  
				} catch (TwitterException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			  
			}else{
				//Valore nei limiti
			}	
		}
		if(error !=0){
			try {
				twitterInt.sendStatusUpdate("Errors found on last check please verify");
			} catch (TwitterException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}

}
