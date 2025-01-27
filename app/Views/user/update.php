<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Mise à jour de <?= session()->prenom . ' ' . session()->nom ?>

<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
<?= session()->prenom . ' ' . session()->nom ?>

<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> Mise à jour du profil</li>
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
        <h5 class="text-white m-b-0">Modifier votre profil</h5>
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="row">
            <?php if (isset($user->matricule)): ?>
              <div class="col-md-6">
                <div class="form-group has-feedback">
                  <label>Matricule<span class="text-muted"> (généré automatiquement)</span></label>
                  <div class="input-group">
                    <div class="input-group-addon"><i class="ti-flickr"></i></div>
                    <input class="form-control" name="matricule" value="<?= $user->matricule ?>" type="text" disabled>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nom</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-user"></i></div>
                  <input class="form-control" name="nom" id="nom" value="<?php echo htmlspecialchars($user->nom); ?>"
                    type="text">
                </div>
                <span style="font-size: smaller;" class="error-message" id="nom-error"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Prénom</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-user"></i></div>
                  <input class="form-control" name="prenom" id="prenom"
                    value="<?php echo htmlspecialchars($user->prenom); ?>" type="text">
                </div>
              </div>
              <span style="font-size: smaller;" class="error-message" id="prenom-error"></span>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Pseudo</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-user"></i></div>
                  <input class="form-control" name="pseudo" id="pseudo"
                    value="<?php echo htmlspecialchars($user->pseudo); ?>" type="text">
                </div>
                <span style="font-size: smaller;" class="error-message" id="pseudo-error"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Adresse e-mail</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-email"></i></div>
                  <input class="form-control" name="email" value="<?php echo htmlspecialchars($user->email); ?>"
                    type="text" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Téléphone</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="ti-mobile"></i></div>
                  <input class="form-control" name="telephone" id="telephone"
                    value="<?php echo htmlspecialchars($user->telephone); ?>" type="tel">
                </div>
                <span style="font-size: smaller;" class="error-message" id="telephone-error"></span>
              </div>
            </div>
            <?php if (session()->get('role') === 'ETUDIANT'): ?>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Cycle</label>
                  <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-spinner"></i></div>
                    <input class="form-control" name="id_cycle" value="<?php echo htmlspecialchars($user->nom_cycle); ?>"
                      type="text" disabled>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Filière</label>
                  <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-spinner"></i></div>
                    <input class="form-control" name="id_filiere"
                      value="<?php echo htmlspecialchars($user->nom_filiere); ?>" type="text" disabled>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <div class="col-md-8 rounded mx-auto d-block">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-black">Photo de profil</h4>
                  <label for="input-file-now"></label>
                  <input type="file" id="input-file-now" name="photo" class="dropify" multiple />
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer text-left">
            <?php
            $role = session()->get('role');
            $cancelUrl = '';

            switch ($role) {
              case 'ADMINISTRATEUR':
                $cancelUrl = site_url('user/administrateur');
                break;
              case 'ETUDIANT':
                $cancelUrl = site_url('user/index');
                break;
              case 'PROFESSEUR':
                $cancelUrl = site_url('user/professeur');
                break;
              default:
                // URL par défaut ou gestion des autres cas si nécessaire
                break;
            }
            ?>

            <a href="<?= $cancelUrl ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>
            <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm">Modifier</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>


<?= $this->section('addjs') ?>
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


    // Fonction pour activer ou désactiver le bouton "Se connecter" en fonction de la validation des champs
    function toggleSubmitButton() {
      if (validateNom() && validatePrenom() && validatePseudo() && validateTelephone()) {
        $("#submitBtn").prop("disabled", false);
      } else {
        $("#submitBtn").prop("disabled", true);
      }
    }

    // Écouter les changements dans les champs nom, prénom, pseudo, email et téléphone
    $("#nom, #prenom, #pseudo, #telephone").on("input", function () {
      toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
    });


  });
})();
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>