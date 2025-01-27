<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>Mise à jour de <?= $user->nom ?> <?= $user->prenom ?> <?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Mise à jour de <?= $user->nom ?> <?= $user->prenom ?><?php if (!empty($user->matricule)): ?>
  <span style="font-size: smaller;">(<?= $user->matricule ?>)</span>
<?php endif; ?>
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('user/administrateur') ?>">Utilisateur</a></li>
<li><i class="fa fa-angle-right"></i> Mise à jour</li>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
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
            <?php if (!empty($user->matricule)): ?>
              <div class="col-md-6">
                <label>Matricule</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-user"></i></div>
                  <input class="form-control" name="matricule" value="<?= $user->matricule ?>" type="text" disabled>
                </div>
              </div>
            <?php endif; ?>

            <div class="col-md-6">
              <label>Nom</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="ti-user"></i></div>
                <input class="form-control" name="nom" id="nom" value="<?= $user->nom ?>" type="text">
              </div>
              <span style="font-size: smaller;" class="error-message" id="nom-error"></span>
            </div>
            <div class="col-md-6">
              <label>Préom</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="ti-user"></i></div>
                <input class="form-control" name="prenom" id="prenom" value="<?= $user->prenom ?>" type="text">
              </div>
              <span style="font-size: smaller;" class="error-message" id="prenom-error"></span>
            </div>
            <div class="col-md-6">
              <label>Pseudo</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="ti-user"></i></div>
                <input class="form-control" name="pseudo" id="pseudo" value="<?= $user->pseudo ?>" type="text">
              </div>
              <span style="font-size: smaller;" class="error-message" id="pseudo-error"></span>
            </div>
            <div class="col-md-6">
              <label>Email</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="ti-email"></i></div>
                <input class="form-control" name="email" id="email" value="<?= $user->email ?>" type="text">
              </div>
              <span style="font-size: smaller;" class="error-message" id="email-error"></span>
            </div>
            <div class="col-md-6">
              <label>Téléphone</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="ti-mobile"></i></div>
                <input class="form-control" name="telephone" id="telephone" value="<?= $user->telephone ?>" type="text">
              </div>
              <span style="font-size: smaller;" class="error-message" id="telephone-error"></span>
            </div>

            <div class="col-md-6">
              <label>Rôle</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="ti-user"></i></div>
                <select class="form-control select2" style="width: 100%;" name="role" id="role" disabled>
                  <option value="ETUDIANT" <?= ($user->role === "ETUDIANT") ? "selected" : "" ?>>Étudiant</option>
                  <option value="PROFESSEUR" <?= ($user->role === "PROFESSEUR") ? "selected" : "" ?>>Professeur</option>
                  <option value="ADMINISTRATEUR" <?= ($user->role === "ADMINISTRATEUR") ? "selected" : "" ?>>Administrateur
                  </option>
                </select>
              </div>
              <span style="font-size: smaller;" class="error-message" id="role-error"></span>
            </div>
            <?php if (!empty($user->id_cycle) && !empty($user->id_filiere)): ?>
              <div class="col-md-6">
                <label>Cycle</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-calendar"></i></div>
                  <select class="form-control" style="width: 100%;" name="id_cycle" id="id_cycle">
                    <?php foreach ($cycles as $cycle): ?>
                      <option value="<?= $cycle->id ?>" <?= ($cycle->id === $user->id_cycle) ? "selected" : "" ?>>
                        <?= $cycle->libelle ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <label>Filière</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-calendar"></i></div>
                  <select class="form-control" style="width: 100%;" name="id_filiere" id="id_filiere">
                    <?php foreach ($filieres as $filiere): ?>
                      <option value="<?= $filiere->id ?>" <?= ($filiere->id === $user->id_filiere) ? "selected" : "" ?>>
                        <?= $filiere->libelle ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php endif; ?>
          </div>
      </div>
      <div class="card-footer text-left">
        <a href="<?= base_url('user/administrateur') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>
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
<script>
(function(){
  $(document).ready(function () {

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
      if (validateNom() && validatePrenom() && validatePseudo() && validateEmail() && validateTelephone() && validateRole()) {
        $("#submitBtn").prop("disabled", false);
      } else {
        $("#submitBtn").prop("disabled", true);
      }
    }

    // Écouter les changements dans les champs nom, prénom, pseudo, email et téléphone
    $("#nom, #prenom, #pseudo, #email, #telephone, #role").on("input", function () {
      toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
    });

    $("#role").on("select2:select select2:unselect", function () {
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