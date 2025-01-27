<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Nouvel utilisateur
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Nouvel utilisateur
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('user/administrateur') ?>">Utilisateur</a></li>
<li><i class="fa fa-angle-right"></i> espace utilisateur</li>
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
                                <label>Matricule<span class="text-muted"> (généré automatiquement)</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-flickr"></i></div>
                                    <input type="text" name="matricule" id="matricule" class="form-control" value="<?= $matricule ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Civilité</label>
                                <div class="form-group">
                                    <select name="civilite" id="civilite" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Choisir la civilité</option>
                                        <option value="M">M.</option>
                                        <option value="Mme">Mme</option>
                                    </select>
                                    <span style="font-size: smaller;" class="error-message" id="civilite-error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Nom</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-user"></i></div>
                                    <input class="form-control" id="nom" name="nom" placeholder="Nom" type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="nom-error"></span>
                            </div>
                            <div class="col-md-6">
                                <label>Prénom</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-user"></i></div>
                                    <input class="form-control" id="prenom" name="prenom" placeholder="Prénom"
                                        type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="prenom-error"></span>
                            </div>
                            <div class="col-md-6">
                                <label>Pseudo</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-user"></i></div>
                                    <input class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo"
                                        type="text">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="pseudo-error"></span>
                            </div>
                            <div class="col-md-6">
                                <label>Email</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-email"></i></div>
                                    <input class="form-control" name="email" id="email" placeholder="Email" type="email"
                                        required>
                                </div>
                                <div class="form-group">
                                    <span style="font-size: smaller;" class="error-message" id="email-error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Téléphone</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-mobile"></i></div>
                                    <input class="form-control" id="telephone" name="telephone" placeholder="Téléphone"
                                        type="tel">
                                </div>
                                <span style="font-size: smaller;" class="error-message" id="telephone-error"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Rôle</label>
                                <div class="form-group has-feedback">
                                    <select name="role" id="role" class="form-control select2" style="width: 100%;"
                                        required>
                                        <option value="">Choisir votre rôle</option>
                                        <?php
                                        $roles = ["ADMINISTRATEUR" => "Administrateur", "ETUDIANT" => "Étudiant", "PROFESSEUR" => "Professeur", "INVITE" => "Invite"];
                                        foreach ($roles as $value => $label) {
                                            echo '<option value="' . $value . '">' . $label . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <span style="font-size: smaller;" class="error-message" id="role-error"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <select name="id_cycle" id="id_cycle" class="form-control"
                                        style="width: 100%; display: none;" data-placeholder="Choisir...">
                                        <option value="">Choisir votre cycle</option>
                                        <?php foreach ($cycles as $cycle): ?>
                                            <option value="<?= esc($cycle->id) ?>"><?= esc($cycle->libelle) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <select name="id_filiere" id="id_filiere" class="form-control" style="width: 100%;"
                                        data-placeholder="Choisir...">
                                        <option value="">Choisir votre filière</option>
                                        <?php foreach ($filieres as $filiere): ?>
                                            <option value="<?= esc($filiere->id) ?>"><?= esc($filiere->libelle) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="card-footer text-left">
                <a href="<?= base_url('user/administrateur') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>
                <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm">Ajouter</button>
            </div>
            <div class="col-md-12">
                <div class="error-messages"></div>
            </div>
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
    (function(){
    $(document).ready(function () {
        $("select#id_cycle").hide();
        $("select#id_filiere").hide();
        $("#matricule").closest('.col-md-6').hide();
        $("#submitBtn").prop("disabled", true);

        $(document).ready(function () {
            $("#submitBtn").prop("disabled", true);

            function validateCivilite() {
                var civilite = $("#civilite").val();
                if (!civilite) {
                    $("#civilite-error").html("Le champ civilité est obligatoire.").css("color", "red");
                    return false;
                } else {
                    $("#civilite-error").html("").css("color", "inherit");
                    return true;
                }
            }

            function validateNom() {
                var nom = $("#nom").val();
                if (nom.length < 2 || !/^[A-Za-zÀ-ÿ]+$/.test(nom)) {
                    $("#nom-error").html("Le nom doit contenir uniquement des lettres et avoir au moins 2 caractères.").css("color", "red");
                    return false;
                } else {
                    $("#nom-error").html("").css("color", "inherit");
                    return true;
                }
            }

            function validatePrenom() {
                var prenom = $("#prenom").val();
                var mots = prenom.split(' '); // Divise le prénom en mots

                for (var i = 0; i < mots.length; i++) {
                    var mot = mots[i];
                    if (mot.length < 3 || !/^[A-Za-zÀ-ÿ]+$/.test(mot)) {
                        $("#prenom-error").html("Chaque mot du prénom doit contenir uniquement des lettres et avoir au moins 3 caractères.").css("color", "red");
                        return false;
                    }
                }

                // Si tous les mots passent la validation, effacer le message d'erreur
                $("#prenom-error").html("").css("color", "inherit");
                return true;
            }



            function validatePseudo() {
                var pseudo = $("#pseudo").val();
                if (!/^(?=.*[A-Za-zÀ-ÿ])(?=.*\d)[A-Za-zÀ-ÿ\d]+$/.test(pseudo)) {
                    $("#pseudo-error").html("Le pseudo doit contenir à la fois des lettres et des chiffres.").css("color", "red");
                    return false;
                } else {
                    $("#pseudo-error").html("").css("color", "inherit");
                    return true;
                }
            }

            function validateEmail() {
                var email = $("#email").val();
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    // Afficher un message d'erreur si l'e-mail est vide ou invalide
                    $("#email-error").html("Veuillez saisir une adresse e-mail valide.").css("color", "red");
                    // Désactiver le bouton soumettre
                    $("#submitBtn").prop("disabled", true);
                    return false;
                } else {
                    // Effacer le message d'erreur s'il y en a un et activer le bouton soumettre
                    $("#email-error").html("").css("color", "inherit");
                    $("#submitBtn").prop("disabled", false);
                    return true;
                }
            }

            function validateTelephone() {
                var telephone = $("#telephone").val();
                if (telephone.length < 8 || !/^\d+$/.test(telephone)) {
                    $("#telephone-error").html("Le numéro de téléphone doit contenir au moins 8 chiffres.").css("color", "red");
                    return false;
                } else {
                    $("#telephone-error").html("").css("color", "inherit");
                    return true;
                }
            }

            function validateRole() {
                var selectedRole = $("#role").val();

                // Vérifier si une option est sélectionnée
                if (!selectedRole) {
                    $("#role-error").html("Le champ rôle est obligatoire.").css("color", "red");
                    return false;
                } else {
                    $("#role-error").html("").css("color", "inherit");
                    return true;
                }
            }

            // Fonction pour activer ou désactiver le bouton "Se connecter" en fonction de la validation des champs
            function toggleSubmitButton() {
                if (validateCivilite() && validateNom() && validatePrenom() && validatePseudo() && validateEmail() && validateTelephone() && validateRole()) {
                    $("#submitBtn").prop("disabled", false);
                    $(".error-messages").html(""); // Effacer les messages d'erreur globaux s'il n'y en a pas
                } else {
                    $("#submitBtn").prop("disabled", true);
                }
            }

            // Écouter les changements dans les champs nom, prénom, pseudo, email et téléphone
            $("#civilite, #nom, #prenom, #pseudo, #email, #telephone").on("input", function () {
                toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
            });

            $("#civilite").on("select2:select select2:unselect", function () {
                toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
            });
            $("#role").on("select2:select select2:unselect", function () {
                toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
            });

        });

        $("select#role").change(function (e) {
            //.preventDefault();

            if (e.target.value === "ETUDIANT") {

                $("select#id_cycle").show();
                $("select#id_filiere").show();
                $("#matricule").closest('.col-md-6').show();
                $("select#id_cycle").attr("required", "required");
                $("select#id_filiere").attr("required", "required");
            } else if (e.target.value === "PROFESSEUR") {

                $("#matricule").closest('.col-md-6').show();
                $("select#id_cycle").hide();
                $("select#id_filiere").hide();
                $("select#id_cycle").removeAttr("required");
                $("select#id_filiere").removeAttr("required");
            } else {
                $("select#id_cycle").hide();
                $("select#id_filiere").hide();
                $("#matricule").closest('.col-md-6').hide();
                $("select#id_cycle").removeAttr("required");
                $("select#id_filiere").removeAttr("required");

            }
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