package controler;

import Users.Admin;
import Users.Owner;
import Users.User;
import app.App;
import javafx.scene.Node;
import javafx.scene.control.Button;
import javafx.scene.layout.HBox;
import javafx.scene.text.Text;
import javafx.stage.Stage;
import model.*;
import view.AddHorse;
import view.Adminset;
import view.EditAuction;
import view.MainView;
import java.util.ArrayList;

public class AddHorseControl {
    AddHorse view;
    Stage stage = new Stage();
    //vstup administratora
    public AddHorseControl(AddHorse view,Admin o){
        this.view=view;
        App a = new App();
        view.b9.setDisable(true);
        //ulozenie aukcie so zavodným konom
        view.b4.setOnAction(b->{
            Racing_horse r = new Racing_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Double.parseDouble(view.speed.getText()));
            o.createAuction(r,(Double.parseDouble(view.minprice.getText())),Integer.parseInt(view.duration.getText()));
            showAuctions();

        });
        //ulozenie aukcie s tazným konom
        view.b5.setOnAction(b->{
            Cart_horse c = new Cart_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Double.parseDouble(view.power.getText()));
            o.createAuction(c,(Double.parseDouble(view.minprice.getText())),Integer.parseInt(view.duration.getText()));
            showAuctions();
        });
        //ulozenie aukcie s vystavnym konom
        view.b6.setOnAction(c->{
            Show_horse s = new Show_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Integer.parseInt(view.win.getText()));
            o.createAuction(s,(Double.parseDouble(view.minprice.getText())),Integer.parseInt(view.duration.getText()));
            showAuctions();
        });
        MainView m = new MainView();
        MainViewControl mcontr = new MainViewControl(m);
        //prechod na uvodnu obrazovku
        view.b8.setOnAction(d-> {
            try {
                a.changeScene(m.getScene());
            } catch (Exception exception) {
                exception.printStackTrace();
            }

        });
        // zobrazenie aukcii
        view.b7.setOnAction(b->{
            Database.getDb().output();
            //view.l6.setText(Database.getDb().output2().toString());
            showAuctions();
        });

    }
    // vstup majitela
    public AddHorseControl(AddHorse view, Owner o){
        this.view=view;
        App a = new App();
        //ulozenie aukcie so zavodnym konom
        view.b4.setOnAction(b->{
            Racing_horse r = new Racing_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Double.parseDouble(view.speed.getText()));
            o.createAuction(r,(Double.parseDouble(view.minprice.getText())),Integer.parseInt(view.duration.getText()));
            showAuctions();
        });
        //ulozenie aukcie s tazným konom
        view.b5.setOnAction(b->{
            Cart_horse c = new Cart_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Double.parseDouble(view.power.getText()));
            o.createAuction(c,(Double.parseDouble(view.minprice.getText())),Integer.parseInt(view.duration.getText()));
            showAuctions();
        });
        //ulozenie aukcie s vystavnym konom
        view.b6.setOnAction(c->{
            Show_horse s = new Show_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Integer.parseInt(view.win.getText()));
            o.createAuction(s,(Double.parseDouble(view.minprice.getText())),Integer.parseInt(view.duration.getText()));
            showAuctions();
        });
        MainView m = new MainView();
        MainViewControl mcontr = new MainViewControl(m);
        //prechod na uvodnu obrazovku
        view.b8.setOnAction(d-> {
            try {
                a.changeScene(m.getScene());
            } catch (Exception exception) {
                exception.printStackTrace();
            }

        });
        view.b7.setOnAction(b->{
            Database.getDb().output();
            //view.l6.setText(Database.getDb().output2().toString());
            showAuctions();
        });
        // zobrazenie aukcii
        view.b9.setOnAction(adm->{
            //Stage stage = new Stage();
            Adminset ad = new Adminset();
            AdminsetControl adc = new AdminsetControl(ad,o);
            try {
                a.changeScene(ad.getSc());
            } catch (Exception e) {
                e.printStackTrace();
            }

        });


    }
    //zobrazenie aukcii
    public void showAuctions(){
        ArrayList<Auction>auctions=Database.getDb().auctions;
        ArrayList<Node> nodes= new ArrayList<>();
        for(Auction auc: auctions){
            Text t = new Text(auc.getReport());
            nodes.add(t);
            Button b = new Button("Zmaž");
            Button del = new Button("Uprav");
            HBox h = new HBox();
            h.getChildren().add(b);
            h.getChildren().add(del);
            nodes.add(h);
            b.setOnAction(d->{
                Database.getDb().auctions.remove(auc);
                Database.getDb().save();
                showAuctions();

            });
            // prechod k uprave aukcii
            del.setOnAction(act->{
                stage.setHeight(600);
                stage.setWidth(300);
                EditAuction view = new EditAuction();
                EditAuctionControl contr = new EditAuctionControl(view,auc,this);
                stage.setScene(view.getSc());
                stage.show();


            });

        }
        view.auctions.getChildren().setAll(nodes);

    }
    public void closeEdit(){
        stage.close();
        showAuctions();
    }
}

