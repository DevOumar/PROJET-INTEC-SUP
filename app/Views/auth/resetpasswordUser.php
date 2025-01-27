<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Mise à jour mot de passe
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Mise à jour du mot de passe
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
    <li><a href="<?= base_url('dashboard')?>">Accueil</a></li>
    <li><i class="fa fa-angle-right"></i> Mise à jour du mot de passe</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-md-6">
     <?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
</div>
<div class="row">
    <div class="col-lg-6">
    <?php if (session()->getFlashdata('warning')) : ?>
    <div class="alert alert-warning"><?= session()->getFlashdata('warning') ?></div>
<?php endif; ?>
        <div class="card ">
        <div class="card-header bg-blue">
          <h5 class="text-white m-b-0">Reinitialisation du Mot de passe</h5>
      </div>
      <div class="card-body">
      <form class="form" method="POST">
      <?= csrf_field() ?>
        <div class="form-group">
          <label for="pwd1">Ancien mot de passe</label>
          <div class="input-group">
            <div class="input-group-addon"><i class="ti-lock"></i></div>
            <input class="form-control" name="old_password" id="old_password" placeholder="Ancien mot de passe" type="text">
          </div>
          <span style="font-size: smaller;" class="error-message" id="old_password-error"></span>
        </div>
        <div class="form-group">
          <label for="pwd1">Nouveau mot de passe</label>
          <div class="input-group">
            <div class="input-group-addon"><i class="ti-lock"></i></div>
            <input class="form-control" name="new_password" id="new_password" placeholder="Nouveau mot de passe" type="text">
          </div>
          <span style="font-size: smaller;" class="error-message" id="new_password-error"></span>
        </div>
        <div class="form-group">
          <label for="pwd2">Confirmer votre mot de passe</label>
          <div class="input-group">
            <div class="input-group-addon"><i class="ti-lock"></i></div>
            <input class="form-control" name="con_password" id="con_password" placeholder="Confirmer votre mot de passe" type="text">
          </div>
          <span style="font-size: smaller;" class="error-message" id="con_password-error"></span>
        </div>
        <button type="submit" id="submitBtn" class="btn btn-success waves-effect waves-light m-r-10">Valider</button>
        <a href="<?= base_url('dashboard')?>" class="btn btn-default">Annuler</a>
      </form>
    </div>
  </div>
</div>
</div>
<?= $this->section('addjs') ?>
<script>
  (function(){
    $(document).ready(function () {
      $("#submitBtn").prop("disabled", true);

      function validatePassword() {
        var oldPassword = $("#old_password").val();
        var newPassword = $("#new_password").val();
        var confirmPassword = $("#con_password").val();

        // Vérifier si le mot de passe actuel respecte les critères (minimum 6 caractères)
        if (oldPassword.length < 6) {
          $("#old_password-error").html("Le mot de passe actuel doit contenir au moins 6 caractères.").css("color", "red");
          return false;
        } else {
          $("#old_password-error").html("").css("color", "inherit");

          // Vérifier si le nouveau mot de passe respecte les critères (minimum 8 caractères avec au moins un caractère spécial)
          if (newPassword.length < 8 || !/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)) {
            $("#new_password-error").html("Le nouveau mot de passe doit contenir au moins 8 caractères avec un caractère spécial.").css("color", "red");
            return false;
          } else {
            $("#new_password-error").html("").css("color", "inherit");

            // Vérifier si les deux nouveaux mots de passe correspondent
            if (newPassword !== confirmPassword) {
              $("#con_password-error").html("Les nouveaux mots de passe ne correspondent pas.").css("color", "red");
              return false;
            } else {
              $("#con_password-error").html("").css("color", "inherit");
              return true;
            }
          }
        }
      }

      // Fonction pour activer ou désactiver le bouton "Se connecter" en fonction de la validation des champs
      function toggleSubmitButton() {
        if (validatePassword()) {
          $("#submitBtn").prop("disabled", false);
        } else {
          $("#submitBtn").prop("disabled", true);
        }
      }

      // Écouter les changements dans les champs de mot de passe
      $("#old_password, #new_password, #con_password").on("input", function () {
        toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
      });
    });
  })();
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>