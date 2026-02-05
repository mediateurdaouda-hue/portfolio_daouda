package com.asbcode.projetetudiant;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import java.sql.*;

public class EtudiantDAO {

    public static void insert(Etudiant e) {
        String sql = "INSERT INTO etudiant(matricule, nom, prenom, age, moyenne) "
                   + "VALUES(?,?,?,?,?)";
        try (Connection c = DB.connect();
             PreparedStatement ps = c.prepareStatement(sql)) {

            ps.setString(1, e.getMatricule());
            ps.setString(2, e.getNom());
            ps.setString(3, e.getPrenom());
            ps.setInt(4, e.getAge());
            ps.setDouble(5, e.getMoyenne());
            ps.executeUpdate();
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    public static void delete(String matricule) {
        String sql = "DELETE FROM etudiant WHERE matricule=?";
        try (Connection c = DB.connect();
             PreparedStatement ps = c.prepareStatement(sql)) {

            ps.setString(1, matricule);
            ps.executeUpdate();
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    public static ObservableList<Etudiant> getAll() {
        ObservableList<Etudiant> list = FXCollections.observableArrayList();
        String sql = "SELECT * FROM etudiant";
        try (Connection c = DB.connect();
             Statement st = c.createStatement();
             ResultSet rs = st.executeQuery(sql)) {

            while (rs.next()) {
                Etudiant e = new Etudiant(
                        rs.getString("nom"),
                        rs.getString("prenom"),
                        rs.getString("matricule"),
                        rs.getInt("age")
                );
                e.setMoyenne(rs.getDouble("moyenne"));
                list.add(e);
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
        return list;
    }
    
    public static void update(Etudiant e) {
    String sql = "UPDATE etudiant SET nom=?, prenom=?, age=?, moyenne=? WHERE matricule=?";
    try (Connection c = DB.connect();
         PreparedStatement ps = c.prepareStatement(sql)) {
        ps.setString(1, e.getNom());
        ps.setString(2, e.getPrenom());
        ps.setInt(3, e.getAge());
        ps.setDouble(4, e.getMoyenne());
        ps.setString(5, e.getMatricule());
        ps.executeUpdate();
    } catch (SQLException ex) {
        ex.printStackTrace();
    }
}

}
