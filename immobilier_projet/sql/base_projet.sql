-- =============================
-- BASE DE DONNÉES PROJET IMMOBILIER
-- =============================

CREATE DATABASE IF NOT EXISTS gestion_immo;
USE gestion_immo;

-- =============================
-- TABLE UTILISATEURS
-- =============================
CREATE TABLE utilisateurs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  identifiant VARCHAR(100) NOT NULL UNIQUE,
  motdepasse VARCHAR(255) NOT NULL,
  telephone VARCHAR(20) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  role ENUM('client', 'bailleur', 'agent', 'manager') NOT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  code_confirmation VARCHAR(10) DEFAULT NULL,
  etat ENUM('actif', 'inactif') NOT NULL DEFAULT 'actif'
);

-- =============================
-- TABLE PROPRIETES
-- =============================
CREATE TABLE proprietes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  titre VARCHAR(150) NOT NULL,
  type VARCHAR(50) NOT NULL,
  utilisation VARCHAR(100) NOT NULL,
  option_vente_location ENUM('Vente', 'Location') NOT NULL,
  superficie FLOAT NOT NULL,
  nb_pieces INT NOT NULL,
  description TEXT NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  prix INT NOT NULL,
  statut ENUM('disponible', 'vendue', 'louée') NOT NULL DEFAULT 'disponible',
  validation ENUM('en_attente', 'validee', 'refusee') NOT NULL DEFAULT 'en_attente',
  proprietaire_id INT NOT NULL,
  date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (proprietaire_id) REFERENCES utilisateurs(id)
);

-- =============================
-- TABLE IMAGES DE PROPRIETES
-- =============================
CREATE TABLE propriete_images (
  id INT PRIMARY KEY AUTO_INCREMENT,
  propriete_id INT NOT NULL,
  image VARCHAR(255) NOT NULL,
  FOREIGN KEY (propriete_id) REFERENCES proprietes(id)
);

-- =============================
-- TABLE RENDEZ-VOUS
-- =============================
CREATE TABLE rendezvous (
  id INT PRIMARY KEY AUTO_INCREMENT,
  client_id INT NOT NULL,
  propriete_id INT NOT NULL,
  date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
  date_rdv DATE DEFAULT NULL,
  heure_rdv TIME DEFAULT NULL,
  agent_id INT DEFAULT NULL,
  statut ENUM('en_attente', 'valide', 'annule') DEFAULT 'en_attente',
  etat_vente ENUM('en_attente', 'vendu_agent', 'confirmee') NOT NULL DEFAULT 'en_attente',
  FOREIGN KEY (client_id) REFERENCES utilisateurs(id),
  FOREIGN KEY (propriete_id) REFERENCES proprietes(id),
  FOREIGN KEY (agent_id) REFERENCES utilisateurs(id)

);
-- =============================
-- TABLE FAVORIS
-- =============================
CREATE TABLE favoris (
  id INT PRIMARY KEY AUTO_INCREMENT,
  client_id INT NOT NULL,
  propriete_id INT NOT NULL,
  date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE conversations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agent_id INT NOT NULL,
  client_id INT NOT NULL,
  date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(agent_id, client_id),
  FOREIGN KEY (agent_id) REFERENCES utilisateurs(id),
  FOREIGN KEY (client_id) REFERENCES utilisateurs(id)
);

CREATE TABLE messages (
  id INT PRIMARY KEY AUTO_INCREMENT,
  conversation_id INT NOT NULL,
  expediteur_id INT NOT NULL,
  contenu TEXT NOT NULL,
  date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (conversation_id) REFERENCES conversations(id),
  FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id)
);
