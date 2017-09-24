<?php

namespace Chururgie\Controller;

use Chururgie\Form\AdmissionForm;
use Chururgie\Form\ConsultationForm;
use Chururgie\Form\PatientForm;
use Chururgie\View\Helper\DateHelper;
use Chururgie\View\Helper\DemandeExamenPdf;
use Chururgie\View\Helper\DocumentPdf;
use Chururgie\View\Helper\HospitalisationPdf;
use Chururgie\View\Helper\OrdonnancePdf;
use Chururgie\View\Helper\RendezVousPdf;
use Chururgie\View\Helper\TraitementChirurgicalPdf;
use Chururgie\View\Helper\TraitementInstrumentalPdf;
use Chururgie\View\Helper\TransfertPdf;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
//use Zend\Stdlib\DateTime;
use Zend\Stdlib\DateTime;
use Zend\View\Model\ViewModel;
use Zend\Code\Reflection\FunctionReflection;

class ChururgieController extends AbstractActionController {
	protected $patientTable;
	protected $controlDate;
	protected $formPatient;
	protected $tarifConsultationTable;
	protected $serviceTable;
	protected $admissionTable;
	protected $consultationTable;
	protected $demandeExamensTable;
  	protected $demandeHospitalisationTable;
	protected $motifAdmissionTable;
	protected $transfererPatientServiceTable;
	protected $antecedantPersonnelTable;
	protected $antecedantsFamiliauxTable;
	protected $donneesExamensPhysiquesTable;
	protected $notesExamensMorphologiquesTable;
	protected $diagnosticsTable;
	protected $ordonnanceTable;
	protected $demandeVisitePreanesthesiqueTable;
	protected $resultatVpaTable;
	protected $rvPatientConsTable;
	protected $demandeActeTable;
	protected $ordonConsommableTable;
	protected $hopitalTable;
	protected $organeTable;
	
	public function getOrganeTable() {
	    if (! $this->organeTable) {
	        $sm = $this->getServiceLocator ();
	        $this->organeTable = $sm->get ( 'Chururgie\Model\OrganeTable' );
	    }
	    return $this->organeTable;
	}
	public function getHopitalTable() {
	    if (! $this->hopitalTable) {
	        $sm = $this->getServiceLocator ();
	        $this->hopitalTable = $sm->get ( 'Personnel\Model\HopitalTable' );
	    }
	    return $this->hopitalTable;
	}
	
	public function getOrdonConsommableTable() {
		if (! $this->ordonConsommableTable) {
			$sm = $this->getServiceLocator ();
			$this->ordonConsommableTable = $sm->get ( 'Chururgie\Model\OrdonConsommableTable' );
		}
		return $this->ordonConsommableTable;
	}
	public function getDemandeActe() {
		if (! $this->demandeActeTable) {
			$sm = $this->getServiceLocator ();
			$this->demandeActeTable = $sm->get ( 'Chururgie\Model\DemandeActeTable' );
		}
		return $this->demandeActeTable;
	}
	public function getRvPatientConsTable() {
		if (! $this->rvPatientConsTable) {
			$sm = $this->getServiceLocator ();
			$this->rvPatientConsTable = $sm->get ( 'Chururgie\Model\RvPatientConsTable' );
		}
		return $this->rvPatientConsTable;
	}
	public function getResultatVpa() {
		if (! $this->resultatVpaTable) {
			$sm = $this->getServiceLocator ();
			$this->resultatVpaTable = $sm->get ( 'Chururgie\Model\ResultatVisitePreanesthesiqueTable' );
		}
		return $this->resultatVpaTable;
	}
	public function getDemandeVisitePreanesthesiqueTable() {
		if (! $this->demandeVisitePreanesthesiqueTable) {
			$sm = $this->getServiceLocator ();
			$this->demandeVisitePreanesthesiqueTable = $sm->get ( 'Chururgie\Model\DemandeVisitePreanesthesiqueTable' );
		}
		return $this->demandeVisitePreanesthesiqueTable;
	}
	public function getOrdonnanceTable() {
		if (! $this->ordonnanceTable) {
			$sm = $this->getServiceLocator ();
			$this->ordonnanceTable = $sm->get ( 'Chururgie\Model\OrdonnanceTable' );
		}
		return $this->ordonnanceTable;
	}
	public function getDiagnosticsTable() {
		if (! $this->diagnosticsTable) {
			$sm = $this->getServiceLocator ();
			$this->diagnosticsTable = $sm->get ( 'Chururgie\Model\DiagnosticsTable' );
		}
		return $this->diagnosticsTable;
	}
	public function getNotesExamensMorphologiquesTable() {
		if (! $this->notesExamensMorphologiquesTable) {
			$sm = $this->getServiceLocator ();
			$this->notesExamensMorphologiquesTable = $sm->get ( 'Chururgie\Model\NotesExamensMorphologiquesTable' );
		}
		return $this->notesExamensMorphologiquesTable;
	}
	public function getDonneesExamensPhysiquesTable() {
		if (! $this->donneesExamensPhysiquesTable) {
			$sm = $this->getServiceLocator ();
			$this->donneesExamensPhysiquesTable = $sm->get ( 'Chururgie\Model\DonneesExamensPhysiquesTable' );
		}
		return $this->donneesExamensPhysiquesTable;
	}
	public function getAntecedantPersonnelTable() {
		if (! $this->antecedantPersonnelTable) {
			$sm = $this->getServiceLocator ();
			$this->antecedantPersonnelTable = $sm->get ( 'Chururgie\Model\AntecedentPersonnelTable' );
		}
		return $this->antecedantPersonnelTable;
	}
	public function getAntecedantsFamiliauxTable() {
		if (! $this->antecedantsFamiliauxTable) {
			$sm = $this->getServiceLocator ();
			$this->antecedantsFamiliauxTable = $sm->get ( 'Chururgie\Model\AntecedentsFamiliauxTable' );
		}
		return $this->antecedantsFamiliauxTable;
	}
	
	public function getTransfererPatientServiceTable() {
		if (! $this->transfererPatientServiceTable) {
			$sm = $this->getServiceLocator ();
			$this->transfererPatientServiceTable = $sm->get ( 'Chururgie\Model\TransfererPatientServiceTable' );
		}
		return $this->transfererPatientServiceTable;
	}
	public function getMotifAdmissionTable() {
		if (! $this->motifAdmissionTable) {
			$sm = $this->getServiceLocator ();
			$this->motifAdmissionTable = $sm->get ( 'Chururgie\Model\MotifAdmissionTable' );
		}
		return $this->motifAdmissionTable;
	}
	public function getDemandeHospitalisationTable() {
		if (! $this->demandeHospitalisationTable) {
			$sm = $this->getServiceLocator ();
			$this->demandeHospitalisationTable = $sm->get ( 'Chururgie\Model\DemandehospitalisationTable' );
		}
		return $this->demandeHospitalisationTable;
	}
	public function demandeExamensTable() {
		if (! $this->demandeExamensTable) {
			$sm = $this->getServiceLocator ();
			$this->demandeExamensTable = $sm->get ( 'Chururgie\Model\DemandeTable' );
		}
		return $this->demandeExamensTable;
	}
	public function getConsultationTable() {
		if (! $this->consultationTable) {
			$sm = $this->getServiceLocator ();
			$this->consultationTable = $sm->get ( 'Chururgie\Model\ConsultationTable' );
		}
		return $this->consultationTable;
	}
	Public function getDateHelper() {
		$this->dateHelper = new DateHelper();
	}
	public function convertDate($date) {
		$nouv_date = substr ( $date, 8, 2 ) . '/' . substr ( $date, 5, 2 ) . '/' . substr ( $date, 0, 4 );
		return $nouv_date;
	}
	public function getAdmissionTable() {
		if (! $this->admissionTable) {
			$sm = $this->getServiceLocator ();
			$this->admissionTable = $sm->get ( 'Chururgie\Model\AdmissionTable' );
		}
		return $this->admissionTable;
	}
	public function getServiceTable() {
		if (! $this->serviceTable) {
			$sm = $this->getServiceLocator ();
			$this->serviceTable = $sm->get ( 'Chururgie\Model\ServiceTable' );
		}
		return $this->serviceTable;
	}
	public function getTarifConsultationTable() {
		if (! $this->tarifConsultationTable) {
			$sm = $this->getServiceLocator ();
			$this->tarifConsultationTable = $sm->get ( 'Chururgie\Model\TarifConsultationTable' );
		}
		return $this->tarifConsultationTable;
	}
	public function getForm() {
		if (! $this->formPatient) {
			$this->formPatient = new PatientForm ();
		}
		return $this->formPatient;
	}
	public function getPatientTable() {
		if (! $this->patientTable) {
			$sm = $this->getServiceLocator ();
			$this->patientTable = $sm->get ( 'Chururgie\Model\PatientTable' );
		}
		return $this->patientTable;
	}
	
	public function getAntecedantPersonnel(){
		
	}
	
	
	
	
	
	//************************************************************************************
	//*************************Mise A jour Consultation du Medecin************************
	//*************************Mise A jour Consultation du Medecin************************
	//*************************Mise A jour Consultation du Medecin************************
	//*************************Mise A jour Consultation du Medecin************************
	
	//************************************************************************************
	public function majComplementConsultationAction() {
	    
	    $this->layout ()->setTemplate ( 'layout/consultation' );
	    
	    $user = $this->layout()->user;
	    $IdDuService = $user['IdService'];
	    $id_medecin = $user['id_personne'];
	    
	    $this->getDateHelper();
	    $id_pat = $this->params()->fromQuery ( 'id_patient', 0 );
	    $id = $this->params()->fromQuery ( 'id_cons' );
	    $id_admission = $this->params()->fromQuery ( 'id_admission' );
	    $form = new ConsultationForm();
	    
	    $liste = $this->getConsultationTable()->getInfoPatient ( $id_pat );
	    $image = $this->getConsultationTable()->getPhoto ( $id_pat );
	    
	    
	    //GESTION DES ALERTES
	    //GESTION DES ALERTES
	    //GESTION DES ALERTES
	    //RECUPERER TOUS LES PATIENTS AYANT UN RV aujourd'hui
	    $tabPatientRV = $this->getConsultationTable()->getPatientsRV($IdDuService);
	    $resultRV = null;
	    if(array_key_exists($id_pat, $tabPatientRV)){
	        $resultRV = $tabPatientRV[ $id_pat ];
	    }
	    
	    //POUR LES CONSTANTES
	    //POUR LES CONSTANTES
	    //POUR LES CONSTANTES
	    $consult = $this->getConsultationTable ()->getConsult ( $id );
	    $pos = strpos($consult->pression_arterielle, '/') ;
	    $tensionmaximale = substr($consult->pression_arterielle, 0, $pos);
	    $tensionminimale = substr($consult->pression_arterielle, $pos+1);
	    
	    $data = array (
	        'id_cons' => $consult->id_cons,
	        'id_medecin' => $consult->id_medecin,
	        'id_patient' => $consult->id_patient,
	        'date_cons' => $consult->date,
	        'poids' => $consult->poids,
	        'taille' => $consult->taille,
	        'temperature' => $consult->temperature,
	        'tensionmaximale' => $tensionmaximale,
	        'tensionminimale' => $tensionminimale,
	        'pouls' => $consult->pouls,
	        'frequence_respiratoire' => $consult->frequence_respiratoire,
	        'glycemie_capillaire' => $consult->glycemie_capillaire,
	    );
	    
	    //POUR LES MOTIFS D'ADMISSION
	    //POUR LES MOTIFS D'ADMISSION
	    //POUR LES MOTIFS D'ADMISSION
	    // instancier le motif d'admission et recup�rer l'enregistrement
	    $motif_admission = $this->getMotifAdmissionTable ()->getMotifAdmission ( $id );
	    $nbMotif = $this->getMotifAdmissionTable ()->nbMotifs ( $id );
	    
	    //POUR LES MOTIFS D'ADMISSION
	    $k = 1;
	    foreach ( $motif_admission as $Motifs ) {
	        $data ['motif_admission' . $k] = $Motifs ['Libelle_motif'];
	        $k ++;
	    }
	    
	    //POUR LES EXAMEN PHYSIQUES
	    //POUR LES EXAMEN PHYSIQUES
	    //POUR LES EXAMEN PHYSIQUES
	    $examen_physique = $this->getDonneesExamensPhysiquesTable()->getExamensPhysiques($id);
	    
	    //POUR LES EXAMEN PHYSIQUES
	    $kPhysique = 1;
	    foreach ($examen_physique as $Examen) {
	        $data['examen_donnee'.$kPhysique] = $Examen['libelle_examen'];
	        $kPhysique++;
	    }
	    
	    // POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
	    // POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
	    // POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
	    $listeConsultation = $this->getConsultationTable ()->getConsultationPatientSaufActu($id_pat, $id);
	    
	    //Recuperer les informations sur le surveillant de service pour les consultations qui diff�rent des consultations prises lors des archives
	    $tabInfoSurv = array();
	    foreach ($listeConsultation as $listeCons){
	        if($listeCons['ID_SURVEILLANT']){
	            $tabInfoSurv [$listeCons['ID_CONS']] = $this->getConsultationTable ()->getInfosSurveillant($listeCons['ID_SURVEILLANT'])['PRENOM'].' '.$this->getConsultationTable ()->getInfosSurveillant($listeCons['ID_SURVEILLANT'])['NOM'];
	        }else{
	            $tabInfoSurv [$listeCons['ID_CONS']] = '_________';
	        }
	    }
	    
	    $listeConsultation = $this->getConsultationTable ()->getConsultationPatientSaufActu($id_pat, $id);
	    
	    //*** Liste des Hospitalisations
	    $listeHospitalisation = $this->getDemandeHospitalisationTable()->getDemandeHospitalisationWithIdPatient($id_pat);
	    
	    //POUR LES EXAMENS COMPLEMENTAIRES
	    //POUR LES EXAMENS COMPLEMENTAIRES
	    //POUR LES EXAMENS COMPLEMENTAIRES
	    // DEMANDES DES EXAMENS COMPLEMENTAIRES
	    $listeDemandesMorphologiques = $this->demandeExamensTable()->getDemandeExamensMorphologiques($id);
	    $listeDemandesBiologiques = $this->demandeExamensTable()->getDemandeExamensBiologiques($id);
	    $listeDemandesActes = $this->getDemandeActe()->getDemandeActe($id);
	    
	    //Liste des examens biologiques
	    $listeDesExamensBiologiques = $this->demandeExamensTable()->getDemandeDesExamensBiologiques();
	    //Liste des examens Morphologiques
	    $listeDesExamensMorphologiques = $this->demandeExamensTable()->getDemandeDesExamensMorphologiques();
	    
	    //var_dump($listeDesExamensBiologiques); exit();
	    
	    ////RESULTATS DES EXAMENS BIOLOGIQUES DEJA EFFECTUES ET ENVOYER PAR LE BIOLOGISTE
	    $listeDemandesBiologiquesEffectuerEnvoyer = $this->demandeExamensTable()->getDemandeExamensBiologiquesEffectuesEnvoyer($id);
	    $listeDemandesBiologiquesEffectuer = $this->demandeExamensTable()->getDemandeExamensBiologiquesEffectues($id);
	    
	    $tableauResultatsExamensBio = array(
	        'temoinGSan' => 0,
	        'temoinHSan' => 0,
	        'temoinBHep' => 0,
	        'temoinBRen' => 0,
	        'temoinBHem' => 0,
	        'temoinBInf' => 0,
	    );
	    foreach ($listeDemandesBiologiquesEffectuerEnvoyer as $listeExamenBioEffectues){
	        if($listeExamenBioEffectues['idExamen'] == 1){
	            $data['groupe_sanguin'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['groupe_sanguin_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['groupe_sanguin_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['groupe_sanguin_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinGSan'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 2){
	            $data['hemogramme_sanguin'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['hemogramme_sanguin_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['hemogramme_sanguin_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['hemogramme_sanguin_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinHSan'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 3){
	            $data['bilan_hepatique'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['bilan_hepatique_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['bilan_hepatique_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['bilan_hepatique_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinBHep'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 4){
	            $data['bilan_renal'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['bilan_renal_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['bilan_renal_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['bilan_renal_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinBRen'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 5){
	            $data['bilan_hemolyse'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['bilan_hemolyse_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['bilan_hemolyse_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['bilan_hemolyse_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinBHem'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 6){
	            $data['bilan_inflammatoire'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['bilan_inflammatoire_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['bilan_inflammatoire_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['bilan_inflammatoire_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinBInf'] = 1;
	        }
	    }
	    
	    ////RESULTATS DES EXAMENS MORPHOLOGIQUE
	    $examen_morphologique = $this->getNotesExamensMorphologiquesTable()->getNotesExamensMorphologiques($id);
	    
	    $data['radio'] = $examen_morphologique['radio'];
	    $data['ecographie'] = $examen_morphologique['ecographie'];
	    $data['fibrocospie'] = $examen_morphologique['fibroscopie'];
	    $data['scanner'] = $examen_morphologique['scanner'];
	    $data['irm'] = $examen_morphologique['irm'];
	    
	    ////RESULTATS DES EXAMENS MORPHOLOGIQUES DEJA EFFECTUES ET ENVOYER PAR LE BIOLOGISTE
	    $listeDemandesMorphologiquesEffectuer = $this->demandeExamensTable()->getDemandeExamensMorphologiquesEffectues($id);
	    
	    //DIAGNOSTICS
	    //DIAGNOSTICS
	    //DIAGNOSTICS
	    $infoDiagnostics = $this->getDiagnosticsTable()->getDiagnostics($id);
	    // POUR LES DIAGNOSTICS
	    $k = 1;
	    foreach ($infoDiagnostics as $diagnos){
	        $data['diagnostic'.$k] = $diagnos['libelle_diagnostics'];
	        $k++;
	    }
	    
	    //TRAITEMENT (Ordonnance) *********************************************************
	    //TRAITEMENT (Ordonnance) *********************************************************
	    //TRAITEMENT (Ordonnance) *********************************************************
	    
	    //POUR LES MEDICAMENTS
	    //POUR LES MEDICAMENTS
	    //POUR LES MEDICAMENTS
	    // INSTANCIATION DES MEDICAMENTS de l'ordonnance
	    $listeMedicament = $this->getConsultationTable()->listeDeTousLesMedicaments();
	    $listeForme = $this->getConsultationTable()->formesMedicaments();
	    $listetypeQuantiteMedicament = $this->getConsultationTable()->typeQuantiteMedicaments();
	    
	    // INSTANTIATION DE L'ORDONNANCE
	    $infoOrdonnance = $this->getOrdonnanceTable()->getOrdonnanceNonHospi($id);
	    
	    if($infoOrdonnance) {
	        $idOrdonnance = $infoOrdonnance->id_document;
	        $duree_traitement = $infoOrdonnance->duree_traitement;
	        //LISTE DES MEDICAMENTS PRESCRITS
	        $listeMedicamentsPrescrits = $this->getOrdonnanceTable()->getMedicamentsParIdOrdonnance($idOrdonnance);
	        $nbMedPrescrit = $listeMedicamentsPrescrits->count();
	    }else{
	        $nbMedPrescrit = null;
	        $listeMedicamentsPrescrits =null;
	        $duree_traitement = null;
	    }
	    
	    //POUR LA DEMANDE PRE-ANESTHESIQUE
	    //POUR LA DEMANDE PRE-ANESTHESIQUE
	    //POUR LA DEMANDE PRE-ANESTHESIQUE
	    $donneesDemandeVPA = $this->getDemandeVisitePreanesthesiqueTable()->getDemandeVisitePreanesthesique($id);
	    
	    $resultatVpa = null;
	    if($donneesDemandeVPA) {
	        $data['diagnostic_traitement_chirurgical'] = $donneesDemandeVPA['DIAGNOSTIC'];
	        $data['observation'] = $donneesDemandeVPA['OBSERVATION'];
	        $data['intervention_prevue'] = $donneesDemandeVPA['INTERVENTION_PREVUE'];
	        
	        $resultatVpa = $this->getResultatVpa()->getResultatVpa($donneesDemandeVPA['idVpa']);
	    }
	    
	    
	    /**** INSTRUMENTAL ****/
	    /**** INSTRUMENTAL ****/
	    /**** INSTRUMENTAL ****/
	    $traitement_instrumental = $this->getConsultationTable()->getTraitementsInstrumentaux($id);
	    
	    $data['endoscopieInterventionnelle'] = $traitement_instrumental['endoscopie_interventionnelle'];
	    $data['radiologieInterventionnelle'] = $traitement_instrumental['radiologie_interventionnelle'];
	    $data['cardiologieInterventionnelle'] = $traitement_instrumental['cardiologie_interventionnelle'];
	    $data['autresIntervention'] = $traitement_instrumental['autres_interventions'];
	    
	    //POUR LES COMPTES RENDU OPERATOIRE
	    //POUR LES COMPTES RENDU OPERATOIRE
	    $compte_rendu_chirurgical = $this->getConsultationTable()->getCompteRenduOperatoire(1, $id);
	    $data['note_compte_rendu_operatoire'] = $compte_rendu_chirurgical['note'];
	    $compte_rendu_instrumental = $this->getConsultationTable()->getCompteRenduOperatoire(2, $id);
	    $data['note_compte_rendu_operatoire_instrumental'] = $compte_rendu_instrumental['note'];
	    
	    //POUR LE TRANSFERT
	    //POUR LE TRANSFERT
	    //POUR LE TRANSFERT
	    // INSTANCIATION DU TRANSFERT
	    // RECUPERATION DE LA LISTE DES HOPITAUX
	    $hopital = $this->getTransfererPatientServiceTable ()->fetchHopital ();
	    
	    //LISTE DES HOPITAUX
	    $form->get ( 'hopital_accueil' )->setValueOptions ( $hopital );
	    // RECUPERATION DU SERVICE OU EST TRANSFERE LE PATIENT
	    $transfertPatientService = $this->getTransfererPatientServiceTable ()->getServicePatientTransfert($id);
	    
	    if( $transfertPatientService ){
	        $idService = $transfertPatientService['ID_SERVICE'];
	        // RECUPERATION DE L'HOPITAL DU SERVICE
	        $transfertPatientHopital = $this->getTransfererPatientServiceTable ()->getHopitalPatientTransfert($idService);
	        $idHopital = $transfertPatientHopital['ID_HOPITAL'];
	        // RECUPERATION DE LA LISTE DES SERVICES DE L'HOPITAL OU SE TROUVE LE SERVICE OU IL EST TRANSFERE
	        $serviceHopital = $this->getTransfererPatientServiceTable ()->fetchServiceWithHopital($idHopital);
	        
	        // LISTE DES SERVICES DE L'HOPITAL
	        $form->get ( 'service_accueil' )->setValueOptions ($serviceHopital);
	        
	        // SELECTION DE L'HOPITAL ET DU SERVICE SUR LES LISTES
	        $data['hopital_accueil'] = $idHopital;
	        $data['service_accueil'] = $idService;
	        $data['motif_transfert'] = $transfertPatientService['MOTIF_TRANSFERT'];
	        $hopitalSelect = 1;
	    }else {
	        $hopitalSelect = 0;
	        // RECUPERATION DE L'HOPITAL DU SERVICE
	        $transfertPatientHopital = $this->getTransfererPatientServiceTable ()->getHopitalPatientTransfert($IdDuService);
	        $idHopital = $transfertPatientHopital['ID_HOPITAL'];
	        $data['hopital_accueil'] = $idHopital;
	        // RECUPERATION DE LA LISTE DES SERVICES DE L'HOPITAL OU SE TROUVE LE SERVICE OU LE MEDECIN TRAVAILLE
	        $serviceHopital = $this->getTransfererPatientServiceTable ()->fetchServiceWithHopitalNotServiceActual($idHopital, $IdDuService);
	        // LISTE DES SERVICES DE L'HOPITAL
	        $form->get ( 'service_accueil' )->setValueOptions ($serviceHopital);
	    }
	    
	    //POUR LE RENDEZ VOUS
	    //POUR LE RENDEZ VOUS
	    //POUR LE RENDEZ VOUS
	    // RECUPERE LE RENDEZ VOUS
	    $leRendezVous = $this->getRvPatientConsTable()->getRendezVous($id);
	    
	    if($leRendezVous) {
	        $data['heure_rv'] = $leRendezVous->heure;
	        $data['date_rv']  = $this->controlDate->convertDate($leRendezVous->date);
	        $data['motif_rv'] = $leRendezVous->note;
	    }
	    // Pour recuper les bandelettes
	    $bandelettes = $this->getConsultationTable ()->getBandelette($id);
	    
	    //RECUPERATION DES ANTECEDENTS
	    //RECUPERATION DES ANTECEDENTS
	    //RECUPERATION DES ANTECEDENTS
	    $donneesAntecedentsPersonnels = $this->getAntecedantPersonnelTable()->getTableauAntecedentsPersonnels($id_pat);
	    $donneesAntecedentsFamiliaux = $this->getAntecedantsFamiliauxTable()->getTableauAntecedentsFamiliaux($id_pat);
	    
	    
	    //Recuperer les antecedents medicaux ajouter pour le patient
	    //Recuperer les antecedents medicaux ajouter pour le patient
	    $antMedPat = $this->getConsultationTable()->getAntecedentMedicauxPersonneParIdPatient($id_pat);
	    
	    //Recuperer les antecedents medicaux
	    //Recuperer les antecedents medicaux
	    $listeAntMed = $this->getConsultationTable()->getAntecedentsMedicaux();
	    
	    
	    //Recuperer la liste des actes
	    //Recuperer la liste des actes
	    $listeActes = $this->getConsultationTable()->getListeDesActes();
	    
	    //FIN ANTECEDENTS --- FIN ANTECEDENTS --- FIN ANTECEDENTS
	    //FIN ANTECEDENTS --- FIN ANTECEDENTS --- FIN ANTECEDENTS
	    
	    //POUR LES DEMANDES D'HOSPITALISATION
	    //POUR LES DEMANDES D'HOSPITALISATION
	    //POUR LES DEMANDES D'HOSPITALISATION
	    $donneesHospi = $this->getDemandeHospitalisationTable()->getDemandehospitalisationParIdcons($id);
	    if($donneesHospi){
	        $data['motif_hospitalisation'] = $donneesHospi->motif_demande_hospi;
	        $data['date_fin_hospitalisation_prevue'] = $this->controlDate->convertDate($donneesHospi->date_fin_prevue_hospi);
	    }
	    $form->populateValues ( array_merge($data,$bandelettes,$donneesAntecedentsPersonnels,$donneesAntecedentsFamiliaux) );
	    return array(
	        'id_cons' => $id,
	        'lesdetails' => $liste,
	        'form' => $form,
	        'nbMotifs' => $nbMotif,
	        'image' => $image,
	        'heure_cons' => $consult->heurecons,
	        'liste' => $listeConsultation,
	        'liste_med' => $listeMedicament,
	        'nb_med_prescrit' => $nbMedPrescrit,
	        'liste_med_prescrit' => $listeMedicamentsPrescrits,
	        'duree_traitement' => $duree_traitement,
	        'verifieRV' => $leRendezVous,
	        'listeDemandesMorphologiques' => $listeDemandesMorphologiques,
	        'listeDemandesBiologiques' => $listeDemandesBiologiques,
	        'listeDemandesActes' => $listeDemandesActes,
	        'hopitalSelect' =>$hopitalSelect,
	        'nbDiagnostics'=> $infoDiagnostics->count(),
	        'nbDonneesExamenPhysique' => $kPhysique,
	        'dateonly' => $consult->dateonly,
	        'temoin' => $bandelettes['temoin'],
	        'listeForme' => $listeForme,
	        'listetypeQuantiteMedicament'  => $listetypeQuantiteMedicament,
	        'donneesAntecedentsPersonnels' => $donneesAntecedentsPersonnels,
	        'donneesAntecedentsFamiliaux'  => $donneesAntecedentsFamiliaux,
	        'resultRV' => $resultRV,
	        'listeDemandesBioEff' => $listeDemandesBiologiquesEffectuer->count(),
	        'listeDemandesMorphoEff' => $listeDemandesMorphologiquesEffectuer->count(),
	        'resultatVpa' => $resultatVpa,
	        'listeHospitalisation' => $listeHospitalisation,
	        'tabInfoSurv' => $tabInfoSurv,
	        'tableauResultatsExamensBio' => $tableauResultatsExamensBio,
	        'listeDesExamensBiologiques' => $listeDesExamensBiologiques,
	        'listeDesExamensMorphologiques' => $listeDesExamensMorphologiques,
	        'listeAntMed' => $listeAntMed,
	        'antMedPat' => $antMedPat,
	        'nbAntMedPat' => $antMedPat->count(),
	        'listeActes' => $listeActes,
	    );
	    
	}
	
	
	//************************************************************************************
	//************************************************************************************
	//************************************************************************************
	public function demandeActeAction() {
	    
	    $id_cons = $this->params()->fromPost('id_cons');
	    $examensActe = $this->params()->fromPost('examensActe');
	    $notesActe = $this->params()->fromPost('notesActe');
	    
	    
	    $this->getDemandeActe()->addDemandeActe($id_cons, $examensActe, $notesActe);
	    
	    $this->getResponse()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html' );
	    return $this->getResponse ()->setContent(Json::encode (  ));
	}
	//************************************************************************************
	//************************************************************************************
	//************************************************************************************
	public function demandeExamenAction()
	{
	    $id_cons = $this->params()->fromPost('id_cons');
	    $examens = $this->params()->fromPost('examens');
	    $notes = $this->params()->fromPost('notes');
	    
	    
	    $this->demandeExamensTable()->saveDemandesExamensMorphologiques($id_cons, $examens, $notes);
	    
	    $this->getResponse()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html' );
	    return $this->getResponse ()->setContent(Json::encode (  ));
	}
	
	//************************************************************************************
	//************************************************************************************
	//************************************************************************************
	public function demandeExamenBiologiqueAction()
	{
	    $id_cons = $this->params()->fromPost('id_cons');
	    $examensBio = $this->params()->fromPost('examensBio');
	    $notesBio = $this->params()->fromPost('notesBio');
	    
	    
	    $this->demandeExamensTable()->saveDemandesExamensBiologiques($id_cons, $examensBio, $notesBio);
	    
	    $this->getResponse()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html' );
	    return $this->getResponse ()->setContent(Json::encode (  ));
	}
	
// 	
// 	    GESTION DES RENDEZ-VOUS DES PATIENTS
// 	   GESTION DES RENDEZ-VOUS DES PATIENTS
// 	   GESTION DES RENDEZ-VOUS DES PATIENTS
// 	   GESTION DES RENDEZ-VOUS DES PATIENTS
// 	   GESTION DES RENDEZ-VOUS DES PATIENTS
// 	
	
	public function infoPatientRvAction() {
	   
	    $id_pat = $this->params()->fromQuery ( 'id_patient', 0 );
	    $id_cons = $this->params()->fromQuery ( 'id_cons' );
	    
	    $this->layout ()->setTemplate ( 'layout/chururgie' );
	   
	    
	    $form = new ConsultationForm();
	    $form->populateValues(array('id_cons' => $id_cons));
	    
	   
	    
	    $user = $this->layout()->user;
	    $IdDuService = $user['IdService'];
	    
	    $patient = $this->getPatientTable ();
	    //var_dump( new \DateTime ( 'now' ));exit(); date d'aujourd'hui
	    
	    $unPatient = $patient->getInfoPatient( $id_pat );
	    //var_dump($unPatient);exit();
	    
	    $rv = $patient->getRvPatientParIdcons($id_cons);
	    $form->populateValues(array('delai_rv' => $rv['DELAI']));
	   // var_dump($rv);exit();
	    return array (
	        'form'=>$form,
	        'lesdetails' => $unPatient,
	        'image' => $patient->getPhoto ( $id_pat ),
	        'id_patient' => $unPatient['ID_PERSONNE'],
	        'date_enregistrement' => $unPatient['DATE_ENREGISTREMENT']
	    );
	}
	
	public function modifierInfosPatientRVAction() {
	    $id_pat = $this->params()->fromQuery ( 'id_patient', 0 );
	    $id_cons = $this->params()->fromQuery ( 'id_cons' );
	    
	    $this->layout ()->setTemplate ( 'layout/chururgie' );
	    
	    //var_dump($id_pat); exit();
	    
	    $form = new ConsultationForm();
	    $form->populateValues(array('id_cons' => $id_cons));
	    
	    //var_dump($form); exit();
	    
	    $user = $this->layout()->user;
	    $IdDuService = $user['IdService'];
	    
	  
	    
	    $patient = $this->getPatientTable ();
	    $unPatient = $patient->getInfoPatient( $id_pat );
	    $rv = $patient->getRvPatientParIdcons($id_cons);
	    
	    //var_dump($rv); exit();
	    $donneesRv = array(
	       // 'motif_rv' => $rv['NOTE'],
	        'date_rv' => (new DateHelper())->convertDate($rv['DATE']),
	        'heure_rv' => $rv['HEURE'],
	        'delai_rv' => $rv['DELAI'],
	    );
	    
	    $form->populateValues($donneesRv);
	   // var_dump(""); exit();
	    return array (
	        'form'=>$form,
	        'lesdetails' => $unPatient,
	        'image' => $patient->getPhoto ( $id_pat ),
	        'id_patient' => $unPatient['ID_PERSONNE'],
	        'date_enregistrement' => $unPatient['DATE_ENREGISTREMENT']
	    );
	}
	
	
	
	
	public function listeRendezVousAConfirmerAction() {
	    
	    //$formConsultation = new ConsultationForm();
	    $layout = $this->layout ();
	    $layout->setTemplate ( 'layout/chururgie' );
	    
	    $user = $this->layout()->user;
	    $idService = $user['IdService'];
	    $id_cons = $this->params()->fromPost('id_cons');
	    $id_patient = $this->params()->fromPost('id_patient');
	    
	    $leRendezVous = $this->getRvPatientConsTable()->getTousRVAConfirmer();
	}
	
	public function listeRendezVousAConfirmerAjaxAction() {
	    $output = $this->getRvPatientConsTable()->getTousRVAConfirmer();
	    
	    //$patient = $this->getPatientTable ();
	    return $this->getResponse ()->setContent ( Json::encode ( $output, array (
	        'enableJsonExprFinder' => true
	    ) ) );
	}
	
	public function listeRendezVousAjaxAction() {
	    $output = $this->getRvPatientConsTable()->getTousRV();
	    
	    //$patient = $this->getPatientTable ();
	    return $this->getResponse ()->setContent ( Json::encode ( $output, array (
	        'enableJsonExprFinder' => true
	    ) ) );
	}
	
	public function listeRendezVousAction() {
	
	    //$formConsultation = new ConsultationForm();
	    $layout = $this->layout ();
	    $layout->setTemplate ( 'layout/chururgie' );
	    
	    $user = $this->layout()->user;
	    $idService = $user['IdService'];
	    $id_cons = $this->params()->fromPost('id_cons');
	    $id_patient = $this->params()->fromPost('id_patient');
	      
	    $leRendezVous = $this->getRvPatientConsTable()->getTousRV();
	    
	    
	    
	    //$lespatients = $this->getConsultationTable()->listePatientsConsParMedecin ( $idService );
	    //
	    //RECUPERER TOUS LES PATIENTS AYANT UN RV aujourd'hui
	    
	    $tabPatientRV = $this->getConsultationTable ()->getPatientsRV($idService);
	   
	    //var_dump($tabPatientRV);exit();
	    //   		if($leRendezVous) {
	    
	    //   		var_dump($leRendezVous); exit();
	    //  		$data['heure_rv'] = $leRendezVous->heure;
	    //   		//$data['date_rv']  = $this->controlDate->convertDate($leRendezVous->date);
	    //   		$data['motif_rv'] = $leRendezVous->note;
	    //   		$data['delai_rv'] = $leRendezVous->note;
	    //   		}
	    
	    
	    if (isset ( $_POST ['terminer'] ))  // si formulaire soumis
	    {
	        $id_patient = $this->params()->fromPost('id_patient');
	        $date_RV_Recu = $this->params()->fromPost('date_rv');
	        
	        if($date_RV_Recu){
	            $date_RV = (new DateHelper())->convertDateInAnglais($date_RV_Recu);
	        }
	        
	        else{
	            $date_RV = $date_RV_Recu;
	        }
	        
	        $infos_rv = array(
	            'ID_CONS' => $id_cons,
	            'HEURE'   => $this->params()->fromPost('heure_rv'),
	            'DATE'    => $date_RV,
	            'DELAI'   => $this->params()->fromPost('delai_rv'),
	        );
	        //var_dump($infos_rv);exit();
	        
	        $this->getRvPatientConsTable()->updateRendezVous($infos_rv);
	        //var_dump('ssssss');exit();
	        if ($this->params()->fromPost ( 'terminer' ) == 'save') {
	            //VALIDER EN METTANT '1' DANS CONSPRISE Signifiant que le medecin a consulter le patient
	            //Ajouter l'id du medecin ayant consulter le patient
	            $valide = array (
	                'VALIDER' => 1,
	                'ID_CONS' => $id_cons,
	                'ID_MEDECIN' => $this->params()->fromPost('med_id_personne')
	            );
	            $this->getConsultationTable ()->validerConsultation ( $valide );
	        }
	        return $this->redirect ()->toRoute ( 'chururgie', array (
	            'action' => 'liste-rendez-vous'
	        ) );
	    }
	    
	
	    return new ViewModel ( array (
	        //'donnees' => $leRendezVous,
	        //'tabPatientRV' => $tabPatientRV,
	        //  				'listeRendezVous' => $patientsRV->getPatientsRV (),
	    //  				'form' => $formConsultation,
	    ) );
	    
	    
	    
	}
	
	
	
	
	public function rechercheVisualisationConsultationAction(){
	    
	    $this->layout ()->setTemplate ( 'layout/chururgie' );
	    
	    $user = $this->layout()->user;
	    $IdDuService = $user['IdService'];
	    $id_medecin = $user['id_personne'];
	    
	    $this->getDateHelper();
	    $id_pat = $this->params()->fromQuery ( 'id_patient', 0 );
	    $id = $this->params()->fromQuery ( 'id_cons' );
	    $form = new ConsultationForm();
	    
	    $liste = $this->getConsultationTable()->getInfoPatient ( $id_pat );
	    $image = $this->getConsultationTable()->getPhoto ( $id_pat );
	    
	    //GESTION DES ALERTES
	    //GESTION DES ALERTES
	    //GESTION DES ALERTES
	    //RECUPERER TOUS LES PATIENTS AYANT UN RV aujourd'hui
	    $tabPatientRV = $this->getConsultationTable()->getPatientsRV($IdDuService);
	    $resultRV = null;
	    if(array_key_exists($id_pat, $tabPatientRV)){
	        $resultRV = $tabPatientRV[ $id_pat ];
	    }
	    //var_dump("tester");exit();
	    //POUR LES CONSTANTES
	    //POUR LES CONSTANTES
	    //POUR LES CONSTANTES
	    $consult = $this->getConsultationTable ()->getConsult ( $id_pat );
	    $pos = strpos($consult->pression_arterielle, '/') ;
	    $tensionmaximale = substr($consult->pression_arterielle, 0, $pos);
	    $tensionminimale = substr($consult->pression_arterielle, $pos+1);
	    
	    $data = array (
	        'id_cons' => $consult->id_cons,
	        'id_medecin' => $consult->id_medecin,
	        'id_patient' => $consult->id_patient,
	        'date_cons' => $consult->date,
	        'poids' => $consult->poids,
	        'taille' => $consult->taille,
	        'temperature' => $consult->temperature,
	        'tensionmaximale' => $tensionmaximale,
	        'tensionminimale' => $tensionminimale,
	        'pouls' => $consult->pouls,
	        'frequence_respiratoire' => $consult->frequence_respiratoire,
	        'glycemie_capillaire' => $consult->glycemie_capillaire,
	    );
	    
	    //POUR LES MOTIFS D'ADMISSION
	    //POUR LES MOTIFS D'ADMISSION
	    //POUR LES MOTIFS D'ADMISSION
	    // instancier le motif d'admission et recup�rer l'enregistrement
	    $motif_admission = $this->getMotifAdmissionTable ()->getMotifAdmission ( $id );
	    $nbMotif = $this->getMotifAdmissionTable ()->nbMotifs ( $id );
	    
	    //POUR LES MOTIFS D'ADMISSION
	    $k = 1;
	    foreach ( $motif_admission as $Motifs ) {
	        $data ['motif_admission' . $k] = $Motifs ['Libelle_motif'];
	        $k ++;
	    }
	    
	    //POUR LES EXAMEN PHYSIQUES
	    //POUR LES EXAMEN PHYSIQUES
	    //POUR LES EXAMEN PHYSIQUES
	    $examen_physique = $this->getDonneesExamensPhysiquesTable()->getExamensPhysiques($id);
	    
	    //POUR LES EXAMEN PHYSIQUES
	    $kPhysique = 1;
	    foreach ($examen_physique as $Examen) {
	        $data['examen_donnee'.$kPhysique] = $Examen['libelle_examen'];
	        $kPhysique++;
	    }
	    
	    // POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
	    // POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
	    // POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
	    
	    $listeConsultation = $this->getConsultationTable ()->getConsultationPatientSaufActu($id_pat, $id);
	    
	    //Recuperer les informations sur le surveillant de service pour les consultations qui diff�rent des consultations prises lors des archives
	    $tabInfoSurv = array();
	    foreach ($listeConsultation as $listeCons){
	        if($listeCons['ID_SURVEILLANT']){
	            $tabInfoSurv [$listeCons['ID_CONS']] = $this->getConsultationTable ()->getInfosSurveillant($listeCons['ID_SURVEILLANT'])['PRENOM'].' '.$this->getConsultationTable ()->getInfosSurveillant($listeCons['ID_SURVEILLANT'])['NOM'];
	        }else{
	            $tabInfoSurv [$listeCons['ID_CONS']] = '_________';
	        }
	    }
	    $listeConsultation = $this->getConsultationTable ()->getConsultationPatientSaufActu($id_pat, $id);
	    
	    //*** Liste des Hospitalisations
	    $listeHospitalisation = $this->getDemandeHospitalisationTable()->getDemandeHospitalisationWithIdPatient($id_pat);
	    
	    //POUR LES EXAMENS COMPLEMENTAIRES
	    //POUR LES EXAMENS COMPLEMENTAIRES
	    //POUR LES EXAMENS COMPLEMENTAIRES
	    // DEMANDES DES EXAMENS COMPLEMENTAIRES
	    $listeDemandesMorphologiques = $this->demandeExamensTable()->getDemandeExamensMorphologiques($id);
	    $listeDemandesBiologiques = $this->demandeExamensTable()->getDemandeExamensBiologiques($id);
	    
	    ////RESULTATS DES EXAMENS BIOLOGIQUES DEJA EFFECTUES ET ENVOYER PAR LE BIOLOGISTE
	    $listeDemandesBiologiquesEffectuerEnvoyer = $this->demandeExamensTable()->getDemandeExamensBiologiquesEffectuesEnvoyer($id);
	    $listeDemandesBiologiquesEffectuer = $this->demandeExamensTable()->getDemandeExamensBiologiquesEffectues($id);
	    
	    $tableauResultatsExamensBio = array(
	        'temoinGSan' => 0,
	        'temoinHSan' => 0,
	        'temoinBHep' => 0,
	        'temoinBRen' => 0,
	        'temoinBHem' => 0,
	        'temoinBInf' => 0,
	    );
	    foreach ($listeDemandesBiologiquesEffectuerEnvoyer as $listeExamenBioEffectues){
	        if($listeExamenBioEffectues['idExamen'] == 1){
	            $data['groupe_sanguin'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['groupe_sanguin_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['groupe_sanguin_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['groupe_sanguin_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinGSan'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 2){
	            $data['hemogramme_sanguin'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['hemogramme_sanguin_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['hemogramme_sanguin_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['hemogramme_sanguin_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinHSan'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 3){
	            $data['bilan_hepatique'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['bilan_hepatique_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['bilan_hepatique_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['bilan_hepatique_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinBHep'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 4){
	            $data['bilan_renal'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['bilan_renal_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['bilan_renal_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['bilan_renal_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinBRen'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 5){
	            $data['bilan_hemolyse'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['bilan_hemolyse_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['bilan_hemolyse_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['bilan_hemolyse_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinBHem'] = 1;
	        }
	        if($listeExamenBioEffectues['idExamen'] == 6){
	            $data['bilan_inflammatoire'] =  $listeExamenBioEffectues['noteResultat'];
	            $tableauResultatsExamensBio['bilan_inflammatoire_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
	            $tableauResultatsExamensBio['bilan_inflammatoire_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
	            $tableauResultatsExamensBio['bilan_inflammatoire_conclusion'] = $listeExamenBioEffectues['conclusion'];
	            $tableauResultatsExamensBio['temoinBInf'] = 1;
	        }
	    }
	    
	    ////RESULTATS DES EXAMENS MORPHOLOGIQUE
	    $examen_morphologique = $this->getNotesExamensMorphologiquesTable()->getNotesExamensMorphologiques($id);
	    
	    $data['radio'] = $examen_morphologique['radio'];
	    $data['ecographie'] = $examen_morphologique['ecographie'];
	    $data['fibrocospie'] = $examen_morphologique['fibroscopie'];
	    $data['scanner'] = $examen_morphologique['scanner'];
	    $data['irm'] = $examen_morphologique['irm'];
	    
	    ////RESULTATS DES EXAMENS MORPHOLOGIQUES DEJA EFFECTUES ET ENVOYER PAR LE BIOLOGISTE
	    $listeDemandesMorphologiquesEffectuer = $this->demandeExamensTable()->getDemandeExamensMorphologiquesEffectues($id);
	    
	    //DIAGNOSTICS
	    //DIAGNOSTICS
	    //DIAGNOSTICS
	    $infoDiagnostics = $this->getDiagnosticsTable()->getDiagnostics($id);
	    // POUR LES DIAGNOSTICS
	    $k = 1;
	    foreach ($infoDiagnostics as $diagnos){
	        $data['diagnostic'.$k] = $diagnos['libelle_diagnostics'];
	        $k++;
	    }
	    
	    //TRAITEMENT (Ordonnance) *********************************************************
	    //TRAITEMENT (Ordonnance) *********************************************************
	    //TRAITEMENT (Ordonnance) *********************************************************
	    
	    //POUR LES MEDICAMENTS
	    //POUR LES MEDICAMENTS
	    //POUR LES MEDICAMENTS
	    // INSTANCIATION DES MEDICAMENTS de l'ordonnance
	    $listeMedicament = $this->getConsultationTable()->listeDeTousLesMedicaments();
	    $listeForme = $this->getConsultationTable()->formesMedicaments();
	    $listetypeQuantiteMedicament = $this->getConsultationTable()->typeQuantiteMedicaments();
	    
	    // INSTANTIATION DE L'ORDONNANCE
	    $infoOrdonnance = $this->getOrdonnanceTable()->getOrdonnanceNonHospi($id);
	    
	    if($infoOrdonnance) {
	        $idOrdonnance = $infoOrdonnance->id_document;
	        $duree_traitement = $infoOrdonnance->duree_traitement;
	        //LISTE DES MEDICAMENTS PRESCRITS
	        $listeMedicamentsPrescrits = $this->getOrdonnanceTable()->getMedicamentsParIdOrdonnance($idOrdonnance);
	        $nbMedPrescrit = $listeMedicamentsPrescrits->count();
	    }else{
	        $nbMedPrescrit = null;
	        $listeMedicamentsPrescrits =null;
	        $duree_traitement = null;
	    }
	    
	    //POUR LA DEMANDE PRE-ANESTHESIQUE
	    //POUR LA DEMANDE PRE-ANESTHESIQUE
	    //POUR LA DEMANDE PRE-ANESTHESIQUE
	    $donneesDemandeVPA = $this->getDemandeVisitePreanesthesiqueTable()->getDemandeVisitePreanesthesique($id);
	    
	    $resultatVpa = null;
	    if($donneesDemandeVPA) {
	        $data['diagnostic_traitement_chirurgical'] = $donneesDemandeVPA['DIAGNOSTIC'];
	        $data['observation'] = $donneesDemandeVPA['OBSERVATION'];
	        $data['intervention_prevue'] = $donneesDemandeVPA['INTERVENTION_PREVUE'];
	        
	        $resultatVpa = $this->getResultatVpa()->getResultatVpa($donneesDemandeVPA['idVpa']);
	    }
	    
	    /**** INSTRUMENTAL ****/
	    /**** INSTRUMENTAL ****/
	    /**** INSTRUMENTAL ****/
	    $traitement_instrumental = $this->getConsultationTable()->getTraitementsInstrumentaux($id);
	    
	    $data['endoscopieInterventionnelle'] = $traitement_instrumental['endoscopie_interventionnelle'];
	    $data['radiologieInterventionnelle'] = $traitement_instrumental['radiologie_interventionnelle'];
	    $data['cardiologieInterventionnelle'] = $traitement_instrumental['cardiologie_interventionnelle'];
	    $data['autresIntervention'] = $traitement_instrumental['autres_interventions'];
	    
	    //POUR LE TRANSFERT
	    //POUR LE TRANSFERT
	    //POUR LE TRANSFERT
	    // INSTANCIATION DU TRANSFERT
	    // RECUPERATION DE LA LISTE DES HOPITAUX
	    $hopital = $this->getTransfererPatientServiceTable ()->fetchHopital ();
	    
	    //LISTE DES HOPITAUX
	    $form->get ( 'hopital_accueil' )->setValueOptions ( $hopital );
	    // RECUPERATION DU SERVICE OU EST TRANSFERE LE PATIENT
	    $transfertPatientService = $this->getTransfererPatientServiceTable ()->getServicePatientTransfert($id);
	    
	    if( $transfertPatientService ){
	        $idService = $transfertPatientService['ID_SERVICE'];
	        // RECUPERATION DE L'HOPITAL DU SERVICE
	        $transfertPatientHopital = $this->getTransfererPatientServiceTable ()->getHopitalPatientTransfert($idService);
	        $idHopital = $transfertPatientHopital['ID_HOPITAL'];
	        // RECUPERATION DE LA LISTE DES SERVICES DE L'HOPITAL OU SE TROUVE LE SERVICE OU IL EST TRANSFERE
	        $serviceHopital = $this->getTransfererPatientServiceTable ()->fetchServiceWithHopital($idHopital);
	        
	        // LISTE DES SERVICES DE L'HOPITAL
	        $form->get ( 'service_accueil' )->setValueOptions ($serviceHopital);
	        
	        // SELECTION DE L'HOPITAL ET DU SERVICE SUR LES LISTES
	        $data['hopital_accueil'] = $idHopital;
	        $data['service_accueil'] = $idService;
	        $data['motif_transfert'] = $transfertPatientService['MOTIF_TRANSFERT'];
	        $hopitalSelect = 1;
	    }else {
	        $hopitalSelect = 0;
	        // RECUPERATION DE L'HOPITAL DU SERVICE
	        $transfertPatientHopital = $this->getTransfererPatientServiceTable ()->getHopitalPatientTransfert($IdDuService);
	        $idHopital = $transfertPatientHopital['ID_HOPITAL'];
	        $data['hopital_accueil'] = $idHopital;
	        // RECUPERATION DE LA LISTE DES SERVICES DE L'HOPITAL OU SE TROUVE LE SERVICE OU LE MEDECIN TRAVAILLE
	        $serviceHopital = $this->getTransfererPatientServiceTable ()->fetchServiceWithHopitalNotServiceActual($idHopital, $IdDuService);
	        // LISTE DES SERVICES DE L'HOPITAL
	        $form->get ( 'service_accueil' )->setValueOptions ($serviceHopital);
	    }
	    
	    //POUR LE RENDEZ VOUS
	    //POUR LE RENDEZ VOUS
	    //POUR LE RENDEZ VOUS
	    // RECUPERE LE RENDEZ VOUS
	    $leRendezVous = $this->getRvPatientConsTable()->getRendezVous($id);
	    
	    if($leRendezVous) {
	        $data['heure_rv'] = $leRendezVous->heure;
	        $data['date_rv']  = $this->controlDate->convertDate($leRendezVous->date);
	        $data['motif_rv'] = $leRendezVous->note;
	    }
	    // Pour recuper les bandelettes
	    $bandelettes = $this->getConsultationTable ()->getBandelette($id);
	    
	    //RECUPERATION DES ANTECEDENTS
	    //RECUPERATION DES ANTECEDENTS
	    //RECUPERATION DES ANTECEDENTS
	    $donneesAntecedentsPersonnels = $this->getAntecedantPersonnelTable()->getTableauAntecedentsPersonnels($id_pat);
	    $donneesAntecedentsFamiliaux = $this->getAntecedantsFamiliauxTable()->getTableauAntecedentsFamiliaux($id_pat);
	    
	    //Recuperer les antecedents medicaux ajouter pour le patient
	    //Recuperer les antecedents medicaux ajouter pour le patient
	    $antMedPat = $this->getConsultationTable()->getAntecedentMedicauxPersonneParIdPatient($id_pat);
	    
	    //Recuperer les antecedents medicaux
	    //Recuperer les antecedents medicaux
	    $listeAntMed = $this->getConsultationTable()->getAntecedentsMedicaux();
	    
	    
	    //Recuperer la liste des actes
	    //Recuperer la liste des actes
	   // $listeActes = $this->getConsultationTable()->getListeDesActes();
	    $listeDemandesActes = $this->getDemandeActe()->getDemandeActe($id);
	    
	    //FIN ANTECEDENTS --- FIN ANTECEDENTS --- FIN ANTECEDENTS
	    //FIN ANTECEDENTS --- FIN ANTECEDENTS --- FIN ANTECEDENTS
	    
	    //Liste des examens biologiques
	    $listeDesExamensBiologiques = $this->demandeExamensTable()->getDemandeDesExamensBiologiques();
	    //Liste des examens Morphologiques 
	    $listeDesExamensMorphologiques = $this->demandeExamensTable()->getDemandeDesExamensMorphologiques();
	    
	    //Liste des examens Fonctionnels
	    $listeActes = $this->demandeExamensTable()->getDemandeDesExamensFonctionnels();
	    //POUR LES DEMANDES D'HOSPITALISATION
	    //POUR LES DEMANDES D'HOSPITALISATION
	    //POUR LES DEMANDES D'HOSPITALISATION
	    $donneesHospi = $this->getDemandeHospitalisationTable()->getDemandehospitalisationParIdcons($id);
	    if($donneesHospi){
	        $data['motif_hospitalisation'] = $donneesHospi->motif_demande_hospi;
	        $data['date_fin_hospitalisation_prevue'] = $this->controlDate->convertDate($donneesHospi->date_fin_prevue_hospi);
	    }
	    $form->populateValues ( array_merge($data,$bandelettes,$donneesAntecedentsPersonnels,$donneesAntecedentsFamiliaux) );
	    return array(
	        'id_cons' => $id,
	        'lesdetails' => $liste,
	        'form' => $form,
	        'nbMotifs' => $nbMotif,
	        'image' => $image,
	        'heure_cons' => $consult->heurecons,
	        'liste' => $listeConsultation,
	        'liste_med' => $listeMedicament,
	        'nb_med_prescrit' => $nbMedPrescrit,
	        'liste_med_prescrit' => $listeMedicamentsPrescrits,
	        'duree_traitement' => $duree_traitement,
	        'verifieRV' => $leRendezVous,
	        'listeDemandesMorphologiques' => $listeDemandesMorphologiques,
	        'listeDemandesBiologiques' => $listeDemandesBiologiques,
	        'hopitalSelect' =>$hopitalSelect,
	        'nbDiagnostics'=> $infoDiagnostics->count(),
	        'nbDonneesExamenPhysique' => $kPhysique,
	        'dateonly' => $consult->dateonly,
	        'temoin' => $bandelettes['temoin'],
	        'listeForme' => $listeForme,
	        'listetypeQuantiteMedicament'  => $listetypeQuantiteMedicament,
	        'donneesAntecedentsPersonnels' => $donneesAntecedentsPersonnels,
	        'donneesAntecedentsFamiliaux'  => $donneesAntecedentsFamiliaux,
	        'resultRV' => $resultRV,
	        'listeDemandesBioEff' => $listeDemandesBiologiquesEffectuer->count(),
	        'listeDemandesMorphoEff' => $listeDemandesMorphologiquesEffectuer->count(),
	        'resultatVpa' => $resultatVpa,
	        'listeHospitalisation' => $listeHospitalisation,
	        'tabInfoSurv' => $tabInfoSurv,
	        'tableauResultatsExamensBio' => $tableauResultatsExamensBio,
	        'listeAntMed' => $listeAntMed,
	        'antMedPat' => $antMedPat,
	        'nbAntMedPat' => $antMedPat->count(),
	        'listeDemandesActes' => $listeDemandesActes,
	        'listeActes' => $listeActes,
	        'listeDesExamensBiologiques' => $listeDesExamensBiologiques,
	        'listeDesExamensMorphologiques' => $listeDesExamensMorphologiques,
	    );
	}
	
	
	public function espaceRechercheMedAction() {
	    $this->layout ()->setTemplate ( 'layout/chururgie' );
	    //var_dump( uniqid(md5(rand()), true) ); //nombre al�atoire
	    $user = $this->layout()->user;
	    $IdDuService = $user['IdService'];
	  
	    $tab = $this->getConsultationTable()->listePatientsConsMedecin ( $IdDuService );
	    
	    return new ViewModel ( array (
	        'donnees' => $tab
	    ) );
	}
	
	/*
	 * Mise a jour complement consultation
	 * Mise a jour complement consultation
	 * Mise a jour complement consultation
	 *
	 * */
	
	public function updateComplementConsultationAction(){
	
		$this->getDateHelper();
		$id_cons = $this->params()->fromPost('id_cons');
		$id_patient = $this->params()->fromPost('id_patient');
		
		
		//
		$user = $this->layout()->user;
		$IdDuService = $user['IdService'];
		$id_medecin = $user['id_personne'];
		
		
		
		
		//**********-- MODIFICATION DES CONSTANTES --********
		//**********-- MODIFICATION DES CONSTANTES --********
		//**********-- MODIFICATION DES CONSTANTES --********
		$form = new ConsultationForm ();
		$formData = $this->getRequest ()->getPost ();
		$form->setData ( $form );
		$id_admission = $this->params()->fromPost('id_admission',0);
		
		//var_dump($id_patient);exit();
		//consultation
		//var_dump($formData);exit();
		$this->getConsultationTable()->addConsultation($formData,$IdDuService, $id_medecin,$id_admission);
		//var_dump($formData);exit();
		// les antecedents medicaux du patient a ajouter addAntecedentMedicauxPersonne
		$this->getConsultationTable()->addAntecedentMedicaux($formData);
		
		$this->getConsultationTable()->addAntecedentMedicauxPersonne($formData);
	
		
		// mettre a jour les motifs d'admission
		$this->getMotifAdmissionTable ()->deleteMotifAdmission ( $id_cons );
		
		$this->getMotifAdmissionTable ()->addMotifAdmission ( $form );
		
		//mettre a jour la consultation
		$this->getConsultationTable ()->updateConsultation ( $form );
		
		//Recuperer les donnees sur les bandelettes urinaires
		//Recuperer les donnees sur les bandelettes urinaires
		$bandelettes = array(
				'id_cons' => $id_cons,
				'albumine' => $this->params()->fromPost('albumine'),
				'sucre' => $this->params()->fromPost('sucre'),
				'corpscetonique' => $this->params()->fromPost('corpscetonique'),
				'croixalbumine' => $this->params()->fromPost('croixalbumine'),
				'croixsucre' => $this->params()->fromPost('croixsucre'),
				'croixcorpscetonique' => $this->params()->fromPost('croixcorpscetonique'),
		);
		
		//mettre a jour les bandelettes urinaires
		$this->getConsultationTable ()->deleteBandelette($id_cons);
		
		$this->getConsultationTable ()->addBandelette($bandelettes);
		
		//POUR LES EXAMENS PHYSIQUES
		//POUR LES EXAMENS PHYSIQUES
		//POUR LES EXAMENS PHYSIQUES
		$info_donnees_examen_physique = array(
				'id_cons' => $id_cons,
				'donnee1' => $this->params()->fromPost('examen_donnee1'),
				'donnee2' => $this->params()->fromPost('examen_donnee2'),
				'donnee3' => $this->params()->fromPost('examen_donnee3'),
				'donnee4' => $this->params()->fromPost('examen_donnee4'),
				'donnee5' => $this->params()->fromPost('examen_donnee5')
		);
		$this->getDonneesExamensPhysiquesTable()->updateExamenPhysique($info_donnees_examen_physique);
		
		//POUR LES ANTECEDENTS ANTECEDENTS ANTECEDENTS
		//POUR LES ANTECEDENTS ANTECEDENTS ANTECEDENTS
		//POUR LES ANTECEDENTS ANTECEDENTS ANTECEDENTS
		$donneesDesAntecedents = array(
				//**=== ANTECEDENTS PERSONNELS
				//**=== ANTECEDENTS PERSONNELS
				//LES HABITUDES DE VIE DU PATIENTS
				/*Alcoolique*/
				'AlcooliqueHV' => $this->params()->fromPost('AlcooliqueHV'),
				'DateDebutAlcooliqueHV' => $this->params()->fromPost('DateDebutAlcooliqueHV'),
				'DateFinAlcooliqueHV' => $this->params()->fromPost('DateFinAlcooliqueHV'),
				/*Fumeur*/
				'FumeurHV' => $this->params()->fromPost('FumeurHV'),
				'DateDebutFumeurHV' => $this->params()->fromPost('DateDebutFumeurHV'),
				'DateFinFumeurHV' => $this->params()->fromPost('DateFinFumeurHV'),
				'nbPaquetFumeurHV' => $this->params()->fromPost('nbPaquetFumeurHV'),
				/*Droguer*/
				'DroguerHV' => $this->params()->fromPost('DroguerHV'),
				'DateDebutDroguerHV' => $this->params()->fromPost('DateDebutDroguerHV'),
				'DateFinDroguerHV' => $this->params()->fromPost('DateFinDroguerHV'),
					
				//LES ANTECEDENTS MEDICAUX
		'DiabeteAM' => $this->params()->fromPost('DiabeteAM'),
		'htaAM' => $this->params()->fromPost('htaAM'),
		'drepanocytoseAM' => $this->params()->fromPost('drepanocytoseAM'),
		'dislipidemieAM' => $this->params()->fromPost('dislipidemieAM'),
		'asthmeAM' => $this->params()->fromPost('asthmeAM'),
			
		//GYNECO-OBSTETRIQUE
		/*Menarche*/
		'MenarcheGO' => $this->params()->fromPost('MenarcheGO'),
		'NoteMenarcheGO' => $this->params()->fromPost('NoteMenarcheGO'),
		/*Gestite*/
		'GestiteGO' => $this->params()->fromPost('GestiteGO'),
		'NoteGestiteGO' => $this->params()->fromPost('NoteGestiteGO'),
		/*Parite*/
		'PariteGO' => $this->params()->fromPost('PariteGO'),
		'NotePariteGO' => $this->params()->fromPost('NotePariteGO'),
		/*Cycle*/
		'CycleGO' => $this->params()->fromPost('CycleGO'),
		'DureeCycleGO' => $this->params()->fromPost('DureeCycleGO'),
		'RegulariteCycleGO' => $this->params()->fromPost('RegulariteCycleGO'),
		'DysmenorrheeCycleGO' => $this->params()->fromPost('DysmenorrheeCycleGO'),
			
		//**=== ANTECEDENTS FAMILIAUX
		//**=== ANTECEDENTS FAMILIAUX
		'DiabeteAF' => $this->params()->fromPost('DiabeteAF'),
		'NoteDiabeteAF' => $this->params()->fromPost('NoteDiabeteAF'),
		'DrepanocytoseAF' => $this->params()->fromPost('DrepanocytoseAF'),
		'NoteDrepanocytoseAF' => $this->params()->fromPost('NoteDrepanocytoseAF'),
		'htaAF' => $this->params()->fromPost('htaAF'),
		'NoteHtaAF' => $this->params()->fromPost('NoteHtaAF'),
		);
		
		$id_personne = $this->getAntecedantPersonnelTable()->getIdPersonneParIdCons($id_cons);
		$this->getAntecedantPersonnelTable()->addAntecedentsPersonnels($donneesDesAntecedents, $id_personne, $id_medecin);
		$this->getAntecedantsFamiliauxTable()->addAntecedentsFamiliaux($donneesDesAntecedents, $id_personne, $id_medecin);
	
		//POUR LES RESULTATS DES EXAMENS MORPHOLOGIQUES
		//POUR LES RESULTATS DES EXAMENS MORPHOLOGIQUES
		//POUR LES RESULTATS DES EXAMENS MORPHOLOGIQUES
		
		$info_examen_morphologique = array(
				'id_cons'=> $id_cons,
				'8'  => $this->params()->fromPost('radio_'),
				'9'  => $this->params()->fromPost('ecographie_'),
				'12' => $this->params()->fromPost('irm_'),
				'11' => $this->params()->fromPost('scanner_'),
				'10' => $this->params()->fromPost('fibroscopie_'),
		);
	
		$this->getNotesExamensMorphologiquesTable()->updateNotesExamensMorphologiques($info_examen_morphologique);
		
		//POUR LES DIAGNOSTICS
		//POUR LES DIAGNOSTICS
		//POUR LES DIAGNOSTICS
		$info_diagnostics = array(
				'id_cons' => $id_cons,
				'diagnostic1' => $this->params()->fromPost('diagnostic1'),
				'diagnostic2' => $this->params()->fromPost('diagnostic2'),
				'diagnostic3' => $this->params()->fromPost('diagnostic3'),
				'diagnostic4' => $this->params()->fromPost('diagnostic4'),
		);
		
		$this->getDiagnosticsTable()->updateDiagnostics($info_diagnostics);
		//POUR LES PATHOLOGIES
		//POUR LES PATHOLOGIES
		//POUR LES PATHOLOGIES
// 		$info_diagnostics = array(
// 		    'id_cons' => $id_cons,
// 		    'typepathologie1' => $this->params()->fromPost('typepathologie1'),
// 		    'typepathologie2' => $this->params()->fromPost('typepathologie2'),
// 		    'typepathologie3' => $this->params()->fromPost('typepathologie3'),
// 		    'typepathologie4' => $this->params()->fromPost('typepathologie4'),
// 		    'typepathologie5' => $this->params()->fromPost('typepathologie5'),
// 		    'typepathologie6' => $this->params()->fromPost('typepathologie6'),
// 		);
		
		//$this->getDiagnosticsTable()->updateDiagnostics($info_diagnostics);
		
		
		//POUR LES TRAITEMENTS
		//POUR LES TRAITEMENTS
		//POUR LES TRAITEMENTS
		/**** MEDICAUX ****/
		/**** MEDICAUX ****/
		$dureeTraitement = $this->params()->fromPost('duree_traitement_ord');
		$donnees = array('id_cons' => $id_cons, 'duree_traitement' => $dureeTraitement);
	
		$Consommable = $this->getOrdonConsommableTable();
		$tab = array();
		$j = 1;
	
		$nomMedicament = "";
		$formeMedicament = "";
		$quantiteMedicament = "";
		for($i = 1 ; $i < 10 ; $i++ ){
			if($this->params()->fromPost("medicament_0".$i)){
	
				$nomMedicament = $this->params()->fromPost("medicament_0".$i);
				$formeMedicament = $this->params()->fromPost("forme_".$i);
				$quantiteMedicament = $this->params()->fromPost("quantite_".$i);
	
				if($this->params()->fromPost("medicament_0".$i)){
						
					$result = $Consommable->getMedicamentByName($this->params()->fromPost("medicament_0".$i))['ID_MATERIEL'];
						
					if($result){
						$tab[$j++] = $result;
						$tab[$j++] = $formeMedicament; $Consommable->addFormes($formeMedicament);
						$tab[$j++] = $this->params()->fromPost("nb_medicament_".$i);
						$tab[$j++] = $quantiteMedicament; $Consommable->addQuantites($quantiteMedicament);
					} else {
						$idMedicaments = $Consommable->addMedicaments($nomMedicament);
						$tab[$j++] = $idMedicaments;
						$tab[$j++] = $formeMedicament; $Consommable->addFormes($formeMedicament);
						$tab[$j++] = $this->params()->fromPost("nb_medicament_".$i);
						$tab[$j++] = $quantiteMedicament; $Consommable->addQuantites($quantiteMedicament);
					}
				}
	
			}
		}
		
		/*Mettre a jour la duree du traitement de l'ordonnance*/
		$idOrdonnance = $this->getOrdonnanceTable()->updateOrdonnance($tab, $donnees);
		
		/*Mettre a jour les medicaments*/
		$resultat = $Consommable->updateOrdonConsommable($tab, $idOrdonnance, $nomMedicament);
	
		/*si aucun m�dicament n'est ajout� ($resultat = false) on supprime l'ordonnance*/
		if($resultat == false){ $this->getOrdonnanceTable()->deleteOrdonnance($idOrdonnance);}
		
		
		/**** Pathologie ****/
		/**** Pathologie ****/
		
		
		$typePathologie = "";
		
		
		$tab = array();
		$j = 1;
		for($i = 1 ; $i < 10 ; $i++ ){
		    
		    if($this->params()->fromPost("typepathologie".$i)){
		        
		        $typePathologie = $this->params()->fromPost("typepathologie".$i);
		        
		        
		        if($typePathologie){
		            
		            $result1 = $this->getConsultationTable()->getPathologiesByName($typePathologie);
		            
		            if($result1){
		                $tab[$j++] = $result1["id_type_pathologie"];
		                //
		            } else {
		                $idPathologie=  $this->getConsultationTable()->addPathologie($this->params()->fromPost("typepathologie".$i));
		                $tab[$j++] = $idPathologie;
		                //  $tab[$j++] = $typePathologie;
		                //  $this->getConsultationTable()->addConsPatho($id_cons,$tab);
		                
		            }
		        }
		        
		    }
		}
		
		for($k=1;$k<count($tab);$k++){
		    // var_dump($tab[$k++]);exit();
		    $this->getConsultationTable()->addConsPatho($id_cons,$tab[$k++]);
		}
		
	
		/**** CHIRURGICAUX ****/
		/**** CHIRURGICAUX ****/
		/**** CHIRURGICAUX ****/
		$infoDemande = array(
				'diagnostic' => $this->params()->fromPost("diagnostic_traitement_chirurgical"),
				'intervention_prevue' => $this->params()->fromPost("intervention_prevue"),
				'observation' => $this->params()->fromPost("observation"),
				'ID_CONS'=>$id_cons
		);
		
		$this->getDemandeVisitePreanesthesiqueTable()->updateDemandeVisitePreanesthesique($infoDemande);
		
		/**** INSTRUMENTAL ****/
		/**** INSTRUMENTAL ****/
		/**** INSTRUMENTAL ****/
		$traitement_instrumental = array(
				'id_cons' => $id_cons,
				'endoscopie_interventionnelle' => $this->params()->fromPost('endoscopieInterventionnelle'),
				'radiologie_interventionnelle' => $this->params()->fromPost('radiologieInterventionnelle'),
				'cardiologie_interventionnelle' => $this->params()->fromPost('cardiologieInterventionnelle'),
				'autres_interventions' => $this->params()->fromPost('autresIntervention'),
		);
		
		
		$this->getConsultationTable()->addTraitementsInstrumentaux($traitement_instrumental);
		
		//POUR LES COMPTES RENDU DES TRAITEMENTS
		//POUR LES COMPTES RENDU DES TRAITEMENTS
		$note_compte_rendu1 = $this->params()->fromPost('note_compte_rendu_operatoire');
		$note_compte_rendu2 = $this->params()->fromPost('note_compte_rendu_operatoire_instrumental');
	
		$this->getConsultationTable()->addCompteRenduOperatoire($note_compte_rendu1, 1, $id_cons);
		$this->getConsultationTable()->addCompteRenduOperatoire($note_compte_rendu2, 2, $id_cons);
		
		//POUR LES RENDEZ VOUS
		//POUR LES RENDEZ VOUS
		//POUR LES RENDEZ VOUS
		$id_patient = $this->params()->fromPost('id_patient');
		$date_RV_Recu = $this->params()->fromPost('date_rv');
		$delai_rv = $this->params()->fromPost('delai_rv');
		
		if($date_RV_Recu){
			$date_RV = $this->dateHelper->convertDateInAnglais($date_RV_Recu);
		}
		else{
			$date_RV = $date_RV_Recu;
		}
		$infos_rv = array(
				'ID_CONS' => $id_cons,
				'NOTE'    => $this->params()->fromPost('motif_rv'),
				'HEURE'   => $this->params()->fromPost('heure_rv'),
		          'DELAI'   => $this->params()->fromPost('delai_rv'),
				'DATE'    => $date_RV,
		);
		
		$this->getRvPatientConsTable()->updateRendezVous($infos_rv);
		
		//POUR LES TRANSFERT
		//POUR LES TRANSFERT
		//POUR LES TRANSFERT
		$info_transfert = array(
				'ID_SERVICE'      => $this->params()->fromPost('id_service'),
				'ID_MEDECIN' => $this->params()->fromPost('med_id_personne'),
				'MOTIF_TRANSFERT' => $this->params()->fromPost('motif_transfert'),
				'ID_CONS' => $id_cons
		);
		
		$this->getTransfererPatientServiceTable()->updateTransfertPatientService($info_transfert);
		
		//POUR LES HOSPITALISATION
		//POUR LES HOSPITALISATION
		//POUR LES HOSPITALISATION
		$today = new \DateTime ();
		$dateAujourdhui = $today->format ( 'Y-m-d H:i:s' );
		$infoDemandeHospitalisation = array(
				'motif_demande_hospi' => $this->params()->fromPost('motif_hospitalisation'),
				'date_demande_hospi' => $dateAujourdhui,
				'date_fin_prevue_hospi' => $this->dateHelper->convertDateInAnglais($this->params()->fromPost('date_fin_hospitalisation_prevue')),
				'id_cons' => $id_cons,
		);

		
		$this->getDemandeHospitalisationTable()->saveDemandehospitalisation($infoDemandeHospitalisation);
	
		//POUR LA PAGE complement-consultation
		//POUR LA PAGE complement-consultation
		//POUR LA PAGE complement-consultation
		if ($this->params ()->fromPost ( 'terminer' ) == 'save') {
	
			//VALIDER EN METTANT '1' DANS CONSPRISE Signifiant que le medecin a consulter le patient
			//Ajouter l'id du medecin ayant consulter le patient
			$valide = array (
					'VALIDER' => 1,
					'ID_CONS' => $id_cons,
					'ID_MEDECIN' => $id_medecin,
						
			);
		
			$this->getConsultationTable ()->validerConsultation ( $valide );
		}
	
		return $this->redirect ()->toRoute ( 'chururgie', array (
				'action' => 'consultation-medecin'
		) );
	}
	
	
	
	
	
	public function listePatientAjaxAction() {
		$output = $this->getPatientTable ()->getListePatient ();
		return $this->getResponse ()->setContent ( Json::encode ( $output, array (
				'enableJsonExprFinder' => true 
		) ) );
	}
	
	public function listeAdmissionAjaxAction() {
		$patient = $this->getPatientTable ();	
		$output = $patient->laListePatientsAjax();
		return $this->getResponse ()->setContent ( Json::encode ( $output, array (
				'enableJsonExprFinder' => true 
		) ) );
	}
	public function supprimerAdmissionAction(){
		if ($this->getRequest()->isPost()){
			$id = (int)$this->params()->fromPost ('id');
			$idPatient = (int)$this->params()->fromPost ('idPatient');
			$idService = (int)$this->params()->fromPost ('idService');
		
			$resultat = $this->getAdmissionTable()->deleteAdmissionPatient($id, $idPatient, $idService);
	
			//$nb = $this->getAdmissionTable()->nbAdmission();
			//$html ="$nb au total";
				
			$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
			return $this->getResponse()->setContent(Json::encode($resultat));
		}
	}
	
	//CONSULTATION DU MEDECIN DE LA CHURURGIE GENERALE
	
	
	
	public function servicesAction()
	{
		$user = $this->layout()->user;
		$IdDuService = $user['IdService'];
	
		$id = (int)$this->params()->fromPost ('id');
	
		if ($this->getRequest()->isPost()){
			$liste_select = "";
			foreach($this->getServiceTable()->getServiceHopital($id, $IdDuService) as $listeServices){
				$liste_select.= "<option value=".$listeServices['Id_service'].">".$listeServices['Nom_service']."</option>";
			}
	
			$this->getResponse()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html' );
			return $this->getResponse ()->setContent(Json::encode ( $liste_select));
		}
	
	}
	
	//***$$$$***
	public function visualisationConsultationAction(){
	
		$this->layout ()->setTemplate ( 'layout/chururgie' );
	
		$user = $this->layout()->user;
		$IdDuService = $user['IdService'];
		$id_medecin = $user['id_personne'];
			
		$this->getDateHelper();
		$id_pat = $this->params()->fromQuery ( 'id_patient', 0 );
		$id = $this->params()->fromQuery ( 'id_cons' );
		$form = new ConsultationForm();
	
		$liste = $this->getConsultationTable()->getInfoPatient ( $id_pat );
		$image = $this->getConsultationTable()->getPhoto ( $id_pat );
		
		//GESTION DES ALERTES
		//GESTION DES ALERTES
		//GESTION DES ALERTES
		//RECUPERER TOUS LES PATIENTS AYANT UN RV aujourd'hui
		$tabPatientRV = $this->getConsultationTable()->getPatientsRV($IdDuService);
		$resultRV = null;
		if(array_key_exists($id_pat, $tabPatientRV)){
			$resultRV = $tabPatientRV[ $id_pat ];
		}
		
		//POUR LES CONSTANTES
		//POUR LES CONSTANTES
		//POUR LES CONSTANTES
		$consult = $this->getConsultationTable ()->getConsult ( $id_pat );
		$pos = strpos($consult->pression_arterielle, '/') ;
		$tensionmaximale = substr($consult->pression_arterielle, 0, $pos);
		$tensionminimale = substr($consult->pression_arterielle, $pos+1);
	
		$data = array (
				'id_cons' => $consult->id_cons,
				'id_medecin' => $consult->id_medecin,
				'id_patient' => $consult->id_patient,
				'date_cons' => $consult->date,
				'poids' => $consult->poids,
				'taille' => $consult->taille,
				'temperature' => $consult->temperature,
				'tensionmaximale' => $tensionmaximale,
				'tensionminimale' => $tensionminimale,
				'pouls' => $consult->pouls,
				'frequence_respiratoire' => $consult->frequence_respiratoire,
				'glycemie_capillaire' => $consult->glycemie_capillaire,
		);
		
		//POUR LES MOTIFS D'ADMISSION
		//POUR LES MOTIFS D'ADMISSION
		//POUR LES MOTIFS D'ADMISSION
		// instancier le motif d'admission et recup�rer l'enregistrement
		$motif_admission = $this->getMotifAdmissionTable ()->getMotifAdmission ( $id );
		$nbMotif = $this->getMotifAdmissionTable ()->nbMotifs ( $id );
	
		//POUR LES MOTIFS D'ADMISSION
		$k = 1;
		foreach ( $motif_admission as $Motifs ) {
			$data ['motif_admission' . $k] = $Motifs ['Libelle_motif'];
			$k ++;
		}
	
		//POUR LES EXAMEN PHYSIQUES
		//POUR LES EXAMEN PHYSIQUES
		//POUR LES EXAMEN PHYSIQUES
		$examen_physique = $this->getDonneesExamensPhysiquesTable()->getExamensPhysiques($id);
	
		//POUR LES EXAMEN PHYSIQUES
		$kPhysique = 1;
		foreach ($examen_physique as $Examen) {
			$data['examen_donnee'.$kPhysique] = $Examen['libelle_examen'];
			$kPhysique++;
		}
	
		// POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
		// POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
		// POUR LES ANTECEDENTS OU TERRAIN PARTICULIER
		//$listeConsultation = $this->getConsultationTable ()->getConsultationPatient($id_pat, $id);
		
		$listeConsultation = $this->getConsultationTable ()->getConsultationPatientSaufActu($id_pat, $id);
	
		//Recuperer les informations sur le surveillant de service pour les consultations qui diff�rent des consultations prises lors des archives
		$tabInfoSurv = array();
		foreach ($listeConsultation as $listeCons){
			if($listeCons['ID_SURVEILLANT']){
				$tabInfoSurv [$listeCons['ID_CONS']] = $this->getConsultationTable ()->getInfosSurveillant($listeCons['ID_SURVEILLANT'])['PRENOM'].' '.$this->getConsultationTable ()->getInfosSurveillant($listeCons['ID_SURVEILLANT'])['NOM'];
			}else{
				$tabInfoSurv [$listeCons['ID_CONS']] = '_________';
			}
		}
		$listeConsultation = $this->getConsultationTable ()->getConsultationPatientSaufActu($id_pat, $id);
		
		//*** Liste des Hospitalisations
		$listeHospitalisation = $this->getDemandeHospitalisationTable()->getDemandeHospitalisationWithIdPatient($id_pat);
	
		//POUR LES EXAMENS COMPLEMENTAIRES
		//POUR LES EXAMENS COMPLEMENTAIRES
		//POUR LES EXAMENS COMPLEMENTAIRES
		// DEMANDES DES EXAMENS COMPLEMENTAIRES
		$listeDemandesMorphologiques = $this->demandeExamensTable()->getDemandeExamensMorphologiques($id);
		$listeDemandesBiologiques = $this->demandeExamensTable()->getDemandeExamensBiologiques($id);
	
		////RESULTATS DES EXAMENS BIOLOGIQUES DEJA EFFECTUES ET ENVOYER PAR LE BIOLOGISTE
		$listeDemandesBiologiquesEffectuerEnvoyer = $this->demandeExamensTable()->getDemandeExamensBiologiquesEffectuesEnvoyer($id);
		$listeDemandesBiologiquesEffectuer = $this->demandeExamensTable()->getDemandeExamensBiologiquesEffectues($id);
		
		$tableauResultatsExamensBio = array(
				'temoinGSan' => 0,
				'temoinHSan' => 0,
				'temoinBHep' => 0,
				'temoinBRen' => 0,
				'temoinBHem' => 0,
				'temoinBInf' => 0,
		);
		foreach ($listeDemandesBiologiquesEffectuerEnvoyer as $listeExamenBioEffectues){
			if($listeExamenBioEffectues['idExamen'] == 1){
				$data['groupe_sanguin'] =  $listeExamenBioEffectues['noteResultat'];
				$tableauResultatsExamensBio['groupe_sanguin_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
				$tableauResultatsExamensBio['groupe_sanguin_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
				$tableauResultatsExamensBio['groupe_sanguin_conclusion'] = $listeExamenBioEffectues['conclusion'];
				$tableauResultatsExamensBio['temoinGSan'] = 1;
			}
			if($listeExamenBioEffectues['idExamen'] == 2){
				$data['hemogramme_sanguin'] =  $listeExamenBioEffectues['noteResultat'];
				$tableauResultatsExamensBio['hemogramme_sanguin_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
				$tableauResultatsExamensBio['hemogramme_sanguin_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
				$tableauResultatsExamensBio['hemogramme_sanguin_conclusion'] = $listeExamenBioEffectues['conclusion'];
				$tableauResultatsExamensBio['temoinHSan'] = 1;
			}
			if($listeExamenBioEffectues['idExamen'] == 3){
				$data['bilan_hepatique'] =  $listeExamenBioEffectues['noteResultat'];
				$tableauResultatsExamensBio['bilan_hepatique_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
				$tableauResultatsExamensBio['bilan_hepatique_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
				$tableauResultatsExamensBio['bilan_hepatique_conclusion'] = $listeExamenBioEffectues['conclusion'];
				$tableauResultatsExamensBio['temoinBHep'] = 1;
			}
			if($listeExamenBioEffectues['idExamen'] == 4){
				$data['bilan_renal'] =  $listeExamenBioEffectues['noteResultat'];
				$tableauResultatsExamensBio['bilan_renal_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
				$tableauResultatsExamensBio['bilan_renal_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
				$tableauResultatsExamensBio['bilan_renal_conclusion'] = $listeExamenBioEffectues['conclusion'];
				$tableauResultatsExamensBio['temoinBRen'] = 1;
			}
			if($listeExamenBioEffectues['idExamen'] == 5){
				$data['bilan_hemolyse'] =  $listeExamenBioEffectues['noteResultat'];
				$tableauResultatsExamensBio['bilan_hemolyse_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
				$tableauResultatsExamensBio['bilan_hemolyse_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
				$tableauResultatsExamensBio['bilan_hemolyse_conclusion'] = $listeExamenBioEffectues['conclusion'];
				$tableauResultatsExamensBio['temoinBHem'] = 1;
			}
			if($listeExamenBioEffectues['idExamen'] == 6){
				$data['bilan_inflammatoire'] =  $listeExamenBioEffectues['noteResultat'];
				$tableauResultatsExamensBio['bilan_inflammatoire_infoInfirmier'] = $this->getConsultationTable()->getInfosSurveillant( $listeExamenBioEffectues['id_personne'] );
				$tableauResultatsExamensBio['bilan_inflammatoire_date_enregistrement'] = $this->controlDate->convertDateTime($listeExamenBioEffectues['date_enregistrement']);
				$tableauResultatsExamensBio['bilan_inflammatoire_conclusion'] = $listeExamenBioEffectues['conclusion'];
				$tableauResultatsExamensBio['temoinBInf'] = 1;
			}
		}
		
		////RESULTATS DES EXAMENS MORPHOLOGIQUE
		$examen_morphologique = $this->getNotesExamensMorphologiquesTable()->getNotesExamensMorphologiques($id);
	
		$data['radio'] = $examen_morphologique['radio'];
		$data['ecographie'] = $examen_morphologique['ecographie'];
		$data['fibrocospie'] = $examen_morphologique['fibroscopie'];
		$data['scanner'] = $examen_morphologique['scanner'];
		$data['irm'] = $examen_morphologique['irm'];
		
		////RESULTATS DES EXAMENS MORPHOLOGIQUES DEJA EFFECTUES ET ENVOYER PAR LE BIOLOGISTE
		$listeDemandesMorphologiquesEffectuer = $this->demandeExamensTable()->getDemandeExamensMorphologiquesEffectues($id);
	
		//DIAGNOSTICS
		//DIAGNOSTICS
		//DIAGNOSTICS
		$infoDiagnostics = $this->getDiagnosticsTable()->getDiagnostics($id);
		// POUR LES DIAGNOSTICS
		$k = 1;
		foreach ($infoDiagnostics as $diagnos){
			$data['diagnostic'.$k] = $diagnos['libelle_diagnostics'];
			$k++;
		}
		
		//TRAITEMENT (Ordonnance) *********************************************************
		//TRAITEMENT (Ordonnance) *********************************************************
		//TRAITEMENT (Ordonnance) *********************************************************
	
		//POUR LES MEDICAMENTS
		//POUR LES MEDICAMENTS
		//POUR LES MEDICAMENTS
		// INSTANCIATION DES MEDICAMENTS de l'ordonnance
		$listeMedicament = $this->getConsultationTable()->listeDeTousLesMedicaments();
		$listeForme = $this->getConsultationTable()->formesMedicaments();
		$listetypeQuantiteMedicament = $this->getConsultationTable()->typeQuantiteMedicaments();
	
		// INSTANTIATION DE L'ORDONNANCE
		$infoOrdonnance = $this->getOrdonnanceTable()->getOrdonnanceNonHospi($id);
	
		if($infoOrdonnance) {
			$idOrdonnance = $infoOrdonnance->id_document;
			$duree_traitement = $infoOrdonnance->duree_traitement;
			//LISTE DES MEDICAMENTS PRESCRITS
			$listeMedicamentsPrescrits = $this->getOrdonnanceTable()->getMedicamentsParIdOrdonnance($idOrdonnance);
			$nbMedPrescrit = $listeMedicamentsPrescrits->count();
		}else{
			$nbMedPrescrit = null;
			$listeMedicamentsPrescrits =null;
			$duree_traitement = null;
		}
		
		//POUR LA DEMANDE PRE-ANESTHESIQUE
		//POUR LA DEMANDE PRE-ANESTHESIQUE
		//POUR LA DEMANDE PRE-ANESTHESIQUE
		$donneesDemandeVPA = $this->getDemandeVisitePreanesthesiqueTable()->getDemandeVisitePreanesthesique($id);
	
		$resultatVpa = null;
		if($donneesDemandeVPA) {
			$data['diagnostic_traitement_chirurgical'] = $donneesDemandeVPA['DIAGNOSTIC'];
			$data['observation'] = $donneesDemandeVPA['OBSERVATION'];
			$data['intervention_prevue'] = $donneesDemandeVPA['INTERVENTION_PREVUE'];
	
			$resultatVpa = $this->getResultatVpa()->getResultatVpa($donneesDemandeVPA['idVpa']);
		}
	
		/**** INSTRUMENTAL ****/
		/**** INSTRUMENTAL ****/
		/**** INSTRUMENTAL ****/
		$traitement_instrumental = $this->getConsultationTable()->getTraitementsInstrumentaux($id);
	
		$data['endoscopieInterventionnelle'] = $traitement_instrumental['endoscopie_interventionnelle'];
		$data['radiologieInterventionnelle'] = $traitement_instrumental['radiologie_interventionnelle'];
		$data['cardiologieInterventionnelle'] = $traitement_instrumental['cardiologie_interventionnelle'];
		$data['autresIntervention'] = $traitement_instrumental['autres_interventions'];
	
		//POUR LE TRANSFERT
		//POUR LE TRANSFERT
		//POUR LE TRANSFERT
		// INSTANCIATION DU TRANSFERT
		// RECUPERATION DE LA LISTE DES HOPITAUX
		$hopital = $this->getTransfererPatientServiceTable ()->fetchHopital ();
	
		//LISTE DES HOPITAUX
		$form->get ( 'hopital_accueil' )->setValueOptions ( $hopital );
		// RECUPERATION DU SERVICE OU EST TRANSFERE LE PATIENT
		$transfertPatientService = $this->getTransfererPatientServiceTable ()->getServicePatientTransfert($id);
		
		if( $transfertPatientService ){
			$idService = $transfertPatientService['ID_SERVICE'];
			// RECUPERATION DE L'HOPITAL DU SERVICE
			$transfertPatientHopital = $this->getTransfererPatientServiceTable ()->getHopitalPatientTransfert($idService);
			$idHopital = $transfertPatientHopital['ID_HOPITAL'];
			// RECUPERATION DE LA LISTE DES SERVICES DE L'HOPITAL OU SE TROUVE LE SERVICE OU IL EST TRANSFERE
			$serviceHopital = $this->getTransfererPatientServiceTable ()->fetchServiceWithHopital($idHopital);
	
			// LISTE DES SERVICES DE L'HOPITAL
			$form->get ( 'service_accueil' )->setValueOptions ($serviceHopital);
	
			// SELECTION DE L'HOPITAL ET DU SERVICE SUR LES LISTES
			$data['hopital_accueil'] = $idHopital;
			$data['service_accueil'] = $idService;
			$data['motif_transfert'] = $transfertPatientService['MOTIF_TRANSFERT'];
			$hopitalSelect = 1;
		}else {
			$hopitalSelect = 0;
			// RECUPERATION DE L'HOPITAL DU SERVICE
			$transfertPatientHopital = $this->getTransfererPatientServiceTable ()->getHopitalPatientTransfert($IdDuService);
			$idHopital = $transfertPatientHopital['ID_HOPITAL'];
			$data['hopital_accueil'] = $idHopital;
			// RECUPERATION DE LA LISTE DES SERVICES DE L'HOPITAL OU SE TROUVE LE SERVICE OU LE MEDECIN TRAVAILLE
			$serviceHopital = $this->getTransfererPatientServiceTable ()->fetchServiceWithHopitalNotServiceActual($idHopital, $IdDuService);
			// LISTE DES SERVICES DE L'HOPITAL
			$form->get ( 'service_accueil' )->setValueOptions ($serviceHopital);
		}
	
		//POUR LE RENDEZ VOUS
		//POUR LE RENDEZ VOUS
		//POUR LE RENDEZ VOUS
		// RECUPERE LE RENDEZ VOUS
		$leRendezVous = $this->getRvPatientConsTable()->getRendezVous($id);
		
		if($leRendezVous) {
			$data['heure_rv'] = $leRendezVous->heure;
			$data['date_rv']  = $this->controlDate->convertDate($leRendezVous->date);
			$data['motif_rv'] = $leRendezVous->note;
		}
		// Pour recuper les bandelettes
		$bandelettes = $this->getConsultationTable ()->getBandelette($id);
	
		//RECUPERATION DES ANTECEDENTS
		//RECUPERATION DES ANTECEDENTS
		//RECUPERATION DES ANTECEDENTS
		$donneesAntecedentsPersonnels = $this->getAntecedantPersonnelTable()->getTableauAntecedentsPersonnels($id_pat);
		$donneesAntecedentsFamiliaux = $this->getAntecedantsFamiliauxTable()->getTableauAntecedentsFamiliaux($id_pat);
		
		//Recuperer les antecedents medicaux ajouter pour le patient
		//Recuperer les antecedents medicaux ajouter pour le patient
		$antMedPat = $this->getConsultationTable()->getAntecedentMedicauxPersonneParIdPatient($id_pat);
	
		//Recuperer les antecedents medicaux
		//Recuperer les antecedents medicaux
		$listeAntMed = $this->getConsultationTable()->getAntecedentsMedicaux();
	
		
		//Recuperer la liste des actes
		//Recuperer la liste des actes
		//$listeActes = $this->getConsultationTable()->getListeDesActes();
		$listeDemandesActes = $this->getDemandeActe()->getDemandeActe($id);
	
		//FIN ANTECEDENTS --- FIN ANTECEDENTS --- FIN ANTECEDENTS
		//FIN ANTECEDENTS --- FIN ANTECEDENTS --- FIN ANTECEDENTS
	
		//Liste des examens biologiques
		$listeDesExamensBiologiques = $this->demandeExamensTable()->getDemandeDesExamensBiologiques();
		//Liste des examens Morphologiques
		$listeDesExamensMorphologiques = $this->demandeExamensTable()->getDemandeDesExamensMorphologiques();
	
		
		//POUR LES DEMANDES D'HOSPITALISATION
		//POUR LES DEMANDES D'HOSPITALISATION
		//POUR LES DEMANDES D'HOSPITALISATION
		$donneesHospi = $this->getDemandeHospitalisationTable()->getDemandehospitalisationParIdcons($id);
		if($donneesHospi){
			$data['motif_hospitalisation'] = $donneesHospi->motif_demande_hospi;
			$data['date_fin_hospitalisation_prevue'] = $this->controlDate->convertDate($donneesHospi->date_fin_prevue_hospi);
		}
		$form->populateValues ( array_merge($data,$bandelettes,$donneesAntecedentsPersonnels,$donneesAntecedentsFamiliaux) );
		
		return array(
				'id_cons' => $id,
				'lesdetails' => $liste,
				'form' => $form,
				'nbMotifs' => $nbMotif,
				'image' => $image,
				'heure_cons' => $consult->heurecons,
				'liste' => $listeConsultation,
				'liste_med' => $listeMedicament,
				'nb_med_prescrit' => $nbMedPrescrit,
				'liste_med_prescrit' => $listeMedicamentsPrescrits,
				'duree_traitement' => $duree_traitement,
				'verifieRV' => $leRendezVous,
				'listeDemandesMorphologiques' => $listeDemandesMorphologiques,
				'listeDemandesBiologiques' => $listeDemandesBiologiques,
				'hopitalSelect' =>$hopitalSelect,
				'nbDiagnostics'=> $infoDiagnostics->count(),
				'nbDonneesExamenPhysique' => $kPhysique,
				'dateonly' => $consult->dateonly,
				'temoin' => $bandelettes['temoin'],
				'listeForme' => $listeForme,
				'listetypeQuantiteMedicament'  => $listetypeQuantiteMedicament,
				'donneesAntecedentsPersonnels' => $donneesAntecedentsPersonnels,
				'donneesAntecedentsFamiliaux'  => $donneesAntecedentsFamiliaux,
				'resultRV' => $resultRV,
				'listeDemandesBioEff' => $listeDemandesBiologiquesEffectuer->count(),
				'listeDemandesMorphoEff' => $listeDemandesMorphologiquesEffectuer->count(),
				'resultatVpa' => $resultatVpa,
				'listeHospitalisation' => $listeHospitalisation,
				'tabInfoSurv' => $tabInfoSurv,
				'tableauResultatsExamensBio' => $tableauResultatsExamensBio,
				'listeAntMed' => $listeAntMed,
				'antMedPat' => $antMedPat,
				'nbAntMedPat' => $antMedPat->count(),
				'listeDemandesActes' => $listeDemandesActes,
				'listeActes' => $listeActes,
				'listeDesExamensBiologiques' => $listeDesExamensBiologiques,
				'listeDesExamensMorphologiques' => $listeDesExamensMorphologiques,
	
		);
	}
	//liste des pateints deja consultes
	public Function patientsConsultesAction(){
	    $this->layout ()->setTemplate ( 'layout/chururgie' );
	    $user = $this->layout()->user;
	    $idService = $user['IdService'];
	    //
	    $form= new ConsultationForm();
	    $lespatients = $this->getConsultationTable()->listePatientsConsultes ( $idService );
	   // var_dump($lespatients);exit();
	    $cons=$form->get('id_cons')->getValue();
	    //var_dump($cons);exit();
	    return new ViewModel ( array (
	        'donnees' => $lespatients,
	        'form'=> $form,
	        'id_cons'=>$cons,
	    ) );
	}
	
	//Liste des  patients qui ne sont pas encore consultes
	public function consultationMedecinAction() {
		
		$this->layout ()->setTemplate ( 'layout/chururgie' );
		$user = $this->layout()->user;
		$idService = $user['IdService'];
		//
		$form= new ConsultationForm();
		$lespatients = $this->getConsultationTable()->listePatientsConsParMedecin ( $idService );
		//RECUPERER TOUS LES PATIENTS AYANT UN RV aujourd'hui		
		//var_dump($lespatients);exit();
		$tabPatientRV = $this->getConsultationTable ()->getPatientsRV($idService);
		$cons=$form->get('id_cons')->getValue();
		//var_dump($cons);exit();
		return new ViewModel ( array (
				'donnees' => $lespatients,
				'tabPatientRV' => $tabPatientRV,
		         'form'=> $form,
		         'id_cons'=>$cons,
		) );
	}

	public function complementConsultationAction() {
	
		$this->layout ()->setTemplate ( 'layout/chururgie' );
	
		$user = $this->layout()->user;
		$IdDuService = $user['IdService'];
		$id_medecin = $user['id_personne'];
	
		$id_pat = $this->params ()->fromQuery ( 'id_patient', 0 );
		//var_dump($id_pat);exit();
		$id_cons = $this->params ()->fromQuery ( 'id_cons' );
		$id= $this->params ()->fromQuery ('id_admission',0);
		
            $listeOrgane=$this->getConsultationTable()->listeDeTousLesOrganes();
          //
            $listeclassePathologie=$this->getConsultationTable()->getClassePathologie();
            
          
		$listeMedicament = $this->getConsultationTable()->listeDeTousLesMedicaments();
		
		$listeForme = $this->getConsultationTable()->formesMedicaments();
		
		$listetypeQuantiteMedicament = $this->getConsultationTable()->typeQuantiteMedicaments();
		
		$liste = $this->getConsultationTable ()->getInfoPatient ( $id_pat );
		$image = $this->getConsultationTable ()->getPhoto ( $id_pat );
		
		//RECUPERER TOUS LES PATIENTS AYANT UN RV aujourd'hui
		$tabPatientRV = $this->getConsultationTable()->getPatientsRV($IdDuService);
		
		$resultRV = null;
		if(array_key_exists($id_pat, $tabPatientRV)){
			$resultRV = $tabPatientRV[ $id_pat ];
		}
		//var_dump($id);exit();
		$form = new ConsultationForm ();
		//var_dump($form);exit();
		// instancier la consultation et r�cup�rer l'enregistrement
		//$consult = $this->getConsultationTable ()->getConsult ( $id_cons );
		
		// POUR LES HISTORIQUES OU TERRAIN PARTICULIER
		// POUR LES HISTORIQUES OU TERRAIN PARTICULIER
		// POUR LES HISTORIQUES OU TERRAIN PARTICULIER
		//*** Liste des consultations
		//
		$listeConsultation = $this->getConsultationTable ()->getConsultationPatient($id_pat, $id_cons);
		
		//Liste des examens biologiques
		$listeDesExamensBiologiques = $this->demandeExamensTable()->getDemandeDesExamensBiologiques();
		
		//Liste des examens Morphologiques
		$listeDesExamensMorphologiques = $this->demandeExamensTable()->getDemandeDesExamensMorphologiques();
	
	
		//*** Liste des Hospitalisations
		$listeHospitalisation = $this->getDemandeHospitalisationTable()->getDemandeHospitalisationWithIdPatient($id_pat);
		
		// instancier le motif d'admission et recup�rer l'enregistrement
		$motif_admission = $this->getMotifAdmissionTable ()->getMotifAdmission ( $id_cons );
		$nbMotif = $this->getMotifAdmissionTable ()->nbMotifs ( $id_cons );
		
		// r�cup�ration de la liste des hopitaux
		$hopital = $this->getTransfererPatientServiceTable ()->fetchHopital ();
		$form->get ( 'hopital_accueil' )->setValueOptions ( $hopital );
		// RECUPERATION DE L'HOPITAL DU SERVICE
		$transfertPatientHopital = $this->getTransfererPatientServiceTable ()->getHopitalPatientTransfert($IdDuService);
		$idHopital = $transfertPatientHopital['ID_HOPITAL'];
		
		// RECUPERATION DE LA LISTE DES SERVICES DE L'HOPITAL OU SE TROUVE LE SERVICE OU LE MEDECIN TRAVAILLE
	//	$serviceHopital = $this->getTransfererPatientServiceTable ()->fetchServiceWithHopitalNotServiceActual($idHopital, $IdDuService);
		$serviceHopital = $this->getServiceTable()->listeService();
		// LISTE DES SERVICES DE L'HOPITAL
		$form->get ( 'service_accueil' )->setValueOptions ($serviceHopital);
	
		// liste des heures rv
		$heure_rv = array (
				'08:00' => '08:00',
				'09:00' => '09:00',
				'10:00' => '10:00',
				'15:00' => '15:00',
				'16:00' => '16:00'
		);
		$form->get ( 'heure_rv' )->setValueOptions ( $heure_rv );
	
// 		$data = array (
// 				'id_cons' => $consult->id_cons,
// 				'id_medecin' => $id_medecin,
// 				'id_patient' => $consult->id_patient,
// 				'date_cons' => $consult->date,
// 				'poids' => $consult->poids,
// 				'taille' => $consult->taille,
// 				'temperature' => $consult->temperature,
// 				'pouls' => $consult->pouls,
// 				'frequence_respiratoire' => $consult->frequence_respiratoire,
// 				'glycemie_capillaire' => $consult->glycemie_capillaire,
// 				'pressionarterielle' => $consult->pression_arterielle,
// 				'hopital_accueil' => $idHopital,
               
// 		);
// 		var_dump($nbMotif);exit();
// 		$k = 1;
// 		foreach ( $motif_admission as $Motifs ) {
// 			$data ['motif_admission' . $k] = $Motifs ['Libelle_motif'];
// 			$k ++;
// 		}
		
		// Pour recuper les bandelettes
		//$bandelettes = $this->getConsultationTable ()->getBandelette($id_cons);
		
		//RECUPERATION DES ANTECEDENTS
		//RECUPERATION DES ANTECEDENTS
		//RECUPERATION DES ANTECEDENTS
		$donneesAntecedentsPersonnels = $this->getAntecedantPersonnelTable()->getTableauAntecedentsPersonnels($id_pat);
		$donneesAntecedentsFamiliaux  = $this->getAntecedantsFamiliauxTable()->getTableauAntecedentsFamiliaux($id_pat);
	
		//Recuperer les antecedents medicaux ajouter pour le patient
		//Recuperer les antecedents medicaux ajouter pour le patient
		$antMedPat = $this->getConsultationTable()->getAntecedentMedicauxPersonneParIdPatient($id_pat);
		
		//Recuperer les antecedents medicaux
		//Recuperer les antecedents medicaux
		$listeAntMed = $this->getConsultationTable()->getAntecedentsMedicaux();
	
		//FIN ANTECEDENTS --- FIN ANTECEDENTS --- FIN ANTECEDENTS
		//FIN ANTECEDENTS --- FIN ANTECEDENTS --- FIN ANTECEDENTS
		
		
		
		//Liste des examens Fonctionnels
		//$listeActes = $this->demandeExamensTable()->getDemandeDesExamensFonctionnels();
		//var_dump($listeActes);exit();
		//Recuperer la liste des actes
		//Recuperer la liste des actes
		$listeActes = $this->getConsultationTable()->getListeDesActes();
		//var_dump($listeActes);exit();
		$form->populateValues ( array_merge($donneesAntecedentsPersonnels,$donneesAntecedentsFamiliaux) );
		
		$listeTypePathologie=$this->getConsultationTable()->getTypePathologie();
		//var_dump($listeTypePathologie);exit();
		//var_dump($liste);exit();
		//$today = new \DateTime ( 'now' );
		$today = new DateTime("now");
		$date = $today->format ( 'dd/mm/yyyy' );
		$heure = $today->format ( "H:i" );
	
		//var_dump($id);exit();
		$form->populateValues(array('id_patient' => $id_pat));
	
		$form->populateValues(array('id_admission' => $id));
		
		return array (
		          'lesOrganes' => $listeOrgane,
		          'listeclassePathologie'=>$listeclassePathologie,
		          'listeTypePathologie'=> $listeTypePathologie,
				'lesdetails' => $liste,
		         'id_cons' => $id_cons,
		          'id_admission' => $id,
				'id_patient' => $id_pat,
				'image' => $image,
				'form' => $form,
		          'heure_cons' => $heure,
		           'dateonly' => $date,
				'liste_med' => $listeMedicament,
				//'temoin' => $bandelettes['temoin'],
				'listeForme' => $listeForme,
				'listetypeQuantiteMedicament'  => $listetypeQuantiteMedicament,
				'donneesAntecedentsPersonnels' => $donneesAntecedentsPersonnels,
				'donneesAntecedentsFamiliaux'  => $donneesAntecedentsFamiliaux,
				'liste' => $listeConsultation,
				'resultRV' => $resultRV,
				'listeHospitalisation' => $listeHospitalisation,
				'listeDesExamensBiologiques' => $listeDesExamensBiologiques,
				'listeDesExamensMorphologiques' => $listeDesExamensMorphologiques,
				'listeAntMed' => $listeAntMed,
				'antMedPat' => $antMedPat,
				'nbAntMedPat' => $antMedPat->count(),
				'listeActes' => $listeActes,
		      
		);
	}
	
	
	//Admission d' un patient en Chururgie
	
	public function listePatientsAdmisAction() {
	 
	    
		$this->layout ()->setTemplate ( 'layout/chururgie' );
		$patientsAdmis = $this->getAdmissionTable ();
		//INSTANCIATION DU FORMULAIRE
		$formAdmission = new AdmissionForm ();
		$service = $this->getServiceTable ()->fetchService ();
		$listeService = $this->getServiceTable ()->listeService ();
		$afficheTous = array (
				"" => 'Tous'
		);
	
		$tab_service = array_merge ( $afficheTous, $listeService );
		$formAdmission->get ( 'service' )->setValueOptions ( $service );
		$formAdmission->get ( 'liste_service' )->setValueOptions ( $tab_service );
		
		///var_dump($patientsAdmis->getPatientsAdmis ());exit();
		return new ViewModel ( array (
				'listePatientsAdmis' => $patientsAdmis->getPatientsAdmis (),
				'form' => $formAdmission,
				'listePatientsCons' => $patientsAdmis->getPatientAdmisCons(),
		) );
		
	}
	public function enregistrerAdmissionAction() {
		$user = $this->layout()->user;
		$id_employe = $user['id_personne'];
		
		
		$today = new \DateTime ( "now" );
		$date_admise = $today->format ( 'Y-m-d' );
		$date_enregistrement = $today->format ( 'Y-m-d H:i:s' );
	
		$id_patient = ( int ) $this->params ()->fromPost ( 'id_patient', 0 );
		//$numero = $this->params ()->fromPost ( 'numero' );
		$id_service = $user['id_service'];
		//$montant = $this->params ()->fromPost ( 'montant' );
		$type_consultation = $this->params ()->fromPost ( 'type_consultation' );
	
		$donnees = array (
				'id_patient' => $id_patient,
				'id_service' => $id_service,
				'date_admise' => $date_admise,
				'type_consultation' => $type_consultation,
				'date_enregistrement' => $date_enregistrement,
				'id_employe' => $id_employe,
		);
		
		$form = new ConsultationForm();
		$formData = $this->getRequest ()->getPost ();
		$form->setData ( $formData );
		
		//$id_cons = $form->get (  "id_cons" )->getValue ();	
		
		//$this->getAdmissionTable ()->addAdmission ( $donnees );		
		$id_admission=$this->getAdmissionTable ()->addAdmission ( $donnees );	
		//NOUVEAU CODE AJOUTER POUR QUE LE MEDECIN PUISSE AJOUTER DIRECTEMENT LES CONSTANTES DU PATIENT SANS LE PASSAGE DE CELUI CI AU NIVEAU DU SURVEILLANT DE SERVICE
		//NOUVEAU CODE AJOUTER POUR QUE LE MEDECIN PUISSE AJOUTER DIRECTEMENT LES CONSTANTES DU PATIENT SANS LE PASSAGE DE CELUI CI AU NIVEAU DU SURVEILLANT DE SERVICE
		//NOUVEAU CODE AJOUTER POUR QUE LE MEDECIN PUISSE AJOUTER DIRECTEMENT LES CONSTANTES DU PATIENT SANS LE PASSAGE DE CELUI CI AU NIVEAU DU SURVEILLANT DE SERVICE
		/* CODE A SUPPRIMER POUR FAIRE INTERVENIR LE SURVEILLANT DE SERVICE*/
		/* CODE A SUPPRIMER POUR FAIRE INTERVENIR LE SURVEILLANT DE SERVICE*/
		/* CODE A SUPPRIMER POUR FAIRE INTERVENIR LE SURVEILLANT DE SERVICE*/		
		
		$this->getConsultationTable()-> addIdAdmission ( $id_admission );	
		//var_dump($id_admission);exit();
		//var_dump($id_admission);exit();
		//$this->getAdmissionTable ()->addConsultationChururgieGenerale($id_cons);
		
		//FIN FIN NOUVEAU CODE AJOUTER POUR QUE LE MEDECIN PUISSE AJOUTER DIRECTEMENT LES CONSTANTES DU PATIENT
		//FIN FIN NOUVEAU CODE AJOUTER POUR QUE LE MEDECIN PUISSE AJOUTER DIRECTEMENT LES CONSTANTES DU PATIENT
		//FIN FIN NOUVEAU CODE AJOUTER POUR QUE LE MEDECIN PUISSE AJOUTER DIRECTEMENT LES CONSTANTES DU PATIENT
		//$id_dernier=$this->getAdmissionTable ()->getLastAdmission();
		//var_dump($id_dernier);exit();
	
		return $this->redirect()->toRoute('chururgie', array('action' =>'liste-patients-admis'));
	
	}
	public function admissionAction() {
		$layout = $this->layout ();
		$layout->setTemplate ( 'layout/chururgie' );
		$user = $this->layout()->user;
		$id_service = $user['id_service'];
		//var_dump($id_service);exit();
		//$numero = $this->numeroFacture();
		//INSTANCIATION DU FORMULAIRE D'ADMISSION
		
		$formAdmission = new AdmissionForm ();
		//var_dump($formAdmission);exit();
		$service = $this->getTarifConsultationTable()->listeService();
	
		$listeService = $this->getServiceTable ()->listeService ();
		$afficheTous = array ("" => 'Tous');
	
		$tab_service = array_merge ( $afficheTous, $listeService );
		$formAdmission->get ( 'service' )->setValueOptions ( $service );
		$formAdmission->get ( 'liste_service' )->setValueOptions ( $tab_service );
	
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
				
			//Verifier si le patient a un rendez-vous et si oui dans quel service et a quel heure
			//$RendezVOUS = $pat->verifierRV($id, $dateAujourdhui);
				
			$unPatient = $pat->getInfoPatient( $id );
	
			$photo = $pat->getPhoto ( $id );
	
				
			$date = $unPatient['DATE_NAISSANCE'];
			if($date){ $date = $this->convertDate ( $unPatient['DATE_NAISSANCE'] ); }else{ $date = null;} 
	
		 $html  = "<div style='width:100%;'>";
				
			$html .= "<div style='width: 18%; height: 190px; float:left;'>";
			$html .= "<div id='photo' style='float:left; margin-left:40px; margin-top:10px; margin-right:30px;'> <img style='width:105px; height:105px;' src='../img/photos_patients/" . $photo . "' ></div>";
			$html .= "<div style='margin-left:60px; margin-top: 150px;'> <div style='text-decoration:none; font-size:14px; float:left; padding-right: 7px; '>Age:</div>  <div style='font-weight:bold; font-size:19px; font-family: time new romans; color: green; font-weight: bold;'>" . $unPatient['AGE'] . " ans</div></div>";
			$html .= "</div>";
				
			$html .= "<div id='vuePatientAdmission' style='width: 70%; height: 190px; float:left;'>";
			$html .= "<table style='margin-top:0px; float:left; width: 100%;'>";
				
			$html .= "<tr style='width: 100%;'>";
			$html .= "<td style='width: 19%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nom:</a><br><div style='width: 150px; max-width: 160px; height:40px; overflow:auto; margin-bottom: 3px;'><p style='font-weight:bold; font-size:17px;'>" . $unPatient['NOM'] . "</p></div></td>";
			$html .= "<td style='width: 29%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Lieu de naissance:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['LIEU_NAISSANCE'] . "</p></div></td>";
			$html .= "<td style='width: 23%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nationalit&eacute;  d'origine:</a><br><div style='width: 95%; '><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['NATIONALITE_ORIGINE'] . "</p></div></td>";
			$html .= "<td style='width: 29%; '></td>";
				
			$html .= "</tr><tr style='width: 100%;'>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Pr&eacute;nom:</a><br><div style='width: 95%; max-width: 130px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['PRENOM'] . "</p></div></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>T&eacute;l&eacute;phone:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['TELEPHONE'] . "</p></div></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nationalit&eacute; actuelle:</a><br><div style='width: 95%; max-width: 135px; overflow:auto; '><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['NATIONALITE_ACTUELLE']. "</p></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Email:</a><br><div style='width: 100%; max-width: 235px; height:40px; overflow:auto;'><p style='font-weight:bold; font-size:17px;'>" . $unPatient['EMAIL'] . "</p></div></td>";
				
			$html .= "</tr><tr style='width: 100%;'>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Date de naissance:</a><br><div style='width: 95%; max-width: 130px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $date . "</p></div></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Adresse:</a><br><div style='width: 97%; max-width: 250px; height:50px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['ADRESSE'] . "</p></div></td>";
			$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Profession:</a><br><div style='width: 95%; max-width: 235px; height:40px; overflow:auto; '><p style=' font-weight:bold; font-size:17px;'>" .  $unPatient['PROFESSION'] . "</p></div></td>";
				
			
			$html .= "</tr>";
			$html .= "</table>";
			$html .= "</div>";
				
			$html .= "<div style='width: 12%; height: 190px; float:left;'>";
			$html .= "<div id='' style='color: white; opacity: 0.09; float:left; margin-right:10px; margin-left:5px; margin-top:5px;'> <img style='width:105px; height:105px;' src='../img/photos_patients/" . $photo . "'></div>";
			$html .= "</div>";
				
			$html .= "</div>";
				
		
	 
			$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
			return $this->getResponse ()->setContent ( Json::encode ($html) );
		}
		
		return array (
				'form' => $formAdmission
		);
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
	// Enregistrement du patient ajout�
	public function enregistrementAction() {
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
			        'TELEPHONE1' => $this->params ()->fromPost ( 'TELEPHONE1' ),
					'NATIONALITE_ORIGINE' => $this->params ()->fromPost ( 'NATIONALITE_ORIGINE' ),
					'PRENOM' => $this->params ()->fromPost ( 'PRENOM' ),
					'PROFESSION' => $this->params ()->fromPost ( 'PROFESSION' ),
					'NATIONALITE_ACTUELLE' => $this->params ()->fromPost ( 'NATIONALITE_ACTUELLE' ),
					'DATE_NAISSANCE' => $date_naissance,
					'ADRESSE' => $this->params ()->fromPost ( 'ADRESSE' ),
					'SEXE' => $this->params ()->fromPost ( 'SEXE' ),
					'AGE' => $this->params ()->fromPost ( 'AGE' ),
					'DATE_MODIFICATION' => $today->format ( 'Y-m-d' ) 
			);
			$sexe = 2;
			if($donnees['SEXE'] == 'Masculin'){ $sexe = 1; }
			if ($img != false) {
				
				$donnees ['PHOTO'] = $nomfile;
				// ENREGISTREMENT DE LA PHOTO
				imagejpeg ( $img, $this->baseUrlRacine().'public/img/photos_patients/' . $nomfile . '.jpg' );
				// ENREGISTREMENT DES DONNEES
				$Patient->addPatient ( $donnees, $date_enregistrement, $id_employe, $sexe );
				
				return $this->redirect ()->toRoute ( 'chururgie', array (
						'action' => 'admission' 
				) );
			} else {
			   
				// On enregistre sans la photo
				$Patient->addPatient ( $donnees, $date_enregistrement, $id_employe, $sexe );
			
				return $this->redirect ()->toRoute ( 'chururgie', array (
						'action' => 'admission' 
				) );
			}
		}
		return $this->redirect ()->toRoute ( 'chururgie', array (
				'action' => 'admission' 
		) );
	}
	public function ajouterAction() {
		$this->layout ()->setTemplate ( 'layout/chururgie' );
		$form = $this->getForm ();
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
	public function enregistrementModificationAction() {
		$user = $this->layout ()->user;
		$id_employe = $user ['id_personne']; // L'utilisateur connect�
		
		if (isset ( $_POST ['terminer'] )) {
			$Control = new DateHelper ();
			$Patient = $this->getPatientTable ();
			$today = new \DateTime ( 'now' );
			$nomfile = $today->format ( 'dmy_His' );
			$date_modification = $today->format ( 'Y-m-d H:i:s' );
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
			        'TELEPHONE1' => $this->params ()->fromPost ( 'TELEPHONE1' ),
					'NATIONALITE_ORIGINE' => $this->params ()->fromPost ( 'NATIONALITE_ORIGINE' ),
					'PRENOM' => $this->params ()->fromPost ( 'PRENOM' ),
					'PROFESSION' => $this->params ()->fromPost ( 'PROFESSION' ),
					'NATIONALITE_ACTUELLE' => $this->params ()->fromPost ( 'NATIONALITE_ACTUELLE' ),
					'DATE_NAISSANCE' => $date_naissance,
					'ADRESSE' => $this->params ()->fromPost ( 'ADRESSE' ),
					'SEXE' => $this->params ()->fromPost ( 'SEXE' ),
					'AGE' => $this->params ()->fromPost ( 'AGE' ) 
			);
			
			$id_patient = $this->params ()->fromPost ( 'ID_PERSONNE' );
			if ($img != false) {
				
				$lePatient = $Patient->getInfoPatient ( $id_patient );
				$ancienneImage = $lePatient ['PHOTO'];
				
				if ($ancienneImage) {
				    unlink ( $this->baseUrlRacine().'public/img/photos_patients/'. $ancienneImage . '.jpg' );
				}
				imagejpeg ( $img, $this->baseUrlRacine().'public/img/photos_patients/'. $nomfile . '.jpg' );
				
				$donnees ['PHOTO'] = $nomfile;
				$Patient->updatePatient ( $donnees, $id_patient, $date_modification, $id_employe );
				
				return $this->redirect ()->toRoute ( 'chururgie', array (
						'action' => 'liste-patient' 
				) );
			} else {
				$Patient->updatePatient ( $donnees, $id_patient, $date_modification, $id_employe );
				return $this->redirect ()->toRoute ( 'chururgie', array (
						'action' => 'liste-patient' 
				) );
			}
		}
		return $this->redirect ()->toRoute ( 'chururgie', array (
				'action' => 'liste-patient' 
		) );
	}
	public function modifierAction() {
		$control = new DateHelper ();
		$this->layout ()->setTemplate ( 'layout/chururgie' );
		$id_patient = $this->params ()->fromRoute ( 'val', 0 );
		
		$infoPatient = $this->getPatientTable ();
		try {
			$info = $infoPatient->getInfoPatient ( $id_patient );
		} catch ( \Exception $ex ) {
			return $this->redirect ()->toRoute ( 'chururgie', array (
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
	public function infoPatientAction() {
		$this->layout ()->setTemplate ( 'layout/chururgie' );
		$id_pat = $this->params ()->fromRoute ( 'val', 0 );
		
		$patient = $this->getPatientTable ();
		$unPatient = $patient->getInfoPatient ( $id_pat );
		
		if ($this->getRequest ()->isPost ()) {
			$id = ( int ) $this->params ()->fromPost ( 'id', 0 );

			$pat = $this->getPatientTable ();
			$unPatient = $pat->getInfoPatient ( $id );
			$photo = $pat->getPhoto ( $id );
				
 			$date = $unPatient['DATE_NAISSANCE'];
 			if($date){ $date = $this->convertDate ($date); }else{ $date = null;}
		
 			$html = " <div style='float:left;' ><div id='photo' style='float:left; margin-right:20px; margin-bottom: 10px;'> <img  src='../img/photos_patients/" . $photo . "'  style='width:105px; height:105px;'></div>";
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
			$html .= "</tr><tr>";
			$html .= "<td><a style='text-decoration:underline; font-size:12px;'>T&eacute;l&eacute;phone Proche:</a><br><p style='width:280px; font-weight:bold; font-size:17px;'>" . $unPatient['TELEPHONE1'] . "</p></td>";
			$html .= "</tr>";
		
			$html .= "</table>";
			$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
			return $this->getResponse ()->setContent ( Json::encode ( $html ) );
		}
		
		return array (
				'lesdetails' => $unPatient,
				'image' => $patient->getPhoto ( $id_pat ),
				'id_patient' => $unPatient ['ID_PERSONNE'],
				'date_enregistrement' => $unPatient ['DATE_ENREGISTREMENT'] 
		);
	}
	public function listePatientAction() {
		$layout = $this->layout ();
		$user = $this->layout()->user;
		//var_dump($user);exit();
		$layout->setTemplate ( 'layout/chururgie' );
		$view = new ViewModel ();
		//
		return $view;
	}


	public function vuePatientAdmisAction(){
		$this->getDateHelper();
	
		$chemin = $this->getServiceLocator()->get('Request')->getBasePath();
		$idPatient = (int)$this->params()->fromPost ('idPatient');
		$idAdmission = (int)$this->params()->fromPost ('idAdmission');
	
		$unPatient = $this->getPatientTable()->getInfoPatient($idPatient);
		$photo = $this->getPatientTable()->getPhoto($idPatient);
	
		//Informations sur l'admission
		$InfoAdmis = $this->getAdmissionTable()->getPatientAdmis($idAdmission);
	
		//Verifier si le patient a un rendez-vous et si oui dans quel service et a quel heure
		$today = new \DateTime ();
		$dateAujourdhui = $today->format( 'Y-m-d' );
		$RendezVOUS = $this->getPatientTable ()->verifierRV($idPatient, $dateAujourdhui);
	
		//Recuperer le service
		$InfoService = $this->getServiceTable()->getServiceAffectation($InfoAdmis->id_service);
	
		$date = $unPatient['DATE_NAISSANCE'];
		if($date){ $date = $this->convertDate ( $unPatient['DATE_NAISSANCE'] ); }else{ $date = null;} 
	
		 $html  = "<div style='width:100%;'>";
			
		$html .= "<div style='width: 18%; height: 180px; float:left;'>";
		$html .= "<div id='photo' style='float:left; margin-left:40px; margin-top:10px; margin-right:30px;'> <img style='width:105px; height:105px;' src='../img/photos_patients/" . $photo .  "' ></div>";
		$html .= "<div style='margin-left:60px; margin-top: 150px;'> <div style='text-decoration:none; font-size:14px; float:left; padding-right: 7px; '>Age:</div>  <div style='font-weight:bold; font-size:19px; font-family: time new romans; color: green; font-weight: bold;'>" . $unPatient['AGE'] . " ans</div></div>";
		$html .= "</div>";
			
		$html .= "<div style='width: 70%; height: 180px; float:left;'>";
		$html .= "<table id='vuePatientAdmission' style='margin-top:10px; float:left'>";
	
		$html .= "<tr style='width: 100%;'>";
		$html .= "<td style='width: 19%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nom:</a><br><div style='width: 150px; max-width: 160px; height:40px; overflow:auto; margin-bottom: 3px;'><p style='font-weight:bold; font-size:17px;'>" . $unPatient['NOM'] . "</p></div></td>";
		$html .= "<td style='width: 29%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Lieu de naissance:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['LIEU_NAISSANCE'] . "</p></div></td>";
		$html .= "<td style='width: 23%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nationalit&eacute;  d'origine:</a><br><div style='width: 95%; '><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['NATIONALITE_ORIGINE'] . "</p></div></td>";
		$html .= "<td style='width: 23%; vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Email:</a><br><div style='width: 100%; max-width: 235px; height:40px; overflow:auto;'><p style='font-weight:bold; font-size:17px;'>" . $unPatient['EMAIL'] . "</p></div></td>";
		$html .= "<td style='width: 29%; '></td>";
			
		$html .= "</tr><tr style='width: 100%;'>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Pr&eacute;nom:</a><br><div style='width: 95%; max-width: 130px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['PRENOM'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>T&eacute;l&eacute;phone:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['TELEPHONE'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>T&eacute;l&eacute;phone Proche:</a><br><div style='width: 95%; max-width: 250px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['TELEPHONE1'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Nationalit&eacute; actuelle:</a><br><div style='width: 95%; max-width: 135px; overflow:auto; '><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['NATIONALITE_ACTUELLE']. "</p></td>";

			
		$html .= "</tr><tr style='width: 100%;'>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Date de naissance:</a><br><div style='width: 95%; max-width: 130px; height:40px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $date . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Adresse:</a><br><div style='width: 97%; max-width: 250px; height:50px; overflow:auto; margin-bottom: 3px;'><p style=' font-weight:bold; font-size:17px;'>" . $unPatient['ADRESSE'] . "</p></div></td>";
		$html .= "<td style='vertical-align: top;'><a style='text-decoration:underline; font-size:12px;'>Profession:</a><br><div style='width: 95%; max-width: 235px; height:40px; overflow:auto; '><p style=' font-weight:bold; font-size:17px;'>" .  $unPatient['PROFESSION'] . "</p></div></td>";
	
		if($RendezVOUS){
			$html .= "<span> <i style='color:green;'>
					        <span id='image-neon' style='color:red; font-weight:bold;'>Rendez-vous! </span> <br>
					        <span style='font-size: 16px;'>Service:</span> <span style='font-size: 16px; font-weight:bold;'> ". $RendezVOUS[ 'NOM' ]." </span> <br>
					        <span style='font-size: 16px;'>Heure:</span>  <span style='font-size: 16px; font-weight:bold;'>". $RendezVOUS[ 'HEURE' ]." </span> </i>
			              </span>";
		}
	
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "</table>";
		$html .="</div>";
			
		$html .= "<div style='width: 12%; height: 180px; float:left; '>";
		$html .= "<div id='' style='color: white; opacity: 0.09; float:left; margin-right:0px; margin-left:0px; margin-top:5px;'> <img style='width:105px; height:105px;' src='../public/img/photos_patients/" . $photo . "'></div>";
		$html .= "</div>";
			
		$html .= "</div>";
	
		//$html .="<div id='titre_info_admis'>Informations sur la facturation <img id='button_pdf' style='width:15px; height:15px; float: right; margin-right: 35px; cursor: pointer;' src='../public/images_icons/button_pdf.png' title='Imprimer la facture' ></div>";
		//$html .="<div id='barre_separateur'></div>";
	
		$html .="<table style='margin-top:10px; margin-left:18%; width: 80%; margin-bottom: 60px;'>";
	
		//$html .="<tr style='width: 80%; '>";
		//$html .="<td style='width: 25%; vertical-align:top; margin-right:10px;'><span id='labelHeureLABEL' style='padding-left: 5px;'>Date d'admission </span><br><p id='zoneChampInfo1' style='background:#f8faf8; padding-left: 5px; padding-top: 5px;'> ". $this->dateHelper->convertDateTime($InfoAdmis->date_enregistrement) ." </p></td>";
		//$html .="<td style='width: 25%; vertical-align:top; margin-right:10px;'><span id='labelHeureLABEL' style='padding-left: 5px;'>Num&eacute;ro facture </span><br><p id='zoneChampInfo1' style='background:#f8faf8; padding-left: 5px; padding-top: 5px;'> ". $InfoAdmis->numero ." </p></td>";
		//$html .="<td style='width: 25%; vertical-align:top; margin-right:10px;'><span id='labelHeureLABEL' style='padding-left: 5px;'>Service </span><br><p id='zoneChampInfo1' style='background:#f8faf8; padding-left: 5px; padding-top: 5px; font-size:15px;'> ". $InfoService->nom ." </p></td>";
	//	$html .="<td style='width: 25%; vertical-align:top; margin-right:10px;'><span id='labelHeureLABEL' style='padding-left: 5px;'>Tarif (frs) </span><br><p id='zoneChampInfo1' style='background:#f8faf8; padding-left: 5px; padding-top: 5px; font-weight:bold; font-size:22px;'> ". $this->prixMill($InfoAdmis->montant)." </p></td>";
		//$html .="</tr>";
	
	
	
	
		$html .="</table>";
		$html .="<table style='margin-top:10px; margin-left:18%; width: 80%;'>";
		$html .="<tr style='width: 80%;'>";
	
		$html .="<td class='block' id='thoughtbot' style='width: 35%; display: inline-block;  vertical-align: bottom; padding-left:350px; padding-bottom: 15px; padding-right: 150px;'><button type='submit' id='terminer'>Terminer</button></td> <script >listepatient()</script>";
	
		$html .="</tr>";
		$html .="</table>";
	
		$html .="<div style='color: white; opacity: 1; margin-top: -100px; margin-right:20px; width:95px; height:40px; float:right'>
                          <img  src='".$chemin."/images_icons/fleur1.jpg' />
                     </div>";
	
		
		$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
		return $this->getResponse()->setContent(Json::encode($html));
	
	}


	//***-*-*-*-*-*-*-*-**-*-*-*-*--*-**-*-*-*-*-*-*-*--*--**-*-*-*-*-*-**-*-*-*--**-*-*-*-*-*--*-**-*-*-*-*-*-*-*-*-**-*-*-*-*-*-*-*-
	//***-**-*-*-*-*-**-*-*-*-*-*-*-*-*-*--**-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*--**-*-*-*-*-*-*-*-*-**-*-*-*-*-*-*-*-*-*-*-**--*-**-*-*-
	
	public function impressionPdfAction(){
		
		$user = $this->layout()->user;
		$serviceMedecin = $user['NomService'];
	      
		$nomMedecin = $user['Nom'];
		$prenomMedecin = $user['Prenom'];
		$donneesMedecin = array('nomMedecin' => $nomMedecin, 'prenomMedecin' => $prenomMedecin);
		//var_dump($donneesMedecin);exit();

		//*************************************
		//*************************************
		//***DONNEES COMMUNES A TOUS LES PDF***
		//*************************************
		//*************************************
// 		$id_pat = $this->params ()->fromRoute ( 'val', 0 );
// 		var_dump($id_pat);exit();
		$id_patient = $this->params ()->fromPost ( 'id_patient', 0 );
		//$id_patient= $this->params()->fromQuery ( 'id_patient', 0 );
		$id_cons = $this->params ()->fromPost ( 'id_cons', 0 );
	
		//var_dump($id_patient);exit();
		
		//*************************************
		$donneesPatientOR = $this->getConsultationTable()->getInfoPatient($id_patient);
		//var_dump($id_patient); exit();
		//**********ORDONNANCE*****************
		//**********ORDONNANCE*****************
		//**********ORDONNANCE*****************
		
		
		if(isset($_POST['ordonnance'])){
		    
		
			//r�cup�ration de la liste des m�dicaments
			$medicaments = $this->getConsultationTable()->fetchConsommable();
				
			$tab = array();
			$j = 1;
				
			//NOUVEAU CODE AVEC AUTOCOMPLETION
			for($i = 1 ; $i < 10 ; $i++ ){
				$nomMedicament = $this->params()->fromPost("medicament_0".$i);
				if($nomMedicament == true){
					$tab[$j++] = $this->params()->fromPost("medicament_0".$i);
					$tab[$j++] = $this->params()->fromPost("forme_".$i);
					$tab[$j++] = $this->params()->fromPost("nb_medicament_".$i);
					$tab[$j++] = $this->params()->fromPost("quantite_".$i);
				}
			}
				
			//-***************************************************************
			//Cr�ation du fichier pdf
			//*************************
			//Cr�er le document
			$DocPdf = new DocumentPdf();
			//Cr�er la page
			$page = new OrdonnancePdf();
	
			//Envoyer l'id_cons
			$page->setIdCons($id_cons);
			$page->setService($serviceMedecin);
			//Envoyer les donn�es sur le partient
			$page->setDonneesPatient($donneesPatientOR);
			//Envoyer les m�dicaments
			$page->setMedicaments($tab);
				
			//Ajouter une note � la page
			$page->addNote();
			//Ajouter la page au document
			$DocPdf->addPage($page->getPage());
	
			//Afficher le document contenant la page
			$DocPdf->getDocument();
		}
		else
		//**********TRAITEMENT CHIRURGICAL*****************
		//**********TRAITEMENT CHIRURGICAL*****************
		//**********TRAITEMENT CHIRURGICAL*****************
		if(isset($_POST['traitement_chirurgical'])){
			//R�cup�ration des donn�es
			$donneesDemande['diagnostic'] = $this->params ()->fromPost ( 'diagnostic_traitement_chirurgical' );
			$donneesDemande['intervention_prevue'] = $this->params ()->fromPost (  'intervention_prevue' );
			$donneesDemande['observation'] = $this->params()->fromPost('observation');
				
			//CREATION DU DOCUMENT PDF
			//Cr�er le document
			$DocPdf = new DocumentPdf();
			//Cr�er la page
			$page = new TraitementChirurgicalPdf();
				
			//Envoi Id de la consultation
			$page->setIdConsTC($id_cons);
			$page->setService($serviceMedecin);
			//Envoi des donn�es du patient
			$page->setDonneesPatientTC($donneesPatientOR);
			//Envoi des donn�es du medecin
			$page->setDonneesMedecinTC($donneesMedecin);
			//Envoi les donn�es de la demande
			$page->setDonneesDemandeTC($donneesDemande);
				
			//Ajouter les donnees a la page
			$page->addNoteTC();
			//Ajouter la page au document
			$DocPdf->addPage($page->getPage());
				
			//Afficher le document contenant la page
			$DocPdf->getDocument();
				
		}
		else
		//**********TRANSFERT DU PATIENT*****************
		//**********TRANSFERT DU PATIENT*****************
		//**********TRANSFERT DU PATIENT*****************
		if (isset ($_POST['transfert']))
		{
		    
			$id_hopital = $this->params()->fromPost('hopital_accueil');
			$id_service = $this->params()->fromPost('service_accueil');
			$motif_transfert = $this->params()->fromPost('motif_transfert');
	
			//R�cup�rer le nom du service d'accueil
			$service = $this->getServiceTable();
			$infoService = $service->getServiceparId($id_service);
			
			//R�cup�rer le nom de l'hopital d'accueil
			$hopital = $this->getHopitalTable();
			// var_dump('tester');exit();
			$infoHopital = $hopital->getHopitalParId($id_hopital);
			
			$donneesDemandeT['NomService'] = $infoService['NOM'];
			$donneesDemandeT['NomHopital'] = $infoHopital['NOM_HOPITAL'];
			$donneesDemandeT['MotifTransfert'] = $motif_transfert;
	
			//-***************************************************************
			//Cr�ation du fichier pdf
			//-***************************************************************
			//Cr�er le document
			$DocPdf = new DocumentPdf();
			//Cr�er la page
			$page = new TransfertPdf();
	
			//Envoi Id de la consultation
			$page->setIdConsT($id_cons);
			$page->setService($serviceMedecin);
			//Envoi des donn�es du patient
			$page->setDonneesPatientT($donneesPatientOR);
			//Envoi des donn�es du medecin
			$page->setDonneesMedecinT($donneesMedecin);
			//Envoi les donn�es de la demande
			$page->setDonneesDemandeT($donneesDemandeT);
	
			//Ajouter les donnees a la page
			$page->addNoteT();
			//Ajouter la page au document
			$DocPdf->addPage($page->getPage());
	
			//Afficher le document contenant la page
			$DocPdf->getDocument();
		}
		else
		//**********RENDEZ VOUS ****************
		//**********RENDEZ VOUS ****************
		//**********RENDEZ VOUS ****************
		if(isset ($_POST['rendezvous'])){
				
			$donneesDemandeRv['dateRv'] = $this->params()->fromPost('date_rv_tampon');
			$donneesDemandeRv['heureRV'] = $this->params()->fromPost('heure_rv_tampon');
			$donneesDemandeRv['MotifRV'] = $this->params()->fromPost('motif_rv');
			$donneesDemandeRv['Delai_rv'] = $this->params()->fromPost('delai_rv');
			//Cr�ation du fichier pdf
			//Cr�er le document
			$DocPdf = new DocumentPdf();
			//Cr�er la page
			$page = new RendezVousPdf();
	
			//Envoi Id de la consultation
			$page->setIdConsR($id_cons);
			$page->setService($serviceMedecin);
			//Envoi des donn�es du patient
			$page->setDonneesPatientR($donneesPatientOR);
			//Envoi des donn�es du medecin
			$page->setDonneesMedecinR($donneesMedecin);
			//Envoi les donn�es du redez vous
			$page->setDonneesDemandeR($donneesDemandeRv);
	
			//Ajouter les donnees a la page
			$page->addNoteR();
			//Ajouter la page au document
			$DocPdf->addPage($page->getPage());
	
			//Afficher le document contenant la page
			$DocPdf->getDocument();
				
		}
		else
		//**********TRAITEMENT INSTRUMENTAL ****************
		//**********TRAITEMENT INSTRUMENTAL ****************
		//**********TRAITEMENT INSTRUMENTAL ****************
		if(isset ($_POST['traitement_instrumental'])){
			//R�cup�ration des donn�es
			$donneesTraitementChirurgical['endoscopieInterventionnelle'] = $this->params ()->fromPost ( 'endoscopieInterventionnelle' );
			$donneesTraitementChirurgical['radiologieInterventionnelle'] = $this->params ()->fromPost (  'radiologieInterventionnelle' );
			$donneesTraitementChirurgical['cardiologieInterventionnelle'] = $this->params()->fromPost('cardiologieInterventionnelle');
			$donneesTraitementChirurgical['autresIntervention'] = $this->params()->fromPost('autresIntervention');
				
			//CREATION DU DOCUMENT PDF
			//Cr�er le document
			$DocPdf = new DocumentPdf();
			//Cr�er la page
			$page = new TraitementInstrumentalPdf();
				
			//Envoi Id de la consultation
			$page->setIdConsTC($id_cons);
			$page->setService($serviceMedecin);
			//Envoi des donn�es du patient
			$page->setDonneesPatientTC($donneesPatientOR);
			//Envoi des donn�es du medecin
			$page->setDonneesMedecinTC($donneesMedecin);
			//Envoi les donn�es de la demande
			$page->setDonneesDemandeTC($donneesTraitementChirurgical);
				
			//Ajouter les donnees a la page
			$page->addNoteTC();
			//Ajouter la page au document
			$DocPdf->addPage($page->getPage());
				
			//Afficher le document contenant la page
			$DocPdf->getDocument();
		}
		else
		//**********HOSPITALISATION ****************
		//**********HOSPITALISATION ****************
		//**********HOSPITALISATION ****************
		if(isset ($_POST['hospitalisation'])){
			//R�cup�ration des donn�es
			$donneesHospitalisation['motif_hospitalisation'] = $this->params ()->fromPost ( 'motif_hospitalisation' );
			$donneesHospitalisation['date_fin_hospitalisation_prevue'] = $this->params ()->fromPost (  'date_fin_hospitalisation_prevue' );
	
			//CREATION DU DOCUMENT PDF
			//Cr�er le document
			$DocPdf = new DocumentPdf();
			//Cr�er la page
			$page = new HospitalisationPdf();
			//Envoi Id de la consultation
			$page->setIdConsH($id_cons);
			$page->setService($serviceMedecin);
			//Envoi des donn�es du patient
			$page->setDonneesPatientH($donneesPatientOR);
			//Envoi des donn�es du medecin
			$page->setDonneesMedecinH($donneesMedecin);
			//Envoi les donn�es de la demande
			$page->setDonneesDemandeH($donneesHospitalisation);
	
			//Ajouter les donnees a la page
			$page->addNoteH();
			//Ajouter la page au document
			$DocPdf->addPage($page->getPage());
	
			//Afficher le document contenant la page
			$DocPdf->getDocument();
		}
		else
		//**********DEMANDES D'EXAMENS****************
		//**********DEMANDES D'EXAMENS****************
		//**********DEMANDES D'EXAMENS****************
		if(isset ($_POST['demandeExamenBioMorpho'])){
		    $i = 1; $j = 1;
		    $donneesExamensFonc = array();
		    $notesExamensFonc = array();
		    //R�cup�ration des donn�es examens fonctionnels
		    for( ; $i <= 6; $i++){
		        if($this->params ()->fromPost ( 'acte_name_'.$i )){
		            $donneesExamensFonc[$j] = $this->params ()->fromPost ( 'acte_name_'.$i );
		            $notesExamensFonc[$j++ ] = $this->params ()->fromPost ( 'noteActe_'.$i  );
		        }
		    }
		    
		    
		    
		    $i = 1; $j = 1;
			$donneesExamensBio = array();
			$notesExamensBio = array();
			//R�cup�ration des donn�es examens biologiques
			for( ; $i <= 6; $i++){
				if($this->params ()->fromPost ( 'examenBio_name_'.$i )){
					$donneesExamensBio[$j] = $this->params ()->fromPost ( 'examenBio_name_'.$i );
					$notesExamensBio[$j++ ] = $this->params ()->fromPost ( 'noteExamenBio_'.$i  );
				}
			}
	
			$k = 1; $l = $j;
			$donneesExamensMorph = array();
			$notesExamensMorph = array();
			//R�cup�ration des donn�es examens morphologiques
			for( ; $k <= 11; $k++){
				if($this->params ()->fromPost ( 'element_name_'.$k )){
					$donneesExamensMorph[$l] = $this->params ()->fromPost ( 'element_name_'.$k );
					$notesExamensMorph[$l++ ] = $this->params ()->fromPost ( 'note_'.$k  );
				}
			}
	
	
			//CREATION DU DOCUMENT PDF
			//Cr�er le document
			$DocPdf = new DocumentPdf();
			//Cr�er la page
			$page = new DemandeExamenPdf();
			//Envoi Id de la consultation
			$page->setIdConsBio($id_cons);
			$page->setService($serviceMedecin);
			//Envoi des donn�es du patient
			$page->setDonneesPatientBio($donneesPatientOR);
			//Envoi des donn�es du medecin
			$page->setDonneesMedecinBio($donneesMedecin);
			//Envoi les donn�es de la demande
			$page->setDonneesDemandeFonctionnel($donneesExamensFonc);
			$page->setNotesDemandeFonctionnel($notesExamensFonc);
			
			$page->setDonneesDemandeBio($donneesExamensBio);
			$page->setNotesDemandeBio($notesExamensBio);
			$page->setDonneesDemandeMorph($donneesExamensMorph);
			$page->setNotesDemandeMorph($notesExamensMorph);
	
				
			//Ajouter les donnees a la page
			$page->addNoteBio();
			//Ajouter la page au document
			$DocPdf->addPage($page->getPage());
				
			//Afficher le document contenant la page
			$DocPdf->getDocument();
		}
			
	}




	
	
	
	
	
	
	
	
	/***** LECTEUR MP3 ---- LECTEUR MP3  *****/
	/***** LECTEUR MP3 ---- LECTEUR MP3  *****/
	/***** LECTEUR MP3 ---- LECTEUR MP3  *****/
	public function lecteurMp3Action($ListeDesSons){
	    $html ='<script>
				 var tab = [];
		        </script>';
	    $i = 0;
	    foreach ($ListeDesSons as $Liste) {
	        $html .='<script>
        		 tab['.$i++.'] = {
	                     "title":"'. $Liste['titre'] .'<span class=\"supprimerSon'.$i.'\" >  </span>",
		                 "mp3":"/simens/public/audios/'. $Liste['nom'] .'",
		         };
		                     
		         setTimeout(function() {
	                $(function () {
		              $(".supprimerSon'.$i.'").click(function () { return false; });
		              $(".supprimerSon'.$i.'").dblclick(function () { supprimerAudioMp3('.$i.'); return false; });
	                });
                 }, 1000);
        		 </script>';
	    }
	    
	    $html .='<script>
        		$(function(){
	              new jPlayerPlaylist({
		          jPlayer: "#jquery_jplayer_2",
		          cssSelectorAncestor: "#jp_container_2"
	            }, tab , {
		        swfPath: "/simens/public/js/plugins/jPlayer-2.9.2/dist/jplayer",
		        supplied: "mp3",
		        wmode: "window",
		        useStateClassSkin: true,
		        autoBlur: false,
		        smoothPlayBar: true,
		        keyEnabled: true,
		        remainingDuration: true,
		        toggleDuration: true
	            });
                });
        		scriptAjoutMp3();
                </script>';
	    
	    $html .='
				<form id="my_form" method="post" action="/simens/public/chururgie/ajouter-mp3" enctype="multipart/form-data">
                <div id="jquery_jplayer_2" class="jp-jplayer" style="margin-bottom: 30px;"></div>
                <div id="jp_container_2" class="jp-audio" role="application" aria-label="media player"  style="margin-bottom: 30px;">
	            <div class="jp-type-playlist">
		         <div class="jp-gui jp-interface">
			       <div class="jp-controls">
				      <button class="jp-previous" role="button" tabindex="0">previous</button>
				      <button class="jp-play" role="button" tabindex="0">play</button>
				      <button class="jp-next" role="button" tabindex="0">next</button>
				      <button class="jp-stop" role="button" tabindex="0">stop</button>
			       </div>
			   <div class="jp-progress">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>
				</div>
			   </div>
			   <div class="jp-volume-controls">
				<button class="jp-mute" role="button" tabindex="0">mute</button>
				<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			   </div>
			   <div class="jp-time-holder">
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
			   </div>
			   <div class="jp-toggles">
				<button class="jp-repeat" role="button" tabindex="0">repeat</button>
				<!-- button class="jp-shuffle" role="button" tabindex="0">shuffle</button-->
				<div class="jp-ajouter" id="ajouter">
				  <input type="file" name="fichier" id="fichier">
				</div>
			   </div>
		       </div>
		       <div class="jp-playlist">
			      <ul>
				     <li>&nbsp;</li>
			      </ul>
		       </div>
	           </div>
               </div>
               </form>';
	    return $html;
	}
	
	public function lecteurMp3InstrumentalAction($ListeDesSons){
	    $html ='<script>
				 var tab = [];
		        </script>';
	    $i = 0;
	    foreach ($ListeDesSons as $Liste) {
	        $html .='<script>
        		 tab['.$i++.'] = {
	                     "title":"'. $Liste['titre'] .'<span class=\"supprimerSonIns'.$i.'\" >  </span>",
		                 "mp3":"/simens/public/audios/'. $Liste['nom'] .'",
		         };
		                     
		         setTimeout(function() {
	                $(function () {
		              $(".supprimerSonIns'.$i.'").click(function () { return false; });
		              $(".supprimerSonIns'.$i.'").dblclick(function () { supprimerAudioMp3('.$i.'); return false; });
	                });
                 }, 1000);
        		 </script>';
	    }
	    
	    $html .='<script>
        		$(function(){
	              new jPlayerPlaylist({
		          jPlayer: "#jquery_jplayer",
		          cssSelectorAncestor: "#jp_container"
	            }, tab , {
		        swfPath: "/simens/public/js/plugins/jPlayer-2.9.2/dist/jplayer",
		        supplied: "mp3",
		        wmode: "window",
		        useStateClassSkin: true,
		        autoBlur: false,
		        smoothPlayBar: true,
		        keyEnabled: true,
		        remainingDuration: true,
		        toggleDuration: true
	            });
                });
        		scriptAjoutMp3_Instrumental();
                </script>';
	    
	    $html .='
				<form id="my_form2" method="post" action="/simens/public/chururgie/ajouter-mp3" enctype="multipart/form-data">
                <div id="jquery_jplayer" class="jp-jplayer" style="margin-bottom: 30px;"></div>
                <div id="jp_container" class="jp-audio" role="application" aria-label="media player"  style="margin-bottom: 30px;">
	            <div class="jp-type-playlist">
		         <div class="jp-gui jp-interface">
			       <div class="jp-controls">
				      <button class="jp-previous" role="button" tabindex="0">previous</button>
				      <button class="jp-play" role="button" tabindex="0">play</button>
				      <button class="jp-next" role="button" tabindex="0">next</button>
				      <button class="jp-stop" role="button" tabindex="0">stop</button>
			       </div>
			   <div class="jp-progress">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>
				</div>
			   </div>
			   <div class="jp-volume-controls">
				<button class="jp-mute" role="button" tabindex="0">mute</button>
				<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			   </div>
			   <div class="jp-time-holder">
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
			   </div>
			   <div class="jp-toggles">
				<button class="jp-repeat" role="button" tabindex="0">repeat</button>
				<!-- button class="jp-shuffle" role="button" tabindex="0">shuffle</button-->
				<div class="jp-ajouter" id="ajouter2">
				  <input type="file" name="fichier" id="fichier">
				</div>
			   </div>
		       </div>
		       <div class="jp-playlist">
			      <ul>
				     <li>&nbsp;</li>
			      </ul>
		       </div>
	           </div>
               </div>
               </form>';
	    return $html;
	}
	
	public function afficherMp3Action(){
	    $id_cons = $this->params()->fromPost('id_cons', 0);
	    $type = (int)$this->params()->fromPost('type', 0);
	    
	    $ListeDesSons = $this->getConsultationTable ()->getMp3($id_cons, $type);
	    
	    $html = null;
	    if($type == 1){
	        $html = $this->lecteurMp3Action($ListeDesSons);
	    }else {
	        $html = $this->lecteurMp3InstrumentalAction($ListeDesSons);
	    }
	    
	    
	    $this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
	    return $this->getResponse ()->setContent ( Json::encode ($html) );
	}
	
	public function supprimerMp3Action(){
	    $id = $this->params()->fromPost('id', 0);
	    $id_cons = $this->params()->fromPost('id_cons', 0);
	    $type = $this->params()->fromPost('type', 0);
	    
	    $this->getConsultationTable ()->supprimerMp3($id, $id_cons, $type);
	    
	    $ListeDesSons = $this->getConsultationTable ()->getMp3($id_cons, $type);
	    
	    $html = null;
	    if($type == 1){
	        $html = $this->lecteurMp3Action($ListeDesSons);
	    }else {
	        $html = $this->lecteurMp3InstrumentalAction($ListeDesSons);
	    }
	    $this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
	    return $this->getResponse ()->setContent ( Json::encode ($html) );
	}
	
	public function ajouterMp3Action(){
	    
	    $type = $_FILES['fichier']['type'];
	    //$nom_file = $_FILES['fichier']['name'];
	    $tmp = $_FILES['fichier']['tmp_name'];
	    
	    $date = new \DateTime();
	    $aujourdhui = $date->format('H-i-s_d-m-y');
	    $nom_file = "audio_".$aujourdhui.".mp3";
	    
	    if($type == 'audio/mp3'){
	        $result = move_uploaded_file($tmp, 'C:\wamp\www\simens\public\audios\\'.$nom_file);
	    } else {
	        $nom_file = 0;
	    }
	    
	    $this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
	    return $this->getResponse ()->setContent ( Json::encode ($nom_file) );
	    
	}
	
	public function insererBdMp3Action(){
	    $id_cons = $this->params()->fromPost('id_cons', 0);
	    $nom_file = $this->params()->fromPost('nom_file', 0);
	    $type = $this->params()->fromPost('type', 0);
	    
	    $this->getConsultationTable ()->insererMp3($nom_file, $nom_file, $id_cons, $type);
	    $ListeDesSons = $this->getConsultationTable ()->getMp3($id_cons, $type);
	    
	    $html = null;
	    if($type == 1){
	        $html = $this->lecteurMp3Action($ListeDesSons);
	    }else {
	        $html = $this->lecteurMp3InstrumentalAction($ListeDesSons);
	    }
	    
	    $this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
	    return $this->getResponse ()->setContent ( Json::encode ($html) );
	}
	
	
	/***** LECTEUR VIDEO ---- LECTEUR VIDEO  *****/
	/***** LECTEUR VIDEO ---- LECTEUR VIDEO  *****/
	/***** LECTEUR VIDEO ---- LECTEUR VIDEO  *****/
	public function lecteurVideoAction($ListeDesVideos){
	    
	    $html ='<script>
				 var tab = [];
		        </script>';
	    $i = 0;
	    foreach ($ListeDesVideos as $Liste) {
	        
	        if($Liste['format'] == "video/mp4" || $Liste['format'] == "video/m4v") {
	            $html .='<script>
        		 tab['.$i++.'] = {
	                     "title":"'. $Liste['titre'] .' <span class=\'supprimerVideoIns'.$i.'\' >  </span>",
		                 "m4v":"/simens/public/videos/'. $Liste['nom'] .'",
		         };
		                     
		         setTimeout(function() {
	                $(function () {
		              $(".supprimerVideoIns'.$i.'").click(function () { return false; });
		              $(".supprimerVideoIns'.$i.'").dblclick(function () { supprimerVideo('.$Liste['id'].'); return false; });
	                });
                 }, 1000);
        		 </script>';
	        }
	        else
	            if($Liste['format'] == "video/webm") {
	                $html .='<script>
        		 tab['.$i++.'] = {
	                     "title":"'. $Liste['titre'] .'<span class=\'supprimerVideoIns'.$i.'\' >  </span>",
		                 "webmv":"/simens/public/videos/'. $Liste['nom'] .'",
		         };
		                     
		         setTimeout(function() {
	                $(function () {
		              $(".supprimerVideoIns'.$i.'").click(function () { return false; });
		              $(".supprimerVideoIns'.$i.'").dblclick(function () { supprimerVideo('.$Liste['id'].'); return false; });
	                });
                 }, 1000);
        		 </script>';
	            }
	    }
	    
	    $html .='<script>
				 $(document).ready(function(){
	        
	               new jPlayerPlaylist({
		             jPlayer: "#jquery_jplayer_1",
		             cssSelectorAncestor: "#jp_container_1"
	               },
				      tab
				    ,{
		               swfPath: "../../dist/jplayer",
		               supplied: "webmv, ogv, m4v",
		               useStateClassSkin: true,
		               autoBlur: false,
		               smoothPlayBar: true,
		               keyEnabled: true
	               });
	        
                 });
	        
				scriptAjoutVideo();
		        </script>';
	    
	    $html .='
	        
		<form id="my_form_video" method="post" action="/simens/public/chururgie/ajouter-video" enctype="multipart/form-data">
		<div id="jp_container_1" class="jp-video jp-video-270p" role="application" aria-label="media player" style="margin: auto;">
	    <div class="jp-type-playlist">
		<div id="jquery_jplayer_1" class="jp-jplayer"></div>
		<div class="jp-gui">
			<div class="jp-video-play">
				<button class="jp-video-play-icon" role="button" tabindex="0">play</button>
			</div>
			<div class="jp-interface">
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
					</div>
				</div>
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
				<div class="jp-controls-holder">
					<div class="jp-controls">
						<button class="jp-previous" role="button" tabindex="0">previous</button>
						<button class="jp-play" role="button" tabindex="0">play</button>
						<button class="jp-next" role="button" tabindex="0">next</button>
						<button class="jp-stop" role="button" tabindex="0">stop</button>
					</div>
					<div class="jp-volume-controls">
						<button class="jp-mute" role="button" tabindex="0">mute</button>
						<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
						<div class="jp-volume-bar">
							<div class="jp-volume-bar-value"></div>
						</div>
					</div>
					<div class="jp-toggles">
						<button class="jp-full-screen" role="button" tabindex="0">full screen</button>
					</div>
	        
				    <div class="jp-toggles2" id="jp-toggles-video" >
				        <div class="jp-ajouter-video">
				           <input type="file" name="fichier-video" id="fichier-video">
				        </div>
					</div>
	        
				</div>
				<div class="jp-details">
					<div class="jp-title" aria-label="title">&nbsp;</div>
				</div>
			</div>
		</div>
		<div class="jp-playlist">
			<ul>
				<li>&nbsp;</li>
			</ul>
		</div>
	    </div>
        </div>
		</form>';
	    return $html;
	}
	
	public function afficherVideoAction(){
	    $id_cons = $this->params()->fromPost('id_cons', 0);
	    $listeDesVideos = $this->getConsultationTable()->getVideos($id_cons);
	    $html = $this->lecteurVideoAction($listeDesVideos);
	    
	    $this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
	    return $this->getResponse ()->setContent ( Json::encode ( $html ) );
	}
	
	
	public function ajouterVideoAction(){
	    
	    $type = $_FILES['fichier-video']['type'];
	    $nom_file = ""; //$_FILES['fichier-video']['name'];
	    $tmp = $_FILES['fichier-video']['tmp_name'];
	    
	    $date = new \DateTime();
	    $aujourdhui = $date->format('H-i-s_d-m-y');
	    
	    /**
	     * 'video/mp4' pour chrome
	     * 'video/x-m4v pour firefox
	     */
	    
	    if($type == 'video/webm' || $type == 'video/mp4' || $type == 'video/x-m4v'){
	        if($type == 'video/webm'){
	            $nom_file ="v_scan_".$aujourdhui.".webm";
	        }
	        else
	            if($type == 'video/mp4'){
	                $nom_file ="v_scan_".$aujourdhui.".mp4";
	        }
	        else
	            if($type == 'video/x-m4v'){
	                $nom_file ="v_scan_".$aujourdhui.".m4v";
	                $type = 'video/m4v';
	        }
	        $result = move_uploaded_file($tmp, 'C:\wamp\www\simens\public\videos\\'.$nom_file);
	    }
	    
	    $html = array($nom_file, $type);
	    $this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
	    return $this->getResponse ()->setContent ( Json::encode ($html) );
	    
	}
	
	
	public function insererBdVideoAction(){
	    $id_cons = $this->params()->fromPost('id_cons', 0);
	    $nom_file = $this->params()->fromPost('nom_file', 0);
	    $type_file = $this->params()->fromPost('type_file', 0);
	    
	    $html = 0;
	    if($type_file == 'video/webm' || $type_file == 'video/mp4' || $type_file == 'video/m4v'){
	        $this->getConsultationTable ()->insererVideo($nom_file, $nom_file, $type_file, $id_cons);
	        $ListeDesVideos = $this->getConsultationTable ()->getVideos($id_cons);
	        $html = $this->lecteurVideoAction($ListeDesVideos);
	    }
	    
	    
	    $this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
	    return $this->getResponse ()->setContent ( Json::encode ($html) );
	}
	
	public function supprimerVideoAction(){
	    $id = $this->params()->fromPost('id', 0);
	    
	    $result = $this->getConsultationTable ()->supprimerVideo($id);
	    
	    $this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html; charset=utf-8' );
	    return $this->getResponse ()->setContent ( Json::encode ($result) );
	}
	
	
	
	
	
	//********************************************************
	//********************************************************
	//********************************************************
	public function imagesExamensMorphologiquesAction()
	{
	    $id_cons = $this->params()->fromPost( 'id_cons' );
	    $ajout = (int)$this->params()->fromPost( 'ajout' );
	    $idExamen = (int)$this->params()->fromPost( 'typeExamen' ); /*Le type d'examen*/
	    $utilisateur = (int)$this->params()->fromPost( 'utilisateur' ); /* 1==radiologue sinon Medecin  */
	    
	    $user = $this->layout()->user;
	    $id_personne = $user['id_personne'];
	    
	    /***
	     * INSERTION DE LA NOUVELLE IMAGE
	     */
	    if($ajout == 1) {
	        /***
	         * Enregistrement de l'image
	         * Enregistrement de l'image
	         * Enregistrement de l'image
	         */
	        $today = new \DateTime ( 'now' );
	        $nomImage = $today->format ( 'dmy_His' );
	        if($idExamen == 8) { $nomImage = "radio_".$nomImage;}
	        if($idExamen == 9) { $nomImage = "echographie_".$nomImage;}
	        if($idExamen == 10) { $nomImage = "irm_".$nomImage;}
	        if($idExamen == 11) { $nomImage = "scanner_".$nomImage;}
	        if($idExamen == 12) { $nomImage = "fibroscopie_".$nomImage;}
	        
	        $date_enregistrement = $today->format ( 'Y-m-d H:i:s' );
	        $fileBase64 = $this->params ()->fromPost ( 'fichier_tmp' );
	        
	        $typeFichier = substr ( $fileBase64, 5, 5 );
	        $formatFichier = substr ($fileBase64, 11, 4 );
	        $fileBase64 = substr ( $fileBase64, 23 );
	        
	        if($utilisateur == 1){
	            
	            if($fileBase64 && $typeFichier == 'image' && $formatFichier =='jpeg'){
	                $img = imagecreatefromstring(base64_decode($fileBase64));
	                if($img){
	                    $resultatAjout = $this->demandeExamensTable()->ajouterImageMorpho($id_cons, $idExamen, $nomImage, $date_enregistrement, $id_personne);
	                }
	                if($resultatAjout){
	                    imagejpeg ( $img, 'C:\wamp\www\simens\public\images\images\\' . $nomImage . '.jpg' );
	                }
	            }
	            
	        }else {
	            
	            if($fileBase64 && $typeFichier == 'image' && $formatFichier =='jpeg'){
	                $img = imagecreatefromstring(base64_decode($fileBase64));
	                if($img){
	                    $resultatAjout = $this->demandeExamensTable()->ajouterImage($id_cons, $idExamen, $nomImage, $date_enregistrement, $id_personne);
	                }
	                if($resultatAjout){
	                    imagejpeg ( $img, 'C:\wamp\www\simens\public\images\images\\' . $nomImage . '.jpg' );
	                }
	            }
	            
	        }
	        
	    }
	    
	    /**
	     * RECUPERATION DE TOUS LES RESULTATS DES EXAMENS MORPHOLOGIQUES
	     */
	    if($utilisateur == 1){
	        $result = $this->demandeExamensTable()->resultatExamensMorpho($id_cons);
	    }else {
	        $result = $this->demandeExamensTable()->resultatExamens($id_cons);
	    }
	    
	    $radio = false;
	    $echographie = false;
	    $irm = false;
	    $scanner = false;
	    $fibroscopie = false;
	    
	    $html = "";
	    $pickaChoose = "";
	    
	    if($result){
	        foreach ($result as $resultat) {
	            /**==========================**/
	            /**Recuperer les images RADIO**/
	            /**==========================**/
	            if($resultat['idExamen'] == 8 && $idExamen == 8){
	                $radio = true;
	                $pickaChoose .=" <li><a href='../images/images/".$resultat['NomImage'].".jpg'><img src='../images/images/".$resultat['NomImage'].".jpg'/></a><span></span></li>";
	            } else
	                /**================================**/
	                    /**Recuperer les images ECHOGRAPHIE**/
	                        /**================================**/
	                            if($resultat['idExamen'] == 9 && $idExamen == 9){
	                                $echographie = true;
	                                $pickaChoose .=" <li><a href='../images/images/".$resultat['NomImage'].".jpg'><img src='../images/images/".$resultat['NomImage'].".jpg'/></a><span></span></li>";
	                        } else
	                            /**================================**/
	                                /**Recuperer les images IRM**/
	                                    /**================================**/
	                                        if($resultat['idExamen'] == 10 && $idExamen == 10){
	                                            $irm = true;
	                                            $pickaChoose .=" <li><a href='../images/images/".$resultat['NomImage'].".jpg'><img src='../images/images/".$resultat['NomImage'].".jpg'/></a><span></span></li>";
	                                    } else
	                                        /**================================**/
	                                            /**Recuperer les images SCANNER**/
	                                                /**================================**/
	                                                    if($resultat['idExamen'] == 11 && $idExamen == 11){
	                                                        $scanner = true;
	                                                        $pickaChoose .=" <li><a href='../images/images/".$resultat['NomImage'].".jpg'><img src='../images/images/".$resultat['NomImage'].".jpg'/></a><span></span></li>";
	                                                } else
	                                                    /**================================**/
	                                                        /**Recuperer les images FIBROSCOPIE**/
	                                                            /**================================**/
	                                                                if($resultat['idExamen'] == 12 && $idExamen == 12){
	                                                                    $fibroscopie = true;
	                                                                    $pickaChoose .=" <li><a href='../images/images/".$resultat['NomImage'].".jpg'><img src='../images/images/".$resultat['NomImage'].".jpg'/></a><span></span></li>";
	                                                            }
	        }
	    }
	    
	    if($radio) {
	        $html ="<div id='pika2'>
				    <div class='pikachoose' style='height: 210px;'>
                      <ul id='pikame' class='jcarousel-skin-pika'>";
	        $html .=$pickaChoose;
	        $html .=" </ul>
                     </div>
				     </div>";
	        
	        $html.="<script>
					  $(function(){ $('.imageRadio').toggle(true);});
					  scriptExamenMorpho();
					</script>";
	    } else
	        if($echographie) {
	            $html ="<div id='pika4'>
				        <div class='pikachoose' style='height: 210px;'>
                          <ul id='pikameEchographie' class='jcarousel-skin-pika'>";
	            $html .=$pickaChoose;
	            $html .=" </ul>
                         </div>
				         </div>";
	            
	            $html.="<script>
						  $(function(){ $('.imageEchographie').toggle(true);});
					      scriptEchographieExamenMorpho();
					    </script>";
	        } else
	            if($irm) {
	                $html ="<div id='pika6'>
				             <div class='pikachoose' style='height: 210px;'>
                              <ul id='pikameIRM' class='jcarousel-skin-pika'>";
	                $html .=$pickaChoose;
	                $html .=" </ul>
                              </div>
				             </div>";
	                
	                $html.="<script>
						     $(function(){ $('.imageIRM').toggle(true);});
					         scriptIRMExamenMorpho();
					        </script>";
	            } else
	                if($scanner) {
	                    $html ="<div id='pika8'>
				             <div class='pikachoose' style='height: 210px;'>
                              <ul id='pikameScanner' class='jcarousel-skin-pika'>";
	                    $html .=$pickaChoose;
	                    $html .=" </ul>
                              </div>
				             </div>";
	                    
	                    $html.="<script>
						     $(function(){ $('.imageScanner').toggle(true);});
					         scriptScannerExamenMorpho();
					        </script>";
	                } else
	                    if($fibroscopie) {
	                        $html ="<div id='pika10'>
				             <div class='pikachoose' style='height: 210px;'>
                              <ul id='pikameFibroscopie' class='jcarousel-skin-pika'>";
	                        $html .=$pickaChoose;
	                        $html .=" </ul>
                              </div>
				             </div>";
	                        
	                        $html.="<script>
						     $(function(){ $('.imageFibroscopie').toggle(true);});
					         scriptFibroscopieExamenMorpho();
					        </script>";
	                    }
	                
	                $this->getResponse()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html' );
	                return $this->getResponse ()->setContent(Json::encode ( $html ));
	}
	
	
	//************************************************************************************
	//************************************************************************************
	//************************************************************************************
	public function supprimerImageAction()
	{
	    $id_cons = $this->params()->fromPost('id_cons');
	    $id = $this->params()->fromPost('id'); //numero de l'image dans le diapo
	    $typeExamen = $this->params()->fromPost('typeExamen');
	    
	    /**
	     * RECUPERATION DE TOUS LES RESULTATS DES EXAMENS MORPHOLOGIQUES
	     */
	    $result = $this->demandeExamensTable()->recupererDonneesExamen($id_cons, $id, $typeExamen);
	    /**
	     * SUPPRESSION PHYSIQUE DE L'IMAGE
	     */
	    unlink ( 'C:\wamp\www\simens\public\images\images\\' . $result['NomImage'] . '.jpg' );
	    /**
	     * SUPPRESSION DE L'IMAGE DANS LA BASE
	     */
	    $this->demandeExamensTable()->supprimerImage($result['IdImage']);
	    
	    $this->getResponse()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html' );
	    return $this->getResponse ()->setContent(Json::encode ( ));
	}
	
	/** POUR LES EXAMENS MORPHOLOGIQUES **/
	/** POUR LES EXAMENS MORPHOLOGIQUES **/
	/** POUR LES EXAMENS MORPHOLOGIQUES **/
	public function supprimerImageMorphoAction()
	{
	    $id_cons = $this->params()->fromPost('id_cons');
	    $id = $this->params()->fromPost('id'); //numero de l'image dans le diapo
	    $typeExamen = $this->params()->fromPost('typeExamen');
	    
	    /**
	     * RECUPERATION DE TOUS LES RESULTATS DES EXAMENS MORPHOLOGIQUES
	     */
	    $result = $this->demandeExamensTable()->recupererDonneesExamenMorpho($id_cons, $id, $typeExamen);
	    /**
	     * SUPPRESSION PHYSIQUE DE L'IMAGE
	     */
	    unlink ( 'C:\wamp\www\simens\public\images\images\\' . $result['NomImage'] . '.jpg' );
	    /**
	     * SUPPRESSION DE L'IMAGE DANS LA BASE
	     */
	    $this->demandeExamensTable()->supprimerImage($result['IdImage']);
	    
	    $this->getResponse()->getHeaders ()->addHeaderLine ( 'Content-Type', 'application/html' );
	    return $this->getResponse ()->setContent(Json::encode ());
	}
	
}


?>