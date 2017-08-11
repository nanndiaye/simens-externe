<?php
return array(
    'acl' => array(
    		
        'roles' => array(
        		'guest'   => null,
        		'infirmier' => 'guest',
        		'laborantin' => 'guest',
        		'admin' => 'guest',
        		'radiologie' => 'guest',
        		'anesthesie' => 'guest',
        		'major' => 'guest',
        		'facturation' => 'guest',
        		'etat_civil' => 'guest',
        		'archivage' => 'guest',
        		
        		//***Polyclinique
        		//***Polyclinique
        		'cardiologue' => 'guest',
        		'gynecologue' => 'guest',
        		'pediatre' => 'guest',
        		'psychiatre' => 'guest',
        		'pneumologue' => 'guest',
        		'orl' => 'guest',
        		'kinesiterapeute' => 'guest',
        		'sage_femme' => 'guest',
        		'secretaire' => 'guest',
        		//***************
        		//***************

        		
        		'medecin'     => 'guest',
        		'superAdmin'  => 'medecin',
        		
        		'infirmier-tri' => 'guest',
        		'infirmier-service' => 'guest',
        		
        ),
    		

    		'resources' => array(
    		
    				'allow' => array(
    						
    						/***
    						 * AdminController
    						 */
    						
    						'Admin\Controller\Admin' => array(
    								'login' => 'guest',
    								'logout' => 'guest',
    								'bienvenue' => 'guest',
    								'modifier-password' => 'guest',
    								'verifier-password' => 'guest',
    								'mise-a-jour-user-password' => 'guest',
    								
    								'utilisateurs' => 'superAdmin',
    								'liste-utilisateurs-ajax' => 'superAdmin',
    								'modifier-utilisateur' => 'superAdmin',
    								'liste-agent-personnel-ajax' => 'superAdmin',
    								'visualisation' => 'superAdmin',
    								'nouvel-utilisateur' => 'superAdmin',
    								'verifier-username' => 'superAdmin',

    								'parametrages' => 'superAdmin',
    								'gestion-des-hopitaux' => 'superAdmin',
    								'liste-hopitaux-ajax' => 'superAdmin',
    								'get-departements' => 'superAdmin',
    								'ajouter-hopital' => 'superAdmin',
    								'get-infos-hopital' => 'superAdmin',
    								'get-infos-modification-hopital' => 'superAdmin',
    								
    								'gestion-des-batiments' => 'superAdmin',
    								'gestion-des-services' => 'superAdmin',
    								'liste-services-ajax' => 'superAdmin',
    								'get-infos-service' => 'superAdmin',
    								'get-infos-modification-service' => 'superAdmin',
    								'ajouter-service' => 'superAdmin',
    								'supprimer-service' => 'superAdmin',
    								
    								'gestion-des-actes' => 'superAdmin',
    								'liste-actes-ajax' => 'superAdmin',
    								'get-infos-acte' => 'superAdmin',
    								'get-infos-modification-acte' => 'superAdmin',
    								'ajouter-acte' => 'superAdmin',
    								'supprimer-acte'  => 'superAdmin',
    								
    						),
    						
    						
    						
    						
    						
    						/***
    						 * PersonnelController
    						 */
    						
    						'Personnel\Controller\Personnel' => array(
    								'liste-personnel' => array('admin','superAdmin'),
    								'liste-personnel-ajax' => array('admin','superAdmin'),
    								'info-personnel' => array('admin','superAdmin'),
    								'supprimer' => array('admin','superAdmin'),
    								'modifier-dossier' => array('admin','superAdmin'),
    								'dossier-personnel' => array('admin','superAdmin'),
    								
    								'transfert' => array('admin','superAdmin'),
    								'liste-personnel-transfert-ajax' => array('admin','superAdmin'),
    								'popup-agent-personnel' => array('admin','superAdmin'),
    								'vue-agent-personnel' => array('admin','superAdmin'),
    								'services' => array('admin','superAdmin'),
    								
    								'liste-transfert' => array('admin','superAdmin'),
    								'liste-transfert-ajax' => array('admin','superAdmin'),
    								'supprimer-transfert' => array('admin','superAdmin'),
    								
    								'intervention' => array('admin','superAdmin'),
    								'liste-personnel-intervention-ajax' => array('admin','superAdmin'),
    								'liste-intervention' => array('admin','superAdmin'),
    								'liste-intervention-ajax' => array('admin','superAdmin'),
    								'supprimer-transfert' => array('admin','superAdmin'),
    								'info-personnel-intervention' => array('admin','superAdmin'),
    								'vue-intervention-agent' => array('admin','superAdmin'),
    								'supprimer-intervention' => array('admin','superAdmin'),
    								'supprimer-une-intervention' => array('admin','superAdmin'),
    								'save-intervention' => array('admin','superAdmin'),
    								'modifier-intervention-agent' => array('admin','superAdmin'),
    						),
    						
    					    						
    						/*MODULE URGENCE */
    						/*MODULE URGENCE */
    						/*MODULE URGENCE */
    						
    						'Urgence\Controller\Urgence' => array(
    								/*Menu Dossier Patient*/
    								'liste-patient' => array('infirmier-service','medecin','infirmier-tri'),
    								'ajout-patient' => array('infirmier-service','medecin','infirmier-tri'),
    								'enregistrement-patient' => array('infirmier-service','medecin','infirmier-tri'),
    								'modifier' => array('infirmier-service','medecin','infirmier-tri'),
    								'info-patient' => array('infirmier-service','medecin','infirmier-tri'),
    								'liste-patient-ajax' => array('infirmier-service','medecin','infirmier-tri'),
    								'enregistrement-modification' => array('infirmier-service','medecin','infirmier-tri'),
    								'get-infos-vue-patient' => array('infirmier-service','medecin','infirmier-tri'),
    						
    								
    								/* Pour le RPU du patient*/
    								'rpu-patients' => array('infirmier-service','medecin'),
    								'liste-patient-encours-ajax'=> array('infirmier-service','medecin'),
    						
    								/*MENU ADMISSION*/
    								/*Inf-tri && Inf-service*/
    								'admission' => array('infirmier-service','medecin','infirmier-tri'),
    								'enregistrement-admission-patient' => array('infirmier-service','medecin','infirmier-tri'),
     								'liste-admission-ajax' => array('infirmier-service','medecin','infirmier-tri'),
    								'liste-patients-admis' => array('infirmier-service','medecin','infirmier-tri'),
    								'liste-patients-admis-ajax' => array('infirmier-service','medecin','infirmier-tri'),
    								'get-infos-modification-admission' => array('infirmier-service','medecin','infirmier-tri'),
     								'enregistrement-modification-admission' => array('infirmier-service','medecin','infirmier-tri'),
    								'suppression_admission_par_infirmiertri' => array('infirmier-service','medecin','infirmier-tri'),
    								
    								/*Inf-service*/
    								'liste-admission-infirmier-tri-ajax' => array('infirmier-service','medecin'),
    								'get-infos-admission-par-infirmier-tri' => array('infirmier-service','medecin'),
    								'liste-patients-admis-infirmier-service-ajax' => array('infirmier-service','medecin'),
    								'get-nb-patient-admis-non-vu' => array('infirmier-service','medecin'),
    								'liste-lits' => array('infirmier-service','medecin'),
    								
    						),
    						
    						
    						
    						
    						/*MODULE Chururgie*/
    						/*MODULE Chururgie*/
    						/*MODULE Chururgie */
    						
    						'Chururgie\Controller\Chururgie' => array(
    								/*Menu Dossier Patient*/
    								'liste-patient' => array('secretaire','medecin'),
    								'ajouter'=> array('secretaire','medecin'),
    								'enregistrement'=> array('secretaire','medecin'),
    								'modifier' => array('secretaire','medecin'),
    								'info-patient' => array('secretaire','medecin'),
    								'liste-patient-ajax' => array('secretaire','medecin'),
    								'enregistrement-modification' => array('secretaire','medecin'),
    								'get-infos-vue-patient'=> array('secretaire','medecin'),
    						
    					
    						
    								/*MENU MEDECIN GENERALE*/
    								'enregistrer-admission'=> array('secretaire','medecin'),
    								'admission' => array('secretaire','medecin'),
    								'liste-patients-admis'	=> array('secretaire','medecin'),
    								'liste-admission-ajax'	=> array('secretaire','medecin'),	
    								'consultation-medecin' => array('medecin'),
    								'complement-consultation'=> array('medecin'),
    								'impression-Pdf'=> array('medecin'),
    								'update-complement-consultation'=> array('medecin'),
    						         'visualisation-consultation'=> array('medecin'),
    						          'espace-recherche-med'=>'medecin',
    						),
    						
    						/***
    						 * ConsultationController
    						*/
    						
    						'Consultation\Controller\Consultation' => array(
    						
    								//============ MEDECIN =========================
    								'liste-patients-admis' => 'medecin',
    								'liste-patients-admis-infirmier-service-ajax' => 'medecin',
    								'get-infos-modification-admission' => 'medecin',
    								'liste-lits' => 'medecin',
    								'enregistrement-donnees-consultation' => 'medecin',
    								'get-nb-patient-admis-non-vu' => 'medecin',
    								'liste-patients-consultes' => 'medecin',
    								'liste-patients-admis-infirmier-service-historique-ajax' => 'medecin',
    						
    						),
    				),
    		),
    )
);