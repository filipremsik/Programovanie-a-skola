package view;

import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.VBox;
import javafx.scene.text.Text;


public class EditAuction {
    private Scene sc;
    private ScrollPane pane;
    public VBox box;
    public TextField name,old,spec,duration;
    public Text specs;
    public Button b;
    public EditAuction(){
        pane = new ScrollPane();
        box = new VBox();
        name = new TextField();
        old = new TextField();
        spec = new TextField();
        duration = new TextField();
        specs = new Text();
        b = new Button("Ulož");
        pane.setContent(box);
        box.getChildren().add(new Text("Úprava aukcie"));
        box.getChildren().add(new Text("Aukcia"));
        box.getChildren().add(new Text("Meno"));
        box.getChildren().add(name);
        box.getChildren().add(new Text("Vek"));
        box.getChildren().add(old);
        box.getChildren().add(specs);
        box.getChildren().add(spec);
        box.getChildren().add(new Text("Trvanie"));
        box.getChildren().add(duration);
        box.getChildren().add(b);
        specs.setText("ok");






        sc = new Scene(pane);
    }


    public Scene getSc() {
        return sc;
    }

}
