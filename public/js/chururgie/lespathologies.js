function chargerPathologies(myArrayOrgane, myArrayClassePathologie, myArrayTypePathologie) {

    $(function () {
        $("LesPathologies khass input").autocomplete({
            source: myArrayOrgane
        });

        $("LesPathologies khassClassePathologie input").autocomplete({
            source: myArrayClassePathologie
        });

        $("LesPathologies khassTypePathologie input").autocomplete({
            source: myArrayTypePathologie
        });
        
    });

}

function creerLalistePathologie($ListeOrgane, $ListeClassePathologie, $ListeTypePathologie) {

    var index = $("LesPathologies").length;
    $liste = "<div id='Pathologie_" + (index + 1) + "'>" +
            "<LesPathologies>" +
            "<table class='table table-bordered' style='margin-bottom: 0px; width: 100%;'>" +
            "<tr style='width: 100%;'>" +
           
            "<th id='Selectorgane_" + (index + 1) + "' style='width: 30%;'>";
    $liste += "<khass> <input style='width: 100%; margin-top: 3px; margin-bottom: 0px; font-size: 13px; height: 30px; font-size: 15px; padding-left: 10px;' id='pathologie_0" + (index + 1) + "' name='pathologie_0" + (index + 1) + "' type='text' > </khass>";
    $liste += "</th>" +
            "<th id='selectClassePathologie_" + (index + 1) + "' style='width: 30%;'  >" +
            "<khassClassePathologie><input type='text' id='classepathologie" + (index + 1) + "' name='classepathologie" + (index + 1) + "' style='width: 100%; margin-top: 3px; height: 30px; margin-bottom: 0px; font-size: 15px; padding-left: 10px;' > </khassClassePathologie>" +
            "</th >" +
            "<th id='notePathologie2_" + (index + 1) + "' style='width: 30%;'  >" +
            "<khassTypePathologie><input type='text' id='typepathologie" + (index + 1) + "' name='typepathologie" + (index + 1) + "' style='float: left; width: 100%; margin-top: 3px; height: 30px; margin-bottom: 0px; font-size: 15px; padding-left: 10px;' > </khassTypePathologie>" +
            "</th >" +
            "<th id='iconePathologie_supp_vider' style='width: 9%;'  >" +
            "<a id='supprimer_pathologie_selectionne_" + (index + 1) + "'  style='width:50%;' >" +
            "<img class='supprimerPathologie' style='margin-left: 5px; margin-top: 10px; cursor: pointer;' src='../images/images/sup.png' title='supprimer' />" +
            "</a>" +
            "<a id='vider_Pathologie_selectionne_" + (index + 1) + "'  style='width:50%;' >" +
            "<img class='viderPathologie' style='margin-left: 15px; margin-top: 10px; cursor: pointer;' src='../images_icons/gomme.png' title='vider' />" +
            "</a>" +
            "</th >" +
            "</tr>" +
            "</table>" +
            "</LesPathologies>" +
            "</div>" +
            "<script>" +
            "$('#supprimer_pathologie_selectionne_" + (index + 1) + "').click(function(){ " +
            "supprimer_pathologie_selectionne(" + (index + 1) + "); });" +
            "$('#vider_Pathologie_selectionne_" + (index + 1) + "').click(function(){ " +
            "vider_Pathologie_selectionne(" + (index + 1) + "); });" +
            "</script>";

    //AJOUTER ELEMENT SUIVANT
    $("#Pathologie_" + index).after($liste);

    //CACHER L'ICONE AJOUT QUAND ON A CINQ LISTES
    if ((index + 1) == 6) {
        $("#ajouter_pathologie").toggle(false);
    }

    //AFFICHER L'ICONE SUPPRIMER QUAND ON A DEUX LISTES ET PLUS
    if ((index + 1) == 2) {
        $("#supprimer_pathologie").toggle(true);
    }

    //CHARGEMENT DES AUTO-COMPLETIONS
    chargerPathologies($ListeOrgane, $ListeClassePathologie, $ListeTypePathologie);

  
}

//NOMBRE DE LISTE AFFICHEES
function nbListePathologies() {
    return $("LesPathologies").length;
}


//SUPPRIMER LE DERNIER ELEMENT
$(function () {
    //Au d�but on cache la suppression
    $("#supprimer_pathologie").click(function () {
        //ON PEUT SUPPRIMER QUAND C'EST PLUS DE DEUX LISTE
        if (nbListePathologies() > 1) {
            $("#Pathologie_" + nbListePathologies()).remove();
        }
        //ON CACHE L'ICONE SUPPRIMER QUAND ON A UNE LIGNE
        if (nbListePathologies() == 1) {
            $("#supprimer_pathologie").toggle(false);
            $(".supprimerPathologie").replaceWith(
                    "<img class='supprimerPathologie' style='margin-left: 5px; margin-top: 10px;' src='../images/images/sup2.png' />"
                    );
        }
        //Afficher L'ICONE AJOUT QUAND ON A CINQ LIGNES
        if ((nbListePathologies() + 1) == 6) {
            $("#ajouter_pathologie").toggle(true);
        }
        Event.stopPropagation();
    });
});


//FONCTION INITIALISATION (Par d�faut)
function DefautPathologie(ListeOrgane, ListeClassePathologie, ListeTypePathologie, n) {
    var i = 0;
    for (i; i < n; i++) {
        creerLalistePathologie(ListeOrgane, ListeClassePathologie, ListeTypePathologie);
    }
    if (n == 1) {
        $(".supprimerPathologie").replaceWith(
                "<img class='supprimerPathologie' style='margin-left: 5px; margin-top: 10px;' src='../images/images/sup2.png' />"
                );
    }
    $('#ajouter_pathologie').click(function () {
    	
        creerLalistePathologie(ListeOrgane, ListeClassePathologie, ListeTypePathologie);
        if (nbListePathologies() == 2) {
            $(".supprimerPathologie").replaceWith(
                    "<img class='supprimerPathologie' style='margin-left: 5px; margin-top: 10px; cursor: pointer;' src='../images/images/sup.png' title='Supprimer' />"
                    );
        }
    });

    //AFFICHER L'ICONE SUPPRIMER QUAND ON A DEUX LISTES ET PLUS
    if (nbListePathologies() > 1) {
        $("#supprimer_pathologie").toggle(true);
    } else {
        $("#supprimer_pathologie").toggle(false);
    }
}

//SUPPRIMER ELEMENT SELECTIONNER
function supprimer_pathologie_selectionne(id) {

    for (var i = (id + 1); i <= nbListePathologies(); i++) {
        var element = $('#pathologie_0' + i).val();
        $("#pathologie_0" + (i - 1)).val(element);

        var note = $('#SelectClassePathologie' + i + ' input').val();
        $("#SelectClassePathologie" + (i - 1) + " input").val(note);

        var note2 = $('#notePathologie2_' + i + '  input').val();
        $("#notePathologie2_" + (i - 1) + "  input").val(note2);

        var note2 = $('#notePathologie2_' + i + ' khassTypePathologie input').val();
        $("#notePathologie2_" + (i - 1) + " khassTypePathologie input").val(note2);
    }

    if (nbListePathologies() <= 2 && id <= 2) {
        $(".supprimerPathologie").replaceWith(
                "<img class='supprimerPathologie' style='margin-left: 5px; margin-top: 10px;' src='../images/images/sup2.png' />"
                );
    }
    if (nbListePathologies() != 1) {
        $('#Pathologie_' + nbListePathologies()).remove();
    }
    if (nbListePathologies() == 1) {
        $("#supprimer_pathologie").toggle(false);
    }
    if ((nbListePathologies() + 1) == 6) {
        $("#ajouter_pathologie").toggle(true);
    }

}

//VIDER LES CHAMPS DE L'ELEMENT SELECTIONNER
function vider_Pathologie_selectionne(id) {
    $("#pathologie_0" + id).val("");
    $("#SelectClassePathologie" + id + " input").val("");
    $("#notePathologie2_" + id + " input").val("");
}


var base_url = window.location.toString();
var tabUrl = base_url.split("public");
//VALIDATION VALIDATION VALIDATION
//********************* EXAMEN MORPHOLOGIQUE *****************************
//********************* EXAMEN MORPHOLOGIQUE *****************************
//********************* EXAMEN MORPHOLOGIQUE *****************************

function ValiderPathologie() {
    $(function () {
        //Au debut on affiche pas le bouton modifier
        $("#bouton_Pathologie_modifier_demande").toggle(false);
        //Au debut on affiche le bouton valider
        $("#bouton_Pathologie_valider_demande").toggle(true);

        $("#bouton_Pathologie_valider_demande button").click(function () {
//		//RECUPERATION DES DONNEES DU TABLEAU
//		var id_cons = $('#id_cons').val();
//		var examensBio = [];
//		var notesBio = [];
//		for(var i = 1, j = 1; i <= nbListePathologies(); i++ ){
//			if($('#pathologie_0'+i).val()) {
//				examensBio[j] = $('#pathologie_0'+i).val();
//				notesBio[j] = $('#SelectClassePathologie'+i+' input').val();
//				j++;
//			}
//		}
//		
//		$.ajax({
//	        type: 'POST',
//	        url: tabUrl[0]+'public/consultation/demande-examen-biologique',
//	        data: {'id_cons':id_cons, 'examensBio': examensBio, 'notesBio':notesBio},
//	        success: function(data) {

            for (var i = 1; i <= nbListePathologies(); i++) {
            	
                $('#pathologie_0' + i).attr('disabled', true);
                $("#classepathologie" + i ).attr('disabled', true);
                $("#typepathologie" + i ).attr('disabled', true);
            }
            $("#controls_pathologie div").toggle(false);
            $("#iconePathologie_supp_vider a img").toggle(false);
            $("#bouton_Pathologie_modifier_demande").toggle(true);
            $("#bouton_Pathologie_valider_demande").toggle(false);
            $("#increm_decrem img").toggle(false);
            return false;
//	      },
//	      error:function(e){console.log(e);alert("Une erreur interne est survenue!");},
//	      dataType: "html"
//		});
//		return false;
        });

        $("#ordonnance").click(function () {
            for (var i = 1; i <= nbListePathologies(); i++) {
                $('#patholoie_0' + i).attr('disabled', false);
                $('#pathologie_0' + i).css({'background': 'white'});
                $("#SelectClassePathologie" + i + " input").attr('disabled', false);
                $("#SelectClassePathologie" + i + " input").css({'background': 'white'});
                $("#notePathologie2_" + i + " input").attr('disabled', false);
                $("#notePathologie2_" + i + " input").css({'background': 'white'});
            }
            setTimeout(function () {
                for (var i = 1; i <= nbListePathologies(); i++) {
                    $('#pathologie_0' + i).attr('disabled', true);
                    $('#pathologie_0' + i).css({'background': '#f8f8f8'});
                    $("#SelectClassePathologie" + i + " input").attr('disabled', true);
                    $("#SelectClassePathologie" + i + " input").css({'background': '#f8f8f8'});
                    $("#notePathologie2_" + i + " input").attr('disabled', true);
                    $("#notePathologie2_" + i + " input").css({'background': '#f8f8f8'});
                }
                $("#controls_pathologie div").toggle(false);
                $("#iconePathologie_supp_vider a img").toggle(false);
                $("#bouton_Pathologie_modifier_demande").toggle(true);
                $("#bouton_Pathologie_valider_demande").toggle(false);
                $("#increm_decrem img").toggle(false);
            }, 1500);
        });

        $("#bouton_Pathologie_modifier_demande").click(function () {
            for (var i = 1; i <= nbListePathologies(); i++) {
            	$('#pathologie_0' + i).attr('disabled', false);
                $("#classepathologie" + i ).attr('disabled', false);
                $("#typepathologie" + i ).attr('disabled', false);
            }
            $("#controls_pathologie div").toggle(true);
            if (nbListePathologies() == 1) {
                $("#supprimer_pathologie").toggle(false);
            }
            $("#iconePathologie_supp_vider a img").toggle(true);
            $("#bouton_Pathologie_modifier_demande").toggle(false);
            $("#bouton_Pathologie_valider_demande").toggle(true);
            $("#increm_decrem img").toggle(true);
            return false;
        });
    });
}
