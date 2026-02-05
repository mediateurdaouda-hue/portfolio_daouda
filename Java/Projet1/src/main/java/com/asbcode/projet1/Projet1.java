/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 */

package com.asbcode.projet1;

/**
 *
 * @author Sofiane
 */
public class Projet1 {

    public static void main(String[] args) {
       Etudiant etudiant1 = new Etudiant("KO","Adama","ETU001",20);
       
       etudiant1.calculMoyenne(17, 20, 20);
        System.out.println("Etudiant : "+ etudiant1.nomComplet());
        System.out.println("Etudiant : "+ etudiant1.getMatricule());
        System.out.println("Etudiant : "+ etudiant1.getMoyenne());
        System.out.println("Etudiant : "+ etudiant1.estAdmis());
    }
}
