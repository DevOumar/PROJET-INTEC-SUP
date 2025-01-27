<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Recherche
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Résultats de la recherche
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/dist/plugins/datatables/css/dataTables.bootstrap.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('livres') ?>">Livres</a></li>
<li><i class="fa fa-angle-right"></i> Nouveau</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="info-box">
  <div class="table-responsive">
    <?php if (count($livres) > 0): ?>
      <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
        <thead>
          <thead>
            <tr>
              <th>ID #</th>
              <th>Nom du livre</th>
              <th>Catégories</th>
              <th>Auteurs</th>
              <th>ISBN</th>
              <th>Rangées</th>
              <th>Casiers</th>
              <th>Nbre pages</th>
              <th>Quantité</th>
              <th>Stock</th>
              <th>Date de création</th>
              <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
              <th>Action</th>
              <?php endif; ?>
            </tr>
          </thead>
        <tbody>
          <?php foreach ($livres as $key => $livre): ?>
            <tr>
              <td><?= $key + 1 ?></td>
              <td><?php echo mb_strtoupper($livre->nom_livre, 'UTF-8'); ?></td>
              <td><span class="btn btn-rounded btn-primary btn-sm"><?php echo $livre->nom_categorie; ?></span></td>
              <td><?php echo mb_strtoupper($livre->nom_auteur, 'UTF-8'); ?></td>
              <td><span class="btn btn-rounded btn-warning btn-sm"><?php echo $livre->isbn; ?></span></td>
              <td><span class="btn btn-rounded btn-warning btn-sm"><?php echo $livre->nom_ranger; ?></span></td>
              <td><span class="btn btn-rounded btn-warning btn-sm"><?php echo $livre->nom_casier; ?></span></td>
              <td><span class="btn btn-rounded btn-warning btn-sm"><?php echo $livre->nbre_page; ?></span></td>
              <td><span class="btn btn-rounded btn-warning btn-sm"><?php echo $livre->quantite; ?></span></td>
              <td>
                <?php if ($livre->qte_stock <= 0): ?>
                  <span class="btn btn-rounded btn-danger btn-sm">Stock épuisé</span>
                <?php else: ?>
                  <span class="btn btn-rounded btn-success btn-sm"><?= $livre->qte_stock ?> en stock</span>
                <?php endif; ?>
              </td>
              <td><?= date('d/m/Y \à H:i', strtotime($livre->created_at)); ?></span></td>
              <td>
                            <?php if (!empty($livre->fichier_livre)): ?>
                                <a href="<?= base_url('public/files/livres_upload/' . $livre->fichier_livre) ?>"
                                    target="_blank">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                                <a href="<?= base_url('livres/edit/' . $livre->id) ?>"><i class="fa fa-edit"></i></a>
                                <a href="#" class="supelm" data-id="<?= $livre->id ?>"><i class="fa fa-trash"></i></a>
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

<?= $this->section('addjs') ?>
<!-- DataTable -->
<script src="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('public/dist/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('public/dist/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
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

          if (result == "1") {
            $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function () { $(this).remove(); });
            swal(
              'Supprimé!',
              'L\'element  a été supprimée avec succès.',
              'success'
            );

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