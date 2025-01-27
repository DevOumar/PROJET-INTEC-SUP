<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Liste des notifications
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Liste des notifications <a href="<?= base_url('notifications') ?>" class="btn btn-rounded btn-primary btn-sm"><i class="fa fa-refresh"></i></a>
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> notifications</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="info-box">
    <h4 class="text-black">
        Liste(<?php echo count($notifications); ?>)
    </h4>
    <hr />
<div class="info-box">
  <div class="table-responsive">
      <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
        <thead>
          <tr>
            <th>ID #</th>
            <th>Message</th>
            <th>Date et heure</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($notifications as $key => $notification): ?>
        <tr>
        <td><?= $key + 1 ?></td>
        <td><?php echo $notification->message; ?></td>
            <td><span class="btn btn-rounded btn-primary btn-sm"><?= date('d/m/Y à H:i', strtotime($notification->created_at)); ?></span></td>
            <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
          <?php endif; ?>
      </tr>
      <?php endforeach; ?>
  </tbody>
</table>
</div>
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
            text: 'Supprimer cette notification !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "<?= base_url('notifications/delete/') ?>" + id,
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