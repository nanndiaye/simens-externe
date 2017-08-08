<?php
namespace Chururgie\Model

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;


class OrganeTable{
    
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway=$tableGateway;
    }
    
    public function fetchAll() {
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}
        
//          public function listeDeTousLesOrganes(){
//              var_dump('es');             exit();
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
// 	}

	public function getOrganeByName($lesorganes){
	    $adapter = $this->tableGateway->getAdapter ();
	    $sql = new Sql ( $adapter );
	    $select = $sql->select ();
	    $select->columns( array('*'));
	    $select->from( array( 'c' => 'organe' ));
	    $select->where ( array( 'c.LESORGANES' => $intitule));
	    
	    $stat = $sql->prepareStatementForSqlObject ( $select );
	    $result = $stat->execute ()->current();
	    
	    return $result;
	}
}