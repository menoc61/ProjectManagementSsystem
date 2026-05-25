<?php
declare(strict_types=1);

function entity_definitions(): array
{
    return [
        'clients' => [
            'table' => 'CLIENT',
            'pk' => 'IdClient',
            'title' => 'Clients',
            'singular' => 'client',
            'icon' => 'fa-solid fa-building-user',
            'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE, ROLE_PERSONNEL],
            'search' => ['NomClient', 'EmailClient', 'TelClient'],
            'fields' => [
                'NomClient' => ['label' => 'Nom du client', 'type' => 'text', 'required' => true],
                'AdresseClient' => ['label' => 'Adresse', 'type' => 'textarea'],
                'EmailClient' => ['label' => 'Email', 'type' => 'email'],
                'TelClient' => ['label' => 'Telephone', 'type' => 'text'],
            ],
            'columns' => ['NomClient', 'EmailClient', 'TelClient'],
        ],
        'typesprojet' => [
            'table' => 'TYPEPROJET',
            'pk' => 'IdTypeProjet',
            'title' => 'Types de projet',
            'singular' => 'type de projet',
            'icon' => 'fa-solid fa-tags',
            'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET],
            'search' => ['LibelleTypeProjet', 'NomTypeProjet'],
            'fields' => [
                'LibelleTypeProjet' => ['label' => 'Libelle', 'type' => 'text', 'required' => true],
                'NomTypeProjet' => ['label' => 'Nom court', 'type' => 'text'],
                'ForfaitCoutTypeProjet' => ['label' => 'Forfait', 'type' => 'number', 'step' => '100'],
                'DescriptionTypeProjet' => ['label' => 'Description', 'type' => 'textarea'],
            ],
            'columns' => ['LibelleTypeProjet', 'NomTypeProjet', 'ForfaitCoutTypeProjet'],
        ],
        'chefs_projet' => [
            'table' => 'CHEF_PROJET',
            'pk' => 'IdChefProjet',
            'title' => 'Chefs de projet',
            'singular' => 'chef de projet',
            'icon' => 'fa-solid fa-user-tie',
            'roles' => [ROLE_ADMIN],
            'search' => ['NomChefProjet', 'PrenomChefProjet', 'EmailChefProjet'],
            'fields' => [
                'NomChefProjet' => ['label' => 'Nom', 'type' => 'text', 'required' => true],
                'PrenomChefProjet' => ['label' => 'Prenom', 'type' => 'text', 'required' => true],
                'EmailChefProjet' => ['label' => 'Email', 'type' => 'email'],
                'TelChefProjet' => ['label' => 'Telephone', 'type' => 'text'],
            ],
            'columns' => ['NomChefProjet', 'PrenomChefProjet', 'EmailChefProjet', 'TelChefProjet'],
        ],
        'chefs_service' => [
            'table' => 'CHEF_SERVICE',
            'pk' => 'IdChefService',
            'title' => 'Chefs de service',
            'singular' => 'chef de service',
            'icon' => 'fa-solid fa-user-shield',
            'roles' => [ROLE_ADMIN],
            'search' => ['NomChefService', 'PrenomChefService', 'EmailChefService'],
            'fields' => [
                'NomChefService' => ['label' => 'Nom', 'type' => 'text', 'required' => true],
                'PrenomChefService' => ['label' => 'Prenom', 'type' => 'text', 'required' => true],
                'EmailChefService' => ['label' => 'Email', 'type' => 'email'],
                'TelChefService' => ['label' => 'Telephone', 'type' => 'text'],
            ],
            'columns' => ['NomChefService', 'PrenomChefService', 'EmailChefService', 'TelChefService'],
        ],
        'services' => [
            'table' => 'SERVICE',
            'pk' => 'CodeService',
            'title' => 'Services',
            'singular' => 'service',
            'icon' => 'fa-solid fa-sitemap',
            'roles' => [ROLE_ADMIN, ROLE_CHEF_SERVICE],
            'search' => ['CodeService', 'LibelleService', 'TypeService'],
            'fields' => [
                'CodeService' => ['label' => 'Code', 'type' => 'text', 'required' => true],
                'LibelleService' => ['label' => 'Libelle', 'type' => 'text', 'required' => true],
                'TypeService' => ['label' => 'Type de service', 'type' => 'text'],
                'DescriptionService' => ['label' => 'Description', 'type' => 'textarea'],
                'IdChefService' => ['label' => 'Chef de service', 'type' => 'select', 'options' => 'CHEF_SERVICE:IdChefService:NomChefService,PrenomChefService'],
            ],
            'columns' => ['CodeService', 'LibelleService', 'TypeService', 'IdChefService'],
        ],
        'personnel' => [
            'table' => 'PERSONNEL',
            'pk' => 'MatriculePersonnel',
            'title' => 'Personnel',
            'singular' => 'membre du personnel',
            'icon' => 'fa-solid fa-id-badge',
            'roles' => [ROLE_ADMIN, ROLE_CHEF_SERVICE],
            'search' => ['MatriculePersonnel', 'NomPersonnel', 'PrenomPersonnel', 'EmailPersonnel'],
            'fields' => [
                'MatriculePersonnel' => ['label' => 'Matricule', 'type' => 'text', 'required' => true],
                'NomPersonnel' => ['label' => 'Nom', 'type' => 'text', 'required' => true],
                'PrenomPersonnel' => ['label' => 'Prenom', 'type' => 'text', 'required' => true],
                'EmailPersonnel' => ['label' => 'Email', 'type' => 'email'],
                'TelPersonnel' => ['label' => 'Telephone', 'type' => 'text'],
                'DateEmbauche' => ['label' => "Date d'embauche", 'type' => 'date'],
                'CodeService' => ['label' => 'Service', 'type' => 'select', 'options' => 'SERVICE:CodeService:LibelleService'],
            ],
            'columns' => ['MatriculePersonnel', 'NomPersonnel', 'PrenomPersonnel', 'EmailPersonnel', 'CodeService'],
        ],
        'projets' => [
            'table' => 'PROJET',
            'pk' => 'IdProjet',
            'title' => 'Projets',
            'singular' => 'projet',
            'icon' => 'fa-solid fa-diagram-project',
            'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET],
            'search' => ['TitreProjet', 'DescriptionProjet'],
            'fields' => [
                'TitreProjet' => ['label' => 'Titre', 'type' => 'text', 'required' => true],
                'DescriptionProjet' => ['label' => 'Description', 'type' => 'textarea'],
                'CoutProjet' => ['label' => 'Cout', 'type' => 'number', 'step' => '100'],
                'DateDebutProjet' => ['label' => 'Date de debut', 'type' => 'date'],
                'DateFinProjet' => ['label' => 'Date de fin', 'type' => 'date'],
                'EtatProjet' => ['label' => 'Etat', 'type' => 'select_static', 'options' => [1 => 'En cours', 2 => 'Termine', 3 => 'Annule']],
                'IdClient' => ['label' => 'Client', 'type' => 'select', 'options' => 'CLIENT:IdClient:NomClient', 'required' => true],
                'IdTypeProjet' => ['label' => 'Type', 'type' => 'select', 'options' => 'TYPEPROJET:IdTypeProjet:LibelleTypeProjet', 'required' => true],
                'IdChefProjet' => ['label' => 'Chef de projet', 'type' => 'select', 'options' => 'CHEF_PROJET:IdChefProjet:NomChefProjet,PrenomChefProjet'],
            ],
            'columns' => ['TitreProjet', 'IdClient', 'IdTypeProjet', 'IdChefProjet', 'CoutProjet', 'EtatProjet'],
        ],
        'taches' => [
            'table' => 'TACHE',
            'pk' => 'IdTache',
            'title' => 'Taches',
            'singular' => 'tache',
            'icon' => 'fa-solid fa-list-check',
            'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE, ROLE_PERSONNEL],
            'search' => ['LibelleTache', 'DescriptionTache'],
            'fields' => [
                'LibelleTache' => ['label' => 'Libelle', 'type' => 'text', 'required' => true],
                'DescriptionTache' => ['label' => 'Description', 'type' => 'textarea'],
                'DateEnregTache' => ['label' => "Date d'enregistrement", 'type' => 'date'],
                'DateDebutTache' => ['label' => 'Date de debut', 'type' => 'date'],
                'DateFinTache' => ['label' => 'Date de fin', 'type' => 'date'],
                'EtatTache' => ['label' => 'Etat', 'type' => 'select_static', 'options' => [1 => 'A faire', 2 => 'En cours', 3 => 'Terminee', 4 => 'Annulee']],
                'IdProjet' => ['label' => 'Projet', 'type' => 'select', 'options' => 'PROJET:IdProjet:TitreProjet', 'required' => true],
                'CodeService' => ['label' => 'Service responsable', 'type' => 'select', 'options' => 'SERVICE:CodeService:LibelleService'],
            ],
            'columns' => ['LibelleTache', 'IdProjet', 'CodeService', 'DateDebutTache', 'DateFinTache', 'EtatTache'],
        ],
        'reglements' => [
            'table' => 'REGLEMENT',
            'pk' => 'IdReglement',
            'title' => 'Reglements',
            'singular' => 'reglement',
            'icon' => 'fa-solid fa-money-bill-wave',
            'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET],
            'search' => ['ModePaiementReglement', 'ReferenceReglement'],
            'fields' => [
                'IdProjet' => ['label' => 'Projet', 'type' => 'select', 'options' => 'PROJET:IdProjet:TitreProjet', 'required' => true],
                'DateReglement' => ['label' => 'Date', 'type' => 'date', 'required' => true],
                'HeureReglement' => ['label' => 'Heure', 'type' => 'time'],
                'MontantReglement' => ['label' => 'Montant', 'type' => 'number', 'step' => '100', 'required' => true],
                'ModePaiementReglement' => ['label' => 'Mode de paiement', 'type' => 'select_static', 'options' => ['Especes' => 'Especes', 'Cheque' => 'Cheque', 'Carte bancaire' => 'Carte bancaire', 'Virement' => 'Virement', 'Mobile Money' => 'Mobile Money', 'Autre' => 'Autre']],
                'ReferenceReglement' => ['label' => 'Reference', 'type' => 'text'],
                'CommentaireReglement' => ['label' => 'Commentaire', 'type' => 'textarea'],
            ],
            'columns' => ['IdProjet', 'DateReglement', 'MontantReglement', 'ModePaiementReglement', 'ReferenceReglement'],
        ],
        'affectations' => [
            'table' => 'AFFECTATION',
            'pk' => 'IdAffectation',
            'title' => 'Affectations',
            'singular' => 'affectation',
            'icon' => 'fa-solid fa-user-check',
            'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE],
            'search' => ['FonctionAffectation'],
            'fields' => [
                'IdTache' => ['label' => 'Tache', 'type' => 'select', 'options' => 'TACHE:IdTache:LibelleTache', 'required' => true],
                'MatriculePersonnel' => ['label' => 'Personnel', 'type' => 'select', 'options' => 'PERSONNEL:MatriculePersonnel:NomPersonnel,PrenomPersonnel', 'required' => true],
                'FonctionAffectation' => ['label' => 'Fonction', 'type' => 'text'],
                'DateAffectation' => ['label' => 'Date', 'type' => 'date'],
                'HeureAffectation' => ['label' => 'Heure', 'type' => 'time'],
            ],
            'columns' => ['IdTache', 'MatriculePersonnel', 'FonctionAffectation', 'DateAffectation'],
        ],
        'utilisateurs' => [
            'table' => 'UTILISATEUR',
            'pk' => 'IdUtilisateur',
            'title' => 'Utilisateurs',
            'singular' => 'utilisateur',
            'icon' => 'fa-solid fa-users-gear',
            'roles' => [ROLE_ADMIN],
            'search' => ['NomUtilisateur', 'EmailUtilisateur'],
            'fields' => [
                'NomUtilisateur' => ['label' => "Nom d'utilisateur", 'type' => 'text', 'required' => true],
                'EmailUtilisateur' => ['label' => 'Email', 'type' => 'email'],
                'MotDePasse' => ['label' => 'Mot de passe', 'type' => 'password'],
                'Role' => ['label' => 'Role', 'type' => 'select_static', 'options' => [ROLE_ADMIN => 'Administrateur', ROLE_CHEF_PROJET => 'Chef de projet', ROLE_CHEF_SERVICE => 'Chef de service', ROLE_PERSONNEL => 'Personnel']],
            ],
            'columns' => ['NomUtilisateur', 'EmailUtilisateur', 'Role', 'DerniereConnexion'],
        ],
    ];
}

function get_entity(string $module): array
{
    $entities = entity_definitions();
    if (!isset($entities[$module])) {
        http_response_code(404);
        exit('Module introuvable.');
    }
    $entity = $entities[$module];
    $entity['module'] = $module;
    return $entity;
}
