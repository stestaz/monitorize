package model;

import java.util.List;

/**
 * Questa interfaccia modella un elemento di tipo Raspberry
 * 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public interface RaspBerry {
	
	/**
	 * Questo metodo restituisce l'id univoco del disposivito
	 * @return id del dispositivo ricevuto dalle API
	 */
	public int getId();
	
	/**
	 * Questo metodo restituisce il nomde univoo del dispositivo
	 * @return una stringa indicante il nome del dispositivo
	 */
	public String getName();
	
	/**
	 * Questo metodo restituisce la posizione occupata nella torre dal dispositivo
	 * @return la posizione occupata dal dispositivo nella torre
	 */
	public String getPosition();
	
	/**
	 * Questo metodo restituisce la descrizione del dispositivo
	 * @return una stringa rappresentante la descrizione del dispositivo
	 */
	public String getDescription();
	
	/**
	 * Questo metodo restituisce la lista dei sensori presenti sul dispositivo
	 * @return una lista di oggetti sensore
	 */
	public List<Sensor> getSensors();
	
	/**
	 * Attraverso questo metodo è possibile impostare il nome del dispositivo
	 * @param name nome del dispositivo
	 */
	public void setName(String name);
	
	/**
	 * Consente l'aggiunta di un sensore all'elenco dei sensori del Raspberry
	 * @param type la tipologia del sensore
	 */
	public void addSensor(int type);
	
	/**
	 * consente di aggiungere un valore rilevato da un sensore di questo Raspberry
	 * @param sensType tipologia del sensore da aggiornare
	 * @param value valore rilevato dal sensore
	 */
	public void addData(int sensType,double value);
	
	/**
	 * Questo metodo permette di definire la posizione del dispositivo nella torre
	 * @param position posizione occupata dal dispositivo
	 */
	public void setPosition(String position);
	
	/**
	 * Questa funzione permette di assegnare una descrizione al dispositivo
	 * @param description la descrizione del dispositivo
	 */
	public void setDescription(String description);
}
