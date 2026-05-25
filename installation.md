# Installation - Gestion des projets d'entreprise

## Prerequis

- Laragon avec Apache demarre.
- MySQL demarre.
- PHP 8.3 disponible dans `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe`.
- MySQL client disponible dans `C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe`.

## Installation rapide

1. Placer le dossier dans:

```text
C:\laragon\www\projet soutenace\GestionprojetsEntreprise
```

2. Importer la base:

```powershell
& 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe' -uroot -e "SOURCE database/schema.sql; SOURCE database/seed.sql;"
```

3. Verifier la configuration dans `app/config.php`:

```php
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'GestionProjetsEntreprise';
```

4. Ouvrir l'application:

```text
http://localhost/projet%20soutenace/GestionprojetsEntreprise/login.php
```

Les modules metier sont accessibles sous `modules/`, par exemple:

```text
http://localhost/projet%20soutenace/GestionprojetsEntreprise/modules/projets/index.php
```

## Comptes par defaut

- Administrateur: `admin` / `admin123`
- Chef de projet: `chefprojet` / `chefprojet123`
- Chef de service: `chefservice` / `chefservice123`
- Personnel: `personnel` / `personnel123`

## Tests apres installation

1. Se connecter avec le compte `admin`.
2. Ouvrir `/tests/index.php`.
3. Verifier que tous les controles sont au vert.
4. Tester la creation d'un client, d'un projet, d'une tache, d'un reglement et d'une affectation.
5. Tester les connexions chef de projet, chef de service et personnel.

## Reinitialiser la base

La commande d'import supprime et recree les tables de `GestionProjetsEntreprise`. Sauvegarder les donnees de production avant de la lancer.
