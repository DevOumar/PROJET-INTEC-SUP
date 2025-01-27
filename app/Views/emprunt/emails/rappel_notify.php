<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification de retard de retour</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-top: 0;
        }
        p {
            color: #666;
        }
        strong {
            color: #000;
        }
        .signature {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-style: italic;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notification de retard de retour</h1>
        <p>Salut cher(e) lecteur/lectrice <strong><?= htmlspecialchars($prenom) ?> <?= htmlspecialchars($nom) ?> !</strong></p>
        <p>
            Nous espérons que vous passez une excellente journée. Nous vous informons que vous êtes en retard de retour pour le(les) livre(s) suivant(s) :
        </p>
        <ul>
            <?php foreach ($books as $book): ?>
                <li>
                    <?= htmlspecialchars($book['nom_livre']) ?>, emprunté le <?= date('d/m/Y \à H:i', strtotime($book['date_emprunt'])) ?>. Le numéro ISBN de ce livre est <strong><?= htmlspecialchars($book['isbn']) ?></strong>.
                </li>
            <?php endforeach; ?>
        </ul>

        <p>
            Nous vous prions de bien vouloir retourner ce(s) livre(s) dès que possible afin d'éviter toute sanction éventuelle.
        </p>
        <div class="signature">
            <p>À bientôt,</p>
            <p>L'équipe de la Bibliothèque-INTEC SUP</p>
            <br>
            <hr>
            <p><i>Ceci est un mail automatique, merci de ne pas y répondre.</i></p>
        </div>
    </div>
</body>
</html>
