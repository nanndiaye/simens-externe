<?php
namespace Chururgie\Form;

use Zend\Form\Form;


class AdmissionForm extends Form{

	public function __construct() {
		
		parent::__construct ();
		$today = new \DateTime ( 'now' );
		$dateheure = $today->format ( 'dmy-His' );
		$date  = $today->format ( "Y-m-d" );
		$heure = $today->format ( "H:i" );
		
		$this->add ( array (
				'name' => 'id_cons',
				'type' => 'hidden',
				'options' => array (
						'label' => 'Code consultation'
				),
				'attributes' => array (
						'readonly' => 'readonly',
						'value' => 'c-ex-' . $dateheure,
						'id' => 'id_cons'
				)
		) );
		
		$this->add ( array (
				'name' => 'heure_cons',
				'type' => 'Hidden',
				'attributes' => array (
						'value' => $heure
				)
		) );
		
		$this->add ( array (
				'name' => 'dateonly',
				'type' => 'Hidden',
				
				'attributes' => array (
						'id' => 'dateonly',
						'value' => $date,
				)
		) );
		
		$this->add ( array (
				'name' => 'id_patient',
				'type' => 'Hidden',
				'attributes' => array(
						'id' => 'id_patient'
				)
		) );
		
		$this->add ( array (
				'name' => 'id_admission',
				'type' => 'Hidden',
				'attributes' => array(
						'id' => 'id_admission'
				)
		) );

		$this->add ( array (
				'name' => 'service',
				'type' => 'Select',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Service'),
						'value_options' => array (
								''=>''
						)
				),
				'attributes' => array (
						'registerInArrrayValidator' => true,
						'onchange' => 'getmontant(this.value)',
						'id' =>'service',
						//'required' => true,
				)
		) );

		$this->add ( array (
				'name' => 'montant_avec_majoration',
				'type' => 'Hidden',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Tarif (frs)')
				),
				'attributes' => array (
						'id' => 'montant_avec_majoration',
				)
		) );
		
		$this->add ( array (
				'name' => 'montant',
				'type' => 'Hidden',
				'attributes' => array (
						'id' => 'montant',
				)
		) );

		$this->add ( array (
				'name' => 'numero',
				'type' => 'Hidden',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Num&eacute;ro facture')
				),
				'attributes' => array (
						'id' => 'numero'
				)
		) );
		$this->add ( array (
				'name' => 'liste_service',
				'type' => 'Select',
				'options' => array (
						'value_options' => array (
								''=>''
						)
				),
				'attributes' => array (
						'id' => 'liste_service',
				)
		) );
		
		$this->add(array(
				'name' => 'type_consultation',
				'type' => 'Zend\Form\Element\radio',
				'options' => array (
						'value_options' => array(
								'1' => 'Normal',
						    '2' => iconv ( 'UTF-8','ISO-8859-1', 'Presentation Resultat') ,
						    '3' => iconv ('UTF-8',  'ISO-8859-1', 'Rendez-vous') ,
						),
				),
				'attributes' => array(
						'id' => 'type_consultation',
						'required' => true,
				),
		));
	
		$this->add ( array (
				'name' => 'date_cons',
				'type' => 'hidden',
				'options' => array (
						'label' => 'Date'
				),
				'attributes' => array (
						'readonly' => 'readonly',
						'id' => 'date_cons',
				)
		) );
		/*=========================*/
		/*=========================*/
		
		
		$this->add ( array (
				'name' => 'date_admission',
				'type' => 'Text',
				'options' => array (
						'label' => 'Date Admission'
				),
				'attributes' => array (
						'id' => 'date_admission',
				)
	    ) );
		
	}
}