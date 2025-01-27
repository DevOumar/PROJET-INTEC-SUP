<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .card {
            margin: 20px auto;
            max-width: 600px;
        }
        .card-body {
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 15px;
        }
        strong {
            color: #007bff;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h1>Réinitialisation du mot de passe</h1>
            <p>Salut cher(e) utilisateur(e) <strong><?= esc($nom) ?> <?= esc($prenom) ?> !</strong></p>
            <p>Il semble que vous ayez besoin d'un nouveau mot de passe. Cliquez sur le lien ci-dessous pour confirmer votre demande :</p>
            <p><a href="<?= esc($link) ?>">Confirmer ma demande.</a></p>
            <p>Vous n'êtes pas à l'origine de cette demande ? Vous rencontrez un problème sur votre compte ? Écrivez-nous à cette adresse :</p>
            <p><a href="mailto:bibliotheque.intecsup@gmail.com">bibliotheque.intecsup@gmail.com</a></p>
            <div class="footer">
                <p>À bientôt,<br>L'équipe Bibliothèque-INTEC SUP</p>
                <hr>
                <p><i>Ceci est un mail automatique, merci de ne pas y répondre.</i></p>
            </div>
        </div>
    </div>
</body>
</html>
