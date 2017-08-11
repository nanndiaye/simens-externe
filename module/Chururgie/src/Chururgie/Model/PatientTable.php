<?php

namespace Chururgie\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\NotIn;
use Chururgie\View\Helper\DateHelper;

class PatientTable {
	protected $tableGateway;
	
	
	public function laListePatientsAjax(){
	    $db = $this->tableGateway->getAdapter();
	    $aColumns = array('Numero_Dossier','Nom','Prenom', 'Age','Sexe','Adresse', 'id', 'Idpatient');
	    /* Indexed column (used for fast and accurate table cardinality) */
	    $sIndexColumn = "id";
	    /*
	     * Paging
	     */
	    $sLimit = array();
	    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	    {
	        $sLimit[0] = $_GET['iDisplayLength'];
	        $sLimit[1] = $_GET['iDisplayStart'];
	    }
	    /*
	     * Ordering
	     */
	    if ( isset( $_GET['iSortCol_0'] ) )
	    {
	        $sOrder = array();
	        $j = 0;
	        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
	        {
	            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
	            {
	                $sOrder[$j++] = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
								 	".$_GET['sSortDir_'.$i];
	            }
	        }
	    }
	    /*
	     * SQL queries
	     */
	    $sql2 = new Sql ($db );
	    $subselect1 = $sql2->select ();
	    $subselect1->from ( array ('d' => 'deces') );
	    $subselect1->columns (array ('id_patient') );
	    $date = new \DateTime ("now");
	    $dateDuJour = $date->format ( 'Y-m-d' );
	    
	    $sql3 = new Sql ($db);
	    $subselect2 = $sql3->select ();
	    $subselect2->from ('admission');
	    $subselect2->columns ( array (	'id_cons') );
	    
	    $sql = new Sql($db);
	    $sQuery = $sql->select()
	    ->from(array('pat' => 'patient'))->columns(array('Numero_Dossier'=>'NUMERO_DOSSIER'))
	    ->join(array('pers' => 'personne'), 'pat.ID_PERSONNE = pers.ID_PERSONNE', array('Nom'=>'NOM','Prenom'=>'PRENOM','Age'=>'AGE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','Taille'=>'TAILLE','id'=>'ID_PERSONNE','Idpatient'=>'ID_PERSONNE'))
	    ->order('pat.ID_PERSONNE DESC');
	    
	    /* Data set length after filtering */
	    $stat = $sql->prepareStatementForSqlObject($sQuery);
	    $rResultFt = $stat->execute();
	    $iFilteredTotal = count($rResultFt);
	    
	    $rResult = $rResultFt;
	    $output = array(
	        "iTotalDisplayRecords" => $iFilteredTotal,
	        "aaData" => array()
	    );
	    
	    /*
	     * $Control pour convertir la date en fran�ais
	     */
	    $Control = new DateHelper();
	    
	    /*
	     * ADRESSE URL RELATIF
	     */
	    $baseUrl = $_SERVER['REQUEST_URI'];
	    $tabURI  = explode('public', $baseUrl);
	    
	    /*
	     * Pr�parer la liste
	     */
	    foreach ( $rResult as $aRow )
	    {
	        $row = array();
	        for ( $i=0 ; $i<count($aColumns) ; $i++ )
	        {
	            if ( $aColumns[$i] != ' ' )
	            {
	                /* General output */
	                if ($aColumns[$i] == 'Nom'){
	                    $row[] = "<khass id='nomMaj'>".$aRow[ $aColumns[$i]]."</khass>";
	                }
	                else if ($aColumns[$i] == 'Numero_dossier'){
	                    $row[] = "<span style='font-size: 19px;'>".$aRow[ $aColumns[$i]]."<span style='display: none;'> ".str_replace(' ', '' ,$aRow[ $aColumns[$i]])."</span></span>";
	                }
	              else if ($aColumns[$i] == 'Datenaissance') {
	                    $date_naissance = $aRow[ $aColumns[$i] ];
	                    if($date_naissance){ $row[] = $Control->convertDate($aRow[ $aColumns[$i] ]); }else{ $row[] = null;}
	                    
	                
	                }
	                else if ($aColumns[$i] == 'Adresse') {
	                    $row[] = $this->adresseText($aRow[ $aColumns[$i] ]);
	                }
	                else if ($aColumns[$i] == 'id') {
	                    $html ="<infoBulleVue> <a href='javascript:visualiser(".$aRow[ $aColumns[$i] ].")' >";
	                    $html .="<img style='margin-left: 5%; margin-right: 15%;' src='".$tabURI[0]."public/images_icons/voir2.png' title='d&eacute;tails'></a> </infoBulleVue>";
	                    
	                    $html .= "<infoBulleVue> <a href='javascript:declarer(".$aRow[ 'id' ].")' >";
	                    $html .="<img style='display: inline; margin-right: 5%;' src='".$tabURI[0]."public/images_icons/transfert_droite.png' title='suivant'></a> </infoBulleVue>";
	                    $row[] = $html;
	                }
	                else {
	                    $row[] = $aRow[ $aColumns[$i] ];
	                }
	            }
	        }
	        $output['aaData'][] = $row;
	    }
	    return $output;
	}
	
	
	
	public function addPatient($donnees , $date_enregistrement , $id_employe, $sexe ){
	    $date = new \DateTime();
	    $mois = $date ->format('m');
	    $annee = $date ->format('Y');
	    
	    $db = $this->tableGateway->getAdapter();
	    $sql = new Sql($db);
	    $sQuery = $sql->insert()
	    ->into('personne')
	    ->values( $donnees );
	    $stat = $sql->prepareStatementForSqlObject($sQuery);
	    $id_personne = $stat->execute()->getGeneratedValue();
	    
	    $dernierPatient = $this->getDernierPatient($mois, $annee);
	    //var_dump($dernierPatient);exit();
	    if($dernierPatient){
	        $suivant = $this->numeroOrdreTroisChiffre(( (int)$dernierPatient['ORDRE'] )+1);
	        $numeroDossier = $sexe.' '.$suivant.' '.$mois.''.$annee;
	        $this->tableGateway->insert ( array('ID_PERSONNE' => $id_personne , 'NUMERO_DOSSIER' => $numeroDossier, 'ORDRE' => $suivant, 'MOIS' => $mois, 'ANNEE' => $annee , 'DATE_ENREGISTREMENT' => $date_enregistrement , 'ID_EMPLOYE' => $id_employe) );
	    }else{
	        $numeroDossier = $this->numeroOrdreTroisChiffre('1').' '.$mois.''.$annee;
	        $this->tableGateway->insert ( array('ID_PERSONNE' => $id_personne , 'NUMERO_DOSSIER' => $numeroDossier, 'ORDRE' => 1, 'MOIS' => $mois, 'ANNEE' => $annee , 'DATE_ENREGISTREMENT' => $date_enregistrement , 'ID_EMPLOYE' => $id_employe) );
	    }
	}
	
	public function numeroOrdreTroisChiffre($ordre) {
	    $nbCharNum = 3 - strlen($ordre);
	    
	    $chaine ="";
	    for ($i=1 ; $i <= $nbCharNum ; $i++){
	        $chaine .= '0';
	    }
	    $chaine .= $ordre;
	    
	    return $chaine;
	}
	
	public function getDernierPatient($mois, $annee){
	    
	    $db = $this->tableGateway->getAdapter();
	    $sql = new Sql($db);
	    $sQuery = $sql->select()
	    ->from(array('pat' => 'patient'))->columns( array( '*' ))
	    ->where(array('MOIS'  => $mois, 'ANNEE' => $annee))
	    ->order('ORDRE DESC');
	    
	    return $sql->prepareStatementForSqlObject($sQuery)->execute()->current();
	    
	}
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	public function fetchAll() {
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}
	
	public function getPatient($id) {
		$id = ( int ) $id;
		$rowset = $this->tableGateway->select ( array (
				'ID_PERSONNE' => $id
		) );
		$row =  $rowset->current ();
		if (! $row) {
			return null;
		}
		return $row;
	}
	
	//A revoir et modifier
	public function listeSalles(){
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('su' => 'salle_urgence'))
		->columns(array('Id_salle' => 'id_salle', 'Numero_salle' => 'numero_salle'));
		$result = $sql->prepareStatementForSqlObject($sQuery)->execute();
	
		$options = array("" => "");
		foreach ($result as $data) {
			$options[$data['Id_salle']] = $data['Numero_salle'];
		}
	
		return $options;
	}
	
	public function nbPatientAdmisParInfirmierTri(){
		$dateDuJour = (new \DateTime ("now"))->format ( 'Y-m-d' );
	
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('pat' => 'patient'))->columns(array('Numero_dossier' => 'NUMERO_DOSSIER'))
		->join(array('pers' => 'personne'), 'pat.ID_PERSONNE = pers.ID_PERSONNE', array('Nom'=>'NOM','Prenom'=>'PRENOM','Datenaissance'=>'DATE_NAISSANCE','Age'=>'AGE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','Taille'=>'TAILLE','id'=>'ID_PERSONNE','Idpatient'=>'ID_PERSONNE'))
		->join(array('au' => 'admission_urgence'), 'au.id_patient = pers.ID_PERSONNE' , array('Id_admission'=>'id_admission', 'Id_infirmier_tri'=>'id_infirmier_tri', 'Id_infirmier_service'=>'id_infirmier_service'))
		->where( array ( 'date' => $dateDuJour, 'id_infirmier_service' => null ) );
	
		return $sql->prepareStatementForSqlObject($sQuery)->execute()->count();
	}
	public function getInfoPatient($id_personne) {
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('pat' => 'patient'))
		->columns( array( '*' ))
		->join(array('pers' => 'personne'), 'pers.id_personne = pat.id_personne' , array('*'))
		->where(array('pat.ID_PERSONNE' => $id_personne));
		
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$resultat = $stat->execute()->current();
		
		return $resultat;
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
	
	
	public function verifierRV($id_personne, $dateAujourdhui){
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('rec' => 'rendezvous_consultation'))
		->columns( array( '*' ))
		->join(array('cons' => 'consultation'), 'rec.ID_CONS = cons.ID_CONS' , array())
		->join(array('s' => 'service'), 's.ID_SERVICE = cons.ID_SERVICE' , array('*'))
		->where(array('cons.ID_PATIENT' => $id_personne, 'rec.DATE' => $dateAujourdhui));
	
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$resultat = $stat->execute()->current();
	
		return $resultat;
	}

	
	public  function updatePatient($donnees, $id_patient, $date_enregistrement, $id_employe){
		$this->tableGateway->update( array('DATE_MODIFICATION' => $date_enregistrement, 'ID_EMPLOYE' => $id_employe), array('ID_PERSONNE' => $id_patient) );
	
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->update()
		->table('personne')
		->set( $donnees )
		->where(array('ID_PERSONNE' => $id_patient ));
	
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$resultat = $stat->execute();
	}
	
	function quoteInto($text, $value, $platform, $count = null)
	{
		if ($count === null) {
			return str_replace('?', $platform->quoteValue($value), $text);
		} else {
			while ($count > 0) {
				if (strpos($text, '?') !== false) {
					$text = substr_replace($text, $platform->quoteValue($value), strpos($text, '?'), 1);
				}
				--$count;
			}
			return $text;
		}
	}
	//Réduire la chaine addresse
	function adresseText($Text){
		$chaine = $Text;
		if(strlen($Text)>36){
			$chaine = substr($Text, 0, 30);
			$nb = strrpos($chaine, ' ');
			$chaine = substr($chaine, 0, $nb);
			$chaine .=' ...';
		}
		return $chaine;
	}
	
	public function getListePatient(){
	
		$db = $this->tableGateway->getAdapter();
	
		$aColumns = array('NUMERO_DOSSIER','Nom','Prenom','Age','Sexe', 'Adresse', 'Nationalite', 'id', 'id2');
	
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id";
	
		/*
		 * Paging
		*/
		$sLimit = array();
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit[0] = $_GET['iDisplayLength'];
			$sLimit[1] = $_GET['iDisplayStart'];
		}
	
		/*
		 * Ordering
		*/
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = array();
			$j = 0;
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder[$j++] = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
								 	".$_GET['sSortDir_'.$i];
				}
			}
		}
	
		/*
		 * SQL queries
		*/
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('pat' => 'patient'))->columns(array('*'))
		->join(array('p' => 'personne'), 'pat.id_personne = p.id_personne' , array('Nom'=>'NOM','Prenom'=>'PRENOM','Age'=>'AGE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','Taille'=>'TAILLE','id'=>'ID_PERSONNE', 'id2'=>'ID_PERSONNE'))
		->order('pat.id_personne DESC');
	
		/* Data set length after filtering */
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$rResultFt = $stat->execute();
		$iFilteredTotal = count($rResultFt);
	
		$rResult = $rResultFt;
	//var_dump($rResult);exit();
		$output = array(
				//"sEcho" => intval($_GET['sEcho']),
				//"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
		);
	
		/*
		 * $Control pour convertir la date en fran�ais
		*/
		$Control = new DateHelper();
	
		/*
		 * ADRESSE URL RELATIF
		*/
		$baseUrl = $_SERVER['REQUEST_URI'];
		$tabURI  = explode('public', $baseUrl);
	
		/*
		 * Pr�parer la liste
		*/
		foreach ( $rResult as $aRow )
		{
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ( $aColumns[$i] != ' ' )
				{
					/* General output */
					if ($aColumns[$i] == 'Nom'){
						$row[] = "<khass id='nomMaj'>".$aRow[ $aColumns[$i]]."</khass>";
					}
	
					else if ($aColumns[$i] == 'Datenaissance') {
						
						$date_naissance = $aRow[ $aColumns[$i] ];
						if($date_naissance){ $row[] = $Control->convertDate($aRow[ $aColumns[$i] ]); }else{ $row[] = null;}
							
					}else if ($aColumns[$i] == 'NUMERO_DOSSIER'){
					    $row[] = "<span style='font-size: 19px;'>".$aRow[ $aColumns[$i]]."<span style='display: none;'> ".str_replace(' ', '' ,$aRow[ $aColumns[$i]])."</span></span>";
					}
	
					else if ($aColumns[$i] == 'Adresse') {
						$row[] = $this->adresseText($aRow[ $aColumns[$i] ]);
					}
					
	
					else if ($aColumns[$i] == 'id') {
						$html ="<infoBulleVue> <a href='".$tabURI[0]."public/chururgie/info-patient/id_patient/".$aRow[ $aColumns[$i] ]."'>";
						$html .="<img style='display: inline; margin-right: 15%;' src='".$tabURI[0]."public/images_icons/voir2.png' title='d&eacute;tails'></a></infoBulleVue>";
	
						$html .= "<infoBulleVue> <a href='".$tabURI[0]."public/chururgie/modifier/id_patient/".$aRow[ $aColumns[$i] ]."'>";
						$html .="<img style='display: inline; margin-right: 15%;' src='".$tabURI[0]."public/images_icons/pencil_16.png' title='Modifier'></a></infoBulleVue>";
	
						$row[] = $html;
					}
	
					else {
						$row[] = $aRow[ $aColumns[$i] ];
					}
	
				}
			}
			$output['aaData'][] = $row;
		}
		return $output;
	}
	
	
	
	/**
	 * LISTE DE TOUTES LES FEMMES SAUF LES FEMMES DECEDES
	 * @param unknown $id
	 * @return string
	 */
	public function getListeAjouterNaissanceAjax(){
	
		$db = $this->tableGateway->getAdapter();
	
		$aColumns = array('Idpatient','Nom','Prenom','Datenaissance', 'Adresse', 'id');
	
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id";
	
		/*
		 * Paging
		*/
		$sLimit = array();
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit[0] = $_GET['iDisplayLength'];
			$sLimit[1] = $_GET['iDisplayStart'];
		}
	
		/*
		 * Ordering
		*/
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = array();
			$j = 0;
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder[$j++] = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
								 	".$_GET['sSortDir_'.$i];
				}
			}
		}
	
		/*
		 * SQL queries
		*/
		$sql2 = new Sql ($db );
		$subselect1 = $sql2->select ();
		$subselect1->from ( array (
				'd' => 'deces'
		) );
		$subselect1->columns (array (
				'ID_PATIENT'
		) );
	
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('pat' => 'patient'))->columns(array('*'))
		->join(array('pers' => 'personne'), 'pat.ID_PERSONNE = pers.ID_PERSONNE' , array('Nom'=>'NOM','Prenom'=>'PRENOM','Datenaissance'=>'DATE_NAISSANCE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','id'=>'ID_PERSONNE','Idpatient'=>'ID_PERSONNE'))
		->where(array('SEXE' => 'Féminin'))
		->where( array (
				new NotIn ( 'pat.ID_PERSONNE', $subselect1 ),
		) )
		->order('pat.ID_PERSONNE DESC');
		/* Data set length after filtering */
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$rResultFt = $stat->execute();
		$iFilteredTotal = count($rResultFt);
	
		$rResult = $rResultFt;
	
		$output = array(
				//"sEcho" => intval($_GET['sEcho']),
				//"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
		);
	
		/*
		 * $Control pour convertir la date en fran�ais
		*/
		$Control = new DateHelper();
	
		/*
		 * ADRESSE URL RELATIF
		*/
		$baseUrl = $_SERVER['REQUEST_URI'];
		$tabURI  = explode('public', $baseUrl);
	
		/*
		 * Pr�parer la liste
		*/
		foreach ( $rResult as $aRow )
		{
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ( $aColumns[$i] != ' ' )
				{
					/* General output */
					if ($aColumns[$i] == 'Nom'){
						$row[] = "<khass id='nomMaj'>".$aRow[ $aColumns[$i]]."</khass>";
					}
	
					else if ($aColumns[$i] == 'Datenaissance') {
						$row[] = $Control->convertDate($aRow[ $aColumns[$i] ]);
					}
	
					else if ($aColumns[$i] == 'Adresse') {
						$row[] = $this->adresseText($aRow[ $aColumns[$i] ]);
					}
	
					else if ($aColumns[$i] == 'id') {
						$html ="<infoBulleVue> <a href='javascript:visualiser(".$aRow[ $aColumns[$i] ].")' >";
						$html .="<img style='margin-left: 5%; margin-right: 15%;' src='".$tabURI[0]."public/images_icons/voir2.png' title='d&eacute;tails'></a> </infoBulleVue>";
	
						$html .= "<infoBulleVue> <a href='javascript:ajouternaiss(".$aRow[ $aColumns[$i] ].")' >";
						$html .="<img style='display: inline; margin-right: 5%;' src='".$tabURI[0]."public/images_icons/transfert_droite.png' title='suivant'></a> </infoBulleVue>";
	
						$row[] = $html;
					}
	
					else {
						$row[] = $aRow[ $aColumns[$i] ];
					}
	
				}
			}
			$output['aaData'][] = $row;
		}
		return $output;
	}
	
	public function addPersonneNaissance($donnees, $date_enregistrement, $id_employe){
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->insert()
		->into('personne')
		->values( $donnees );
	
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$id_personne = $stat->execute()->getGeneratedValue();
		
		$this->tableGateway->insert ( array('ID_PERSONNE' => $id_personne , 'DATE_ENREGISTREMENT' => $date_enregistrement , 'ID_EMPLOYE' => $id_employe) );
		
		return $id_personne;
	}
	
	
	
	
	
	

	//=============================================================================================================================
	//=============================================================================================================================
	//=============================================================================================================================
	//=============================================================================================================================
	
	public function getServiceParId($id_service){
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('s' => 'service'))
		->columns( array( '*' ))
		->where(array('ID_SERVICE' => $id_service));
		
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$resultat = $stat->execute()->current();
		
		return $resultat;
	}
	
	public function deletePatient($id) {
		$this->tableGateway->delete ( array (
				'ID_PERSONNE' => $id
		) );
	}
	
	
	
	public function tousPatientsAdmis($service, $IdService) {
		$today = new \DateTime();
		$date = $today->format('Y-m-d');
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select1 = $sql->select ();
		$select1->from ( array (
				'p' => 'patient'
		) );
		$select1->columns(array () );
		$select1->join(array('pers' => 'personne'), 'pers.ID_PERSONNE = p.ID_PERSONNE', array(
				'Nom' => 'NOM',
				'Prenom' => 'PRENOM',
				'Datenaissance' => 'DATE_NAISSANCE',
				'Sexe' => 'SEXE',
				'Adresse' => 'ADRESSE',
				'Nationalite' => 'NATIONALITE_ACTUELLE',
				'Id' => 'ID_PERSONNE'
		));
		
		$select1->join(array('a' => 'admission'), 'p.ID_PERSONNE = a.id_patient', array('Id_admission' => 'id_admission'));
		$select1->join(array('s' => 'service'), 'a.id_service = s.ID_SERVICE', array('Nomservice' => 'NOM'));
		$select1->where(array('a.date_cons' => $date, 's.NOM' => $service));
		$select1->order('id_admission ASC');
		$statement1 = $sql->prepareStatementForSqlObject ( $select1 );
		$result1 = $statement1->execute ();
		
		$select2 = $sql->select ();
		$select2->from( array( 'cons' => 'consultation'));
		$select2->columns(array('Id' => 'ID_PATIENT', 'Id_cons' => 'ID_CONS', 'Date_cons' => 'DATEONLY',));
		$select2->join(array('cons_eff' => 'consultation_effective'), 'cons_eff.ID_CONS = cons.ID_CONS' , array('*'));
		$select2->where(array('DATEONLY' => $date , 'ID_SERVICE' => $IdService));
		$statement2 = $sql->prepareStatementForSqlObject ( $select2 );
		$result2 = $statement2->execute ();
		$tab = array($result1,$result2);
		return $tab;
	} 
	
	
	
	/**
	 * Une consultation pour laquelle tous les actes sont pay�es
	 */
	public function verifierActesPayesEnTotalite($idCons){
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->columns(array('*'));
		$select->from(array('d'=>'demande_acte'));
		$select->join( array( 'a' => 'actes' ), 'd.idActe = a.id' , array ( '*' ) );
		$select->where(array('d.idCons' => $idCons));
		
		$stat = $sql->prepareStatementForSqlObject($select);
		$result = $stat->execute();
		
		foreach ($result as $resultat){
			if($resultat['reglement'] == 0){
				return false;
			}
		}
		
		return true;
	}
	
	
	/**
	 * LISTE DES PATIENTS POUR Le paiement des actes
	 * @param unknown $id
	 * @return string
	 */
	public function listeDesActesImpayesDesPatientsAjax(){
	
		$db = $this->tableGateway->getAdapter();
	
		$aColumns = array('Idpatient','Nom','Prenom','Datenaissance', 'Adresse', 'id', 'idDemande');
	
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id";
	
		/*
		 * Paging
		*/
		$sLimit = array();
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit[0] = $_GET['iDisplayLength'];
			$sLimit[1] = $_GET['iDisplayStart'];
		}
	
		/*
		 * Ordering
		*/
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = array();
			$j = 0;
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder[$j++] = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
								 	".$_GET['sSortDir_'.$i];
				}
			}
		}
	
		/*
		 * SQL queries
		*/
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('pat' => 'patient'))->columns(array('*'))
		->join(array('pers' => 'personne'), 'pat.ID_PERSONNE = pers.ID_PERSONNE' , array('Nom'=>'NOM','Prenom'=>'PRENOM','Datenaissance'=>'DATE_NAISSANCE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','Taille'=>'TAILLE','id'=>'ID_PERSONNE','Idpatient'=>'ID_PERSONNE'))
		->join(array('cons' => 'consultation'), 'cons.ID_PATIENT = pers.ID_PERSONNE' , array('*') )
		->join(array('dem_act' => 'demande_acte'), 'cons.ID_CONS = dem_act.idCons' , array('*') )
		->order('dem_act.idDemande ASC')
		->group('dem_act.idCons');
	
		/* Data set length after filtering */
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$rResultFt = $stat->execute(); 
		$iFilteredTotal = count($rResultFt);
	
		$rResult = $rResultFt;
	
		$output = array(
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
		);
	
		/*
		 * $Control pour convertir la date en francais
		*/
		$Control = new DateHelper();
	
		/*
		 * ADRESSE URL RELATIF
		*/
		$baseUrl = $_SERVER['REQUEST_URI'];
		$tabURI  = explode('public', $baseUrl);
	
		/*
		 * Preparer la liste
		*/
		foreach ( $rResult as $aRow )
		{
			if( $this->verifierActesPayesEnTotalite($aRow['idCons']) == false ){ 

				$row = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					if ( $aColumns[$i] != ' ' )
					{
						/* General output */
						if ($aColumns[$i] == 'Nom'){
							$row[] = "<khass id='nomMaj'>".$aRow[ $aColumns[$i]]."</khass>";
						}
				
						else if ($aColumns[$i] == 'Datenaissance') {
							$date_naissance = $aRow[ $aColumns[$i] ];
							if($date_naissance){ $row[] = $Control->convertDate($aRow[ $aColumns[$i] ]); }else{ $row[] = null;}
						}
				
						else if ($aColumns[$i] == 'Adresse') {
							$row[] = $this->adresseText($aRow[ $aColumns[$i] ]);
						}
				
						else if ($aColumns[$i] == 'id') {
							$html ="<infoBulleVue> <a href='javascript:visualiser(".$aRow[ $aColumns[$i] ].")' >";
							$html .="<img style='margin-left: 5%; margin-right: 15%;' src='".$tabURI[0]."public/images_icons/voir2.png' title='d&eacute;tails'></a> </infoBulleVue>";
				
							$html .= "<infoBulleVue> <a href='javascript:paiement(".$aRow[ $aColumns[$i] ].",".$aRow[ 'idDemande' ] .",1)' >";
							$html .="<img style='display: inline; margin-right: 5%;' src='".$tabURI[0]."public/images_icons/transfert_droite.png' title='suivant'></a> </infoBulleVue>";
				
							$row[] = $html;
						}
				
						else {
							$row[] = $aRow[ $aColumns[$i] ];
						}
				
					}
				}
				$output['aaData'][] = $row;
			}
			
		}
		return $output;
	}
	
	
	/**
	 * LISTE DES PATIENTS POUR les actes deja pay�s
	 * @param unknown $id
	 * @return string
	 */
	public function listeDesActesPayesDesPatientsAjax(){
	
		$db = $this->tableGateway->getAdapter();
	
		$aColumns = array('Idpatient','Nom','Prenom','Datenaissance', 'Adresse', 'id', 'idDemande');
	
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id";
	
		/*
		 * Paging
		*/
		$sLimit = array();
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit[0] = $_GET['iDisplayLength'];
			$sLimit[1] = $_GET['iDisplayStart'];
		}
	
		/*
		 * Ordering
		*/
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = array();
			$j = 0;
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder[$j++] = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
								 	".$_GET['sSortDir_'.$i];
				}
			}
		}
	
		/*
		 * SQL queries
		*/
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('pat' => 'patient'))->columns(array('*'))
		->join(array('pers' => 'personne'), 'pat.ID_PERSONNE = pers.ID_PERSONNE', array('Nom'=>'NOM','Prenom'=>'PRENOM','Datenaissance'=>'DATE_NAISSANCE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','Taille'=>'TAILLE','id'=>'ID_PERSONNE','Idpatient'=>'ID_PERSONNE'))
		->join(array('cons' => 'consultation'), 'cons.ID_PATIENT = pers.ID_PERSONNE', array('*') )
		->join(array('dem_act' => 'demande_acte'), 'cons.ID_CONS = dem_act.idCons', array('*') )
		->order('dem_act.idDemande DESC')
		->group('dem_act.idCons');
	
		/* Data set length after filtering */
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$rResultFt = $stat->execute();
		$iFilteredTotal = count($rResultFt);
	
		$rResult = $rResultFt;
	
		$output = array(
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
		);
	
		/*
		 * $Control pour convertir la date en francais
		*/
		$Control = new DateHelper();
	
		/*
		 * ADRESSE URL RELATIF
		*/
		$baseUrl = $_SERVER['REQUEST_URI'];
		$tabURI  = explode('public', $baseUrl);
	
		/*
		 * Preparer la liste
		*/
		foreach ( $rResult as $aRow )
		{
			if( $this->verifierActesPayesEnTotalite($aRow['idCons']) == true ){
				$row = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					if ( $aColumns[$i] != ' ' )
					{
						/* General output */
						if ($aColumns[$i] == 'Nom'){
							$row[] = "<khass id='nomMaj'>".$aRow[ $aColumns[$i]]."</khass>";
						}
				
						else if ($aColumns[$i] == 'Datenaissance') {
							$date_naissance = $aRow[ $aColumns[$i] ];
							if($date_naissance){ $row[] = $Control->convertDate($aRow[ $aColumns[$i] ]); }else{ $row[] = null;}
						}
				
						else if ($aColumns[$i] == 'Adresse') {
							$row[] = $this->adresseText($aRow[ $aColumns[$i] ]);
						}
				
						else if ($aColumns[$i] == 'id') {
							$html ="<infoBulleVue> <a href='javascript:visualiser(".$aRow[ $aColumns[$i] ].")' >";
							$html .="<img style='margin-left: 5%; margin-right: 15%;' src='".$tabURI[0]."public/images_icons/voir2.png' title='d&eacute;tails'></a> </infoBulleVue>";
				
							$html .= "<infoBulleVue> <a href='javascript:paiement(".$aRow[ $aColumns[$i] ].",".$aRow[ 'idDemande' ] .",2)' >";
							$html .="<img style='display: inline; margin-right: 5%;' src='".$tabURI[0]."public/images_icons/transfert_droite.png' title='suivant'></a> </infoBulleVue>";
				
							$row[] = $html;
						}
				
						else {
							$row[] = $aRow[ $aColumns[$i] ];
						}
				
					}
				}
				$output['aaData'][] = $row;
			}
		}
		return $output;
	}
	
	
	//Tous les patients qui ont pour ID_PESONNE > 900
	public function tousPatients(){
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->from ( array (
				'p' => 'patient'
		) );
		$select->columns(array (
				'Nom' => 'NOM',
				'Prenom' => 'PRENOM',
				'Datenaissance' => 'DATE_NAISSANCE',
				'Sexe' => 'SEXE',
				'Adresse' => 'ADRESSE',
				'Nationalite' => 'NATIONALITE_ACTUELLE',
				'Taille' => 'TAILLE',
				'Id' => 'ID_PERSONNE'
		) );
		$select->where( array (
				'ID_PERSONNE > 900'
		) );
		$select->order('ID_PERSONNE DESC');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		return $result;
	}

	//le nombre de patients qui ont pour ID_PESONNE > 900
	public function nbPatientSUP900(){
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ('patient');
		$select->columns(array ('ID_PERSONNE'));
		$select->where( array (
				'ID_PERSONNE > 900'
		) );
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		return $result->count();
	}
	
	public function listeDeTousLesPays()
	{
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->from(array('p'=>'pays'));
		$select->columns(array ('nom_fr_fr'));
		$select->order('nom_fr_fr ASC');
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		foreach ($result as $data) {
			$options[$data['nom_fr_fr']] = $data['nom_fr_fr'];
		}
		return $options;
	}
	
	public function listeServices()
	{
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->from(array('serv'=>'service'));
		$select->columns(array ('ID_SERVICE', 'NOM'));
		$select->order('ID_SERVICE ASC');
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$options = array();
		$options[""] = "";
		foreach ($result as $data) {
			$options[$data['ID_SERVICE']] = $data['NOM'];
		}
		return $options;
	}
	
	public function getTypePersonnel()
	{
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->from(array('t'=>'type_employe'));
		$select->columns(array ('id', 'nom'));
		$select->order('id ASC');
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$options = array();
		$options[""] = "";
		foreach ($result as $data) {
			$options[$data['id']] = $data['nom'];
		}
		return $options;
	}
	
	public function listeHopitaux()
	{
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select('hopital');
		$select->order('ID_HOPITAL ASC');
		$stat = $sql->prepareStatementForSqlObject($select);
		$result = $stat->execute();
		foreach ($result as $data) {
			$options[$data['ID_HOPITAL']] = $data['NOM_HOPITAL'];
		}
		return $options;
	}
	
	/**
	 * LISTE DES PATIENTS DECEDES
	 * @param unknown $id
	 * @return string
	 */
	public function getListePatientsDecedesAjax(){
	
		$db = $this->tableGateway->getAdapter();
	
		$aColumns = array('Nom','Prenom','Datenaissance','Sexe', 'Adresse', 'Nationalite', 'id');
	
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id";
	
		/*
		 * Paging
		*/
		$sLimit = array();
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit[0] = $_GET['iDisplayLength'];
			$sLimit[1] = $_GET['iDisplayStart'];
		}
	
		/*
		 * Ordering
		*/
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = array();
			$j = 0;
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder[$j++] = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
								 	".$_GET['sSortDir_'.$i];
				}
			}
		}
	
		/*
		 * SQL queries
		*/
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('pat' => 'patient'))->columns(array('Nom'=>'NOM','Prenom'=>'PRENOM','Datenaissance'=>'DATE_NAISSANCE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','Taille'=>'TAILLE','id'=>'ID_PERSONNE'))
		->join(array('d' => 'deces') , 'd.id_personne = pat.ID_PERSONNE')
		->order('d.date_deces DESC');
		/* Data set length after filtering */
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$rResultFt = $stat->execute();
		$iFilteredTotal = count($rResultFt);
	
		$rResult = $rResultFt;
	
		$output = array(
				//"sEcho" => intval($_GET['sEcho']),
				//"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
		);
	
		/*
		 * $Control pour convertir la date en fran�ais
		*/
		$Control = new DateHelper();
	
		/*
		 * ADRESSE URL RELATIF
		*/
		$baseUrl = $_SERVER['REQUEST_URI'];
		$tabURI  = explode('public', $baseUrl);
	
		/*
		 * Pr�parer la liste
		*/
		foreach ( $rResult as $aRow )
		{
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ( $aColumns[$i] != ' ' )
				{
					/* General output */
					if ($aColumns[$i] == 'Nom'){
						$row[] = "<khass id='nomMaj'>".$aRow[ $aColumns[$i]]."</khass>";
					}
	
					else if ($aColumns[$i] == 'Datenaissance') {
						$row[] = $Control->convertDate($aRow[ $aColumns[$i] ]);
					}
	
					else if ($aColumns[$i] == 'Adresse') {
						$row[] = $this->adresseText($aRow[ $aColumns[$i] ]);
					}
	
					else if ($aColumns[$i] == 'id') {
						$html ="<infoBulleVue> <a href='javascript:affichervue(".$aRow[ $aColumns[$i] ].")' >";
						$html .="<img style='margin-right: 15%;' src='".$tabURI[0]."public/images_icons/voir2.PNG' title='d&eacute;tails'></a> </infoBulleVue>";
	
						$html .= "<infoBulleVue> <a href='javascript:modifierdeces(".$aRow[ $aColumns[$i] ].")' >";
						$html .="<img style='display: inline; margin-right: 15%;' src='".$tabURI[0]."public/images_icons/modifier.PNG' title='Modifier'></a> </infoBulleVue>";
	
						$html .= "<infoBulleVue> <a id='".$aRow[ $aColumns[$i] ]."' href='javascript:envoyer(".$aRow[ $aColumns[$i] ].")'>";
						$html .="<img style='display: inline;' src='".$tabURI[0]."public/images_icons/trash_16.PNG' title='Supprimer'></a> </infoBulleVue>";
	
						$row[] = $html;
					}
	
					else {
						$row[] = $aRow[ $aColumns[$i] ];
					}
	
				}
			}
			$output['aaData'][] = $row;
		}
		return $output;
	}
	
	//GESTION DES FICHIER MP3
	//GESTION DES FICHIER MP3
	//GESTION DES FICHIER MP3
	public function insererMp3($titre , $nom){
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->insert()
		->into('fichier_mp3')
		->columns(array('titre', 'nom'))
		->values(array('titre' => $titre , 'nom' => $nom));
		
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		return $stat->execute();
	}
	

	
	protected function nbAnnees($debut, $fin) {
		$nbSecondes = 60*60*24*365;
		$debut_ts = strtotime($debut);
		$fin_ts = strtotime($fin);
		$diff = $fin_ts - $debut_ts;
		return (int)($diff / $nbSecondes);
	}
	
	//Ce code n'est pas optimal
	//Ce code n'est pas optimal
	public function miseAJourAgePatient($id_personne) {
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from(array('pat' => 'patient'))
		->columns( array( '*' ))
		->join(array('pers' => 'personne'), 'pers.ID_PERSONNE = pat.ID_PERSONNE' , array('*'))
		->where(array('pat.ID_PERSONNE' => $id_personne));
		$pat = $sql->prepareStatementForSqlObject($sQuery)->execute()->current();
		
 		$today = (new \DateTime())->format('Y-m-d');

 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		
 		$controle = new DateHelper();
 			
 		if($pat['DATE_NAISSANCE']){
 			
 			//POUR LES AGES AVEC DATE DE NAISSANCE
 			//POUR LES AGES AVEC DATE DE NAISSANCE
 		
 			$age = $this->nbAnnees($pat['DATE_NAISSANCE'], $today);
 			
 			$donnees = array('AGE' => $age, 'DATE_MODIFICATION' => $today);
 			$sQuery = $sql->update()
 			->table('personne')
 			->set( $donnees )
 			->where(array('ID_PERSONNE' => $pat['ID_PERSONNE'] ));
 			$sql->prepareStatementForSqlObject($sQuery)->execute();
 				
 		} else {
 			
 			//POUR LES AGES SANS DATE DE NAISSANCE
 			//POUR LES AGES SANS DATE DE NAISSANCE
 		
 			$age = $this->nbAnnees($controle->convertDateInAnglais($controle->convertDate($pat['DATE_MODIFICATION'])), $today);
 			
 			if($age != 0) {
 				$donnees = array('AGE' => $age+$pat['AGE'], 'DATE_MODIFICATION' =>$today);
 				$sQuery = $sql->update()
 				->table('personne')
 				->set( $donnees )
 				->where(array('ID_PERSONNE' => $pat['ID_PERSONNE'] ));
 				$sql->prepareStatementForSqlObject($sQuery)->execute();
 			}

 		}
		
	}
	
	
}