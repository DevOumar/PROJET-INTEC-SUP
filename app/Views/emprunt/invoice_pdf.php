<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returned Books</title>
    <style>
        /* Styles CSS pour le contenu du PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h5 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            word-wrap: break-word;
            /* Permet de diviser le texte long sur plusieurs lignes */
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <h5>Liste des Livres Retournés</h5>

    <table>
        <thead>
            <tr>
                <th>ID #</th>
                <th>Nom complet (matricule)</th>
                <th>Livre</th>
                <th>Date d'emprunt</th>
                <th>Date de retour</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($emprunts as $key => $emprunt): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= mb_strtoupper($emprunt->prenom . ' ' . $emprunt->nom, 'UTF-8') ?>(<?= $emprunt->matricule ?>)
                    </td>
                    <td><?= $emprunt->nom_livre ?></td>
                    <td><?= date('d/m/Y à H:i', strtotime($emprunt->date_emprunt)) ?></td>
                    <td><?= isset($emprunt->date_retour) ? date('d/m/Y à H:i', strtotime($emprunt->date_retour)) : '' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <footer>Facture générée automatiquement depuis le système - <?= date('d/m/Y à H:i') ?></footer>
</body>

</html>