<div class="row">
    <div class="col-lg-10">
        <div class="info-box">
            <strong><i class="fa fa-spinner margin-r-5"></i> NOTIFICATION DE:</strong>
            <?= strtoupper($notification->prenom) . ' ' . strtoupper($notification->nom); ?></span>
            <hr>
            <strong><i class="fa fa-spinner margin-r-5"></i> MATRICULE:</strong>
            <span class="pull-right"><?= strtoupper($notification->matricule) ?></span>
            <hr>
                                    <strong><i class="fa fa-user margin-r-5"></i> RÔLE:</strong>
            <span class="pull-right"><?= strtoupper($notification->role) ?></span>
            <hr>
            <strong><i class="fa fa-spinner margin-r-5"></i> CONTENU:</strong>
            <span class="pull-right btn btn-rounded btn-warning"><?= strtoupper($notification->message) ?></span>
            <hr>
            <strong><i class="fa fa-calendar margin-r-5"></i> DATE DE CREATION:</strong>
            <span
                class="pull-right btn btn-rounded btn-warning"><?= date('d/m/Y', strtotime($notification->created_at)) ?></span>
        </div>
    </div>
</div>

<!-- /.box-body -->