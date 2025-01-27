{% extends 'layouts/base.volt' %}
{% block title %}Boite de messagerie{% endblock %}
{% block pageTitle %} Boite de messagerie{% endblock %}
{% block addcss %}
{{ stylesheet_link("template/vendor/sweetalert/dist/sweetalert.css") }}
{% endblock %}
{% block breadcrumb %}
    <ol class="breadcrumb">
        <li><a href="#">Accueil</a></li>
        <li><i class="fa fa-angle-right"></i> Boite de messagerie</li>
    </ol>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-lg-3"> <a href="{{url('mailbox/send')}}" class="btn btn-danger btn-block margin-bottom">Nouveau message</a>
        <div class="box box-solid">
        {% include 'mailbox/menu.inc.volt' %}
        <!-- /.box-body --> 
        </div>
    </div>
    <div class="col-lg-9">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Détails Mail envoyé</h3>
              <div class="box-tools pull-right"> <a href="{{url('mailbox/details/'~(mail.id-1))}}" class="btn btn-box-tool" data-toggle="tooltip" title="Précedent"><i class="fa fa-chevron-left"></i></a> <a href="{{url('mailbox/details/'~(mail.id+1))}}" class="btn btn-box-tool" data-toggle="tooltip" title="Suivant"><i class="fa fa-chevron-right"></i></a> </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3>{{mail.subject}}</h3>
                <h5>De: {{mail.sender_email}} <span class="mailbox-read-time pull-right">{{date('d/m/Y \à H:i',strtotime(mail.date))}}</span></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border text-left">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm supelm" data-toggle="tooltip" data-container="body" title="Supprimer" data-id = "{{mail.id}}"> <i class="fa fa-trash-o"></i></button>
                  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Répondre"> <i class="fa fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Transferer"> <i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Print"> <i class="fa fa-print"></i></button>
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                <pre>{{mail.message }}</pre>
              </div>
              <!-- /.mailbox-read-message --> 
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <!-- <ul class="mailbox-attachments clearfix">
                <li> <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>
                  <div class="mailbox-attachment-info"> <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> Sep2014-report.pdf</a> <span class="mailbox-attachment-size"> 1,245 KB <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a> </span> </div>
                </li>
                <li> <span class="mailbox-attachment-icon"><i class="fa fa-file-word-o"></i></span>
                  <div class="mailbox-attachment-info"> <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> App Description.docx</a> <span class="mailbox-attachment-size"> 1,245 KB <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a> </span> </div>
                </li>
                <li> <span class="mailbox-attachment-icon has-img"><img src="dist/img/img7.jpg" alt="Attachment"></span>
                  <div class="mailbox-attachment-info"> <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo1.png</a> <span class="mailbox-attachment-size"> 2.67 MB <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a> </span> </div>
                </li>
                <li> <span class="mailbox-attachment-icon has-img"><img src="dist/img/img8.jpg" alt="Attachment"></span>
                  <div class="mailbox-attachment-info"> <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo2.png</a> <span class="mailbox-attachment-size"> 1.9 MB <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a> </span> </div>
                </li>
              </ul> -->
            </div>
            <!-- /.box-footer -->
            <div class="box-footer m-b-2">
              <div class="pull-right">
                <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Répondre</button>
                <button type="button" class="btn btn-default"><i class="fa fa-share"></i> Transferer</button>
              </div>
              <button type="button" class="btn btn-default supelm" data-id = "{{mail.id}}"><i class="fa fa-trash-o"></i> Supprimer</button>
              <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Imprimer</button>
            </div>
            <!-- /.box-footer --> 
          </div> 
    </div>
</div>
<!-- Main row --> 
{% endblock %}
{% block addjs %}
{{ javascript_include("template/vendor/sweetalert/dist/sweetalert.min.js") }}
<script>
$('body').on('click', '.supelm', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var currentTr = $(this).closest("tr");
        swal({
          title: 'Êtes-vous sûr ?',
          text: 'Supprimer ce mail !',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Oui, supprimer !',
          cancelButtonText: 'Annuler',
          closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: "{{url('mailbox/deleteMail')}}/"+id,
                cache: false,
                async: true
            })
            .done(function( result ) {

                if(result == "1"){
                    swal(
                        'Supprimé!',
                        'L\'element  a été supprimée avec succès.',
                        'success'
                    );

                    location.href = "{{url('mailbox')}}";
                    
                }
                else{
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
{% endblock %}