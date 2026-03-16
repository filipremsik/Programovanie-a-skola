package controler;

import Users.Normal_user;
import app.App;
import app.OwnException;
import javafx.scene.Node;
import javafx.scene.control.Button;
import javafx.scene.layout.HBox;
import javafx.scene.text.Text;
import model.Auction;
import model.Auctioner;
import model.Database;
import view.LoginView;
import view.MainView;

import java.util.ArrayList;

public class LoginViewControl {
    LoginView view;
    public LoginViewControl(LoginView view, Normal_user n_user){
        this.view=view;
        App a = new App();
        showAuctions(n_user);
        MainView m = new MainView();
        MainViewControl mcontr = new MainViewControl(m);
        //späť na úvodnú obrazovku
        view.b.setOnAction(e-> {
            try {
                a.changeScene(m.getScene());
            } catch (Exception exception) {
                exception.printStackTrace();
            }

        });



    }
    //zobrazenie prebiehajucich a ukoncenych aukcii
    public void showAuctions(Normal_user normal_user){
        ArrayList<Auction>auctions=Database.getDb().auctions;
        ArrayList<Auction>auctionsend=Database.getDb().endAuctions;
        ArrayList<Node> nodes= new ArrayList<>();
        ArrayList<Node> winner = new ArrayList<>();
        for (Auction auction:auctionsend){
            winner.add(new Text("Ukončené aukcie"));
            Text txt = new Text(auction.getReportfinal());
            winner.add(txt);
            Button bz = new Button("Zmaž");
            winner.add(bz);
            bz.setOnAction(z->{
                Database.getDb().endAuctions.remove(auction);
                Database.getDb().save();
                showAuctions(normal_user);
            });

        }
        view.ended.getChildren().setAll(winner);
        for(Auction auc: auctions) {
            Text t = new Text(auc.getReport());
            nodes.add(t);
            Button bp = new Button("Pridaj ponuku");
            try {
                ArrayList<Auctioner> auctioners = auc.getParticipants();
                for (Auctioner auctioner : auctioners) {
                    System.out.println(auctioner.auctioner());
                    if (normal_user.getUsername().equals(auctioner.auctioner())) {
                        bp.setDisable(true);
                    }
                }
            }catch(Exception e){
                System.out.println("chyba");
            }
            nodes.add(bp);
            bp.setOnAction(act->{
                view.l1.setText(auc.getReport().toString());
                view.b1.setDisable(false);
                adCash(auc,normal_user);

            });

        }
        view.auction.getChildren().setAll(nodes);

    }
    //pridanie ponuky k vybranej aukcii
    public void adCash(Auction auc,Normal_user normal_user){
        view.b1.setOnAction(save->{
            normal_user.getMyBid().add(auc);
            try{
                if(auc.minPrice()<=Double.parseDouble(view.cash.getText())) {
                    System.out.println(auc.minPrice()+" "+Double.parseDouble(view.cash.getText()));
                    normal_user.getOffer(Double.parseDouble(view.cash.getText()),auc);
                }else {
                    view.l2.setText("Príliš malá ponuka");
                }
                //overenie vstupu
            }catch (Exception exception){
                view.l2.setText("Zlý formát");
                try {
                    throw new OwnException("Zlý formát");
                } catch (OwnException e) {
                    e.printStackTrace();
                }


            }
            view.b1.setDisable(true);
            showAuctions(normal_user);
        });


    }
}
