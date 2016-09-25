package controller;

import model.ServerConfiguration;
import model.ServerConfigurationImpl;

/**
 * Questa classe, estendendo la superclasse Thread, avvia un thread che si occupa di avviarne altri due specifici,
 * uno che si occuperà della comunicazione ed uno che si occuperà del polling dei dati
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public class MainThread extends Thread{
	private Boolean stop;
	private MessageCrawlerThread messageHandler;
	private RaspberryDataCollectorThread dataCollector;
	@Override
	public void run() {
		this.stop=false;
		ApiInteractor apint = new ApiInteractor();
		int checkRes = 0;
		Boolean boolRes = apint.checkResolution();
		while (checkRes<50){
			checkRes ++;
			boolRes = apint.checkResolution();
			if(boolRes){
				checkRes = 50;
			}
			try {
				Thread.sleep(1000);
			} catch (InterruptedException e) {
			}
		}
		if(boolRes){
			ServerConfiguration serverConf = new ServerConfigurationImpl(apint,apint.getInstID());
			TwitterInteractor twitterInt = new TwitterInteractor(serverConf.getTwitterConsumerKey(),
					serverConf.getTwitterConsumerSecret(),
					serverConf.getTwitterAccessToken(),
					serverConf.getTwitterAccessTokenSecret(),
					serverConf.getTwitterContactName()
					);
			this.messageHandler = new MessageCrawlerThread(twitterInt);
			this.dataCollector = new RaspberryDataCollectorThread(serverConf.getFolder(),apint,twitterInt);
			messageHandler.start();
			dataCollector.start();
			while (!this.stop){
				try {
					Thread.sleep(10000);
				} catch (InterruptedException e) {
					System.out.println("Presa eccezione nel main");
					messageHandler.stopThread();
					e.printStackTrace();
				}
			}
		}
	}
	public void stopThread(){
		this.stop=true;
		messageHandler.stopThread();
	}
}
