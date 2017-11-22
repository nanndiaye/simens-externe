<?php
namespace Chururgie\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Chururgie\View\Helper\DateHelper;
use Doctrine\Tests\Common\Annotations\Null;


class AntecedentsFamiliauxTable {

	protected $tableGateway;
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	public function getAntecedentsFamiliaux($id_personne){
	    //var_dump($id_personne);exit();
		$rowset = $this->tableGateway->select ( array (
				'ID_PERSONNE' => $id_personne
		) );
		if (! $rowset) {
			return null;
		}
		$tableau= array();
		$j=0;
		foreach ($rowset as $tab){
		    //var_dump($tab);exit();
		    $tableau[$j]=$tab->id_antecedent;
		    $tableau[$j]=$tab->libelle;
		    $j++;
		}
		//var_dump($tableau);exit();
		return $tableau;
	}
	
	
	// Recuperer les nouveaux antecedents
	// Recuperer les nouveaux antecedents
	public function getAntecedentFamiliauxPersonneParIdPatient($id_patient){
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->columns(array('*'));
	    $select->from(array('afp'=>'ant_familiaux_patient'));
	    $select->where(array('afp.ID_PERSONNE' => $id_patient, 'afp.libelle != ?'=>''));
	    $result = $sql->prepareStatementForSqlObject($select)->execute();
// 	    $tab = array();
// 	    $j = 0;
// 	    foreach ($result as $resul){
// 	        $tab['checkboxAF_'.$j] = $resul['libelle'];
// 	        $j++;
// 	    }
	    //var_dump($tab);exit();
	    return $result;
	}
	
	
	/**
	 * Recuperer dans un tableau tous les antecedents familiaux du patient avec son id_personne
	 */
	public function getTableauAntecedentsFamiliaux($id_personne){
		$rowset = $this->tableGateway->select ( array (
				'ID_PERSONNE' => $id_personne
		) );
		//var_dump($rowset);exit();
		$Control = new DateHelper();
		$donnees = array();
		$donnees['DiabeteAF'] = 0;
		$donnees['DrepanocytoseAF'] = 0;
		$donnees['htaAF'] = 0;
		$donnees['asthmeAF'] = 0;
		$donnees['dislipidémieAF'] = 0;
		
		foreach ($rowset as $rows){
		  
			if($rows->id_antecedent == 1){
				$donnees['DiabeteAF'] = 1;
				//$donnees['NoteDiabeteAF'] = $rows->note;
			}
			if($rows->id_antecedent == 2){
				$donnees['DrepanocytoseAF'] = 1;
				//$donnees['NoteDrepanocytoseAF'] = $rows->note;
			}
			if($rows->id_antecedent == 3){
				$donnees['htaAF'] = 1;
				//$donnees['NoteHtaAF'] = $rows->note;
			}
			
			if($rows->id_antecedent == 7){
			    $donnees['dislipidémieAF'] = 1;
			    //$donnees['NoteHtaAF'] = $rows->note;
			}
			//var_dump($rows);exit();
			if($rows->id_antecedent == 8){
			    $donnees['asthmeAF'] = 1;
			   
			}
		}
		//var_dump($donnees);exit();
		return $donnees;
	}
	
	
	/**
	 * Recuperer l'id du patient avec son id_cons
	 */
	public function getIdPersonneParIdCons($id_cons){
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns( array( 'ID_PERSONNE' => 'PAT_ID_PERSONNE' ));
		$select->from( array( 'c' => 'consultation' ));
		$select->where(array('c.ID_CONS' =>$id_cons));

		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ()->current();
		
		return $result['ID_PERSONNE'];
	}
	
	/**
	 * Ajouter et mettre � jour les ant�c�dents familiaux des patients
	 */
	public function addAntecedentsFamiliaux($donneesDesAntecedents, $id_personne, $id_medecin){
		$this->tableGateway->getAdapter()->getDriver()->getConnection()->beginTransaction();
		$Control = new DateHelper();
		//var_dump($donneesDesAntecedents->nbCheckboxAF);exit();
		try {
			$this->tableGateway->delete(array('ID_PERSONNE' => $id_personne));
			//LES ANTECEDANTS FAMILIAUX
			//LES ANTECEDANTS FAMILIAUX
			
			/*Diab�te*/
			if($donneesDesAntecedents['DiabeteAF'] == 1){
				$donneesAntecedents = array(
						'ID_PERSONNE' => $id_personne,
						'ID_ANTECEDENT' => 1,
						//'NOTE' => $donneesDesAntecedents['NoteDiabeteAF'], 
						'ID_EMPLOYE' => $id_medecin,
				);
				
				$this->tableGateway->insert($donneesAntecedents);
			}
			/*Dr�panocytose*/
			if($donneesDesAntecedents['DrepanocytoseAF'] == 1){
				$donneesAntecedents = array(
						'ID_PERSONNE' => $id_personne,
						'ID_ANTECEDENT' => 2,
						//'NOTE' => $donneesDesAntecedents['NoteDrepanocytoseAF'],
						'ID_EMPLOYE' => $id_medecin,
				);
			
				$this->tableGateway->insert($donneesAntecedents);
			}
			/*HTA*/
			if($donneesDesAntecedents['htaAF'] == 1){
				$donneesAntecedents = array(
						'ID_PERSONNE' => $id_personne,
						'ID_ANTECEDENT' => 3,
						//'NOTE' => $donneesDesAntecedents['NoteHtaAF'],
						'ID_EMPLOYE' => $id_medecin,
				);
					
				$this->tableGateway->insert($donneesAntecedents);
			}
			/*displédémie*/
			if($donneesDesAntecedents['dislipidemieAF'] == 1){
			    $donneesAntecedents = array(
			        'ID_PERSONNE' => $id_personne,
			        'ID_ANTECEDENT' => 7,
			        //'NOTE' => $donneesDesAntecedents['NoteHtaAF'],
			        'ID_EMPLOYE' => $id_medecin,
			    );
			    
			    $this->tableGateway->insert($donneesAntecedents);
			}
			
			/*Asthme*/
			if($donneesDesAntecedents['asthmeAF'] == 1){
			    $donneesAntecedents = array(
			        'ID_PERSONNE' => $id_personne,
			        'ID_ANTECEDENT' => 8,
			        //'NOTE' => $donneesDesAntecedents['NoteHtaAF'],
			        'ID_EMPLOYE' => $id_medecin,
			    );
			    
			    $this->tableGateway->insert($donneesAntecedents);
			}

			//var_dump($donneesAntecedents);exit();
			$this->tableGateway->getAdapter()->getDriver()->getConnection()->commit();
			
		} catch (\Exception $e) {
			$this->tableGateway->getAdapter()->getDriver()->getConnection()->rollback();
		}
	}
	
	//Recupere l'antecedent Familiaux de la personne
	//Recupere l'antecedent Familiaux de la personne
	public function getAntecedentFamiliauxPersonneParId($id, $id_patient){
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->from(array('afp'=>'ant_familiaux_patient'));
	    $select->columns(array('*'));
	    $select->where(array('ID_ANTECEDENT' => $id, 'ID_PERSONNE' => $id_patient));
	    return $sql->prepareStatementForSqlObject($select)->execute()->current();
	}
	
	
	
	//Recupere les antecedents Familiaux
	//Recupere les antecedents Familiaux
	public function getAntecedentFamiliauxParLibelle($libelle){
	  
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->from(array('af'=>'ant_familiaux_patient'));
	    $select->columns(array('*'));
	    $select->where(array('libelle' => $libelle));
	   
	    return $sql->prepareStatementForSqlObject($select)->execute()->current();
	}
	
	
	
	//Ajout des antecedents familiaux
	//Ajout des antecedents familiaux
	//Ajout des antecedents familiaux
	//Ajout des antecedents familiaux
	public function addAntecedentFamiliauxNouveaux($data,$id_patient, $id_medecin){
	    $date = (new \DateTime())->format('Y-m-d H:i:s');
	    $db = $this->tableGateway->getAdapter();
	    $sql = new Sql($db);
	   
	    //var_dump($data->id_patient);exit();
	    for($i = 0; $i<$data->nbCheckboxAF; $i++){
	        $champ = "champTitreLabelAF_".$i;
	        $libelle =  $data->$champ;
	       
	        if(!$this->getAntecedentFamiliauxParLibelle($libelle)){
	           
	            $sQuery = $sql->insert()
	            ->into('ant_familiaux_patient')
	            ->values(array('libelle' => $libelle, 'ID_PERSONNE'=> $id_patient,'ID_EMPLOYE' => $id_medecin, 'DATE_ENREGISTREMENT' => $date));
	            
	            $sql->prepareStatementForSqlObject($sQuery)->execute();
	         
	        }
	        
	    }
	 
	}
	
	

}