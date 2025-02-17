package model;

import Users.User;

import java.io.*;
import java.util.ArrayList;

public class Database implements Serializable {


    private static final long serialVersionUID = 622197535515987367L;
    private static Database db;
    private Database(){}
    public static Database getDb(){
        if(db ==null){
            db =new Database();
        }
        return db;
    }
    //ukladane data
    public static ArrayList<Auction>auctions = new ArrayList<>();
    public static ArrayList<User>users = new ArrayList<>();
    public static ArrayList<Auction>endAuctions= new ArrayList<>();

    //vypis do terminalu
    public static void output(){
        auctions.forEach(auction -> {
            StringBuilder sb = new StringBuilder();
            sb.append("Minimálna cena: "+auction.minPrice());
            for (Horse h:auction.getHorse()){
                sb.append(h.data());

            }
            System.out.println(sb.toString());

        });
    }
    //vratenie pre zobrazenie v okne
    public StringBuilder output2(){
        StringBuilder s = new StringBuilder();
        for (Auction a:auctions){
            s.append("Minimálna cena: "+a.minPrice());
            for (Horse h:a.getHorse()){
                s.append(h.data());

            }
            s.append("\n");



        }
        return s;
    }
    // ukladanie dat do suborov
    public static void save(){
        try {
            FileOutputStream f1o = new FileOutputStream("users.txt");
            ObjectOutputStream o1o = new ObjectOutputStream(f1o);
            o1o.writeObject(users);
            o1o.close();
            f1o.close();


            FileOutputStream fo = new FileOutputStream("data_save.txt");
            ObjectOutputStream oo = new ObjectOutputStream(fo);
            oo.writeObject(auctions);
            oo.close();
            fo.close();


            FileOutputStream f2o = new FileOutputStream("auction_end.txt");
            ObjectOutputStream o2o = new ObjectOutputStream(f2o);
            o2o.writeObject(endAuctions);
            o2o.close();
            f2o.close();


            System.out.println("Saved");
        }catch(Exception e){
            e.printStackTrace();

        }
    }
    //nacitavanie dat zo suborov
    public static void load(){
        try {

            FileInputStream f1i = new FileInputStream("users.txt");
            ObjectInputStream o1i = new ObjectInputStream(f1i);
            users = (ArrayList<User>) o1i.readObject();
            o1i.close();
            f1i.close();


        }catch(Exception e){
            e.printStackTrace();

        }
        try {

            FileInputStream fi = new FileInputStream("data_save.txt");
            ObjectInputStream oi = new ObjectInputStream(fi);
            auctions = (ArrayList<Auction>) oi.readObject();
            oi.close();
            fi.close();

        }catch(Exception e){
            e.printStackTrace();

        }
        try {


            FileInputStream f2i = new FileInputStream("auction_end.txt");
            ObjectInputStream o2i = new ObjectInputStream(f2i);
            endAuctions = (ArrayList<Auction>) o2i.readObject();
            o2i.close();
            f2i.close();



        }catch(Exception e){
            e.printStackTrace();

        }


        System.out.println("Loaded");
    }
}
