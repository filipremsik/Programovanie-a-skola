package app;

public class OwnException extends Exception {
    //typ vlastnej vynimky
    public OwnException(String errorMessage) {
        super(errorMessage);
    }

}
