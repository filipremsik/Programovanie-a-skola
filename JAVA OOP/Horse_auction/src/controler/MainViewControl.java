package controler;

import Users.Admin;
import Users.Normal_user;
import Users.Owner;
import Users.User;
import app.App;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.scene.text.Text;
import model.Database;
import view.AddHorse;
import view.LoginView;
import view.MainView;

import java.sql.SQLOutput;
import java.util.ArrayList;

public class MainViewControl {
    MainView view;

    public MainViewControl(MainView view) {
               this.view = view;
               login();

    }
    //prihlasenie do systemu
    public void login(){
        ArrayList<User> users=Database.getDb().users;
        view.log.setDisable(true);
        view.pane.setContent(view.box);
        view.reg.setOnAction(act->{
            regist();
        });
        view.b1.setOnAction(e -> {
            App a = new App();
            LoginView l = new LoginView();
            AddHorse ad = new AddHorse();
            //AddHorseControl adcont= new AddHorseControl(ad);

            Integer next=0;
            //prehladavanie medzi uzivatelmi
            for (User o:users){
                System.out.println(o.getUsername());
                if(o.getUsername().equals(view.name.getText().toString()) & o.getPassword().equals(view.password.getText().toString())) {
                    //vstup bezny uzivatel
                    if (o.getClass().toString().equals("class Users.Normal_user")) {
                        try {
                            Normal_user normal_user = new Normal_user(o.getUsername(),o.getPassword());
                            LoginViewControl lcont = new LoginViewControl(l,normal_user);
                            a.changeScene(l.getScene());
                        } catch (Exception exception) {
                            exception.printStackTrace();
                        }
                        //vstup administrator
                    }else if(o.getClass().toString().equals("class Users.Admin")) {
                        try {

                            Admin admin = new Admin(o.getUsername(),o.getPassword());
                            AddHorseControl adcont= new AddHorseControl(ad,admin);
                            a.changeScene(ad.getScene());
                        } catch (Exception exception) {
                            exception.printStackTrace();
                        }
                        //vstup majitel
                    }else if(o.getClass().toString().equals("class Users.Owner")){
                        try {
                            Owner owner = new Owner(o.getUsername(),o.getPassword());
                            AddHorseControl adcont= new AddHorseControl(ad,owner);
                            a.changeScene(ad.getScene());
                        } catch (Exception exception) {
                            exception.printStackTrace();
                        }
                    }

                }
            }


            if(next==0)
                view.l.setText("Zle meno/heslo");

        });

    }
    //registrácia bežného užívateľa
    public void regist(){
        int test=0;
        view.reg1.setDisable(true);
        view.pane.setContent(view.box1);

        view.log1.setOnAction(act->{
            login();
        });
        view.b2.setOnAction(regs->{
            testreg();
        });
    }
    //overenie či už účet existuje
    public void testreg(){
        ArrayList<User> users=Database.getDb().users;
        boolean copy=true;
        for(User u: users){
            System.out.println(u.getUsername());
            if(u.getUsername().equals(view.name1.getText().toString())){
                view.l1.setText("Daný účet už existuje");
                copy=false;

                break;
            }
        }
        if(copy){
            Normal_user normal_user = new Normal_user(view.name1.getText().toString(),view.newpassword.getText().toString());
            Database.getDb().users.add(normal_user);
            Database.getDb().save();
        }

    }
}
