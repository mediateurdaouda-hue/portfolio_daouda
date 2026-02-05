/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.asbcode.exofx1;

/**
 *
 * @author Sofiane
 */

public class Etudiant {

    // Attributs
    private String nom;
    private String prenom;
    private String matricule;
    private int age;
    private double moyenne;

    // Constructeur
    public Etudiant(String nom, String prenom, String matricule, int age) {
        this.nom = nom;
        this.prenom = prenom;
        this.matricule = matricule;
        this.age = age;
    }
    
    // Accesseur et mutateur 

    public String getNom() {
        return nom;
    }

    public String getPrenom() {
        return prenom;
    }

    public String getMatricule() {
        return matricule;
    }

    public int getAge() {
        return age;
    }

    public double getMoyenne() {
        return moyenne;
    }

    public boolean estAdmis(){
        return moyenne >= 10;
    }
    
    public String nomComplet(){
        return prenom + " "+ this.nom;
    }
    public void calculMoyenne(double note1, double note2,double note3) {
        this.moyenne = (note1 + note2 + note3) / 3;
    }
    public void setMoyenne(double moyenne){
        if(moyenne >=0 && moyenne <= 20) {
            this.moyenne = moyenne;
        }
    }
    
            
     // setters       
    public void setNom(String nom) {
        this.nom = nom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }
    
    
   
    public void setMatricule(String matricule) {
        this.matricule = matricule;
    }

    public void setAge(int age) {
        this.age = age;
    }

   
    
    
}

