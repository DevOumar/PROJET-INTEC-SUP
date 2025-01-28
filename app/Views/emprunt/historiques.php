<?= $this->extend('layouts/base'); ?>
<?= $this->section('title'); ?>
Historique des emprunts
<?= $this->endSection(); ?>
<?= $this->section('pageTitle'); ?>
Historique de tous les emprunts <a href="<?= base_url('emprunts/historiques') ?>"
    class="btn btn-rounded btn-primary btn-sm"><i class="fa fa-refresh"></i></a>
<?= $this->endSection(); ?>
<?= $this->section('addcss'); ?>
<!-- daterangepicker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?= $this->endSection(); ?>
<?= $this->section('breadcrumb'); ?>
<div class="pull-right mt-5 d-flex " style="position: absolute; right: 20px; font-size:13px;">
    <div id="reportrange" class="form-control text-truncate">
        <span>January 5, 2022 - February 3, 2022</span>
        <i class="fa fa-caret-down text-light-40 font-12 ml-10"></i>&nbsp;
    </div>
    <form id="date-filter" method="POST" hidden>
        <?= csrf_field() ?>
        <input type="date" value="<?= isset($start_date) ? $start_date : '' ?>" name="start_date" id="start_date">
        <input type="date" value="<?= isset($end_date) ? $end_date : '' ?>" name="end_date" id="end_date">
        <input type="text" value="<?= isset($date_filter_chosen_label) ? $date_filter_chosen_label : '' ?>"
            name="date_filter_chosen_label" id="date_filter_chosen_label">
    </form>
    <button id="reportrange-toggler" class="btn btn-outline"
        style="background-color: #008000; box-shadow: 1px 2px 0 2px #FFFF; border-radius: 5px; margin-left: 20px;">
        <i class="fa fa-calendar text-light-40"></i>
    </button>
</div>
<li><a href="<?= base_url('emprunts') ?>">emprunt</a></li>
<li><i class="fa fa-angle-right"></i> Historique de tous les emprunts</li>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="info-box">
    <div class="col-md-6">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>
    <h4 class="text-black">Liste(<?= count($emprunt_filter_etudiant) ?>)
    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
    <a href="<?= base_url('emprunts/exportFiltered/' . $start_date . '/' . $end_date . '/' . $date_filter_chosen_label) ?>"
       class="btn btn-rounded btn-success btn-sm <?= empty($emprunt_filter_etudiant) ? 'disabled' : ''; ?>">
        <i class="fa fa-file-excel-o"></i> Exporter en excel
    </a>
<?php endif; ?>
    </h4>
    <hr />
    <div class="table-responsive">
        <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
            <thead>
                <tr>
                    <th>ID #</th>
                    <th>Nom complet<span style="font-size: smaller;">(Rôle)</span></th>
                    <th>Matricule</th>
                    <th>Nom du livre</th>
                    <th>Date d'emprunt</th>
                    <th>Délai de retour</th>
                    <th>Date de retour</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emprunt_filter_etudiant as $key => $emprunt): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
                            <span style="font-size: smaller;" class="btn btn-rounded btn-success btn-sm">
                                <?= mb_strtoupper($emprunt->nom, 'UTF-8') ?>-<?= mb_strtoupper($emprunt->prenom, 'UTF-8') ?>(<span
                                    style="font-size: smaller;">
                                    <?php
                                    $role = mb_strtoupper($emprunt->role, 'UTF-8');
                                    $civilite = $emprunt->civilite;

                                    if ($role === 'ADMINISTRATEUR') {
                                        if ($civilite === 'Mme') {
                                            echo 'ADMINISTRATRICE';
                                        } else {
                                            echo 'ADMINISTRATEUR';
                                        }
                                    } elseif ($role === 'ETUDIANT') {
                                        if ($civilite === 'Mme') {
                                            echo 'ETUDIANTE';
                                        } else {
                                            echo 'ETUDIANT';
                                        }
                                    } elseif ($role === 'PROFESSEUR') {
                                        if ($civilite === 'Mme') {
                                            echo 'PROFESSEURE';
                                        } else {
                                            echo 'PROFESSEUR';
                                        }
                                    } else {
                                        echo $user->role; // Fallback au cas où le rôle ne correspondrait pas aux options ci-dessus
                                    }
                                    ?>
                                </span>)
                            </span>
                        </td>
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm" style="font-size: smaller;">
                                <?= mb_strtoupper($emprunt->matricule, 'UTF-7') ?>
                            </span>
                        </td>
                        <td>
                            <span>
                                <?= mb_strtoupper($emprunt->nom_livre, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <span style="font-size: smaller;" class="btn btn-rounded btn-primary btn-sm">
                                <?= date('d/m/Y à H:i', strtotime($emprunt->date_emprunt)) ?> </span>
                        </td>
                        <td>
                            <?php if (empty($emprunt->date_retour)): ?>
                                <?php $delaiLivre = date('d/m/Y', strtotime($emprunt->delai_retour)); ?>
                                <span class="btn btn-rounded btn-danger btn-sm">
                                    <?= $delaiLivre ?>
                                </span>
                                <?php if (strtotime($emprunt->delai_retour) < strtotime(date('Y-m-d'))): ?>
                                    <br><span class="btn btn-rounded btn-danger btn-sm">
                                        Délai expiré
                                    </span>
                                <?php endif ?>
                            <?php else: ?>
                                <span class="btn btn-rounded btn-success btn-sm">
                                    Retourné
                                </span>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if (empty($emprunt->date_retour)): ?>
                                <span class="btn btn-rounded btn-danger btn-sm">
                                    Livre non retourné
                                </span>
                            <?php else: ?>
                                <span class="btn btn-rounded btn-primary btn-sm">
                                    <?= date('d/m/Y à H:i', strtotime($emprunt->date_retour)) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#" class="view-emprunt" data-toggle="modal" data-target="#empruntModal"
                                data-id="<?= $emprunt->id ?>"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="empruntModal" tabindex="-1" role="dialog" aria-labelledby="empruntModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empruntModalLabel">Détails de l'emprunt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="empruntDetails">
                <!-- Les détails des emprunts seront chargés ici -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('addjs'); ?>
<!-- daterangepicker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="<?= base_url('public/dist/js/js/calendrier.js') ?>"></script>

<script>
    (function () {
        $(document).ready(function () {
            // Utiliser un délégué d'événements pour les boutons "Détails"
            $(document).on('click', '.view-emprunt', function (e) {
                e.preventDefault();
                var empruntId = $(this).data('id');
                $.ajax({
                    url: "<?= base_url('emprunts/details/') ?>" + empruntId,
                    method: "GET",
                    dataType: "html",
                    success: function (response) {
                        $('#empruntDetails').html(response);
                    }
                });
            });
        });
    })();
</script>
<?= $this->endSection(); ?>
<?= $this->endSection(); ?>