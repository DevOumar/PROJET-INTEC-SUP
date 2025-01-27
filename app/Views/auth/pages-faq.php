<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BIBLIOTHEQUE - FAQ</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />

    <!-- logo -->
    <link rel="icon" href="<?= base_url('public/dist/img/logo-intecsup.png') ?>" type="image/png" />

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
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

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
            <a href="<?= base_url('auth/connexion') ?>" class="logo blue-bg">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <h4 class="logo-lg text-white">INTEC SUP<span class="orange" style="font-size:40px;">.</span></h4>
            </a>
        </header>
        <!-- Left side column. contains the logo and sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header sty-one">
                <h1 class="text-black">Faqs</h1>
                <ol class="breadcrumb">
                    <li class="sub-bread"> Pages</li>
                    <li><i class="fa fa-angle-right"></i> Faqs</li>
                </ol>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="info-box">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title"> <a role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">Q1. Comment puis-je m'inscrire à la bibliothèque
                                        ?</a> </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                aria-labelledby="headingOne">
                                <div class="panel-body">Réponse : L'inscription s'effectue auprès du responsable de la
                                    bibliothèque. Une fois celle-ci validée, vous recevrez vos identifiants ainsi qu'un
                                    numéro matricule, qui vous permettront d'emprunter des livres et de profiter des
                                    autres services de la bibliothèque.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">Q2. Puis-je m'inscrire en ligne ?</a> </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingTwo">
                                <div class="panel-body">Réponse : Non, l'inscription doit se faire en personne auprès du
                                    responsable de la bibliothèque. </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">Q3. Comment puis-je emprunter des livres ?</a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingThree">
                                <div class="panel-body">Réponse : Vous devez être membre de la bibliothèque pour
                                    emprunter des livres. Une fois inscrit, présentez votre numéro matricule, choisissez
                                    vos livres, et effectuez l'emprunt. L'inscription et l'emprunt se font sur place.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingFour">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseFour" aria-expanded="false"
                                        aria-controls="collapseFour">Q4. Combien de livres puis-je emprunter à la fois
                                        ?</a> </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingFour">
                                <div class="panel-body">Réponse : Vous pouvez emprunter jusqu'à 2 livres à la fois pour
                                    une durée maximale de 2 semaines. Au-delà de cette limite, il faudra attendre de
                                    rendre des livres avant d'en emprunter de nouveaux.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingFive">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseFive" aria-expanded="false"
                                        aria-controls="collapseFive">Q5. Puis-je prolonger la durée d'emprunt ?</a>
                                </h4>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingFive">
                                <div class="panel-body">Réponse : Oui, vous pouvez prolonger la durée de votre emprunt
                                    en ligne via votre espace membre, sauf si le livre est réservé par un autre
                                    utilisateur. La prolongation est possible pour une durée supplémentaire de 2
                                    semaines maximum.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingSix">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseSix" aria-expanded="false"
                                        aria-controls="collapseSix">Q6. Que se passe-t-il si je rends un livre en retard
                                        ?</a> </h4>
                            </div>
                            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingSix">
                                <div class="panel-body">Réponse : Un frais de retard est appliqué pour chaque jour
                                    au-delà de la date de retour prévue. Le montant des frais varie en fonction de la
                                    durée du retard et du nombre de livres en retard. Si les frais s'accumulent au-delà
                                    d'un certain seuil, votre accès à l'emprunt peut être temporairement suspendu
                                    jusqu'au règlement des frais.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingSeven">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseSeven" aria-expanded="false"
                                        aria-controls="collapseSeven">Q7. Comment puis-je réserver un livre ?</a> </h4>
                            </div>
                            <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingSeven">
                                <div class="panel-body">Réponse : Vous pouvez réserver un livre directement à la
                                    bibliothèque ou via votre espace membre en ligne. Une fois la réservation approuvée,
                                    vous recevez une notification dans votre espace membre. Vous avez 24 heures pour
                                    venir retirer le livre auprès du responsable. N'oubliez pas d'imprimer votre ticket
                                    de réservation pour le présenter lors du retrait.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingEight">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseEight" aria-expanded="false"
                                        aria-controls="collapseEight">Q8. Puis-je faire une réservation de livre depuis
                                        chez moi ?</a> </h4>
                            </div>
                            <div id="collapseEight" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingEight">
                                <div class="panel-body">Réponse : Oui, vous pouvez réserver des livres en ligne depuis
                                    chez vous via l'espace membre de la bibliothèque. Une fois connecté, recherchez le
                                    livre que vous souhaitez, et si celui-ci est disponible pour réservation, suivez les
                                    instructions pour compléter la réservation.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingNine">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseNine" aria-expanded="false"
                                        aria-controls="collapseNine">Q9. Quelles sont les conditions pour une
                                        réservation de livre ?</a> </h4>
                            </div>
                            <div id="collapseNine" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingNine">
                                <div class="panel-body">
                                    <ul>
                                        <li>Vous avez 24 heures pour effectuer l'emprunt une fois que la
                                            réservation a
                                            été
                                            approuvée.</li>
                                        <li>Vous devez imprimer votre ticket de réservation pour le présenter lors du
                                            retrait du
                                            livre.</li>
                                        <li>Toute réservation non traitée par le responsable dans un délai de 24 heures
                                            sera
                                            automatiquement annulée.</li>
                                        <li>Si vous avez déjà des réservations en attente depuis moins de 48 heures,
                                            vous devrez
                                            attendre que ces 48 heures s'écoulent avant de pouvoir effectuer une
                                            nouvelle
                                            réservation.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTen">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseTen" aria-expanded="false"
                                        aria-controls="collapseTen">Q10. Que se passe-t-il si je ne viens pas chercher
                                        ma réservation dans les temps ?</a> </h4>
                            </div>
                            <div id="collapseTen" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingTen">
                                <div class="panel-body">Réponse : Si vous ne récupérez pas le livre réservé dans les 24
                                    heures suivant l'approbation de la réservation, celle-ci sera automatiquement
                                    annulée. Le livre sera remis à disposition des autres membres ou à la personne
                                    suivante sur la liste d'attente.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingEleven">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseEleven" aria-expanded="false"
                                        aria-controls="collapseEleven">Q11. Puis-je annuler une réservation de livre
                                        ?</a> </h4>
                            </div>
                            <div id="collapseEleven" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingEleven">
                                <div class="panel-body">Réponse : Oui, vous pouvez annuler une réservation à tout moment
                                    avant de venir chercher le livre, soit en ligne via votre espace membre, soit en
                                    contactant directement le responsable de la bibliothèque. Cela permet à d'autres
                                    utilisateurs de réserver le livre.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwelve">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseTwelve" aria-expanded="false"
                                        aria-controls="collapseTwelve">Q12. Combien de temps puis-je garder un livre
                                        réservé avant de devoir le retourner ?</a> </h4>
                            </div>
                            <div id="collapseTwelve" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingTwelve">
                                <div class="panel-body">Réponse : Une fois que vous avez emprunté un livre réservé, la
                                    durée de prêt est la même que pour un emprunt classique : 2 semaines. Vous pouvez
                                    également prolonger ce prêt si le livre n'est pas réservé par un autre membre.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThirteen">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseThirteen" aria-expanded="false"
                                        aria-controls="collapseThirteen">Q13. Puis-je réserver plusieurs livres en même
                                        temps
                                        ?</a> </h4>
                            </div>
                            <div id="collapseThirteen" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingThirteen">
                                <div class="panel-body">Réponse : Oui, vous pouvez réserver plusieurs livres en même
                                    temps, dans la limite de 2 réservations actives. Cependant, vous devez retirer
                                    chaque réservation dans les 24 heures suivant son approbation.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingFourteen">
                                <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseFourteen" aria-expanded="false"
                                        aria-controls="collapseFour">Conseils supplémentaires pour les
                                        utilisateurs(trice)
                                        :</a> </h4>
                            </div>
                            <div id="collapseFourteen" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingThirteen">
                                <div class="panel-body">
                                    <ul>
                                        <li>Vérifiez régulièrement vos notifications. Les réservations approuvées
                                            expirent au bout de 24 heures, et il est important de ne pas rater ce délai.
                                        </li>
                                        <li>Utilisez l'option de prolongation d'emprunt en ligne pour éviter les frais
                                            de retard et permettre à d'autres utilisateurs d'accéder aux livres plus
                                            rapidement.</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">Version 1.2</div>
            Copyright © 2024 INTEC SUP. All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="<?= base_url('public/dist/js/jquery.min.js') ?>"></script>

    <!-- v4.0.0-alpha.6 -->
    <script src="<?= base_url('public/dist/bootstrap/js/bootstrap.min.js') ?>"></script>

    <!-- template -->
    <script src="<?= base_url('public/dist/js/niche.js') ?>"></script>
</body>

</html>