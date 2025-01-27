<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Boite de messagerie
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
Boite de messagerie
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li><a href="<?= base_url('dashboard') ?>">Tableau de bord</a></li>
<li><i class="fa fa-angle-right"></i> Boite de messagerie</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-3">
        <a href="<?= base_url('mailbox/send') ?>" class="btn btn-danger btn-block margin-bottom">Nouveau message</a>
        <div class="box box-solid">
            <?php include 'menu.inc.php'; ?>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Boite de reception(<?= $pager->getTotal() ?>)</h3>
                <div class="col-md-6">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i> </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div>
                    <!-- /.btn-group -->
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <a href="<?= base_url('mailbox') ?>" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
                    <div class="pull-right">
                        <?= $pager->getCurrentPage() ?>/<?= $pager->getPageCount() ?> - Total: <?= $pager->getTotal() ?>
                        <div class="btn-group">
                            <?php if (property_exists($pager, 'hasPreviousPage') && $pager->hasPreviousPage()) : ?>
                                <a href="<?= $pager->getPreviousPage() ?>" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                            <?php endif; ?>
                            <?php if ($pager->getCurrentPage() < $pager->getPageCount()) : ?>
                                <a href="<?= $pager->getNextPage() ?>" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /.pull-right -->
                </div>
                <div class="table-responsive mailbox-messages">
                    <table class="table table-hover no-wrap table-striped">
                        <tbody>
                            <?php foreach ($mails as $mail): ?>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                <td class="mailbox-name"><?= $mail->sender_fullname ?></td>
                                <td class="mailbox-subject"><a href="<?= base_url('mailbox/details/' . $mail->id) ?>"><?= $mail->subject ?></a></td>
                                <td class="mailbox-attachment"></td>
                                <td class="mailbox-date"><?= date('d-m-Y \à H:i', strtotime($mail->date)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- /.table -->
                </div>
                <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding m-b-2">
                <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i> </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div>
                    <!-- /.btn-group -->
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <a href="<?= base_url('mailbox') ?>" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
                    <div class="pull-right">
                        <?= $pager->getCurrentPage() ?>/<?= $pager->getPageCount() ?> - Total: <?= $pager->getTotal() ?>
                        <div class="btn-group">
                            <?php if (property_exists($pager, 'hasPreviousPage') && $pager->hasPreviousPage()) : ?>
                                <a href="<?= $pager->getPreviousPage() ?>" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                            <?php endif; ?>
                            <?php if ($pager->getCurrentPage() < $pager->getPageCount()) : ?>
                                <a href="<?= $pager->getNextPage() ?>" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /.pull-right -->
                </div>
            </div>
        </div>
        <!-- /. box --> 
    </div>
</div>
<!-- Main row --> 
<?= $this->endSection() ?>
