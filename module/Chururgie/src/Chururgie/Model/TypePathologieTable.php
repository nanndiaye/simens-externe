<?php
namespace Chururgie\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;


class TypePathologieTable{
    
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway=$tableGateway;
    }
}