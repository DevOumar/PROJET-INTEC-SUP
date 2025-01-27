$(document).ready(function(){
                // Basic
                $('.dropify').dropify();

                // Translated
                $('.dropify-fr').dropify({
                    messages: {
                        default: 'Glissez-déposez un fichier ici ou cliquez',
                        replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                        remove:  'Supprimer',
                        error:   'Désolé, le fichier trop volumineux'
                    }
                });

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element){
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element){
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element){
                    console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e){
                    e.preventDefault();
                    if (drDestroy.isDropified()) {
                        drDestroy.destroy();
                    } else {
                        drDestroy.init();
                    }
                })
            });

$(function () {
  $('#dataList').DataTable({
    "language": {
      "sEmptyTable":     "Aucune donnée disponible dans le tableau",
      "sInfo":           "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
      "sInfoEmpty":      "Affichage de l'élément 0 à 0 sur 0 élément",
      "sInfoFiltered":   "(filtré à partir de _MAX_ éléments au total)",
      "sInfoThousands":  ",",
      "sLengthMenu":     "Afficher _MENU_ éléments",
      "sLoadingRecords": "Chargement...",
      "sProcessing":     "Traitement...",
      "sSearch":         "Rechercher :",
      "sZeroRecords":    "Aucun élément correspondant trouvé",
      "oPaginate": {
        "sFirst":    "Premier",
        "sLast":     "Dernier",
        "sNext":     "Suivant",
        "sPrevious": "Précédent"
    },
    "oAria": {
        "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
    },
    "select": {
        "rows": {
          "_": "%d lignes sélectionnées",
          "0": "Aucune ligne sélectionnée",
          "1": "1 ligne sélectionnée"
      }  
  }
}
});
});




$(function () {
      $('#dataList1').DataTable({
        "language": {
          "sEmptyTable": "Aucune donnée disponible dans le tableau",
          "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
          "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
          "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
          "sInfoThousands": ",",
          "sLengthMenu": "Afficher _MENU_ éléments",
          "sLoadingRecords": "Chargement...",
          "sProcessing": "Traitement...",
          "sSearch": "Rechercher :",
          "sZeroRecords": "Aucun élément correspondant trouvé",
          "oPaginate": {
            "sFirst": "Premier",
            "sLast": "Dernier",
            "sNext": "Suivant",
            "sPrevious": "Précédent"
          },
          "oAria": {
            "sSortAscending": ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
          },
          "select": {
            "rows": {
              "_": "%d lignes sélectionnées",
              "0": "Aucune ligne sélectionnée",
              "1": "1 ligne sélectionnée"
            }
          }
        }
      });
    });



    $(function () {
      $('#dataList2').DataTable({
        "language": {
          "sEmptyTable": "Aucune donnée disponible dans le tableau",
          "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
          "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
          "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
          "sInfoThousands": ",",
          "sLengthMenu": "Afficher _MENU_ éléments",
          "sLoadingRecords": "Chargement...",
          "sProcessing": "Traitement...",
          "sSearch": "Rechercher :",
          "sZeroRecords": "Aucun élément correspondant trouvé",
          "oPaginate": {
            "sFirst": "Premier",
            "sLast": "Dernier",
            "sNext": "Suivant",
            "sPrevious": "Précédent"
          },
          "oAria": {
            "sSortAscending": ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
          },
          "select": {
            "rows": {
              "_": "%d lignes sélectionnées",
              "0": "Aucune ligne sélectionnée",
              "1": "1 ligne sélectionnée"
            }
          }
        }
      });
    });



function verif() {
  var a = document.getElementById("new_password").value;
  var b = document.getElementById("con_password").value;
  result = document.getElementById("result");
  if (a!=b) {
   result.innerHTML= "<p class='text-danger'>Oops,les deux mots de passe ne sont pas identiques.</p>";
   return false;
}
else {
   result.innerHTML= "<p class='text-success'>Les deux mots de passe sont identiques.</p>";
}
}



$(document).ready(function () {
    let selectedBook = null;
    const livreSelect = $('#id_livre');
    $('#id_livre').on('select2:select', function (e) {
        var id = e.params.data.id;
        $.get("verifStock/" + id,
            function (data, textStatus, jqXHR) {

                selectedBook = data;

                $('#book-stock strong').html(`${selectedBook.qte_stock == 0 ? `Ce livre n'est pas disponible en stock.` : `Il reste ${selectedBook.qte_stock} livres en stock`}`);

                if (selectedBook.qte_stock > 1) {
                    $('#submit').attr('disabled', 'false');
                } else {
                    $('#submit').attr('disabled', 'true');

                }
                
            },
            "json"
        );
    });
});

