<?php
namespace Urgence\Form;

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
						'value' => 's-c-' . $dateheure,
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
						'label' => iconv('ISO-8859-1', 'UTF-8','Numéro facture')
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
				'name' => 'type_facturation',
				'type' => 'Zend\Form\Element\radio',
				'options' => array (
						'value_options' => array(
								1 => 'Normal',
								2 => iconv ( 'ISO-8859-1', 'UTF-8','Prise en charge') ,
						),
				),
				'attributes' => array(
						'id' => 'type_facturation',
						//'required' => true,
				),
		));
		
		$this->add(array(
				'name' => 'organisme',
				'type' => 'textarea',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Organisme')
				),
				'attributes' => array(
						'id' => 'organisme',
				),
		));
		
		$this->add(array(
				'name' => 'taux',
				'type' => 'Select',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Taux (%)'),
						'value_options' => array(
								'' => '00',
								5  => '05',
								10 => '10',
						),
				),
				'attributes' => array(
						'registerInArrrayValidator' => true,
						'onchange' => 'getTarif(this.value)',
						'id' => 'taux',
				),
		));
		
		
		$this->add ( array (
				'name' => 'motif_admission',
				'type' => 'Text',
				'options' => array (
						'label' => 'Motif_admission'
				)
		) );
		/**
		 * ********* LES MOTIFS D ADMISSION *************
		*/
		/**
		 * ********* LES MOTIFS D ADMISSION *************
		*/
		$this->add ( array (
				'name' => 'motif_admission1',
				'type' => 'Text',
				'options' => array (
						'label' => 'motif 1'
				),
				'attributes' => array (
						'readonly' => 'readonly',
						'id' => 'motif_admission1'
				)
		) );
		$this->add ( array (
				'name' => 'motif_admission2',
				'type' => 'Text',
				'options' => array (
						'label' => 'motif 2'
				),
				'attributes' => array (
						'readonly' => 'readonly',
						'id' => 'motif_admission2'
				)
		) );
		$this->add ( array (
				'name' => 'motif_admission3',
				'type' => 'Text',
				'options' => array (
						'label' => 'motif 3'
				),
				'attributes' => array (
						'readonly' => 'readonly',
						'id' => 'motif_admission3'
				)
		) );
		$this->add ( array (
				'name' => 'motif_admission4',
				'type' => 'Text',
				'options' => array (
						'label' => 'motif 4'
				),
				'attributes' => array (
						'readonly' => 'readonly',
						'id' => 'motif_admission4'
				)
		) );
		$this->add ( array (
				'name' => 'motif_admission5',
				'type' => 'Text',
				'options' => array (
						'label' => 'motif 5'
				),
				'attributes' => array (
						'readonly' => 'readonly',
						'id' => 'motif_admission5'
				)
		) );
		
		
		/**
		 * ************************* CONSTANTES *****************************************************
		 */
		/**
		 * ************************* CONSTANTES *****************************************************
		 */
		/**
		 * ************************* CONSTANTES *****************************************************
		 */
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
		$this->add ( array (
				'name' => 'poids',
				'type' => 'number',
				'options' => array (
						'label' => 'Poids (kg)'
				),
				'attributes' => array (
						'id' => 'poids',
						'min' => 1,
				)
		) );
		$this->add ( array (
				'name' => 'taille',
				'type' => 'number',
				'options' => array (
						'label' => 'Taille (cm)'
				),
				'attributes' => array (
						'id' => 'taille',
						'min' => 1,
				)
		) );
		$this->add ( array (
				'name' => 'temperature',
				'type' => 'number',
				'options' => array (
						'label' => iconv ( 'ISO-8859-1', 'UTF-8', 'Température (°C)' )
				),
				'attributes' => array (
						'id' => 'temperature',
						'min' => 34,
						'max' => 45,
				)
		) );
		
		$this->add ( array (
				'name' => 'tension',
				'type' => 'number',
				'options' => array (
						'label' => 'Tension'
				),
				'attributes' => array (
						'id' => 'tension'
				)
		) );
		
		$this->add ( array (
				'name' => 'pressionarterielle',
				'type' => 'number',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8', 'Pression artérielle (mmHg)')
				),
				'attributes' => array (
						'id' => 'pressionarterielle'
				)
		) );
		
		$this->add ( array (
				'name' => 'tensionmaximale',
				'type' => 'number',
				'attributes' => array (
						'id' => 'tensionmaximale',
						'min' => 1,
						'max' => 300,
				)
		) );
		
		$this->add ( array (
				'name' => 'tensionminimale',
				'type' => 'number',
				'attributes' => array (
						'id' => 'tensionminimale',
						'min' => 1,
						'max' => 300,
				)
		) );
		
		$this->add ( array (
				'name' => 'pouls',
				'type' => 'number',
				'options' => array (
						'label' => 'Pouls (bat/min)'
				),
				'attributes' => array (
						'id' => 'pouls',
						'min' => 20,
						'max' => 300,
				)
		) );
		$this->add ( array (
				'name' => 'frequence_respiratoire',
				'type' => 'number',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Fréquence respiratoire')
				),
				'attributes' => array (
						'id' => 'frequence_respiratoire',
						'min' => 5,
						'max' => 50,
				)
		) );
		$this->add ( array (
				'name' => 'glycemie_capillaire',
				'type' => 'number',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8', 'Glycémie capillaire (g/l)')
				),
				'attributes' => array (
						'id' => 'glycemie_capillaire',
						'min' => 1,
						'max' => 16,
				)
		) );
		$this->add ( array (
				'name' => 'bu',
				'type' => 'Text',
				'options' => array (
						'label' => 'Bandelette urinaire'
				),
				'attributes' => array (
						'id' => 'bu',
						'min' => 1,
				)
		) );
		
		
		/*** LES TYPES DE BANDELETTES URINAIRES ***/
		/*** LES TYPES DE BANDELETTES URINAIRES ***/
		/*** LES TYPES DE BANDELETTES URINAIRES ***/
		$this->add ( array (
				'name' => 'albumine',
				'type' => 'radio',
				'options' => array (
						'value_options' => array (
								'0' => 'â€“',
								'1' => '+',
						)
				),
				'attributes' => array (
						'id' => 'albumine',
		
				)
		) );
		$this->add ( array (
				'name' => 'croixalbumine',
				'type' => 'radio',
				'options' => array (
						'value_options' => array (
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
						)
				),
				'attributes' => array (
						'id' => 'croixalbumine',
		
				)
		) );
		
		
		$this->add ( array (
				'name' => 'sucre',
				'type' => 'radio',
				'options' => array (
						'value_options' => array (
								'0' => 'â€“',
								'1' => '+',
						)
				),
				'attributes' => array (
						'id' => 'sucre',
		
				)
		) );
		$this->add ( array (
				'name' => 'croixsucre',
				'type' => 'radio',
				'options' => array (
						'value_options' => array (
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
						)
				),
				'attributes' => array (
						'id' => 'croixsucre',
		
				)
		) );
		
		
		
		$this->add ( array (
				'name' => 'corpscetonique',
				'type' => 'radio',
				'options' => array (
						'value_options' => array (
								'0' => 'â€“',
								'1' => '+',
						)
				),
				'attributes' => array (
						'id' => 'corpscetonique',
		
				)
		) );
		$this->add ( array (
				'name' => 'croixcorpscetonique',
				'type' => 'radio',
				'options' => array (
						'value_options' => array (
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
						)
				),
				'attributes' => array (
						'id' => 'croixcorpscetonique',
						'class' => 'croixcorpscetonique',
		
				)
		) );
		
		
		//Niveau d'urgence du patient
		$this->add ( array (
				'name' => 'niveau',
				'type' => 'radio',
				'options' => array (
						'value_options' => array (
								array( 'label' => '4', 'value' => 4, 'attributes' => array ( 'id' => 'blanc'  ) ),
								array( 'label' => '3', 'value' => 3, 'attributes' => array ( 'id' => 'jaune'  ) ),
								array( 'label' => '2', 'value' => 2, 'attributes' => array ( 'id' => 'orange' ) ),
								array( 'label' => '1', 'value' => 1, 'attributes' => array ( 'id' => 'rouge'  ) ),
						)
				),
				'attributes' => array (
						'id' => 'niveau',
						'class' => 'niveau',
		
				)
		) );
		
		
		$this->add ( array (
				'name' => 'salle',
				'type' => 'Select',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Salle'),
				),
				'attributes' => array (
						'onchange' => 'getListeLits(this.value)',
						'id' => 'salle',
				)
		) );
		
		$this->add ( array (
				'name' => 'lit',
				'type' => 'Select',
				'options' => array (
						'label' => 'Lit'
				),
				'attributes' => array (
						'id' => 'lit'
				)
		) );
		
		$this->add ( array (
				'name' => 'couloir',
				'type' => 'Checkbox',
				'attributes' => array (
						'id' => 'couloir'
				)
		) );
		
		//RPU Hospitalisation  ---  RPU Hospitalisation
		//RPU Hospitalisation  ---  RPU Hospitalisation
		$this->add(array(
				'name' => 'rpu_hospitalisation',
				'type' => 'Textarea',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','RPU')
				),
				'attributes' => array(
						'id' => 'rpu_hospitalisation',
				),
		));
		
		$this->add(array(
				'name' => 'rpu_hospitalisation_note',
				'type' => 'Textarea',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Note')
				),
				'attributes' => array(
						'id' => 'rpu_hospitalisation_note',
				),
		));
		
		/*====================================*/
		/*====================================*/
		
		
		//RPU Traumatisme  ---  RPU Traumatisme
		//RPU Traumatisme  ---  RPU Traumatisme
		
		//Histoire de la maladie
		//Histoire de la maladie
		$this->add ( array (
				'name' => 'rpu_traumatisme_date_heure',
				'type' => 'Text',
				'options' => array (
						'label' => 'Date & heure'
				),
				'attributes' => array (
						'id' => 'rpu_traumatisme_date_heure',
				)
		) );
		
		$this->add ( array (
				'name' => 'rpu_traumatisme_circonstances',
				'type' => 'Text',
				'options' => array (
						'label' => 'Circonstances'
				),
				'attributes' => array (
						'id' => 'rpu_traumatisme_circonstances',
				)
		) );
		
		$this->add ( array (
				'name' => 'rpu_traumatisme_mecanismes',
				'type' => 'Text',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Mécanismes')
				),
				'attributes' => array (
						'id' => 'rpu_traumatisme_mecanismes',
				)
		) );
		
		
		$this->add(array(
				'name' => 'rpu_traumatisme_diagnostic',
				'type' => 'Textarea',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Diagnostic')
				),
				'attributes' => array(
						'id' => 'rpu_traumatisme_diagnostic',
				),
		));
		
		$this->add(array(
				'name' => 'rpu_traumatisme_conduite',
				'type' => 'Textarea',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Conduite à tenir')
				),
				'attributes' => array(
						'id' => 'rpu_traumatisme_conduite',
				),
		));
		
		$this->add ( array (
				'name' => 'transfert_consultation',
				'type' => 'Select',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Transfert || Consultat°'),
						'value_options' => array (
								'' => '',
								'1' => 'Transfert', 
								'2' => 'Consultation', 
						)
				),
				'attributes' => array (
						'id' => 'transfert_consultation'
				)
		) );
		
		/*====================================*/
		/*====================================*/
		
		//RPU Sortie  ---  RPU Sortie
		//RPU Sortie  ---  RPU Sortie
		$this->add(array(
				'name' => 'rpu_sortie_diagnostic',
				'type' => 'Textarea',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Diagnostic de sortie')
				),
				'attributes' => array(
						'id' => 'rpu_sortie_diagnostic',
				),
		));
		
		$this->add(array(
				'name' => 'rpu_sortie_traitement',
				'type' => 'Textarea',
				'options' => array (
						'label' => iconv('ISO-8859-1', 'UTF-8','Traitement')
				),
				'attributes' => array(
						'id' => 'rpu_sortie_traitement',
				),
		));
		
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