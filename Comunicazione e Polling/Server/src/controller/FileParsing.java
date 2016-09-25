package controller;

/**
 * Questa classe modella un oggetto utile al parsing dei file ricevuti da Raspberry
 * 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public interface FileParsing {

	/**
	 * Restituisce il valore della CPU letto nel file
	 * @return valore della CPU
	 */
	public double getCPU();
	
	/**
	 * Restituisce il valore della memoria disponibile letto nel file
	 * @return valore della memoria libera
	 */
	public double getMem();
	
	/**
	 * Restituisce lo spazio libero presente sul dispositivo e letto dal file
	 * @return valore del disco libero
	 */
	public double getDisk();
	
	/**
	 * Restituisce la temperatura presente sul Raspberry
	 * @return temperatura rilevata
	 */
	public double getTemp();
	
	/**
	 * Restituisce il nome del file
	 * @return nome del file
	 */
	public String getFileName();
	
	/**
	 * Restituisce il nome del Raspberry
	 * @return nome del Raspberry letto dal file
	 */
	public String getRaspiName();

	
}
