function creerLalisteExamenBio ($listeDesElements) {
    	var index = $("ExamensBiologiques").length; 
			        $liste = "<div id='ExamenBio_"+(index+1)+"'>"+
				             "<ExamensBiologiques>"+
				             "<table class='table table-bordered' id='Examen' style='margin-bottom: 0px; width: 100%;'>"+
                             "<tr style='width: 100%;'>" +
                             
                             "<th style='width: 4%;'>"+
                             "<label style='width: 100%; margin-top: 10px; margin-left: 5px; font-weight: bold; font-family: police2; font-size: 14px;' >"+(index+1)+"<span id='element_label'></span></label>" +
                             "</th >"+
                             
                             "<th id='SelectExamenBio_"+(index+1)+"' style='width: 28%;'>"+
                             "<select onchange='ActiverResultatExamenBiologie()' style='width: 100%; margin-top: 3px; margin-bottom: 0px; font-size: 13px;' name='examenBio_name_"+(index+1)+"' id='examenBio_name_"+(index+1)+"'>"+
			                 "<option value=''> -- S&eacute;l&eacute;ctionner un examen -- </option>";
                             for(var i = 1 ; i < $listeDesElements.length ; i++){
                            	 if($listeDesElements[i]){
                    $liste +="<option value='"+i+"'>"+$listeDesElements[i]+"</option>";
                            	 }
                             }   
                    $liste +="</select>"+                           
                             "</th>"+
                             
                             "<th id='noteExamenBio_"+(index+1)+"'  style='width: 55%;'  >"+
                             "<input   id='noteExamenBio"+(index+1)+"'     name='noteExamenBio_"+(index+1)+"' type='text' style='width: 100%; margin-top: 3px; height: 30px; margin-bottom: 0px; font-size: 15px; padding-left: 10px;' >" +
                             "</th >"+
                             
                             "<th id='iconeExamenBio_supp_vider' style='width: 25%;'  >"+
                             "<a id='supprimer_examenBio_selectionne_"+ (index+1) +"'  style='width:50%;' >"+
                             "<img class='supprimerExamenBio' style='margin-left: 5px; margin-top: 10px; cursor: pointer;' src='../images/images/sup.png' title='supprimer' />"+
                             "</a>"+
                             
                             "<a id='vider_examenBio_selectionne_"+ (index+1) +"'  style='width:50%;' >"+
                             "<img class='viderExamenBio' style='margin-left: 15px; margin-top: 10px; cursor: pointer;' src='../images_icons/gomme.png' title='vider' />"+
                             "</a>"+
                             "<input value='' type='submit'  name='demandeExamenBio_"+ (index+1) +"' id='demandeExamenBio_"+ (index+1) +"' " +
                       		" style='width: 32px; height: 32px; background: url(../images_icons/pdf.PNG) no-repeat	right top;'  title='Imprimer'/>"+
                       
                             "</tr>" +
                             "</table>" +
                             "</ExamensBiologiques>" +
                             "</div>"+
                             
                             
                             "<script>"+
                                "$('#supprimer_examenBio_selectionne_"+ (index+1) +"').click(function(){ " +
                                		"supprimer_examenBio_selectionne("+ (index+1) +"); });" +
                                				
                                "$('#vider_examenBio_selectionne_"+ (index+1) +"').click(function(){ " +
                                		"vider_examenBio_selectionne("+ (index+1) +"); });" +
                             "</script>";
                    
                    //AJOUTER ELEMENT SUIVANT
                    $("#ExamenBio_"+index).after($liste);
                    
                    //CACHER L'ICONE AJOUT QUAND ON A CINQ LISTES
                    if((index+1) == 6){
                    	$("#ajouter_examenBio").toggle(false);
                    }
                    
                    //AFFICHER L'ICONE SUPPRIMER QUAND ON A DEUX LISTES ET PLUS
                    if((index+1) == 2){
                    	$("#supprimer_examenBio").toggle(true);
                    }

                    var element = $('#examenBio_name_'+i).val();
            		$("#SelectExamenBio_"+(i-1)+" option[value='"+element+"']").attr('selected','selected');
            		
}

//NOMBRE DE LISTE AFFICHEES
function nbListeExamenBio () {
	return $("ExamensBiologiques").length;
}
//Activation d'un résultat de l'examen biologique
function ActiverResultatExamenBiologie(){
	var index = $("ExamensBiologiques").length;

	d = document.getElementById("examenBio_name_"+index+"").value;

	if(d==1){
		$("#resultat_groupe_sanguin").toggle(true);
		
	}
	if(d==2){
		$("#resultat_hemogramme_sanguin").toggle(true);
	}
	if(d==3){
		$("#resultat_bilan_hepatique").toggle(true);
	}
	if(d==4){
		$("#resultat_bilan_renal").toggle(true);
	}
	if(d==5){
		$("#resultat_bilan_hemolyse").toggle(true);
	}
	if(d==6){
		$("#resultat_bilan_inflammatoire").toggle(true);
	}
	
}

//SUPPRIMER LE DERNIER ELEMENT
$(function () {
	//Au d�but on cache la suppression
	$("#supprimer_examenBio").click(function(){
		//ON PEUT SUPPRIMER QUAND C'EST PLUS DE DEUX LISTE
		if(nbListeExamenBio () >  1){$("#ExamenBio_"+nbListeExamenBio ()).remove();}
		//ON CACHE L'ICONE SUPPRIMER QUAND ON A UNE LIGNE
		if(nbListeExamenBio () == 1) {
			$("#supprimer_examenBio").toggle(false);
			$(".supprimerExamenBio" ).replaceWith(
			  "<img class='supprimerExamenBio' style='margin-left: 5px; margin-top: 10px;' src='../images/images/sup2.png' />"
			);
		}
		//Afficher L'ICONE AJOUT QUAND ON A CINQ LIGNES
		if((nbListeExamenBio()+1) == 6){
			$("#ajouter_examenBio").toggle(true);
		}    
		Event.stopPropagation();
	});
});


//FONCTION INITIALISATION (Par d�faut)
function partDefautExamenBio (Liste, n) { 
	
	var i = 0;
	for( i ; i < n ; i++){
		creerLalisteExamenBio(Liste);
	}
	if(n == 1){
		$(".supprimerExamenBio" ).replaceWith(
				"<img class='supprimerExamenBio' style='margin-left: 5px; margin-top: 10px;' src='../images/images/sup2.png' />"
			);
	}
	$('#ajouter_examenBio').click(function(){ 
		creerLalisteExamenBio(Liste);
		if(nbListeExamenBio() == 2){
		$(".supprimerExamenBio" ).replaceWith(
				"<img class='supprimerExamenBio' style='margin-left: 5px; margin-top: 10px; cursor: pointer;' src='../images/images/sup.png' title='Supprimer' />"
		);
		}
	});

	//AFFICHER L'ICONE SUPPRIMER QUAND ON A DEUX LISTES ET PLUS
    if(nbListeExamenBio () > 1){
    	$("#supprimer_examenBio").toggle(true);
    } else {
    	$("#supprimer_examenBio").toggle(false);
      }
}

//SUPPRIMER ELEMENT SELECTIONNER
function supprimer_examenBio_selectionne(id) { 
	
	
	 
	
	//On cache le champ résultat en cas de suppression dún examen fonctionnel
	if( $('#examenBio_name_'+id).val()==1){
		$("#resultat_groupe_sanguin").toggle(false);
		//$('#groupe_sanguin').attr('readonly',false); 
	}
	if( $('#examenBio_name_'+id).val()==2){
		$("#resultat_hemogramme_sanguin").toggle(false);
		//$('#hemogramme_sanguin').attr('readonly',false); 
	}
	
	if( $('#examenBio_name_'+id).val()==3){
		$("#resultat_bilan_hemolyse").toggle(false);
		//$('#bilan_hemolyse').attr('readonly',false);
	}
	if( $('#examenBio_name_'+id).val()==4){
		$("#resultat_bilan_hepatique").toggle(false);
		//$('#bilan_hepatique').attr('readonly',false);
	}
	if( $('#examenBio_name_'+id).val()==5){
		$("#resultat_bilan_renal").toggle(false);
		//$('#bilan_renal').attr('readonly',false);
	}
	if( $('#examenBio_name_'+id).val()==6){
		$("#resultat_bilan_inflammatoire").toggle(false);
		//$('#bilan_inflammatoire').attr('readonly',false); 
	}
	
	
	for(var i = (id+1); i <= nbListeExamenBio(); i++ ){
		var element = $('#examenBio_name_'+i).val();
		$("#SelectExamenBio_"+(i-1)+" option[value='"+element+"']").attr('selected','selected');
		
		var note = $('#noteExamenBio_'+i+' input').val();
		$("#noteExamenBio_"+(i-1)+" input").val(note);
	}

	if(nbListeExamenBio() <= 2 && id <= 2){
		$(".supprimerExamenBio" ).replaceWith(
			"<img class='supprimerExamenBio' style='margin-left: 5px; margin-top: 10px;' src='../images/images/sup2.png' />"
		);
	}
	if(nbListeExamenBio() != 1) {
		$('#ExamenBio_'+nbListeExamenBio()).remove();
	}
	if(nbListeExamenBio() == 1) {
		$("#supprimer_examenBio").toggle(false);
	}
	if((nbListeExamenBio()+1) == 6){
		$("#ajouter_examenBio").toggle(true);
	}
   
}

//VIDER LES CHAMPS DE L'ELEMENT SELECTIONNER
function vider_examenBio_selectionne(id) {
	
	//On cache le champ résultat en cas de suppression dún examen fonctionnel
	if( $('#examenBio_name_'+id).val()==1){
		$("#resultat_groupe_sanguin").toggle(false);
		//$('#groupe_sanguin').attr('readonly',false); 
	}
	if( $('#examenBio_name_'+id).val()==2){
		$("#resultat_hemogramme_sanguin").toggle(false);
		//$('#hemogramme_sanguin').attr('readonly',false); 
	}
	
	if( $('#examenBio_name_'+id).val()==3){
		$("#resultat_bilan_hemolyse").toggle(false);
		//$('#bilan_hemolyse').attr('readonly',false);
	}
	if( $('#examenBio_name_'+id).val()==4){
		$("#resultat_bilan_hepatique").toggle(false);
		//$('#bilan_hepatique').attr('readonly',false);
	}
	if( $('#examenBio_name_'+id).val()==5){
		$("#resultat_bilan_renal").toggle(false);
		//$('#bilan_renal').attr('readonly',false);
	}
	if( $('#examenBio_name_'+id).val()==6){
		$("#resultat_bilan_inflammatoire").toggle(false);
		//$('#bilan_inflammatoire').attr('readonly',false); 
	}
	$("#SelectExamenBio_"+id+" option[value='']").attr('selected','selected');
	$("#noteExamenBio_"+id+" input").val("");
}

//CHARGEMENT DES ELEMENTS SELECTIONNES POUR LA MODIFICATION
//CHARGEMENT DES ELEMENTS SELECTIONNES POUR LA MODIFICATION
//CHARGEMENT DES ELEMENTS SELECTIONNES POUR LA MODIFICATION
function desactiverResutatsBio () {
$(function(){
	//ON CACHE TOUT
//	$('#groupe_sanguin').toggle(false); 
//	$('#hemogramme_sanguin').toggle(false);
//	$('#bilan_hemolyse').toggle(false);
//	$('#bilan_hepatique').toggle(false); 
//	$('#bilan_renal').toggle(false);
//	$('#bilan_inflammatoire').toggle(false);
	
	
//	$('#groupe_sanguin').attr('readonly',false); 
//	$('#hemogramme_sanguin').attr('readonly',false); 
//	$('#bilan_hemolyse').attr('readonly',false); 
//	$('#bilan_hepatique').attr('readonly',false); 
//	$('#bilan_renal').attr('readonly',false); 
//	$('#bilan_inflammatoire').attr('readonly',false); 
});
}



function chargementModificationBio (index , element , note) { 
	$("#SelectExamenBio_"+(index+1)+" option[value='"+element+"']").attr('selected','selected'); 
	$("#noteExamenBio_"+(index+1)+" input").val(note);
	
	 
		if(element == 1) {
			setTimeout(function(){
				$('#resultat_groupe_sanguin').toggle(true);
			},5000);
			  
		} 
		if(element == 2) {
			setTimeout(function(){
				$('#resultat_hemogramme_sanguin').toggle(true);
			},5000);
			 
			}  
		if(element == 5) {
					setTimeout(function(){
						$('#resultat_bilan_hemolyse').toggle(true); 
					},5000);
				 	} 
		if(element == 3) {
						setTimeout(function(){
							$('#resultat_bilan_hepatique').toggle(true);
						},5000); 
			}  
		if(element == 4) {
						setTimeout(function(){
							$('#resultat_bilan_renal').toggle(true);
						},5000); 
						} 
		if(element == 6) {
			setTimeout(function(){
				$('#resultat_bilan_inflammatoire').toggle(true); 
			},5000); 
			}
 
}

var base_url = window.location.toString();
var tabUrl = base_url.split("public");

//VALIDATION VALIDATION VALIDATION
//********************* EXAMEN MORPHOLOGIQUE *****************************
//********************* EXAMEN MORPHOLOGIQUE *****************************
//********************* EXAMEN MORPHOLOGIQUE *****************************

function ValiderDemandeExamenBio(){

		
$(function(){
	
	
var i=1;

	if( $('#examenBio_name_'+i).val()=="groupe sanguin"){
		 
	}
	

	//Au debut on affiche pas le bouton modifier
	//$("#bouton_ExamenBio_modifier_demande").toggle(false);
	//Au debut on affiche le bouton valider
	//$("#bouton_ExamenBio_valider_demande").toggle(true);
	
//	$("#demandeExamenBio").click(function(){ 
//		//RECUPERATION DES DONNEES DU TABLEAU
//		var id_cons = $('#id_cons').val();
//		var examensBio = [];
//		var notesBio = [];
//		for(var i = 1, j = 1; i <= nbListeExamenBio(); i++ ){
//			if($('#examenBio_name_'+i).val()) {
//				examensBio[j] = $('#examenBio_name_'+i).val();
//				notesBio[j] = $('#noteExamenBio_'+i+' input').val();
//				j++;
//			
//			}
//		}
//		
//		
//		
//		$.ajax({
//	        type: 'POST',
//	        url: tabUrl[0]+'public/chururgie/demande-examen-biologique',
//	        data: {'id_cons':id_cons, 'examensBio': examensBio, 'notesBio':notesBio},
//	        success: function(data) {
//	        	
//	        	//ON CACHE TOUT
//	        	$('#groupe_sanguin').toggle(true); 
//        		$('#hemogramme_sanguin').toggle(true);
//        		$('#bilan_hemolyse').toggle(true);
//        		$('#bilan_hepatique').toggle(true); 
//        		$('#bilan_renal').toggle(true);
//	        	$('#bilan_inflammatoire').toggle(true);
//        		
//	        	//ON AFFICHE UNIQUEMENT CEUX AYANT ETE DEMANDE
//	        	for(var k = 1; k<=nbListeExamenBio(); k++){
//	        		if(examensBio[k] == 1){ $('#groupe_sanguin').toggle(true); } 
//	        		if(examensBio[k] == 2){ $('#hemogramme_sanguin').toggle(true); } 
//	        		if(examensBio[k] == 5){ $('#bilan_hemolyse').toggle(true); } 
//	        		if(examensBio[k] == 3){ $('#bilan_hepatique').toggle(true); } 
//	        		if(examensBio[k] == 4){ $('#bilan_renal').toggle(true); } 
//	        		if(examensBio[k] == 6){ $('#bilan_inflammatoire').toggle(true); } 
//	        	}
//	        	
//	        	
//	            for(var i = 1; i <= nbListeExamenBio(); i++ ){
//	    			//$('#examenBio_name_'+i).attr('disabled',true);
//	    			//$('#examenBio_name_'+i).css({'background':'#f8f8f8'});
//	    			//$("#noteExamenBio_"+i+" input").attr('disabled',true); 
//	    			//$("#noteExamenBio_"+i+" input").css({'background':'#f8f8f8'});
//	    		}
//	    		//$("#controls_examenBio div").toggle(false);
//	    		//$("#iconeExamenBio_supp_vider a img").toggle(false);
//	    		//$("#bouton_ExamenBio_modifier_demande").toggle(true);
//	    	//	$("#bouton_ExamenBio_valider_demande").toggle(false);
//	    		return false;
//	      },
//	      error:function(e){console.log(e);alert("Une erreur interne est survenue!");},
//	      dataType: "html"
//		});
//		return false;
//	});
	
//	$("#bouton_ExamenBio_modifier_demande").click(function(){
//		for(var i = 1; i <= nbListeExamenBio(); i++ ){
//			//$('#examenBio_name_'+i).attr('disabled',false); 
//			$('#examenBio_name_'+i).css({'background':'white'});
//			//$("#noteExamenBio_"+i+" input").attr('disabled',false); 
//			$("#noteExamenBio_"+i+" input").css({'background':'white'});
//		}
//		$("#controls_examenBio div").toggle(true);
//		if(nbListeExamenBio() == 1){
//			$("#supprimer_examenBio").toggle(false);
//		}
//		$("#iconeExamenBio_supp_vider a img").toggle(true);
//		$("#bouton_ExamenBio_modifier_demande").toggle(false);
//		$("#bouton_ExamenBio_valider_demande").toggle(true);
//		return false;
//	});
	
//	$("#demandeExamenBioMorpho").click(function(){
//		for(var i = 1; i <= nbListeExamenBio(); i++ ){
//			//$('#examenBio_name_'+i).attr('disabled',false);
//			$('#examenBio_name_'+i).css({'background':'white'});
//			//$("#noteExamenBio_"+i+" input").attr('disabled',false);
//			$("#noteExamenBio_"+i+" input").css({'background':'white'});
//		}
//		$("#controls_examenBio div").toggle(true);
//		if(nbListeExamenBio() == 1){
//			$("#supprimer_examenBio").toggle(false);
//		}
//		$("#iconeExamenBio_supp_vider a img").toggle(true);
//		$("#bouton_ExamenBio_modifier_demande").toggle(false);
//		$("#bouton_ExamenBio_valider_demande").toggle(true);
//		return true;
//	});
	
});
}

