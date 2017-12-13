function cacherTouteDonnee(){
 $(function(){
	$('#donnee1').toggle(false);
	$('#donnee2').toggle(false);
	$('#donnee3').toggle(false);
	$('#donnee4').toggle(false);
	$('#donnee5').toggle(false);
	$('#donnee6').toggle(false);
	$('#donnee7').toggle(false);
	$('#donnee8').toggle(false);
	$('#donnee9').toggle(false);
	$('#donnee10').toggle(false);
	
 });
}
var nbChampDonnee;
function afficher(nbexamen_donnee) {
 $(function(){
	for(var i = 1; i<=nbexamen_donnee ; i++){ 
		$('#donnee'+i).toggle(true);
	}
 });
 nbChampDonnee = nbexamen_donnee;

 if(nbChampDonnee == 10){
		$('#ajouter_donnee_img').toggle(false);
	}
 
 if(nbChampDonnee == 1){
		$('#supprimer_donnee_img').toggle(false);
		$(".supprimerDonnee1" ).replaceWith(
				"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
			);
	}
 ajouterDonnee();
 supprimerDonnee();
 supprimerLeDonnee1();
}

function ajouterDonnee(){ 
	$('#ajouter_donnee_img').click(function(){ 
		nbChampDonnee++;
		$('#donnee'+(nbChampDonnee)).toggle(true);
		
		if(nbChampDonnee == 10){
			$('#ajouter_donnee_img').toggle(false);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(true);
			$(".supprimerDonnee" ).replaceWith(
					"<img class='supprimerDonnee1' style='cursor: pointer;' src='../images/images/sup.png' title='supprimer' />"
				);
			supprimerLeDonnee1();
		}
	});
	if(nbChampDonnee == 10){
		$('#ajouter_donnee_img').toggle(false);
	}
}

function supprimerDonnee(){ 
	$('#supprimer_donnee_img').click(function(){ 
		$("#examen_donnee"+nbChampDonnee).val('');
		$('#donnee'+nbChampDonnee).toggle(false);
		nbChampDonnee--;
		if(nbChampDonnee == 1){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		if(nbChampDonnee == 9){
			$('#ajouter_donnee_img').toggle(true);
		}
	});
}

function supprimerLeDonnee1(){
	$(".supprimerDonnee1").click(function(){ 
		for(var i=1; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 10){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
}

function supprimerUneDonnee(){
	$(".supprimerDonnee2").click(function(){ 
		for(var i=2; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 5){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	
	$(".supprimerDonnee3").click(function(){ 
		for(var i=3; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 5){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	
	$(".supprimerDonnee4").click(function(){ 
		for(var i=4; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 5){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	
	$(".supprimerDonnee5").click(function(){ 
		for(var i=5; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 5){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	
	
	
	$(".supprimerDonnee6").click(function(){ 
		for(var i=6; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 6){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	$(".supprimerDonnee7").click(function(){ 
		for(var i=7; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 7){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	
	$(".supprimerDonnee8").click(function(){ 
		for(var i=5; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 8){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	
	
	$(".supprimerDonnee9").click(function(){ 
		for(var i=5; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 9){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	
	$(".supprimerDonnee10").click(function(){ 
		for(var i=5; i<nbChampDonnee; i++){
			$("#examen_donnee"+i).val( $("#examen_donnee"+(i+1)).val() );
		}
		$("#examen_donnee"+i).val('');
		$('#donnee'+i).toggle(false);
		if(nbChampDonnee == 10){
			$('#ajouter_donnee_img').toggle(true);
		}
		if(nbChampDonnee == 2){
			$('#supprimer_donnee_img').toggle(false);
			$(".supprimerDonnee1" ).replaceWith(
					"<img class='supprimerDonnee' src='../images/images/sup2.png' />"
				);
		}
		nbChampDonnee--;
		return false;
	});
	
}


//********************* examen_donnee *****************************
//********************* examen_donnee *****************************
$(function(){
	var donnee1 = $("#examen_donnee1");
	var donnee2 = $("#examen_donnee2");
	var donnee3 = $("#examen_donnee3");
	var donnee4 = $("#examen_donnee4");
	var donnee5 = $("#examen_donnee5");
	var donnee6 = $("#examen_donnee6");
	var donnee7 = $("#examen_donnee7");
	var donnee8 = $("#examen_donnee8");
	var donnee9 = $("#examen_donnee9");
	var donnee10 = $("#examen_donnee10")
	
	//Au debut on affiche pas le bouton modifier
//	$("#bouton_donnee_modifier").toggle(false);
	//Au debut on affiche le bouton valider
//	$("#bouton_donnee_valider").toggle(true);
	
	//Au debut on desactive tous les champs
	donnee1.attr( 'readonly', false);
	donnee2.attr( 'readonly', false);
	donnee3.attr( 'readonly', false);
	donnee4.attr( 'readonly', false);
	donnee5.attr( 'readonly', false);
	donnee6.attr( 'readonly', false);
	donnee7.attr( 'readonly', false);
	donnee8.attr( 'readonly', false);
	donnee9.attr( 'readonly', false);
	donnee10.attr( 'readonly', false);
	
//	$("#bouton_donnee_valider").click(function(){
//		donnee1.attr( 'readonly', true).css({'background':'#f8f8f8'});
//		donnee2.attr( 'readonly', true).css({'background':'#f8f8f8'});
//		donnee3.attr( 'readonly', true).css({'background':'#f8f8f8'});
//		donnee4.attr( 'readonly', true).css({'background':'#f8f8f8'});
//		donnee5.attr( 'readonly', true).css({'background':'#f8f8f8'});
//		$("#bouton_donnee_modifier").toggle(true);
//		$("#bouton_donnee_valider").toggle(false);
//		
//		$('#ajouter_donnee_img').toggle(false);
//		$('#supprimer_donnee_img').toggle(false);
//		
//		$('.supprimerDonnee1, .supprimerDonnee2, .supprimerDonnee3, .supprimerDonnee4, .supprimerDonnee5').toggle(false);
//		return false;
//	});
	
//	$("#bouton_donnee_modifier").click(function(){
//		donnee1.attr( 'readonly', false).css({'background':'#fff'});
//		donnee2.attr( 'readonly', false).css({'background':'#fff'});
//		donnee3.attr( 'readonly', false).css({'background':'#fff'});
//		donnee4.attr( 'readonly', false).css({'background':'#fff'});
//		donnee5.attr( 'readonly', false).css({'background':'#fff'});
//		$("#bouton_donnee_modifier").toggle(false);
//		$("#bouton_donnee_valider").toggle(true);
//		
//		if(nbChampDonnee != 5) { $('#ajouter_donnee_img').toggle(true); }
//		if(nbChampDonnee != 1) { $('#supprimer_donnee_img').toggle(true); }
//		
//		$('.supprimerDonnee1, .supprimerDonnee2, .supprimerDonnee3, .supprimerDonnee4, .supprimerDonnee5').toggle(true);
//		return false;
//	});
	
});

//$(function(){
//	var diagnostic1 = $("#diagnostic1");
//	var diagnostic2 = $("#diagnostic2");
//	var diagnostic3 = $("#diagnostic3");
//	var diagnostic4 = $("#diagnostic4");
//	
//	//Au debut on affiche pas le bouton modifier
//	$("#bouton_diagnostic_modifier").toggle(false);
//	//Au debut on affiche le bouton valider
//	$("#bouton_diagnostic_valider").toggle(true);
//	
//	//Au debut on desactive tous les champs
//	diagnostic1.attr( 'readonly', false); 
//	diagnostic2.attr( 'readonly', false);
//	diagnostic3.attr( 'readonly', false);
//	diagnostic4.attr( 'readonly', false);
//	
//	$("#bouton_diagnostic_valider").click(function(){
//		diagnostic1.attr( 'readonly', true).css({'background':'#f8f8f8'});;
//		diagnostic2.attr( 'readonly', true).css({'background':'#f8f8f8'});;
//		diagnostic3.attr( 'readonly', true).css({'background':'#f8f8f8'});;
//		diagnostic4.attr( 'readonly', true).css({'background':'#f8f8f8'});;
//		$("#bouton_diagnostic_modifier").toggle(true);
//		$("#bouton_diagnostic_valider").toggle(false);
//		
//		$('#ajouter_donnee_img').toggle(false);
//		$('#supprimer_donnee_img').toggle(false);
//		
//		$('.supprimerDonnee1, .supprimerDonnee2, .supprimerDonnee3, .supprimerDonnee4').toggle(false);
//		return false;
//	});
//	
//	$("#bouton_diagnostic_modifier").click(function(){
//		diagnostic1.attr( 'readonly', false).css({'background':'#fff'});;
//		diagnostic2.attr( 'readonly', false).css({'background':'#fff'});;
//		diagnostic3.attr( 'readonly', false).css({'background':'#fff'});;
//		diagnostic4.attr( 'readonly', false).css({'background':'#fff'});;
//		$("#bouton_diagnostic_modifier").toggle(false);
//		$("#bouton_diagnostic_valider").toggle(true);
//		
//		if(nbChampDonnee != 5) { $('#ajouter_donnee_img').toggle(true); }
//		if(nbChampDonnee != 1) { $('#supprimer_donnee_img').toggle(true); }
//		
//		$('.supprimerDonnee1, .supprimerDonnee2, .supprimerDonnee3, .supprimerDonnee4').toggle(true);
//		return false;
//	});
//	
//});
