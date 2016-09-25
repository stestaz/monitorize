package controller;

import java.io.File;
import model.Sensor;
import model.RaspBerry;
import model.RaspBerryImplOnline;

/**
 * Questa classe implementa un particolare tipo di thread utilizzato per effettuare in modo periodico il polling dei dati ricevuti dai dispositivi.
 * 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public class RaspberryDataCollectorThread extends Thread{
	private Boolean stop;
	private String folder;
	private ApiInteractor apint;
	private TwitterInteractor twitterInt;
	
	public RaspberryDataCollectorThread(String folder,ApiInteractor apint,TwitterInteractor twitterInt) {
		this.folder = folder;
		this.apint = apint;
		this.twitterInt = twitterInt;
		this.stop=false;
	}
	
	@Override
	public void run() {
		while(!stop){
			try {
				System.out.println("Parsing new Data");
				for (File file : new File(folder).listFiles()) {
					FileParsing fp = new FileParsingImpl(file);
					RaspBerry rasp = new RaspBerryImplOnline(apint,twitterInt,fp.getRaspiName());
					if(rasp.getSensors().size() ==0){
						rasp.addSensor(1);
						rasp.addSensor(2);
						rasp.addSensor(3);
						rasp.addSensor(4);
					}
					for (Sensor sensor : rasp.getSensors()) {
						switch (sensor.getType()){
							case 1:
								rasp.addData(1, 100 - fp.getCPU());
								break;
							case 2:
								rasp.addData(2, fp.getMem());
								break;
							case 3:
								rasp.addData(3, fp.getTemp());
								break;
							case 4:
								rasp.addData(4, fp.getDisk());
								break;							
						}
					}
					sleep(300000);	
				}
			} catch (InterruptedException e) {
			}
		}
	}
	public void stopThread(){
		this.stop = true;
	}
}
