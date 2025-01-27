<?= $this->extend('layouts/base'); ?>
<?= $this->section('title'); ?>
Emprunts en cours
<?= $this->endSection(); ?>
<?= $this->section('pageTitle'); ?>
Tous les emprunts en cours <a href="<?= base_url('emprunts/encours') ?>" class="btn btn-primary btn-sm"><i
        class="fa fa-refresh"></i></a>
<?= $this->endSection(); ?>
<?= $this->section('addcss'); ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?> ">
<?= $this->endSection(); ?>
<?= $this->section('breadcrumb'); ?>
<li><a href="<?= base_url('dashboard') ?>">Accueil</a></li>
<li><i class="fa fa-angle-right"></i> tous les emprunts en cours</li>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="info-box">
    <div class="col-md-6">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>

    <h4 class="text-black">Liste(<?= count($emprunts); ?>)
    </h4>
    <hr />
    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
        <!-- Vos cartes et contenu pour les administrateurs -->
    <?php endif; ?>

    <div class="table-responsive">
        <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
            <thead>
                <tr>
                    <th>ID #</th>
                    <th>Nom complet<span style="font-size: smaller;">(Rôle)</span></th>
                    <th>Matricule</th>
                    <th>Nom du livre</th>
                    <th>Date d'emprunt</th>
                    <th>Délai de retour</th>
                    <th>Date de retour</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emprunts as $key => $emprunt): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
                            <span style="font-size: smaller;" class="btn btn-rounded btn-success btn-sm">
                                <?= mb_strtoupper($emprunt->nom, 'UTF-8') ?>-<?= mb_strtoupper($emprunt->prenom, 'UTF-8') ?>(<span
                                    style="font-size: smaller;">
                                    <?php
                                    $role = mb_strtoupper($emprunt->role, 'UTF-8');
                                    $civilite = $emprunt->civilite;

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
                                </span>)
                            </span>
                        </td>
                        <td>
                            <span style="font-size: smaller;" class="btn btn-rounded btn-success btn-sm">
                                <?= mb_strtoupper($emprunt->matricule, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <span>
                                <?= mb_strtoupper($emprunt->nom_livre, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <span style="font-size: smaller;" class="btn btn-rounded btn-primary btn-sm">
                                <?= date('d/m/Y à H:i', strtotime($emprunt->date_emprunt)) ?> </span>
                        </td>

                        <td>
                            <?php if (empty($emprunt->date_retour)): ?>
                                <?php $delaiLivre = date('d/m/Y', strtotime($emprunt->delai_retour)); ?>
                                <span class="btn btn-rounded btn-danger btn-sm">
                                    <?= $delaiLivre ?>
                                </span>
                                <?php if (strtotime($emprunt->delai_retour) < strtotime(date('d-m-Y'))): ?>
                                    <br>
                                    <span class="btn btn-rounded btn-danger btn-sm">
                                        Délai expiré
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (empty($emprunt->date_retour)): ?>
                                <span class="btn btn-rounded btn-danger btn-sm">
                                    Livre non retourné
                                </span>
                            <?php else: ?>
                                <span class="btn btn-rounded btn-primary btn-sm">
                                    <?= date('d/m/Y à H:i', strtotime($emprunt->date_retour)) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#" class="supelm" data-id="<?= $emprunt->id ?>"><i class="fa fa-trash"></i></a>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->section('addjs'); ?>
<!-- SweetAlert -->
<script src="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.min.js') ?>"></script>
<script>
    $('body').on('click', '.supelm', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var currentTr = $(this).closest("tr");
        swal({
            title: 'Êtes-vous sûr ?',
            text: 'Supprimer cet emprunt !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "<?= base_url('emprunts/delete/') ?>" + id,
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
<?= $this->endSection(); ?>
<?= $this->endSection(); ?>