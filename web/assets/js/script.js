$(document).ready(function() {
	// Initialisation menu déroulant
	$(".dropdown-button").dropdown({ hover: false });

	// Initialisation boite de dialogue d'ajout d'adresse
	$('.modal-trigger').leanModal();

	//Initialisation des liste déroulantes
	$('select').material_select();

	// Initialisation des messages des tooltips
	$('.tooltip-identifiants').attr("data-tooltip", "Entrez vos identifiants fournis par le Lycée");
})