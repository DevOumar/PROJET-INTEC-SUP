<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reinitialisation - Mot de passe</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />

  <!-- logo -->
<link rel="icon" href="<?= base_url('public/dist/img/logo-intecsup.png')?>" type="image/png" />

  <!-- v4.0.0-alpha.6 -->
  <link rel="stylesheet" href="<?= base_url('public/dist/bootstrap/css/bootstrap.min.css') ?>">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('public/dist/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/css/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/css/et-line-font/et-line-font.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/css/themify-icons/themify-icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/plugins/hmenu/ace-responsive-menu.css') ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-box-body">
      <h3 class="login-box-msg m-b-1">Récupérer mot de passe</h3>
      <p>Ça y est, vous êtes presque arrivé ! </p>
      <form method="post" action="<?= base_url('auth/confirmation') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="token_activation" value="<?= $token_activation ?>">
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <div class="form-group">
          <label for="pwd1">Nouveau mot de passe</label>
          <div class="input-group">
            <div class="input-group-addon"><i class="ti-lock"></i></div>
            <input class="form-control" name="new_password" id="new_password" placeholder="Nouveau mot de passe"
              type="text">
          </div>
          <span style="font-size: smaller;" class="error-message" id="new_password-error"></span>
        </div>
        <div class="form-group">
          <label for="pwd2">Confirmer votre mot de passe</label>
          <div class="input-group">
            <div class="input-group-addon"><i class="ti-lock"></i></div>
            <input class="form-control" name="con_password" id="con_password" placeholder="Confirmer votre mot de passe"
              type="text">
          </div>
          <span style="font-size: smaller;" class="error-message" id="con_password-error"></span>
        </div>
        <div>
          <div class="col-xs-4 m-t-1">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-block btn-flat">Réinitialiser</button>
          </div>
          <div class="m-t-2">
            <a href="<?= base_url('auth/connexion') ?>" class="text-center">Connectez-vous facilement !</a>
          </div>
        </div>
        <!-- /.col -->
    </div>
    </form>
  </div>
  <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->
  <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
  <!-- jQuery 3 -->
  <script src="<?= base_url('public/dist/js/jquery.min.js') ?>"></script>

  <!-- v4.0.0-alpha.6 -->
  <script src="<?= base_url('public/dist/bootstrap/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('public/dist/js/function.js') ?>"></script>
  <!-- template -->
  <script src="<?= base_url('public/dist/js/niche.js') ?>"></script>
  <script type="text/javascript" src="<?= base_url('public/dist/plugins/hmenu/ace-responsive-menu.js') ?>"></script>
  <!--Plugin Initialization-->
  <script type="text/javascript">
    $(document).ready(function () {
      $("#respMenu").aceResponsiveMenu({
        resizeWidth: '768', // Set the same in Media query       
        animationSpeed: 'fast', //slow, medium, fast
        accoridonExpAll: false //Expands all the accordion menu on click
      });
    });
  </script>
  <script>
    (function() {
    $(document).ready(function () {


      $(document).ready(function () {
        $("#submitBtn").prop("disabled", true);


        function validatePassword() {
          var password = $("#new_password").val();
          var confirmPassword = $("#con_password").val();

          // Vérifier si le mot de passe respecte les critères (minimum 8 caractères avec au moins un caractère spécial)
          if (password.length < 8 || !/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            $("#new_password-error").html("Le mot de passe doit contenir au moins 8 caractères avec un caractère spécial.").css("color", "red");
            return false;
          } else {
            $("#new_password-error").html("").css("color", "inherit");

            // Vérifier si les deux mots de passe correspondent
            if (password !== confirmPassword) {
              $("#con_password-error").html("Les mots de passe ne correspondent pas.").css("color", "red");
              return false;
            } else {
              $("#con_password-error").html("").css("color", "inherit");
              return true;
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

        // Écouter les changements dans les champs nom, prénom, pseudo, email et téléphone
        $("#new_password, #con_password").on("input", function () {
          toggleSubmitButton(); // Vérifier la validité des champs et activer/désactiver le bouton en conséquence
        });


      });

    });
  })();
  </script>
</body>

</html>