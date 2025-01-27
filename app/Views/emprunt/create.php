<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Espace Emprunt
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Espace Emprunt <a href="<?= base_url('emprunts/create') ?>" class="btn btn-rounded btn-primary btn-sm"><i class="fa fa-refresh"></i></a>
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('emprunts') ?>">espace emprunt</a></li>
<li><i class="fa fa-angle-right"></i> nouvel</li>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">Formulaire</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Matricule<span class="text-muted"> (généré automatiquement)</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-flickr"></i></div>
                                    <input type="text" name="user_id" id="user_id" class="form-control"
                                        required="required" hidden="true">
                                    <input onblur="getinfos()" class="form-control" type="text" id="matricule">
                                </div>
                                <div class="form-group">
                                    <span id="get_student_name" style="font-size:16px;"></span>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <label>Séléction de livres</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-book"></i></div>
                                    <select name="id_livre[]" id="id_livre" class="form-control select2"
                                        style="width: 100%;" multiple>
                                        <?php foreach ($livres as $livre): ?>
                                            <option value="<?= $livre->id ?>">
                                            <?= mb_strtoupper($livre->nom_livre, 'UTF-8') . ' | ' . mb_strtoupper($livre->nom_auteur, 'UTF-8') ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="selectionAlert" class="alert alert-danger" style="display: none;">
                                    Vous ne pouvez sélectionner que deux livres maximum.
                                </div>
                                <div id="bookStock" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Délai de retour</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                    <input type="date" name="delai_retour" class="form-control"
                                        value="<?= date("Y-m-d", strtotime("+15 days")) ?>">
                                </div>
                            </div>

                        </div>
                </div>
            </div>
            <div class="card-footer text-left">
                <a href="<?= base_url('emprunts') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>

                <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm"
                    disabled>Affecter</button>

            </div>
        </div>
        </form>
    </div>
</div>

<?= $this->section('addjs'); ?>
<script src="<?= base_url('public/dist/vendor/select2/dist/js/select2.full.min.js') ?>"></script>
<script type="text/javascript">
    $(".select2").select2({ 'data-placeholder': 'Choisir...' });
</script>

<script>

// Fonction pour obtenir les informations sur l'étudiant
function getinfos() {
    $("#loaderIcon").show();
    $("#user_id").val('');

    $.ajax({
        url: "<?= base_url('emprunts/infos') ?>",
        data: {
            'matricule': $("#matricule").val(),
        },
        type: "POST",
        success: function (data) {
            resp = JSON.parse(data);
            if (resp?.error) {
                $("#get_student_name").html("<span class='text-danger'>Matricule non valide. Veuillez entrer un matricule correct</span>");
                $("#submitBtn").prop("disabled", true); // Désactiver le bouton si le matricule n'est pas valide
            } else if (resp?.error === false) {
                $("#user_id").val(resp?.user?.id);
                $("#get_student_name").html(
                    "<span class='text-black'>" +
                    resp?.user?.prenom + ' ' + resp?.user?.nom + ' | ' + resp?.user?.role +
                    "</span>"
                );
                $("#submitBtn").prop("disabled", false); // Activer le bouton si le matricule est valide
            }
            $("#loaderIcon").hide();
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText); // Affichez les détails de l'erreur dans la console
        }
    });
}

// Déclencher la fonction getinfos() dès que l'utilisateur commence à saisir quelque chose dans le champ de saisie
$("#matricule").on("input", function () {
    getinfos();
});

    $(document).ready(function () {
        // Initialiser Select2
        $('.select2').select2();

        // Limiter la sélection à deux options
        $('.select2').on('select2:select', function (e) {
            if ($(this).select2('data').length > 2) {
                $(this).find('[value="' + e.params.data.id + '"]').prop('selected', false);
                $(this).trigger('change');
                $('#selectionAlert').fadeIn();
                setTimeout(function () {
                    $('#selectionAlert').fadeOut();
                }, 3000); // Disparaître après 3 secondes
            }
        });
    });

    $(document).ready(function () {
    const livreSelect = $('#id_livre');
    const submitButton = $('#submitBtn'); // Sélectionnez le bouton Submit

    // Fonction pour vérifier la disponibilité des livres sélectionnés
    function checkLivresDisponibles() {
        const selectedIds = livreSelect.val();

        // Si aucune sélection, désactiver le bouton Submit et vider le conteneur des stocks
        if (!selectedIds || selectedIds.length === 0) {
            submitButton.prop('disabled', true);
            $('#bookStock').html('');
            return;
        }

        // Vider le conteneur des stocks avant de le mettre à jour
        $('#bookStock').html('');

        // Variable pour vérifier la disponibilité
        let allAvailable = true;

        // Parcourir tous les IDs sélectionnés et récupérer les informations sur le stock
        selectedIds.forEach(function(id) {
            $.get("<?= base_url('emprunts/verifStock/') ?>" + id, function (data) {
                const selectedBook = data;
                
                // Mettre à jour le conteneur des stocks avec les informations sur chaque livre
                const stockInfo = selectedBook.qte_stock == 0 ? 
                    `<div style="color: red;">Ce livre (${selectedBook.livre.nom_livre}) n'est pas disponible en stock.</div>` : 
                    `<div style="color: green;">Il reste ${selectedBook.qte_stock} exemplaires du livre "${selectedBook.livre.nom_livre}" en stock.</div>`;
                
                $('#bookStock').append(stockInfo);

                // Si un livre n'est pas disponible en stock, définir allAvailable à false
                if (selectedBook.qte_stock == 0) {
                    allAvailable = false;
                }

                // Désactiver le bouton Submit si un livre n'est pas disponible en stock
                submitButton.prop('disabled', !allAvailable);
            }, "json");
        });
    }

    // Appeler checkLivresDisponibles lorsque la sélection des livres change
    livreSelect.on('select2:select select2:unselect', function (e) {
        checkLivresDisponibles();
    });

    // Déclencher la fonction getinfos() dès que l'utilisateur commence à saisir quelque chose dans le champ de saisie
    $("#matricule").on("input", function () {
        getinfos();
    });

    // Vérifier la disponibilité des livres lorsque le champ matricule perd le focus
    $("#matricule").on("blur", function () {
        checkLivresDisponibles();
    });
});

</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>