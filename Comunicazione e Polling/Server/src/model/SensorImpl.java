package model;

/**
 * L'oggetto qui descritto è un elemento sensore, ogni elemento ha un range, una tipologia, un id ed una descrizione
 * 
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public class SensorImpl implements Sensor {
	private int id;
	private int type;
	private double to;
	private double from;
	private String description;
	@Override
	public String getDescription() {
		return this.description;
	}

	@Override
	public int getId() {
		return this.id;
	}
	
	@Override
	public int getType() {
		return this.type;
	}

	@Override
	public double getFrom() {
		return this.from;
	}

	@Override
	public double getTo() {
		return this.to;
	}

	@Override
	public void setId(int id) {
		this.id = id;
	}
	
	@Override
	public void setType(int type) {
		this.type = type;
	}

	@Override
	public void setDescription(String description) {
		this.description = description;
	}

	@Override
	public void setFrom(double from) {
		this.from = from;
	}

	@Override
	public void setTo(double to) {
		this.to = to;
	}

}
