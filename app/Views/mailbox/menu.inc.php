<div class="box-header with-border">
    <h3 class="box-title">Dossiers</h3>
    <div class="box-tools">
    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
    </div>
</div>
<div class="box-body no-padding">
    <ul class="nav nav-pills nav-stacked">
    <li class="active"><a href="<?= base_url('mailbox') ?>"><i class="fa fa-inbox"></i> Boite de reception(<?= $pager->getTotal() ?>) <span class="label label-primary pull-right"></span></a></li>
    <li><a href="<?= base_url('mailbox/mailsSent') ?>"><i class="fa fa-envelope-o"></i> Envoyés</a></li>
    <li><a href="#"><i class="fa fa-file-text-o"></i> Brouillon</a></li>
    <li><a href="#"><i class="fa fa-trash-o"></i> Corbeille</a></li>
    </ul>
</div>