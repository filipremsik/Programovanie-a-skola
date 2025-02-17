package model;

import java.io.Serializable;

//Zoznam aukcionarov
public class Auctioner implements Serializable {

    private static final long serialVersionUID = -1356698089635217626L;
    public String name;
    public double cash;

    public Auctioner(String name, double cash) {
        this.name=name;
        this.cash=cash;
    }

    public String auctioner(){return name;}
    public double aucash(){return cash;}
}
