# Documentation - Gestion des projets d'entreprise

## Presentation

Cette application PHP/MySQL permet de gerer les clients, projets, taches, services, personnel, affectations, reglements et utilisateurs d'une entreprise. La version modernisee est alignee sur le MCD adapte et expose tous les acteurs attendus: administrateur, chef de projet, chef de service et personnel.

## Architecture

```
GestionprojetsEntreprise/
├── app/                  # Configuration, connexion, auth, helpers, CRUD commun
├── database/             # Schema MySQL et donnees de demonstration
├── public/assets/        # CSS et JavaScript applicatifs
├── clients/              # Module clients
├── projets/              # Module projets
├── taches/               # Module taches
├── reglements/           # Module reglements
├── services/             # Module services
├── personnel/            # Module personnel
├── affectations/         # Module affectations
├── chefs_projet/         # Module chefs de projet
├── chefs_service/        # Module chefs de service
├── utilisateurs/         # Module utilisateurs
├── dashboard/            # Tableau de bord
└── tests/                # Controles techniques
```

## Base de donnees

La base officielle est `GestionProjetsEntreprise`. Les tables principales sont:

- `CLIENT`
- `TYPEPROJET`
- `CHEF_PROJET`
- `CHEF_SERVICE`
- `SERVICE`
- `PERSONNEL`
- `PROJET`
- `TACHE`
- `REGLEMENT`
- `AFFECTATION`
- `UTILISATEUR`

Les reglements sont rattaches aux projets. Les projets sont rattaches aux clients, types de projet et chefs de projet. Les services peuvent etre diriges par un chef de service et les taches peuvent etre rattachees a un service responsable.

## Roles et permissions

- `admin`: acces complet, gestion des utilisateurs et des chefs.
- `chef_projet`: gestion des projets, types, taches, affectations et reglements.
- `chef_service`: gestion des services, personnel, taches et affectations.
- `personnel`: consultation des clients et taches.

## Comptes de demonstration

| Role | Identifiant | Mot de passe |
|---|---|---|
| Administrateur | `admin` | `admin123` |
| Chef de projet | `chefprojet` | `chefprojet123` |
| Chef de service | `chefservice` | `chefservice123` |
| Personnel | `personnel` | `personnel123` |

## Securite

- Les mots de passe sont stockes avec `password_hash`.
- La connexion utilise `password_verify`.
- Les requetes du noyau applicatif passent par des requetes preparees.
- Les sessions utilisent des cookies `HttpOnly` et `SameSite=Lax`.
- Les pages sensibles verifient les roles cote serveur.
- Les sorties HTML passent par un helper d'echappement.

## Interface

L'interface utilise Bootstrap 5, FontAwesome et une feuille CSS centralisee. La navigation est partagee par tous les modules et affiche uniquement les sections autorisees pour le role connecte.

## Verification

La page `/tests/index.php` controle:

- la connexion MySQL;
- la presence des tables du schema;
- la disponibilite des roles principaux;
- les comptes de demonstration requis.

Pour verifier la syntaxe PHP:

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { & 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' -l $_.FullName }
```
