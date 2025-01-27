<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Tableau de bord
<hr>
<h4 id='p1'></h4>
<h4>
    <marquee>Bienvenue
        <?= esc(mb_ucwords(session()->get('prenom'))) ?>
        <?= esc(mb_strtoupper(session()->get('nom'), 'UTF-8')) ?>
        dans l'application de gestion de bibliothèque.
    </marquee>
</h4>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Accueil</a></li>
<li><i class="fa fa-angle-right"></i> Tableau de bord</li>
<?= $this->endSection() ?>
<link rel="stylesheet" href="<?= base_url('public/dist/jquery-toast-plugin/dist/jquery.toast.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/dist/plugins/chartist-js/chartist.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <a href="<?= base_url('livres') ?>">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ti-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-number"><?= $totalQuantite ?></span>
                    <span class="info-box-text">Total Quantité Livres</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>

    <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'INVITE'])): ?>
        <!-- /.col -->
        <div class="col-lg-3 col-xs-6">
            <a href="<?= base_url('auteurs') ?>">
                <div class="info-box"> <span class="info-box-icon bg-green"><i class="ti-user"></i></span>
                    <div class="info-box-content"> <span class="info-box-number"><?= $totalAuteurs ?></span> <span
                            class="info-box-text">Total Auteurs</span></div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <!-- /.col -->
        <div class="col-lg-3 col-xs-6">
            <a href="<?= base_url('user/index') ?>">
                <div class="info-box"> <span class="info-box-icon bg-yellow"><i class="ti-user"></i></span>
                    <div class="info-box-content"> <span class="info-box-number"><?= $totalStudents; ?></span> <span
                            class="info-box-text">Total Etudiants inscrit(e)s</span></div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <!-- /.col -->
        <div class="col-lg-3 col-xs-6">
            <a href="<?= base_url('user/professeur') ?>">
                <div class="info-box"> <span class="info-box-icon bg-red"><i class="ti-user"></i></span>
                    <div class="info-box-content"> <span class="info-box-number"><?= $totalTeachers; ?></span> <span
                            class="info-box-text">Total professeurs inscrit(e)s</span></div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
    <?php endif; ?>
    <?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
        <div class="col-lg-4 col-xs-6">
            <a href="<?= base_url('memoires') ?>">
                <div class="info-box"> <span class="info-box-icon bg-red"><i class="icon-layers"></i></span>
                    <div class="info-box-content"> <span class="info-box-number"><?= $totalMemoires; ?></span> <span
                            class="info-box-text">Total Mémoires</span></div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <div class="col-lg-5 col-xs-6">
            <a href="<?= base_url('reservations') ?>">
                <div class="info-box"> <span class="info-box-icon bg-blue"><i class="icon-layers"></i></span>
                    <div class="info-box-content"> <span class="info-box-number"><?= $totalStatus1; ?></span> <span
                            class="info-box-text">Total Réservations accepté</span></div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <div class="col-lg-4 col-xs-6">
            <a href="<?= base_url('reservations') ?>">
                <div class="info-box"> <span class="info-box-icon bg-red"><i class="icon-layers"></i></span>
                    <div class="info-box-content"> <span class="info-box-number"><?= $totalStatus0; ?></span> <span
                            class="info-box-text">Total Réservations en cours</span></div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <div class="col-lg-4 col-xs-6">
            <a href="<?= base_url('emprunts') ?>">
                <div class="info-box"> <span class="info-box-icon bg-yellow"><i class="icon-layers"></i></span>
                    <div class="info-box-content"> <span class="info-box-number"><?= $totalRetourStatus1; ?></span> <span
                            class="info-box-text">Total Livres emprunté</span></div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <div class="col-lg-4 col-xs-6">
            <a href="<?= base_url('emprunts') ?>">
                <div class="info-box"> <span class="info-box-icon bg-green"><i class="icon-layers"></i></span>
                    <div class="info-box-content"> <span class="info-box-number"><?= $totalRetourStatus0; ?></span> <span
                            class="info-box-text">Total emprunt en cours</span></div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
    <?php endif; ?>
    <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'INVITE'])): ?>
        <div class="col-lg-6 col-xlg-6">
            <div class="info-box">
                <div class="d-flex flex-wrap">
                    <span class="text-black">LES 5 DERNIERS ETUDIANTS INSCRITS</span>
                    <hr />
                    <div class="table-responsive">
                        <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
                            <thead>
                                <tr>
                                    <th>ID #</th>
                                    <th>Nom de l'étudiant</th>
                                    <th>Matricule</th>
                                    <th>Cycle-Filière</th>
                                    <th>E-mail <i class="fa fa-check-circle text-success"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lastStudents as $k => $lastStudent): ?>
                                    <tr>
                                        <td><?= $k + 1; ?></td>
                                        <td>
                                            <div class="media align-items-center">
                                                <div class="media-img-wrap d-flex mr-10">
                                                    <div class="avatar avatar-sm">

                                                        <span class="avatar-text avatar-text-purple rounded-circle">
                                                            <span class="initial-wrap">
                                                                <span><?= esc($lastStudent->initials) ?></span>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <span
                                                    class="btn btn-rounded btn-info btn-sm"><?= strtoupper($lastStudent->nom) . ' ' . strtoupper($lastStudent->prenom); ?></span>
                                            </div>
                                        </td>
                                        <td><span class="btn btn-rounded btn-info btn-sm"><?= $lastStudent->matricule; ?></span>
                                        </td>
                                        <td><span
                                                class="btn btn-rounded btn-info btn-sm"><?= esc($lastStudent->nom_cycle . '-' . $lastStudent->nom_filiere); ?></span>
                                        </td>
                                        <td><span
                                                class="btn btn-rounded btn-info btn-sm"><?= esc($lastStudent->email); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'INVITE'])): ?>
        <div class="col-lg-6 col-xlg-6">
            <div class="info-box">
                <div class="d-flex flex-wrap">
                    <span class="text-black">LES 5 DERNIERS PROFESSEURS INSCRITS</span>
                    <hr />
                    <div class="table-responsive">
                        <table id="dataList1" class="table table-bordered table-hover" data-name="cool-table">
                            <thead>
                                <tr>
                                    <th>ID #</th>
                                    <th>Nom du professeur</th>
                                    <th>Matricule</th>
                                    <th>E-mail <i class="fa fa-check-circle text-success"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lastTeachers as $k => $lastTeacher): ?>
                                    <tr>
                                        <td><?= $k + 1; ?></td>
                                        <td>
                                            <div class="media align-items-center">
                                                <div class="media-img-wrap d-flex mr-10">
                                                    <div class="avatar avatar-sm">

                                                        <span class="avatar-text avatar-text-teal rounded-circle">
                                                            <span class="initial-wrap">
                                                                <span><?= esc($lastTeacher->initials) ?></span>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="media-body">
                                                    <div class="text-capitalize font-weight-500 text-dark">
                                                        <span
                                                            class="btn btn-rounded btn-primary btn-sm"><?= strtoupper($lastTeacher->nom) . ' ' . strtoupper($lastTeacher->prenom); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span
                                                class="btn btn-rounded btn-primary btn-sm"><?= esc($lastTeacher->matricule); ?></span>
                                        </td>
                                        <td><span
                                                class="btn btn-rounded btn-primary btn-sm"><?= esc($lastTeacher->email); ?></span>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- /.col -->
</div>
<div class="row">
    <div class="col-lg-5 col-xlg-3">
        <div class="info-box">
            <div class="d-flex flex-wrap">
                <div>
                    <h4 class="text-black">Nombre d'étudiants inscrit par cycle</h4>
                </div>
            </div>
            <div class="m-t-2">
                <canvas id="pie-chart" height="210"></canvas>
            </div>
        </div>
    </div>
    <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'INVITE'])): ?>
        <div class="col-lg-7 col-xlg-7">
            <div class="info-box">
                <div class="d-flex flex-wrap">
                    <span class="text-black">Statistique sur les visites</span>
                    <form class="form-inline" style="margin-right: 20px">
                        <?= csrf_field() ?>
                        <div class="form-group inline" style="margin-left: 15px;">
                            <label for="">Du :&nbsp;</label>
                            <input type="date" value="<?php echo isset($start_date) ? $start_date : date('Y-m-d'); ?>"
                                name="start_date" id="start_date" class="form-control" placeholder=""
                                aria-describedby="helpId">
                        </div>
                        <div class="form-group inline" style="margin-left: 15px;">
                            <label for="">Au :&nbsp;</label>
                            <input type="date" value="<?php echo isset($end_date) ? $end_date : date('Y-m-d'); ?>"
                                name="end_date" id="end_date" class="form-control" placeholder="" aria-describedby="helpId">
                        </div>
                        &nbsp;
                        <button class="btn btn-rounded btn-primary btn-sm" type="submit" role="button">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                        </button>&nbsp;<a href="<?= base_url('dashboard') ?>" class="btn btn-rounded btn-primary btn-sm"><i
                                class="fa fa-refresh"></i></a>
                    </form>
                    <hr />
                    <div class="table-responsive">
                        <table id="dataList2" class="table table-bordered table-hover" data-name="cool-table">
                            <thead>
                                <tr>
                                    <td colspan="3"><strong>Les 3 étudiant.e.s ayant effectué le plus de visites</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>ID #</th>
                                    <th>Nom complet</th>
                                    <th>Cycle-Filière</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mostVisitedStudents as $k => $mostVisitedStudent): ?>
                                    <tr>
                                        <td><?= $k + 1; ?></td>
                                        <td>
                                            <span class="btn btn-rounded btn-warning btn-sm">
                                                <?= strtoupper($mostVisitedStudent->nom . '-' . $mostVisitedStudent->prenom) ?>
                                                (<span
                                                    style="font-size: smaller; font-style: italic;"><?= $mostVisitedStudent->matricule; ?></span>)
                                            </span>
                                        </td>
                                        <td>
                                            <span class="btn btn-rounded btn-warning btn-sm">
                                                <?= strtoupper($mostVisitedStudent->nom_cycle . '-' . $mostVisitedStudent->nom_filiere) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <thead>
                                    <tr>
                                        <td colspan="3"><strong>Les 3 cycles et filières ayant effectué le plus de
                                                visites</strong></td>
                                    </tr>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Cycle</th>
                                        <th>Filière</th>
                                        <th>Nombre de visites</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <?php foreach ($mostVisitedCycleAndFiliere as $k => $item): ?>
                                    <tr>
                                        <td><?= $k + 1; ?></td>
                                        <td> <span class="btn btn-rounded btn-warning btn-sm">
                                                <?= mb_strtoupper($item->nom_cycle, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="btn btn-rounded btn-warning btn-sm"><?= mb_strtoupper($item->nom_filiere, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="btn btn-rounded btn-warning btn-sm">
                                                <?= $item->visite_count; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                   
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
    <div class="col-lg-7 col-xlg-7">
        <div class="info-box">
            <div class="d-flex flex-wrap">
                <span class="text-black">MES 5 DERNIERES RESERVATIONS</span>
                <hr />
                <div class="table-responsive">
                    <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
                        <thead>
                            <tr>
                                <th>ID #</th>
                                <th>Nom complet</th>
                                <th>Livre</th>
                                <th>Nom de l'auteur</th>
                                <th>Stock</th>
                                <th>Date de reservation</th>
                                <th>Date statut</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lastReservations as $k => $lastReservation): ?>
                                <tr>
                                    <td><?= $k + 1; ?></td>
                                    <td>
                                        <span class="btn btn-rounded btn-primary btn-sm">
                                            <?= strtoupper($lastReservation->nom . '-' . $lastReservation->prenom) ?>(<span
                                                style="font-size: smaller; font-style: italic;"><?= $lastReservation->matricule; ?></span>)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="btn btn-rounded btn-primary btn-sm">
                                            <?= strtoupper($lastReservation->nom_livre) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="btn btn-rounded btn-primary btn-sm">
                                            <?= strtoupper($lastReservation->nom_auteur) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($lastReservation->qte_stock <= 0): ?>
                                            <span class="btn btn-rounded btn-danger btn-sm">Stock épuisé</span>
                                        <?php else: ?>
                                            <span class="btn btn-rounded btn-success btn-sm"><?= $lastReservation->qte_stock ?>
                                                en stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span
                                            class="btn btn-rounded btn-primary btn-sm"><?= date('d/m/Y à H:i', strtotime($lastReservation->date_reservation)) ?></span>
                                    </td>
                                    <td> <?php
                                    if (!empty($lastReservation->date_status) && $lastReservation->date_status != '0000-00-00') {
                                        echo '<span class="btn btn-rounded btn-primary btn-sm">' . date('d/m/Y à H:i', strtotime($lastReservation->date_status)) . '</span>';
                                    } else {
                                        echo '<span class="btn btn-rounded btn-secondary btn-sm">En attente</span>';
                                    }
                                    ?></td>
                                    <td>
                                        <?php
                                        switch ($lastReservation->status) {
                                            case 0:
                                                echo '<span class="btn btn-rounded btn-secondary btn-sm">En attente</span>';
                                                break;
                                            case 1:
                                                echo '<span class="btn btn-rounded btn-success btn-sm">Accepté</span>';
                                                break;
                                            case 2:
                                                echo '<span class="btn btn-rounded btn-danger btn-sm">Refusé</span>';
                                                break;
                                            default:
                                                echo '<span class="btn btn-rounded btn-default btn-sm">Statut inconnu</span>';
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                </div>
<?php endif; ?>
<?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'ETUDIANT'])): ?>
    <div class="col-lg-12 col-xlg-6">
        <div class="info-box">
            <div class="d-flex flex-wrap">
                <div>
                    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                        <h4 class="text-black">Graphique de la production des emprunts des étudiants</h4>
                    <?php elseif (session()->get('role') === 'ETUDIANT'): ?>
                        <h4 class="text-black">Graphique de la production de mes emprunts</h4>
                    <?php endif; ?>
                </div>

                <div class="ml-auto">
                    <ul class="list-inline">

                    </ul>
                </div>
            </div>
            <div>
                <canvas id="line-chart"></canvas>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'PROFESSEUR'])): ?>
    <div class="col-lg-12 col-xlg-6">
        <div class="info-box">
            <div class="d-flex flex-wrap">
                <div>
                    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                        <h4 class="text-black">Graphique de la production des emprunts des professeurs</h4>
                    <?php elseif (session()->get('role') === 'PROFESSEUR'): ?>
                        <h4 class="text-black">Graphique de la production de mes emprunts</h4>
                    <?php endif; ?>
                </div>

                <div class="ml-auto">
                    <ul class="list-inline">

                    </ul>
                </div>
            </div>
            <div>
                <canvas id="line-chart2"></canvas>
            </div>
        </div>
        </div>
   
<?php endif; ?>
    </div>

<?= $this->section('addjs') ?>
<!-- Toast JavaScript -->
<script src="<?= base_url('public/dist/jquery-toast-plugin/dist/jquery.toast.min.js') ?>"></script>
<script src="<?= base_url('public/dist/jquery-toast-plugin/dist/dashboard-data.js') ?>"></script>
<!-- Chartjs JavaScript -->
<script src="<?= base_url('public/dist/plugins/chartjs/chart.min.js') ?>"></script>
<script src="<?= base_url('public/dist/c3-charts/c3.min.js') ?>"></script>
<script src="<?= base_url('public/dist/c3-charts/d3.min.js') ?>"></script>

<script>
    // ======
    // line chart
    // ======   
    var ctx = document.getElementById('line-chart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: <?php echo $fiches_graph; ?>,

        options: {
            responsive: true
        }
    });
</script>
<script>
    // ======
    // line chart
    // ======
    var ctx = document.getElementById('line-chart2').getContext('2d');
    var chart = new Chart(ctx, { // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: <?php echo $fiches_graph2; ?>,
        options: {
            responsive: true
        }
    });
</script>
<script>
    // ======
    // Pie chart
    // ======
    new Chart(document.getElementById("pie-chart"), {
    type: 'pie',
    data: {
        labels: [
            <?php foreach ($studentStatByCycle as $stat): ?>
                '<?php echo $stat->cycle; ?>',
            <?php endforeach; ?>
            <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'PROFESSEUR']) && $totalTeachers > 0): ?>
                'PROFESSEUR',  
            <?php endif; ?>
        ],
        datasets: [
            {
                label: 'My First Dataset',
                data: [
                    <?php foreach ($studentStatByCycle as $stat): ?>
                        <?php echo $stat->number; ?>,
                    <?php endforeach; ?>
                    <?php if (in_array(session()->get('role'), ['ADMINISTRATEUR', 'PROFESSEUR']) && $totalTeachers > 0): ?>
                        <?php echo $totalTeachers; ?>,
                    <?php endif; ?>
                ],
                backgroundColor: [
                    'rgb(230, 150, 245)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 230, 86)',
                    'rgb(255, 70, 80)',
                    'rgb(190, 200, 230)'
                ]
            }
        ]
    },
    options: {
        responsive: true
    }
});

</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>