package model;

import java.io.Serializable;
//tazny kon
public class Cart_horse extends Horse implements Serializable {
    private static final long serialVersionUID = 1323914890192623699L;
    protected double power;
    public Cart_horse(String name, int old,double power) {
        super(name, old);
        //sila(prekonavanie)
        this.power=power;
    }
    public Cart_horse(){}
    //vratenie udajov
    public StringBuilder data(){
        StringBuilder sb = new StringBuilder();
        sb.append(" Meno ");
        sb.append(name);
        sb.append(" Vek ");
        sb.append(old);
        sb.append(" Sila ");
        sb.append(power);


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

    public double getPower() {
        return power;
    }

    public void setPower(double power) {
        this.power = power;
    }
    public String getSpec() {
        return String.valueOf(power);
    }
}
