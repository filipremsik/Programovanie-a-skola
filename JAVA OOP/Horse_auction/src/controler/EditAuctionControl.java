package controler;

import javafx.scene.control.TextField;
import javafx.scene.text.Text;
import model.*;
import view.EditAuction;

import java.util.function.Function;
import java.util.stream.Collectors;

public class EditAuctionControl {
    EditAuction view;
    Auction auction;
    Auction auctionUpdate;
    AddHorseControl addhorse;

    public EditAuctionControl(EditAuction view, Auction auction,AddHorseControl addhorse) {
        this.view = view;
        this.auction=auction;
        this.addhorse=addhorse;
        refreshAuction();


    }
    //zobrazenie konkretnej aukcie
    public void refreshAuction(){
        switch(auction.getHorse().get(0).getClass().toString()) {
            case "class model.Cart_horse":
                view.specs.setText("Sila");
                break;
            case "class model.Racing_horse":
                view.specs.setText("Rýchlosť");
                break;
            case "class model.Show_horse":
                view.specs.setText("Výhry");

        }
        view.name.setText(auction.getHorse().get(0).getName());
        view.old.setText(String.valueOf(auction.getHorse().get(0).getOld()));
        view.spec.setText(auction.getHorse().get(0).getSpec());
        view.duration.setText(auction.getDuration_());
        addhorse.showAuctions();
        view.b.setOnAction(a->{
            newData();
        });



    }
    public void newData(){
        auctionUpdate = auction;
        switch(auction.getHorse().get(0).getClass().toString()) {
            case "class model.Cart_horse":
                Cart_horse ch = new Cart_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Double.parseDouble(view.spec.getText()));
                auctionUpdate.getHorse().remove(0);
                auctionUpdate.getHorse().add(ch);
                break;
            case "class model.Racing_horse":
                Racing_horse rh = new Racing_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Double.parseDouble(view.spec.getText()));
                auctionUpdate.getHorse().remove(0);
                auctionUpdate.getHorse().add(rh);

                break;
            case "class model.Show_horse":
                Show_horse sh = new Show_horse(view.name.getText(),Integer.parseInt(view.old.getText()),Integer.parseInt(view.spec.getText()));
                auctionUpdate.getHorse().remove(0);
                auctionUpdate.getHorse().add(sh);
        }
        auctionUpdate.setDuration(Integer.parseInt(view.duration.getText()));
        Integer a = Database.getDb().auctions.indexOf(auction);
        Database.getDb().auctions.set(a,auctionUpdate);
        addhorse.closeEdit();

    }

}
