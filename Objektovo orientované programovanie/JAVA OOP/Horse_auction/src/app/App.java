package app;

import Users.Normal_user;
import Users.*;
import controler.MainViewControl;
import javafx.application.Application;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.ScrollPane;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import model.Auction;
import model.Database;
import model.Racing_horse;
import view.AddHorse;
import view.LoginView;
import view.MainView;

import java.util.ArrayList;

public class App extends Application {
    public ScrollPane pane;
    public VBox box;
    public Button btn;
    public Scene scene;
    private static Stage window;
    @Override
    public void start(Stage primaryStage) throws Exception {
        Database.getDb().load();
        window=primaryStage;

        //spustenie vlakna pre overovanie konca aukcie
        Time t = new Time();
       // t.start();
        primaryStage.setTitle("Aukcia koní");
        ArrayList<Auction> ended=Database.getDb().endAuctions;
        for(Auction auction:ended){
            System.out.println(auction.getReportfinal());
        }
        primaryStage.setWidth(900);
        primaryStage.setHeight(600);
        primaryStage.setMinHeight(600);
        primaryStage.setMinWidth(900);
        MainView mv = new MainView();
        MainViewControl mcontr = new MainViewControl(mv);
        AddHorse a = new AddHorse();
        primaryStage.setScene(mv.getScene());
        primaryStage.show();


    }
    //zmena obrazoviek
    public void changeScene(Scene next) throws Exception{
        window.setScene(next);

    }

    public static void main(String[] args) {
        launch(args);









    }


}
