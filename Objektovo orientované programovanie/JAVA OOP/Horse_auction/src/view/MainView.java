package view;

import app.App;
import javafx.scene.control.*;
import view.LoginView;
import javafx.scene.Scene;
import javafx.scene.layout.*;
import javafx.scene.text.Text;
import javafx.stage.Stage;

public class MainView {
    public ScrollPane pane;
    public VBox box,box1;
    public HBox hbox,hbox1;
    public Button log,log1,reg,reg1,b1,b2;
    public Scene scene;
    public Stage primaryStage;
    public TextField name,name1,newpassword;
    public PasswordField password;
    public Label l,l1;

    public MainView(){
        pane =new ScrollPane();
        box = new VBox();
        box1 = new VBox();
        hbox = new HBox();
        hbox1 = new HBox();
        name = new TextField();
        name1 = new TextField();
        newpassword = new TextField();
        password = new PasswordField();
        l = new Label();
        l1 = new Label();
        log = new Button("Prihlásenie");
        reg = new Button("Registrácia");
        log1 = new Button("Prihlásenie");
        reg1 = new Button("Registrácia");
        b1 = new Button("Prihlásiť");
        b2 = new Button("Registrovať");
        box.getChildren().add(new Text("Meno"));
        box.getChildren().add(name);
        box.getChildren().add(new Text("Heslo"));
        box.getChildren().add(password);
        box.getChildren().add(b1);
        hbox.getChildren().add(log);
        hbox.getChildren().add(reg);
        box.getChildren().add(hbox);
        box.getChildren().add(l);
        //box 1
        box1.getChildren().add(new Text("Meno"));
        box1.getChildren().add(name1);
        box1.getChildren().add(new Text("Heslo"));
        box1.getChildren().add(newpassword);
        box1.getChildren().add(b2);
        hbox1.getChildren().add(log1);
        hbox1.getChildren().add(reg1);
        box1.getChildren().add(hbox1);
        box1.getChildren().add(l1);

        scene=new Scene(pane);
    }


    public Scene getScene(){
        return (scene);
    }

}
