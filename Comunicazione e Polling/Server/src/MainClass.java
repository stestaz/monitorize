
import controller.MainThread;

public class MainClass {

	public static void main(String[] args) {
		final Thread mainThread = new MainThread();
		Runtime.getRuntime().addShutdownHook(new Thread() {
			@Override
			public void run() {
				System.out.println("interrupt received, killing server…");
				((MainThread) mainThread).stopThread();
			}
		});

		mainThread.start();
	}
}