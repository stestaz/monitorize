package model;

/**
 * Questa interfaccia modella un elemento di tipo sensore
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public interface Sensor {
	/**
	 * questo metodo restituisce la descrizione del sensore
	 * @return descrizione del sensore
	 */
	public String getDescription();
	
	/**
	 * Questo metodo restituisce la tipologia
	 * @return un numero rappresentate la tipologia del sensore
	 */
	public int getType();
	
	/**
	 * Questo metodo restituisce il valore da cui parte il range
	 * @return un valore minimo da cui parte il range
	 */
	public double getFrom();
	
	/**
	 * Questo metodo restituisce il valore massimo a cui arriva il range
	 * @return valore massimo oltre i quale la soglia è superata
	 */
	public double getTo();
	
	/**
	 * Questo metodo restituisce l'id univoco del sensore
	 * @return id univoco che rappresenta il sensore su interfaccia web
	 */
	int getId();
	
	/**
	 * Questo metodo permette di impostare la tipologia del sensore
	 * @param type un intero rappresentatne la tipologia di sesnore
	 */
	public void setType(int type);
	
	/**
	 * Questo metodo permette di impostare la descrizione di questo sensore
	 * @param description una stringa per descrivere il sensore
	 */
	public void setDescription(String description);
	
	/**
	 * Attraverso questo metodo è possibile definire il valore minimo del range
	 * @param from un numero indicante la partenza inferiore del range
	 */
	public void setFrom(double from);
	
	/**
	 * Attraverso questo metodo è possibile definire il valore massimo del range
	 * @param to un numero incande la soglia massima del range ammesso
	 */
	public void setTo(double to);
	
	/**
	 * Questo metodo permette di impostare l'id univoco del sensore
	 * @param id id univoco da assegnare al sensore
	 */
	void setId(int id);
}
