<div class="row">
    <div class="col-lg-10">
        <div class="info-box">
            <strong><i class="fa fa-id-card margin-r-5"></i> NOM COMPLET:</strong>
            <span class="pull-right text-primary"><?= mb_strtoupper($reservation->nom, 'UTF-8') ?>-<?= mb_strtoupper($reservation->prenom, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-id-badge margin-r-5"></i> MATRICULE:</strong>
            <span class="pull-right badge badge-info"><?= mb_strtoupper($reservation->matricule, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-phone margin-r-5"></i> TELEPHONE:</strong>
            <span class="pull-right badge badge-warning"><?= $reservation->telephone ?></span>
            <hr>
            <strong><i class="fa fa-envelope margin-r-5"></i> EMAIL:</strong>
            <span class="pull-right badge badge-warning"><?= mb_strtoupper($reservation->email, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-book margin-r-5"></i> NOM DU LIVRE:</strong>
            <span class="pull-right badge badge-warning"><?= mb_strtoupper($reservation->nom_livre, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-user margin-r-5"></i> NOM DE L'AUTEUR:</strong>
            <span class="pull-right badge badge-warning"><?= mb_strtoupper($reservation->nom_auteur, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-user-tag margin-r-5"></i> RÔLE:</strong>
            <span class="pull-right badge badge-warning">
                <?php
                $role = mb_strtoupper($reservation->role, 'UTF-8');
                $civilite = $reservation->civilite;

                if ($role === 'ADMINISTRATEUR') {
                    echo $civilite === 'Mme' ? 'ADMINISTRATRICE' : 'ADMINISTRATEUR';
                } elseif ($role === 'ETUDIANT') {
                    echo $civilite === 'Mme' ? 'ETUDIANTE' : 'ETUDIANT';
                } elseif ($role === 'PROFESSEUR') {
                    echo $civilite === 'Mme' ? 'PROFESSEURE' : 'PROFESSEUR';
                } else {
                    echo $reservation->role; // Fallback au cas où le rôle ne correspondrait pas aux options ci-dessus
                }
                ?>
            </span>
            <hr>
            <strong><i class="fa fa-calendar-alt margin-r-5"></i> DATE DE LA RESERVATION:</strong>
            <span class="pull-right badge badge-warning"><?= date('d/m/Y à H:i', strtotime($reservation->date_reservation)) ?></span>
            <hr>
            <strong><i class="fa fa-calendar-check margin-r-5"></i> DATE DE STATUT:</strong>
            <span class="pull-right badge badge-warning">
                <?php
                if (!empty($reservation->date_status) && $reservation->date_status != '0000-00-00') {
                    echo date('d/m/Y à H:i', strtotime($reservation->date_status));
                } else {
                    echo 'En attente';
                }
                ?>
            </span>
            <hr>
            <strong><i class="fa fa-spinner margin-r-5"></i> STATUT:</strong>
            <span class="pull-right badge badge-warning">
                <?php
                switch ($reservation->status) {
                    case 0:
                        echo 'En attente';
                        break;
                    case 1:
                        echo 'Accepté';
                        break;
                    case 2:
                        echo 'Refusé';
                        break;
                    default:
                        echo 'Statut inconnu';
                }
                ?>
            </span>
        </div>
    </div>
</div>
