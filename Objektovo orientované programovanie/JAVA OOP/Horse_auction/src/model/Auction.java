package model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Date;

public class Auction implements Serializable {
    private static final long serialVersionUID = 161573190565573487L;
    private Double minprice=new Double(1.0);
    private Integer duration = new Integer(0);
    private Date date = new Date();
    private ArrayList<Horse> auct = new ArrayList<>();
    private ArrayList<Auctioner> participants = new ArrayList<>();
    private String name = new String();
    private Double winprice;
    public void setminprice(Double price){
        this.minprice=price;

    }
    //vratenie udajov z beziacej aukcie
    public String getReport(){
        StringBuilder s = new StringBuilder();
        s.append("Minimálna cena: "+minPrice());
        s.append(" trvanie: "+duration);
        for (Horse h:getHorse()){
            s.append(h.data());

        }
        return s.toString();
    }
    //vratenie udajov z ukoncenej aukcie
    public String getReportfinal(){
        StringBuilder s = new StringBuilder();
        for (Horse h:getHorse()){
            s.append(h.data());
        }
        s.append("\n");
        s.append(" Víťaz: "+name);
        s.append(" Víťazná cena: "+winprice);
        s.append("\n");
        return s.toString();
    }

    public Integer getDuration() {
        return duration;
    }
    public String getDuration_(){return String.valueOf(duration);}

    public void setDuration(Integer duration) {
        this.date.getTime();
        this.duration = duration;
    }

    public Date getDate() {
        return date;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public Double getWinprice() {
        return winprice;
    }

    public void setWinprice(Double winprice) {
        this.winprice = winprice;
    }

    public ArrayList<Horse> getHorse(){
        return auct;
    }
    public Double minPrice() {
        return minprice;
    }
    public ArrayList<Auctioner> getParticipants() {
        return participants;}
    }

