<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Interop\Container\ContainerInterface;
use Application\Model\Solicitante;
use Application\Model\Assunto;
use Application\Model\Demanda;

class IndexController extends AbstractActionController
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
         $this->container = $container;
    }
    
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function processarAction()
    {

        $solicitante = new Solicitante($_POST);
        
        $assunto = new Assunto($_POST);
        
        $_SESSION['dados'] = [
            'solicitante' => $solicitante,
            'assunto' => $assunto
        ];
        
        if (empty($solicitante->cpf) || empty($assunto->assunto) || empty($assunto->detalhes)) {
                $_SESSION['mensagem'] = 'Preencha os campos!';
                return $this->redirect()->toRoute('application');//redireciona pra tal rota escrita
            }

            $solicitanteTable = $this->container->get('SolicitanteTable');

            if ($solicitanteTable->persist($solicitante)) {
                
                $assuntoTable = $this->container->get('AssuntoTable');
                
                $assuntoTable->persist($assunto);
                
                $codigoAssunto = $assuntoTable->getMaxCodigo();
                
                $demandas = new Demanda($solicitante->cpf, $codigoAssunto);
                
                $demandasTable = $this->container->get('DemandaTable');
                
                $demandasTable->persist($demandas);
                
                $_SESSION['dados'] = [];
                
                return new ViewModel();
            }
            
   

            $_SESSION['mensagem'] = 'Campos Iguais';
            return $this->redirect()->toRoute('application');
                

    }
}
