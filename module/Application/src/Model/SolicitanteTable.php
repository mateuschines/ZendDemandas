<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class SolicitanteTable
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
    
    public function persist(Solicitante $solicitante)
    {
        $set = $solicitante->toArray();
        $result = $this->tableGateway->select(['cpf' => $set['cpf']]);
        if ($result->count() == 0){
            $this->tableGateway->insert($set);
            return true;
        }
        return false;
    }
    
    public function getByAssunto($assunto)
    {
        return $this->tableGateway->select( ['assunto' => $assunto]);
    }
    
    
    
    
    
    
}
