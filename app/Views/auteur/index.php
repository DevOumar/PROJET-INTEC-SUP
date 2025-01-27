<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Auteurs
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Auteurs
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css')?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
    <li><a href="<?= base_url('dashboard')?>">Tableau de bord</a></li>
    <li><i class="fa fa-angle-right"></i> auteur</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="col-md-6">
     <?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header bg-primary d-flex justify-content-between">
                <h5 class="text-white m-b-0">Formulaire</h5>
                <a href="<?= base_url('auteurs') ?>" class="btn btn-rounded btn-info btn-sm">Recharger</a>
            </div> 
<div class="card-body">
    <form method="POST" <?php if (isset($auteur)): ?> action="<?= base_url('auteurs/index/' . $auteur->id) ?>" <?php else: ?> action="<?= base_url('auteurs') ?>" <?php endif; ?>>
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label class="control-label">Nom de l'auteur</label>
                    <input type="text" name="libelle" value="<?= isset($auteur) ? $auteur->libelle : '' ?>" class="form-control" placeholder="Nom de l'auteur">
                    <span class="fa fa-user form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <?php if (isset($auteur)): ?>
                    <button type="submit" class="btn btn-rounded btn-success btn-sm pull-right">Modifier</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-rounded btn-success btn-sm pull-right">Ajouter</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
    </div>
    </div>
</div>

<div class="col-xl-8">
    <div class="card">
        <div class="card-header bg-primary">
            <h4 class="text-white m-b-0">Liste des auteurs(<?php echo count($auteurs); ?>)</h4>
        </div>
        <div class="card-body">
            <hr>
            <div class="table-responsive">
                <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
                    <thead>
                        <tr>
                            <th>ID #</th>
                            <th>Auteurs</th>
                            <th>Date de création</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($auteurs as $key => $auteur): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                <span
                                class="btn btn-rounded btn-primary btn-sm">
                                    <?= mb_strtoupper($auteur->libelle, 'UTF-8') ?></td>
                        </span>
                                <td>
                                <span
                                class="btn btn-rounded btn-success btn-sm">
                                    <?= date('d/m/Y à H:i', strtotime($auteur->created_at)) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('auteurs/index/' . $auteur->id) ?>"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="supelm" data-id="<?= $auteur->id ?>"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>

</table>
</div>
</div>
</div>
</div>
</div>


<?= $this->section('addjs') ?>
<!-- SweetAlert --> 
<script src="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.min.js')?>"></script>
<script>
$('body').on('click', '.supelm', function (e) {

    e.preventDefault();
    var id = $(this).data('id');
    var currentTr = $(this).closest("tr");
    swal({
      title: 'Êtes-vous sûr ?',
      text: 'Supprimer cet auteur !',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Oui, valider !',
      cancelButtonText: 'Annuler',
      closeOnConfirm: false,
  }, function () {
    $.ajax({
        url: "<?= base_url('auteurs/delete/') ?>" + id,
       // type: 'post',
        cache: false,
        async: true
    })
    .done(function( result ) {

        if(result = "1"){
            $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
            swal(
                'Supprimé!',
                'L\'element  a été supprimé avec succès.',
                'success'
                );
            location.reload();
        }
        else{
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