<?php

namespace Urgence\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Urgence\Form\PatientForm;
use Urgence\Form\AdmissionForm;
use Urgence\View\Helper\DateHelper;
use Zend\Json\Json;

class UrgenceController extends AbstractActionController {
	protected $patientTable;
	protected $formPatient;
	protected $tarifConsultationTable;
	protected $consultationTable;
	protected $serviceTable;
	protected $admissionTable;
	protected $dateHelper;
	protected $motifAdmissionTable;
	
	public function getPatientTable() {
		if (! $this->patientTable) {
			$sm = $this->getServiceLocator ();
			$this->patientTable = $sm->get ( 'Urgence\Model\PatientTable' );
		}
		return $this->patientTable;
	}
	
	public function getAdmissionTable() {
		if (! $this->admissionTable) {
			$sm = $this->getServiceLocator ();
			$this->admissionTable = $sm->get ( 'Urgence\Model\AdmissionTable' );
		}
		return $this->admissionTable;
	}
	
	public function getTarifConsultationTable() {
		if (! $this->tarifConsultationTable) {
			$sm = $this->getServiceLocator ();
			$this->tarifConsultationTable = $sm->get ( 'Urgence\Model\TarifConsultationTable' );
		}
		return $this->tarifConsultationTable;
	}
	
	public function getConsultationTable() {
		if (! $this->consultationTable) {
			$sm = $this->getServiceLocator ();
			$this->consultationTable = $sm->get ( 'Urgence\Model\ConsultationTable' );
		}
		return $this->consultationTable;
	}
	
	public function getServiceTable() {
		if (! $this->serviceTable) {
			$sm = $this->getServiceLocator ();
			$this->serviceTable = $sm->get ( 'Urgence\Model\ServiceTable' );
		}
		return $this->serviceTable;
	}
	
	public function getMotifAdmissionTable() {
		if (! $this->motifAdmissionTable) {
			$sm = $this->getServiceLocator ();
			$this->motifAdmissionTable = $sm->get ( 'Urgence\Model\MotifAdmissionTable' );
		}
		return $this->motifAdmissionTable;
	}
	
	
	public function baseUrl() {
		$baseUrl = $_SERVER ['REQUEST_URI'];
		$tabURI = explode ( 'public', $baseUrl );
		return $tabURI [0];
	}
	
	public function baseUrlRacine() {
		$baseUrl = $_SERVER ['SCRIPT_FILENAME'];
		$tabURI = explode ( 'public', $baseUrl );
		return $tabURI[0];
	}
	
	//**************************************************************************************
	//**************************************************************************************
	//**************************************************************************************
	//**************************************************************************************
	/* ----- DOMAINE DE LA CREATION DU DOSSIER PATIENT ------- */
	/* ----- DOMAINE DE LA CREATION DU DOSSIER PATIENT ------- */
	//--------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------
	
	
	public function listePatientAction() {
		// $personne = $this->getPatientTable()->miseAJourAgePatient(4);
		$layout = $this->layout ();
		$layout->setTemplate ( 'layout/urgence' );
		$view = new ViewModel ();
		return $view;
	}
	
	/**
	 * Pour la creation du dossier patient
	 */
	public function enregistrementPatientAction() {
		$user = $this->layout ()->user;
		$id_employe = $user ['id_personne']; // L'utilisateur connect�
		                                    
		// CHARGEMENT DE LA PHOTO ET ENREGISTREMENT DES DONNEES
		if (isset ( $_POST ['terminer'] )) 		// si formulaire soumis
		{
			$Control = new DateHelper ();
			$form = new PatientForm ();
			$Patient = $this->getPatientTable ();
			$today = new \DateTime ( 'now' );
			$nomfile = $today->format ( 'dmy_His' );
			$date_enregistrement = $today->format ( 'Y-m-d H:i:s' );
			$fileBase64 = $this->params ()->fromPost ( 'fichier_tmp' );
			$fileBase64 = substr ( $fileBase64, 23 );
			
			if ($fileBase64) {
				$img = imagecreatefromstring ( base64_decode ( $fileBase64 ) );
			} else {
				$img = false;
			}
			
			$date_naissance = $this->params ()->fromPost ( 'DATE_NAISSANCE' );
			if ($date_naissance) {
				$date_naissance = $Control->convertDateInAnglais ( $this->params ()->fromPost ( 'DATE_NAISSANCE' ) );
			} else {
				$date_naissance = null;
			}
			
			$donnees = array (
					'LIEU_NAISSANCE' => $this->params ()->fromPost ( 'LIEU_NAISSANCE' ),
					'EMAIL' => $this->params ()->fromPost ( 'EMAIL' ),
					'NOM' => $this->params ()->fromPost ( 'NOM' ),
					'TELEPHONE' => $this->params ()->fromPost ( 'TELEPHONE' ),
					'NATIONALITE_ORIGINE' => $this->params ()->fromPost ( 'NATIONALITE_ORIGINE' ),
					'PRENOM' => $this->params ()->fromPost ( 'PRENOM' ),
					'PROFESSION' => $this->params ()->fromPost ( 'PROFESSION' ),
					'NATIONALITE_ACTUELLE' => $this->params ()->fromPost ( 'NATIONALITE_ACTUELLE' ),
					'DATE_NAISSANCE' => $date_naissance,
					'ADRESSE' => $this->params ()->fromPost ( 'ADRESSE' ),
					'SEXE' => $this->params ()->fromPost ( 'SEXE' ),
					'AGE' => $this->params ()->fromPost ( 'AGE' ) 
			);
			
			$sexe = 2;
			if($donnees['SEXE'] == 'Masculin'){ $sexe = 1; }
			
			//var_dump($donnees); exit();
			
			if ($img != false) {
				
				$donnees ['PHOTO'] = $nomfile;
				// ENREGISTREMENT DE LA PHOTO
				imagejpeg ( $img, $this->baseUrlRacine().'public/img/photos_patients/' . $nomfile . '.jpg' );
				// ENREGISTREMENT DES DONNEES
				$Patient->addPatientAvecNumeroDossier ( $donnees, $date_enregistrement, $id_employe, $sexe );
			} else {
				// On enregistre sans la photo
				$Patient->addPatientAvecNumeroDossier ( $donnees, $date_enregistrement, $id_employe, $sexe );
			}
			
			return $this->redirect ()->toRoute ( 'urgence', array (
					'action' => 'admission' 
			) );
		}
	}
	
	public function getForm() {
		if (! $this->formPatient) {
			$this->formPatient = new PatientForm ();
		}
		return $this->formPatient;
	}
	
	public function getPhoto($id) {
		$donneesPatient =  $this->getInfoPatient( $id );
	
		$nom = null;
		if($donneesPatient){$nom = $donneesPatient['PHOTO'];}
		if ($nom) {
			return $nom . '.jpg';
		} else {
			return 'identite.jpg';
		}
	}
	
	public function ajoutPatientAction() {
		$this->layout ()->setTemplate ( 'layout/urgence' );
		$form = $this->getForm ();
		// $form = new PatientForm ();
		$patientTable = $this->getPatientTable ();
		$form->get ( 'NATIONALITE_ORIGINE' )->setvalueOptions ( $patientTable->listeDeTousLesPays () );
		$form->get ( 'NATIONALITE_ACTUELLE' )->setvalueOptions ( $patientTable->listeDeTousLesPays () );
		$data = array (
				'NATIONALITE_ORIGINE' => 'Sénégal',
				'NATIONALITE_ACTUELLE' => 'Sénégal' 
		);
		
		$form->populateValues ( $data );
		
		return new ViewModel ( array (
				'form' => $form 
		) );
	}
	
	public function listePatientAjaxAction() {
		$output = $this->getPatientTable ()->getListePatient ();
		return $this->getResponse ()->setContent ( Json::encode ( $output, array (
				'enableJsonExprFinder' => true 
		) ) );
	}
	
	// modification donnees d'un patient
	public function modifierAction() {
		$control = new DateHelper ();
		$this->layout ()->setTemplate ( 'layout/urgence' );
		$id_patient = $this->params ()->fromRoute ( 'id_patient', 0 );
		//var_dump($id_patient);exit();
		$infoPatient = $this->getPatientTable ();
		try {
			$info = $infoPatient->getInfoPatient ( $id_patient );
		} catch ( \Exception $ex ) {
			return $this->redirect ()->toRoute ( 'urgence', array (
					'action' => 'liste-patient' 
			) );
		}
		$form = new PatientForm ();
		$form->get ( 'NATIONALITE_ORIGINE' )->setvalueOptions ( $infoPatient->listeDeTousLesPays () );
		$form->get ( 'NATIONALITE_ACTUELLE' )->setvalueOptions ( $infoPatient->listeDeTousLesPays () );
		
		$date_naissance = $info ['DATE_NAISSANCE'];
		if ($date_naissance) {
			$info ['DATE_NAISSANCE'] = $control->convertDate ( $info ['DATE_NAISSANCE'] );
		} else {
			$info ['DATE_NAISSANCE'] = null;
		}
		
		$form->populateValues ( $info );
		
		if (! $info ['PHOTO']) {
			$info ['PHOTO'] = "identite";
		}
		return array (
				'form' => $form,
				'photo' => $info ['PHOTO'] 
		);
	}
    
    //Enregistrement modification
	public function enregistrementModificationAction() {
	
		$user = $this->layout()->user;
		$id_employe = $user['id_personne']; //L'utilisateur connect�
	
		if (isset ( $_POST ['terminer'] ))
		{
			$Control = new DateHelper();
			$Patient = $this->getPatientTable ();
			$today = new \DateTime ( 'now' );
			$nomfile = $today->format ( 'dmy_His' );
			$date_modification = $today->format ( 'Y-m-d H:i:s' );
			$fileBase64 = $this->params ()->fromPost ( 'fichier_tmp' );
			$fileBase64 = substr ( $fileBase64, 23 );
	
			if($fileBase64){
				$img = imagecreatefromstring(base64_decode($fileBase64));
			}else {
				$img = false;
			}
	
			$date_naissance = $this->params ()->fromPost ( 'DATE_NAISSANCE' );
			if($date_naissance){ $date_naissance = $Control->convertDateInAnglais($this->params ()->fromPost ( 'DATE_NAISSANCE' )); }else{ $date_naissance = null;}
	
			$donnees = array(
					'LIEU_NAISSANCE' => $this->params ()->fromPost ( 'LIEU_NAISSANCE' ),
					'EMAIL' => $this->params ()->fromPost ( 'EMAIL' ),
					'NOM' => $this->params ()->fromPost ( 'NOM' ),
					'TELEPHONE' => $this->params ()->fromPost ( 'TELEPHONE' ),
					'NATIONALITE_ORIGINE' => $this->params ()->fromPost ( 'NATIONALITE_ORIGINE' ),
					'PRENOM' => $this->params ()->fromPost ( 'PRENOM' ),
					'PROFESSION' => $this->params ()->fromPost ( 'PROFESSION' ),
					'NATIONALITE_ACTUELLE' => $this->params ()->fromPost ( 'NATIONALITE_ACTUELLE' ),
					'DATE_NAISSANCE' => $date_naissance,
					'ADRESSE' => $this->params ()->fromPost ( 'ADRESSE' ),
					'SEXE' => $this->params ()->fromPost ( 'SEXE' ),
					'AGE' => $this->params ()->fromPost ( 'AGE' ),
			);
	
			$id_patient =  $this->params ()->fromPost ( 'ID_PERSONNE' );
			if ($img != false) {
	
				$lePatient = $Patient->getInfoPatient ( $id_patient );
				$ancienneImage = $lePatient['PHOTO'];
	
				if($ancienneImage) {
					unlink ( $this->baseUrlRacine().'public/img/photos_patients/' . $ancienneImage . '.jpg' );
				}
				imagejpeg ( $img, $this->baseUrlRacine().'public/img/photos_patients/' . $nomfile . '.jpg' );
	
				$donnees['PHOTO'] = $nomfile;
				$Patient->updatePatient ( $donnees , $id_patient, $date_modification, $id_employe);
					
			} else {
				$Patient->updatePatient($donnees, $id_patient, $date_modification, $id_employe);
				
			}
			return $this->redirect ()->toRoute ( 'urgence', array (
					'action' => 'liste-patient'
			) );
		}
	}

    //Afficher Information Patient
	public function infoPatientAction() {
		$this->layout ()->setTemplate ( 'layout/urgence' );
		$id_pat = $this->params ()->fromRoute ( 'id_patient', 0 );
	
		$patient = $this->getPatientTable ();
		$unPatient = $patient->getInfoPatient( $id_pat );
	
		return array (
				'lesdetails' => $unPatient,
				'image' => $patient->getPhoto ( $id_pat ),
				'id_patient' => $unPatient['ID_PERSONNE'],
				'numero_dossier' => $unPatient['NUMERO_DOSSIER'],
				'date_enregistrement' => $unPatient['DATE_ENREGISTREMENT']
		);
	}


	public function ajouterAction() {
		$this->layout ()->setTemplate ( 'layout/urgence' );
		$form = $this->getForm ();
		$patientTable = $this->getPatientTable();
		$form->get('NATIONALITE_ORIGINE')->setvalueOptions($patientTable->listeDeTousLesPays());
		$form->get('NATIONALITE_ACTUELLE')->setvalueOptions($patientTable->listeDeTousLesPays());
		$data = array('NATIONALITE_ORIGINE' => 'Sénégal', 'NATIONALITE_ACTUELLE' => 'Sénégal');
	
		$form->populateValues($data);
	
		return new ViewModel ( array (
				'form' => $form
		) );
	}

	public function getInfosVuePatientAction() {
		$id = ( int ) $this->params ()->fromPost ( 'id', 0 );
		//MISE A JOUR DE L'AGE DU PATIENT
		//MISE A JOUR DE L'AGE DU PATIENT
		//MISE A JOUR DE L'AGE DU PATIENT
		  //$personne = $this->getPatientTable()->miseAJourAgePatient($id);
		//*******************************
		//*******************************
		//*******************************
		$pat = $this->getPatientTable ();
		$unPatient = $pat->getInfoPatient ( $id );
		$photo = $pat->getPhoto ( $id );
		
		$date = $unPatient['DATE_NAISSANCE'];
		if($date){ $date = (new DateHelper())->convertDate ($date); }else{ $date = null;}
		
		$html = "<div style='float:left;' ><div id='photo' style='float:left; margin-right:20px; margin-bottom: 10px;'> <img  src='".$this->baseUrl()."public/img/photos_patients/" . $photo . "'  style='width:105px; height:105px;'></div>";
		$html .= "<div style='margin-left:6px;'> <div style='text-decoration:none; font-size:14px; float:left; padding-right: 7px; '>Age:</div>  <div style='font-weight:bold; font-size:19px; font-family: time new romans; color: green; font-weight: bold;'>" . $unPatient['AGE'] . " ans</div></div></div>";
		
		
		$html .= "<table>";
		
		$html .= "<tr>";
		$html .= "<td><a style='text-decoration:underline; font-size:12px;'>Nom:</a><br><p style='width:280px; font-weight:bold; font-size:17px;'>" . $unPatient['NOM'] . "</p></td>";
		$html .= "</tr><tr>";
		$html .= "<td><a style='text-decoration:underline; font-size:12px;'>Pr&eacute;nom:</a><br><p style='width:280px; font-weight:bold; font-size:17px;'>" . $unPatient['PRENOM'] . "</p></td>";
		$html .= "</tr><tr>";
		$html .= "<td><a style='text-decoration:underline; font-size:12px;'>Date de naissance:</a><br><p style='width:280px; font-weight:bold; font-size:17px;'>" . $date . "</p></td>";
		$html .= "</tr><tr>";
		$html .= "<td><a style='text-decoration:underline; font-size:12px;'>Adresse:</a><br><p style='width:280px; font-weight:bold; font-size:17px;'>" . $unPatient['ADRESSE'] . "</p></td>";
		$html .= "</tr><tr>";
		$html .= "<td><a style='text-decoration:underline; font-size:12px;'>T&eacute;l&eacute;phone:</a><br><p style='width:280px; font-weight:bold; font-size:17px;'>" . $unPatient['TELEPHONE'] . "</p></td>";
		$html .= "</tr>";
		
		$html .= "</table>";
		$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
		return $this->getResponse ()->setContent ( Json::encode ( $html ) );
		
	}
	
	//**************************************************************************************
	//**************************************************************************************
	//**************************************************************************************
	//**************************************************************************************
	   /* ----- DOMAINE DE LA GESTION DES ADMISSIONS DES PATIENTS ------- */
	   /* ----- DOMAINE DE LA GESTION DES ADMISSIONS DES PATIENTS ------- */
	//--------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------
	public function listeAdmissionAjaxAction() {
		$output = $this->getPatientTable ()->laListePatientsAjax();
		return $this->getResponse ()->setContent ( Json::encode ( $output, array (
				'enableJsonExprFinder' => true
		) ) );
	}
	
	public function listeAdmissionInfirmierTriAjaxAction() {
		$output = $this->getPatientTable ()->laListePatientsAdmisParInfimierTriAjax();
		return $this->getResponse ()->setContent ( Json::encode ( $output, array (
				'enableJsonExprFinder' => true
		) ) );
	}
	
	public function getNbPatientAdmisNonVuAction(){
		$nbPatientAdmisInfTriNonVu = $this->getPatientTable ()->nbPatientAdmisParInfirmierTri();
		
		$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
		return $this->getResponse ()->setContent ( Json::encode ( $nbPatientAdmisInfTriNonVu ) );
	}
	
	public function listeLitsAction()
	{
		$id_salle = (int)$this->params()->fromPost ('id_salle');
		$liste_select = "";
	    foreach($this->getPatientTable()->getListeLitsPourSalle($id_salle) as $listeLits){
	    	$liste_select.= "<option value=".$listeLits['Id_lit'].">".$listeLits['Numero_lit']."</option>";
	    }

	    $this->getResponse()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html' );
	    return $this->getResponse ()->setContent(Json::encode ( $liste_select ));
	}
	
    //Admission d' un patient aux urgences
	public function admissionAction() {
	
		$layout = $this->layout ();
		$layout->setTemplate ( 'layout/urgence' );		
		
		$nbPatientAdmisInfTriNonVu = $this->getPatientTable ()->nbPatientAdmisParInfirmierTri();
		
		//INSTANCIATION DU FORMULAIRE D'ADMISSION
		$formAdmission = new AdmissionForm ();
		
		$listeSalles = $this->getPatientTable ()->listeSalles();
		$formAdmission->get ( 'salle' )->setValueOptions ($listeSalles);
		
		//A REVOIR DANS LA PARTI AMELIORATION
		//A REVOIR DANS LA PARTI AMELIORATION
// 		$listeLitsParSalles = $this->getPatientTable ()->listeLitsParSalle();
// 		$liste_select = "";
// 		for($tS = 0 ; $tS < count($listeLitsParSalles[0]) ; $tS++){
// 			var_dump($listeLitsParSalles[1][$listeLitsParSalles[0][$tS]]); exit();
// 			for($i = 0 ; $i < count($listeLitsParSalles[1][$listeLitsParSalles[0][$tS]]) ; $i++){
// 				$liste_select.= "<option value=".$listeServices['Id_service'].">".$listeServices['Nom_service']."</option>";
// 			}
// 		}
// 		var_dump($listeLitsParSalles); exit();

		//Fin --- A REVOIR DANS LA PARTI AMELIORATION
		//Fin --- A REVOIR DANS LA PARTI AMELIORATION
		
		
		//var_dump($this->getPatientTable()->getListeLitsPourSalle(2)->current()); exit();
		
		
		if ($this->getRequest ()->isPost ()) {
				
			$today = new \DateTime ();
			$dateAujourdhui = $today->format( 'Y-m-d' );
				
			$id = ( int ) $this->params ()->fromPost ( 'id', 0 );
				
			//MISE A JOUR DE L'AGE DU PATIENT
			//MISE A JOUR DE L'AGE DU PATIENT
			//MISE A JOUR DE L'AGE DU PATIENT
			         //$personne = $this->getPatientTable()->miseAJourAgePatient($id);
			//*******************************
			//*******************************
			//*******************************
				
			$pat = $this->getPatientTable ();
				
			$unPatient = $pat->getInfoPatient( $id );
	
			$photo = $pat->getPhoto ( $id );
	
				
			$date = $unPatient['DATE_NAISSANCE'];
			if($date){ $date = (new DateHelper())->convertDate( $unPatient['DATE_NAISSANCE'] ); }else{ $date = null;}
	
			$html  = "<div style='width:100%; height: 190px;'>";
				
			$html .= "<div style='width: 18%; height: 190px; float:left;'>";
			$html .= "<div id='photo' style='float:left; margin-left:40px; margin-top:10px; margin-right:30px;'> <img style='width:105px; height:105px;' src='".$this->baseUrl()."public/img/photos_patients/" . $photo . "' ></div>";
			$html .= "<div style='margin-left:60px; margin-top: 150px;'> <div style='text-decoration:none; font-size:14px; float:left; padding-right: 7px; '>Age:</div>  <div style='font-weight:bold; font-size:19px; font-family: time new romans; color: green; font-weight: bold;'>" . $unPatient['AGE'] . " ans</div></div>";
			$html .= "</div>";
				
			$html .= "<div id='vuePatientAdmission' style='width: 70%; height: 190px; float:left;'>";
			$html .= "<table style='margin-top:0px; float:left; width: 100%;'>";
				
			$html .= "<tr style='width: 100%;'>";
			$html .= "<td style='width: 24%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nom:</a><br><div style='width: 150px; max-width: 160px; height:40px; overflow:auto; margin-bottom: 3px;'><p style='font-weight:bold; font-size:19px;'>" . $unPatient['NOM'] . "</p></div></td>";
			$html .= "<td style='width: 24%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Date de naissance:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $date . "</p></div></td>";
			$html .= "<td style='width: 23%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>T&eacute;l&eacute;phone:</a><br><div style='width: 95%; '><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['TELEPHONE'] . "</p></div></td>";
			$html .= "<td style='width: 29%; '></td>";
			
			$html .= "</tr><tr style='width: 100%;'>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Pr&eacute;nom:</a><br><div style='width: 95%; max-width: 180px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['PRENOM'] . " </p></div></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Lieu de naissance:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['LIEU_NAISSANCE'] . "</p></div></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nationalit&eacute; actuelle:</a><br><div style='width: 95%; max-width: 135px; overflow:auto; '><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['NATIONALITE_ACTUELLE']. "</p></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Email:</a><br><div style='width: 100%; max-width: 235px; height:40px; overflow:auto;'><p style='font-weight:bold; font-size:19px;'>" . $unPatient['EMAIL'] . "</p></div></td>";
			
			$html .= "</tr><tr style='width: 100%;'>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Sexe:</a><br><div style='width: 95%; max-width: 130px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['SEXE'] . "</p></div></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Adresse:</a><br><div style='width: 97%; max-width: 250px; height:50px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['ADRESSE'] . "</p></div></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Profession:</a><br><div style='width: 95%; max-width: 235px; height:40px; overflow:auto; '><p style=' font-weight:bold; font-size:19px;'>" .  $unPatient['PROFESSION'] . "</p></div></td>";
			
			$html .= "<td style='width: 30%; height: 50px;'>";
			$html .= "</td>";
			$html .= "</tr>";
			$html .= "</table>";
			$html .= "</div>";
				
			$html .= "<div style='width: 12%; height: 190px; float:left;'>";
			$html .= "<div style='color: white; opacity: 0.09; float:left; margin-right:10px; margin-left:5px; margin-top:5px;'> <img style='width:105px; height:105px;' src='".$this->baseUrl()."public/img/photos_patients/" . $photo . "'></div>";
			$html .= "<div style='margin-left: 5px; margin-top: 10px; margin-right:10px;'>  <div style='font-size:19px; font-family: time new romans; color: green; float:left; margin-top: 10px;'>" . $unPatient['NUMERO_DOSSIER'] . " </div></div>";
			$html .= "</div>";
				
			$html .= "</div>";
			
			$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
			return $this->getResponse ()->setContent ( Json::encode ( $html ) );
		}
		return array (
				'form' => $formAdmission,
				'nbPatientAdmisInfTriNonVu' => $nbPatientAdmisInfTriNonVu,
		);
	}
	
	//Verifier si un tableau est vide ou pas 
    function array_empty($array) {
    	$is_empty = true;
        foreach($array as $k) {
        	$is_empty = $is_empty && empty($k);
        }
        return $is_empty;
    }
    
	/**
	 * Admission du patient par l'infirmier de tri et l'infirmier de service
	 */
	public function enregistrementAdmissionPatientAction() {
	
		$this->layout ()->setTemplate ( 'layout/urgence' );
		$user = $this->layout()->user;
		$role = $user['role'];
		
		$today = new \DateTime ();
		$date = $today->format( 'Y-m-d' );
		$heure = $today->format( 'H:i:s' );
	
		$form = new AdmissionForm();
		$formData = $this->getRequest ()->getPost ();
		$form->setData ( $formData );
		
		$id_cons = $form->get ( "id_cons" )->getValue ();
		$id_patient = $this->params ()->fromPost( "id_patient" );
		$id_Infirmier = $user['id_employe'];
		
		$niveau = $this->params ()->fromPost( "niveau" );
		
		//Recuperation des donnees des motifs d'admission
		$donneesMotifAdmission	 = array(
				'motif_admission1' => trim($form->get ( 'motif_admission1' )->getValue ()),
				'motif_admission2' => trim($form->get ( 'motif_admission2' )->getValue ()),
				'motif_admission3' => trim($form->get ( 'motif_admission3' )->getValue ()),
				'motif_admission4' => trim($form->get ( 'motif_admission4' )->getValue ()),
				'motif_admission5' => trim($form->get ( 'motif_admission5' )->getValue ()),
		);
		
		//Recuperation des donnees des constantes
		$donneesConstantes = array(
				'POIDS' => (int)trim($form->get ( "poids" )->getValue ()),
				'TAILLE' => (int)trim($form->get ( "taille" )->getValue ()),
				'TEMPERATURE' => (int)trim($form->get ( "temperature" )->getValue ()),
				'PRESSION_ARTERIELLE' => trim( trim($form->get ( "tensionmaximale" )->getValue ()).' '.trim($form->get ( "tensionminimale" )->getValue ()) ),
				'POULS' => (int)trim($form->get ( "pouls" )->getValue ()),
				'FREQUENCE_RESPIRATOIRE' => (int)trim($form->get ( "frequence_respiratoire" )->getValue ()),
				'GLYCEMIE_CAPILLAIRE' => (int)trim($form->get ( "glycemie_capillaire" )->getValue ()),
		);
		
		//Recuperer les donnees sur les bandelettes urinaires
		//Recuperer les donnees sur les bandelettes urinaires
		$bandelettes = array(
				'albumine' => $this->params()->fromPost('albumine'),
				'sucre' => $this->params()->fromPost('sucre'),
				'corpscetonique' => $this->params()->fromPost('corpscetonique'),
				'croixalbumine' => $this->params()->fromPost('croixalbumine'),
				'croixsucre' => $this->params()->fromPost('croixsucre'),
				'croixcorpscetonique' => $this->params()->fromPost('croixcorpscetonique'),
		);

		//Insertion des donnees de l'infirmier de tri
		//Insertion des donnees de l'infirmier de tri
		//Insertion des donnees de l'infirmier de tri
		if($role == "infirmier-tri"){
			
			//Insertion de l'admission pour admettre le patient au niveau de l'infirmier de service
			//Insertion de l'admission pour admettre le patient au niveau de l'infirmier de service
			$donneesAdmission = array(
					'id_patient' => $id_patient,
					'id_infirmier_tri' => $id_Infirmier,
					'heure_infirmier_tri' =>  $heure,
					'date' => $date,
					'niveau' => $niveau,
			);
			$id_admission = $this->getAdmissionTable()->addAdmission($donneesAdmission);
			
			
			//Insertion des motifs de l'admission s'il y'en a
			//Insertion des motifs de l'admission s'il y'en a
			if(!$this->array_empty($donneesMotifAdmission)){
				$this->getMotifAdmissionTable ()->addMotifAdmission ( $form , $id_admission);
			}
			
			//Insertion des constantes s'il y'en a
			//Insertion des constantes s'il y'en a
			if(!$this->array_empty($donneesConstantes) || !$this->array_empty($bandelettes)){ 
				$donneesConstantes['ID_CONS']    = $id_cons;
				$donneesConstantes['ID_PATIENT'] = (int)$id_patient;
				$donneesConstantes['DATEONLY']   = $form->get ( "dateonly" )->getValue ();
				$donneesConstantes['HEURECONS']  = $form->get ( "heure_cons" )->getValue ();
				
				$this->getConsultationTable ()->addConsultation ($donneesConstantes); 
				$this->getConsultationTable ()->addConsultationUrgence($id_cons, $id_admission, $id_Infirmier);
				
				//mettre � jour les bandelettes urinaires
				$bandelettes['id_cons'] = $id_cons;
				$this->getConsultationTable ()->deleteBandelette($id_cons);
				$this->getConsultationTable ()->addBandelette($bandelettes);
			}
	
		}
	
		if($role == "infirmier-service"){
			$id_admission = $this->params ()->fromPost( "id_admission" );
			//Si c'est un patient d�j� admis par l'infirmier de tri
			//Si c'est un patient d�j� admis par l'infirmier de tri
			if($id_admission){
				
				//Validation de l'admission par l'infirmier de service
				//Validation de l'admission par l'infirmier de service
				$donneesAdmission = array(
						'id_infirmier_service' => $id_Infirmier,
						'heure_infirmier_service' =>  $heure,
						'niveau' => $niveau,
						'salle'  => trim($form->get ( "salle" )->getValue ()),
						'lit'    => trim($form->get ( "lit" )->getValue ()),
						'couloir' => trim($form->get ( "couloir" )->getValue ()),
				);
				
				$this->getAdmissionTable()->updateAdmission($donneesAdmission, $id_admission);
				
				//Insertion des motifs de l'admission s'il y'en a
				//Insertion des motifs de l'admission s'il y'en a
				$this->getMotifAdmissionTable ()->deleteMotifAdmission($id_admission);
				if(!$this->array_empty($donneesMotifAdmission)){
					$this->getMotifAdmissionTable ()->addMotifAdmission ( $form , $id_admission);
				}
					
				//Insertion des constantes s'il y'en a (est pass� par l'infirmier de tri)
				//Insertion des constantes s'il y'en a (est pass� par l'infirmier de tri)
				$consultation_urgence = $this->getConsultationTable ()->getConsultationUrgence($id_admission);
				if($consultation_urgence){
					$this->getConsultationTable ()->updateConsultationUrgence($donneesConstantes, $consultation_urgence['id_cons']);
					$this->getConsultationTable ()->miseajourConsultationUrgence($id_Infirmier, $consultation_urgence['id_cons']);
					
					//mettre � jour les bandelettes urinaires
					$bandelettes['id_cons'] = $consultation_urgence['id_cons'];
					$this->getConsultationTable ()->deleteBandelette($consultation_urgence['id_cons']);
					$this->getConsultationTable ()->addBandelette($bandelettes);
				}else{
					//Insertion des constantes s'il y'en a
					//Insertion des constantes s'il y'en a
					if(!$this->array_empty($donneesConstantes) || !$this->array_empty($bandelettes)){
						$donneesConstantes['ID_CONS']    = $id_cons;
						$donneesConstantes['ID_PATIENT'] = (int)$id_patient;
						$donneesConstantes['DATEONLY']   = $form->get ( "dateonly" )->getValue ();
						$donneesConstantes['HEURECONS']  = $form->get ( "heure_cons" )->getValue ();
							
						$this->getConsultationTable ()->addConsultation ($donneesConstantes);
						$this->getConsultationTable ()->addConsultationUrgenceInfirmierService ($id_cons, $id_admission, $id_Infirmier);
					
						//mettre � jour les bandelettes urinaires
						$bandelettes['id_cons'] = $id_cons;
						$this->getConsultationTable ()->deleteBandelette($id_cons);
						$this->getConsultationTable ()->addBandelette($bandelettes);
					}
				}
				
			}else{

				$donneesAdmission = array(
						'id_patient' => $id_patient,
						'id_infirmier_service' => $id_Infirmier,
						'heure_infirmier_service' =>  $heure,
						'date' => $date,
						'niveau' => $niveau,
						'salle'  => trim($form->get ( "salle" )->getValue ()),
						'lit'    => trim($form->get ( "lit" )->getValue ()),
						'couloir'    => trim($form->get ( "couloir" )->getValue ()),
				);
				
				$id_admission = $this->getAdmissionTable()->addAdmission($donneesAdmission);
					
				//Insertion des motifs de l'admission s'il y'en a
				//Insertion des motifs de l'admission s'il y'en a
				if(!$this->array_empty($donneesMotifAdmission)){
					$this->getMotifAdmissionTable ()->addMotifAdmission ( $form , $id_admission);
				}
					
				//Insertion des constantes s'il y'en a
				//Insertion des constantes s'il y'en a
				if(!$this->array_empty($donneesConstantes) || !$this->array_empty($bandelettes)){
					$donneesConstantes['ID_CONS']    = $id_cons;
					$donneesConstantes['ID_PATIENT'] = (int)$id_patient;
					$donneesConstantes['DATEONLY']   = $form->get ( "dateonly" )->getValue ();
					$donneesConstantes['HEURECONS']  = $form->get ( "heure_cons" )->getValue ();
				
					$this->getConsultationTable ()->addConsultation ($donneesConstantes);
					$this->getConsultationTable ()->addConsultationUrgenceInfirmierService($id_cons, $id_admission, $id_Infirmier);
				
					//mettre � jour les bandelettes urinaires
					$bandelettes['id_cons'] = $id_cons;
					$this->getConsultationTable ()->deleteBandelette($id_cons);
					$this->getConsultationTable ()->addBandelette($bandelettes);
				}
			}

		}
	
		return $this->redirect ()->toRoute ('urgence', array ('action' => 'liste-patients-admis' ));
	
	}
	
	public function listePatientsAdmisAjaxAction() {
		$output = $this->getPatientTable ()->getListePatientAdmis ();
		return $this->getResponse ()->setContent ( Json::encode ( $output, array (
				'enableJsonExprFinder' => true
		) ) );
	}
	
	public function listePatientsAdmisAction() {
		$this->layout ()->setTemplate ( 'layout/urgence' );
		
		//INSTANCIATION DU FORMULAIRE D'ADMISSION
		$formAdmission = new AdmissionForm ();
		$listeSalles = $this->getPatientTable ()->listeSalles();
		$formAdmission->get ( 'salle' )->setValueOptions ($listeSalles);
		
		//$bandelettes = $this->getConsultationTable ()->getBandelette('s-c-280517-175637');
		//var_dump($bandelettes); exit();
		
		
		return array (
				'form' => $formAdmission
		);
	}
	
	public function getListeDesLits($id_salle)
	{
		$liste_select = "";
		foreach($this->getPatientTable()->getListeLitsPourSalle($id_salle) as $listeLits){
			$liste_select.= "<option value=".$listeLits['Id_lit'].">".$listeLits['Numero_lit']."</option>";
		}
	
		return $liste_select;
	}

	public function getInfosModificationAdmissionAction() {

		$user = $this->layout()->user;
		$role = $user['role'];
		
		$today = new \DateTime ();
		$dateAujourdhui = $today->format( 'Y-m-d' );
		
		$id_patient = ( int ) $this->params ()->fromPost ( 'id_patient', 0 );
		$id_admission = ( int ) $this->params ()->fromPost ( 'id_admission', 0 );
		
		//MISE A JOUR DE L'AGE DU PATIENT
		//MISE A JOUR DE L'AGE DU PATIENT
		//MISE A JOUR DE L'AGE DU PATIENT
		           //$this->getPatientTable()->miseAJourAgePatient($id_patient);
		//*******************************
		//*******************************
		//*******************************
		
		$pat = $this->getPatientTable ();
		
		$unPatient = $pat->getInfoPatient( $id_patient );
		
		$photo = $pat->getPhoto ( $id_patient );
		
		
		$date = $unPatient['DATE_NAISSANCE'];
		if($date){ $date = (new DateHelper())->convertDate( $unPatient['DATE_NAISSANCE'] ); }else{ $date = null;}
		
		$html  = "<div style='width:100%; height: 190px;'>";
		
		$html .= "<div style='width: 18%; height: 190px; float:left;'>";
		$html .= "<div id='photo' style='float:left; margin-left:40px; margin-top:10px; margin-right:30px;'> <img style='width:105px; height:105px;' src='".$this->baseUrl()."public/img/photos_patients/" . $photo . "' ></div>";
		$html .= "<div style='margin-left:60px; margin-top: 150px;'> <div style='text-decoration:none; font-size:14px; float:left; padding-right: 7px; '>Age:</div>  <div style='font-weight:bold; font-size:19px; font-family: time new romans; color: green; font-weight: bold;'>" . $unPatient['AGE'] . " ans</div></div>";
		$html .= "</div>";
		
		$html .= "<div id='vuePatientAdmission' style='width: 70%; height: 190px; float:left;'>";
		$html .= "<table style='margin-top:0px; float:left; width: 100%;'>";
		
		$html .= "<tr style='width: 100%;'>";
		$html .= "<td style='width: 24%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nom:</a><br><div style='width: 150px; max-width: 160px; height:40px; overflow:auto; margin-bottom: 3px;'><p style='font-weight:bold; font-size:19px;'>" . $unPatient['NOM'] . "</p></div></td>";
		$html .= "<td style='width: 24%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Date de naissance:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $date . "</p></div></td>";
		$html .= "<td style='width: 23%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>T&eacute;l&eacute;phone:</a><br><div style='width: 95%; '><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['TELEPHONE'] . "</p></div></td>";
		$html .= "<td style='width: 29%; '></td>";
		
		$html .= "</tr><tr style='width: 100%;'>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Pr&eacute;nom:</a><br><div style='width: 95%; max-width: 180px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['PRENOM'] . " </p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Lieu de naissance:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['LIEU_NAISSANCE'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nationalit&eacute; actuelle:</a><br><div style='width: 95%; max-width: 135px; overflow:auto; '><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['NATIONALITE_ACTUELLE']. "</p></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Email:</a><br><div style='width: 100%; max-width: 235px; height:40px; overflow:auto;'><p style='font-weight:bold; font-size:19px;'>" . $unPatient['EMAIL'] . "</p></div></td>";
		
		$html .= "</tr><tr style='width: 100%;'>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Sexe:</a><br><div style='width: 95%; max-width: 130px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['SEXE'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Adresse:</a><br><div style='width: 97%; max-width: 250px; height:50px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['ADRESSE'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Profession:</a><br><div style='width: 95%; max-width: 235px; height:40px; overflow:auto; '><p style=' font-weight:bold; font-size:19px;'>" .  $unPatient['PROFESSION'] . "</p></div></td>";
		
		$html .= "<td style='width: 30%; height: 50px;'>";
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "</table>";
		$html .= "</div>";
		
		$html .= "<div style='width: 12%; height: 190px; float:left;'>";
		$html .= "<div id='' style='color: white; opacity: 0.09; float:left; margin-right:10px; margin-left:5px; margin-top:5px;'> <img style='width:105px; height:105px;' src='".$this->baseUrl()."public/img/photos_patients/" . $photo . "'></div>";
		$html .= "<div style='margin-left: 5px; margin-top: 10px; margin-right:10px;'>  <div style='font-size:19px; font-family: time new romans; color: green; float:left; margin-top: 10px;'>" . $unPatient['NUMERO_DOSSIER'] . " </div></div>";
		$html .= "</div>";
		
		$html .= "</div>";
		
		$admission = $this->getAdmissionTable()->getPatientAdmis($id_admission);
		
		if($admission){
			$niveau = (int)$admission->niveau;
			
			if($niveau == 4){
				$html .= "<script> setTimeout(function(){ $('#blanc' ).trigger('click'); $('#blanc' ).trigger('click'); });</script>"; 
			}else if($niveau == 3){
				$html .= "<script> setTimeout(function(){ $('#jaune' ).trigger('click'); $('#jaune' ).trigger('click'); });</script>";
			}else if($niveau == 2){
				$html .= "<script> setTimeout(function(){ $('#orange').trigger('click'); $('#orange').trigger('click'); });</script>";
			}else if($niveau == 1){
				$html .= "<script> setTimeout(function(){ $('#rouge' ).trigger('click'); $('#rouge' ).trigger('click');}); </script>";
			}

			if($role == "infirmier-service"){
				if($admission->couloir == 1){
					$html .="<script> setTimeout(function(){ $('#couloir').trigger('click'); }); </script>";
				}else{
					$html .="<script> setTimeout(function(){ $('#salle').val('".$admission->salle."'); $('#lit').html('".$this->getListeDesLits($admission->salle)."'); }); </script>";
					$html .="<script> setTimeout(function(){ $('#lit').val('".$admission->lit."'); }); </script>";
				}

			}
		}
			
		//Recuperation de l'admission et de l'id du patient
		//Recuperation de l'admission et de l'id du patient
		$html .="<script> $('#id_patient').val('".$id_patient."'); </script>";
		$html .="<script> $('#id_admission').val('".$id_admission."'); </script>";
		
		
		//R�cup�ration des motifs des consultations
		//R�cup�ration des motifs des consultations
		$motif_admission = $this->getMotifAdmissionTable()->getMotifAdmissionUrgence($id_admission);
		$nbMotif = $motif_admission->count(); $i=1;
		if($nbMotif > 1){ $html .="<script> afficherMotif(".$nbMotif."); $('#bouton_motif_valider').trigger('click'); </script>"; }
		else{  
			if($nbMotif == 1){ $html .="<script> $('#bouton_motif_valider').trigger('click'); </script>"; }
			$html .="<script> afficherMotif(1); </script>";  
		}
		foreach ($motif_admission as $motif){
			$html .= "<script> setTimeout(function(){ $('#motif_admission".$i++."').val('".$motif->libelle_motif."'); });</script>";
		}
		
		//R�cup�ration des constantes
		//R�cup�ration des constantes 
		$constantes = $this->getConsultationTable()->getConsultationParIdAdmission($id_admission);
        if($constantes){
        	$tensions = explode(' ', $constantes['PRESSION_ARTERIELLE']);
        	if($tensions && count($tensions) == 2){
        		$html .="<script> $('#tensionmaximale').val('".$tensions[0]."'); </script>";
        		$html .="<script> $('#tensionminimale').val('".$tensions[1]."'); </script>";
        	}
        	if($constantes['TEMPERATURE'] ){ $html .="<script> $('#temperature').val('".$constantes['TEMPERATURE']."'); </script>"; }
        	if($constantes['POIDS']       ){ $html .="<script> $('#poids').val('".$constantes['POIDS']."'); </script>";             }
        	if($constantes['TAILLE']      ){ $html .="<script> $('#taille').val('".$constantes['TAILLE']."'); </script>";           }
        	if($constantes['POULS']       ){ $html .="<script> $('#pouls').val('".$constantes['POULS']."'); </script>";              }
        	if($constantes['FREQUENCE_RESPIRATOIRE'] ){ $html .="<script> $('#frequence_respiratoire').val('".$constantes['FREQUENCE_RESPIRATOIRE']."'); </script>"; }
        	if($constantes['GLYCEMIE_CAPILLAIRE']    ){ $html .="<script> $('#glycemie_capillaire').val('".$constantes['GLYCEMIE_CAPILLAIRE']."'); </script>";       }
        	$html .="<script>setTimeout(function(){ $('#bouton_constantes_valider').trigger('click'); }); </script>";
        
        	//GESTION DES BANDELETTES URINAIRE
        	$bandelettes = $this->getConsultationTable ()->getBandelette($constantes['ID_CONS']);
        	
        	if($bandelettes['temoin'] == 1){
        		if($bandelettes['albumine'] == 1){
        			$html .="<script> setTimeout(function(){ $('#BUcheckbox input[name=albumine][value=".$bandelettes['albumine']."]').attr('checked', true); $('#BUcheckbox input[name=croixalbumine][value=".$bandelettes['croixalbumine']."]').attr('checked', true); albumineOption(); }, 1000); </script>";
        		}
        		
        		if($bandelettes['sucre'] == 1){
        			$html .="<script> setTimeout(function(){ $('#BUcheckbox input[name=sucre][value=".$bandelettes['sucre']."]').attr('checked', true); $('#BUcheckbox input[name=croixsucre][value=".$bandelettes['croixsucre']."]').attr('checked', true); sucreOption(); }, 1000); </script>";
        		}

         		if($bandelettes['corpscetonique'] == 1){
         			$html .="<script> setTimeout(function(){ $('#BUcheckbox input[name=corpscetonique][value=".$bandelettes['corpscetonique']."]').attr('checked', true); $('#BUcheckbox input[name=croixcorpscetonique][value=".$bandelettes['croixcorpscetonique']."]').attr('checked', true); corpscetoniqueOption(); }, 1000); </script>";
         		}
        		 
        		$html .="<script> $('#depliantBandelette').trigger('click'); </script>";
        	}

        }

        if($role == "infirmier-service"){
        	$html .="<script> setTimeout(function(){ $('#bouton_motif_modifier, #bouton_constantes_modifier').trigger('click'); }, 500); </script>";
        }
        
        
        
        
        
		$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
		return $this->getResponse ()->setContent ( Json::encode ( $html ) );
	}
	
	public function enregistrementModificationAdmissionAction() {

		$this->layout ()->setTemplate ( 'layout/urgence' );
		$user = $this->layout()->user;
		$role = $user['role'];
		
		$today = new \DateTime ();
		$date = $today->format( 'Y-m-d' );
		$heure = $today->format( 'H:i:s' );
		
		$form = new AdmissionForm();
		$formData = $this->getRequest ()->getPost ();
		$form->setData ( $formData );
		
		$id_cons = $form->get ( "id_cons" )->getValue ();
		$id_patient = $this->params ()->fromPost( "id_patient" );
		$id_Infirmier = $user['id_employe'];
		
		$id_admission = $this->params ()->fromPost( "id_admission" );
		$niveau = $this->params ()->fromPost( "niveau" );
		
		//Recuperation des donnees des motifs d'admission
		$donneesMotifAdmission	 = array(
				'motif_admission1' => trim($form->get ( 'motif_admission1' )->getValue ()),
				'motif_admission2' => trim($form->get ( 'motif_admission2' )->getValue ()),
				'motif_admission3' => trim($form->get ( 'motif_admission3' )->getValue ()),
				'motif_admission4' => trim($form->get ( 'motif_admission4' )->getValue ()),
				'motif_admission5' => trim($form->get ( 'motif_admission5' )->getValue ()),
		);
		
		//Recuperation des donnees des constantes
		$donneesConstantes = array(
				'POIDS' => (int)trim($form->get ( "poids" )->getValue ()),
				'TAILLE' => (int)trim($form->get ( "taille" )->getValue ()),
				'TEMPERATURE' => (int)trim($form->get ( "temperature" )->getValue ()),
				'PRESSION_ARTERIELLE' => trim( trim($form->get ( "tensionmaximale" )->getValue ()).' '.trim($form->get ( "tensionminimale" )->getValue ()) ),
				'POULS' => (int)trim($form->get ( "pouls" )->getValue ()),
				'FREQUENCE_RESPIRATOIRE' => (int)trim($form->get ( "frequence_respiratoire" )->getValue ()),
				'GLYCEMIE_CAPILLAIRE' => (int)trim($form->get ( "glycemie_capillaire" )->getValue ()),
		);
		
		//Recuperer les donnees sur les bandelettes urinaires
		//Recuperer les donnees sur les bandelettes urinaires
		$bandelettes = array(
				'albumine' => $this->params()->fromPost('albumine'),
				'sucre' => $this->params()->fromPost('sucre'),
				'corpscetonique' => $this->params()->fromPost('corpscetonique'),
				'croixalbumine' => $this->params()->fromPost('croixalbumine'),
				'croixsucre' => $this->params()->fromPost('croixsucre'),
				'croixcorpscetonique' => $this->params()->fromPost('croixcorpscetonique'),
		);
		
		//Insertion des donnees de l'infirmier de tri
		//Insertion des donnees de l'infirmier de tri
		//Insertion des donnees de l'infirmier de tri
		if($role == "infirmier-tri"){
				
			//Insertion de l'admission pour admettre le patient au niveau de l'infirmier de service
			//Insertion de l'admission pour admettre le patient au niveau de l'infirmier de service
			$donneesAdmission = array(
					'id_infirmier_tri' => $id_Infirmier,
					'niveau' => $niveau,
			);
			$this->getAdmissionTable()->updateAdmission($donneesAdmission, $id_admission);
				
			
			//Insertion des motifs de l'admission s'il y'en a
			//Insertion des motifs de l'admission s'il y'en a
			$this->getMotifAdmissionTable ()->deleteMotifAdmission($id_admission);
			if(!$this->array_empty($donneesMotifAdmission)){
				$this->getMotifAdmissionTable ()->addMotifAdmission ( $form , $id_admission);
			}
				
			//Insertion des constantes s'il y'en a
			//Insertion des constantes s'il y'en a
			$consultation_urgence = $this->getConsultationTable ()->getConsultationUrgence($id_admission);
			if($consultation_urgence){
				$this->getConsultationTable ()->updateConsultationUrgence($donneesConstantes, $consultation_urgence['id_cons']);
				
				//mettre � jour les bandelettes urinaires
				$bandelettes['id_cons'] = $consultation_urgence['id_cons'];
				$this->getConsultationTable ()->deleteBandelette($consultation_urgence['id_cons']);
				$this->getConsultationTable ()->addBandelette($bandelettes);
			}else{
				//Insertion des constantes s'il y'en a
				//Insertion des constantes s'il y'en a
				if(!$this->array_empty($donneesConstantes) || !$this->array_empty($bandelettes)){
					$donneesConstantes['ID_CONS']    = $id_cons;
					$donneesConstantes['ID_PATIENT'] = (int)$id_patient;
					$donneesConstantes['DATEONLY']   = $form->get ( "dateonly" )->getValue ();
					$donneesConstantes['HEURECONS']  = $form->get ( "heure_cons" )->getValue ();
				
					$this->getConsultationTable ()->addConsultation ($donneesConstantes);
					$this->getConsultationTable ()->addConsultationUrgence ($id_cons, $id_admission, $id_Infirmier);
					
					//mettre � jour les bandelettes urinaires
					$bandelettes['id_cons'] = $id_cons;
					$this->getConsultationTable ()->deleteBandelette($id_cons);
					$this->getConsultationTable ()->addBandelette($bandelettes);
				}
				
			}
			
		}
		
		if($role == "infirmier-service"){
			
			$id_admission = $this->params ()->fromPost( "id_admission" );
			if($id_admission){
			
				//Validation de l'admission par l'infirmier de service
				//Validation de l'admission par l'infirmier de service
				$donneesAdmission = array(
						'id_infirmier_service' => $id_Infirmier,
						'niveau' => $niveau,
						'salle'  => trim($form->get ( "salle" )->getValue ()),
						'lit'    => trim($form->get ( "lit" )->getValue ()),
						'couloir' => trim($form->get ( "couloir" )->getValue ()),
				);
				$this->getAdmissionTable()->updateAdmission($donneesAdmission, $id_admission);
			
				//Insertion des motifs de l'admission s'il y'en a
				//Insertion des motifs de l'admission s'il y'en a
				$this->getMotifAdmissionTable ()->deleteMotifAdmission($id_admission);
				if(!$this->array_empty($donneesMotifAdmission)){
					$this->getMotifAdmissionTable ()->addMotifAdmission ( $form , $id_admission);
				}
					
				//Insertion des constantes s'il y'en a (est pass� par l'infirmier de tri)
				//Insertion des constantes s'il y'en a (est pass� par l'infirmier de tri)
				$consultation_urgence = $this->getConsultationTable ()->getConsultationUrgence($id_admission);
				if($consultation_urgence){
					$this->getConsultationTable ()->updateConsultationUrgence($donneesConstantes, $consultation_urgence['id_cons']);
					$this->getConsultationTable ()->miseajourConsultationUrgence($id_Infirmier, $consultation_urgence['id_cons']);
					
					//mettre � jour les bandelettes urinaires
					$bandelettes['id_cons'] = $consultation_urgence['id_cons'];
					$this->getConsultationTable ()->deleteBandelette($consultation_urgence['id_cons']);
					$this->getConsultationTable ()->addBandelette($bandelettes);
				}else{
					//Insertion des constantes s'il y'en a
					//Insertion des constantes s'il y'en a
					if(!$this->array_empty($donneesConstantes) ||  !$this->array_empty($bandelettes)){
						$donneesConstantes['ID_CONS']    = $id_cons;
						$donneesConstantes['ID_PATIENT'] = (int)$id_patient;
						$donneesConstantes['DATEONLY']   = $form->get ( "dateonly" )->getValue ();
						$donneesConstantes['HEURECONS']  = $form->get ( "heure_cons" )->getValue ();
							
						$this->getConsultationTable ()->addConsultation ($donneesConstantes);
						$this->getConsultationTable ()->addConsultationUrgenceInfirmierService ($id_cons, $id_admission, $id_Infirmier);
						
						//mettre � jour les bandelettes urinaires
						$bandelettes['id_cons'] = $id_cons;
						$this->getConsultationTable ()->deleteBandelette($id_cons);
						$this->getConsultationTable ()->addBandelette($bandelettes);
					}
				}
			
			}
		
		}
		
		return $this->redirect ()->toRoute ('urgence', array ('action' => 'liste-patients-admis' ));
		
	}
	
	
	
	public function suppressionAdmissionParInfirmiertriAction(){
		
		$id_patient = ( int ) $this->params ()->fromPost ( 'id_patient', 0 );
		$id_admission = ( int ) $this->params ()->fromPost ( 'id_admission', 0 );
		$reponse = 1;
		$admission = $this->getAdmissionTable()->getAdmissionParInfirmierTri($id_admission);
		if($admission){
			$consultation_urgence = $this->getConsultationTable ()->getConsultationUrgence($id_admission);
			$this->getConsultationTable()->deleteConsultationUrgence($consultation_urgence['id_cons']);
			$this->getAdmissionTable()->deleteAdmission($id_admission);
		}else{
			$reponse = 0;
		}
		
		$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
		return $this->getResponse ()->setContent ( Json::encode ( $reponse ) );
	}
	
	
	//DOMAINE DE LA GESTION DES INTERFACE DE L'INFIRMIER DE SERVICE
	//DOMAINE DE LA GESTION DES INTERFACE DE L'INFIRMIER DE SERVICE
	//DOMAINE DE LA GESTION DES INTERFACE DE L'INFIRMIER DE SERVICE
	/**
	 * Afficher les infos sur l'admission d'un patient par l'infir�ier de tri 
	 */
	public function getInfosAdmissionParInfirmierTriAction() {
	
		$today = new \DateTime ();
		$dateAujourdhui = $today->format( 'Y-m-d' );
	
		$id_patient = ( int ) $this->params ()->fromPost ( 'id_patient', 0 );
		$id_admission = ( int ) $this->params ()->fromPost ( 'id_admission', 0 );
	
		
		$pat = $this->getPatientTable ();
		
		$unPatient = $pat->getInfoPatient( $id_patient );
		
		$photo = $pat->getPhoto ( $id_patient );
		
		
		$date = $unPatient['DATE_NAISSANCE'];
		if($date){ $date = (new DateHelper())->convertDate( $unPatient['DATE_NAISSANCE'] ); }else{ $date = null;}
		
		$html  = "<div style='width:100%; height: 190px;'>";
		
		$html .= "<div style='width: 18%; height: 190px; float:left;'>";
		$html .= "<div id='photo' style='float:left; margin-left:40px; margin-top:10px; margin-right:30px;'> <img style='width:105px; height:105px;' src='".$this->baseUrl()."public/img/photos_patients/" . $photo . "' ></div>";
		$html .= "<div style='margin-left:60px; margin-top: 150px;'> <div style='text-decoration:none; font-size:14px; float:left; padding-right: 7px; '>Age:</div>  <div style='font-weight:bold; font-size:19px; font-family: time new romans; color: green; font-weight: bold;'>" . $unPatient['AGE'] . " ans</div></div>";
		$html .= "</div>";
		
		$html .= "<div id='vuePatientAdmission' style='width: 70%; height: 190px; float:left;'>";
		$html .= "<table style='margin-top:0px; float:left; width: 100%;'>";
		
		$html .= "<tr style='width: 100%;'>";
		$html .= "<td style='width: 24%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nom:</a><br><div style='width: 150px; max-width: 160px; height:40px; overflow:auto; margin-bottom: 3px;'><p style='font-weight:bold; font-size:19px;'>" . $unPatient['NOM'] . "</p></div></td>";
		$html .= "<td style='width: 24%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Date de naissance:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $date . "</p></div></td>";
		$html .= "<td style='width: 23%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>T&eacute;l&eacute;phone:</a><br><div style='width: 95%; '><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['TELEPHONE'] . "</p></div></td>";
		$html .= "<td style='width: 29%; '></td>";
		
		$html .= "</tr><tr style='width: 100%;'>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Pr&eacute;nom:</a><br><div style='width: 95%; max-width: 180px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['PRENOM'] . " </p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Lieu de naissance:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['LIEU_NAISSANCE'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nationalit&eacute; actuelle:</a><br><div style='width: 95%; max-width: 135px; overflow:auto; '><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['NATIONALITE_ACTUELLE']. "</p></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Email:</a><br><div style='width: 100%; max-width: 235px; height:40px; overflow:auto;'><p style='font-weight:bold; font-size:19px;'>" . $unPatient['EMAIL'] . "</p></div></td>";
		
		$html .= "</tr><tr style='width: 100%;'>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Sexe:</a><br><div style='width: 95%; max-width: 130px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['SEXE'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Adresse:</a><br><div style='width: 97%; max-width: 250px; height:50px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:19px;'>" . $unPatient['ADRESSE'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Profession:</a><br><div style='width: 95%; max-width: 235px; height:40px; overflow:auto; '><p style=' font-weight:bold; font-size:19px;'>" .  $unPatient['PROFESSION'] . "</p></div></td>";
		
		$html .= "<td style='width: 30%; height: 50px;'>";
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "</table>";
		$html .= "</div>";
		
		$html .= "<div style='width: 12%; height: 190px; float:left;'>";
		$html .= "<div id='' style='color: white; opacity: 0.09; float:left; margin-right:10px; margin-left:5px; margin-top:5px;'> <img style='width:105px; height:105px;' src='".$this->baseUrl()."public/img/photos_patients/" . $photo . "'></div>";
		$html .= "<div style='margin-left: 5px; margin-top: 10px; margin-right:10px;'>  <div style='font-size:19px; font-family: time new romans; color: green; float:left; margin-top: 10px;'>" . $unPatient['NUMERO_DOSSIER'] . " </div></div>";
		$html .= "</div>";
		
		$html .= "</div>";
		
		
		$admission = $this->getAdmissionTable()->getPatientAdmis($id_admission);
	
		if($admission){
			$niveau = (int)$admission->niveau;
				
			if($niveau == 4){
				$html .= "<script> setTimeout(function(){ $('#blanc' ).trigger('click'); $('#blanc' ).trigger('click'); });</script>";
			}else if($niveau == 3){
				$html .= "<script> setTimeout(function(){ $('#jaune' ).trigger('click'); $('#jaune' ).trigger('click'); });</script>";
			}else if($niveau == 2){
				$html .= "<script> setTimeout(function(){ $('#orange').trigger('click'); $('#orange').trigger('click'); });</script>";
			}else if($niveau == 1){
				$html .= "<script> setTimeout(function(){ $('#rouge' ).trigger('click'); $('#rouge' ).trigger('click');}); </script>";
			}
	
		}
			
		//Recuperation de l'admission et de l'id du patient
		//Recuperation de l'admission et de l'id du patient
		$html .="<script> $('#id_patient').val('".$id_patient."'); </script>";
		$html .="<script> $('#id_admission').val('".$id_admission."'); </script>";
	
	
		//R�cup�ration des motifs des consultations
		//R�cup�ration des motifs des consultations
		$motif_admission = $this->getMotifAdmissionTable()->getMotifAdmissionUrgence($id_admission);
		$nbMotif = $motif_admission->count(); $i=1;
		if($nbMotif > 1){ $html .="<script> afficherMotif(".$nbMotif."); $('#bouton_motif_valider').trigger('click'); </script>"; }
		else{
			if($nbMotif == 1){ $html .="<script> $('#bouton_motif_valider').trigger('click'); </script>"; }
			$html .="<script> afficherMotif(1); </script>";
		}
		foreach ($motif_admission as $motif){
			$html .= "<script> setTimeout(function(){ $('#motif_admission".$i++."').val('".$motif->libelle_motif."'); });</script>";
		}
	
		//R�cup�ration des constantes
		//R�cup�ration des constantes
		$constantes = $this->getConsultationTable()->getConsultationParIdAdmission($id_admission);
		if($constantes){
			$tensions = explode(' ', $constantes['PRESSION_ARTERIELLE']);
			if($tensions && count($tensions) == 2){
				$html .="<script> $('#tensionmaximale').val('".$tensions[0]."'); </script>";
				$html .="<script> $('#tensionminimale').val('".$tensions[1]."'); </script>";
			}
			if($constantes['TEMPERATURE'] ){ $html .="<script> $('#temperature').val('".$constantes['TEMPERATURE']."'); </script>"; }
			if($constantes['POIDS']       ){ $html .="<script> $('#poids').val('".$constantes['POIDS']."'); </script>";             }
			if($constantes['TAILLE']      ){ $html .="<script> $('#taille').val('".$constantes['TAILLE']."'); </script>";           }
			if($constantes['POULS']       ){ $html .="<script> $('#pouls').val('".$constantes['POULS']."'); </script>";              }
			if($constantes['FREQUENCE_RESPIRATOIRE'] ){ $html .="<script> $('#frequence_respiratoire').val('".$constantes['FREQUENCE_RESPIRATOIRE']."'); </script>"; }
			if($constantes['GLYCEMIE_CAPILLAIRE']    ){ $html .="<script> $('#glycemie_capillaire').val('".$constantes['GLYCEMIE_CAPILLAIRE']."'); </script>";       }
			$html .="<script>setTimeout(function(){ /* $('#bouton_constantes_valider').trigger('click'); */ }); </script>";
		
			//GESTION DES BANDELETTES URINAIRE
			$bandelettes = $this->getConsultationTable ()->getBandelette($constantes['ID_CONS']);
			 
			if($bandelettes['temoin'] == 1){
        		if($bandelettes['albumine'] == 1){
        			$html .="<script> setTimeout(function(){ $('#BUcheckbox input[name=albumine][value=".$bandelettes['albumine']."]').attr('checked', true); $('#BUcheckbox input[name=croixalbumine][value=".$bandelettes['croixalbumine']."]').attr('checked', true); albumineOption(); }, 1000); </script>";
        		}
        		
        		if($bandelettes['sucre'] == 1){
        			$html .="<script> setTimeout(function(){ $('#BUcheckbox input[name=sucre][value=".$bandelettes['sucre']."]').attr('checked', true); $('#BUcheckbox input[name=croixsucre][value=".$bandelettes['croixsucre']."]').attr('checked', true); sucreOption(); }, 1000); </script>";
        		}

         		if($bandelettes['corpscetonique'] == 1){
         			$html .="<script> setTimeout(function(){ $('#BUcheckbox input[name=corpscetonique][value=".$bandelettes['corpscetonique']."]').attr('checked', true); $('#BUcheckbox input[name=croixcorpscetonique][value=".$bandelettes['croixcorpscetonique']."]').attr('checked', true); corpscetoniqueOption(); }, 1000); </script>";
         		}
        		 
        		$html .="<script> $('#depliantBandelette').trigger('click'); </script>";
        	}
		}
	
	
		$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
		return $this->getResponse ()->setContent ( Json::encode ( $html ) );
	}
	
	public function listePatientsAdmisInfirmierServiceAjaxAction() {
		$output = $this->getPatientTable ()->getListePatientAdmisInfirmierService ();
		return $this->getResponse ()->setContent ( Json::encode ( $output, array (
				'enableJsonExprFinder' => true
		) ) );
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

    //impression d'une facture de patient 
// 	public function impressionPdfAction(){
	
// 		$id_patient = $this->params()->fromPost( 'id_patient' );
// 		$user = $this->layout()->user;
// 		$service = $user['NomService'];
// 		//******************************************************
// 		//******************************************************
// 		//*********** DONNEES COMMUNES A TOUS LES PDF **********
// 		//******************************************************
// 		//******************************************************
// 		$lePatient = $this->getPatientTable()->getInfoPatient( $id_patient );
	
// 		$infos = array(
// 				'numero' => $this->params ()->fromPost ( 'numero' ),
// 				'service' => $this->getPatientTable()->getServiceParId( $this->params ()->fromPost ( 'service' ) )['NOM'],
// 				'montant' => $this->params ()->fromPost ( 'montant' ),
// 				'montant_avec_majoration' => $this->params ()->fromPost ( 'montant_avec_majoration' ),
// 				'type_facturation' => $this->params ()->fromPost ( 'type_facturation' ),
// 				'organisme' => $this->params ()->fromPost ( 'organisme' ),
// 				'taux' => $this->params ()->fromPost ( 'taux' ),
// 		);
			
// 		//******************************************************
// 		//******************************************************
// 		//*************** Cr�ation du fichier pdf **************
// 		//******************************************************
// 		//******************************************************
// 		//Cr�er le document
// 		$DocPdf = new DocumentPdf();
// 		//Cr�er la page
// 		$page = new FacturePdf();
	
// 		//Envoyer les donn�es sur le partient
// 		$page->setDonneesPatient($lePatient);
// 		$page->setService($service);
// 		$page->setInformations($infos);
// 		//Ajouter une note � la page
// 		$page->addNote();
// 		//Ajouter la page au document
// 		$DocPdf->addPage($page->getPage());
// 		//Afficher le document contenant la page
	
// 		$DocPdf->getDocument();
	
// 	}

}