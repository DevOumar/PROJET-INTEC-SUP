<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Recommandation
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Espace recommandation de livres
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('recommandations') ?>">recommandation</a></li>
<li><i class="fa fa-angle-right"></i> Nouvelle</li>
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
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nom du livre</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-book"></i></div>
                                    <input class="form-control" id="nom_livre" name="nom_livre" type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="nom_livre_error"></span>
                            </div>
                            <div class="col-md-6">
                                <label>Nom de l'auteur</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-user"></i></div>
                                    <input class="form-control" id="nom_auteur" name="nom_auteur" type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="nom_auteur_error"></span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label class="control-label">Description</label>
                                    <textarea name="description" id="description" class="form-control"
                                        required></textarea>
                                    <span class="fa fa-book form-control-feedback" aria-hidden="true"></span>
                                    <span style="font-size: smaller;" class="error-message" id="description_error"></span>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <div class="card-footer text-left">
                <a href="<?= base_url('recommandations') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>
                <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm">Ajouter</button>
            </div>
        </div>
        </form>
    </div>
</div>
<?= $this->section('addjs') ?>
<script>
    (function(){
    $(document).ready(function () {

        $("#submitBtn").prop("disabled", true);

        function validateNomLivre() {
            var nomLivre = $("#nom_livre").val();
            var chiffres = /[0-9]/; // Expression régulière pour vérifier la présence de chiffres

            if (nomLivre.length < 3) {
                $("#nom_livre_error").html("Le nom du livre doit avoir au moins 3 caractères.").css("color", "red");
                return false;
            } else if (chiffres.test(nomLivre)) {
                $("#nom_livre_error").html("Le nom du livre ne doit pas contenir de chiffres.").css("color", "red");
                return false;
            } else {
                $("#nom_livre_error").html("").css("color", "inherit");
                return true;
            }
        }

        function validateNomAuteur() {
            var nomAuteur = $("#nom_auteur").val();
            var chiffres = /[0-9]/; // Expression régulière pour vérifier la présence de chiffres

            if (nomAuteur.length < 3) {
                $("#nom_auteur_error").html("Le nom de l'auteur doit avoir au moins 3 caractères.").css("color", "red");
                return false;
            } else if (chiffres.test(nomAuteur)) {
                $("#nom_auteur_error").html("Le nom de l'auteur ne doit pas contenir de chiffres.").css("color", "red");
                return false;
            } else {
                $("#nom_auteur_error").html("").css("color", "inherit");
                return true;
            }
        }

        function validateDescription() {
            var description = $("#description").val();

            if (description.trim() === "") {
                $("#description_error").html("La description est obligatoire.").css("color", "red");
                return false;
            } else if (description.length > 500) {
                $("#description_error").html("La description ne doit pas dépasser 500 caractères.").css("color", "red");
                return false;
            } else {
                $("#description_error").html("").css("color", "inherit");
                return true;
            }
        }
        // Fonction pour activer ou désactiver le bouton de soumission en fonction de la validation des champs
        function toggleSubmitButton() {
            if (
                validateNomLivre() &&
                validateNomAuteur() &&
                validateDescription()
                ) {
                $("#submitBtn").prop("disabled", false);
            } else {
                $("#submitBtn").prop("disabled", true);
            }
        }

        // Écouter les changements dans les champs du formulaire
        $("#nom_livre, #nom_auteur, #description").on("input change", function () {
            toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
        });

    });
})();
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>