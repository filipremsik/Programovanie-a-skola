package model;

import java.io.Serializable;
//zavodny kon
public class Racing_horse extends Horse implements Serializable {
    private static final long serialVersionUID = 1885827292344098417L;
    protected double speed;


    public Racing_horse(String name,int old,double speed){
        super(name,old);
        //rychlost
        this.speed=speed;


    }
    public Racing_horse(){}
    //vratenie udajov
    @Override
    public StringBuilder data(){
        StringBuilder sb = new StringBuilder();
        sb.append(" Meno ");
        sb.append(name);
        sb.append(" Vek ");
        sb.append(old);
        sb.append(" Rýchlosť ");
        sb.append(speed);


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

    public double getSpeed() {
        return speed;
    }

    public void setSpeed(double speed) {
        this.speed = speed;
    }
    public String getSpec() {
        return String.valueOf(speed);
    }
}
