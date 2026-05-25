USE GestionProjetsEntreprise;

INSERT INTO CLIENT (NomClient, AdresseClient, EmailClient, TelClient) VALUES
('Entreprise ABC', '123 Rue du Commerce, Douala', 'contact@entrepriseabc.cm', '699 000 001'),
('Societe XYZ', '456 Avenue des Affaires, Yaounde', 'info@societe-xyz.cm', '699 000 002'),
('Cabinet Horizon', 'Akwa, Douala', 'contact@horizon.cm', '699 000 003');

INSERT INTO TYPEPROJET (LibelleTypeProjet, NomTypeProjet, ForfaitCoutTypeProjet, DescriptionTypeProjet) VALUES
('Developpement Web', 'Web', 3000000, 'Sites web, plateformes et portails metier'),
('Refonte Graphique', 'Design', 1500000, 'Identite visuelle, maquettes et supports'),
('Application de gestion', 'ERP leger', 5000000, 'Application interne de pilotage et reporting');

INSERT INTO CHEF_PROJET (NomChefProjet, PrenomChefProjet, EmailChefProjet, TelChefProjet) VALUES
('Tchana', 'Delilia', 'delilia.tchana@entreprise.cm', '699 100 001'),
('Mbarga', 'Eric', 'eric.mbarga@entreprise.cm', '699 100 002');

INSERT INTO CHEF_SERVICE (NomChefService, PrenomChefService, EmailChefService, TelChefService) VALUES
('Mballa', 'Nadine', 'nadine.mballa@entreprise.cm', '699 200 001'),
('Fouda', 'Patrick', 'patrick.fouda@entreprise.cm', '699 200 002');

INSERT INTO SERVICE (CodeService, LibelleService, TypeService, DescriptionService, IdChefService) VALUES
('DEV', 'Developpement', 'Technique', 'Equipe chargee du developpement logiciel', 1),
('DESIGN', 'Design graphique', 'Creation', 'Equipe chargee des maquettes et supports', 2),
('SUPPORT', 'Support client', 'Operationnel', 'Suivi client et assistance fonctionnelle', 1);

INSERT INTO PERSONNEL (MatriculePersonnel, NomPersonnel, PrenomPersonnel, EmailPersonnel, TelPersonnel, DateEmbauche, CodeService) VALUES
('EMP001', 'Dupont', 'Jean', 'jean.dupont@entreprise.cm', '699 300 001', '2024-01-10', 'DEV'),
('EMP002', 'Martin', 'Sophie', 'sophie.martin@entreprise.cm', '699 300 002', '2024-03-05', 'DESIGN'),
('EMP003', 'Ngo', 'Aline', 'aline.ngo@entreprise.cm', '699 300 003', '2025-02-17', 'SUPPORT');

INSERT INTO PROJET (TitreProjet, DescriptionProjet, CoutProjet, DateDebutProjet, DateFinProjet, EtatProjet, IdClient, IdTypeProjet, IdChefProjet) VALUES
('Site E-commerce', 'Creation d''un site e-commerce complet', 4200000, '2026-01-15', '2026-04-30', 1, 1, 1, 1),
('Refonte Logo', 'Modernisation de l''identite visuelle', 1600000, '2026-02-01', '2026-02-28', 2, 2, 2, 2),
('Plateforme interne', 'Suivi des activites et reporting operationnel', 5800000, '2026-03-10', '2026-07-20', 1, 3, 3, 1);

INSERT INTO TACHE (LibelleTache, DescriptionTache, DateEnregTache, DateDebutTache, DateFinTache, EtatTache, IdProjet, CodeService) VALUES
('Maquettage des pages', 'Preparation des maquettes principales', '2026-01-16', '2026-01-20', '2026-02-05', 3, 1, 'DESIGN'),
('Developpement front-end', 'Integration responsive des interfaces', '2026-01-16', '2026-02-06', '2026-03-15', 2, 1, 'DEV'),
('Creation des propositions', 'Propositions graphiques pour le client', '2026-02-02', '2026-02-03', '2026-02-10', 3, 2, 'DESIGN'),
('Analyse fonctionnelle', 'Recueil des besoins metier', '2026-03-10', '2026-03-12', '2026-03-25', 2, 3, 'SUPPORT');

INSERT INTO REGLEMENT (IdProjet, DateReglement, HeureReglement, MontantReglement, ModePaiementReglement, ReferenceReglement, CommentaireReglement) VALUES
(1, '2026-01-15', '10:30:00', 2000000, 'Virement', 'VIR-2026-001', 'Avance initiale'),
(2, '2026-02-01', '14:45:00', 1600000, 'Mobile Money', 'MM-2026-014', 'Projet solde'),
(3, '2026-03-12', '09:20:00', 2500000, 'Cheque', 'CHQ-7781', 'Premier acompte');

INSERT INTO AFFECTATION (IdTache, MatriculePersonnel, DateAffectation, HeureAffectation, FonctionAffectation) VALUES
(1, 'EMP002', '2026-01-20', '09:00:00', 'Responsable maquettage'),
(2, 'EMP001', '2026-02-06', '09:00:00', 'Developpeur principal'),
(3, 'EMP002', '2026-02-03', '09:00:00', 'Designer graphique'),
(4, 'EMP003', '2026-03-12', '10:00:00', 'Analyste fonctionnel');

INSERT INTO UTILISATEUR (NomUtilisateur, EmailUtilisateur, MotDePasse, Role) VALUES
('admin', 'admin@example.com', '$2y$10$8usLgGRU.iJK04V9R/T81u67LUma8FgVT/rX/Uzfw2Ndw0gK2Ld.G', 'admin'),
('chefprojet', 'chefprojet@example.com', '$2y$10$He3RKx1I2cXgswdpJbSsiu4utlnhWF8fDIRV6VuC./YKLnAhFJv12', 'chef_projet'),
('chefservice', 'chefservice@example.com', '$2y$10$vNfr8JXNe6K2Xd5M2xYew.rh53wgIWjLvyiaLlVYUQ8otICJsQFvS', 'chef_service'),
('personnel', 'personnel@example.com', '$2y$10$.sdzobpp8B7BypVVu.hcFeobYHAS4Xaxez1gkSdVQ1RPbLW8qfSv.', 'personnel');
