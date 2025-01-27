$(document).ready(function () {
    const firstCategorieOption = $("#id_categorie option").eq(0);
    const firstAuteurOption = $("#id_auteur option").eq(0);

    $(
        `<option class="add-element" value="-1" id="add-categorie" value="">Ajouter une catégorie</option>`
    ).insertAfter(firstCategorieOption);
    $(
        `<option class="add-element" value="-1" id="add-auteur" value="">Ajouter un auteur</option>`
    ).insertAfter(firstAuteurOption);

    $("select#id_categorie").on("select2:select", function (e) {
        let element = e.params.data.element;
        if ($(element).attr("id") == "add-categorie") {
            $("select#id_categorie").val("").trigger("change");
            $(".modal#createCategorie #libelle-categorie").val("");
            $(".modal#createCategorie small#helpId").html("");
            $("#createCategorie").modal("show");
            e.preventDefault();
        }
    });

    $("select#id_auteur").on("select2:select", function (e) {
        let element = e.params.data.element;
        if ($(element).attr("id") == "add-auteur") {
            $("select#id_auteur").val("").trigger("change");
            $(".modal#createAuteur #libelle-auteur").val("");
            $(".modal#createAuteur small#helpId").html("");
            $("#createAuteur").modal("show");
            e.preventDefault();
        }
    });


    $(".modal#createAuteur #btn-save-auteur").click((e) => {
        e.preventDefault();

        let libelleInput = $(".modal#createAuteur #libelle-auteur");
        if (libelleInput.val().length == 0) {
            libelleInput.addClass("is-invalid");
            return;
        }
        $.post(
            `${getBaseUrl()}/memoires/createAuteur`,
            { nom_auteur: libelleInput.val() },
            function (data, textStatus, jqXHR) {
                if (data?.error) {
                    $(".modal#createAuteur small#helpId").html(
                        "Echec d'enregistrement/Cet Auteur existe déjà."
                    );

                    return;
                }
                var newOption = new Option(
                    data?.nom_auteur,
                    data?.id,
                    true,
                    true
                );
                $("#id_auteur").prepend(newOption).trigger("change");
                libelleInput.val("");
                $(".modal#createAuteur small#helpId").html("");
                $("#createAuteur").modal("hide");

            },
            "json"
        );
    });

    $(".modal#createCategorie #btn-save-categorie").click((e) => {
        e.preventDefault();

        let libelleInput = $(".modal#createCategorie #libelle-categorie");
        if (libelleInput.val().length == 0) {
            libelleInput.addClass("is-invalid");
            return;
        }
        $.post(
            "createCategorie",
            { libelle: libelleInput.val() },
            function (data, textStatus, jqXHR) {
                if (data?.error) {
                    $(".modal#createCategorie small#helpId").html(
                        "Echec d'enregistrement/Cette Catégorie existe déjà."
                    );

                    return;
                }
                var newOption = new Option(
                    data?.libelle,
                    data?.id,
                    true,
                    true
                );
                $("#id_categorie").prepend(newOption).trigger("change");
                libelleInput.val("");
                $(".modal#createCategorie small#helpId").html("");
                $("#createCategorie").modal("hide");

            },
            "json"
        );
    });
});
const getBaseUrl = () => {
    const hostname = window.location.hostname;
    const protocol = window.location.protocol;
    const url = `${protocol}`;
    return url;
};

//Chargement des données de filière

$("select#id_filiere").hide();
$("select#id_cycle").change(function (e) {
    if ("select#id_cycle") {
        $("select#id_filiere ").show();
        $("select#id_filiere ").attr("required", "required");
    } else {
        $("select#id_filiere").hide();
    }
});

document.querySelector("select#id_cycle").
    addEventListener("change", function (e) {
        //   console.log(e.target.value);
        $("select#id_filiere").html("");
        $.get(`${window.location.origin}/filiere/list/${e.target.value}`,
            function (data, textStatus, jqXHR) {
                let optionList = `<option value>Choisir votre filière</option>`;

                data?.forEach(cycle => {

                    optionList += `<option value="${cycle.id}">${cycle.libelle}</option>`
                });

                $("select#id_filiere").html(optionList);

                // console.log(data)
            },
            "json"
        );
    });