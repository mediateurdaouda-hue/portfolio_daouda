/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.asbcode.exofx1;

import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.stage.Modality;
import javafx.stage.Stage;



/**
 *
 * @author Sofiane
 */




public class FormModal {
    public static void show(ObservableList<Etudiant> etudiants ){
        Stage modal = new Stage();
        modal.initModality(Modality.APPLICATION_MODAL);
        
        Label labelNom = new Label("Nom:");
        TextField champNom = new TextField();
        HBox ligneNom = new HBox(10, labelNom, champNom); 

        // Ligne Prénom
        Label labelPrenom = new Label("Prenom:");
        TextField champPrenom = new TextField();
        HBox lignePrenom = new HBox(10, labelPrenom, champPrenom);

        // Ligne Matricule
        Label labelMatricule = new Label("Matricule:");
        TextField champMatricule = new TextField();
        HBox ligneMatricule = new HBox(10, labelMatricule, champMatricule);

        // Ligne Âge
        Label labelAge = new Label("Age:");
        TextField champAge = new TextField();
        HBox ligneAge = new HBox(10, labelAge, champAge);

        // Bouton
        Button btnSave = new Button("Enregistrer");
        Label message = new Label();
        
        
        btnSave.setOnAction(new EventHandler<ActionEvent>() {
            @Override
            public void handle(ActionEvent even) {
                try {
                    Etudiant e;
                    e = new Etudiant
                              (  champNom.getText(),
                                      champPrenom.getText(),
                                      champMatricule.getText(),
                                      Integer.parseInt(champAge.getText())
                              );
                    e.setMoyenne(12);
                    etudiants.add(e);
                    message.setText("Etudiant ajoute");
                    champNom.clear();
                    champPrenom.clear();
                    champMatricule.clear();
                    champAge.clear();
                    
                    
                    modal.close();
                }catch (NumberFormatException ex){
                    message.setText("Age invalid");
                }   }
        });
        
        VBox root = new VBox(
                 10,
                champNom,
                champPrenom,
                champMatricule,
                champAge,
                btnSave
        );
        root.setPadding(new Insets(20));
        root.setAlignment(Pos.CENTER);
        
        Scene scene = new Scene(root, 300, 300);
        modal.setScene(scene);
        
        modal.showAndWait();
        
    }
    
}
