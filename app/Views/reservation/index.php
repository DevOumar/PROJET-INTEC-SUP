<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Réservations
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
Espace des Réservations <a href="<?= base_url('reservations') ?>" class="btn btn-rounded btn-primary btn-sm"><i
        class="fa fa-refresh"></i></a>
<?= $this->endSection() ?>

<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> espace Réservations</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="info-box">
    <div class="col-md-6">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>
    <h4 class="text-black">Liste(<?php echo count($reservations); ?>)

        <a href="<?= base_url('reservations/create') ?>" class="btn btn-rounded btn-primary btn-sm"><i
                class="fa fa-plus-circle"></i>
        Réservation</a>
        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?><a href="#" class="btn btn-rounded btn-success btn-sm" data-toggle="modal" data-target="#generateInvoiceModal"><i class="fa fa-file-pdf-o"></i> Générer la réservation</a>
        <?php endif; ?>
        <?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
            <a href="#" class="btn btn-rounded btn-success btn-sm" onclick="generateInvoiceForCurrentUser()">
        <i class="fa fa-file-pdf-o"></i> Télécharger mon ticket
    </a>
        <?php endif; ?>
    </h4>
    <?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
        <div class="m-b-3">
            <div class="callout callout-info" style="margin-bottom: 0!important;">
                <h4><i class="fa fa-info"></i> Note:</h4>
                <ul>
                    <li>Chaque fois qu'une réservation de livres est approuvée, vous avez 24 heures pour effectuer l'emprunt
                        auprès du responsable. Vous devez imprimer votre ticket de réservation à cet effet.</li>
                    <li>Toute réservation non traitée par le responsable au-delà de 24 heures depuis sa date de réservation
                        sera automatiquement refusée.</li>
                    <li>Si vous avez déjà des réservations depuis moins de 48 heures, vous devez attendre que ces 48 heures
                        s'écoulent avant de pouvoir en effectuer de nouvelles.</li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <hr />

    <div class="table-responsive">
        <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
            <thead>
                <tr>
                    <th>ID #</th>
                    <th>Nom complet<span style="font-size: smaller;">(Matricule)</span></th>
                    <th>Livre</th>
                    <th>Nom de l'auteur</th>
                    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                        <th>Quantité</th>
                    <?php endif; ?>
                    <th>Stock</th>
                    <th>Date de réservation</th>
                    <th>Date statut</th>
                    <th>Statut</th>
                    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                        <th>Décision</th>
                    <?php endif; ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $key => $reservation): ?>
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
                            <span class="btn btn-rounded btn-primary btn-sm">
                                <?= mb_strtoupper($reservation->nom_auteur, 'UTF-8') ?>
                            </span>
                        </td>
                        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                            <td>
                                <span
                                    class="btn btn-rounded btn-danger btn-sm"><?= strtoupper($reservation->quantite) ?></span>
                            </td>
                        <?php endif; ?>
                        <td>
                            <?php if ($reservation->qte_stock <= 0): ?>
                                <span class="btn btn-rounded btn-danger btn-sm">Stock épuisé</span>
                            <?php else: ?>
                                <span class="btn btn-rounded btn-success btn-sm"><?= $reservation->qte_stock ?> en stock</span>
                            <?php endif; ?>
                        </td>
                        <td><span
                                class="btn btn-rounded btn-primary btn-sm"><?= date('d/m/Y à H:i', strtotime($reservation->date_reservation)) ?></span>
                        </td>
                        <td> <?php
                        if (!empty($reservation->date_status) && $reservation->date_status != '0000-00-00') {
                            echo '<span class="btn btn-rounded btn-primary btn-sm">' . date('d/m/Y à H:i', strtotime($reservation->date_status)) . '</span>';
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
                        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                            <td>
                                <?php if ($reservation->status == 0): ?>
                                    <span class="accept-btn" data-id="<?= $reservation->id ?>">
                                        <a href="#" class="btn btn-rounded btn-success btn-sm">Accepter</a>
                                    </span>
                                    <span class="refuse-btn" data-id="<?= $reservation->id ?>">
                                        <a href="#" class="btn btn-rounded btn-danger btn-sm">Refuser</a>
                                    </span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <a href="#" class="view-reservation" data-toggle="modal" data-target="#reservationModal"
                                data-id="<?= $reservation->id ?>"><i class="fa fa-eye"></i></a>
                            <a href="#" class="supelm" data-id="<?= $reservation->id ?>"><i class="fa fa-trash"></i></a>

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
                <h5 class="modal-title" id="reservationModalLabel">Détails de la réservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="reservationDetails">
                <!-- Les détails de la réservation seront chargés ici -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-rounded btn-secondary btn-sm" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour l'administrateur -->
<div class="modal fade" id="generateInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="generateInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateInvoiceModalLabel">Générer une facture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="generateInvoiceForm">
                    <div class="form-group">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" id="generateInvoiceBtn">Générer</button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?= $this->section('addjs') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.min.js') ?>"></script>

<script>
    function generateInvoiceForCurrentUser() {
    $.ajax({
        url: '<?= base_url('reservations/generateInvoice') ?>',
        type: 'POST',
        success: function(response) {
            if (response.success) {
                downloadPdf(response.pdf_content);
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        }
    });
}

function downloadPdf(pdfContent) {
    // Décoder la chaîne base64
    var binaryString = window.atob(pdfContent);
    var binaryLen = binaryString.length;
    var bytes = new Uint8Array(binaryLen);
    for (var i = 0; i < binaryLen; i++) {
        var ascii = binaryString.charCodeAt(i);
        bytes[i] = ascii;
    }

    // Créer un objet Blob à partir du contenu décodé
    var pdfBlob = new Blob([bytes], { type: 'application/pdf' });

    // Créer une URL object à partir du Blob
    var pdfUrl = URL.createObjectURL(pdfBlob);

    // Ouvrir le PDF dans un nouvel onglet
    window.open(pdfUrl, '_blank');
}

$(document).ready(function () {
    $('#generateInvoiceForm').on('submit', function (e) {
        e.preventDefault();
        var email = $('#email').val();

        // Envoyer l'adresse e-mail au serveur via AJAX
        $.ajax({
            url: '<?= base_url('reservations/generateInvoice') ?>',
            type: 'POST',
            data: {
                email: email
            },
            success: function (response) {
                if (response.success) {
                    downloadPdf(response.pdf_content);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
});
</script>
<script>
    $('body').on('click', '.supelm', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var currentTr = $(this).closest("tr");
        swal({
            title: 'Êtes-vous sûr ?',
            text: 'Supprimer cette réservation !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "<?= base_url('reservations/delete/') ?>" + id,
                cache: false,
                async: true
            })
                .done(function (result) {
                    if (result = "1") {
                        $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function () { $(this).remove(); });
                        swal(
                            'Supprimé!',
                            'L\'element  a été supprimé avec succès.',
                            'success'
                        );
                        location.reload();
                    }
                    else {
                        swal(
                            'Impossible de supprimer. Objet lié !',
                            'Erreur de suppression',
                            'error'
                        );
                    }
                });
        });
    });
</script>
<script>
    (function () {
        $('.accept-btn').on('click', function () {
            var reservationId = $(this).data('id');
            $.ajax({
                url: '<?= base_url('reservations/accept/') ?>' + reservationId,
        type: 'POST',
                success: function (response) {
                    if (response.success) {
                        // Afficher un toast de succès avec une durée de 3 secondes et centré
                        toastr.success(response.message, '', { timeOut: 3000, positionClass: 'toast-top-center' });
                        // Recharger la page automatiquement après 2 secondes
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        // Afficher un toast d'erreur avec une durée de 3 secondes et centré
                        toastr.error(response.message, '', { timeOut: 3000, positionClass: 'toast-top-center' });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        $('.refuse-btn').on('click', function () {
            var reservationId = $(this).data('id');
            $.ajax({
                url: '<?= base_url('reservations/refuse/') ?>' + reservationId,
        type: 'POST',
                success: function (response) {
                    if (response.success) {
                        // Afficher un toast de succès avec une durée de 3 secondes et centré
                        toastr.success(response.message, '', { timeOut: 3000, positionClass: 'toast-top-center' });
                        // Recharger la page automatiquement après 2 secondes
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        // Afficher un toast d'erreur avec une durée de 3 secondes et centré
                        toastr.error(response.message, '', { timeOut: 3000, positionClass: 'toast-top-center' });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });
    })();
</script>
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

<?= $this->endSection() ?>
<?= $this->endSection() ?>