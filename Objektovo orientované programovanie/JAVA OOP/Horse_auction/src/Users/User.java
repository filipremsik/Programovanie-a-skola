package Users;
import java.io.Serializable;


public abstract class User implements Serializable{

    private static final long serialVersionUID = 404141677546322128L;
    protected String username;
    protected String password;



    public User(String username,String password) {
        this.username=username;
        this.password=password;

    }

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }






}
