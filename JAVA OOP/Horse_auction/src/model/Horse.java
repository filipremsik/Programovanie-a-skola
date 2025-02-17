package model;

import java.io.Serializable;

//zakladna trieda kon
public abstract class Horse implements Serializable {
    private static final long serialVersionUID = 9202486156977872895L;
    protected String name;
    protected int old;

    public Horse(String name,int old) {
        this.name=name;
        this.old=old;

    }
    public Horse(){}
    //vratenie udajov
    public StringBuilder data(){
        StringBuilder sb = new StringBuilder();
        sb.append("Meno");
        sb.append(name);
        sb.append("Vek");
        sb.append(old);

        return (sb);
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
    public String getSpec() {
        return "1";
    }
}
