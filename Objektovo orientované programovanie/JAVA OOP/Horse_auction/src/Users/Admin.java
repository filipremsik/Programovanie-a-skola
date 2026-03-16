package Users;

import model.*;

import java.io.Serializable;


public class Admin extends User implements Serializable {


    private static final long serialVersionUID = 2261306337002839875L;
    protected Integer created_auctions=0;
    public Admin(String name, String password){
        super(name,password);
    }
    public String getUsername() {
        return username;
    }
    public String getPassword() {
        return password;
    }

    public Integer getCreated_auctions() {
        return created_auctions;
    }



    //tvorba aukcie na zaklade typu kona
    public void createAuction(Racing_horse horse,Double minprice,Integer duration){
        Auction auction = new Auction();
        auction.getHorse().add(horse);
        auction.setminprice(minprice);
        auction.setDuration(duration);
        Database.getDb().auctions.add(auction);
        Database.getDb().save();
        created_auctions=created_auctions+1;
    }
    public void createAuction(Cart_horse horse,Double minprice,Integer duration){
        Auction auction = new Auction();
        auction.getHorse().add(horse);
        auction.setminprice(minprice);
        auction.setDuration(duration);
        Database.getDb().auctions.add(auction);
        Database.getDb().save();
        created_auctions=created_auctions+1;

    }
    public void createAuction(Show_horse horse,Double minprice,Integer duration){
        Auction auction = new Auction();
        auction.getHorse().add(horse);
        auction.setminprice(minprice);
        auction.setDuration(duration);
        Database.getDb().auctions.add(auction);
        Database.getDb().save();
        created_auctions=created_auctions+1;

    }




}
