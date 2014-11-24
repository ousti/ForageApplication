<?php


require_once 'BaseController.php';

class FormationCoutDirectController extends BaseController {

   public function init()
    {
        $this->params = $this->getRequest()->getParams();
        $formationObject = new Application_Model_DbTable_Formation();
        $this->view->formation = $formationObject->find($this->params['id_formation'])->current();
   }

    /**** Liste des couts direct *******/
    public function indexAction() {
        $model = new Application_Model_FormationCoutDirectMapper();
        $this->view->couts_direct = $model->getList('s.id_formation = '.$this->params['id_formation']);
        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
   }

    /**** enregistrer formation cout direct *******/
    public function addAction() {
       $bObj =  new Application_Model_BudgetMapper(); 
       $sObj = new Application_Model_DbTable_Session();
       $sessions = $sObj->fetchAll('id_formation = '.$this->params['id_formation']);
       $params = array('budgets' =>$bObj->getEntiteBudgetEncours(), 'id'=>null,'sessions'=>$sessions,
                       'id_formation' =>$this->params['id_formation']);
       $form = new Application_Form_FormationCoutDirectForm($params);
       $this->view->form = $form;
    }
    
    public function editAction() {
       $bObj =  new Application_Model_BudgetMapper(); 
       $sObj = new Application_Model_DbTable_Session();
       $sessions = $sObj->fetchAll('id_formation = '.$this->params['id_formation']);
       $params = array('budgets' =>$bObj->getEntiteBudgetEncours(), 'id'=>$this->params['id'],'sessions'=>$sessions,
                       'id_formation' =>$this->params['id_formation']);
       $form = new Application_Form_FormationCoutDirectForm($params);
       $fcdObj = new Application_Model_DbTable_FormationCoutDirect();
       $formationCoutDirect = $fcdObj->find((int)$this->params['id'])->current()->toArray();
       $form->populate($formationCoutDirect);
       $this->view->form = $form;
    }
    
    
    public function saveFormAction() {
       $bObj =  new Application_Model_BudgetMapper();
       $sObj = new Application_Model_DbTable_Session();
       $sessions = $sObj->fetchAll('id_formation = '.$this->params['id_formation']);
       $params = array('budgets' =>$bObj->getEntiteBudgetEncours(), 'id'=>$this->params['id'], 'sessions'=>$sessions,
                       'id_formation' =>$this->params['id_formation']);
       # Form
       $form = new Application_Form_FormationCoutDirectForm($params);
       $form->populate($params); 
       $this->view->form = $form;
       if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_FormationCoutDirect($form->getValues()); 
                $mapper = new Application_Model_FormationCoutDirectMapper();
                $newId = $mapper->save($values);
                if($newId=='') $newId = $this->params['id'];
                $this->_redirect("/formation-cout-direct/index/id_formation/" . $this->params['id_formation']);
                return;
            }
        }
        $this->renderScript("formation-cout-direct/add.phtml");
    }

    
   public function deleteAction() {
       $params = $this->getRequest()->getParams();
       if(isset($params['id'])) {
           $formation = new Application_Model_DbTable_Formation();
           $f = $formation->find($params['id'])->current();
           $f->delete();
           $this->getHelper('FlashMessenger')->addMessage('Formation supprimée avec succès!');
           $this->redirect("/index/index");
       }
       $this->renderScript("index/index.phtml");
    }
    
   
    
   
    
    
    
}

