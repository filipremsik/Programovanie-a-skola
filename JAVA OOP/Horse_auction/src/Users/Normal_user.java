package Users;

import model.Auction;
import model.Auctioner;
import model.Database;

import java.io.Serializable;
import java.util.ArrayList;

public class Normal_user extends User implements Serializable {

    private static final long serialVersionUID = -4849485751206805400L;

    public Normal_user(String username, String password){
        super(username,password);
    }
    protected ArrayList<Auction>myBid=new ArrayList<>();

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }

    public ArrayList<Auction> getMyBid() {
        return myBid;
    }


    //pridanie ponuky do aukcie
    public void getOffer(Double cash,Auction auction){
        Auction editauc=auction;
        Auctioner auctioner = new Auctioner(this.getUsername(),cash);
        editauc.getParticipants().add(auctioner);
        int a=Database.getDb().auctions.indexOf(auction);
        System.out.println(a);
        Database.getDb().auctions.set(a,editauc);
        Database.getDb().save();

        System.out.println("Vypíš aukcie"+cash);
    }
    public String getUser(){return username;}

}
