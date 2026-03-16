package controler;

import Users.Owner;
import Users.User;
import app.App;
import javafx.scene.Node;
import javafx.scene.control.Button;
import javafx.scene.text.Text;
import model.Auction;
import model.Database;
import view.AddHorse;
import view.Adminset;

import java.util.ArrayList;

public class AdminsetControl {
    Adminset view;
    public AdminsetControl(Adminset view, Owner o){
        this.view=view;
        showAdmin();
        App a = new App();
        //vytvorenie administratora
        view.b1.setOnAction(b->{
            o.createAdmin(view.name.getText().toString(),view.pass.getText().toString());
            showAdmin();

        });
        //vratenie na obrazovku s aukciami
        view.b2.setOnAction(c->{
            AddHorse add = new AddHorse();
            AddHorseControl adcont =new AddHorseControl(add,o);
            try {
                a.changeScene(add.getScene());
            } catch (Exception e) {
                e.printStackTrace();
            }

        });


    }
    //zobrazenie administratorov
    public void showAdmin(){
        ArrayList<User>users=Database.getDb().users;
        ArrayList<Node> nodes= new ArrayList<>();
        Text ooo = new Text("Zoznam adminov");
        nodes.add(ooo);
        for(User user: users){
            if(user.getClass().toString().equals("class Users.Admin")) {
                Text t = new Text(user.getUsername().toString());
                nodes.add(t);
                Button b = new Button("Zmaž");
                nodes.add(b);
                //odstránenie administrátora
                b.setOnAction(d -> {
                    Database.getDb().users.remove(user);
                    Database.getDb().save();
                    showAdmin();

                });
            }

        }
        view.box.getChildren().setAll(nodes);


    }






}
