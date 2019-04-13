<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Interop\Container\ContainerInterface;
use Application\Model\Solicitante;

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
        
        $assunto = ($_POST['assunto'] ?? null);
        $detalhes = ($_POST['detalhes'] ?? null);
        $_SESSION['dados'] = [
            'solicitante' => $solicitante,
            'assunto' => $assunto,
            'detalhes' => $detalhes
        ];
            if (empty($solicitante->cpf) || empty($assunto) || empty(detalhes)) {
                $_SESSION['mensagem'] = 'Preencha os campos!';
                return $this->redirect()->toRoute('application');//redireciona pra tal rota escrita
            }

            $solicitanteTable = $this->container->get('SolicitanteTable');

            $solicitanteTable->persist($solicitante);
            
            $select = new Select('solicitante');
            $select->columns(['cpf'])
            ->where(['cpf' => $cpf]);
            $sql = $select->getSqlString($adapter->getPlatform());
            $statement = $adapter->query($sql);
            $result = $statement->execute();
            if ($result->count() == 0) {
                $insert = new Insert('solicitante');
                $insert->columns(['cpf','nome','cep','municipio','uf','email','ddd','telefone'])
                ->values([$cpf,$nome,$cep,$municipio,$uf,$email,$ddd,$telefone]);
                $sql = $insert->getSqlString($adapter->getPlatform());
                
                $statement = $adapter->query($sql);
                $result = $statement->execute();
            }

                $select = new Select('assunto');
                $select->columns(['assunto','detalhes'])
                ->where(['assunto' => $assunto]);
                $sql = $select->getSqlString($adapter->getPlatform());
                $statement = $adapter->query($sql);
                $result = $statement->execute();
                if ($result->count() > 0) {
                    $_SESSION['dados']['detalhes_gravados'] = $result->current()['detalhes'];
                    return $this->redirect()->toRoute('application');

                } else {
                    $insert = new Insert('assunto');
                    $insert->columns(['assunto','detalhes'])
                    ->values([$assunto,$detalhes]);
                    $sql = $insert->getSqlString($adapter->getPlatform());
                    $statement = $adapter->query($sql);
                    $result = $statement->execute();
                    
                    $expression = new Expression('max(codigo)');
                    $select = new Select('assunto');
                    $select->columns(['codigoAssunto' => $expression]);
                    $sql = $select->getSqlString($adapter->getPlatform());
                    $statement = $adapter->query($sql);
                    $result = $statement->execute();
                    $codigoAssunto = $result->current()['codigoAssunto'];
                }
                $adapter->getDriver()->getConnection()->disconnect();
                $insert = new Insert('demanda');
                $insert->columns(['codigo_solicitante','codigo_assunto'])
                ->values([$cpf,$codigoAssunto]);
                $sql = $insert->getSqlString($adapter->getPlatform());
                $statement = $adapter->query($sql);
                $result = $statement->execute();
                $_SESSION['dados'] = [];
                
                return new ViewModel();

    }
}
