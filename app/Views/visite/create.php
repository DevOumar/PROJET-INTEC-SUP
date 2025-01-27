<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Espace de visite
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Espace de visite
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('visites') ?>">espace de viste</a></li>
<li><i class="fa fa-angle-right"></i> nouvel</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="info-box">
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
            <div class="card ">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">Formulaire</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Matricule<span class="text-muted"> (généré automatiquement)</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-flickr"></i></div>
                                    <input type="text" name="user_id" id="user_id" class="form-control"
                                        required="required" hidden="true">
                                    <input onblur="getinfos()" class="form-control" type="text" id="matricule">
                                </div>
                                <div class="form-group">
                                    <span id="get_student_name" style="font-size:16px;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <label>Sélection des motifs</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-flickr"></i></div>
                                        <select name="motifVisite_id" id="motifVisite_id" class="form-control select2" style="width: 100%;"
                                        required>
                                        <option value="">Choisir le motif</option>
                                        <?php foreach ($motifvisites as $motifvisite): ?>
                                            <option value="<?= esc($motifvisite->id) ?>"><?= esc($motifvisite->libelle) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="card-footer text-left">
                <a href="<?= base_url('visites') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>
                <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm" disabled>Ajouter</button>

            </div>
        </div>
        </form>
    </div>
</div>

<?= $this->section('addjs'); ?>
<script src="<?= base_url('public/dist/vendor/select2/dist/js/select2.full.min.js') ?>"></script>
<script type="text/javascript">
    $(".select2").select2({ 'data-placeholder': 'Choisir...' });
</script>

<script>
    (function() {
        // Fonction pour obtenir les informations sur l'étudiant
        function getinfos() {
            $("#loaderIcon").show();
            $("#user_id").val('');

            $.ajax({
                url: "<?= base_url('emprunts/infos') ?>",
                data: 'matricule=' + $("#matricule").val(),
                type: "POST",
                success: function(data) {
                    resp = JSON.parse(data);
                    if (resp?.error) {
                        $("#get_student_name").html("<span class='text-danger'>Matricule non valide. Veuillez entrer un matricule correct</span>");
                        $("#submitBtn").prop("disabled", true); // Désactiver le bouton si le matricule n'est pas valide
                    } else if (resp?.error === false) {
                        $("#user_id").val(resp?.user?.id);
                        $("#get_student_name").html(
                            "<span class='text-black'>" +
                            resp?.user?.prenom + ' ' + resp?.user?.nom + ' | ' + resp?.user?.role +
                            "</span>"
                        );
                        $("#submitBtn").prop("disabled", false); // Activer le bouton si le matricule est valide
                    }
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }

        // Déclencher la fonction getinfos() dès que l'utilisateur commence à saisir quelque chose dans le champ de saisie
        $("#matricule").on("input", function() {
            getinfos();
        });
    })();
</script>


<?= $this->endSection() ?>
<?= $this->endSection() ?>