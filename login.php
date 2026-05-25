<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

if (is_logged_in()) {
    redirect(app_url('dashboard/index.php'));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    $user = db_one(
        'SELECT * FROM UTILISATEUR WHERE NomUtilisateur = ? OR EmailUtilisateur = ? LIMIT 1',
        [$username, $username]
    );

    if ($user && password_verify($password, $user['MotDePasse'])) {
        login_user($user);
        redirect(app_url('dashboard/index.php'));
    }

    $error = "Identifiants incorrects. Verifiez votre nom d'utilisateur et votre mot de passe.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - <?= e(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= e(app_url('public/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body class="login-page">
    <main class="login-panel">
        <div class="w-100">
            <div class="brand text-dark mb-4">
                <span class="brand-icon"><i class="fa-solid fa-layer-group"></i></span>
                <span>Gestion Projets</span>
            </div>
            <p class="eyebrow">Connexion securisee</p>
            <h1 class="fw-bold mb-2">Pilotez vos projets avec precision.</h1>
            <p class="text-muted mb-4">Accedez aux clients, projets, taches, reglements et affectations selon votre role.</p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" class="vstack gap-3">
                <div>
                    <label class="form-label" for="username">Nom d'utilisateur ou email</label>
                    <input class="form-control form-control-lg" id="username" name="username" required autocomplete="username">
                </div>
                <div>
                    <label class="form-label" for="password">Mot de passe</label>
                    <input class="form-control form-control-lg" id="password" type="password" name="password" required autocomplete="current-password">
                </div>
                <button class="btn btn-primary btn-lg" type="submit">
                    <i class="fa-solid fa-right-to-bracket"></i> Se connecter
                </button>
            </form>
            <div class="small text-muted mt-4">
                Comptes de test: admin/admin123, chefprojet/chefprojet123, chefservice/chefservice123, personnel/personnel123.
            </div>
        </div>
    </main>
    <aside class="login-art">
        <div>
            <p class="eyebrow text-white-50">Application professionnelle</p>
            <h2 class="display-6 fw-bold">Suivi centralise des projets, equipes et paiements.</h2>
        </div>
    </aside>
</body>
</html>
