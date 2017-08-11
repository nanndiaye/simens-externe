var base_url = window.location.toString();
var tabUrl = base_url.split("public");

//*********************************************************************
//*********************************************************************
//*********************************************************************
var identifierListeAfficher = 1;
$(function() {
	
    $( "button" ).button();

    //Au debut on cache le bouton modifier et on affiche le bouton valider
	$( "#bouton_constantes_valider" ).toggle(true);
	$( "#bouton_constantes_modifier" ).toggle(false);

	$( "#bouton_constantes_valider" ).click(function(){
		
		if($('#poids'          )[0].checkValidity() == true &&
		   $('#taille'         )[0].checkValidity() == true &&
		   $('#temperature'    )[0].checkValidity() == true &&
		   $('#tensionmaximale')[0].checkValidity() == true &&
		   $('#tensionminimale')[0].checkValidity() == true &&
		   $('#pouls'          )[0].checkValidity() == true
		  ){	
			
			$('#poids').attr( 'readonly', true );    
	   		$('#taille').attr( 'readonly', true );
	    	$('#temperature').attr( 'readonly', true);
			$('#glycemie_capillaire').attr( 'readonly', true);
	  		$('#pouls').attr( 'readonly', true);
	 		$('#frequence_respiratoire').attr( 'readonly', true);
	  		$("#tensionmaximale").attr( 'readonly', true );
	   		$("#tensionminimale").attr( 'readonly', true );
	   		
	   		
	   		$("#bouton_constantes_modifier").toggle(true); 
	   		$("#bouton_constantes_valider").toggle(false);
	   		
	   		return false; 
		}
   		 
	});
	
	$( "#bouton_constantes_modifier" ).click(function(){
		$('#poids').attr( 'readonly', false );
		$('#taille').attr( 'readonly', false ); 
		$('#temperature').attr( 'readonly', false);
		$('#glycemie_capillaire').attr( 'readonly', false);
		$('#pouls').attr( 'readonly', false);
		$('#frequence_respiratoire').attr( 'readonly', false);
		$("#tensionmaximale").attr( 'readonly', false );
		$("#tensionminimale").attr( 'readonly', false );
		
	 	$("#bouton_constantes_modifier").toggle(false);   //on cache le bouton permettant de modifier les champs
	 	$("#bouton_constantes_valider").toggle(true);    //on affiche le bouton permettant de valider les champs
	 	return  false;
	});

	
	$('#afficherAdmissionInfirmierTri').css({'font-weight':'bold', 'font-size': '17px' });
	$('#titre span').html('<span>PATIENTS ADMIS PAR L\'INFIRMIER DE TRI</span>');
	
});


$('#niveauAlerte div input[name=niveau][value="4"]').attr('checked', true); 
$('#blanc' ).parent().css({'background' : '#e1e1e1'});
var boutons = $('#niveauAlerte div input[name=niveau]');
$(boutons).click(function(){

	if(boutons[0].checked){ 
		$('#blanc' ).parent().css({'background' : '#e1e1e1'});
		$('#jaune' ).parent().css({'background' : '#eeeeee'});
		$('#orange').parent().css({'background' : '#eeeeee'});
		$('#rouge' ).parent().css({'background' : '#eeeeee', 'color' : '#000000'});
	}else
		if(boutons[1].checked){ 
			$('#blanc' ).parent().css({'background' : '#eeeeee'});
			$('#jaune' ).parent().css({'background' : 'yellow'});
			$('#orange').parent().css({'background' : '#eeeeee'});
			$('#rouge' ).parent().css({'background' : '#eeeeee', 'color' : '#000000'});
		}else
			if(boutons[2].checked){ 
				$('#blanc' ).parent().css({'background' : '#eeeeee'});
				$('#jaune' ).parent().css({'background' : '#eeeeee'});
				$('#orange').parent().css({'background' : 'orange'});
				$('#rouge' ).parent().css({'background' : '#eeeeee', 'color' : '#000000'});
			}else
				if(boutons[3].checked){ 
					$('#blanc' ).parent().css({'background' : '#eeeeee'});
					$('#jaune' ).parent().css({'background' : '#eeeeee'});
					$('#orange').parent().css({'background' : '#eeeeee'});
					$('#rouge' ).parent().css({'background' : 'red', 'color' : '#FFFFFF'});
				}
	
});

function clignoterAlerte1(){
	$('#rouge' ).parent().css({'background' : '#eeeeee'});
	setTimeout(function(){ clignoterAlerte2(); }, 500);
}

function clignoterAlerte2(){
	$('#rouge' ).parent().css({'background' : '#FF0000'});
	setTimeout(function(){ clignoterAlerte1(); }, 500);
}
//*********************************************************************
//*********************************************************************
//*********************************************************************


function ajouterPatient(){
	vart=tabUrl[0]+'public/urgence/ajout-patient';
    $(location).attr("href",vart);
    return false;
}

// BOITE DE DIALOG POUR LA CONFIRMATION DE SUPPRESSION
function visualisationInformations(id) {
	$("#visualisationInformations").dialog({
		resizable : false,
		height : 375,
		width : 485,
		autoOpen : false,
		modal : true,
		buttons : {
			"Terminer" : function() {
				$(this).dialog("close");
				return false;
			}
		}
	});
}

function visualiser(id) { 
	visualisationInformations(id);
	var cle = id;
	var chemin = tabUrl[0] + 'public/urgence/get-infos-vue-patient';
	$.ajax({
		type : 'POST',
		url : chemin,
		data : $(this).serialize(),
		data : 'id=' + cle,
		success : function(data) {
			var result = jQuery.parseJSON(data);
			$("#info").html(result);

			$("#visualisationInformations").dialog('open');

		},
		error : function(e) {
			console.log(e);
			alert("Une erreur interne est survenue!");
		},
		dataType : "html"
	});
}

$(function() {
	setTimeout(function() {
		infoBulle();
	}, 1000);
});

function infoBulle() {
	/***************************************************************************
	 * INFO BULLE FE LA LISTE
	 */
	var tooltips = $('table tbody tr td infoBulleVue').tooltip({
		show : {
			effect : 'slideDown',
			delay : 250
		}
	});
	tooltips.tooltip('close');
	$('table tbody tr td infoBulleVue').mouseenter(function() {
		var tooltips = $('table tbody tr td infoBulleVue').tooltip({
			show : {
				effect : 'slideDown',
				delay : 250
			}
		});
		tooltips.tooltip('open');
	});
}
var oTable;
function initialisation() {

	var asInitVals = new Array();
	oTable = $('#patientAAdmettre') .dataTable( {
						"sPaginationType" : "full_numbers",
						"aLengthMenu" : [ 5, 7, 10, 15 ],
						"aaSorting" : [], // On ne trie pas la liste automatiquement
						"oLanguage" : {
							"sInfo" : "_START_ &agrave; _END_ sur _TOTAL_ patients",
							"sInfoEmpty" : "0 &eacute;l&eacute;ment &agrave; afficher",
							"sInfoFiltered" : "",
							"sUrl" : "",
							"oPaginate" : {
								"sFirst" : "|<",
								"sPrevious" : "<",
								"sNext" : ">",
								"sLast" : ">|"
							}
						},

						"sAjaxSource" : "" + tabUrl[0] + "public/urgence/liste-admission-ajax",

						"fnDrawCallback" : function() {
							// markLine();
							clickRowHandler();
						}

					});

	$("#patientAAdmettre thead input").keyup(function() {
		/* Filter on the column (the index) of this element */
		oTable.fnFilter(this.value, $("#patientAAdmettre thead input").index(this));
	});

	/*
	 * Support functions to provide a little bit of 'user friendlyness' to the
	 * textboxes in the footer
	 */
	$("#patientAAdmettre thead input").each(function(i) {
		asInitVals[i] = this.value;
	});

	$("#patientAAdmettre thead input").focus(function() {
		if (this.className == "search_init") {
			this.className = "";
			this.value = "";
		}
	});

	$("#patientAAdmettre thead input").blur(function(i) {
		if (this.value == "") {
			this.className = "search_init";
			this.value = asInitVals[$("#patientAAdmettre thead input").index(this)];
		}
	});
	
	$('#patientAAdmettre thead th').unbind('click');

}

function clickRowHandler() {
	var id;
	$('#patientAAdmettre tbody tr').contextmenu(
			{
				target : '#context-menu',
				onItem : function(context, e) {

					if ($(e.target).text() == 'Visualiser'
							|| $(e.target).is('#visualiserCTX')) {
						visualiser(id);
					} else if ($(e.target).text() == 'Suivant'
							|| $(e.target).is('#suivantCTX')) {
						admettre(id);

					}

				}

			}).bind('mousedown', function(e) {
		var aData = oTable.fnGetData(this);
		id = aData[6];
	});

	$("#patientAAdmettre tbody tr").bind('dblclick', function(event) {
		var aData = oTable.fnGetData(this);
		var id = aData[6];
		visualiser(id);
	});

}

function animation() {
	// ANIMATION
	// ANIMATION
	// ANIMATION

	$('#admission_urgence').toggle(false);

	$('#precedent').click(function() {
		$('#titre span').html('<span>RECHERCHER LE PATIENT</span>');
		
		$('#contenu').animate({
			height : 'toggle'
		}, 1000);
		
		
		$('#admission_urgence').animate({
			height : 'toggle'
		}, 1000);
		
		
		// IL FAUT LE RECREER POUR L'ENLEVER DU DOM A CHAQUE
		// FOIS QU'ON CLIQUE SUR PRECEDENT
		$("#termineradmission")	.replaceWith("<button id='termineradmission' style='height:35px;'>Terminer</button>");
		
		return false;
		
	});
}

function getListeLits(id_salle){ 
	if(couloirClick == 1){
		$('#couloir').trigger('click');
	}
	
	$('#lit').html("");
	var chemin = tabUrl[0] + 'public/urgence/liste-lits';
	$.ajax({
		type : 'POST',
		url : chemin,
		data : {'id_salle':id_salle},
		success : function(data) {
			var result = jQuery.parseJSON(data);
			$("#lit").html(result);
		}
	});
}

function gestionDesChampsRequis(){
	$("#poids").attr( 'required', true );    
	$("#taille").attr( 'required', true );
	$("#temperature").attr( 'required', true);
	$("#tensionmaximale").attr( 'required', true );
	$("#tensionminimale").attr( 'required', true );
	$("#pouls").attr( 'required', true );
	$("#salle").attr( 'required', true );
	$("#lit").attr( 'required', false );
}

var entreIniMotif = 0;
function admettre(id_patient){ 

	gestionDesChampsRequis();
	if(entreIniMotif == 0){ afficherMotif(1); entreIniMotif = 1;}
	
	$("#termineradmission").replaceWith("<button id='termineradmission' style='height:35px;'>Admettre</button>");
	$("#annuleradmission" ).replaceWith("<button id='annuleradmission' style='height:35px;'>Annuler</button>");
	$('#titre span').html('<span>ADMISSION</span>');
	
	//Envoyer le formulaire
	$('#termineradmission').click(function(){

		if( $('#poids').val() && $('#taille').val() && $('#temperature').val() 
		    && $('#tensionmaximale').val() && $('#tensionminimale').val() && $('#pouls').val()
		    && $('#salle').val()){
			
			if($('#listePatientAdmisInfServiceForm')[0].checkValidity() == true){
				
    			$(this).attr('disabled', true);
    			$('#envoyerDonneesForm').trigger('click');
			}else{
				if(
				   $('#poids'          )[0].checkValidity() == false ||
				   $('#taille'         )[0].checkValidity() == false ||
				   $('#temperature'    )[0].checkValidity() == false ||
				   $('#tensionmaximale')[0].checkValidity() == false ||
				   $('#tensionminimale')[0].checkValidity() == false ||
				   $('#pouls'          )[0].checkValidity() == false 
				){ 
					$(".constantes_donnees_onglet").trigger('click');
				}else{
					$(".orientation_donnees_onglet").trigger('click');
				}
			}
			
		}else{ 
			if( !$('#poids').val() || !$('#taille').val() || !$('#temperature').val() ||
				!$('#tensionmaximale').val() || !$('#tensionminimale').val() || !$('#pouls').val()){ 
				$(".constantes_donnees_onglet").trigger('click');
			}else{
				if( couloirClick == 1 ){
					if($('#listePatientAdmisInfServiceForm')[0].checkValidity() == true){
						
		    			$(this).attr('disabled', true); 
		    			$('#envoyerDonneesForm').trigger('click');
					}else{ 
						$(".constantes_donnees_onglet").trigger('click');
					}
				}else{
					$(".orientation_donnees_onglet").trigger('click');
				}
			}
		}
		
	});
	
	// R�cup�ration des donn�es du patient
	var chemin = tabUrl[0] + 'public/urgence/admission';
	$.ajax({
		type : 'POST',
		url : chemin,
		data : {'id':id_patient},
		success : function(data) {
			var result = jQuery.parseJSON(data);
			$("#info_patient").html(result);
			// PASSER A SUIVANT
			$('#admission_urgence').animate({
				height : 'toggle'
			}, 1000);
			$('#contenu').animate({
				height : 'toggle'
			}, 1000);
			
			$("#motif_admission_donnees").css({'height':'350px'});
			$("#constantes_donnees").css({'height':'330px'});
			$("#orientation_donnees").css({'height':'100px'});

			//Reduction de linterface
			$("#accordionsUrgence").css({'min-height':'100px'});
			
		},
		error : function(e) {
			console.log(e);
			alert("Une erreur interne est survenue!");
		},
		dataType : "html"
	});
	// Fin R�cup�ration des donn�es de la maman

	// Annuler l'enregistrement d'une naissance
	$("#annuleradmission").click( function() {

		vart = tabUrl[0] + 'public/urgence/admission';
		$(location).attr("href", vart);
		return false;
		
	});

	$("#id_patient").val(id_patient);

}


//GESTION DE LA LISTE DES PATIENTS ADMIS PAR L'INFIMIER DE TRI
//GESTION DE LA LISTE DES PATIENTS ADMIS PAR L'INFIMIER DE TRI
//GESTION DE LA LISTE DES PATIENTS ADMIS PAR L'INFIMIER DE TRI

var oTableListeInfTri;
function initialisationListeInfTri() {

	var asInitValsListeInfTri = new Array();
	oTableListeInfTri = $('#patientAdmisParInfirmierTri') .dataTable( {
						"sPaginationType" : "full_numbers",
						"aLengthMenu" : [ 5, 7, 10, 15 ],
						"aaSorting" : [], // On ne trie pas la liste automatiquement
						"oLanguage" : {
							"sInfo" : "_START_ &agrave; _END_ sur _TOTAL_ patients",
							"sInfoEmpty" : "0 &eacute;l&eacute;ment &agrave; afficher",
							"sInfoFiltered" : "",
							"sUrl" : "",
							"oPaginate" : {
								"sFirst" : "|<",
								"sPrevious" : "<",
								"sNext" : ">",
								"sLast" : ">|"
							}
						},

						"sAjaxSource" : "" + tabUrl[0] + "public/urgence/liste-admission-infirmier-tri-ajax",

						"fnDrawCallback" : function() {
							// markLine();
							clickRowHandler();
						}

					} );

	$("#patientAdmisParInfirmierTri thead input").keyup(function() {
		/* Filter on the column (the index) of this element */
		oTableListeInfTri.fnFilter(this.value, $("#patientAdmisParInfirmierTri thead input").index(this));
	});

	/*
	 * Support functions to provide a little bit of 'user friendlyness' to the
	 * textboxes in the footer
	 */
	$("#patientAdmisParInfirmierTri thead input").each(function(i) {
		asInitValsListeInfTri[i] = this.value;
	});

	$("#patientAdmisParInfirmierTri thead input").focus(function() {
		if (this.className == "search_init") {
			this.className = "";
			this.value = "";
		}
	});

	$("#patientAdmisParInfirmierTri thead input").blur(function(i) {
		if (this.value == "") {
			this.className = "search_init";
			this.value = asInitValsListeInfTri[$("#patientAdmisParInfirmierTri thead input").index(this)];
		}
	});

	
	$('#afficherAdmissionInfirmierTri').click(function(){
		identifierListeAfficher = 1;
		$('#divPatientAAdmettre').fadeOut(function(){
			$('#divPatientAdmisParInfirmierTri').fadeIn();
		});
		$('#afficherAdmissionInfirmierTri').css({'font-weight':'bold', 'font-size': '17px' });
		$('#afficherAdmissionInfirmierSevice').css({'font-weight':'normal', 'font-size': '13px' });
		
		$('#titre span span').fadeOut(function(){
			$('#titre span').html('<span>PATIENTS ADMIS PAR L\'INFIRMIER DE TRI</span>');
		});
		
		if(entreIniMotif == 1){ 
			vart = tabUrl[0] + 'public/urgence/admission';
			$(location).attr("href", vart);
		}

	});

	$('#afficherAdmissionInfirmierSevice').click(function(){
		identifierListeAfficher = 0;
		$('#divPatientAdmisParInfirmierTri').fadeOut(function(){
			$('#divPatientAAdmettre').fadeIn();
		});
		$('#afficherAdmissionInfirmierTri').css({'font-weight':'normal', 'font-size': '13px'});
		$('#afficherAdmissionInfirmierSevice').css({'font-weight':'bold', 'font-size': '17px' });
		
		$('#titre span span').fadeOut(function(){
			$('#titre span').html('<span>RECHERCHER LE PATIENT</span>');
		});

	});
	
	$('#patientAdmisParInfirmierTri thead th').unbind('click');
	
	raffarichirLaListePatientAdmisParInfirmierTri();
}

//Raffraichir s'il y'a de nouvels admis par l'infirmier de tri
//Raffraichir s'il y'a de nouvels admis par l'infirmier de tri

function raffarichirLaListePatientAdmisParInfirmierTri(){
	setTimeout(function(){

		var chemin = tabUrl[0] + 'public/urgence/get-nb-patient-admis-non-vu';
		$.ajax({
			type : 'POST',
			url : chemin,
			success : function(data) {
				var result = jQuery.parseJSON(data);
				
				if(identifierListeAfficher == 1 && nbPatientAdmisNonVu < result){
					//nbPatientAdmisNonVu++;
					//oTableListeInfTri.fnDestroy(); 
					//initialisationListeInfTri();
					
					//Raffraichir la liste
					vart = tabUrl[0] + 'public/urgence/admission';
					$(location).attr("href", vart);
				}
				
				raffarichirLaListePatientAdmisParInfirmierTri();
				
				return false;
			}
		});
		
	},30000);
}


function admettreVersMedecin(id_patient, id_admission) { 

	gestionDesChampsRequis();
	identifierListeAfficher = 0;

	$("#termineradmission").replaceWith("<button id='termineradmission' style='height:35px;'>Admettre</button>");
	$("#annuleradmission" ).replaceWith("<button id='annuleradmission' style='height:35px;'>Annuler</button>");
	$('#titre span').html('<span>ADMISSION</span>');
	$("#precedent").css('visibility','hidden');
	
	
	//Envoyer le formulaire
	$('#termineradmission').click(function(){
	  	
		if( $('#poids').val() && $('#taille').val() && $('#temperature').val() 
		    && $('#tensionmaximale').val() && $('#tensionminimale').val() && $('#pouls').val()
		    && $('#salle').val()){
			
			if($('#listePatientAdmisInfServiceForm')[0].checkValidity() == true){
				
    			$(this).attr('disabled', true);
    			$('#envoyerDonneesForm').trigger('click');
			}else{
				if(
				   $('#poids'          )[0].checkValidity() == false ||
				   $('#taille'         )[0].checkValidity() == false ||
				   $('#temperature'    )[0].checkValidity() == false ||
				   $('#tensionmaximale')[0].checkValidity() == false ||
				   $('#tensionminimale')[0].checkValidity() == false ||
				   $('#pouls'          )[0].checkValidity() == false 
				){ 
					$(".constantes_donnees_onglet").trigger('click');
				}else{
					$(".orientation_donnees_onglet").trigger('click');
				}
			}
			
		}else{ 
			if( !$('#poids').val() || !$('#taille').val() || !$('#temperature').val() ||
				!$('#tensionmaximale').val() || !$('#tensionminimale').val() || !$('#pouls').val()){ 
				$(".constantes_donnees_onglet").trigger('click');
			}else{
				if( couloirClick == 1 ){
					if($('#listePatientAdmisInfServiceForm')[0].checkValidity() == true){
						
		    			$(this).attr('disabled', true); 
		    			$('#envoyerDonneesForm').trigger('click');
					}else{ 
						$(".constantes_donnees_onglet").trigger('click');
					}
				}else{
					$(".orientation_donnees_onglet").trigger('click');
				}
			}
		}
		
	});
	
	// R�cup�ration des donn�es du patient
	var chemin = tabUrl[0] + 'public/urgence/get-infos-admission-par-infirmier-tri';
	$.ajax({
		type : 'POST',
		url : chemin,
		data : {'id_patient':id_patient, 'id_admission':id_admission},
		success : function(data) {
			var result = jQuery.parseJSON(data);
			$("#info_patient").html(result);
			// PASSER A SUIVANT
			$('#admission_urgence').animate({
				height : 'toggle'
			}, 1000);
			$('#contenu').animate({
				height : 'toggle'
			}, 1000);
			
			$("#motif_admission_donnees").css({'height':'350px'});
			$("#constantes_donnees").css({'height':'330px'});
			$("#orientation_donnees").css({'height':'100px'});

			//Reduction de linterface
			$("#accordionsUrgence").css({'min-height':'100px'});
			
		},
		error : function(e) {
			console.log(e);
			alert("Une erreur interne est survenue!");
		},
		dataType : "html"
	});
	// Fin R�cup�ration des donn�es de la maman

	// Annuler l'enregistrement d'une naissance
	$("#annuleradmission").click( function() {

		vart = tabUrl[0] + 'public/urgence/admission';
		$(location).attr("href", vart);
		return false;
		
	});

	$("#id_patient").val(id_patient);
	$("#id_admission").val(id_admission);

}



function dep1(){
	$('#depliantBandelette').click(function(){
		$("#depliantBandelette").replaceWith("<img id='depliantBandelette' style='cursor: pointer; position: absolute; padding-right: 120px; margin-left: -5px;' src='../img/light/plus.png' />");
		dep();
	    $('#BUcheckbox').animate({
	        height : 'toggle'
	    },1000);
	 return false;
	});
}

function dep(){
	$('#depliantBandelette').click(function(){
		$("#depliantBandelette").replaceWith("<img id='depliantBandelette' style='cursor: pointer; position: absolute; padding-right: 120px; margin-left: -5px;' src='../img/light/minus.png' />");
		dep1();
	    $('#BUcheckbox').animate({
	        height : 'toggle'
	    },1000);
	 return false;
	});
}

function OptionCochee() {
	$("#labelAlbumine").toggle(false);
	$("#labelSucre").toggle(false);
	$("#labelCorpscetonique").toggle(false);
	
	$('#BUcheckbox input[name=albumine]').click(function(){
		$("#ChoixPlus").toggle(false);
		var boutons = $('#BUcheckbox input[name=albumine]');
		if(boutons[0].checked){	$("#labelAlbumine").toggle(false); $("#BUcheckbox input[name=croixalbumine]").attr('checked', false); }
		if(boutons[1].checked){ $("#labelAlbumine").toggle(true); $("#labelCroixAlbumine").toggle(true);}
	});

	$('#BUcheckbox input[name=sucre]').click(function(){
		$("#ChoixPlus2").toggle(false);
		var boutons = $('#BUcheckbox input[name=sucre]');
		if(boutons[0].checked){	$("#labelSucre").toggle(false); $("#BUcheckbox input[name=croixsucre]").attr('checked', false); }
		if(boutons[1].checked){ $("#labelSucre").toggle(true); $("#labelCroixSucre").toggle(true);}
	});

	$('#BUcheckbox input[name=corpscetonique]').click(function(){
		$("#ChoixPlus3").toggle(false);
		var boutons = $('#BUcheckbox input[name=corpscetonique]');
		if(boutons[0].checked){	$("#labelCorpscetonique").toggle(false); $("#BUcheckbox input[name=croixcorpscetonique]").attr('checked', false); }
		if(boutons[1].checked){ $("#labelCorpscetonique").toggle(true); $("#labelCroixCorpscetonique").toggle(true);}
	});


	//CHOIX DU CROIX
	//========================================================
	$("#ChoixPlus").toggle(false);
	$('#BUcheckbox input[name=croixalbumine]').click(function(){
		var boutons = $('#BUcheckbox input[name=croixalbumine]');
		if(boutons[0].checked){
			$("#labelCroixAlbumine").toggle(false); 
			$("#ChoixPlus").toggle(true); 
			$("#ChoixPlus label").html("1+");

		}
		if(boutons[1].checked){ 
			$("#labelCroixAlbumine").toggle(false); 
			$("#ChoixPlus").toggle(true); 
			$("#ChoixPlus label").html("2+");

		}
		if(boutons[2].checked){ 
			$("#labelCroixAlbumine").toggle(false); 
			$("#ChoixPlus").toggle(true); 
			$("#ChoixPlus label").html("3+");
			
		}
		if(boutons[3].checked){ 
			$("#labelCroixAlbumine").toggle(false); 
			$("#ChoixPlus").toggle(true); 
			$("#ChoixPlus label").html("4+");

		}
	});

	//========================================================
	$("#ChoixPlus2").toggle(false);
	$('#BUcheckbox input[name=croixsucre]').click(function(){
		var boutons = $('#BUcheckbox input[name=croixsucre]');
		if(boutons[0].checked){
			$("#labelCroixSucre").toggle(false); 
			$("#ChoixPlus2").toggle(true); 
			$("#ChoixPlus2 label").html("1+");

		}
		if(boutons[1].checked){ 
			$("#labelCroixSucre").toggle(false); 
			$("#ChoixPlus2").toggle(true); 
			$("#ChoixPlus2 label").html("2+");

		}
		if(boutons[2].checked){ 
			$("#labelCroixSucre").toggle(false); 
			$("#ChoixPlus2").toggle(true); 
			$("#ChoixPlus2 label").html("3+");
			
		}
		if(boutons[3].checked){ 
			$("#labelCroixSucre").toggle(false); 
			$("#ChoixPlus2").toggle(true); 
			$("#ChoixPlus2 label").html("4+");

		}
	});

	//========================================================
	$("#ChoixPlus3").toggle(false);
	$('#BUcheckbox input[name=croixcorpscetonique]').click(function(){
		var boutons = $('#BUcheckbox input[name=croixcorpscetonique]');
		if(boutons[0].checked){
			$("#labelCroixCorpscetonique").toggle(false); 
			$("#ChoixPlus3").toggle(true); 
			$("#ChoixPlus3 label").html("1+");

		}
		if(boutons[1].checked){ 
			$("#labelCroixCorpscetonique").toggle(false); 
			$("#ChoixPlus3").toggle(true); 
			$("#ChoixPlus3 label").html("2+");

		}
		if(boutons[2].checked){ 
			$("#labelCroixCorpscetonique").toggle(false); 
			$("#ChoixPlus3").toggle(true); 
			$("#ChoixPlus3 label").html("3+");
			
		}
		if(boutons[3].checked){ 
			$("#labelCroixCorpscetonique").toggle(false); 
			$("#ChoixPlus3").toggle(true); 
			$("#ChoixPlus3 label").html("4+");

		}
	});
}



//========================================================
//========================================================
//========================================================
//========================================================

function albumineOption(){
	  
	  $("#labelAlbumine").toggle(true);
		
	    var boutons = $('#BUcheckbox input[name=croixalbumine]');
		if(boutons[0].checked){
			$("#labelCroixAlbumine").toggle(false); 
			$("#ChoixPlus").toggle(true); 
			$("#ChoixPlus label").html("1+");

		}
		if(boutons[1].checked){ 
			$("#labelCroixAlbumine").toggle(false); 
			$("#ChoixPlus").toggle(true); 
			$("#ChoixPlus label").html("2+");

		}
		if(boutons[2].checked){ 
			$("#labelCroixAlbumine").toggle(false); 
			$("#ChoixPlus").toggle(true); 
			$("#ChoixPlus label").html("3+");
			
		}
		if(boutons[3].checked){ 
			$("#labelCroixAlbumine").toggle(false); 
			$("#ChoixPlus").toggle(true); 
			$("#ChoixPlus label").html("4+");

		}
}

//========================================================
//========================================================
	
function sucreOption(){
	  
	  $("#labelSucre").toggle(true);
	  
	  var boutons = $('#BUcheckbox input[name=croixsucre]');
	  if(boutons[0].checked){
			$("#labelCroixSucre").toggle(false); 
			$("#ChoixPlus2").toggle(true); 
			$("#ChoixPlus2 label").html("1+");

		}
		if(boutons[1].checked){ 
			$("#labelCroixSucre").toggle(false); 
			$("#ChoixPlus2").toggle(true); 
			$("#ChoixPlus2 label").html("2+");

		}
		if(boutons[2].checked){ 
			$("#labelCroixSucre").toggle(false); 
			$("#ChoixPlus2").toggle(true); 
			$("#ChoixPlus2 label").html("3+");
			
		}
		if(boutons[3].checked){ 
			$("#labelCroixSucre").toggle(false); 
			$("#ChoixPlus2").toggle(true); 
			$("#ChoixPlus2 label").html("4+");

		}
}

//========================================================
//========================================================
	
function corpscetoniqueOption(){
	  
	  $("#labelCorpscetonique").toggle(true);
	  
	  var boutons = $('#BUcheckbox input[name=croixcorpscetonique]');
		if(boutons[0].checked){
			$("#labelCroixCorpscetonique").toggle(false); 
			$("#ChoixPlus3").toggle(true); 
			$("#ChoixPlus3 label").html("1+");

		}
		if(boutons[1].checked){ 
			$("#labelCroixCorpscetonique").toggle(false); 
			$("#ChoixPlus3").toggle(true); 
			$("#ChoixPlus3 label").html("2+");

		}
		if(boutons[2].checked){ 
			$("#labelCroixCorpscetonique").toggle(false); 
			$("#ChoixPlus3").toggle(true); 
			$("#ChoixPlus3 label").html("3+");
			
		}
		if(boutons[3].checked){ 
			$("#labelCroixCorpscetonique").toggle(false); 
			$("#ChoixPlus3").toggle(true); 
			$("#ChoixPlus3 label").html("4+");

		}
}
