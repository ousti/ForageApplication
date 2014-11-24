<?php


require_once 'BaseController.php';

class BonCommandeController extends BaseController {

   public function init()
    {
        $this->params = $this->getRequest()->getParams();
        $formationObject = new Application_Model_DbTable_Formation();
        $this->view->formation = $formationObject->find($this->params['id_formation'])->current();
        
        
    }

    /**** Liste des bons de commande *******/
    public function indexAction() {
        $model = new Application_Model_BonCommandeMapper();
        $this->view->bons = $model->getList('bc.id_formation = '.$this->params['id_formation']);
        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
   }

    /**** enregistrer Bon de commande *******/
    public function addAction() {
       $bObj =  new Application_Model_BudgetMapper(); 
       $params = array('budgets' =>$bObj->getEntiteBudgetEncours(), 'id'=>null,
                       'id_formation' =>$this->params['id_formation']);
       $form = new Application_Form_BonCommandeForm($params);
       $this->view->form = $form;
    }
    
    public function editAction() {
       $bObj =  new Application_Model_BudgetMapper(); 
       $params = array('budgets' =>$bObj->getEntiteBudgetEncours(), 'id'=>null);
       $form = new Application_Form_BonCommandeForm($params);
       
        $formation = $obj->find((int)$params['id'])->current()->toArray();
        # Liste deroulante
       $dObj =  new Application_Model_DbTable_Direction(); 
       $nObj = new Application_Model_DbTable_NaturesFormation();
       $tObj = new Application_Model_DbTable_TypesFormation();
       $doObj = new Application_Model_DbTable_DomainesFormation();
       $pyObj = new Application_Model_DbTable_Pays();
       $selects = array('directions' =>$dObj->fetchAll(),'budgets'=>$this->budgets,
                       'natures' => $nObj->fetchAll(), 'types' => $tObj->fetchAll(),
                       'pays' => $pyObj->fetchAll(), 'id'=>$params['id'],
                       'domaines'=>$doObj->fetchAll()
                 );
        $form = new Application_Form_FormationForm($selects);
        $form->populate($formation);
        $this->view->form = $form;
    }
    
    
    public function saveFormAction() {
       $bObj =  new Application_Model_BudgetMapper(); 
       $params = array('budgets' =>$bObj->getEntiteBudgetEncours(), 'id'=>$this->params['id'],
                       'id_formation' =>$this->params['id_formation']);
       # Form
       $form = new Application_Form_BonCommandeForm($params);
       $form->populate($params); 
       $this->view->form = $form;
       if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_BonCommande($form->getValues()); 
                $mapper = new Application_Model_BonCommandeMapper();
                $newId = $mapper->save($values);
                if($newId=='') $newId = $parameters['id'];
                $this->_redirect("/bon-commande/index/id_formation/" . $this->params['id_formation']);
                return;
            }
        }
        $this->renderScript("bon-commande/add.phtml");
    }

    
    
    public function detailAction() {
       $params = $this->getRequest()->getParams(); 
       $objFormation = new Application_Model_FormationMapper();
       $formation = $objFormation->find((int)$params['id']);
       $this->view->formation = $formation;
       # formation externe 
       if($formation->getType_formateur() == 1) {
           // get id_organisme et info organisme 
           $fe = new Application_Model_DbTable_FormationExterne();
           $f = $fe->find((int)$params['id'])->current();
           $o = new Application_Model_DbTable_Organisme();
           $org = $o->find((int)$f['id_organisme'])->current();
           $this->view->formationExterne = array('organisme' => $org['organisme'], 
                                                 'formateur'=>$f['formateur'],
                                                 'description_formateur'=>$f['description_formateur']
                                           );
       }
       # formation externe
       else {
           
       }
       //Zend_Debug::dump($formation, $label = null, $echo = true);
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

