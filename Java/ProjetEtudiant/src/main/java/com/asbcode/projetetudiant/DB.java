/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.asbcode.projetetudiant;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

/**
 *
 * @author adamako
 */
public class DB {
    private static final String URL = "jdbc:sqlite:etudiants.db";

    public static Connection connect() throws SQLException {
        return DriverManager.getConnection(URL);
    }

    // Crée la table si elle n'existe pas
    public static void init() {
        String sql = "CREATE TABLE IF NOT EXISTS etudiant ("
                   + "matricule TEXT PRIMARY KEY, "
                   + "nom TEXT, "
                   + "prenom TEXT, "
                   + "age INTEGER, "
                   + "moyenne REAL"
                   + ");";
        try (Connection c = connect(); Statement st = c.createStatement()) {
            st.execute(sql);
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
