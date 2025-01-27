<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Recommandations
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Recommandations <a href="<?= base_url('recommandations') ?>" class="btn btn-rounded btn-primary btn-sm"><i
        class="fa fa-refresh"></i></a>
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> recommandation</li>
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
        Liste (<?php echo count($recommandations); ?>)
        <?php if (in_array(session()->get('role'), ['PROFESSEUR', 'ETUDIANT'])): ?>
            <a href="<?= base_url('recommandations/create') ?>" class="btn btn-rounded btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i> Nouveau
            </a>
        <?php endif; ?>

        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
            <a href="<?= base_url('recommandations/exportFiltered/' . $start_date . '/' . $end_date) ?>"
                class="btn btn-rounded btn-success btn-sm <?php if (empty($recommandations))
                    echo 'disabled'; ?>">
                <i class="fa fa-file-excel-o"></i> Exporter en excel
            </a>

        <?php endif; ?>
    </h4>
    <hr />
    <div class="table-responsive">
        <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
            <thead>
                <tr>
                    <th>ID #</th>
                    <th>Livres recommandés</th>
                    <th>Auteurs recommandés</th>
                    <th>Description</th>
                    <th>Recommandé par</th>
                    <th>Rôle</th>
                    <th>Date de création</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recommandations as $key => $recommandation): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
                            <?= mb_strtoupper($recommandation->nom_livre, 'UTF-8') ?>
                        </td>
                        <td><span
                                class="btn btn-rounded btn-primary btn-sm"><?= mb_strtoupper($recommandation->nom_auteur, 'UTF-8') ?></span>
                        </td>
                        <td>
                            <?= substr($recommandation->description, 0, 100) ?>
                            <?php if (strlen($recommandation->description) > 100): ?>
                                <a href="#" class="view-recommandation" data-toggle="modal" data-target="#recommandationModal"
                                    data-id="<?= $recommandation->id ?>"><span
                                        class="btn btn-rounded btn-primary btn-sm">...voir plus</span></a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm">
                                <?= mb_strtoupper($recommandation->prenom, 'UTF-8') . ' ' . mb_strtoupper($recommandation->nom, 'UTF-8'); ?>
                            </span>

                        </td>
                        <td>
                            <span class="btn btn-rounded btn-success btn-sm">
                                <?= mb_strtoupper($recommandation->role, 'UTF-8') ?>
                            </span>
                        </td>

                        <td><span class="btn btn-rounded btn-primary btn-sm">
                                <?= date('d/m/Y à H:i', strtotime($recommandation->created_at)) ?>
                            </span>
                        </td>
                        <td>
                            <a href="#" class="view-recommandation" data-toggle="modal" data-target="#recommandationModal"
                                data-id="<?= $recommandation->id ?>"><i class="fa fa-eye"></i></a>
                            <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'PROFESSEUR', 'ETUDIANT'])): ?>
                                <a href="<?= base_url('recommandations/edit/' . $recommandation->id) ?>"><i
                                        class="fa fa-edit"></i></a>
                                </a>
                                <a href="#" class="supelm" data-id="<?= $recommandation->id ?>"><i class="fa fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
<div class="modal fade" id="recommandationModal" tabindex="-1" role="dialog" aria-labelledby="recommandationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recommandationModalModalLabel">Détail sur la recommandation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="recommandationDetails">
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
    (function () {
        $(document).ready(function () {
            // Utiliser un délégué d'événements pour les boutons "Détails"
            $(document).on('click', '.view-recommandation', function (e) {
                e.preventDefault();
                var recommandationId = $(this).data('id');
                $.ajax({
                    url: "<?= base_url('recommandations/details/') ?>" + recommandationId,
                    method: "GET",
                    dataType: "html",
                    success: function (response) {
                        $('#recommandationDetails').html(response);
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
            text: 'Supprimer cette recommandation !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false
        }, function () {
            $.ajax({
                url: "<?= base_url('recommandations/delete/') ?>" + id,
                cache: false,
                async: true
            }).done(function (result) {

                if (result = "1") {
                    $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function () {
                        $(this).remove();
                    });
                    swal('Supprimé!', 'L\'element  a été supprimé avec succès.', 'success');
                    location.reload();
                } else {
                    swal('Impossible de supprimer. Objet lié !', 'Erreur de suppression', 'error');
                }
            });
        });

    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>