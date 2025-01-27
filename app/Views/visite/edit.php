<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Mise à jour de <?= $visite->matricule ?>
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Mise à jour de <?= strtoupper($visite->nom . '-' . $visite->prenom) ?> (<?= $visite->matricule ?>)
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('visites') ?>">visite</a></li>
<li><i class="fa fa-angle-right"></i> Mise à jour</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="col-md-6">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Formulaire</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Matricule<span class="text-muted"> (généré automatiquement)</span></label>
                            <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-flickr"></i></div>
                                    <input type="text" class="form-control" value="<?= $visite->matricule ?>" disabled>
                                    <input type="hidden" name="user_id" id="user_id" value="<?= $visite->user_id ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Statut</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="ti-flag"></i></div>
                                <select class="form-control select2" style="width: 100%;" id="status" name="status">
                                    <option value="">Choisir...</option>
                                    <option value="en_cours" <?= ($visite->status === 'en_cours') ? "selected" : "" ?>>En
                                        cours</option>
                                    <option value="terminee" <?= ($visite->status === 'terminee') ? "selected" : "" ?>>
                                        Terminée</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                <label>Sélection des motifs</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-calendar"></i></div>
                  <select class="form-control" style="width: 100%;" name="motifVisite_id" id="motifVisite_id">
                  <option value="">Choisir le motif</option>
                    <?php foreach ($motifvisites as $motifvisite): ?>
                      <option value="<?= $motifvisite->id ?>" <?= ($motifvisite->id === $visite->motifVisite_id) ? "selected" : "" ?>>
                        <?= $motifvisite->libelle ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

                    </div>
            </div>
            <div class="card-footer text-left">
                <a href="<?= base_url('visites') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>
                <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm">Modifier</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?= $this->section('addjs') ?>
<script src="<?= base_url('public/dist/vendor/select2/dist/js/select2.full.min.js') ?>"></script>
<script type="text/javascript">
    $(".select2").select2({ 'data-placeholder': 'Choisir...' });
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>