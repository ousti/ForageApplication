<?php


require_once 'BaseController.php';

class FormationController extends BaseController {
    
    protected $exercice;

   /**** init controller *******/
    public function init()
    {  
        // Optional added for consistency
        parent::init();
        $budgetObject = new Application_Model_BudgetMapper();
        $this->budgets = $budgetObject->getEntiteBudgetEncours(); 
        
        // Excel format context
        $excelConfig =
                    array(
                        'excel' => array(
                            'suffix'  => 'excel',
                            'headers' => array(
                                'Content-type' => 'application/vnd.ms-excel',
                                'charset' => 'iso-8859-1')
                            ),
                    );

        // Init the Context Switch Action helper
        $contextSwitch = $this->_helper->contextSwitch();
        // Add the new context
        $contextSwitch->setContexts($excelConfig);
        // Set the new context to the reports action
        $contextSwitch->addActionContext('reporting', 'excel');
        // Initializes the action helper
        $contextSwitch->initContext();
    }

    

    /**** ajouter formation *******/
    public function addAction() {
       # Liste deroulante
       $dObj =  new Application_Model_DbTable_Direction(); 
       $nObj = new Application_Model_DbTable_NaturesFormation();
       $tObj = new Application_Model_DbTable_TypesFormation();
       $doObj = new Application_Model_DbTable_DomainesFormation();
       $pyObj = new Application_Model_DbTable_Pays();
       $params = array('directions' =>$dObj->fetchAll(), 'budgets'=>$this->budgets,
                       'natures' => $nObj->fetchAll(), 'types' => $tObj->fetchAll(),
                       'pays' => $pyObj->fetchAll(), 'id'=>null,
                       'domaines'=>$doObj->fetchAll()
                 );
       $form = new Application_Form_FormationForm($params);
       $this->view->form = $form;
      
    }
    
    /**** modifier formation *******/
    public function editAction() {
        $params = $this->getRequest()->getParams();
        $obj = new Application_Model_DbTable_Formation();
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
    
   /**** enregistrer formation *******/
    public function saveFormAction() {
      $request = $this->getRequest();
      $parameters = $request->getParams();
       # Liste deroulante
       $dObj =  new Application_Model_DbTable_Direction(); 
       $nObj = new Application_Model_DbTable_NaturesFormation();
       $tObj = new Application_Model_DbTable_TypesFormation();
       $doObj = new Application_Model_DbTable_DomainesFormation();
       $lObj = new Application_Model_DbTable_Pays();
       $params = array('directions' =>$dObj->fetchAll(), 'budgets'=>$this->budgets,
                       'natures' => $nObj->fetchAll(), 'types' => $tObj->fetchAll(),
                       'pays' => $lObj->fetchAll(), 'id'=>$parameters['id'],
                       'domaines'=>$doObj->fetchAll()
                 );
        # Form
        $form = new Application_Form_FormationForm($params);
        $form->populate($parameters); 
        $this->view->form = $form;
        if ($request->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_Formation($form->getValues()); 
                $mapper = new Application_Model_FormationMapper();
                $newId = $mapper->save($values);
                if($newId=='') $newId = $parameters['id'];
                $this->_redirect("/formation/detail/id/" . $newId);
                // $this->_redirect("/session-formation/add/id_formation/" . $newId);
                return;
            }
        }
        $this->renderScript("formation/add.phtml");
    }

   /**** detail formation *******/
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
       # Recuperer liste des objectifs de la formation en cours
       $fo = new Application_Model_DbTable_FormationObjectif();
       $this->view->objectif = $fo->fetchAll("id_formation = ".(int)$params['id']);
       //Zend_Debug::dump($this->view->objectif, $label = "Objectif de la formation id = 1", $echo = false);
        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];

    }
    
   /**** supprimer formation *******/
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
    
   /**** cloturer formation *******/  
   public function closeAction() {
       $params = $this->getRequest()->getParams();
       if(isset($params['id'])) {
           $formation = new Application_Model_DbTable_Formation();
           $formation->update(array('statut'=>1), 'id = '.(int)$params['id']);
           $this->getHelper('FlashMessenger')->addMessage('Formation cloturée avec succès!');
           $this->getHelper('FlashMessenger')->addMessage('Veuillez saisir les absents de ma formation');
           $this->redirect("auditeur/index/id_formation/".$params['id']);
       }
       $this->renderScript("index/index.phtml");
    }
    
   /**** annuler cloture formation *******/
   public function rollbackcloseAction() {
       $params = $this->getRequest()->getParams();
       if(isset($params['id'])) {
           $formation = new Application_Model_DbTable_Formation();
           $formation->update(array('statut'=>0), 'id = '.(int)$params['id']);
           $this->getHelper('FlashMessenger')->addMessage('Clôture de formation annulée avec succès!');
           $this->redirect("formation/detail/id/".$params['id']);
       }
       //$this->renderScript("index/index.phtml");
    }
    
   /**** reporting formation *******/
   public function reportingAction(){
        $this->view->count = 0;
        $model = new Application_Model_PersonnelMapper();
        $dObj =  new Application_Model_DbTable_Direction(); 
        $pObj =  new Application_Model_DbTable_Pole(); 
        $eObj = new Application_Model_DbTable_Entite();
        $doObj = new Application_Model_DbTable_DomainesFormation();
        $this->view->filterReportingForm = new Application_Form_FilterReportingFormationForm(array(
                                    'directions'=>$dObj->fetchAll(), 'poles'=>$pObj->fetchAll(), 
                                    'entites'=>$eObj->fetchAll(),'domaines'=>$doObj->fetchAll()
                                    ));
        # Chaine d'affichage de Tri
        $this->view->sortString = array();
        # ********************* Tri
        $sortBy = array();
        $parameters = $this->getRequest()->getParams(); 
        if(isset($parameters['id_direction']) and $parameters['id_direction']) {
            $sortBy['id_direction'] = $parameters['id_direction'];
            foreach($dObj->fetchAll()->toArray() as $key=>$array) {
                if($array['id'] == $parameters['id_direction']) :
                 $this->view->sortString['direction'] = $array['direction'];
                 break;
                endif;
            }
            
        }
        if(isset($parameters['id_pole']) and $parameters['id_pole']) {
            $sortBy['id_pole'] = $parameters['id_pole'];
            foreach($pObj->fetchAll()->toArray() as $key=>$array) {
                if($array['id'] == $parameters['id_pole']) :
                 $this->view->sortString['pole'] = $array['pole'];
                 break;
                endif;
            }
        }
        if(isset($parameters['id_domaine']) and $parameters['id_domaine']) {
            $sortBy['id_domaine'] = $parameters['id_domaine'];
            foreach($doObj->fetchAll()->toArray() as $key=>$array) {
                if($array['id'] == $parameters['id_domaine']) :
                 $this->view->sortString['domaine'] = $array['domaine_formation'];
                 break;
                endif;
            }
        }
       if(isset($parameters['id_entite']) and $parameters['id_entite']) {
            $sortBy['id_entite'] = $parameters['id_entite'];
            foreach($eObj->fetchAll()->toArray() as $key=>$array) {
                if($array['id'] == $parameters['id_entite']) :
                 $this->view->sortString['entite'] = $array['entite'];
                 break;
                endif;
            }
        }
        $sortBy['date_debut'] = date("Y-m");  
        if(isset($parameters['date_debut']) and $parameters['date_debut']) {
            $sortBy['date_debut'] = $parameters['date_debut'];
             list($year,$month) = explode('-',$sortBy['date_debut']);
             $this->view->sortString['date_debut'] = $month.'-'.$year;
        }
        if(isset($parameters['date_fin']) and $parameters['date_fin']) {
            $sortBy['date_fin'] = $parameters['date_fin'];
             list($year,$month) = explode('-',$sortBy['date_fin']);
             $this->view->sortString['date_fin'] = $month.'-'.$year;;
        }
        $this->view->filterReportingForm->populate($parameters);
          
  
        $param = array('report'=>true);
        $param += $sortBy;
        $reports = $model->fetchAll($param);
        $this->view->reports = array();
        foreach($reports as $list) {
            $intitule = $list['intitule'];
            $this->view->reports[$intitule][] = $list;
            $this->view->count++;
        }
        
       /* if (isset($parameters['export'])) {
            $this->view->export = true;
            $this->export($this->view->reports);
           
        }*/
        
   }
   
   private function export($data) {
        $this->renderScript("index/index.phtml");
        
   }
    
    
    
}

