<?php
namespace Chururgie\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\NotIn;

class ConsultationTable {


	// Recuperer Identification de la Demande d'examen
	// Recuperer Identification de la Demande d'examen
	// Recuperer Identification de la Demande d'examen
	
	public function getIdDemande($idCons,$idExamen){
	//var_dump($idCons,$idExamen);exit();
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		//var_dump($idCons);exit();
		$select = $sql->select();
		$select->columns(array('*'));
		$select->from(array( 'd' => 'demande'));
		$select->where(array('d.idCons'=>$idCons,'d.idExamen'=>$idExamen));
			$stat = $sql->prepareStatementForSqlObject($select);
			$res = $stat->execute();
			$idDemade = '';$i=1;
			foreach ($res as $result){
			$idDemade = $result['idDemande'];
		}
		//var_dump($idExamen);exit();
		return $idDemade;
		
	}
   
    public function updateResultatExamensComplementaires($id_medecin,$id_cons,$resulat_examen){
    	var_dump($resulat_examen);exit();
    	//$this->tableGateway->delete(array('id_consult'=>$id_cons));
    	$today = new \DateTime ();
    	$date_enreg = $today->format ( 'Y-m-d H:i:s' );
    	
    	 $db = $this->tableGateway->getAdapter();
         $sql = new Sql($db);
//          $re_sup = $sql->delete('*')->from('resultat_examen')->where(array('id_consult'=>$id_cons));
//          $sql->prepareStatementForSqlObject($re_sup)->execute();
         
     $j=1;
 //   $idDemandeExamen="";
        for($i=1; $i<=15;$i++){
        	if($j==7){
        		$j=8;
        	}
        	
        	if($resulat_examen[$i]!=""){
        		$idDemandeExamen = $this->getIdDemande($id_cons, $j );
        		$j++;
        		$sQuery = $sql->insert()
        		->into('resultats_examens')
        		->values(array(
        				'idDemande'=>$idDemandeExamen,
        				'Note_Resultat' => $resulat_examen[$i],
        				'id_personne' => $id_medecin,
        				'date_enregis'=> $date_enreg,
        				'id_consult' => $id_cons,
        		));
        		
        		
        	}
        	$sql->prepareStatementForSqlObject($sQuery)->execute();
        	
        }	
       
        
    }
    
    
    
    //  Inserer la note de l'histoire de  la maladie
    //  Inserer la note de l'histoire de la  maladie
    
    
    public function updateExamenPhysique($donnees)
    {
    	$this->tableGateway->delete(array('id_cons' => $donnees['id_cons']));
    
    	for($i=1 ; $i<=10; $i++){ // 5 car on s'arrete a 5 champs de donn�es
    		if($donnees['donnee'.$i]){
    			$datadonnee	 = array(
    					'libelle_examen' => $donnees['donnee'.$i],
    					'id_cons' => $donnees['id_cons'],
    			);
    			$this->tableGateway->insert($datadonnee);
    		}
    
    	}
    }
    
    
    public function addHistoireMaladie($donnees,$ID_CONS,$id_medecin){
        
    	$today = (new \DateTime())->format('Y-m-d');
        $db = $this->tableGateway->getAdapter();
        $sql = new Sql($db);
        // On fait la mise a jour l'id_cons existe
        $req_supp = $sql->delete('*')->from('histoire_maladie')->where(array('ID_CONS'=> $ID_CONS));
        $sql->prepareStatementForSqlObject($req_supp)->execute();
        
        
        for($i=1;$i<=10;$i++){
        	if($donnees['symptome'.$i]){
        		$sQuery = $sql->insert()
        		->into('histoire_maladie')
        		->values(array('histoire_maladie' => $donnees['symptome'.$i], 'ID_CONS'=> $ID_CONS,
        		'date_enregistrement'=>$today,'id_employe_e'=>$id_medecin));
        		$sql->prepareStatementForSqlObject($sQuery)->execute();
        	}
        }
//         if($formData->histoire_maladie){
//         $requeteUpdate = $sql->update('histoire_maladie')->set(array(
//         		'histoire_maladie' => $formData->histoire_maladie,
//         		'date_enregistrement'=>$formData->dateonly,
//         		'id_employe_e'=>$id_medecin
//         ))->where(array('ID_CONS'=> $ID_CONS));
//         $requeteUpdate = $sql->prepareStatementForSqlObject($requeteUpdate);
//         $result = $requeteUpdate->execute()->isQueryResult();
         
//         //Si non on enregistre dans la base de donnees
//         if(!$result){
       
//         	$sQuery = $sql->insert()
//         	->into('histoire_maladie')
//         	->values(array('histoire_maladie' => $formData->histoire_maladie, 'ID_CONS'=> $ID_CONS,
//         			'date_enregistrement'=>$formData->dateonly,'id_employe_e'=>$id_medecin));
//         	$sql->prepareStatementForSqlObject($sQuery)->execute();
        	
//         }
//         }
      
    }
    
    
    
    //  Inserer la note de l'autre de l'hisrorique
    //  Inserer la note de l'autre de l'hisrorique
    public function addAutreHistorique($formData,$ID_CONS){
        
       // var_dump($formData->autre_historique);exit();
        $db = $this->tableGateway->getAdapter();
        $sql = new Sql($db);
        
        if($formData->autre_historique){
        // On fait la mise a jour l'id_cons existe
        $requeteUpdate = $sql->update('autre_historique')->set(array(
        		'note_autre_historique' => $formData->autre_historique,
        			
        ))->where('ID_CONS',$ID_CONS);
        $requeteUpdate = $sql->prepareStatementForSqlObject($requeteUpdate);
        $result = $requeteUpdate->execute()->isQueryResult();
         
        //Si non on enregistre dans la base de donnees
        if(!$result){
        
        	$sQuery = $sql->insert()
        	->into('autre_historique')
        	->values(array('note_autre_historique' => $formData->autre_historique, 'ID_CONS'=> $ID_CONS));
        	$sql->prepareStatementForSqlObject($sQuery)->execute();
        }
       
        }
    }
    
    
    
    //  Inserer la note de l'examen de l'hisrorique
    //  Inserer la note de l'examen de l'hisrorique
    public function addExamenHistorique($formData,$ID_CONS){
        
        
        $db = $this->tableGateway->getAdapter();
        $sql = new Sql($db);
        
        if($formData->examen_historique){
        // On fait la mise a jour l'id_cons existe
        $requete = $sql->update('examen_historique')->set(array(
        	'note_historique_examen' => $formData->examen_historique,
        ))->where('ID_CONS',$ID_CONS);
        $requeteUpdate = $sql->prepareStatementForSqlObject($requete);
        $result = $requeteUpdate->execute()->isQueryResult();
         
        //Si no on enregistre dans la base de donnees
        if(!$result){
       
        		$sQuery = $sql->insert()
        		->into('examen_historique')
        		->values(array('note_historique_examen' => $formData->examen_historique, 'ID_CONS'=> $ID_CONS));
        		$sql->prepareStatementForSqlObject($sQuery)->execute();
        	}
        
        }
    }
    
    
    
    //  Inserer antécédents chirurgicaux
    //  Inserer antécédents chirurgicaux
    public function addAntecedentsChirurgicaux($formData,$id_patient,$id_cons){
    	$db = $this->tableGateway->getAdapter();
    	$sql = new Sql($db);
    	if($formData->antecedents_chirugicaux){
       // var_dump($formData->antecedents_chirugicaux);exit();
       $reqUpdate = $sql->update('antecedents_chirurgicaux')->set(array('note_antecedents'=>$formData->antecedents_chirugicaux))
       ->where('ID_CONS',$id_cons);
       $result = $sql->prepareStatementForSqlObject($reqUpdate);
       $res_execution = $result->execute($result)->isQueryResult();
       
     if(!$res_execution){

     	$sQuery = $sql->insert()
     	->into('antecedents_chirurgicaux')
     	->values(array('note_antecedents' => $formData->antecedents_chirugicaux, 'id_patient'=> $id_patient,'id_cons' => $id_cons,));
     	$sql->prepareStatementForSqlObject($sQuery)->execute();
     	 
     }
     	

       }
        
    }
    
    
    /**
     * RECUPERER Les types de pathologlogies de la conultation
     */
    public  function getConsTypePathologie($id_cons){
        
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql ( $adapter );
        $select = $sql->select();
        $select->columns(array('*'));
        $select->from(array('cp' => 'consultationpathologie'));
        $select->join(array('tp'=>'type_pathologie'),
            'cp.type_patho = tp.id_type_pathologie',array("*"));
        $select->where(array('cp.ID_CONS'=> $id_cons));
        $stat = $sql->prepareStatementForSqlObject($select);
        $resultat = $stat->execute();
        //var_dump($id_cons);exit();
        //         $listeorgane=array();
        //         $j=0;
        //         foreach ($resultat as $result) {
        //             $listeorgane[$j++] = $result["nom_type_pathologie"];
        //         }
            // var_dump($listeorgane);exit();
        return $resultat;
    }
    
    /**
     * Ajouter une nouvelle pathologie dans la base de donn�es
     */
    public function addPathologie($patho){
        $db = $this->tableGateway->getAdapter();
        $sql = new Sql($db);
        $sQuery = $sql->insert()
        ->into('type_pathologie')
        ->values(array('nom_type_pathologie' => $patho));
        $requete = $sql->prepareStatementForSqlObject($sQuery);
        return $requete->execute()->getGeneratedValue();
    }
    
    /**
     * Ajouter des pathologies
     */
    public function addConsPatho($ID_CONS,$id_type_pathologie){
        //if( $this->existeFormes($libelleForme) == false ){
      // var_dump($ID_CONS,$id_type_pathologie);exit();
            $db = $this->tableGateway->getAdapter();
            $sql = new Sql($db);
            $sQuery = $sql->insert()
            ->into('consultationpathologie')
            ->values(array('ID_CONS' => $ID_CONS, 'type_patho'=> $id_type_pathologie));
            $sql->prepareStatementForSqlObject($sQuery)->execute();
       // }
    }
    
    
    
 /**
     * verifier si la pathologie existe dans la base de donnees
     */
    
    public function getPathologiesByName($lestypesPathologies){
        
        $adapter = $this->tableGateway->getAdapter ();
        $sql = new Sql ( $adapter );
        $select = $sql->select ();
        $select->columns( array('*'));
        $select->from( array( 'c' => 'type_pathologie' ));
        $select->where ( array( 'c.nom_type_pathologie' => $lestypesPathologies));
        $stat = $sql->prepareStatementForSqlObject ( $select );
        $result = $stat->execute ()->current();
        
        return $result;
    }
    
    /**
     * RECUPERER Les types de pathologlogies
     */
    public  function getClassePathologie(){
        
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql ( $adapter );
        $select = $sql->select('classe_pathologie');
        $select->columns(array( 'id_classe_pathologie','nom_classe_pathologie'));
        $stat = $sql->prepareStatementForSqlObject($select);
        $result = $stat->execute();
        
        return $result;
    }
    
    
    /**
     * RECUPERER La liste des organes pour la pathologie
     */
     public function listeDeTousLesOrganes(){
         
   
         $adapter = $this->tableGateway->getAdapter();
         $sql = new Sql ( $adapter );
         $select = $sql->select('organe');
         $select->columns(array( 'id_organe','LESORGANES'));
         $stat = $sql->prepareStatementForSqlObject($select);
         $result = $stat->execute();
         
         return $result;
         
// 		$adapter = $this->tableGateway->getAdapter();
// 		$sql = new Sql ( $adapter );
// 		$select = $sql->select('organe');
// 		$select->columns(array('*'));
// 		$stat = $sql->prepareStatementForSqlObject($select);
// 		$resultat = $stat->execute();
//                 $listeorgane=array();
//                 foreach ($resultat as $result) {
//                     $listeorgane[$result["id_organe"]] = $result["nom_organe"];
//                 }
                
// 	        return $listeorgane;
	}
    
    /**
     * RECUPERER Les classes de pathologlogies
     */
	public  function getTypePathologie(){
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql ( $adapter );
	    $select = $sql->select('type_pathologie');
	    $select->columns(array('*'));
	    $stat = $sql->prepareStatementForSqlObject($select);
	    $resultat = $stat->execute();
	    $listeorgane=array();
	    $j=0;
	    foreach ($resultat as $result) {
	        $listeorgane[$j++] = $result["nom_type_pathologie"];
	    }
	    
	    return $listeorgane;
    }
    
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	public function getConsult($id){
	   
	    $id = (String) $id;
	  
	    $rowset = $this->tableGateway->select ( array (
	        'ID_CONS' => $id
	    ) );
	    $row =  $rowset->current ();
	    if (! $row) {
	        throw new \Exception ( "Could not find row $id" );
	    }
	   // var_dump($row);exit();
	    return $row;
	}
	
	
	// Recuperer Autre de la partie de l'histoirique
	// Recuperer Autre de la partie de l'histoirique
	// Recuperer Autre de la partie de l'histoirique
	
	public function getAutreDEHIstoriqueIDCONS($id_cons){
	    $today = (new \DateTime())->format('Y-m-d');
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->columns(array('*'));
	    $select->from(array('eh'=> 'autre_historique'));
	    $where = new Where();
	    $where->equalTo('eh.ID_CONS', $id_cons);
	    
	    $select->where($where);
	    $stat = $sql->prepareStatementForSqlObject($select);
	    $result = $stat->execute ();
	    //var_dump( $result);exit();
	    $autre_histori= '';
	    foreach ($result as $result) {
	        $autre_histori = $result["note_autre_historique"];
	     
	    }
	    //var_dump( $autre_histori);exit();
	    return $autre_histori;
	    
	}
	
	
	
	// Recuperer Examen de la partie de l'histoirique 
	// Recuperer Examen de la partie de l'histoirique
	// Recuperer Examen de la partie de l'histoirique
	
	public function getExamenDEHIstoriqueIDCONS($id_cons){
	    $today = (new \DateTime())->format('Y-m-d');
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->columns(array('*'));
	    $select->from(array('eh'=> 'examen_historique'));
	    $where = new Where();
	    $where->equalTo('eh.ID_CONS', $id_cons);
	    
	    $select->where($where);
	    $stat = $sql->prepareStatementForSqlObject($select);
	    $result = $stat->execute ();
	    $examen_histori='';
	    foreach ($result as $result) {
	        $examen_histori = $result["note_historique_examen"];
	    }
	    //var_dump( $histoire_maladie);exit();
	    return $examen_histori;
	    
	}
	
	
	
	
	
	// Recuperer l'histoire de la maladie
	// Recuperer l'histoire de la maladie
	// Recuperer l'histoire de la maladie 
	
	public function getHistoireDeLaMaladieIDCONS($id_cons){
	    $today = (new \DateTime())->format('Y-m-d');
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->columns(array('*'));
	    $select->from(array('hm'=> 'histoire_maladie'));
	    $where = new Where();
	    $where->equalTo('hm.ID_CONS', $id_cons);
	  
	    $select->where($where);
	    $select->order('id_histoire_maladie ASC');
	    $stat = $sql->prepareStatementForSqlObject($select);
	    $result = $stat->execute ();
	    return $result;
	    
	}
	
	
	
	
	
	
	
	// Recuperer l'antécédent chirurgical
	// Recuperer l'antécédent chirurgical
	// Recuperer l'antécédent chirurgical
	
	public function getAntecedentChirugicalByID($id_pat,$id_cons){
	    $today = (new \DateTime())->format('Y-m-d');
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->columns(array('*'));
	    $select->from(array('ac'=> 'antecedents_chirurgicaux'));
	    $select->join(array('c'=> 'consultation'), 'c.ID_PATIENT = ac.id_patient',array('*'));
	    $where = new Where();
	    $where->equalTo('ac.id_patient', $id_pat);
	   $where->equalTo('c.ID_CONS', $id_cons);
	    $where->equalTo('c.DATEONLY', $today);
	    $select->where($where);
	    $stat = $sql->prepareStatementForSqlObject($select);
	    $result = $stat->execute ();
	    $j=0;
	    $ant_chirur='';
	    foreach ($result as $result) {
	        $ant_chirur = $result["note_antecedents"];
	    }
	    //var_dump( $ant_chirur);exit();
	    return $ant_chirur;
	    
	}
	
	public function getConsultationPatient($id_pat){
	
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns( array( '*' ));
		$select->from( array( 'c' => 'consultation' ));
		$select->join( array('e1' => 'employe'), 'e1.id_personne = c.ID_MEDECIN' , array('*'));
		//$select->join( array('e2' => 'employe'), 'e2.id_personne = c.ID_SURVEILLANT' , array());
		$select->join( array('p1' => 'personne'), 'e1.id_personne = p1.ID_PERSONNE' , array('*'));
		$select->join( array('p2' => 'personne'), 'e1.id_personne = p2.ID_PERSONNE' , array('NomSurveillant' => 'NOM', 'PrenomSurveillant' => 'PRENOM'));
		$select->join( array('s' => 'service'), 's.ID_SERVICE = c.ID_SERVICE' , array('nomService' => 'NOM', 'domaineService' => 'DOMAINE'));

		//On affiche toutes les consultations sauf celle ouverte
		$where = new Where();
		$where->equalTo('c.ID_PATIENT', $id_pat);
		//$where->notEqualTo('c.ID_CONS', $id_cons);
		$select->where($where);
		$select->order('DATEONLY DESC');
		
		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ();
		//var_dump($result);exit();
		return $result;
	}
	
	public function getConsultationDuJour(){
		$today = (new \DateTime())->format('Y-m-d');
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->from( array( 'c' => 'consultation' ));
		$select->where(array('DATEONLY' => $today));
		return $sql->prepareStatementForSqlObject ( $select )->execute()->current();
	}
	/** --------------=============================------------------------------ */
	public function getConsultationPatientSaufActu($id_pat, $id_cons){
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns( array( '*' ));
		$select->from( array( 'c' => 'consultation' ));
		$select->join( array('e1' => 'employe'), 'e1.id_personne = c.ID_MEDECIN' , array('*'));
		$select->join( array('p1' => 'personne'), 'e1.id_personne = p1.ID_PERSONNE' , array('*'));
		$select->join( array('s' => 'service'), 's.ID_SERVICE = c.ID_SERVICE' , array('nomService' => 'NOM', 'domaineService' => 'DOMAINE'));
	
		//La consultation du jour -- pour �viter d'afficher la consultation du jour
		$id_cons_du_jour = $this->getConsultationDuJour()['ID_CONS'];
		
		//On affiche toutes les consultations sauf celle ouverte
		$where = new Where();
		$where->equalTo('c.ID_PATIENT', $id_pat);
		$where->notEqualTo('c.ID_CONS', $id_cons);
		$where->notEqualTo('c.ID_CONS', $id_cons_du_jour);
		$select->where($where);
		$select->order('DATEONLY DESC');
	
		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ();
	
		return $result;
	}
	
	public function getInfosSurveillant($id_personne){
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns( array( '*' ));
		$select->from( array('e1' => 'employe'));
		$select->join( array('p1' => 'personne'), 'e1.id_personne = p1.ID_PERSONNE' , array('*'));
	
		$where = new Where();
		$where->equalTo('e1.id_personne', $id_personne);
		$select->where($where);
	
		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ();
	
		return $result->current();
	}
	/** --------------=============================-------------------------------*/
	
	public function updateConsultation($values)
	{
	    
	    
		$donnees = array(
				'POIDS' => $values->get ( "poids" ), 
				'TAILLE' => $values->get ( "taille" ), 
				'TEMPERATURE' => $values->get ( "temperature" ), 
				'PRESSION_ARTERIELLE' => $values->get ( "tensionmaximale" ).'/'.$values->get ( "tensionminimale" ),
				'POULS' => $values->get ( "pouls" ), 
				'FREQUENCE_RESPIRATOIRE' => $values->get ( "frequence_respiratoire" ), 
				'GLYCEMIE_CAPILLAIRE' => $values->get ( "glycemie_capillaire" ), 
		);
		//var_dump($donnees);exit();
		$this->tableGateway->update( $donnees, array('ID_CONS'=> $values->get ( "id_cons" )) );
	}
	
// 	public function validerConsultation($values){
// 		$donnees = array(
// 				'CONSPRISE' => $values['VALIDER'],
// 				'ID_MEDECIN' => $values['ID_MEDECIN']
// 		);
// 			$adapter = $this->tableGateway->getAdapter ();
// 			$sql = new Sql ( $adapter );
// 		$req = $sql->update('admission')->set('CONS_FAIT',$values['VALIDER'])->where('id_admission',$values['id_admission']);
// 		$stat = $sql->prepareStatementForSqlObject($req);
// 		$stat->execute($req);
// 		//$this->tableGateway->update($donnees, array('ID_CONS'=> $values['ID_CONS']));
// 	}
	
	public function validerAdmissionConsulte($values){
		
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$req = $sql->update('admission')->set(array('CONS_FAIT'=>$values['VALIDER']))->where(array('id_admission'=>$values['id_admission']));
		$stat = $sql->prepareStatementForSqlObject($req);
		$stat->execute();
	}
	
	public function addConsultation($values , $IdDuService,$id_medecin,$id_admission ){
	    $today = new \DateTime();
	    $date = $today->format('Y-m-d');
		$this->tableGateway->getAdapter()->getDriver()->getConnection()->beginTransaction();
		try {
			// var_dump($values->tensionmaximale,$values->tensionminimale);exit();
			$dataconsultation = array(
					'ID_CONS'=> $values->id_cons, 
			          'ID_MEDECIN'=> $id_medecin, 
					'ID_PATIENT'=> $values->id_patient,
			         'id_admission'=>$id_admission,
			          'DATE'=> $date, 
					'POIDS' => $values->poids, 
					'TAILLE' => $values->taille, 
					'TEMPERATURE' => $values->temperature, 
			    'PRESSION_ARTERIELLE' => $values->tensionmaximale.' / '. $values->tensionminimale, 
					'POULS' => $values->pouls, 
					'FREQUENCE_RESPIRATOIRE' => $values->frequence_respiratoire, 
					'GLYCEMIE_CAPILLAIRE' => $values->glycemie_capillaire, 
			         'DATEONLY' => $date,
					'HEURECONS' => $values->heure_cons,
					'ID_SERVICE' => $IdDuService,
			    			
			         
			);
		
			$resultatUpdate = $this->updateConsultation($values);
		//
			 if(!$resultatUpdate){
			 
			 	$this->tableGateway->insert($dataconsultation);
			 	
			 }
		
			 $this->tableGateway->getAdapter()->getDriver()->getConnection()->commit();
			 //var_dump($id_admission);exit();
			
			
		} catch (\Exception $e) {
			$this->tableGateway->getAdapter()->getDriver()->getConnection()->rollback();
		}
	}
	
	
	// Ajout des demandes d'examens fonctionnels
	public function AddDemandeExamenFonctionnel($tabExamen,$NoteExamen,$idCons){
	    $today = new \DateTime();
	    $date = $today->format('Y-m-d');
	  $db= $this->tableGateway->getAdapter();
	  $sql = new Sql($db);
	  //var_dump();exit($idCons);
	  // On fait la mise a jour l'id_cons existe 
	  $requeteUpdate = $sql->update('demande')->set(array(
	          'noteDemande'=>$NoteExamen,
	          'dateDemande'=>$date,
	          'idExamen'=>$tabExamen,
	          
	      ))->where(array('idCons'=>$idCons));
	  $requeteUpdate = $sql->prepareStatementForSqlObject($requeteUpdate);
	  $result = $requeteUpdate->execute()->isQueryResult();
	  
	  // Si non on fait une insertion dans la base de données
	      if(!$result){
	      	
	      	$sQuery = $sql->insert()->into('demande')
	      	->values(array(
	      			'noteDemande'=>$NoteExamen,
	      			'dateDemande'=>$date,
	      			'idCons'=>$idCons,
	      			'idExamen'=>$tabExamen,
	      			 
	      	));
	      	$requete = $sql->prepareStatementForSqlObject($sQuery);
	      	$requete->execute();
	      }
	     
	 
	}

	
	// Ajout des demandes d'examens Morphologique
	public function AddDemandeExamen($tab_result,$tabExamen,$NoteExamen,$idCons){
		$today = new \DateTime();
		$date = $today->format('Y-m-d');
		$db= $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		//var_dump();exit($idCons);
		// On fait la mise a jour l'id_cons existe
// 		$requeteUpdate = $sql->update('demande')->set(array(
// 				'noteDemande'=>$NoteExamen,
// 				'dateDemande'=>$date,
// 				'idExamen'=>$tabExamen,
				 
// 		))->where(array('idCons'=>$idCons, 'idExamen'=>$tabExamen));
// 		  $requeteUpdate = $sql->prepareStatementForSqlObject($requeteUpdate);
// 		  $result = $requeteUpdate->execute()->isQueryResult();
		  
		  $re = $sql->delete(array('*'))->from('demande')->where(array('idCons'=>$idCons, 'idExamen'=>$tabExamen));
		  $sql->prepareStatementForSqlObject($re)->execute();
		  	
		  
		  // Si non on fait une insertion dans la base de données
		
	
		$sQuery = $sql->insert()->into('demande')
			->values(array(
			'noteDemande'=>$NoteExamen,
			'dateDemande'=>$date,
			'idCons'=>$idCons,
			'idExamen'=>$tabExamen,
			'noteResultat' =>$tab_result,
			 
			));
			$requete = $sql->prepareStatementForSqlObject($sQuery);
			$requete->execute();
		
	
	
	}
	
	// Ajout des demandes d'examens Biologiques
	public function AddDemandeExamenBiologique($tabExamen,$NoteExamen,$idCons){
		$today = new \DateTime();
		$date = $today->format('Y-m-d');
		$db= $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		//var_dump();exit($idCons);
		// On fait la mise a jour l'id_cons existe
		$requeteUpdate = $sql->update('demande')->set(array(
				'noteDemande'=>$NoteExamen,
				'dateDemande'=>$date,
				'idExamen'=>$tabExamen,
				 
		))->where(array('idCons'=>$idCons));
		  $requeteUpdate = $sql->prepareStatementForSqlObject($requeteUpdate);
		  $result = $requeteUpdate->execute()->isQueryResult();
		   
		  // Si non on fait une insertion dans la base de données
		if(!$result){
	
		$sQuery = $sql->insert()->into('demande')
			->values(array(
			'noteDemande'=>$NoteExamen,
			'dateDemande'=>$date,
			'idCons'=>$idCons,
			'idExamen'=>$tabExamen,
			 
			));
			$requete = $sql->prepareStatementForSqlObject($sQuery);
			$requete->execute();
		}
	
	
		}
	
	
	
	//Ajouter Idadmission a la table consultation
	public function addIdAdmission($id_admission,$date_admise){
	    
	    $db = $this->tableGateway->getAdapter();
	    $sql = new Sql($db);
	    $sQuery = $sql->insert()
	    ->into('admissionChirurgie')
	    ->values(array('id_admission' => $id_admission,
	                       'date' => $date_admise));
	    $requete = $sql->prepareStatementForSqlObject($sQuery);
	  //var_dump($id_admission);exit();
	  $requete->execute();
	  //var_dump($id_admission);exit();
	}
	
	
	
	public function getInfoPatientMedecin($idcons){
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns( array( '*' ));
		$select->from( array( 'c' => 'consultation' ));
		$select->join( array('s' => 'service'), 's.ID_SERVICE = c.ID_SERVICE' , array (
				'NomService' => 'NOM',
				'DomaineService' => 'DOMAINE'
		) );
		$select->join( array('p' => 'patient' ), 'p.ID_PERSONNE = c.ID_PATIENT' , array('*'));
		$select->join( array('pers' => 'personne'), 'pers.ID_PERSONNE = p.ID_PERSONNE' , array('*'));
		$select->join( array('m' => 'personne'), 'm.ID_PERSONNE = c.ID_MEDECIN' , array(
				'NomMedecin' => 'NOM', 
				'PrenomMedecin' => 'PRENOM', 
				'AdresseMedecin' => 'ADRESSE',
				'TelephoneMedecin' => 'TELEPHONE'
		));
		$select->where ( array( 'c.ID_CONS' => $idcons));
		
		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ();
		
		return $result;
	}
	
	public function addBandelette($bandelettes){
		$values = array();
		if($bandelettes['albumine'] == 1){
			$values[] = array('ID_TYPE_BANDELETTE'=>1, 'ID_CONS'=>$bandelettes['id_cons'], 'CROIX_BANDELETTE'=>(int)$bandelettes['croixalbumine']);
		}
		if($bandelettes['sucre'] == 1){
			$values[] = array('ID_TYPE_BANDELETTE'=>2, 'ID_CONS'=>$bandelettes['id_cons'], 'CROIX_BANDELETTE'=>(int)$bandelettes['croixsucre']);
		}
		if($bandelettes['corpscetonique'] == 1){
			$values[] = array('ID_TYPE_BANDELETTE'=>3, 'ID_CONS'=>$bandelettes['id_cons'], 'CROIX_BANDELETTE'=>(int)$bandelettes['croixcorpscetonique']);
		}
	
		for($i = 0 ; $i < count($values) ; $i++ ){
		  
			$db = $this->tableGateway->getAdapter();
			$sql = new Sql($db);
			$sQuery = $sql->insert()
			->into('bandelette')
			->columns(array('ID_TYPE_BANDELETTE', 'ID_CONS', 'CROIX_BANDELETTE'))
			->values($values[$i]);
			//var_dump($values[$i]);exit();
			$stat = $sql->prepareStatementForSqlObject($sQuery);
			$stat->execute();
			
		}
	}
	
	public function getBandelette($id_cons){
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->select()
		->from('bandelette')
		->columns(array('*'))
		->where(array('id_cons' => $id_cons));
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$result = $stat->execute();
		
		$donnees = array();
		$donnees['temoin'] = 0;
		foreach ($result as $resultat){
			if($resultat['ID_TYPE_BANDELETTE'] == 1){
				$donnees['albumine'] = 1; //C'est � coch�
				$donnees['croixalbumine'] = $resultat['CROIX_BANDELETTE'];
			}
			if($resultat['ID_TYPE_BANDELETTE'] == 2){
				$donnees['sucre'] = 1; //C'est � coch�
				$donnees['croixsucre'] = $resultat['CROIX_BANDELETTE'];
			}
			if($resultat['ID_TYPE_BANDELETTE'] == 3){
				$donnees['corpscetonique'] = 1; //C'est � coch�
				$donnees['croixcorpscetonique'] = $resultat['CROIX_BANDELETTE'];
			}
			
			//temoin
			$donnees['temoin'] = 1;
		}
		
		return $donnees;
	}
	
	public function deleteBandelette($id_cons){
		$db = $this->tableGateway->getAdapter();
		$sql = new Sql($db);
		$sQuery = $sql->delete()
		->from('bandelette')
		->where(array('id_cons' => $id_cons));
		$stat = $sql->prepareStatementForSqlObject($sQuery);
		$result = $stat->execute();
	}
	
	//Tous les patients consultes sauf ceux du jour
	public function tousPatientsCons($idService){
		$today = new \DateTime();
		$date = $today->format('Y-m-d');
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->from ( array (
				'p' => 'patient'
		) );
		$select->columns(array () );
		$select->join(array('pers' => 'personne'), 'pers.ID_PERSONNE = p.ID_PERSONNE', array(
				'Nom' => 'NOM',
				'Prenom' => 'PRENOM',
				'Datenaissance' => 'DATE_NAISSANCE',
				'Sexe' => 'SEXE',
				'Adresse' => 'ADRESSE',
				'Nationalite' => 'NATIONALITE_ACTUELLE',
				'Id' => 'ID_PERSONNE'
		));
		
		$select->join(array('c' => 'consultation'), 'p.ID_PERSONNE = c.ID_PATIENT', array('Id_cons' => 'ID_CONS', 'Dateonly' => 'DATEONLY', 'Consprise' => 'CONSPRISE'));
		$select->join(array('s' => 'service'), 'c.ID_SERVICE = s.ID_SERVICE', array('Nomservice' => 'NOM'));
		$select->join(array('cons_eff' => 'consultation_effective'), 'cons_eff.ID_CONS = c.ID_CONS' , array('*'));
		$where = new Where();
		$where->equalTo('s.ID_SERVICE', $idService);
		$where->notEqualTo('DATEONLY', $date);
		$select->order('c.DATE DESC');
		$select->group('c.ID_PATIENT');
		$select->where($where);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		return $result;
	}
	
	
	
	//liste des patients à consulter par le medecin dans le service de ce dernier
	public function listePatientsConsultes($idService){
	    $today = new \DateTime();
	    $date = $today->format('Y-m-d');
	    $adapter = $this->tableGateway->getAdapter ();
	    $sql = new Sql ( $adapter );
	    $select = $sql->select ();
	    $select->from ( array (
	        'p' => 'patient'
	    ) );
	    $select->columns(array ('NUMERO_DOSSIER'=> 'NUMERO_DOSSIER') );
	    $select->join(array('pers'=>'personne'), 'pers.ID_PERSONNE = p.ID_PERSONNE', array(
	        'Nom' => 'NOM',
	        'Prenom' => 'PRENOM',
	        'Datenaissance' => 'DATE_NAISSANCE',
	        'AGE'=>'AGE',
	        'Sexe' => 'SEXE',
	        'Adresse' => 'ADRESSE',
	        'Nationalite' => 'NATIONALITE_ACTUELLE',
	        'Id' => 'ID_PERSONNE'
	    ));
	   
	    $select->join(array('c' => 'consultation'), 'p.ID_PERSONNE = c.ID_PATIENT', array('*'));
	    $select->join(array('a' => 'admission'), 'c.id_admission = a.id_admission', array('*'));
	   
	    $select->where(array('c.ID_SERVICE' => $idService, 'DATEONLY' => $date));
	  
	    $select->order('c.ID_CONS ASC');
	    
// 	    $select->join(array('a' => 'admission'), 'a.ID_PATIENT = p.ID_PERSONNE', array('Id_admission' => 'id_admission','date_admise'=>'date_admise',
// 	        'type_consultation'=>'type_consultation','id_service'=>'id_service'));
// 	    $select->join(array('type_cons' => 'type_consultation'), 'type_cons.ID = a.type_consultation', array('designation' => 'designation'));
// 	    $select->join(array('c' => 'consultation'), 'a.Id_admission = c.Id_admission', array('ID_CONS'=>'ID_CONS','CONSPRISE' => 'CONSPRISE','date'=>'DATEONLY'));
// 	    $select->where(array('a.id_service' => $idService, 'a.date_admise' => $date, 'c.CONSPRISE'=> 1));
// 	    $select->order('id_admission ASC');
	    
	    $stmt = $sql->prepareStatementForSqlObject($select);
	    $result = $stmt->execute();
	    
	    return $result;
	    
	}
	
	public function ListeTypesCons(){
		
		$adapter = $this->tableGateway->getAdapter();
		$sql  = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('t_c' => 'type_consultation'));
		$select->columns(array('*'));
		$stat = $sql->prepareStatementForSqlObject($select);
		$result = $stat->execute();
		$tab= array(''=>'Tous'); $i=1;
		
		foreach ($result as $r){
			$tab[$r["designation"]] = $r["designation"];
			$i++;
		}
		return $tab;
	}
	
	
	//liste des patients à consulter par le medecin dans le service de ce dernier
	public function listePatientsConsParMedecin($idService){
		$today = new \DateTime();
		$date = $today->format('Y-m-d');
		$adapter = $this->tableGateway->getAdapter ();
		
		$sql2 = new Sql ($adapter );
		$select1= $sql2->select ();
		$select1->from ( array ('c' => 'consultation') );
		$select1->columns (array ('id_admission') );
		$stmt1 = $sql2->prepareStatementForSqlObject($select1);
		$result1= $stmt1->execute();
		foreach ($result1 as $r){
			$id_admi  = $r["id_admission"];
		}
		//var_dump($id_admi);exit();
		
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->from ( array (
				'p' => 'patient'
		) );
		$select->columns(array ('NUMERO_DOSSIER'=> 'NUMERO_DOSSIER') );
		$select->join(array('pers'=>'personne'), 'pers.ID_PERSONNE = p.ID_PERSONNE', array(
				'Nom' => 'NOM',
				'Prenom' => 'PRENOM',
				'Datenaissance' => 'DATE_NAISSANCE',
		         'AGE'=>'AGE',
				'Sexe' => 'SEXE',
				'Adresse' => 'ADRESSE',
				'Nationalite' => 'NATIONALITE_ACTUELLE',
				'Id' => 'ID_PERSONNE'
		));
		//
		//
		
		
		$select->join(array('a' => 'admission'), 'a.ID_PATIENT = p.ID_PERSONNE', array('Id_admission' => 'id_admission','date_admise'=>'date_admise',
		 'type_consultation'=>'type_consultation','id_service'=>'id_service'));
		//$select->join(array('c' => 'consultation'), 'a.date_admise = c.DATE', array('*'));
		$select->join(array('type_cons' => 'type_consultation'), 'type_cons.ID = a.type_consultation', array('designation' => 'designation'));
		$select->where(array(
		    'a.date_admise' => $date,
		    'CONS_FAIT != ?' => 1,
		));
		$select->order('id_admission ASC');
	
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		return $result;
	}
	
	public function getPatientsRV($id_service){
		$today = new \DateTime();
		$date = $today->format('Y-m-d');
	
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql( $adapter );
		$select = $sql->select();
		$select->from( array(
				'rec' =>  'rendezvous_consultation'
		));
		$select->join(array('cons' => 'consultation'), 'cons.ID_CONS = rec.ID_CONS ', array('*'));
		$select->where( array(
				'rec.DATE' => $date,
				'cons.ID_SERVICE' => $id_service,
		) );
	
		$statement = $sql->prepareStatementForSqlObject( $select );
		$resultat = $statement->execute();
	
		$tab = array();
		foreach ($resultat as $result) {
			$tab[$result['ID_PATIENT']] = $result['HEURE'];
		}
	
		return $tab;
	}
	
	/**
	 * Recuperation de la liste des medicaments
	 */
	public function listeDeTousLesMedicaments(){
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql ( $adapter );
		$select = $sql->select('consommable');
		$select->columns(array('ID_MATERIEL','INTITULE'));
		$stat = $sql->prepareStatementForSqlObject($select);
		$result = $stat->execute();
	
		return $result;
	}
	
	/**
	 * RECUPERER LA FORME DES MEDICAMENTS
	 */
	
	
	public function formesMedicaments(){
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns( array('*'));
		$select->from( array( 'forme' => 'forme_medicament' ));
	
		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ();
	
		return $result;
	}
	
	/**
	 * RECUPERER LES TYPES DE QUANTITE DES MEDICAMENTS
	 */
	
	public function typeQuantiteMedicaments(){
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns( array('*'));
		$select->from( array( 'typeQuantite' => 'quantite_medicament' ));
	
		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ();
	
		return $result;
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
	
	//liste des patients deja consultés par le medecin pour l'espace recherche
	public function listePatientsConsMedecin($idService){
		$today = new \DateTime();
		$date = $today->format('Y-m-d');
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->from ( array (
				'p' => 'patient'
		) );
		$select->columns(array () );
		$select->join(array('pers' => 'personne'), 'pers.ID_PERSONNE = p.ID_PERSONNE', array(
				'Nom' => 'NOM',
				'Prenom' => 'PRENOM',
				'Datenaissance' => 'DATE_NAISSANCE',
				'Sexe' => 'SEXE',
				'Adresse' => 'ADRESSE',
				'Nationalite' => 'NATIONALITE_ACTUELLE',
				'Id' => 'ID_PERSONNE'
		));
		$select->join(array('c' => 'consultation'), 'p.ID_PERSONNE = c.ID_PATIENT', array('Id_cons' => 'ID_CONS', 'Dateonly' => 'DATEONLY', 'Consprise' => 'CONSPRISE', 'date' => 'DATE'));
		//$select->join(array('cons_eff' => 'consultationchururgiegenerale'), 'cons_eff.ID_CONS = c.ID_CONS' , array('*'));
		$select->join( array('e1' => 'employe'), 'e1.id_personne = c.ID_MEDECIN' , array('*'));
		$select->join(array('s' => 'service'), 'c.ID_SERVICE = s.ID_SERVICE', array('Nomservice' => 'NOM'));
		$where = new Where();
		$where->equalTo('s.ID_SERVICE', $idService);
		$where->notEqualTo('DATEONLY', $date);
		$select->where($where);
		$select->order('c.DATE DESC');
		//$select->group('c.ID_PATIENT');
	
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		//Recuperation des donnees 
		$tableauDonnees = array();
		$tableauCles = array();
		foreach ($result as $resultat){
 			if(!in_array($resultat['Id'], $tableauCles)){
 				$tableauCles[] = $resultat['Id']; 
 				$tableauDonnees[] = $resultat;
 			}
		}
		
		return $tableauDonnees;
	
	}
	
 	public function addTraitementsInstrumentaux($traitement_instrumental){

 		
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->delete()
 		->from('traitement_instrumental')
 		->where(array('id_cons' => $traitement_instrumental['id_cons']));
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		$result = $stat->execute();
 		
		if($traitement_instrumental['endoscopie_interventionnelle'] || $traitement_instrumental['radiologie_interventionnelle'] ||
		 $traitement_instrumental['cardiologie_interventionnelle'] || $traitement_instrumental['autres_interventions']){
			$db = $this->tableGateway->getAdapter();
			$sql = new Sql($db);
			$sQuery = $sql->insert()
			->into('traitement_instrumental')
			->columns(array('id_cons', 'endoscopie_interventionnelle', 'radiologie_interventionnelle', 'cardiologie_interventionnelle', 'autres_interventions'))
			->values($traitement_instrumental);
			$stat = $sql->prepareStatementForSqlObject($sQuery);
			$stat->execute();
		}
 	}
 	
 	public function getTraitementsInstrumentaux($id_cons){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from('traitement_instrumental')
 		->where(array('id_cons' => $id_cons));
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		$result = $stat->execute()->current();
 		return $result;
 	}
 	
 	
 	public function fetchConsommable(){
 		$adapter = $this->tableGateway->getAdapter();
 		$sql = new Sql ( $adapter );
 		$select = $sql->select('consommable');
 		$select->columns(array('ID_MATERIEL','INTITULE'));
 		$stat = $sql->prepareStatementForSqlObject($select);
 		$result = $stat->execute();
 		foreach ($result as $data) {
 			$options[$data['ID_MATERIEL']] = $data['INTITULE'];
 		}
 		return $options;
 	}
 	
 	
 	//GESTION DES FICHIER MP3
 	//GESTION DES FICHIER MP3
 	//GESTION DES FICHIER MP3
 	public function insererMp3($titre , $nom, $id_cons, $type){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->insert()
 		->into('fichier_mp3')
 		->columns(array('titre', 'nom', 'id_cons', 'type'))
 		->values(array('titre' => $titre , 'nom' => $nom, 'id_cons'=>$id_cons, 'type'=>$type));
 	
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		return $stat->execute();
 	}
 	
 	public function getMp3($id_cons, $type){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from(array('f' => 'fichier_mp3'))->columns(array('*'))
 		->where(array('id_cons' => $id_cons, 'type' => $type))
 		->order('id DESC');
 	
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		$result = $stat->execute();
 		return $result;
 	}
 	
 	public function supprimerMp3($idLigne, $id_cons, $type){
 		$liste = $this->getMp3($id_cons, $type);
 	
 		$i=1;
 		foreach ($liste as $list){
 			if($i == $idLigne){
 				unlink('C:\wamp\www\simens\public\audios\\'.$list['nom']);
 	
 				$db = $this->tableGateway->getAdapter();
 				$sql = new Sql($db);
 				$sQuery = $sql->delete()
 				->from('fichier_mp3')
 				->where(array('id' => $list['id']));
 	
 				$stat = $sql->prepareStatementForSqlObject($sQuery);
 				$stat->execute();
 	
 				return true;
 			}
 			$i++;
 		}
 		return false;
 	}
 	
 	
 	//GESTION DES FICHIERS VIDEOS
 	//GESTION DES FICHIERS VIDEOS
 	//GESTION DES FICHIERS VIDEOS
 	public function insererVideo($titre , $nom, $format, $id_cons){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->insert()
 		->into('fichier_video')
 		->columns(array('titre', 'nom', 'format', 'id_cons'))
 		->values(array('titre' => $titre , 'nom' => $nom, 'format' => $format, 'id_cons'=>$id_cons));
 	
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		return $stat->execute();
 	}
 	
 	public function getVideos($id_cons){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from(array('f' => 'fichier_video'))->columns(array('*'))
 		->where(array('id_cons' => $id_cons))
 		->order('id DESC');
 	
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		$result = $stat->execute();
 		return $result;
 	}
 	
 	public function getVideoWithId($id){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from(array('f' => 'fichier_video'))->columns(array('*'))
 		->where(array('id' => $id));
 	
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		$result = $stat->execute()->current();
 		return $result;
 	}
 	
 	public function supprimerVideo($id){

 		$laVideo = $this->getVideoWithId($id);
 		$result = unlink('C:\wamp\www\simens\public\videos\\'.$laVideo['nom']);
 		
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->delete()->from('fichier_video')->where(array('id' => $id));

 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		$stat->execute();
 		
 		return $result;
 	}
 	
 	//COMPTE RENDU OPERATOIRE
 	//COMPTE RENDU OPERATOIRE
 	public function deleteCompteRenduOperatoire($id_cons, $type){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->delete()
 		->from('compte_rendu_operatoire')
 		->where(array('id_cons' => $id_cons, 'type' => $type));
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		$result = $stat->execute();
 	}
 	
 	public function addCompteRenduOperatoire($note, $type, $id_cons){
 		$this->deleteCompteRenduOperatoire($id_cons, $type);
 		if($note) {
 			$db = $this->tableGateway->getAdapter();
 			$sql = new Sql($db);
 			$sQuery = $sql->insert()
 			->into('compte_rendu_operatoire')
 			->values(array('note' => $note , 'type' => $type, 'id_cons'=>$id_cons));
 				
 			$stat = $sql->prepareStatementForSqlObject($sQuery);
 			return $stat->execute();
 		}
 	}
 	
 	public function getCompteRenduOperatoire($type, $id_cons){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from(array('c' => 'compte_rendu_operatoire'))->columns(array('*'))
 		->where(array('id_cons' => $id_cons, 'type' => $type));
 		
 		$stat = $sql->prepareStatementForSqlObject($sQuery);
 		$result = $stat->execute()->current();
 		return $result;
 	}
 	
 	
 	
 	//GESTION DES EXAMENS DU JOUR LORS D'UNE HOSPITALISATION
 	//GESTION DES EXAMENS DU JOUR LORS D'UNE HOSPITALISATION
 	public function addConsultationExamenDuJour($codeExamen, $values , $IdDuService , $idMedecin){
 		$this->tableGateway->getAdapter()->getDriver()->getConnection()->beginTransaction();
 		$date = new \DateTime();
 		$aujourdhui = $date->format('Y-m-d H:i:s');
 		$dateonly = $date->format('Y-m-d');
 		
 		try {
 			$dataconsultation = array(
					'ID_CONS'=> $codeExamen,
 					'ID_MEDECIN'=> $idMedecin,
 					'ID_PATIENT'=> $values->id_personne,
 					'DATE'=> $aujourdhui,
 					'POIDS' => $values->poids,
 					'TAILLE' => $values->taille,
  					'TEMPERATURE' => $values->temperature,
  					'PRESSION_ARTERIELLE' => $values->pressionarterielle,
 					'POULS' => $values->pouls,
 					'FREQUENCE_RESPIRATOIRE' => $values->frequence_respiratoire,
 					'GLYCEMIE_CAPILLAIRE' => $values->glycemie_capillaire,
  					'DATEONLY' => $dateonly,
  					'ID_SERVICE' => $IdDuService
 			);
 			
 			$this->tableGateway->insert($dataconsultation);
 	
 			$this->tableGateway->getAdapter()->getDriver()->getConnection()->commit();
 		} catch (\Exception $e) {
 			$this->tableGateway->getAdapter()->getDriver()->getConnection()->rollback();
 		}
 		
 		
 		
 	}
 	
 	public function addExamenDuJour($id_cons, $id_hosp){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->insert()
 		->into('examen_du_jour')
 		->values(array('ID_CONS' => $id_cons, 'ID_HOSP' => $id_hosp));
 		$requete = $sql->prepareStatementForSqlObject($sQuery);
 		$requete->execute();
 	}
 	
 	public function getExamenDuJour($id_hosp){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from(array('e' => 'examen_du_jour'))
 		->join(array('c' => 'consultation'), 'c.ID_CONS = e.ID_CONS' , array('*'))
 		->join(array('p' => 'personne'), 'p.ID_PERSONNE = c.ID_MEDECIN' , array('NomMedecin' => 'NOM', 'PrenomMedecin' => 'PRENOM'))
 		->where(array('ID_HOSP' => $id_hosp))
 		->order('DATE DESC');
 		$requete = $sql->prepareStatementForSqlObject($sQuery);
 		return $requete->execute();
 	}
 	
 	public function supprimerExamenDuJour($id_examen_jour){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from(array('e' => 'examen_du_jour'))
 		->where(array('ID_EXAMEN_JOUR' => $id_examen_jour));
 		
 		$requete = $sql->prepareStatementForSqlObject($sQuery);
 		$result = $requete->execute()->current();
 		
 		$db2 = $this->tableGateway->getAdapter();
 		$sql2 = new Sql($db2);
 		$sQuery2 = $sql2->delete()
 		->from('consultation')
 		->where(array('ID_CONS' => $result['ID_CONS']));
 			
 		$requete2 = $sql2->prepareStatementForSqlObject($sQuery2);
 		$requete2->execute();
 		
 		return $result['ID_HOSP'];
 	}
 	
 	public function getExamenDuJourParIdExamenJour($id_examen_jour){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from(array('e' => 'examen_du_jour'))
 		->join(array('c' => 'consultation'), 'c.ID_CONS = e.ID_CONS' , array('*'))
 		->join(array('p' => 'personne'), 'p.ID_PERSONNE = c.ID_MEDECIN' , array('NomMedecin' => 'NOM', 'PrenomMedecin' => 'PRENOM'))
 		->where(array('ID_EXAMEN_JOUR' => $id_examen_jour));
 		$requete = $sql->prepareStatementForSqlObject($sQuery);
 		return $requete->execute()->current();
 	}
 	
 	public function getConsultationExamenJour($id_cons){
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		$sQuery = $sql->select()
 		->from(array('c' => 'consultation'))
 		->where(array('ID_CONS' => $id_cons));
 		$requete = $sql->prepareStatementForSqlObject($sQuery);
 		return $requete->execute()->current();
 	}
 	
 	public function getTarifDeLacte($idActe){
 		$adapter = $this->tableGateway->getAdapter();
 		$sql = new Sql($adapter);
 		$select = $sql->select();
 		$select->from(array('s'=>'actes'));
 		$select->columns(array('*'));
 		$select->where(array('id' => $idActe));
 		$stat = $sql->prepareStatementForSqlObject($select);
 		$result = $stat->execute()->current();
 		return $result;
 	}
 	
 	//Recupere les antecedents m�dicaux
 	//Recupere les antecedents m�dicaux
 	public function getAntecedentMedicauxParLibelle($libelle){
 		$adapter = $this->tableGateway->getAdapter();
 		$sql = new Sql($adapter);
 		$select = $sql->select();
 		$select->from(array('am'=>'ant_medicaux'));
 		$select->columns(array('*'));
 		$select->where(array('libelle' => $libelle));
 		return $sql->prepareStatementForSqlObject($select)->execute()->current();
 	}
 	
 	//Ajout des ant�c�dents m�dicaux
 	//Ajout des ant�c�dents m�dicaux
 	public function addAntecedentMedicaux($data,$id_medecin,$id_cons){
 		$date = (new \DateTime())->format('Y-m-d H:i:s');
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 		
 			for($i = 0; $i<$data->nbCheckboxAM; $i++){
 				$champ = "champTitreLabel_".$i;
 				$libelle =  $data->$champ;
 				
 				if($libelle){
 				$requeteUpdate = $sql->update('ant_medicaux')
 				->set(array('libelle' => $libelle))
 				->where(array('ID_CONS'=>$id_cons));
 				$result_exe = $sql->prepareStatementForSqlObject($requeteUpdate)->execute();
 					
 				
 				if(!$result_exe){
 				if(!$this->getAntecedentMedicauxParLibelle($libelle)){
 					$sQuery = $sql->insert()
 					->into('ant_medicaux')
 					->values(array('libelle' => $libelle, 'date_enregistrement' => $date, 'id_medecin' => $id_medecin, 'id_cons'=>$id_cons));
 					$sql->prepareStatementForSqlObject($sQuery)->execute();
 				}
 				}
 			}
 		}
 		//var_dump($data,$id_medecin);exit();
 		
 		
 	}
 	
 	
 	//Recupere l'antecedent m�dical de la personne
 	//Recupere l'antecedent m�dical de la personne
 	public function getAntecedentMedicauxPersonneParId($id, $id_patient){
 		$adapter = $this->tableGateway->getAdapter();
 		$sql = new Sql($adapter);
 		$select = $sql->select();
 		$select->from(array('amp'=>'ant_medicaux_personne'));
 		$select->columns(array('*'));
 		$select->where(array('id_ant_medicaux' => $id, 'id_patient' => $id_patient));
 		return $sql->prepareStatementForSqlObject($select)->execute()->current();
 	}
 	
 	
 	//Recuperer les antecedents m�dicaux du patient
 	//Recuperer les antecedents m�dicaux du patient
 	public function getAntecedentsMedicauxPatient($id_patient){
 		$adapter = $this->tableGateway->getAdapter();
 		$sql = new Sql($adapter);
 		$select = $sql->select();
 		$select->columns(array('*'));
 		$select->from(array('amp'=>'ant_medicaux_personne'));
 		$select->join( array('am' => 'ant_medicaux'), 'am.id = amp.id_ant_medicaux' , array('*'));
 		$select->where(array('amp.id_patient' => $id_patient));
 		$result = $sql->prepareStatementForSqlObject($select)->execute();
 		
 		$tableau = array();
 		
 		foreach ($result as $resul){
 			$tableau[] = $resul['libelle'];
 		}
 		
 		return $tableau;
 	}
 	
 	
 	//Ajout des ant�c�dents m�dicaux de la personne
 	//Ajout des ant�c�dents m�dicaux de la personne
 	public function addAntecedentMedicauxPersonne($data,$id_medecin,$idCons){
 		$date = (new \DateTime())->format('Y-m-d H:i:s');
 		$db = $this->tableGateway->getAdapter();
 		$sql = new Sql($db);
 			
 		//Tableau des nouveaux antecedents ajouter array('ID_PERSONNE' => $id_personne)
 		$tableau = array();
 		$re = $sql->delete(array('*'))->from('ant_medicaux_personne')->where(array('id_patient'=>$data->id_patient));
 		
 		$sql->prepareStatementForSqlObject($re)->execute();
 		
 		
 		for($i = 0; $i<$data->nbCheckboxAM; $i++){
 			$champ = "champTitreLabel_".$i;
 			$libelle =  $data->$champ;
 			//Ajout des nouveaux libelles dans le tableau
 			$tableau[] = $libelle;
 			
//  			$reqUpdate = $sql->update('ant_medicaux_personne')
//  							->set(array('id_patient' => $data->id_patient,
//  									 'date_enregistrement' => $date,
//  									 'id_medecin' => $id_medecin))
//  							->where('ID_CONS',$idCons);
//  					$result = $sql->prepareStatementForSqlObject($reqUpdate)->execute();
//  					var_dump($data);exit();
 			//if(!$result){
 				$antecedent = $this->getAntecedentMedicauxParLibelle($libelle);
 				if($antecedent){
 					if(!$this->getAntecedentMedicauxPersonneParId($antecedent['id'], $data->id_patient)){
 						$sQuery = $sql->insert()
 						->into('ant_medicaux_personne')
 						->values(array('id_ant_medicaux' => $antecedent['id'], 'id_patient' => $data->id_patient, 'date_enregistrement' => $date, 'id_medecin' => $id_medecin));
 						$sql->prepareStatementForSqlObject($sQuery)->execute();
 					}
 				//}
 			}
 			if(!$this->getAntecedentMedicauxParLibelle($libelle)){
 				$sQuery = $sql->insert()
 				->into('ant_medicaux')
 				->values(array('libelle' => $libelle, 'date_enregistrement' => $date, 'id_medecin' => $id_medecin, 'id_cons'=>$idCons));
 				$id_ant_med = $sql->prepareStatementForSqlObject($sQuery)->execute()->getGeneratedValue();
 				
 				$sQuery1 = $sql->insert()
 				->into('ant_medicaux_personne')
 				->values(array('id_ant_medicaux' => $id_ant_med, 'id_patient' => $data->id_patient, 'date_enregistrement' => $date, 'id_medecin' => $id_medecin));
 				$sql->prepareStatementForSqlObject($sQuery1)->execute();
 			}
 			
 			
 		}
 		
 		//Tableau de tous les antecedents medicaux du patient avant la mise � jour
 		$tableau2 = $this->getAntecedentsMedicauxPatient($data->id_patient);
 		
 		//var_dump($data->nbCheckboxAM); exit();
 		//Suppression des antecedents non s�lectionn�s
 		for($i=0; $i<count($tableau2); $i++){
 			if(!in_array($tableau2[$i], $tableau)){
 				$id_ant_medicaux = $this->getAntecedentMedicauxParLibelle($tableau2[$i])['id'];
 				//var_dump($id_ant_medicaux); exit();
 				$sQuery = $sql->delete()
 				->from('ant_medicaux_personne')
 				->where(array('id_ant_medicaux' => $id_ant_medicaux, 'id_patient' => $data->id_patient));
 				$sql->prepareStatementForSqlObject($sQuery)->execute();
 			}
 		}
 			
 	}
 	
 	
 	
 	//Ajout des ant�c�dents familiaux de la personne
 	//Ajout des ant�c�dents familiaux de la personne
 	public function addAntecedentFamiliauxPersonne($data,$id_medecin){
 	    $date = (new \DateTime())->format('Y-m-d H:i:s');
 	    $db = $this->tableGateway->getAdapter();
 	    $sql = new Sql($db);
 	    //var_dump($data);exit();
 	    //Tableau des nouveaux antecedents ajouter
 	    $tableau = array();
 	    
 	    for($i = 0; $i<$data->nbCheckboxAF; $i++){
 	        $champ = "champTitreLabel_".$i;
 	        $libelle =  $data->$champ;
 	        
 	        //Ajout des nouveaux libelles dans le tableau
 	        $tableau[] = $libelle;
 	        
 	        $antecedent = $this->getAntecedentMedicauxParLibelle($libelle);
 	        //var_dump($antecedent);exit();
 	        if($antecedent){
 	            if(!$this->getAntecedentMedicauxPersonneParId($antecedent['id'], $data->id_patient)){
 	                $sQuery = $sql->insert()
 	                ->into('ant_medicaux_personne')
 	                ->values(array('id_ant_medicaux' => $antecedent['id'], 'id_patient' => $data->id_patient, 'date_enregistrement' => $date, 'id_medecin' => $id_medecin));
 	                $sql->prepareStatementForSqlObject($sQuery)->execute();
 	            }
 	        }
 	    }
 	    
 	    //Tableau de tous les antecedents medicaux du patient avant la mise � jour
 	    $tableau2 = $this->getAntecedentsMedicauxPatient($data->id_patient);
 	    
 	    //var_dump($data->nbCheckboxAM); exit();
 	    //Suppression des antecedents non s�lectionn�s
 	    for($i=0; $i<count($tableau2); $i++){
 	        if(!in_array($tableau2[$i], $tableau)){
 	            $id_ant_medicaux = $this->getAntecedentMedicauxParLibelle($tableau2[$i])['id'];
 	            $sQuery = $sql->delete()
 	            ->from('ant_medicaux_personne')
 	            ->where(array('id_ant_medicaux' => $id_ant_medicaux, 'id_patient' => $data->id_patient));
 	            $sql->prepareStatementForSqlObject($sQuery)->execute();
 	        }
 	    }
 	    
 	}
 	
 	
 	
 	
 	//Recupere les antecedents m�dicaux de la personne
 	//Recupere les antecedents m�dicaux de la personne
 	public function getAntecedentMedicauxPersonneParIdPatient($id_patient){
 		$adapter = $this->tableGateway->getAdapter();
 		$sql = new Sql($adapter);
 		$select = $sql->select();
 		$select->columns(array('*'));
 		$select->from(array('amp'=>'ant_medicaux_personne'));
 		$select->join( array('am' => 'ant_medicaux'), 'am.id = amp.id_ant_medicaux' , array('*'));
 		$select->where(array('amp.id_patient' => $id_patient));
 		return $sql->prepareStatementForSqlObject($select)->execute();
 	}
 	
 	
 	//Recupere les antecedents m�dicaux 
 	//Recupere les antecedents m�dicaux 
 	public function getAntecedentsMedicaux(){
 		$adapter = $this->tableGateway->getAdapter();
 		$sql = new Sql($adapter);
 		$select = $sql->select();
 		$select->columns(array('*'));
 		$select->from(array('am'=>'ant_medicaux'));
 		return $sql->prepareStatementForSqlObject($select)->execute();
 	}
 	
 	
 	//Recupere la liste des actes
 	//Recupere la liste des actes
 	public function getListeDesActes(){
 		$adapter = $this->tableGateway->getAdapter();
 		$sql = new Sql($adapter);
 		$select = $sql->select();
 		$select->columns(array('*'));
 		$select->from(array('a'=>'actes'));
 		return $sql->prepareStatementForSqlObject($select)->execute();
 	}
 	
 	/**
 	 * Recuperer la liste des examens Fonctionnels
 	 */
 	public function getDemandeDesExamensFonctionnels(){
 	    $adapter = $this->tableGateway->getAdapter();
 	    $sql = new Sql($adapter);
 	    $select = $sql->select();
 	    $select->columns(array('*'));
 	    $select->from(array('e'=>'examens'));
 	    $select->where(array('idType' => 3));
 	    $select->order('idExamen ASC');
 	    $stat = $sql->prepareStatementForSqlObject($select);
 	    $result = $stat->execute();
 	    
 	    return $result;
 	}
 	
}