<div class="row">
  <div class="col-lg-12">
    <div class="info-box">
      <strong><i class="fa fa-book margin-r-10"></i> NOM DU LIVRE:</strong>
      <span class="pull-right text-primary"><?= mb_strtoupper($livre->nom_livre, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-tags margin-r-5"></i> CATEGORIE:</strong>
      <span class="pull-right badge badge-info"><?= mb_strtoupper($livre->nom_categorie, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-user margin-r-5"></i> AUTEUR:</strong>
      <span class="pull-right badge badge-warning"><?= mb_strtoupper($livre->nom_auteur, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-barcode margin-r-5"></i> ISBN:</strong>
      <span class="pull-right badge badge-warning"><?= $livre->isbn ?> </span>
      <hr>
      <strong><i class="fa fa-th-large margin-r-5"></i> RANGEE:</strong>
      <span class="pull-right badge badge-warning"><?= $livre->nom_ranger ?> </span>
      <hr>
      <strong><i class="fa fa-archive margin-r-5"></i> CASIER:</strong>
      <span class="pull-right badge badge-warning"><?= $livre->nom_casier ?> </span>
      <hr>
      <strong><i class="fa fa-file-text margin-r-5"></i> NOMBRE DE PAGES:</strong>
      <span class="pull-right badge badge-warning"><?= $livre->nbre_page ?> </span>
      <hr>
      <strong><i class="fa fa-sort-numeric-asc margin-r-5"></i> QUANTITE:</strong>
      <span class="pull-right badge badge-warning"><?= $livre->quantite ?> </span>
      <hr>
      <strong><i class="fa fa-database margin-r-5"></i> STOCK:</strong>
      <span>
        <?php if ($livreModel->getQteStock($livre->id) <= 0): ?>
          <span class="pull-right badge badge-danger">Stock épuisé</span>
        <?php else: ?>
          <span class="pull-right badge badge-success"><?= $livreModel->getQteStock($livre->id) ?> en stock</span>
        <?php endif; ?>
      </span>
    </div>
    <!-- /.box-body -->
  </div>
</div>