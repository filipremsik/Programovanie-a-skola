package app;

import model.Auction;
import model.Auctioner;
import model.Database;

import java.util.ArrayList;
import java.util.Date;
import java.util.concurrent.TimeUnit;

public class Time extends Thread{
    public void run(){
       while (true){



           ArrayList<Auction> auctions=Database.getDb().auctions;
           Date date = new Date();
           date.getTime();
           Integer minutes=date.getMinutes()+(date.getHours()*60);
           Integer minauc;
           for(Auction auc:auctions){
               //zber udajov a premena casu na minuty
               ArrayList<Auctioner> auctioners=auc.getParticipants();
               Auction auc_end= new Auction();
               auc_end=auc;
               minauc=auc.getDate().getMinutes()+(auc.getDate().getHours())*60;
               System.out.println(minutes+" konec"+(minauc+auc.getDuration()));
               if(minauc+auc.getDuration()<=minutes){
                   Double firstprice=auc.minPrice();
                   Double secondprice=auc.minPrice();
                   String winner= new String();
                   //prehladavanie listu s ucastnikmi aukcie pre najdenie vitaza
                   for(Auctioner auctioner:auctioners){
                       System.out.println(auctioner.auctioner());
                       if(auctioner.aucash()>firstprice){
                           winner=auctioner.auctioner();
                           secondprice=firstprice;
                           firstprice=auctioner.aucash();
                       }

                   }
                   System.out.println(winner+" "+firstprice+" "+secondprice);
                   System.out.println("našiel");

                   auc_end.setName(winner);
                   auc_end.setWinprice(secondprice);
                   //zmazanie ukoncenej aukcie a vytvorenie vysledku aukcie
                   Database.getDb().auctions.remove(auc);
                   Database.getDb().endAuctions.add(auc_end);
                   Database.getDb().save();




               }

           }
           System.out.println("idem");
           try {
               TimeUnit.SECONDS.sleep(5);
           } catch (InterruptedException e) {
               e.printStackTrace();
           }
       }
    }

}
