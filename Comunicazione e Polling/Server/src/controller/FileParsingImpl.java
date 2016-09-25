package controller;

import java.io.File;
import java.util.List;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.io.BufferedReader;

/**
 * Questa Classe implementa l'interfaccia FileParsing, il suo compito è leggere il file dato ed estrarne i valori necessari all'aggiornamento di un sensore
 * @author Stefano Falzaresi <stefano.falzaresi2@studio.unibo.it>
 *
 */
public class FileParsingImpl implements FileParsing{
	private File file;
	private List<String> fileContent;
	public FileParsingImpl(File file) {
		this.file = file;
		this.fileContent = new ArrayList<>();
		loadFile();
	}

	@Override
	public double getCPU() {
		double toRet = 0;
		toRet = Double.parseDouble(getReq(1));
		return toRet;
	}

	@Override
	public double getMem() {
		double toRet = 0;
		toRet = Double.parseDouble(getReq(2));
		return toRet;
	}

	@Override
	public double getDisk() {
		double toRet = 0;
		toRet = Double.parseDouble(getReq(4));
		return toRet;
	}

	@Override
	public double getTemp() {
		double toRet = 0;
		toRet = Double.parseDouble(getReq(3))/1000.00;
		return toRet;
	}
	
	@Override
	public String getFileName() {
		return this.file.getName();
	}
	
	@Override
	public String getRaspiName() {
		return getReq(0);
	}
	
	private void loadFile(){
		try {
			BufferedReader br= new BufferedReader(new FileReader(this.file));
			String toAdd = br.readLine();;
			do{
				this.fileContent.add(toAdd);
				toAdd = br.readLine();
			}while(toAdd != null);
			br.close();
		} catch (IOException e) {
			// file non presente
			e.printStackTrace();
		}
	}
	
	private String getReq(int id){
		String toRet = null;
		for (String row : fileContent) {
			int delimiter = row.indexOf("-");
			int index = Integer.parseInt(row.substring(0, delimiter));
			if(index == id){
				toRet = row.substring(delimiter+1);
			}
		}
		return toRet;
	}
}