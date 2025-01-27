<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Nouveau Livre
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Nouveau Livre
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('livres') ?>">Livres</a></li>
<li><i class="fa fa-angle-right"></i> Nouveau</li>
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
                    <form method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nom du livre</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-book"></i></div>
                                    <input class="form-control" name="nom_livre" id="nom_livre"
                                        placeholder="Nom du livre" type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="nom_livre_error"></span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label class="control-label">Auteur</label>
                                    <select name="id_auteur" class="form-control select2" style="width: 100%;"
                                        id="id_auteurs">
                                        <option value="">Choisir....</option>
                                        <?php foreach ($auteurs as $auteur): ?>
                                            <option value="<?= $auteur->id ?>"><?= mb_strtoupper($auteur->libelle, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span style="font-size: smaller;" class="error-message"
                                        id="id_auteurs_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label class="control-label">Catégorie</label>
                                    <select name="id_categorie" class="form-control select2" style="width: 100%;"
                                        id="id_categories">
                                        <option value="">Choisir....</option>
                                        <?php foreach ($categories as $categorie): ?>
                                            <option value="<?= $categorie->id ?>"><?= mb_strtoupper($categorie->libelle, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span style="font-size: smaller;" class="error-message"
                                        id="id_categories_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label class="control-label">Rangée</label>
                                    <select name="id_ranger" class="form-control select2" style="width: 100%;"
                                        id="id_ranger">
                                        <option value="">Choisir....</option>
                                        <?php foreach ($rangers as $ranger): ?>
                                            <option value="<?= $ranger->id ?>"><?= mb_strtoupper($ranger->libelle, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span style="font-size: smaller;" class="error-message" id="id_ranger_error"></span>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label class="control-label">Casier</label>
                                    <select name="id_casier" class="form-control select2" style="width: 100%;"
                                        id="id_casier">
                                        <option value="">Choisir....</option>
                                        <?php foreach ($casiers as $casier): ?>
                                            <option value="<?= $casier->id ?>"><?= mb_strtoupper($casier->libelle, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span style="font-size: smaller;" class="error-message" id="id_casier_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Numéro ISBN</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-spinner"></i></div>
                                    <input class="form-control" name="isbn" id="isbn" placeholder="Numéro ISBN"
                                        type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="isbn_error"></span>
                            </div>
                            <div class="col-md-6">
                                <label>Nombre de pages</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-spinner"></i></div>
                                    <input class="form-control" name="nbre_page" id="nbre_page"
                                        placeholder="Nombre de pages" type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="nbre_page_error"></span>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label>Quantité</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-spinner"></i></div>
                                    <input class="form-control" name="quantite" id="quantite" placeholder="Quantité"
                                        type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="quantite_error"></span>
                            </div>

                            <div class="col-md-8 rounded mx-auto d-block">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="text-black">Fichier livre</h4>
                                        <label for="input-file-now"></label>
                                        <input type="file" id="input-file-now" name="fichier_livre" class="dropify">
                                    </div>
                                </div>
                            </div>

                        </div>
                </div>
            </div>

            <div class="card-footer text-left">
                <a href="<?= base_url('livres') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>
                <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm">Ajouter</button>
            </div>
        </div>
        </form>
    </div>
</div>
<?= $this->section('addjs') ?>
<script src="<?= base_url('public/dist/js/js/memoire.js') ?>"></script>
<script src="<?= base_url('public/dist/vendor/select2/dist/js/select2.full.min.js') ?>"></script>
<script type="text/javascript">
    $(".select2").select2({ 'data-placeholder': 'Choisir...' });
</script>
<script>
    (function () {
        $(document).ready(function () {

            $("#submitBtn").prop("disabled", true);

            function validateNomLivre() {
                var nomLivre = $("#nom_livre").val();

                if (nomLivre.length < 3) {
                    $("#nom_livre_error").html("Le nom du livre doit avoir au moins 3 caractères.").css("color", "red");
                    return false;
                } else {
                    // Supprimer la vérification des chiffres
                    $("#nom_livre_error").html("").css("color", "inherit");
                    return true;
                }
            }


            function validateSelect2Field(fieldId, errorMessage) {
                var fieldValue = $("#" + fieldId).val();
                if (!fieldValue) {
                    $("#" + fieldId + "_error").html(errorMessage).css("color", "red");
                    return false;
                } else {
                    $("#" + fieldId + "_error").html("").css("color", "inherit");
                    return true;
                }
            }

            function validateIsbn() {
                var isbn = $("#isbn").val();
                if (!/^[0-9A-Za-z]+$/.test(isbn)) {
                    $("#isbn_error").html("Le numéro ISBN doit contenir uniquement des chiffres et des lettres.").css("color", "red");
                    return false;
                } else {
                    $("#isbn_error").html("").css("color", "inherit");
                    return true;
                }
            }


            function validateNbrePages() {
                var nbrePages = $("#nbre_page").val();
                if (!/^\d+$/.test(nbrePages)) {
                    $("#nbre_page_error").html("Le nombre de pages doit contenir uniquement des chiffres.").css("color", "red");
                    return false;
                } else {
                    $("#nbre_page_error").html("").css("color", "inherit");
                    return true;
                }
            }

            function validateQuantite() {
                var quantite = $("#quantite").val();
                if (!/^\d+$/.test(quantite)) {
                    $("#quantite_error").html("La quantité doit contenir uniquement des chiffres.").css("color", "red");
                    return false;
                } else {
                    $("#quantite_error").html("").css("color", "inherit");
                    return true;
                }
            }

            // Fonction pour activer ou désactiver le bouton de soumission en fonction de la validation des champs
            function toggleSubmitButton() {
                if (
                    validateNomLivre() &&
                    validateSelect2Field("id_auteurs", "Veuillez sélectionner un auteur.") &&
                    validateSelect2Field("id_categories", "Veuillez sélectionner une catégorie.") &&
                    validateSelect2Field("id_ranger", "Veuillez sélectionner une rangée.") &&
                    validateSelect2Field("id_casier", "Veuillez sélectionner un casier.") &&
                    validateIsbn() &&
                    validateNbrePages() &&
                    validateQuantite()
                ) {
                    $("#submitBtn").prop("disabled", false);
                } else {
                    $("#submitBtn").prop("disabled", true);
                }
            }

            // Écouter les changements dans les champs du formulaire
            $("#nom_livre, #id_auteurs, #id_categories, #id_ranger, #id_casier, #isbn, #nbre_page, #quantite").on("input change", function () {
                toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
            });

        });
    })();
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>