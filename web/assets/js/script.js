$(document).ready(function () {
    // Initialisation menu déroulant
    $(".dropdown-button").dropdown({hover: false});

    // Initialisation boite de dialogue d'ajout d'adresse
    $('.modal-trigger').leanModal();

    //Initialisation des liste déroulantes
    $('select').material_select();

    // Initialisation des messages des tooltips
    $('.tooltip-identifiants').attr("data-tooltip", "Entrez vos identifiants fournis par le Lycée");

    // Gestion de la suppression d'un peripherique.
    // L'id du peripherique est caché dans le bouton supprimer. IL faut le recuperer au click sur ce dernier pour ajouter un
    // input type hidden contenant l'id dans le formulaire de suppression
    $('a.suppr').on('click', function () {
        var id = $(this).attr("data-id");
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "id");
        input.setAttribute("value", id);

        $("#formSuppr").append(input);
    });


    // Gestion des input adresse mac
    $("#adresseMac").addrMac();

    /*
     * Gestion des notifications
     */
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-full-width",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "0",
        "extendedTimeOut": "0",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    var message = $('.notification').attr("data-message");
    toastr.error(message);


    /*
    ** Gestion de la recherche admin
    * Si aucun champs n'est rempli, on de valide pas
     */
    $("#formRecherche").on('submit', function() {
        if (null == $("#promotion").val() &&
        "" == $("#username").val() &&
        "" == $("#IP").val() &&
        "" == $("#MAC").val()) {
            alert('Il faut au moins un des champs!');
            return false;
        } else {
            $(this).submit();
        }
    });

});