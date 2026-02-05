package com.asbcode.projetetudiant;


import javafx.application.Application;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.Separator;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;


/**
 * JavaFX App
 */
public class App extends Application {

    private ObservableList<Etudiant> etudiants =
            FXCollections.observableArrayList();

    @Override
    public void start(Stage stage) { 
        DB.init();// creer et initialistiion de la table

        // Champs de saisie
        //TextField txtNom = new TextField();
        //txtNom.setPromptText("Nom");
        //TextField txtPrenom = new TextField();
        //txtPrenom.setPromptText("Prénom");
        //TextField txtMatricule = new TextField();
        //txtMatricule.setPromptText("Matricule");
        //TextField txtAge = new TextField();
        //txtAge.setPromptText("Age");

        // Lignes (Label + Champ)
        //HBox ligneNom = new HBox(80, new Label("Nom :"), txtNom);
        //HBox lignePrenom = new HBox(65, new Label("Prénom :"), txtPrenom);
        //HBox ligneMatricule = new HBox(60, new Label("Matricule :"), txtMatricule);
        //HBox ligneAge = new HBox(90, new Label("Âge :"), txtAge);




        // Bouton
        //Button btnSave = new Button("Enregistrer");

        Button btnAjouter = new Button("AJouter un étudiant");
        Label message = new Label();
        Label texte = new Label();
        texte.setText("Liste des étudiants");

        //Table view

        TableView<Etudiant> table = new TableView();
        table.setItems(EtudiantDAO.getAll());
        table.setPrefHeight(300);

        TableColumn<Etudiant, String> colonneMatricule = new TableColumn("Matricule");
        colonneMatricule.setCellValueFactory(new PropertyValueFactory<>("matricule"));

        TableColumn<Etudiant, String> colonneNom = new TableColumn("Nom");
        colonneNom.setCellValueFactory(new PropertyValueFactory<>("nom"));


        TableColumn<Etudiant, String> colonnePrenom = new TableColumn("Prénom");
        colonnePrenom.setCellValueFactory(new PropertyValueFactory<>("prenom"));


        TableColumn<Etudiant, Integer> colonneAge = new TableColumn("Age");
        colonneAge.setCellValueFactory(new PropertyValueFactory<>("age"));

        TableColumn<Etudiant, Double> colonneMoyenne = new TableColumn("Moyenne");
        colonneMoyenne.setCellValueFactory(new PropertyValueFactory<>("moyenne"));

        TableColumn<Etudiant, String> colonneStatut = new TableColumn("Admis");
        colonneStatut.setCellValueFactory(cellValue -> {
            Etudiant etudiant = cellValue.getValue();
            String statut = etudiant.estAdmis() ? "OUI" : "NON";

            return new javafx.beans.property.SimpleStringProperty(statut);
        });



        //-------------------------ACTION------------------
        btnAjouter.setOnAction(event -> {
            FormModal.show(etudiants);
            table.setItems(EtudiantDAO.getAll());
        });

        // Conteneur principal (VBox)
        VBox root = new VBox(10);
        root.setAlignment(Pos.CENTER);
        root.setStyle("-fx-padding: 20;");
        root.getChildren().addAll(
                btnAjouter,
                texte,
                new Separator(),
                table
        );


        // AJout des colonnes dans la table
        table.getColumns().addAll(
                colonneMatricule,
                colonneNom,
                colonnePrenom,
                colonneAge,
                colonneMoyenne,
                colonneStatut
        );
        //etudiants.add(new Etudiant("BELLA","Sofiane","ETU125",18));
        // Scène
        Scene scene = new Scene(root, 500, 300);

        // Stage
        stage.setTitle("Java");
        stage.setScene(scene);
        stage.show();
    }

    public static void main(String[] args) {
        launch();
    }

}

