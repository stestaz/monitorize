package model;

/**
 * Questa interfaccia modella l'elemento che si occupa di configurare il sistema attingendo dai dati forniti dalle API
 * 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public interface ServerConfiguration {
	
	/**
	 * Questo metodo restituisce l'id univoco della configurazione
	 * @return l'id univoco della configurazione
	 */
	public int getId();
	
	/**
	 * Questo metodo restituisce la cartella di lavoro
	 * @return percorso della cartella in cui verrano salvati i file dai dispositivi
	 */
	public String getFolder();
	
	/**
	 * Questo metodo restituice la chiave di autenticazione per l'utilizzo delle API
	 * @return una stringa rappresentante la chiave univoca di autenticazione
	 */
	public String getKey();
	
	/**
	 * Questo metodo restituisce la twitter consumer Key
	 * @return Twitter consumer key
	 */
	public String getTwitterConsumerKey();
	
	/**
	 * Questo metodo restituisce la chiave segreta di twitter
	 * @return Twitter Consumer Secret
	 */
	public String getTwitterConsumerSecret();
	
	/**
	 * Questo metodo restituisce il twitter Access Toker
	 * @return twitter Access Token
	 */
	public String getTwitterAccessToken();
	
	/**
	 * Questo metodo restituisce il Twitter Access Token Secret
	 * @return Twitter Access Token Secret
	 */
	public String getTwitterAccessTokenSecret();
	
	/**
	 * Questo metodo restituisce lo username dell'amministratore del sistema
	 * @return username twitter dell'amministratore del sistema
	 */
	public String getTwitterContactName();	
	
}
