<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Mémoires
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
Mémoires <a href="<?= base_url('memoires') ?>" class="btn btn-rounded btn-primary btn-sm"><i class="fa fa-refresh"></i></a>
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> mémoire</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card-header d-flex justify-content-between align-items-center">
    <h6></h6>
    <div>
        <form class="form-inline" style="margin-right: 20px">
            <?= csrf_field() ?>
            <div class="form-group inline" style="margin-right: 15px;">
                <label for="">Du :&nbsp;</label>
                <input type="date" value="<?php echo isset($start_date) ? $start_date : date('Y-m-d'); ?>"
                    name="start_date" id="start_date" class="form-control" placeholder="" aria-describedby="helpId">
            </div>
            <div class="form-group inline" style="margin-right: 15px;">
                <label for="">Au :&nbsp;</label>
                <input type="date" value="<?php echo isset($end_date) ? $end_date : date('Y-m-d'); ?>" name="end_date"
                    id="end_date" class="form-control" placeholder="" aria-describedby="helpId">
            </div>
            <button class="btn btn-rounded btn-primary btn-sm" type="submit" role="button">
                <i class="fa fa-filter" aria-hidden="true"></i>
            </button>
        </form>
    </div>
</div>

<div class="info-box">
    <div class="col-md-6">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>

    <h4 class="text-black">
        Liste(<?php echo count($memoires); ?>)<?php if (session()->get('role') === 'ADMINISTRATEUR'): ?> <a
                href="<?= base_url('memoires/create') ?>" class="btn btn-rounded btn-primary btn-sm"><i
                    class="fa fa-plus-circle"></i>
                Nouveau</a><a href="<?= base_url('memoires/exportFiltered/' . $start_date . '/' . $end_date) ?>"
   class="btn btn-rounded btn-success btn-sm <?php if (empty($memoires)) echo 'disabled'; ?>">
   <i class="fa fa-file-excel-o"></i> Exporter en excel
</a>

        <?php endif; ?>
    </h4>
    <hr />

    <div class="table-responsive">
        <?php if (isset($memoires) && count($memoires) > 0): ?>
            <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
                <thead>
                    <tr>
                        <th>ID #</th>
                        <th>Thème du mémoire</th>
                        <th>Catégories</th>
                        <th>Auteurs</th>
                        <th>Cycle-Filière</th>
                        <th>Rangées</th>
                        <th>Casiers</th>
                        <th>Nbre pages</th>
                        <th>Date de soutenance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($memoires as $key => $memoire): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= mb_strtoupper($memoire->nom_memoire, 'UTF-8') ?></td>
                            <td><span
                                    class="btn btn-rounded btn-primary btn-sm"><?= mb_strtoupper($memoire->nom_categorie, 'UTF-8') ?></span>
                            </td>
                            <td><?= mb_strtoupper($memoire->nom_auteur) ?></td>
                            <td><span
                                    class="btn btn-rounded btn-primary btn-sm"><?= mb_strtoupper($memoire->nom_cycle . '-' . $memoire->nom_filiere, 'UTF-8') ?></span>
                            </td>
                            <td><span class="btn btn-rounded btn-warning btn-sm"><?= mb_strtoupper($memoire->nom_ranger, 'UTF-8') ?></span>
                            </td>
                            <td><span class="btn btn-rounded btn-warning btn-sm"><?= mb_strtoupper($memoire->nom_casier, 'UTF-8') ?></span>
                            </td>
                            <td><span class="btn btn-rounded btn-warning btn-sm"><?= mb_strtoupper($memoire->nbre_page, 'UTF-8') ?></span>
                            </td>
                            <td><span
                                    class="btn btn-rounded btn-warning btn-sm"><?= date('d/m/Y', strtotime($memoire->date_soutenance)) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($memoire->fichier_memoire)): ?>
                                    <a href="<?= base_url('public/files/memoires_upload/' . $memoire->fichier_memoire) ?>"
                                        target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="#" class="view-memoire" data-toggle="modal" data-target="#memoireModal"
                                    data-id="<?= $memoire->id ?>"><i class="fa fa-eye"></i></a>
                                <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                                    <a href="<?= base_url('memoires/edit/' . $memoire->id) ?>"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="supelm" data-id="<?= $memoire->id ?>"><i class="fa fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h4 class="alert alert-info text-center"> Aucun élément trouvé !</h4>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="memoireModal" tabindex="-1" role="dialog" aria-labelledby="memoireModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="memoireModalLabel">Détails du mémoire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="memoireDetails">
                <!-- Les détails du mémoire seront chargés ici -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


<?= $this->section('addjs') ?>
<!-- SweetAlert -->
<script src="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.min.js') ?>"></script>
<script>
    (function(){
$(document).ready(function () {
    // Utiliser un délégué d'événements pour les boutons "Détails"
    $(document).on('click', '.view-memoire', function (e) {
        e.preventDefault();
        var memoireId = $(this).data('id');
        $.ajax({
            url: "<?= base_url('memoires/details/') ?>" + memoireId,
            method: "GET",
            dataType: "html",
            success: function (response) {
                $('#memoireDetails').html(response);
            }
        });
    });
});
})();
</script>

<script>
    $('body').on('click', '.supelm', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var currentTr = $(this).closest("tr");
        swal({
            title: 'Êtes-vous sûr ?',
            text: 'Supprimer ce mémoire !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "<?= base_url('memoires/delete/') ?>" + id,
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
<?= $this->endSection() ?>
<?= $this->endSection() ?>