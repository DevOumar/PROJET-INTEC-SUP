<?= $this->extend('layouts/base'); ?>

<?= $this->section('title'); ?>
Espace Emprunt
<?= $this->endSection(); ?>

<?= $this->section('pageTitle'); ?>
Espace Emprunt <a href="<?= base_url('emprunts') ?>" class="btn btn-rounded btn-primary btn-sm"><i
        class="fa fa-refresh"></i></a>
<?= $this->endSection(); ?>

<?= $this->section('addcss'); ?>
<style>
    .copy-input {
        font-size: 14px;
        width: 100px;
        display: inline-block;
        padding: 0 5px;
        background: transparent;
        border: none;
        color: #555;
        outline: none;
        text-align: center;
        font-weight: bold;
    }

    .copy-input:hover {
        background: #f0f4f8;
    }
</style>
<!-- SweetAlert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.css') ?> ">
<?= $this->endSection(); ?>

<?= $this->section('breadcrumb'); ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> emprunt</li>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="info-box">
    <div class="col-md-6">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
    </div>
    <h4 class="text-black">Liste(<?= count($emprunts); ?>)
        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
            <a href="<?= base_url('emprunts/create') ?>" class="btn btn-rounded btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i> Affecter un livre
            </a>
            <button id="returnSelectedBtn" class="btn btn-rounded btn-danger btn-sm">Retourner les emprunts
                sélectionnés</button>
        <?php endif; ?>
        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
        <a href="#" class="btn btn-rounded btn-info btn-sm" data-toggle="modal"
                data-target="#generateInvoiceModal">Générer ticket d'emprunt</a>
            <?php endif; ?>
            <?php if (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])): ?>
            <a href="#" class="btn btn-rounded btn-info btn-sm" onclick="generateInvoiceForCurrentUser()">Télécharger mon ticket</a>
            <?php endif; ?>
        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <form id="notify_form" action="<?= base_url('emprunts/notify') ?>" method="post" class="form-inline">
                        <div class="form-group">
                            <label for="notify_option">Choisir une option :</label>
                            <select class="form-control" name="notify_option" id="notify_option">
                                <option value="">Choisir...</option>
                                <option value="TOUS">TOUS</option>
                                <option value="UTILISATEUR">CHOISIR UN UTILISATEUR</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-rounded btn-success btn-sm" title="Notifier">
                            <i class="fa fa-bell-o"></i> Notifier
                        </button>
                    </form>
                </div>
            </div>
            <!-- Modal -->
        <?php endif; ?>

    </h4>

    <hr />
    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
        <!-- Vos cartes et contenu pour les administrateurs -->
    <?php endif; ?>

    <div class="table-responsive">
        <table id="dataList" class="table table-bordered table-hover" data-name="cool-table">
            <thead>
                <tr>
                    <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                        <th></th>
                    <?php endif; ?>
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
                <?php foreach ($emprunts as $key => $emprunt): ?>
                    <tr>
                        <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                            <td>
                                <input type="checkbox" class="selected-emprunt" value="<?= $emprunt->id ?>">
                            </td>
                        <?php endif; ?>
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
                        <span class="btn btn-rounded btn-primary btn-sm copy-text">
    <input type="text" class="form-control copy-input" value="<?= mb_strtoupper($emprunt->matricule, 'UTF-8') ?>" readonly>
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

                        <td data-emprunt-id="<?= $emprunt->id ?>" data-emprunt-date-retour="<?= $emprunt->date_retour ?>">
                            <?php if (empty($emprunt->date_retour)): ?>
                                <?php $delaiLivre = date('d/m/Y', strtotime($emprunt->delai_retour)); ?>
                                <span class="btn btn-rounded btn-danger btn-sm">
                                    <?= $delaiLivre ?>
                                </span>
                                <?php if (strtotime($emprunt->delai_retour) < strtotime(date('d-m-Y'))): ?>
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
                            <?php if (session()->get('role') === 'ADMINISTRATEUR'): ?>
                                <a href="#" class="supelm" data-id="<?= $emprunt->id ?>"><i class="fa fa-trash"></i></a>
                            <?php endif; ?>
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
                <!-- Les détails du mémoire seront chargés ici -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
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

<?= $this->section('addjs'); ?>
<!-- SweetAlert -->
<script src="<?= base_url('public/dist/vendor/sweetalert/dist/sweetalert.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    function generateInvoiceForCurrentUser() {
    $.ajax({
        url: '<?= base_url('emprunts/generateInvoice') ?>',
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
            url: '<?= base_url('emprunts/generateInvoice') ?>',
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
            },
            error: function () {
                // Afficher un toast d'erreur si une erreur se produit lors de la requête AJAX
                toastr.error('Une erreur s\'est produite lors de la communication avec le serveur.');
                location.reload();
            }
        });
    });
});

</script>

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

<script>
    (function () {
        document.addEventListener("DOMContentLoaded", function () {
            // Cibler la liste déroulante
            var notifyOption = document.getElementById("notify_option");

            // Cibler le formulaire principal
            var form = document.getElementById("notify_form");

            // Cibler le bouton pour notifier l'utilisateur dans le modal
            var notifyUserBtn = document.getElementById("notifyUserBtn");

            // Cibler le champ email dans le modal
            var emailField = document.getElementById("email");

            // Si l'option sélectionnée est "UTILISATEUR"
            notifyOption.addEventListener("change", function () {
                var selectedOption = notifyOption.value;

                if (selectedOption === "UTILISATEUR") {
                    // Afficher le modal
                    $('#emailModal').modal('show');
                }
            });

            // Ajouter un événement onclick au bouton "Notifier" dans le modal
            notifyUserBtn.addEventListener("click", function () {
                // Récupérer la valeur de l'email
                var userEmail = emailField.value;

                // Vérifier si l'email est saisi
                if (userEmail.trim() !== "") {
                    // Injecter l'email dans le formulaire principal
                    document.getElementById("email").value = userEmail;

                    // Soumettre le formulaire principal
                    form.submit();
                } else {
                    // Afficher un message d'erreur si l'email n'est pas saisi
                    alert("Veuillez saisir une adresse e-mail.");
                }
            });
        });
    })();
</script>


<script>
    (function () {
        $(document).ready(function () {
            toastr.options = {
                "positionClass": "toast-top-center", // Position à droite de l'écran
                "timeOut": 2000, // Durée d'affichage en millisecondes (8 secondes)
                "progressBar": true, // Afficher la barre de progression
                "backgroundColor": "#006400"
            };


            // Gérer le clic sur le bouton "Retourner les emprunts sélectionnés"
            $('#returnSelectedBtn').on('click', function () {
                var selectedEmprunts = [];
                $('.selected-emprunt:checked').each(function () {
                    selectedEmprunts.push($(this).val());
                });
                console.log(selectedEmprunts); // Vérifier les emprunts sélectionnés dans la console

                var empruntsDejaRetournes = selectedEmprunts.filter(function (empruntId) {
            var dateRetour = $(`td[data-emprunt-id="${empruntId}"]`).data('emprunt-date-retour'); // Récupérer la date de retour
            console.log($(`td[data-emprunt-id="${empruntId}"]`).data());
            return dateRetour !== ''; // Vérifier si la date de retour est différente de NULL
        });
        console.log("Emprunts déjà retournés : ", empruntsDejaRetournes);

        if (empruntsDejaRetournes.length > 0) {
            toastr.warning('Certains des emprunts sélectionnés sont déjà retournés.');
        } else {
            $.ajax({
                url: '<?= base_url('emprunts/returnSelected') ?>',
                method: 'POST',
                data: { emprunts: selectedEmprunts },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Les emprunts sélectionnés ont été retournés avec succès.');
                    } else {
                        toastr.error('Une erreur s\'est produite lors du retour des emprunts.');
                    }
                },
                error: function () {
                    toastr.error('Une erreur s\'est produite lors de la communication avec le serveur.');
                }
            });
        }
    });
});
    })();
</script>

<script>
    $('body').on('click', '.supelm', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var currentTr = $(this).closest("tr");
        swal({
            title: 'Êtes-vous sûr ?',
            text: 'Supprimer cet emprunt !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Oui, valider !',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "<?= base_url('emprunts/delete/') ?>" + id,
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

<style>
    .toast-top-center {
        top: 10%;
        /* Ajustez la valeur pour déplacer les messages plus près du haut */
        right: 100px;
    }
</style>

<?= $this->endSection(); ?>
<?= $this->endSection(); ?>