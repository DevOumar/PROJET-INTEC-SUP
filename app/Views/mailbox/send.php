<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Boite de messagerie
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
Boite de messagerie
<?= $this->endSection() ?>

<?= $this->section('addcss') ?>
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/dist/plugins/dropzone-master/dropzone.css')?>">
<link rel="stylesheet" href="<?= base_url('public/dist/plugins/iCheck/flat/blue.css')?>">

<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard')?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> Boite de messagerie</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-3">
        <a href="<?= base_url('mailbox/send')?>" class="btn btn-danger btn-block margin-bottom">Boite de reception</a>
        <div class="box box-solid">
            <?php include 'menu.inc.php'; ?>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Ecrire un nouveau message</h3>
            </div>
            <!-- /.box-header -->
            <form action="<?= base_url('mailbox/send') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="box-body pad-10">
              <div class="form-group">
              <label for="recipient">A:</label>
            <select name="user_id" class="form-control select2" style="width: 100%;">
                <option value="">Choisir...</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user->id ?>">
                    <?= strtoupper($user->prenom . ' ' . $user->nom) ?> (<?= $user->role ?>-<?= $user->matricule ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            </div>
              <div class="form-group">
              <label for="recipient">Objet:</label>
                <input class="form-control" name="subject" required>
              </div>
                    <div class="form-group">
                <textarea id="compose-textarea" name="message" class="form-control" style="height: 300px" required></textarea>
              </div>
                   
                </div>
                <!-- /.box-body -->
                <div class="box-footer m-b-2">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Envoyer</button>
                    </div>
                    <a href="<?= base_url('mailbox') ?>" class="btn btn-default"><i class="fa fa-times"></i> Annuler</a>
                </div>
            </form>
            <!-- /.box-footer -->
        </div>
    </div>
</div>
<!-- Main row --> 
<?= $this->section('addjs') ?>
<script src="<?= base_url('public/dist/vendor/select2/dist/js/select2.full.min.js') ?>"></script>
<script src="<?= base_url('public/dist/plugins/dropzone-master/dropzone.js') ?>"></script>
<script src="<?= base_url('public/dist/plugins/iCheck/icheck.min.js') ?>"></script>

<script type="text/javascript">
    $(".select2").select2({ 'data-placeholder': 'Choisir...' });
</script>
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
