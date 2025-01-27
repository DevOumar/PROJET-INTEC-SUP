<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture de réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            /* Taille de la police ajustée */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            /* Taille de la police ajustée */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px;
            /* Ajustement de l'espacement */
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2,
        p {
            font-size: 10pt;
            /* Taille de la police ajustée */
        }
    </style>
</head>

<body>
    <h2>Facture de réservation</h2>
    <p>Liste des réservations effectuées par l'utilisateur au cours des dernières 24 heures :</p>
    <table>
        <thead>
            <tr>
                <th>Nom complet</th>
                <th>Livre</th>
                <th>Nom de l'auteur</th>
                <th>Date de réservation</th>
                <th>Date de statut</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= mb_strtoupper($reservation->nom . ' ' . $reservation->prenom, 'UTF-8') ?></td>
                    <td><?= mb_strtoupper($reservation->nom_livre, 'UTF-8') ?></td>
                    <td><?= mb_strtoupper($reservation->nom_auteur, 'UTF-8') ?></td>
                    <td><?= date('d/m/Y \à H:i', strtotime($reservation->date_reservation)) ?></td>
                    <td> <?php
                    if (!empty($reservation->date_status) && $reservation->date_status != '0000-00-00') {
                        echo date('d/m/Y \à H:i', strtotime($reservation->date_status));
                    } else {
                        echo 'En attente';
                    }
                    ?></td>
                    <td>
                        <?php
                        switch ($reservation->status) {
                            case 0:
                                echo '<span class="label label-default">En attente</span>';
                                break;
                            case 1:
                                echo '<span class="label label-success">Accepté</span>';
                                break;
                            case 2:
                                echo '<span class="label label-danger">Refusé</span>';
                                break;
                            default:
                                echo '<span class="label label-default">Statut inconnu</span>';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Merci pour votre réservation!</p>
</body>

</html>