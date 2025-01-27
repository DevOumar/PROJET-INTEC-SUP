<?= $this->extend('layouts/base') ?>
<?= $this->section('title') ?>
Espace Réservation
<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
Espace Réservation
<?= $this->endSection() ?>
<?= $this->section('addcss') ?>
<!-- SweetAlert -->
<link rel="stylesheet" href="<?= base_url('public/dist/vendor/select2/dist/css/select2.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('reservations') ?>">espace réservation</a></li>
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
                                <div id="bookStock"></div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="card-footer text-left">
                <a href="<?= base_url('reservations') ?>" class="btn btn-rounded btn-default btn-sm">Annuler</a>

                <button type="submit" id="submitBtn" class="btn btn-rounded btn-success btn-sm" disabled>Réserver</button>

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
    (function(){
    $(document).ready(function () {
        // Initialiser Select2
        $('.select2').select2();

        // Limiter la sélection à deux options
        $('.select2').on('select2:select', function (e) {
            var selectedOptions = $(this).select2('data').length;
            // Afficher ou masquer l'alerte si plus de deux options sont sélectionnées
            if (selectedOptions > 2) {
                $(this).find('[value="' + e.params.data.id + '"]').prop('selected', false);
                $(this).trigger('change');
                $('#selectionAlert').fadeIn();
                setTimeout(function () {
                    $('#selectionAlert').fadeOut();
                }, 3000); // Disparaître après 3 secondes
            }
            // Activer ou désactiver le bouton de soumission en fonction du nombre d'options sélectionnées
            if (selectedOptions > 0) {
                $('#submitBtn').prop('disabled', false); // Activer le bouton
            } else {
                $('#submitBtn').prop('disabled', true); // Désactiver le bouton
            }
        });
    });
})();
</script>

<script>
   $(document).ready(function () {
    const livreSelect = $('#id_livre');
    const submitButton = $('#submitBtn'); // Sélectionnez le bouton Submit

    livreSelect.on('select2:select select2:unselect', function (e) {
        // Obtenir tous les IDs des livres sélectionnés
        const selectedIds = $(this).val();

        // Si aucune sélection, vider le conteneur des stocks et désactiver le bouton Submit
        if (selectedIds.length === 0) {
            $('#bookStock').html('');
            submitButton.prop('disabled', true);
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

                // Activer ou désactiver le bouton Submit en fonction de la disponibilité
                submitButton.prop('disabled', !allAvailable);
            }, "json");
        });
    });
});

</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>