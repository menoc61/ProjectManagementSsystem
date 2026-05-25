# Todo - Gestion des projets d'entreprise

## Termine

- [x] Aligner le schema MySQL sur le MCD adapte.
- [x] Ajouter les tables `CHEF_PROJET` et `CHEF_SERVICE`.
- [x] Rattacher les reglements aux projets.
- [x] Creer les roles `admin`, `chef_projet`, `chef_service`, `personnel`.
- [x] Securiser l'authentification avec `password_hash` et `password_verify`.
- [x] Centraliser la connexion MySQL, les helpers, le layout et le CRUD.
- [x] Moderniser l'interface avec Bootstrap 5 et FontAwesome.
- [x] Regrouper les modules metier dans le dossier `modules/`.
- [x] Ajouter la table de connexion rapide par role.
- [x] Ajouter le bouton afficher/masquer le mot de passe.
- [x] Ajouter un footer copyright avec le lien GitHub `menoc61`.
- [x] Ajouter une difference visuelle entre les roles.
- [x] Ajouter des mini-graphiques utiles dans les modules.
- [x] Ajouter les modules chefs de projet et chefs de service.
- [x] Ajouter une page de tests techniques.
- [x] Reecrire la documentation et le guide d'installation en francais.

## A verifier manuellement

- [ ] Connexion Apache depuis `http://localhost/GestionprojetsEntreprise/login.php`.
- [ ] CRUD complet de chaque module avec le compte admin.
- [ ] Restrictions d'acces pour chef de projet, chef de service et personnel.
- [ ] Exports CSV depuis les listes.
- [x] Responsive desktop, tablette et mobile.

## Ameliorations futures

- [ ] Ajouter des exports PDF reels avec une bibliotheque dediee.
- [ ] Ajouter une recherche avancee par periode et statut sur projets/taches/reglements.
- [ ] Ajouter un journal d'audit des actions sensibles.
- [ ] Ajouter des tests end-to-end automatises.
