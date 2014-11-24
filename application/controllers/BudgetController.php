<?php

require_once 'BaseController.php';

class BudgetController extends BaseController {

    
    //protected $exercice;
    private $params;
    

    public function init() {
        parent::init();
        $this->params = $this->getRequest()->getParams();  
    }
    
    
    public function indexAction() {
        $this->view->exerciceYear = $this->params['exercice'];
        $model = new Application_Model_BudgetMapper();
        $this->view->budgets = $model->getEntiteBudgetEncours(NULL,$this->params['exercice']);
        $d = new Application_Model_DbTable_DomainesFormation();
        $this->view->domaines = $d->fetchAll()->toArray();
        $this->view->budgetDomaine = array();
        foreach($this->view->budgets  as $budget) {
           /* $key = $domaine['id'];
            $value = $domaine['domaine_formation'];*/
            $id = $budget['id'];
            $this->view->budgetDomaine[$id] = $model->getMontantDomaineAlloue($id);
        }
    }

    

}

