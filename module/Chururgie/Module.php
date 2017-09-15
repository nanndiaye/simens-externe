<?php

namespace Chururgie;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Chururgie\Model\Patient;
use Chururgie\Model\PatientTable;
use Chururgie\Model\ServiceTable;
use Chururgie\Model\TarifConsultation;
use Chururgie\Model\TarifConsultationTable;
use Chururgie\Model\Service;
use Chururgie\Model\Admission;
use Chururgie\Model\AdmissionTable;


use Chururgie\Model\Consultation;
use Chururgie\Model\ConsultationTable;
use Chururgie\Model\MotifAdmission;
use Chururgie\Model\RvPatientConsTable;
use Chururgie\Model\RvPatientCons;
use Chururgie\Model\MotifAdmissionTable;
use Chururgie\Model\TransfererPatientServiceTable;
use Chururgie\Model\TransfererPatientService;
use Chururgie\Model\DonneesExamensPhysiquesTable;
use Chururgie\Model\DonneesExamensPhysiques;
use Chururgie\Model\DiagnosticsTable;
use Chururgie\Model\Diagnostics;
use Chururgie\Model\Ordonnance;
use Chururgie\Model\OrdonnanceTable;
use Chururgie\Model\DemandeVisitePreanesthesiqueTable;
use Chururgie\Model\DemandeVisitePreanesthesique;
use Chururgie\Model\NotesExamensMorphologiquesTable;
use Chururgie\Model\NotesExamensMorphologiques;
use Chururgie\Model\DemandeTable;
use Chururgie\Model\Demande;
use Chururgie\Model\OrdonConsommable;
use Chururgie\Model\OrdonConsommableTable;
use Chururgie\Model\AntecedentPersonnelTable;
use Chururgie\Model\AntecedentPersonnel;
use Chururgie\Model\AntecedentsFamiliauxTable;
use Chururgie\Model\AntecedentsFamiliaux;
use Chururgie\Model\DemandehospitalisationTable;
use Chururgie\Model\Demandehospitalisation;
use Chururgie\Model\Soinhospitalisation;
use Chururgie\Model\SoinhospitalisationTable;
use Chururgie\Model\SoinsTable;
use Chururgie\Model\Soins;
use Chururgie\Model\HospitalisationTable;
use Chururgie\Model\Hospitalisation;
use Chururgie\Model\HospitalisationlitTable;
use Chururgie\Model\Hospitalisationlit;
use Chururgie\Model\LitTable;
use Chururgie\Model\Lit;
use Chururgie\Model\SalleTable;
use Chururgie\Model\Salle;
use Chururgie\Model\BatimentTable;
use Chururgie\Model\Batiment;
use Chururgie\Model\ResultatVisitePreanesthesiqueTable;
use Chururgie\Model\ResultatVisitePreanesthesique;
use Chururgie\Model\DemandeActeTable;
use Chururgie\Model\DemandeActe;

use Chururgie\Model\OrganeTable;
use Chururgie\Model\ClassePathologieTable;
use Chururgie\Model\ClassePathologie;
use Chururgie\Model\ClasseOrganePathologieTable;
use Chururgie\Model\ClasseOrganePathologie;
use Chururgie\Model\TypePathologieTable;
use Chururgie\Model\TypePathologie;
use Chururgie\Model\ConsultantOrganeTable;
use Chururgie\Model\ConsultantOrgane;



class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface, ViewHelperProviderInterface {

	public function registerJsonStrategy(MvcEvent $e)
	{
		$app          = $e->getTarget();
		$locator      = $app->getServiceManager();
		$view         = $locator->get('Zend\View\View');
		$jsonStrategy = $locator->get('ViewJsonStrategy');

		// Attach strategy, which is a listener aggregate, at high priority
		$view->getEventManager()->attach($jsonStrategy, 100);
	}

	public function getAutoloaderConfig() {
		return array (
				'Zend\Loader\StandardAutoloader' => array (
						'namespaces' => array (
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
						)
				)
		);
	}
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}
	public function getServiceConfig() {
		return array (
				'factories' => array (
                                    
                         
		
				    
                                    'Chururgie\Model\TypePathologieTable' => function ($sm) {
				    $tableGateway = $sm->get('typePathologieFactGateway');
				    $table = new TypePathologieTable($tableGateway);
				    return $table;
				    },
				    'TypePathologieTableFactGateway' => function($sm) {
				    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				    $resultSetPrototype = new ResultSet();
				    $resultSetPrototype->setArrayObjectPrototype(new TypePathologieTable());
				    return new TableGateway('type_pathologie', $dbAdapter, null, $resultSetPrototype);
				    },
                                    
                                    
                                    'Chururgie\Model\ClasseOrganePathologieTable' => function ($sm) {
				    $tableGateway = $sm->get('ClasseOrganePathologieFactGateway');
				    $table = new ClasseOrganePathologieTable($tableGateway);
				    return $table;
				    },
				    'ClasseOrganePathologieTableFactGateway' => function($sm) {
				    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				    $resultSetPrototype = new ResultSet();
				    $resultSetPrototype->setArrayObjectPrototype(new ClasseOrganePathologie());
				    return new TableGateway('classe_organe_pathologie', $dbAdapter, null, $resultSetPrototype);
				    },
                                     'Chururgie\Model\ConultantOrganeTable' => function ($sm) {
				    $tableGateway = $sm->get('ConsultantOrganeFactGateway');
				    $table = new ConultantOrganeTable($tableGateway);
				    return $table;
				    },
				    'ConsultantOrganeTableFactGateway' => function($sm) {
				    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				    $resultSetPrototype = new ResultSet();
				    $resultSetPrototype->setArrayObjectPrototype(new ConsultantOrgane());
				    return new TableGateway('consultant_organe', $dbAdapter, null, $resultSetPrototype);
				    },
				    'Chururgie\Model\OrganeTable' => function ($sm) {
				    $tableGateway = $sm->get('OrganeFactGateway');
				    $table = new OrganeTable($tableGateway);
				    return $table;
				    },
				    'OrganeTableFactGateway' => function($sm) {
				    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				    $resultSetPrototype = new ResultSet();
				    $resultSetPrototype->setArrayObjectPrototype(new Organe());
				    return new TableGateway('organe', $dbAdapter, null, $resultSetPrototype);
				    },
				    'Chururgie\Model\ClassePathologieTable' => function ($sm) {
				    $tableGateway = $sm->get('ClassePathologieFactGateway');
				    $table = new ClassePathologieTable($tableGateway);
				    return $table;
				    },
				    'ClassePathologieTableFactGateway' => function($sm) {
				    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				    $resultSetPrototype = new ResultSet();
				    $resultSetPrototype->setArrayObjectPrototype(new ClassePathologie());
				    return new TableGateway('classe_pathologie', $dbAdapter, null, $resultSetPrototype);
				    },
						'Chururgie\Model\PatientTable' => function ($sm) {
							$tableGateway = $sm->get ( 'PatientTable1Gateway' );
							$table = new PatientTable ( $tableGateway );
							return $table;
						},
						'PatientTable1Gateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Patient () );
							return new TableGateway ( 'patient', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\ServiceTable' => function ($sm) {
							$tableGateway = $sm->get('ServiceTableFactGateway');
							$table = new ServiceTable($tableGateway);
							return $table;
						},
						'ServiceTableFactGateway' => function($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype(new Service());
							return new TableGateway('service', $dbAdapter, null, $resultSetPrototype);
						},
						'Chururgie\Model\TarifConsultationTable' => function ($sm) {
							$tableGateway = $sm->get( 'TarifConsultationTableGateway' );
							$table = new TarifConsultationTable( $tableGateway );
							return $table;
						},
						'TarifConsultationTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype (new TarifConsultation());
							return new TableGateway ( 'tarif_consultation', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\AdmissionTable' => function ($sm) {
							$tableGateway = $sm->get( 'AdmissionTableGateway' );
							$table = new AdmissionTable( $tableGateway );
							return $table;
						},
						'AdmissionTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Admission() );
							return new TableGateway ( 'admission', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\ConsultationTable' => function ($sm) {
							$tableGateway = $sm->get ( 'ConsultationTableConsGateway' );
							$table = new ConsultationTable ( $tableGateway );
							return $table;
						},
						'ConsultationTableConsGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new Consultation());
							return new TableGateway ( 'consultation', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\MotifAdmissionTable' => function ($sm) {
							$tableGateway = $sm->get ( 'MotifAdmissionTableGateway' );
							$table = new MotifAdmissionTable($tableGateway);
							return $table;
						},
						'MotifAdmissionTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new MotifAdmission());
							return new TableGateway ( 'motif_admission', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\RvPatientConsTable' => function ($sm) {
							$tableGateway = $sm->get ( 'RvPatientConsTableGateway' );
							$table = new RvPatientConsTable ( $tableGateway );
							return $table;
						},
						'RvPatientConsTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new RvPatientCons());
							return new TableGateway ( 'rendezvous_consultation', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\TransfererPatientServiceTable' => function ($sm) {
							$tableGateway = $sm->get ( 'TransfererPatientServiceTableGateway' );
							$table = new TransfererPatientServiceTable($tableGateway);
							return $table;
						},
						'TransfererPatientServiceTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new TransfererPatientService());
							return new TableGateway ( 'transferer_patient_service', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\DonneesExamensPhysiquesTable' => function ($sm) {
							$tableGateway = $sm->get ( 'DonneesExamensPhysiquesTableGateway' );
							$table = new DonneesExamensPhysiquesTable($tableGateway);
							return $table;
						},
						'DonneesExamensPhysiquesTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new DonneesExamensPhysiques());
							return new TableGateway ( 'Donnees_examen_physique', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\DiagnosticsTable' => function ($sm) {
							$tableGateway = $sm->get ( 'DiagnosticsTableGateway' );
							$table = new DiagnosticsTable($tableGateway);
							return $table;
						},
						'DiagnosticsTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new Diagnostics());
							return new TableGateway ( 'diagnostic', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\OrdonnanceTable' => function ($sm) {
							$tableGateway = $sm->get ( 'OrdonnanceTableGateway' );
							$table = new OrdonnanceTable($tableGateway);
							return $table;
						},
						'OrdonnanceTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new Ordonnance());
							return new TableGateway ( 'ordonnance', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\DemandeVisitePreanesthesiqueTable' => function ($sm) {
							$tableGateway = $sm->get ( 'DemandeVisitePreanesthesiqueTableGateway' );
							$table = new DemandeVisitePreanesthesiqueTable($tableGateway);
							return $table;
						},
						'DemandeVisitePreanesthesiqueTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new DemandeVisitePreanesthesique());
							return new TableGateway ( 'demande_visite_preanesthesique', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\NotesExamensMorphologiquesTable' => function ($sm) {
							$tableGateway = $sm->get ( 'NotesExamensMorphologiquesTableGateway' );
							$table = new NotesExamensMorphologiquesTable($tableGateway);
							return $table;
						},
						'NotesExamensMorphologiquesTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new NotesExamensMorphologiques());
							return new TableGateway ( 'note_examen_morphologique', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\DemandeTable' => function ($sm) {
							$tableGateway = $sm->get ( 'DemandeTableGateway' );
							$table = new DemandeTable($tableGateway);
							return $table;
						},
						'DemandeTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new Demande());
							return new TableGateway ( 'demande', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\OrdonConsommableTable' => function ($sm) {
							$tableGateway = $sm->get ( 'OrdonConsommableTableGateway' );
							$table = new OrdonConsommableTable($tableGateway);
							return $table;
						},
						'OrdonConsommableTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new OrdonConsommable());
							return new TableGateway ( 'ordon_consommable', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\AntecedentPersonnelTable' => function ($sm) {
							$tableGateway = $sm->get ( 'AntecedentPersonnelPatientTableGateway' );
							$table = new AntecedentPersonnelTable($tableGateway);
							return $table;
						},
						'AntecedentPersonnelPatientTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new AntecedentPersonnel());
							return new TableGateway ( 'ant_personnels_patient', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\AntecedentsFamiliauxTable' => function ($sm) {
							$tableGateway = $sm->get ( 'AntecedentsFamiliauxPatientTableGateway' );
							$table = new AntecedentsFamiliauxTable($tableGateway);
							return $table;
						},
						'AntecedentsFamiliauxPatientTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new AntecedentsFamiliaux());
							return new TableGateway ( 'ant_familiaux_patient', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\DemandehospitalisationTable' => function ($sm) {
							$tableGateway = $sm->get ( 'DemandehospitalisationTableeGateway' );
							$table = new DemandehospitalisationTable ( $tableGateway );
							return $table;
						},
						'DemandehospitalisationTableeGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Demandehospitalisation () );
							return new TableGateway ( 'demande_hospitalisation', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\SoinhospitalisationTable' => function ($sm) {
							$tableGateway = $sm->get ( 'SoinhospitalisationConsTableGateway' );
							$table = new SoinhospitalisationTable( $tableGateway );
							return $table;
						},
						'SoinhospitalisationConsTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Soinhospitalisation() );
							return new TableGateway ( 'soins_hospitalisation', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\SoinsTable' => function ($sm) {
							$tableGateway = $sm->get ( 'SoinsTableGateway' );
							$table = new SoinsTable( $tableGateway );
							return $table;
						},
						'SoinsTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Soins() );
							return new TableGateway ( 'soins', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\HospitalisationTable' => function ($sm) {
							$tableGateway = $sm->get ( 'HospitalisationTableGateway' );
							$table = new HospitalisationTable ( $tableGateway );
							return $table;
						},
						'HospitalisationTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Hospitalisation() );
							return new TableGateway ( 'hospitalisation', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\HospitalisationlitTable' => function ($sm) {
							$tableGateway = $sm->get ( 'HospitalisationlitTableGateway' );
							$table = new HospitalisationlitTable ( $tableGateway );
							return $table;
						},
						'HospitalisationlitTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Hospitalisationlit() );
							return new TableGateway ( 'hospitalisation_lit', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\LitTable' => function ($sm) {
							$tableGateway = $sm->get ( 'LitTableGateway' );
							$table = new LitTable ( $tableGateway );
							return $table;
						},
						'LitTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Lit() );
							return new TableGateway ( 'lit', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\SalleTable' => function ($sm) {
							$tableGateway = $sm->get ( 'SalleTableGateway' );
							$table = new SalleTable( $tableGateway );
							return $table;
						},
						'SalleTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Salle() );
							return new TableGateway ( 'salle', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\BatimentTable' => function ($sm) {
							$tableGateway = $sm->get ( 'BatimentTableGateway' );
							$table = new BatimentTable ( $tableGateway );
							return $table;
						},
						'BatimentTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Batiment () );
							return new TableGateway ( 'batiment', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\ResultatVisitePreanesthesiqueTable' => function ($sm) {
							$tableGateway = $sm->get ( 'ResultatVisitePreanesthesiqueTableGateway' );
							$table = new ResultatVisitePreanesthesiqueTable( $tableGateway );
							return $table;
						},
						'ResultatVisitePreanesthesiqueTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new ResultatVisitePreanesthesique() );
							return new TableGateway ( 'resultat_vpa', $dbAdapter, null, $resultSetPrototype );
						},
						'Chururgie\Model\DemandeActeTable' => function ($sm) {
							$tableGateway = $sm->get ( 'DemandeActeTableGateway' );
							$table = new DemandeActeTable($tableGateway);
							return $table;
						},
						'DemandeActeTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype ( new DemandeActe());
							return new TableGateway ( 'demande_acte', $dbAdapter, null, $resultSetPrototype );
						},
					
				)
		);
	}
	public function getViewHelperConfig() {
		return array ();
	}
}