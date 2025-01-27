
<!--/**Application développée par OUMAR CISSE *Ingenieur décisionnel et Head of Financial Data Analysis *Email cisseoumar621@gmail.com*/-->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?= $this->renderSection('title') ?> - BIBLIOTHEQUE</title>
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
<link rel="stylesheet" href="<?= base_url('public/dist/plugins/dropify/dropify.min.css')?>">
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('public/dist/plugins/datatables/css/dataTables.bootstrap.min.css')?>">


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<?= $this->renderSection('addcss') ?>
<style type="text/css">
  .orange {
    color: #ff7a00;
  }
  </style>
  
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper boxed-wrapper">
  <header class="main-header"> 
    <!-- Logo --> 
    <a href="<?= base_url('dashboard') ?>" class="logo blue-bg"> 
    <!-- mini logo for sidebar mini 50x50 pixels --> 
    <h4 class="logo-lg text-white">INTEC SUP<span class="orange" style="font-size:40px;">.</span></h4>
    </a> 
    <!-- Header Navbar: style can be found in header.less -->
    <?= $this->include('partials/navbar') ?>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar"> 
    <!-- sidebar: style can be found in sidebar.less -->
    <?= $this->include('partials/sidebar') ?>
    
    <!-- /.sidebar --> 
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1><?= $this->renderSection('pageTitle') ?></h1>
            <ol class="breadcrumb">
            <?= $this->renderSection('breadcrumb') ?>
            </ol>
        </div>
    
    <!-- Main content -->
    <div class="content"> 
        <?= $this->renderSection('content') ?>
    </div>   
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->
  <?= $this->include('partials/footer') ?>
</div>
<!-- ./wrapper --> 

<!-- jQuery 3 --> 
<script src="<?= base_url('public/dist/js/jquery.min.js')?>"></script> 

<!-- v4.0.0-alpha.6 --> 
<script src="<?= base_url('public/dist/bootstrap/js/bootstrap.min.js')?>"></script> 

<!-- template --> 
<script src="<?= base_url('public/dist/js/niche.js')?>"></script> 

<script src="<?= base_url('public/dist/plugins/hmenu/ace-responsive-menu.js')?>"></script>
<script src="<?= base_url('public/dist/js/js/function.js')?>"></script>
<script src="<?= base_url('public/dist/js/js/dateheure.js')?>"></script>
<script src="<?= base_url('public/dist/plugins/dropify/dropify.min.js')?>"></script>
<!-- DataTables -->
<script src="<?= base_url('public/dist/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('public/dist/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

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
<?= $this->renderSection('addjs') ?>
</body>
</html>
