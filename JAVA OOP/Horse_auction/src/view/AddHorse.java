package view;

import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import javafx.stage.Stage;
import model.*;

public class AddHorse {
    public Pane pane;
    public ScrollPane sp;
    public Button b1,b2,b3,b4,b5,b6,b7,b8,b9;
    public Scene scene;
    public Stage primaryStage;
    public TextField name,old,speed,power,win,minprice,duration;
    public Label l1,l2,l3,l4,l5,l6,l7,l8;
    public Auction a;
    public VBox auctions;



    public AddHorse(){
        pane =new Pane();
        sp = new ScrollPane();


        //Vbox
        auctions=new VBox();
        auctions.setLayoutY(270);
        //buttons
        b1 = new Button("Závodný");
        b2 = new Button("Ťažný");
        b2.setLayoutX(100);
        b3 = new Button("Výstavný");
        b3.setLayoutX(200);
        b4 = new Button("Ulož");
        b4.setLayoutX(80);
        b4.setLayoutY(230);
        b5 = new Button("Ulož");
        b5.setLayoutX(80);
        b5.setLayoutY(230);
        b6 = new Button("Ulož");
        b6.setLayoutX(80);
        b6.setLayoutY(230);
        b7 = new Button("Výpis");
        b7.setLayoutX(150);
        b7.setLayoutY(230);
        b8 = new Button("Späť");
        b8.setLayoutX(250);
        b8.setLayoutY(230);
        b9 = new Button("Správa Adminov");
        b9.setLayoutX(600);

        //labels
        l1 = new Label("Meno");
        l1.setLayoutX(20);
        l1.setLayoutY(40);
        l2 = new Label("Vek");
        l2.setLayoutX(20);
        l2.setLayoutY(120);
        l3=new Label("Rýchlosť");
        l3.setLayoutX(300);
        l3.setLayoutY(120);
        l4=new Label("Sila");
        l4.setLayoutX(300);
        l4.setLayoutY(120);
        l5=new Label("Výhry");
        l5.setLayoutX(300);
        l5.setLayoutY(120);
        l6=new Label();
        l6.setLayoutX(20);
        l6.setLayoutY(270);
        l7=new Label("Minimálna cena");
        l7.setLayoutX(300);
        l7.setLayoutY(40);
        l8=new Label("Trvanie");
        l8.setLayoutX(600);
        l8.setLayoutY(120);
        //fields
        name = new TextField();
        name.setLayoutX(20);
        name.setLayoutY(80);

        old = new TextField();
        old.setLayoutX(20);
        old.setLayoutY(160);

        speed = new TextField();
        speed.setLayoutX(300);
        speed.setLayoutY(160);

        power = new TextField();
        power.setLayoutX(300);
        power.setLayoutY(160);

        minprice = new TextField();
        minprice.setLayoutX(300);
        minprice.setLayoutY(80);

        win = new TextField();
        win.setLayoutX(300);
        win.setLayoutY(160);

        duration = new TextField();
        duration.setLayoutX(600);
        duration.setLayoutY(160);

        pane.getChildren().add(b7);
        pane.getChildren().add(b1);
        pane.getChildren().add(b2);
        pane.getChildren().add(b3);
        pane.getChildren().add(b8);
        pane.getChildren().add(b9);
        pane.getChildren().add(l1);
        pane.getChildren().add(l2);
        pane.getChildren().add(l6);
        pane.getChildren().add(l7);
        pane.getChildren().add(l8);
        pane.getChildren().add(name);
        pane.getChildren().add(old);
        pane.getChildren().add(minprice);
        pane.getChildren().add(auctions);
        pane.getChildren().add(duration);
        scene=new Scene(sp);
        sp.setContent(pane);
        ////////////////////////////////////

        b1.setOnAction(e->{
            b1.setDisable(true);
            rmb2();
            rmb3();
            pane.getChildren().add(l3);
            pane.getChildren().add(speed);
            pane.getChildren().add(b4);


        });

        b2.setOnAction(e->{
            rmb1();
            rmb3();
            b2.setDisable(true);
            pane.getChildren().add(l4);
            pane.getChildren().add(power);
            pane.getChildren().add(b5);

        });
        b3.setOnAction(e->{
            rmb1();
            rmb2();
            b3.setDisable(true);
            pane.getChildren().add(l5);
            pane.getChildren().add(win);
            pane.getChildren().add(b6);

        });



}

    public void rmb1() {
        b1.setDisable(false);
        pane.getChildren().remove(l3);
        pane.getChildren().remove(speed);
        pane.getChildren().remove(b4);

    }

    public void rmb2(){
        b2.setDisable(false);
        pane.getChildren().remove(l4);
        pane.getChildren().remove(power);
        pane.getChildren().remove(b5);

    }
    public void rmb3(){
        b3.setDisable(false);
        pane.getChildren().remove(l5);
        pane.getChildren().remove(win);
        pane.getChildren().remove(b6);

    }



    public Scene getScene(){
        return (scene);
    }
}