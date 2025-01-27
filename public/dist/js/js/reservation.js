function generateInvoiceForCurrentUser() {
    $.ajax({
        url: '<?= base_url("reservations/generateInvoice") ?>',
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

$(document).ready(function() {
    $('#generateInvoiceForm').on('submit', function(e) {
        e.preventDefault();
        var email = $('#email').val();

        // Envoyer l'adresse e-mail au serveur via AJAX
        $.ajax({
            url: '<?= base_url("reservations/generateInvoice") ?>',
            type: 'POST',
            data: {
                email: email
            },
            success: function(response) {
                if (response.success) {
                    downloadPdf(response.pdf_content);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    $('body').on('click', '.supelm', function(e) {
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
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: '<?= base_url("reservations/delete/") ?>' + id,
                    type: 'POST',
                    cache: false,
                    async: true,
                    success: function(response) {
                        if (response.success) {
                            $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function() {
                                $(this).remove();
                            });
                            swal('Supprimé!', response.message, 'success');
                        } else {
                            swal('Erreur', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        swal('Erreur', 'Une erreur s\'est produite lors de la suppression.', 'error');
                    }
                });
            }
        });
    });
    

    (function() {
        $('.accept-btn').on('click', function() {
            var reservationId = $(this).data('id');
            $.ajax({
                url: '<?= base_url("reservations/accept/") ?>' + reservationId,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, '', { timeOut: 3000, positionClass: 'toast-top-center' });
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        toastr.error(response.message, '', { timeOut: 3000, positionClass: 'toast-top-center' });
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        $('.refuse-btn').on('click', function() {
            var reservationId = $(this).data('id');
            $.ajax({
                url: '<?= base_url("reservations/refuse/") ?>' + reservationId,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, '', { timeOut: 3000, positionClass: 'toast-top-center' });
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        toastr.error(response.message, '', { timeOut: 3000, positionClass: 'toast-top-center' });
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });
    })();

    (function() {
        $(document).on('click', '.view-reservation', function(e) {
            e.preventDefault();
            var reservationId = $(this).data('id');
            $.ajax({
                url: '<?= base_url("reservations/details/") ?>' + reservationId,
                method: 'GET',
                dataType: 'html',
                success: function(response) {
                    $('#reservationDetails').html(response);
                }
            });
        });
    })();
});
