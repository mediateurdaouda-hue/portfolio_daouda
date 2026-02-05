/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 */

package com.asbcode.hello;
import java.util.Scanner;

/**
 *
 * @author Sofiane
 */
public class Hello {

    public static void main(String[] args) {
        
        System.out.println("======= TEST Java======");
        System.out.println("Java version: " + System.getProperty("java.version"));
        
        Scanner sc = new Scanner(System.in);
        
        System.out.println("Entrez votre nom: ");
        // Recuperation de nom 
        String nom = sc.nextLine();
        
        System.out.println("Entrez votre age: ");
        int age = sc.nextInt();
        
        System.out.println();
        System.out.println("onjour " + nom + "!");
        System.out.println("Dans 10 ans vous aurez " + (age+10)+ "ans 😁");
        System.out.println();
        System.out.println("Java fonctionne correctement");
        
    }
}
