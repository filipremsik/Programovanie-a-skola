package view;

import app.App;
import controler.MainViewControl;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.Pane;
import javafx.scene.layout.VBox;
import javafx.scene.text.Text;
import model.Database;

public class LoginView {
    private Scene sc;
    public VBox box,auction,setcash,ended;
    public TextField cash;
    public Button b,b1;
    public Label l,l1,l2;
    public LoginView(){
        Pane pan = new Pane();
        ScrollPane pane = new ScrollPane();
        box = new VBox();
        auction = new VBox();
        setcash = new VBox();
        setcash.setLayoutX(400);
        cash = new TextField();
        cash.setLayoutX(500);
        cash.setLayoutY(50);

        b=new Button("Späť");
        b1 = new Button("Ulož ponuku");
        b1.setLayoutX(500);
        b1.setLayoutY(100);
        l=new Label();
        l1=new Label();
        l1.setLayoutX(400);
        l2=new Label();
        l2.setLayoutX(500);
        l2.setLayoutY(150);
        ended = new VBox();
        ended.setLayoutX(500);
        ended.setLayoutY(200);

        setcash.getChildren().add(cash);
        box.getChildren().add(b);
        box.getChildren().add(new Text("Zoznam aukcii"));
        box.setSpacing(20);
        box.getChildren().add(auction);
        //box.getChildren().add(ended);
        pan.getChildren().add(box);
        pan.getChildren().add(cash);
        pan.getChildren().add(l1);
        pan.getChildren().add(l2);
        pan.getChildren().add(b1);
        pan.getChildren().add(ended);
        pane.setContent(pan);
        sc=new Scene(pane);


    }
    public Scene getScene(){
        return(sc);
    }
}
