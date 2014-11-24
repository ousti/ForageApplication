<?php


require_once 'BaseController.php';

class SessionCoutIndirectController extends BaseController {

   public function init()
    {
        $this->params = $this->getRequest()->getParams();
        $formationObject = new Application_Model_DbTable_Formation();
        $this->view->formation = $formationObject->find($this->params['id_formation'])->current();
    }

   
    public function indexAction() {
        # Liste des sessions d'une formation
        $this->view->sessions = $this->view->formation->findApplication_Model_DbTable_Session(); 
        # Couts de ces sessions
        $coutIndirectMapper = new Application_Model_SessionCoutIndirectMapper();
        $cond = " id_formation = " . (int)$this->params['id_formation'];
        # appel requete
        $coutsIndirects = $coutIndirectMapper->fetchAll($cond)->toArray(); 
        $this->view->couts_indirect = array();
        foreach($coutsIndirects as $c) {
            $this->view->couts_indirect[$c['id_session']][] = $c;
        } 
        $typesCoutTable = new Application_Model_DbTable_TypesCout();
        $typesCout  = $typesCoutTable->fetchAll()->toArray();
        $this->view->typesCout = array();
        foreach($typesCout as $t) {
            $k = $t['id'];
            $this->view->typesCout[$k] = array('id'=>$k, 'libelle'=>$t['libelle'], 'type'=>$t['type']);           
        }
        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
    }
    
    
    /**** enregistrer cout indirect d'une session *******/
    public function addAction() {
       $this->view->idFormation = $this->params['id_formation'];
       # Liste deroulante
       $obj =  new Application_Model_TypesCoutMapper();
       $res = $obj->getNotLinkedToSession((int)$this->params['id_session']);
       $bObj =  new Application_Model_BudgetMapper(); 
       $params = array('action'=>'add','typesCout' => $res, 'id_session' =>$this->params['id_session'],
                        'budgets' =>$bObj->getEntiteBudgetEncours(), );
       $form = new Application_Form_SessionCoutIndirectForm($params);
       $this->view->form = $form;
    }
    
     /** modifier cout de formation ***/
    public function editAction() {
        $this->view->idFormation = (int)$this->params['id_formation'];
        # Liste deroulante
        $objMapper =  new Application_Model_TypesCoutMapper();
        $res = $objMapper->find($this->params['id_type_cout']);
        $bObj =  new Application_Model_BudgetMapper(); 
        $obj = new Application_Model_DbTable_SessionCoutIndirect();
        $sessionCoutIndirect = $obj->find((int)$this->params['id_session'],$this->params['id_type_cout'])->current()->toArray();
        $form = new Application_Form_SessionCoutIndirectForm(array('action'=>'edit','typesCout' => array($res), 'id_formation' =>$this->params['id_formation'],
             'id_session' =>$this->params['id_session'],'budgets' =>$bObj->getEntiteBudgetEncours(), ));
        $form->populate($sessionCoutIndirect);
        $this->view->form = $form;
    }
    
     public function saveFormAction() {
       $this->view->idFormation = (int)$this->params['id_formation'];
       # Liste deroulante
       $obj =  new Application_Model_TypesCoutMapper();
       $bObj =  new Application_Model_BudgetMapper(); 
       # Form add or Form edit
       if($this->params['action'] == 'add') {
        $res = $obj->getNotLinkedToSession((int)$this->params['id_formation']);
       }
       else {
        $res = array($obj->find($this->params['id_type_cout']));
        }
       $params = array('action'=>$this->params['action'], 'typesCout' => $res, 'id_formation' =>$this->params['id_formation'],'budgets' =>$bObj->getEntiteBudgetEncours());
       $form = new Application_Form_SessionCoutIndirectForm($params);
       $form->populate($this->params);
       $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_SessionCoutIndirect($form->getValues());
                $mapper = new Application_Model_SessionCoutIndirectMapper();
                $newId = $mapper->save($values);
                $this->getHelper('FlashMessenger')->addMessage('Cout Indirect de session enregistrée avec succès!');
                $this->_redirect("/session-cout-indirect/index/id_formation/".$this->params['id_formation']);
                return;
            }
        }
        $this->renderScript("session-cout-indirect/add.phtml");
    }

    
}

