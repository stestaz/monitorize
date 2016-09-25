package controller;

/**
 * Questa Classe si occupa di monitorare la presenza di messaggi su twitter e i conseguenza eseguire le operazioni necessarie,
 * il metodo stopThread permette di arrestare il thread in modo sicuro
 * 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public class MessageCrawlerThread extends Thread{
	TwitterInteractor ti;
	private Boolean stop;
	public MessageCrawlerThread(TwitterInteractor ti) {
		this.ti = ti;
	}
	@Override
	public void run() {
		this.stop=false;
		super.run();
		while (!this.stop){
			try {
				ti.parseMessages();
				Thread.sleep(5000);
			} catch (InterruptedException e) {
			}
		}
	}
	public void stopThread(){
		this.stop = true;
	}
}
