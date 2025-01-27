<div class="row">
    <div class="col-lg-10">
        <div class="info-box">
            <strong><i class="fa fa-id-card margin-r-5"></i> NOM COMPLET:</strong>
            <span class="pull-right text-primary"><?= mb_strtoupper($emprunt->nom, 'UTF-8') ?>-<?= mb_strtoupper($emprunt->prenom, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-id-badge margin-r-5"></i> MATRICULE:</strong>
            <span class="pull-right badge badge-info"><?= mb_strtoupper($emprunt->matricule, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-envelope margin-r-5"></i> EMAIL:</strong>
            <span class="pull-right badge badge-warning"><?= mb_strtoupper($emprunt->email, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-phone margin-r-5"></i> TELEPHONE:</strong>
            <span class="pull-right badge badge-warning"><?= $emprunt->telephone ?></span>
            <hr>
            <strong><i class="fa fa-book margin-r-5"></i> NOM DU LIVRE:</strong>
            <span class="pull-right badge badge-warning"><?= mb_strtoupper($emprunt->nom_livre, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-user-tag margin-r-5"></i> RÔLE:</strong>
            <span class="pull-right badge badge-warning">
                <?php
                $role = mb_strtoupper($emprunt->role, 'UTF-8');
                $civilite = $emprunt->civilite;

                if ($role === 'ADMINISTRATEUR') {
                    echo $civilite === 'Mme' ? 'ADMINISTRATRICE' : 'ADMINISTRATEUR';
                } elseif ($role === 'ETUDIANT') {
                    echo $civilite === 'Mme' ? 'ETUDIANTE' : 'ETUDIANT';
                } elseif ($role === 'PROFESSEUR') {
                    echo $civilite === 'Mme' ? 'PROFESSEURE' : 'PROFESSEUR';
                } else {
                    echo $emprunt->role; // Fallback au cas où le rôle ne correspondrait pas aux options ci-dessus
                }
                ?>
            </span>
            <hr>
            <strong><i class="fa fa-calendar-alt margin-r-5"></i> DATE D'EMPRUNT:</strong>
            <span class="pull-right badge badge-warning"><?= date('d/m/Y à H:i', strtotime($emprunt->date_emprunt)) ?></span>
            <hr>
            <strong><i class="fa fa-calendar-times margin-r-5"></i> DELAI DE RETOUR:</strong>
            <span class="pull-right">
                <?php if (empty($emprunt->date_retour)): ?>
                    <?php $delaiLivre = date('d/m/Y', strtotime($emprunt->delai_retour)); ?>
                    <span class="btn btn-rounded btn-danger btn-sm"><?= $delaiLivre ?></span>
                    <?php if (strtotime($emprunt->delai_retour) < strtotime(date('d-m-Y'))): ?>
                        <br><span class="btn btn-rounded btn-danger btn-sm">Délai expiré</span>
                    <?php endif ?>
                <?php else: ?>
                    <span class="btn btn-rounded btn-danger btn-sm"><?= date('d/m/Y', strtotime($emprunt->delai_retour)) ?></span>
                    <span class="btn btn-rounded btn-success btn-sm">Retourné</span>
                <?php endif ?>
            </span>
            <hr>
            <strong><i class="fa fa-calendar-check margin-r-5"></i> DATE DE RETOUR:</strong>
            <span class="pull-right">
                <?php if (empty($emprunt->date_retour)): ?>
                    <span class="btn btn-rounded btn-danger btn-sm">Livre non retourné</span>
                <?php else: ?>
                    <span class="btn btn-rounded btn-success btn-sm"><?= date('d/m/Y à H:i', strtotime($emprunt->date_retour)) ?></span>
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>
