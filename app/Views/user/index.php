<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Étudiants
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Espace Étudiants <a href="<?= base_url('user/index') ?>" class="btn btn-rounded btn-primary btn-sm"><i
        class="fa fa-refresh"></i></a>
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<style>
    .copy-input {
        font-size: 14px;
        width: 100px;
        display: inline-block;
        padding: 0 5px;
        background: transparent;
        border: none;
        color: #555;
        outline: none;
        text-align: center;
        font-weight: bold;
    }
</style>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> espace Étudiants</li>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="info-box">
    <div class="col-md-6">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>

    <h4 class="text-black">Liste(<?php echo count($users); ?>)<?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>

            <a href="<?= base_url('user/create') ?>" class="btn btn-rounded btn-primary btn-sm"><i
                    class="fa fa-plus-circle"></i> Nouveau</a><?php endif; ?>
    </h4>
    <hr />

    <div class="table-responsive">
        <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
            <thead>
                <tr>
                    <th>ID #</th>
                    <th>Nom complet<span style="font-size: smaller;">(Matricule)</span></th>
                    <th>E-mail <i class="fa fa-check-circle text-success"></i></th>
                    <th>Cycle-Filière</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Connecté depuis</th>
                    <th>Adresse IP</th>
                    <th>Dernière connexion</th>
                    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($users as $key => $user): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
    <span class="btn btn-rounded btn-success btn-sm">
        <span class="text-capitalize font-weight-500 text-dark">
            <?= mb_strtoupper($user->nom, 'UTF-8') . ' ' . mb_strtoupper($user->prenom, 'UTF-8'); ?>
            (<input type="text" class="form-control copy-input" value="<?= $user->matricule ?>" readonly>
            )
        </span>
    </span>
</td>
                        <td>
                            <span class="btn btn-rounded btn-success btn-sm">
                                <?= $user->email; ?>
                            </span>
                        </td>
                        <td>
                            <span class="btn btn-rounded btn-success btn-sm">
                                <?= $user->nom_cycle . ' - ' . $user->nom_filiere; ?>
                            </span>
                        </td>
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm">
                                <?= $user->telephone; ?></span>
                        </td>
                        <td>
    <span class="btn btn-rounded btn-primary btn-sm">
        <?php
        $role = mb_strtoupper($user->role, 'UTF-8');
        $civilite = $user->civilite;

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
</td>

                        <td>
                            <?php if ($user->status): ?>
                                <a href="#" class="changeStat" data-id="<?= $user->id ?>"><span
                                        class="btn btn-rounded btn-primary btn-sm">Activé</span></a>
                            <?php else: ?>
                                <a href="#" class="changeStat" data-id="<?= $user->id ?>"><span
                                        class="btn btn-rounded btn-danger btn-sm">Désactivé</span></a>
                            <?php endif; ?>
                        </td>

                        <!-- Affichage de l'adresse IP et du pays -->
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm">
                                <?php echo $user->last_country ?: 'Non disponible'; ?>
                            </span>
                        </td>
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm">
                                <?php echo $user->last_ip ?: 'Non disponible'; ?>
                            </span>
                        </td>

                        <td>
                            <?php if ($user->datelastlogin): ?>
                                <span class="btn btn-rounded btn-primary btn-sm">
                                    <?= date('d/m/Y - H:i', strtotime($user->datelastlogin)) ?>
                                </span>
                            <?php else: ?>
                                <span class="btn btn-rounded btn-danger btn-sm">
                                    Aucune activité récente
                                <?php endif; ?>
                            </span>
                        </td>

                        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                            <td>
                                <a href="<?= base_url('user/edit/' . $user->id) ?>"><i class="fa fa-edit"></i></a>
                                <a href="#" class="supelm" data-id="<?= $user->id ?>"><i class="fa fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<?= $this->section('addjs') ?>
<!-- SweetAlert -->
<script src="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.min.js') ?>"></script>
<script>
    $('body').on('click', '.supelm', function (e) {

        e.preventDefault();
        var id = $(this).data('id');
        var currentTr = $(this).closest("tr");
        swal({
            title: 'Êtes-vous sûr ?',
            text: 'Supprimer cet étudiant !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "<?= base_url('auth/delete/') ?>" + id,
                // type: 'post',
                cache: false,
                async: true
            })
                .done(function (result) {

                    if (result = "1") {
                        $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function () { $(this).remove(); });
                        swal(
                            'Supprimé!',
                            'L\'element  a été supprimé avec succès.',
                            'success'
                        );
                        location.reload();
                    }
                    else {
                        swal(
                            'Impossible de supprimer. Objet lié !',
                            'Erreur de suppression',
                            'error'
                        );
                    }
                });
        });

    });
</script>

<script>
    $('body').on('click', '.changeStat', function (e) {

        e.preventDefault();
        var id = $(this).data('id');
        var currentTr = $(this).closest("tr");
        swal({
            title: 'Êtes-vous sûr ?',
            text: 'Vous êtes sur le point de désactiver votre compte définitivement ! Cette action est irréversible.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "<?= base_url('auth/updateStatus/') ?>" + id,
                // type: 'post',
                cache: false,
                async: true
            })
                .done(function (result) {

                    if (result = "1") {
                        $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function () { $(this).success(); });
                        swal(
                            'Changé!',
                            'L\'element  a été changé avec succès.',
                            'success'
                        );
                        location.reload();
                    }
                    else {
                        swal(
                            'Impossible de changer. Objet lié !',
                            'Erreur de changement',
                            'error'
                        );
                    }
                });
        });

    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>