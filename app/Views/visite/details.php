<div class="row">
    <div class="col-lg-10">
        <div class="info-box">
            <strong><i class="fa fa-spinner margin-r-5"></i> MATRICULE:</strong>
            <span class="pull-right"><?= mb_strtoupper($visite->matricule, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-user margin-r-5"></i> NOM COMPLET:</strong>
            <span class="pull-right"><?= mb_strtoupper($visite->nom . '-' . $visite->prenom, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-envelope margin-r-5"></i> EMAIL:</strong>
            <span class="pull-right"><?= mb_strtoupper($visite->email, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-phone margin-r-5"></i> TELEPHONE:</strong>
            <span class="pull-right"><?= $visite->telephone ?></span>
            <hr>
            <strong><i class="fa fa-user margin-r-5"></i> ROLE:</strong>
            <span class="pull-right btn btn-rounded btn-warning btn-sm">
                <?php
                $role = mb_strtoupper($visite->role, 'UTF-8');
                $civilite = $visite->civilite;

                if ($role === 'ADMINISTRATEUR') {
                    if ($civilite === 'Mme') {
                        echo 'ADMINISTRATRICE';
                    } else {
                        echo 'ADMINISTRATEUR';
                    }
                } elseif ($role === 'ETUDIANT') {
                    if ($civilite === 'Mme') {
                        echo 'ETUDIANTE';
                    } else {
                        echo 'ETUDIANT';
                    }
                } elseif ($role === 'PROFESSEUR') {
                    if ($civilite === 'Mme') {
                        echo 'PROFESSEURE';
                    } else {
                        echo 'PROFESSEUR';
                    }
                } else {
                    echo $user->role; // Fallback au cas où le rôle ne correspondrait pas aux options ci-dessus
                }
                ?>
            </span>
            <hr>
            <strong><i class="fa fa-spinner margin-r-5"></i> MOTIF DE LA VISITE:</strong>
            <span class="pull-right btn btn-rounded btn-warning btn-sm"><?= mb_strtoupper($visite->libelle, 'UTF-8') ?></span>
            <hr>
            <hr>
            <strong><i class="fa fa-calendar margin-r-5"></i> DATE DE DEBUT:</strong>
            <span
                class="pull-right btn btn-rounded btn-warning btn-sm"><?= date('d/m/Y à H:i', strtotime($visite->date_debut)) ?></span>
            <hr>
            <strong><i class="fa fa-calendar margin-r-5"></i> DATE DE FIN:</strong>

            <?php if (empty($visite->date_fin)): ?>
                <span class="pull-right btn btn-rounded btn-danger btn-sm">EN ATTENTE</span>
            <?php else: ?>
                <span
                    class="pull-right btn btn-rounded btn-success btn-sm"><?= date('d/m/Y à H:i', strtotime($visite->date_fin)) ?>
                </span>
            <?php endif; ?>
            <hr>
            <strong><i class="fa fa-spinner margin-r-5"></i> STATUT:</strong>
            <?php if ($visite->status === 'en_cours'): ?>
                <span class="pull-right btn btn-rounded btn-danger btn-sm">EN COURS </span>
            <?php elseif ($visite->status === 'terminee'): ?>
                <span class="pull-right btn btn-rounded btn-success btn-sm">TERMINEE </span>
            <?php else: ?>
                <?= $visite->status ?>
            <?php endif; ?>
            </span>
        </div>
    </div>
</div>

<!-- /.box-body -->