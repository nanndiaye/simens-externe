<?php
namespace Chururgie\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\XmlRpc\Value\String;
use Chururgie\View\Helper\DateHelper;
use Doctrine\Tests\Common\Annotations\Null;

class RvPatientConsTable{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	public function getRendezVous($id){
		$rowset = $this->tableGateway->select ( array (
				'ID_CONS' => $id
		) );
		$row =  $rowset->current ();
		if (! $row) { 
 			//throw new \Exception ( "Could not find row $id" );
 			return $row;
 		}
		return $row;
	}
	
	public function updateRendezVous($infos_rv){
		$this->tableGateway->delete(array('ID_CONS'=>$infos_rv['ID_CONS']));
		
		if($infos_rv['DATE'] && $infos_rv['HEURE']){
			$this->tableGateway->insert($infos_rv);
		}
	}
	
	
	
	
	
	public function getTousRV(){
	  
	    //$today = new \DateTime ( 'now' );
	    //mettre la variable qui permet de r�cup�rer la date d'aujourd'hui et de le mettre dans la clause where $today = new \DateTime ( 'now' );
	    //$comp_date=$this->controlDate->convertDate($leRendezVous->date);
	    $db = $this->tableGateway->getAdapter();
	    $aColumns = array('NUMERO_DOSSIER', 'Nom','Prenom','Age','Sexe', 'Adresse', 'Nationalite', 'id', 'id2');
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
	    //->from(array('a' => 'admission'))->columns(array('*'))
	    ->join(array('p' => 'personne'), 'pat.id_personne = p.id_personne' , array('Nom'=>'NOM','Prenom'=>'PRENOM','Datenaissance'=>'DATE_NAISSANCE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','Taille'=>'TAILLE','id'=>'ID_PERSONNE', 'id2'=>'ID_PERSONNE', 'Age'=>'AGE'))
	    //->join(array('a' => 'admission'), 'p.ID_PERSONNE = a.id_patient', array('Id_admission' => 'id_admission'))
	    ->join(array('cons' => 'consultation'), 'p.ID_PERSONNE = cons.ID_PATIENT', array('Id_cons' => 'ID_CONS'))
	    ->join(array('rv' => 'rendezvous_consultation') , 'rv.ID_CONS = cons.ID_CONS', array('DELAI' => 'DELAI'))
	    ->order('rv.HEURE_ENR ASC')
	    ->where(array( 'rv.DATE' => null));
	    //->where(array( 'rv.$comp_date' => '00/00/0000'));
	   
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
	                    
	                    $date_naissance = $aRow[ $aColumns[$i] ];
	                    if($date_naissance){ $row[] = $Control->convertDate($aRow[ $aColumns[$i] ]); }else{ $row[] = null;}
	                }
	                else if ($aColumns[$i] == 'Adresse') {
	                    $row[] = $aRow[ $aColumns[$i] ];
	                }
	                else if ($aColumns[$i] == 'id') {
	                    
	                    $html ="<infoBulleVue> <a href='".$tabURI[0]."public/chururgie/info-patient-rv?id_cons=".$aRow[ 'Id_cons' ]."&id_patient=".$aRow[ $aColumns[$i] ]."'>";
	                    $html .="<img style='display: inline; margin-right: 10%;' src='".$tabURI[0]."public/images_icons/voir2.png' title='d&eacute;tails'></a></infoBulleVue>";
	                    
	                    //$html .= "<infoBulleVue> <a href='".$tabURI[0]."public/secretariat/modifier/id_patient/".$aRow[ $aColumns[$i] ]."'>";
	                    //$html .="<img style='display: inline; margin-right: 10%;' src='".$tabURI[0]."public/images_icons/pencil_16.png' title='Modifier'></a></infoBulleVue>";
	                    
	                    // 						if(!$this->verifierExisteAdmission($aRow[ $aColumns[$i] ])){
	                    // 							$html .= "<infoBulleVue> <a id='".$aRow[ $aColumns[$i] ]."' href='javascript:supprimer(".$aRow[ $aColumns[$i] ].");'>";
	                    // 							$html .="<img style='display: inline;' src='".$tabURI[0]."public/images_icons/symbol_supprimer.png' title='Supprimer'></a></infoBulleVue>";
	                    // 						}
	                    $row[] = $html;
	            }
	            else {
	                $row[] = $aRow[ $aColumns[$i] ];
	            }
	        }
	    }
	    $output['aaData'][] = $row;
	}
	
	
	
	
	
	/*
	 * SQL queries
	 */
	$sql2 = new Sql($db);
	$sQuery2 = $sql2->select()
	->from(array('pat' => 'patient'))->columns(array('*'))
	//->from(array('a' => 'admission'))->columns(array('*'))
	->join(array('p' => 'personne'), 'pat.id_personne = p.id_personne' , array('Nom'=>'NOM','Prenom'=>'PRENOM','Datenaissance'=>'DATE_NAISSANCE','Sexe'=>'SEXE','Adresse'=>'ADRESSE','Nationalite'=>'NATIONALITE_ACTUELLE','Taille'=>'TAILLE','id'=>'ID_PERSONNE', 'id2'=>'ID_PERSONNE', 'Age'=>'AGE'))
	//->join(array('a' => 'admission'), 'p.ID_PERSONNE = a.id_patient', array('Id_admission' => 'id_admission'))
	->join(array('cons' => 'consultation'), 'p.ID_PERSONNE = cons.ID_PATIENT', array('Id_cons' => 'ID_CONS'))
	->join(array('rv' => 'rendezvous_consultation') , 'rv.ID_CONS = cons.ID_CONS', array('delai' => 'DELAI'))
	->order('rv.HEURE_ENR ASC')
	->where(array( 'rv.DATE  != ?' => ""));
	
	/* Data set length after filtering */
	$stat = $sql2->prepareStatementForSqlObject($sQuery2);
	$rResultFt = $stat->execute();
	
	$rResult = $rResultFt;
	
	
	
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
	            }
	            else if ($aColumns[$i] == 'Adresse') {
	                $row[] = $aRow[ $aColumns[$i] ];
	            }
	            else if ($aColumns[$i] == 'id') {
	                
	                $html ="<infoBulleVue> <a href='".$tabURI[0]."public/chururgie/modifier-infos-patient-rv?id_cons=".$aRow[ 'Id_cons' ]."&id_patient=".$aRow[ $aColumns[$i] ]."'>";
	                $html .="<img style='display: inline; margin-right: 20%;' src='".$tabURI[0]."public/images_icons/pencil_16.png' title='modifier'></a></infoBulleVue>";
	                
	                $html .= "<infoBulleVue>";
	                $html .="<img style='display: inline; margin-right: 10%;' src='".$tabURI[0]."public/images_icons/tick_16.png'></a></infoBulleVue>";
	                
	                
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
}