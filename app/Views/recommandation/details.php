<div class="row">
    <div class="col-lg-12">
        <div class="info-box">
            <strong><i class="fa fa-user margin-r-5"></i> Nom du livre:</strong>
            <span class="pull-right"><?= mb_strtoupper($recommandation->nom_livre, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-user margin-r-5"></i> Nom de l'auteur:</strong>
            <span class="pull-right"><?= mb_strtoupper($recommandation->nom_auteur, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-spinner margin-r-5"></i> Livre recommandé par:</strong>
            <span class="pull-right">
                <?= mb_strtoupper($recommandation->prenom, 'UTF-8') . ' ' . mb_strtoupper($recommandation->nom, 'UTF-8'); ?></span>
            <hr>
            <strong><i class="fa fa-user margin-r-5"></i> Description:</strong>
            <span class="pull-right"><?= mb_strtoupper($recommandation->description, 'UTF-8') ?></span>
            <hr>
            <strong><i class="fa fa-calendar margin-r-5"></i> Date de creation:</strong>
            <span
                class="pull-right btn btn-rounded btn-primary btn-sm"><?= date('d/m/Y à H:i', strtotime($recommandation->created_at)) ?></span>
        </div>
    </div>
</div>

<!-- /.box-body -->