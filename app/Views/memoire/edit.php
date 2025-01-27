<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>Mise à jour de <?= $memoire->nom_memoire ?> <?= $this->endSection() ?>
<?= $this->section('pageTitle') ?> <?= $memoire->nom_memoire ?> <?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('memoires') ?>">Mémoire</a></li>
<li><i class="fa fa-angle-right"></i> Mise à jour</li>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>
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
                            <label>Thème du mémoire</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="ti-user"></i></div>
                                <input class="form-control" id="libelle" name="libelle"
                                    value="<?= mb_strtoupper($memoire->nom_memoire, 'UTF-8') ?>" type="text">
                            </div>
                            <span style="font-size: smaller;" class="error-message" id="libelle_error"></span>
                        </div>
                        <div class="col-md-6">
                            <label>Auteur du mémoire</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="ti-user"></i></div>
                                <input class="form-control" id="nom_auteur" name="nom_auteur"
                                    value="<?= mb_strtoupper($memoire->nom_auteur, 'UTF-8') ?>" type="text">
                            </div>
                            <span style="font-size: smaller;" class="error-message" id="nom_auteur_error"></span>
                        </div>

                        <div class="col-md-6">
                            <label>Catégorie</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                <select class="form-control select2" style="width: 100%;" id="id_categorie"
                                    name="id_categorie">
                                    <option value="">Choisir....</option>
                                    <?php foreach ($categories as $categorie): ?>
                                        <option value="<?= $categorie->id ?>" <?= ($categorie->id === $memoire->id_categorie) ? "selected" : "" ?>>
                                            <?= mb_strtoupper($categorie->libelle, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span style="font-size: smaller;" class="error-message" id="id_categorie_error"></span>
                        </div>

                        <div class="col-md-6">
                            <label>Cycle</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                <select class="form-control" style="width: 100%;" id="id_cycle" name="id_cycle">
                                    <option value="">Choisir...</option>
                                    <?php foreach ($cycles as $cycle): ?>
                                        <option value="<?= $cycle->id ?>" <?= ($cycle->id == $memoire->id_cycle) ? "selected" : "" ?>>
                                            <?= mb_strtoupper($cycle->libelle, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span style="font-size: smaller;" class="error-message" id="id_cycle_error"></span>
                        </div>

                        <div class="col-md-6">
                            <label>Filière</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                <select class="form-control" style="width: 100%;" id="id_filiere" name="id_filiere">
                                    <option value="">Choisir...</option>
                                    <?php foreach ($filieres as $filiere): ?>
                                        <option value="<?= $filiere->id ?>" <?= ($filiere->id == $memoire->id_filiere) ? "selected" : "" ?>>
                                            <?= mb_strtoupper($filiere->libelle, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span style="font-size: smaller;" class="error-message" id="id_filiere_error"></span>
                        </div>

                        <div class="col-md-6">
                            <label>Casier</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                <select class="form-control select2" style="width: 100%;" id="id_casier"
                                    name="id_casier">
                                    <option value="">Choisir...</option>
                                    <?php foreach ($casiers as $casier): ?>
                                        <option value="<?= $casier->id ?>" <?= ($casier->id == $memoire->id_casier) ? "selected" : "" ?>>
                                            <?= mb_strtoupper($casier->libelle, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span style="font-size: smaller;" class="error-message" id="id_casier_error"></span>
                        </div>
                        <div class="col-md-6">
                            <label>Rangée</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                <select class="form-control select2" style="width: 100%;" id="id_ranger"
                                    name="id_ranger">
                                    <option value="">Choisir...</option>
                                    <?php foreach ($rangers as $ranger): ?>
                                        <option value="<?= $ranger->id ?>" <?= ($ranger->id == $memoire->id_ranger) ? "selected" : "" ?>>
                                            <?= mb_strtoupper($ranger->libelle, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span style="font-size: smaller;" class="error-message" id="id_ranger_error"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Nombre de pages</label>
                                <input class="form-control" id="nbre_page" name="nbre_page"
                                    value="<?php echo htmlspecialchars($memoire->nbre_page); ?>" type="text">
                                <span style="font-size: smaller;" class="error-message" id="nbre_page_error"></span>
                                <span class="fa fa-spinner form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Date de soutenance</label>
                                <input class="form-control" id="date_soutenance" name="date_soutenance"
                                    value="<?php echo date('d/m/Y', strtotime($memoire->date_soutenance)); ?>"
                                    type="text">
                                <span style="font-size: smaller;" class="error-message"
                                    id="date_soutenance_error"></span>
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
            <a href="<?= base_url('memoires') ?>" class="btn btn-default">Annuler</a>
            <button type="submit" id="submitBtn" class="btn btn-success">Modifier</button>
        </div>
        </form>
    </div>
</div>
<?= $this->section('addjs') ?>
<script src="<?= base_url('public/dist/vendor/select2/dist/js/select2.full.min.js') ?>"></script>
<script type="text/javascript">
    $(".select2").select2({ 'data-placeholder': 'Choisir...' });
</script>
<script>
    (function () {
        $(document).ready(function () {

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

            function validateDateSoutenance() {
                var dateSoutenance = $("input[name='date_soutenance']").val(); // Récupère la valeur du champ "date_soutenance"
                var datePattern = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20)\d\d$/; // Expression régulière pour le format jj/mm/aaaa

                if (dateSoutenance.trim() === "") {
                    $("#date_soutenance_error").html("Le champ ne doit pas être vide.").css("color", "red");
                    return false;
                } else if (!datePattern.test(dateSoutenance)) {
                    $("#date_soutenance_error").html("Le format doit être : jj/mm/aaaa.").css("color", "red");
                    return false;
                } else {
                    $("#date_soutenance_error").html("").css("color", "inherit");
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
                    validateNbrePages() &&
                    validateDateSoutenance()
                ) {
                    $("#submitBtn").prop("disabled", false);
                } else {
                    $("#submitBtn").prop("disabled", true);
                }
            }

            // Écouter les changements dans les champs du formulaire
            $("#libelle, #nom_auteur, #id_categorie, #id_cycle, #id_filiere, #id_casier, #id_ranger, #nbre_page, #date_soutenance").on("input change", function () {
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