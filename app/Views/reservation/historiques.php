<?= $this->extend('layouts/base'); ?>
<?= $this->section('title'); ?>
Historique des réservations
<?= $this->endSection(); ?>
<?= $this->section('pageTitle'); ?>
Historique de toutes les réservations <a href="<?= base_url('reservations/historiques') ?>"
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
<li><a href="<?= base_url('reservations') ?>">réservation</a></li>
<li><i class="fa fa-angle-right"></i> Historique de toutes les réservations</li>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="info-box">
    <h4 class="text-black">Liste(<?= count($reservation_filter_etudiant) ?>)
        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
            <a href="<?= base_url('reservations/exportFiltered/' . $start_date . '/' . $end_date . '/' . $date_filter_chosen_label) ?>"
                class="btn btn-rounded btn-success btn-sm <?php if (empty($reservation_filter_etudiant))
                    echo 'disabled'; ?>">
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
                    <th>Nom complet<span style="font-size: smaller;">(Matricule)</span></th>
                    <th>Livre</th>
                    <th>Nom de l'auteur</th>
                    <th>Rôle</th>
                    <th>Quantité</th>
                    <th>Stock</th>
                    <th>Date de réservation</th>
                    <th>Date statut</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservation_filter_etudiant as $key => $reservation): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm">
                                <?= mb_strtoupper($reservation->nom . '-' . $reservation->prenom, 'UTF-8') ?>(<span
                                    style="font-size: smaller; font-style: italic;"><?= $reservation->matricule; ?></span>)
                            </span>
                        </td>

                        <td>
                            <span>
                                <?= mb_strtoupper($reservation->nom_livre, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm" style="font-size: smaller;">
                                <?= mb_strtoupper($reservation->nom_auteur, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm" style="font-size: smaller;">
                                <?php
                                $role = mb_strtoupper($reservation->role, 'UTF-8');
                                $civilite = $reservation->civilite;

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
                            </span>
                        </td>
                        <td>
                            <span class="btn btn-rounded btn-primary btn-sm" style="font-size: smaller;">
                                <?= $reservation->quantite ?>
                            </span>
                        <td>
                            <span class="btn btn-rounded btn-danger btn-sm" style="font-size: smaller;">
                                <?= $reservation->qte_stock ?>
                            </span>
                        </td>
                        <td>
                            <span style="font-size: smaller;" class="btn btn-rounded btn-primary btn-sm">
                                <?= date('d/m/Y à H:i', strtotime($reservation->date_reservation)) ?> </span>
                        </td>
                        <td> <?php
                        if (!empty($reservation->date_status) && $reservation->date_status != '0000-00-00') {
                            echo '<span style="font-size: smaller;" class="btn btn-rounded btn-primary btn-sm">' . date('d/m/Y \à H:i', strtotime($reservation->date_status)) . '</span>';
                        } else {
                            echo '<span class="btn btn-rounded btn-secondary btn-sm">En attente</span>';
                        }
                        ?></td>
                        <td>
                            <?php
                            switch ($reservation->status) {
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
                        <td>
                            <a href="#" class="view-reservation" data-toggle="modal" data-target="#reservationModal"
                                data-id="<?= $reservation->id ?>"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel">Détails de l'reservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="reservationDetails">
                <!-- Les détails du mémoire seront chargés ici -->
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
            $(document).on('click', '.view-reservation', function (e) {
                e.preventDefault();
                var reservationId = $(this).data('id');
                $.ajax({
                    url: "<?= base_url('reservations/details/') ?>" + reservationId,
                    method: "GET",
                    dataType: "html",
                    success: function (response) {
                        $('#reservationDetails').html(response);
                    }
                });
            });
        });
    })();
</script>
<?= $this->endSection(); ?>
<?= $this->endSection(); ?>