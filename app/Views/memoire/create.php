<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Mémoires
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Mémoires
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">

<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('memoires') ?>">Mémoires</a></li>
<li><i class="fa fa-angle-right"></i> Nouveau</li>
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
        <div class="card ">
            <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Formulaire</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Thème du mémoire</label>
                                <input class="form-control" name="libelle" id="libelle" placeholder="Nom de la mémoire"
                                    type="text">
                                <span class="fa fa-user form-control-feedback" aria-hidden="true"></span>
                                <span style="font-size: smaller;" class="error-message" id="libelle_error"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Auteur du mémoire</label>
                                <input class="form-control" name="nom_auteur" id="nom_auteur"
                                    placeholder="Auteur du mémoire" type="text">
                                <span style="font-size: smaller;" class="error-message" id="nom_auteur_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Catégorie</label>
                                <select name="id_categorie" class="form-control select2" style="width: 100%;"
                                    id="id_categorie">
                                    <option value="">Choisir....</option>
                                    <?php foreach ($categories as $categorie): ?>
                                        <option value="<?= $categorie->id ?>"><?= mb_strtoupper($categorie->libelle, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span style="font-size: smaller;" class="error-message" id="id_categorie_error"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Cycle</label>
                                <select name="id_cycle" id="id_cycle" class="form-control select2" style="width: 100%;"
                                    data-placeholder="Choisir...">
                                    <option value="">Choisir...</option>
                                    <?php foreach ($cycles as $cycle): ?>
                                        <option value="<?= $cycle->id ?>"><?= mb_strtoupper($cycle->libelle, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span style="font-size: smaller;" class="error-message" id="id_cycle_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Filière</label>
                                <select name="id_filiere" class="form-control select2" style="width: 100%;" id="id_filiere">
                                    <option value="">Choisir...</option>
                                    <?php foreach ($filieres as $filiere): ?>
                                        <option value="<?= $filiere->id ?>"><?= mb_strtoupper($filiere->libelle, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span style="font-size: smaller;" class="error-message" id="id_filiere_error"></span>
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
                                <label class="control-label">Nombre de pages</label>
                                <input class="form-control" name="nbre_page" id="nbre_page"
                                    placeholder="Nombre de pages" type="text">
                                <span style="font-size: smaller;" class="error-message" id="nbre_page_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Date de soutenance</label>
                                <input type="date" name="date_soutenance" id="date_soutenance" class="form-control"
                                    min="<?= date('Y-m-d', strtotime(date('Y-m-d') . ' - 15 year')) ?>" required>
                                <span class="fa fa form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="col-md-8 rounded mx-auto d-block">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="text-black">Fichier memoire</h4>
                                    <label for="input-file-now"></label>
                                    <input type="file" id="input-file-now" name="fichier_memoire" class="dropify"
                                        multiple />
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="card-footer text-left">
            <a href="<?= base_url('memoires') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>
            <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm">Ajouter</button>
        </div>
    </div>
    </form>
</div>
<!-- Catégorie Modal -->
<div class="modal fade" id="createCategorie" tabindex="-1" role="dialog" aria-labelledby="createCategorieLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategorieLabel">Nouvelle catégorie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="form-group ">
                        <label for="libelle-categorie">Nom de la catégorie</label>
                        <input type="text" name="libelle-categorie" id="libelle-categorie" class="form-control"
                            placeholder="" aria-describedby="helpId">
                        <small id="helpId" class="text-red"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button id="btn-save-categorie" type="submit" class="btn btn-primary">Enregistrer</button>
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
<script src="<?= base_url('public/dist/js/js/memoire.js') ?>"></script>

<script>
    (function () {
        $(document).ready(function () {
            $("select#id_filiere").show();

            $("#submitBtn").prop("disabled", true);

            function validateLibelle() {
                var nomLivre = $("#libelle").val();

                if (nomLivre.length < 3) {
                    $("#libelle_error").html("Le thème doit avoir au moins 3 caractères.").css("color", "red");
                    return false;
                } else {
                    // Supprimer la vérification des chiffres
                    $("#libelle_error").html("").css("color", "inherit");
                    return true;
                }
            }


            function validateNomAuteur() {
                var nomAuteur = $("#nom_auteur").val(); // Récupère la valeur du champ "nom_auteur"
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


            // Fonction pour activer ou désactiver le bouton de soumission en fonction de la validation des champs
            function toggleSubmitButton() {
                if (
                    validateLibelle() &&
                    validateNomAuteur() &&
                    validateSelect2Field("id_categorie", "Veuillez sélectionner une catégorie.") &&
                    validateSelect2Field("id_cycle", "Veuillez sélectionner un cycle.") &&
                    validateSelect2Field("id_filiere", "Veuillez sélectionner une filière.") &&
                    validateSelect2Field("id_casier", "Veuillez sélectionner un casier.") &&
                    validateSelect2Field("id_ranger", "Veuillez sélectionner une rangée.") &&
                    validateNbrePages()
                ) {
                    $("#submitBtn").prop("disabled", false);
                } else {
                    $("#submitBtn").prop("disabled", true);
                }
            }

            // Écouter les changements dans les champs du formulaire
            $("#libelle, #nom_auteur, #id_categorie, #id_cycle, #id_filiere, #id_casier, #id_ranger, #nbre_page").on("input change", function () {
                toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
            });

            document.querySelector("select#id_cycle").
                addEventListener("change", function (e) {

                    //   console.log(e.target.value);
                    $("select#id_filiere").html("");
                    $.get("<?= base_url('filiere/list/') ?>" + e.target.value,
                        function (data, textStatus, jqXHR) {
                            let optionList = `<option value>Choisir votre filière</option>`;

                            data?.forEach(cycle => {

                                optionList += `<option value="${cycle.id}">${cycle.libelle}</option>`
                            });

                            $("select#id_filiere").html(optionList);

                            // console.log(data)
                        },
                        "json"
                    );
                })

        });
    })();
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>