<!--/**Application développée par OUMAR CISSE *Ingenieur décisionnel et Head of Financial Data Analysis *Email cisseoumar621@gmail.com*/-->
<!DOCTYPE html>
<html lang="fr">

<head>
<meta name="description" content="Connectez-vous à votre compte bibliothèque en ligne pour accéder à vos emprunts, réservations et plus encore. Interface sécurisée et facile d'utilisation.">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BIBLIOTHEQUE | CONNEXION </title>
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js')}}"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
<![endif]-->

<style>
  .pages-faq {
    position: fixed;
    bottom: 10px;
    right: 20px;
  }
</style>

</head>

<body class="hold-transition login-page sty1">
  <div class="login-box sty1">
    <div class="login-box-body sty1">
      <div class="login-logo">

        <!--  <img src="{{url('img/logo-login.jpeg')}}" alt="logo" width="250">-->

      </div>
      <!-- <p class="login-box-msg">Veuillez-vous connecter</p> -->
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <?= $error ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="role" required id="account-type" />
        <div class="row d-flex d-justify-content-center d-align-iems-center mb-10">
          <div class="col-md-12">
            <div class="switcher-login">
              <div>
                <a id="previous-account-type" class="btn btn-primary" href="#" role="button"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i></a>
              </div>
              <div id="account-type-displayer" class="bold">Se connecter en tant que ?</div>
              <div><a name="" id="next-account-type" class="btn btn-primary" href="#" role="button"><i
                    class="fa fa-arrow-right" aria-hidden="true"></i></a> </div>
            </div>
          </div>
        </div>

        &nbsp;
        <div class="form-group has-feedback">
          <input type="email" class="form-control sty1" name="email" placeholder="Adresse e-mail">
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control sty1" name="password" placeholder="Mot de passe">
        </div>
        <div class="checkbox icheck">
  <label>
    <a href="<?= base_url('auth/resetpassword') ?>" class="pull-right"><i class="fa fa-lock"></i> Mot de passe oublié? </a>
  </label>
</div>

<!-- Conteneur pour le lien du mot de passe oublié -->
<div class="pages-faq">
  <a href="<?= base_url('auth/pages-faq') ?>" class="pull-right">
    <i class="fa fa-question-circle"></i> Pages-faq
  </a>
</div>
        <div>
          <!-- /.col -->
          <div class="col-xs-4 m-t-1">
            <button type="submit" id="submit" class="btn btn-primary btn-block btn-flat">Se connecter</button>
          </div>
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
  <script src="<?= base_url('public/dist/js/jquery.min.js') ?>"></script>

  <!-- v4.0.0-alpha.6 -->
  <script src="<?= base_url('public/dist/bootstrap/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('public/dist/js/js/login.js') ?>"></script>
  <!-- template -->
  <script src="<?= base_url('public/dist/js/niche.js') ?>"></script>


</body>

</html>