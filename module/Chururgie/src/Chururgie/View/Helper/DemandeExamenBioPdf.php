<?php
namespace Chururgie\View\Helper;

use ZendPdf;
use ZendPdf\Page;
use ZendPdf\Font;
use Chururgie\Model\Consultation;
use Chururgie\View\Helper\DateHelper; 


class DemandeExamenBioPdf
{
	protected $_page;
	protected $_yPosition;
	protected $_leftMargin;
	protected $_pageWidth;
	protected $_pageHeight;
	protected $_normalFont;
	protected $_boldFont;
	protected $_newTime;
	protected $_newTimeGras;
	protected $_year;
	protected $_headTitle;
	protected $_introText;
	protected $_graphData;
	protected $_patient;
	protected $_id_cons;
	protected $_date;
	protected $_note;
	protected $_idPersonne;
	protected $_Medicaments;
	protected $_DonneesPatient;
	protected $_DonneesMedecin;
	protected $_DonneesDemande;
	protected $_NotesDemande;
	
	protected $_DonneesDemandeFonctionnel;
	protected $_NotesDemandeFonctionnel;
	protected $_DonneesDemandeMorph;
	protected $_notesDemandeMorph;
	
	protected $_policeContenu;
	protected $_newPolice;
	protected $_courier;
	protected $_service;	
	protected $_xPosition;	
	public function __construct()
	{
		$this->_page = new Page(650,700);
		
 		$this->_yPosition = 610;
 		$this->_leftMargin = 50;
 		$this->_pageHeight = $this->_page->getHeight();
 		$this->_pageWidth = $this->_page->getWidth();
 		$this->_xPosition = 700;
 		/**
 		 * Pas encore utilis�
 		 */
 		$this->_normalFont = Font::fontWithName( ZendPdf\Font::FONT_HELVETICA);
 		$this->_boldFont = Font::fontWithName( ZendPdf\Font::FONT_HELVETICA_BOLD);
 		/**
 		 ***************** 
 		 */
 		$this->_courier = Font::fontWithName(ZendPdf\Font::FONT_COURIER);
 		$this->_newTime = Font::fontWithName(ZendPdf\Font::FONT_TIMES_ROMAN);
 		$this->_newTimeGras = Font::fontWithName(ZendPdf\Font::FONT_TIMES_BOLD);
 		$this->_policeContenu = ZendPdf\Font::fontWithName(ZendPdf\Font::FONT_COURIER);
 		$this->_newPolice = ZendPdf\Font::fontWithName(ZendPdf\Font::FONT_TIMES);
	}
	
	public function getPage(){
		return $this->_page;
	}
	
	public function addNoteBio(){
		$this->_page->saveGS();
		
		$this->setEnTete();
		$this->getNoteBio();
		//var_dump($this->getNoteBio());exit();
		$this->getPiedPage();
		
		$this->_page->restoreGS();
	}
	
	
	
	
	public function addNotePlusieursExamensBiologiques(){
	    
	    
	    $this->_page->saveGS();
	    $this->setEnTete();
	    $this->AfficherExamenParPage();
	    
	    $this->getPiedPage();
	    $this->_page->restoreGS();
	    
	}
	
	public function setEnTete(){
		$baseUrl = $_SERVER['SCRIPT_FILENAME'];
		$tabURI  = explode('public', $baseUrl);
		
		$imageHeader = ZendPdf\Image::imageWithPath($tabURI[0].'public/img/logo_vert.png');
		$this->_page->drawImage($imageHeader, 505, //-x
				$this->_pageHeight - 130, //-y
				600, //+x
				650); //+y
		
		$this->_page->setFont($this->_newTime, 10);
		$this->_page->drawText('République du Sénégal',
				$this->_leftMargin,
				$this->_pageHeight - 50);
		$this->_page->setFont($this->_newTime, 10);
		$this->_page->drawText('Ministère de la santé et de l\'action sociale',
				$this->_leftMargin,
				$this->_pageHeight - 65);
		$this->_page->setFont($this->_newTime, 10);
		$this->_page->drawText('Polyclinique de l\'UGB de Saint-Louis',
				$this->_leftMargin,
				$this->_pageHeight - 80);
		$this->_page->setFont($this->_newTime, 10);
		$this->_page->drawText('Service: '.iconv ('UTF-8' ,'ISO-8859-1' ,$this->_Service),
				$this->_leftMargin,
				$this->_pageHeight - 95);
		$font = ZendPdf\Font::fontWithName(ZendPdf\Font::FONT_TIMES_ROMAN);
		$this->_page->setFont($font, 8);
		$today = new \DateTime ();
		$dateNow = $today->format ( 'd/m/Y' );
		$this->_page->drawText('Saint-Louis, le ' . $dateNow, 510,
				$this->_pageHeight - 50);
		
		$this->_yPosition -= 35;
		$this->_page->setFont($this->_newTime, 15);
		$this->_page->setFillColor(new ZendPdf\Color\Html('green'));
		$this->_page->drawText('Demande d\'examen Biologique',
				$this->_leftMargin+155,
				$this->_yPosition);
		$this->_yPosition -= 5;
		$this->_page->setlineColor(new ZendPdf\Color\Html('green'));
		//$this->_page->setLineWidth(1);
		$this->_page->drawLine($this->_leftMargin,
				$this->_yPosition,
				$this->_pageWidth -
				$this->_leftMargin,
				$this->_yPosition);
		$this->_yPosition -= 15;
	}
	
	public function setService($service){
		$this->_Service = $service;
	}
	
	public function setDonneesPatientBio($donneesPatient){
		$this->_DonneesPatient = $donneesPatient;
	}
	
	public function setDonneesMedecinBio($donneesMedecin){
		$this->_DonneesMedecin = $donneesMedecin;
	}
	

	
	
	public function setDonneesDemandeBio($donneesDemande){
	   
		$this->_DonneesDemande = $donneesDemande;
	}
	
	public function setNotesDemandeBio($notesDemande){
	    
		$this->_NotesDemande = $notesDemande;
	}

	
	public function setIdConsBio($id_cons){
		$this->_id_cons = $id_cons;
	}
	
	public function getNewItalique(){
		$font = ZendPdf\Font::fontWithName(ZendPdf\Font::FONT_HELVETICA_OBLIQUE);
		$this->_page->setFont($font, 12);
	}
	
	public function getNewTime(){
		$font = ZendPdf\Font::fontWithName(ZendPdf\Font::FONT_TIMES_ROMAN);
		$this->_page->setFont($font, 12);
	}
	
	protected function scinderText($Text){
		$tab = array();
		$tab[1] = substr($Text, 0, 50);
		$tab[2] = substr($Text, 50, 65);
		$tab[3] = substr($Text, 115, 65);
		$tab[4] = substr($Text, 180, 65);
		$tab[5] = substr($Text, 245, 65);
		$tab[6] = substr($Text, 310, 65);
		$tab[7] = substr($Text, 375, 65);
		return $tab;
	}
	
	protected function nbAnnees($debut, $fin) {
		//60 secondes X 60 minutes X 24 heures dans une journee
		$nbSecondes = 60*60*24*365;
	
		$debut_ts = strtotime($debut);
		$fin_ts = strtotime($fin);
		$diff = $fin_ts - $debut_ts;
		return (int)($diff / $nbSecondes);
	}
	
	
	
	protected function listeDesExamensBio(){
		$ExamensBio = array(
			
				'1' => 'Groupe sanguin',
				'2' => 'Hémogramme sanguin',
				'3' => 'Bilan hématique',
				'4' => 'Bilan rénal',
				'5' => 'Bilan hémostase',
				'6' => 'Bilan inflammatoire',
		);
		return $ExamensBio;
	}
	
	
	protected  function getNoteBio(){
		$Control = new DateHelper();
		$noteLineHeight = 30;
		$this->_page->setFillColor(new ZendPdf\Color\Html('black')); //Pour le text
		
		//$this->_page->setLineColor(new ZendPdf\Color\Html('#999999')); //Pour les ligne
		//$this->_page->setLineWidth(0.2);
		//$this->_page->setLineDashingPattern(array(1, 2));

			
		$today = new \DateTime();
		$date_actu = $today->format('Y-m-d');

		//-----------------------------------------------
		$this->_page->setFont($this->_newTimeGras, 9);
		$this->_page->drawText('PRENOM & NOM :',
		    $this->_leftMargin+155,
		    $this->_yPosition);
		$this->_page->setFont($this->_newTime, 11);
		$this->_page->drawText($this->_DonneesPatient['PRENOM'].' '.$this->_DonneesPatient['NOM'].'  ('.$this->_DonneesPatient['NUMERO_DOSSIER'].')',
		    $this->_leftMargin+240,
		    $this->_yPosition);
		//-----------------------------------------------
		$this->_yPosition -= 15;// allez a ligne suivante
		//----- -----------------------------------------
		$this->_page->setFont($this->_newTimeGras, 9);
		$this->_page->drawText('SEXE (AGE) : ',
		    $this->_leftMargin+180,
		    $this->_yPosition);
		$this->_page->setFont($this->_newTime, 11);
		$this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' , $this->_DonneesPatient['SEXE'] .' ('.$this->_DonneesPatient['AGE'] ."  ans".')'),
		    $this->_leftMargin+240,
		    $this->_yPosition);
		
		//-----------------------------------------------
		$this->_yPosition -= 15;// allez a ligne suivante
		//----- -----------------------------------------
		
		//----- -----------------------------------------
		$this->_page->setFont($this->_newTimeGras, 9);
		$this->_page->drawText('ADRESSE (Téléphone) :',
		    $this->_leftMargin+140,
		    $this->_yPosition);
		$this->_page->setFont($this->_newTime, 11);
		$this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' ,$this->_DonneesPatient['ADRESSE'].' ('.$this->_DonneesPatient['TELEPHONE'].')'),
		    $this->_leftMargin+240,
		    $this->_yPosition);
			
			
		
		
		$this->_page->drawRectangle($this->_leftMargin+250, $this->_yPosition-395,   $this->_pageWidth- 350,   $this->_yPosition-15);
		
		
		//Ligne pour séparerles elements
		$this->_page->drawLine($this->_leftMargin,
		    $this->_yPosition-175,
		    $this->_pageWidth -350,
		    $this->_yPosition-175);
		
		
		
			//$this->_page->setlineColor(new ZendPdf\Color\Html('green'));
			//$this->_page->setLineWidth(1);
			//$this->_page->setLineDashingPattern(array(0, 0));
			$this->_page->drawLine($this->_leftMargin,
					$this->_yPosition-10,
					$this->_pageWidth -
					$this->_leftMargin,
					$this->_yPosition-10);
			
		$this->_yPosition -= $noteLineHeight+10;//aller a la ligne suivante
		//$examensFonctionnel = count($this->_DonneesDemandeFonctionnel);
		$examensBio = count($this->_DonneesDemande);
		//$examensMorpho = count($this->_DonneesDemandeMorph);
		$i = 1;
		$n = 1;

		
		//PREPARATION DU TEXT Diagnostic
		while($this->_yPosition > 70) {

		    
		    if($this->_DonneesDemande && $i == 1){
		        $this->_page->setFont($this->_courier, 15);
		        $this->_page->setFont($this->_courier, 15);
		        $this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' ,'Type D\'examen:'),
		            $this->_leftMargin,
		            $this->_yPosition);
		        
		        
		        
		        $this->_page->setFont($this->_courier, 15);
		        $this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' ,'Renseignement Clinique :'),
		            $this->_leftMargin,
		            $this->_yPosition-150);
		        
		        $this->_page->setFont($this->_courier, 15);
		        $this->_page->drawText('Résultat :',
		            $this->_leftMargin +275,
		            $this->_yPosition);
		        
		        
		        $this->_yPosition -= $noteLineHeight;
		    }
		    
		    
		    if($i <= $examensBio){
		        
		        if($this->_DonneesDemande[$i]){
		            
		            $this->_page->setFont($this->_newTimeGras, 27);
		            $this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' ,$this->listeDesExamensBio()[$this->_DonneesDemande[$i]]),
		                $this->_leftMargin,
		                $this->_yPosition-20);
		            $this->_page->setFont($this->_policeContenu, 12);
		            $this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' ,$this->_NotesDemande[$i]),
		                $this->_leftMargin,
		                $this->_yPosition-150);
		            
		        }
		        
		    }
		    

		    $i++;
		    
		    $this->_page->drawLine(
		        200,
		        125,215,
		        115
		        );
		    $this->_page->setlineColor(new ZendPdf\Color\Html('white'));
		    $this->_page->setLineWidth(0);
		    //$this->_page->setLineDashingPattern(array(1, 1));
		    $this->_page->drawLine(
		        $this->_leftMargin,
		        $this->_yPosition-5,
		        $this->_pageWidth -
		        $this->_leftMargin,
		        $this->_yPosition-5
		        );
		    
		    
		    $this->_yPosition -= $noteLineHeight;
		   
		}
	
		
		
		$this->_page->drawText($this->_DonneesMedecin['prenomMedecin'].' '.$this->_DonneesMedecin['nomMedecin'] ,
		    $this->_leftMargin+15,
		    $this->_yPosition+70);
		
		$this->_page->setFont($this->_policeContenu, 14);
		$this->_page->drawText("Cachet du Médecin :" ,
		    $this->_leftMargin+370,
		    $this->_yPosition+70);
	} 

	
	
	
	
	// Affichage page par page des examens Morphologique choisis
	public function AfficherExamenParPage(){
	    $Control = new DateHelper();
	    $noteLineHeight = 30;
	    $this->_page->setFillColor(new ZendPdf\Color\Html('black')); //Pour le text
	    
	    //$this->_page->setLineColor(new ZendPdf\Color\Html('#999999')); //Pour les ligne
	    //$this->_page->setLineWidth(0.2);
	    //$this->_page->setLineDashingPattern(array(1, 2));
	    
	    
	    $today = new \DateTime();
	    $date_actu = $today->format('Y-m-d');
	    
	    //-----------------------------------------------
	    $this->_page->setFont($this->_newTimeGras, 9);
	    $this->_page->drawText('PRENOM & NOM :',
	        $this->_leftMargin+155,
	        $this->_yPosition);
	    $this->_page->setFont($this->_newTime, 11);
	    $this->_page->drawText($this->_DonneesPatient['PRENOM'].' '.$this->_DonneesPatient['NOM'].'  ('.$this->_DonneesPatient['NUMERO_DOSSIER'].')',
	        $this->_leftMargin+240,
	        $this->_yPosition);
	    //-----------------------------------------------
	    $this->_yPosition -= 15;// allez a ligne suivante
	    //----- -----------------------------------------
	    $this->_page->setFont($this->_newTimeGras, 9);
	    $this->_page->drawText('SEXE (AGE) : ',
	        $this->_leftMargin+180,
	        $this->_yPosition);
	    $this->_page->setFont($this->_newTime, 11);
	    $this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' , $this->_DonneesPatient['SEXE'] .' ('.$this->_DonneesPatient['AGE'] ."  ans".')'),
	        $this->_leftMargin+240,
	        $this->_yPosition);
	    
	    //-----------------------------------------------
	    $this->_yPosition -= 15;// allez a ligne suivante
	    //----- -----------------------------------------
	    
	    //----- -----------------------------------------
	    $this->_page->setFont($this->_newTimeGras, 9);
	    $this->_page->drawText('ADRESSE (Téléphone) :',
	        $this->_leftMargin+140,
	        $this->_yPosition);
	    $this->_page->setFont($this->_newTime, 11);
	    $this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' ,$this->_DonneesPatient['ADRESSE'].' ('.$this->_DonneesPatient['TELEPHONE'].')'),
	        $this->_leftMargin+240,
	        $this->_yPosition);
	    
	    
	    
	    
	    $this->_page->drawRectangle($this->_leftMargin+250, $this->_yPosition-395,   $this->_pageWidth- 350,   $this->_yPosition-15);
	    
	    
	    //Ligne pour séparerles elements
	    $this->_page->drawLine($this->_leftMargin,
	        $this->_yPosition-175,
	        $this->_pageWidth -350,
	        $this->_yPosition-175);
	    
	    
	    
	    //$this->_page->setlineColor(new ZendPdf\Color\Html('green'));
	    //$this->_page->setLineWidth(1);
	    //$this->_page->setLineDashingPattern(array(0, 0));
	    $this->_page->drawLine($this->_leftMargin,
	        $this->_yPosition-10,
	        $this->_pageWidth -
	        $this->_leftMargin,
	        $this->_yPosition-10);
	    
	    $this->_yPosition -= $noteLineHeight+10;//aller a la ligne suivante
	    //$examensFonctionnel = count($this->_DonneesDemandeFonctionnel);
	    $examensBio = count($this->_DonneesDemande);
	    //$examensMorpho = count($this->_DonneesDemandeMorph);
	    $i = 1;
	    $n = 1;
	    
	    
	    //PREPARATION DU TEXT Diagnostic
	    while($this->_yPosition > 70) {
	        
	        
	        if($this->_DonneesDemande && $i == 1){
	            $this->_page->setFont($this->_courier, 15);
	            $this->_page->setFont($this->_courier, 15);
	            $this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' ,'Type D\'examen:'),
	                $this->_leftMargin,
	                $this->_yPosition);
	            
	            
	            
	            $this->_page->setFont($this->_courier, 15);
	            $this->_page->drawText(iconv ('UTF-8' ,'ISO-8859-1' ,'Renseignement Clinique :'),
	                $this->_leftMargin,
	                $this->_yPosition-150);
	            
	            $this->_page->setFont($this->_courier, 15);
	            $this->_page->drawText('Résultat :',
	                $this->_leftMargin +275,
	                $this->_yPosition);
	            
	            
	            $this->_yPosition -= $noteLineHeight;
	        }
	        
	      
	        $i++;
	        
	        $this->_page->drawLine(
	            200,
	            125,215,
	            115
	            );
	        $this->_page->setlineColor(new ZendPdf\Color\Html('white'));
	        $this->_page->setLineWidth(0);
	        //$this->_page->setLineDashingPattern(array(1, 1));
	        $this->_page->drawLine(
	            $this->_leftMargin,
	            $this->_yPosition-5,
	            $this->_pageWidth -
	            $this->_leftMargin,
	            $this->_yPosition-5
	            );
	        
	        
	        $this->_yPosition -= $noteLineHeight;
	        
	    }
	    
	    
	    if($this->_DonneesDemande){
	        
	        $this->_page->setFont($this->_newTimeGras, 27);
	        $this->_page->drawText($this->listeDesExamensBio()[$this->_DonneesDemande],
	            $this->_leftMargin+50,
	            $this->_yPosition+350);
	        $this->_page->setFont($this->_policeContenu, 12);
	        $this->_page->drawText($this->_NotesDemande,
	            $this->_leftMargin,
	            $this->_yPosition+250);
	        
	    }
	    
	    $this->_page->drawText($this->_DonneesMedecin['prenomMedecin'].' '.$this->_DonneesMedecin['nomMedecin'] ,
	        $this->_leftMargin+15,
	        $this->_yPosition+70);
	    
	    $this->_page->setFont($this->_policeContenu, 14);
	    $this->_page->drawText("Cachet du Médecin :" ,
	        $this->_leftMargin+370,
	        $this->_yPosition+70);
	}
	
	
	
	
	public function getPiedPage(){
		$this->_yPosition -= -30;
		$this->_page->setlineColor(new ZendPdf\Color\Html('green'));
		$this->_page->setLineWidth(2);
		//$this->_page->setLineDashingPattern(array(0, 0));		
		$this->_page->drawLine($this->_leftMargin,
						$this->_yPosition,
						$this->_pageWidth -
						$this->_leftMargin,
						$this->_yPosition);
		
		$this->_page->setFont($this->_newTime, 10);
		$this->_page->drawText('Téléphone: 33 726 25 36   BP: 25 000',
				$this->_leftMargin,
				$this->_pageWidth - ( $this->_yPosition + 503));
		
		$this->_page->setFont($this->_newTime, 10);
		$this->_page->drawText('Consultation numéro: ',
				$this->_leftMargin + 370,
				$this->_pageWidth - ( $this->_yPosition + 503));
		$this->_page->setFont($this->_newTimeGras, 11);
		$this->_page->drawText($this->_id_cons,
				$this->_leftMargin + 465,
				$this->_pageWidth - ( $this->_yPosition + 503));
	}
	
	
}