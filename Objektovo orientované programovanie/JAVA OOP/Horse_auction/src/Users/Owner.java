package Users;

import model.*;

import java.io.Serializable;

public class Owner extends Admin implements Serializable {
    private static final long serialVersionUID = -903236630781189066L;
    protected Integer created_auctions=0;
    protected Integer created_admins=0;

    public Owner(String name, String password) {
        super(name, password);

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

    public void setCreated_auctions(Integer created_auctions) {
        this.created_auctions = created_auctions;
    }

    public Integer getCreated_admins() {
        return created_admins;
    }

    public void setCreated_admins(Integer created_admins) {
        this.created_admins = created_admins;
    }

    //vytvaranie noveho administratora
    public void createAdmin(String name,String pass){
        Admin admin = new Admin(name,pass);
        Database.getDb().users.add(admin);
        Database.getDb().save();
        created_admins=created_admins+1;
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
