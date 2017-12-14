<?php
namespace Chururgie\Model;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;


class ClasseOrganePathologieTable{
    
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway=$tableGateway;
    }
}