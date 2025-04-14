-- Script SQL pour la création de la base de données et l'insertion des données d'exemple
-- Application de gestion de projets d'entreprise

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS GestionProjetsEntreprise;
USE GestionProjetsEntreprise;

-- Suppression des tables si elles existent déjà (pour éviter les erreurs)
DROP TABLE IF EXISTS AFFECTATION;
DROP TABLE IF EXISTS REGLEMENT;
DROP TABLE IF EXISTS TACHE;
DROP TABLE IF EXISTS PROJET;
DROP TABLE IF EXISTS PERSONNEL;
DROP TABLE IF EXISTS SERVICE;
DROP TABLE IF EXISTS TYPEPROJET;
DROP TABLE IF EXISTS CLIENT;
DROP TABLE IF EXISTS UTILISATEUR;

-- Création des tables selon la structure fournie
CREATE TABLE CLIENT (
  IdClient INT PRIMARY KEY AUTO_INCREMENT,
  NomClient VARCHAR(255) NOT NULL,
  AdresseClient VARCHAR(255),
  EmailClient VARCHAR(255),
  TelClient VARCHAR(255)
);

CREATE TABLE TYPEPROJET (
  IdTypeProjet INT PRIMARY KEY AUTO_INCREMENT,
  LibelleTypeProjet VARCHAR(255) NOT NULL,
  ForfaitCoutTypeProjet INT
);

CREATE TABLE SERVICE (
  CodeService VARCHAR(255) PRIMARY KEY,
  LibelleService VARCHAR(255) NOT NULL
);

CREATE TABLE PERSONNEL (
  MatriculePersonnel VARCHAR(255) PRIMARY KEY,
  NomPersonnel VARCHAR(255) NOT NULL,
  PrenomPersonnel VARCHAR(255) NOT NULL,
  EmailPersonnel VARCHAR(255),
  TelPersonnel VARCHAR(255),
  CodeService VARCHAR(255),
  FOREIGN KEY (CodeService) REFERENCES SERVICE(CodeService)
);

CREATE TABLE PROJET (
  IdProjet INT PRIMARY KEY AUTO_INCREMENT,
  TitreProjet VARCHAR(255) NOT NULL,
  DescriptionProjet VARCHAR(255),
  CoutProjet INT,
  DateDebutProjet DATE,
  DateFinProjet DATE,
  EtatProjet INT, -- 1: En cours, 2: Terminé, 3: Annulé
  IdClient INT,
  IdTypeProjet INT,
  FOREIGN KEY (IdClient) REFERENCES CLIENT(IdClient),
  FOREIGN KEY (IdTypeProjet) REFERENCES TYPEPROJET(IdTypeProjet)
);

CREATE TABLE TACHE (
  IdTache INT PRIMARY KEY AUTO_INCREMENT,
  LibelleTache VARCHAR(255) NOT NULL,
  DateEnregTache DATE,
  DateDebutTache DATE,
  DateFinTache DATE,
  EtatTache INT, -- 1: À faire, 2: En cours, 3: Terminée, 4: Annulée
  IdProjet INT,
  FOREIGN KEY (IdProjet) REFERENCES PROJET(IdProjet)
);

CREATE TABLE REGLEMENT (
  IdReglement INT PRIMARY KEY AUTO_INCREMENT,
  DateReglement DATE,
  HeureReglement TIME,
  MontantReglement INT,
  IdClient INT,
  FOREIGN KEY (IdClient) REFERENCES CLIENT(IdClient)
);

CREATE TABLE AFFECTATION (
  IdAffectation INT PRIMARY KEY AUTO_INCREMENT,
  DateAffectation DATE,
  HeureAffectation TIME,
  FonctionAffectation VARCHAR(255),
  MatriculePersonnel VARCHAR(255),
  IdTache INT,
  FOREIGN KEY (MatriculePersonnel) REFERENCES PERSONNEL(MatriculePersonnel),
  FOREIGN KEY (IdTache) REFERENCES TACHE(IdTache)
);

CREATE TABLE UTILISATEUR (
  IdUtilisateur INT AUTO_INCREMENT PRIMARY KEY,
  NomUtilisateur VARCHAR(255) NOT NULL,
  MotDePasse VARCHAR(255) NOT NULL,
  Role VARCHAR(50) NOT NULL
);

-- Insertion des données d'exemple

-- Insertion des clients
INSERT INTO CLIENT (IdClient, NomClient, AdresseClient, EmailClient, TelClient) VALUES
(1, 'Entreprise ABC', '123 Rue du Commerce, Paris', 'contact@entrepriseabc.fr', '01 23 45 67 89'),
(2, 'Société XYZ', '456 Avenue des Affaires, Lyon', 'info@societe-xyz.fr', '04 56 78 90 12');

-- Insertion des types de projet
INSERT INTO TYPEPROJET (IdTypeProjet, LibelleTypeProjet, ForfaitCoutTypeProjet) VALUES
(1, 'Développement Web', 5000),
(2, 'Refonte Graphique', 3000);

-- Insertion des services
INSERT INTO SERVICE (CodeService, LibelleService) VALUES
('DEV', 'Développement'),
('DESIGN', 'Design Graphique');

-- Insertion du personnel
INSERT INTO PERSONNEL (MatriculePersonnel, NomPersonnel, PrenomPersonnel, EmailPersonnel, TelPersonnel, CodeService) VALUES
('EMP001', 'Dupont', 'Jean', 'jean.dupont@entreprise.fr', '06 12 34 56 78', 'DEV'),
('EMP002', 'Martin', 'Sophie', 'sophie.martin@entreprise.fr', '06 98 76 54 32', 'DESIGN');

-- Insertion des projets
INSERT INTO PROJET (IdProjet, TitreProjet, DescriptionProjet, CoutProjet, DateDebutProjet, DateFinProjet, EtatProjet, IdClient, IdTypeProjet) VALUES
(1, 'Site E-commerce', 'Création d\'un site e-commerce complet', 6000, '2025-01-15', '2025-04-30', 1, 1, 1),
(2, 'Refonte Logo', 'Modernisation de l\'identité visuelle', 2500, '2025-02-01', '2025-02-28', 2, 2, 2);

-- Insertion des tâches
INSERT INTO TACHE (IdTache, LibelleTache, DateEnregTache, DateDebutTache, DateFinTache, EtatTache, IdProjet) VALUES
(1, 'Maquettage des pages', '2025-01-16', '2025-01-20', '2025-02-05', 3, 1),
(2, 'Développement Front-end', '2025-01-16', '2025-02-06', '2025-03-15', 2, 1),
(3, 'Création des propositions', '2025-02-02', '2025-02-03', '2025-02-10', 3, 2),
(4, 'Finalisation du logo', '2025-02-11', '2025-02-12', '2025-02-25', 2, 2);

-- Insertion des règlements
INSERT INTO REGLEMENT (IdReglement, DateReglement, HeureReglement, MontantReglement, IdClient) VALUES
(1, '2025-01-15', '10:30:00', 3000, 1),
(2, '2025-02-01', '14:45:00', 1500, 2);

-- Insertion des affectations
INSERT INTO AFFECTATION (IdAffectation, DateAffectation, HeureAffectation, FonctionAffectation, MatriculePersonnel, IdTache) VALUES
(1, '2025-01-20', '09:00:00', 'Responsable maquettage', 'EMP002', 1),
(2, '2025-02-06', '09:00:00', 'Développeur principal', 'EMP001', 2),
(3, '2025-02-03', '09:00:00', 'Designer graphique', 'EMP002', 3),
(4, '2025-02-12', '09:00:00', 'Designer graphique', 'EMP002', 4);

-- Insertion des utilisateurs (admin et personnel)
-- Mot de passe pour admin: admin123 (hashé)
-- Mot de passe pour user: user123 (hashé)
INSERT INTO UTILISATEUR (IdUtilisateur, NomUtilisateur, MotDePasse, Role) VALUES
(1, 'admin', '$2y$10$YourSaltHere1234567890uWtRiKVNtDNy4wUzRU3zoC5eT5f3Oc2', 'admin'),
(2, 'user', '$2y$10$YourSaltHere1234567890uQlZr7KRbGxh2z4VpZKBTQZB5q.ZtO', 'personnel');
