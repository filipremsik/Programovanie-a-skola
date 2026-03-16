package model;

import java.io.Serializable;
//vystavny kon
public class Show_horse extends Horse implements Serializable {
    private static final long serialVersionUID = -3399294425942654392L;
    protected int win;
    public Show_horse(String name, int old,int win) {
        super(name, old);
        //vyhry
        this.win=win;
    }
    public Show_horse(){}
    //vratenie udajov
    public StringBuilder data(){
        StringBuilder sb = new StringBuilder();
        sb.append(" Meno ");
        sb.append(name);
        sb.append(" Vek ");
        sb.append(old);
        sb.append(" Výhry ");
        sb.append(win);


        return sb;
    }
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public int getOld() {
        return old;
    }

    public void setOld(int old) {
        this.old = old;
    }

    public int getWin() {
        return win;
    }

    public void setWin(int win) {
        this.win = win;
    }
    public String getSpec() {
        return String.valueOf(win);
    }
}
