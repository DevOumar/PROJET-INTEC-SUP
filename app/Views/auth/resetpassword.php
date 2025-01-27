<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reinitialisation - Mot de passe</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />

  <!-- logo -->
<link rel="icon" href="<?= base_url('public/dist/img/logo-intecsup.png')?>" type="image/png" />
  <!-- v4.0.0-alpha.6 -->
  <link rel="stylesheet" href="<?= base_url('public/dist/bootstrap/css/bootstrap.min.css')?>">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('public/dist/css/style.css')?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/css/font-awesome/css/font-awesome.min.css')?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/css/et-line-font/et-line-font.css')?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/css/themify-icons/themify-icons.css')?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/plugins/hmenu/ace-responsive-menu.css')?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js')}}"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
<![endif]-->

<style type="text/css">
  .orange {
    color: #ff7a00;
  }
  </style>

</head>
<body class="hold-transition login-page sty1">
  <div class="login-box sty1">
    <div class="login-box-body sty1">
      <div class="login-logo">

      <h4 class="logo-lg">INTEC SUP<span class="orange" style="font-size:40px;">.</span></h4>

      </div>
      <?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
      <form method="post">
      <?= csrf_field() ?>
        <h6>Entrez votre adresse e-mail ci-dessous et nous vous enverrons la marche à suivre. Si vous ne voyez pas notre e-mail, regardez dans votre dossier Spam.</h6>
        <div class="form-group has-feedback">
          <input type="email" class="form-control sty1" name="email" id="email" placeholder="Adresse e-mail" required="required">
        </div>
        <span style="font-size: smaller;" class="error-message" id="email-error"></span>
        <div>
          <div class="col-xs-8">
            <div class="checkbox icheck">
            </div>
            <!-- /.col -->
            <div class="col-xs-4 m-t-1">
              <button type="submit" id="submitBtn" class="btn btn-primary btn-block btn-flat">Envoyer ma demande</button>
            </div>
            <p>
              <a href="<?= base_url('auth/connexion')?>" class="text-center"><i class="fa fa-arrow-left"></i> Retourner en arrière</a>
            </p>
            <!-- /.col --> 
          </div>
        </form>
        
        <!-- /.social-auth-links -->
        
      </div>
      <!-- /.login-box-body --> 
    </div>
    <!-- /.login-box --> 
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <!-- jQuery 3 --> 
    <script src="<?= base_url('public/dist/js/jquery.min.js')?>"></script> 

    <!-- v4.0.0-alpha.6 --> 
    <script src="<?= base_url('public/dist/bootstrap/js/bootstrap.min.js')?>"></script> 

    <!-- template --> 
    <script src="<?= base_url('public/dist/js/niche.js')?>"></script>

    <script>
      (function(){
      $(document).ready(function () {
        $("#submitBtn").prop("disabled", true);

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

        // Fonction pour activer ou désactiver le bouton "Se connecter" en fonction de la validation des champs
        function toggleSubmitButton() {
          if (validateEmail()) {
            $("#submitBtn").prop("disabled", false);
          } else {
            $("#submitBtn").prop("disabled", true);
          }
        }

        // Écouter les changements dans les champs nom, prénom, pseudo, email et téléphone
        $("#email").on("input", function () {
          toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
        });
      });
    })();
  </script>

  </body>
  </html>