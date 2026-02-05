package com.asbcode.calculatrice;

import java.util.Scanner;

/**
 * @author Sofiane
 */
public class Calculatrice {

    public static void main(String[] args) {
        // Utilisation du try-with-resources pour fermer le scanner proprement
        try (Scanner scanner = new Scanner(System.in)) {
            Calculatrice calc = new Calculatrice();
            boolean continuer = true;

            System.out.println("******* Bienvenue dans votre Calculatrice Java******");

            while (continuer) {
                afficherMenu();

                if (!scanner.hasNextInt()) {
                    System.out.println("Erreur : Veuillez entrer un nombre pour le menu.");
                    scanner.next();
                    continue;
                }

                int choix = scanner.nextInt();

                if (choix == 6) {
                    System.out.println("Au revoir !");
                    break;
                }

                if (choix < 1 || choix > 6) {
                    System.out.println("Option invalide, réessayez.");
                    continue;
                }

                System.out.print("Entrez le premier nombre : ");
                double n1 = scanner.nextDouble();
                System.out.print("Entrez le deuxième nombre : ");
                double n2 = scanner.nextDouble();

                try {
                    //###  Utilisation du switch 
                    switch (choix) {
                        case 1 ->
                            System.out.println("La somme est : " + calc.additionner(n1, n2));
                        case 2 ->
                            System.out.println("La difference est: " + calc.soustraire(n1, n2));
                        case 3 ->
                            System.out.println("Le produit est : " + calc.multiplier(n1, n2));
                        case 4 ->
                            System.out.println("Le quotient est : " + calc.diviser(n1, n2));
                        case 5 ->
                            System.out.println("La puissance est  : " + calc.puissance(n1, n2));
                    }
                } catch (ArithmeticException e) {
                    System.out.println(e.getMessage());
                }
                System.out.println("-------------------------------------------");
            }
        }
    }

    //#### Affichage du MENU
    private static void afficherMenu() {
        System.out.println("\n***** MENU ******");
        System.out.println("1. Addition (+)");
        System.out.println("2. Soustraction (-)");
        System.out.println("3. Multiplication (*)");
        System.out.println("4. Division (/)");
        System.out.println("5. Puissance (a^b)");
        System.out.println("6. Quitter le programme");
        System.out.print("Entrez votre choix : ");
    }

    // ###LOGIQUE DE CALCUL
    public double additionner(double a, double b) {
        return a + b;
    }

    public double soustraire(double a, double b) {
        return a - b;
    }

    public double multiplier(double a, double b) {
        return a * b;
    }

    public double diviser(double a, double b) throws ArithmeticException {
        if (b == 0) {
            throw new ArithmeticException("Erreur : Division par zéro impossible !");
        }
        return a / b;
    }

    public double puissance(double a, double b) {
        return Math.pow(a, b);
    }
}
