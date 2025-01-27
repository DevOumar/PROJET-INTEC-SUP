<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Livres
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Livres <a href="<?= base_url('livres') ?>" class="btn btn-rounded btn-primary btn-sm"><i class="fa fa-refresh"></i></a>
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> Nouveau</li>
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
        Liste(Total Quantités : <?php echo $totalQuantite; ?>)<?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
            <a href="<?= base_url('livres/create') ?>" class="btn btn-rounded btn-primary btn-sm"><i
                    class="fa fa-plus-circle"></i>
                Nouveau</a>
            <a href="<?= base_url('livres/exportFiltered/' . $start_date . '/' . $end_date) ?>"
                class="btn btn-rounded btn-success btn-sm <?php if (empty($livres))
                    echo 'disabled'; ?>">
                <i class="fa fa-file-excel-o"></i> Exporter en excel
            </a>

        <?php endif; ?>
        <a href="<?= base_url('recommandations') ?>" class="btn btn-rounded btn-success btn-sm pull-right"
            title="Liste des livres recommandés par les utilisateurs."><i class="fa fa-book"></i> Recommandations</a>
    </h4>
    <hr />
    <div class="table-responsive">
        <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
            <thead>
                <tr>
                    <th>ID #</th>
                    <th>Nom du livre & Auteurs</th>
                    <th>Catégories</th>
                    <th>ISBN</th>
                    <th>Rangées</th>
                    <th>Casiers</th>
                    <th>Nbre pages</th>
                    <th>Quantité</th>
                    <th>Stock</th>
                    <th>Date de création</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livres as $key => $livre): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?php echo mb_strtoupper($livre->nom_livre, 'UTF-8'); ?>
                            <span>(<?php echo mb_strtoupper($livre->nom_auteur, 'UTF-8'); ?>)</span>
                        </td>
                        <td><span
                                class="btn btn-rounded btn-primary btn-sm"><?php echo mb_strtoupper($livre->nom_categorie, 'UTF-8'); ?></span>
                        </td>
                        <td><span
                                class="btn btn-rounded btn-warning btn-sm"><?php echo mb_strtoupper($livre->isbn, 'UTF-8'); ?></span>
                        </td>
                        <td><span
                                class="btn btn-rounded btn-warning btn-sm"><?php echo mb_strtoupper($livre->nom_ranger, 'UTF-8'); ?></span>
                        </td>
                        <td><span
                                class="btn btn-rounded btn-warning btn-sm"><?php echo mb_strtoupper($livre->nom_casier, 'UTF-8'); ?></span>
                        </td>
                        <td><span class="btn btn-rounded btn-warning btn-sm"><?php echo $livre->nbre_page; ?></span></td>
                        <td><span class="btn btn-rounded btn-warning btn-sm"><?php echo $livre->quantite; ?></span></td>
                        <td>
                            <?php if ($livre->qte_stock <= 0): ?>
                                <span class="btn btn-rounded btn-danger btn-sm">Stock épuisé</span>
                            <?php else: ?>
                                <span class="btn btn-rounded btn-success btn-sm"><?= $livre->qte_stock ?> en stock</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= date('d/m/Y à H:i', strtotime($livre->created_at)); ?>
                        </td>
                        <td>
                            <?php if (!empty($livre->fichier_livre)): ?>
                                <a href="<?= base_url('public/files/livres_upload/' . $livre->fichier_livre) ?>"
                                    target="_blank">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            <?php endif; ?>

                            <a href="#" class="view-livre" data-toggle="modal" data-target="#livreModal"
                                data-id="<?= $livre->id ?>"><i class="fa fa-eye"></i></a>

                            <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                                <a href="<?= base_url('livres/edit/' . $livre->id) ?>"><i class="fa fa-edit"></i></a>
                                <a href="#" class="supelm" data-id="<?= $livre->id ?>"><i class="fa fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
<div class="modal fade" id="livreModal" tabindex="-1" role="dialog" aria-labelledby="livreModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="livreModalLabel">Détail du Livre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="livreDetails">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('addjs') ?>
<!-- DataTable -->
<script src="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('public/dist/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('public/dist/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script>
    (function () {
        $(document).ready(function () {
            // Utiliser un délégué d'événements pour les boutons "Détails"
            $(document).on('click', '.view-livre', function (e) {
                e.preventDefault();
                var livreId = $(this).data('id');
                $.ajax({
                    url: "<?= base_url('livres/details/') ?>" + livreId,
                    method: "GET",
                    dataType: "html",
                    success: function (response) {
                        $('#livreDetails').html(response);
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
            text: 'Supprimer ce livre !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "<?= base_url('livres/delete/') ?>" + id,
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