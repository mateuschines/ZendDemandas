<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class DemandaTable
{
    /**
     * @var TableGatewayInterface
     */
    private $tableGateway;
    
    //fazer ligacao de uma class e uma tabela
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function persist(Demanda $demanda)
    {
        $set = $demanda->toArray();
        $this->tableGateway->insert($set);
    }
}
