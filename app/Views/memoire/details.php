<div class="row">
  <div class="col-lg-10">
    <div class="info-box">
      <strong><i class="fa fa-book margin-r-5"></i> NOM DU MEMOIRE:</strong>
      <span class="pull-right text-primary"><?= mb_strtoupper($memoire->nom_memoire, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-tags margin-r-5"></i> CATEGORIE:</strong>
      <span class="pull-right badge badge-info"><?= mb_strtoupper($memoire->nom_categorie, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-user margin-r-5"></i> AUTEUR:</strong>
      <span class="pull-right badge badge-warning"><?= mb_strtoupper($memoire->nom_auteur, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-graduation-cap margin-r-5"></i> CYCLE:</strong>
      <span class="pull-right badge badge-warning"><?= mb_strtoupper($memoire->nom_cycle, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-list-alt margin-r-5"></i> FILIERE:</strong>
      <span class="pull-right badge badge-warning"><?= mb_strtoupper($memoire->nom_filiere, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-th-large margin-r-5"></i> RANGEE:</strong>
      <span class="pull-right badge badge-warning"><?= mb_strtoupper($memoire->nom_ranger, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-archive margin-r-5"></i> CASIER:</strong>
      <span class="pull-right badge badge-warning"><?= mb_strtoupper($memoire->nom_casier, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-file-text margin-r-5"></i> NOMBRE DE PAGES:</strong>
      <span class="pull-right badge badge-warning"><?= mb_strtoupper($memoire->nbre_page, 'UTF-8') ?></span>
      <hr>
      <strong><i class="fa fa-calendar margin-r-5"></i> DATE DE SOUTENANCE:</strong>
      <span class="pull-right badge badge-warning"><?= date('d/m/Y', strtotime($memoire->date_soutenance)) ?></span>
    </div>
    <!-- /.box-body -->
  </div>
</div>
