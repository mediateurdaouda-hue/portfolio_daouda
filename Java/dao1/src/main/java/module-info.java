module com.mycompany.dao1 {
    requires javafx.controls;
    requires javafx.fxml;

    opens com.mycompany.dao1 to javafx.fxml;
    exports com.mycompany.dao1;
}
