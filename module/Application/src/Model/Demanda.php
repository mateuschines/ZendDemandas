<?php
namespace Application\Model;

class Demanda
{
    public $codigo_solicitante;
    public $codigo_assunto;
    
    public function __construct($codigo_solicitante, $codigo_assunto)
    {
        
        $this->codigo_solicitante = $codigo_solicitante;
        $this->codigo_assunto = $codigo_assunto;
        
    }
    
    public function toArray()
    {
        return get_object_vars($this);//ira criar um array com todos os dados
    }
}