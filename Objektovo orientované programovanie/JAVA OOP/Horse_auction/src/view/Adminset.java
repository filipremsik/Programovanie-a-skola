package view;

import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.ScrollPane;
import javafx.scene.control.TextField;
import javafx.scene.layout.*;
import javafx.scene.text.Text;


public class Adminset {

    private Scene sc;
    private Pane pane;
    private ScrollPane scpan;
    public VBox box,login;
    public Button b1,b2,b3;
    public TextField name,pass;
    public Adminset(){
        pane = new Pane();
        scpan = new ScrollPane();
        box = new VBox();
        login = new VBox();
        login.setLayoutX(300);
        name = new TextField();
        pass = new TextField();
        b1 = new Button("Ulož");
        b2 = new Button("Späť");

        login.getChildren().add(new Text("Vytvorenie admina"));
        login.getChildren().add(new Text("Meno"));
        login.getChildren().add(name);
        login.getChildren().add(new Text("Heslo"));
        login.getChildren().add(pass);
        login.getChildren().add(b1);
        login.getChildren().add(b2);
        pane.getChildren().add(box);
        pane.getChildren().add(login);
        sc = new Scene(scpan);
        scpan.setContent(pane);



    }

    public Scene getSc() {
        return sc;
    }
}
