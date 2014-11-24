<?php

require_once 'BaseController.php';

class PersonnelController extends BaseController {
  
    
    public function init() {
        $dObj =  new Application_Model_DbTable_Direction(); 
        $this->directions = $dObj->fetchAll(); //echo '<pre>'.print_r($this->directions).'</pre>';
    }

    
    
    public function indexAction() {
        $this->view->count = 0;
        # Formulaire de tri
        $this->view->filterPersonnelForm = new Application_Form_FilterPersonnelForm(array(
                                    'directions'=>$this->directions));
        # Chaine d'affichage de Tri
        $this->view->sortString = array();
        
        # ********************* Tri
        $sortBy = array();
        $parameters = $this->getRequest()->getParams(); 
        if(isset($parameters['id_direction']) and $parameters['id_direction']) {
            $sortBy['id_direction'] = $parameters['id_direction'];
            foreach($this->directions->toArray() as $key=>$array) {
                if($array['id'] == $parameters['id_direction']) :
                 $this->view->sortString['direction'] = $array['direction'];
                 break;
                endif;
            }
            
        } 
        if(isset($parameters['byField']) and $parameters['byField']) {
            $sortBy['byField'] = $parameters['byField'];
            $this->view->sortString['byField'] = $parameters['byField'];            
        }
        
        
        $model = new Application_Model_PersonnelMapper();
        $list = $model->fetchAll($sortBy);
        $listByEntite = array();
        foreach($list as $p) {
            $entite = $p['entite'];
            $listByEntite[$entite][] = $p;
        }
         $this->view->personnels = $listByEntite;
         
        # salarié > detail + formation recue
        $param = $this->getRequest()->getParams();
                # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
    }
    
    
    /*** DETAIL PERSONNEL ************/
    public function detailAction() {
        $param = $this->getRequest()->getParams(); 
        $model = new Application_Model_PersonnelMapper();
        $this->view->salarie = $model->find($param['id']);
        $auditeur = new Application_Model_FormationMapper();
        $this->view->formationRecues = $auditeur->getListeFormationRealise(array('id_personnel'=>$param['id'],'present'=>1));
        $pTable = new Application_Model_DbTable_Personnel();
        if($matriculeSup = $this->view->salarie->getSuperieur_hierarchique())
           $this->view->superieur = $pTable->fetchRow('matricule = '.$matriculeSup);
        else
          $this->view->superieur = null; 
    }
    
     /**** enregistrer personnel *******/
    public function addAction() {
       # Liste deroulante
       $dObj =  new Application_Model_DbTable_Direction(); 
       $eObj = new Application_Model_DbTable_Entite();
       $cObj = new Application_Model_DbTable_Categorie();
       $params = array('directions' =>$dObj->fetchAll(),
                       'entites' => $eObj->fetchAll(), 
                       'categories' => $cObj->fetchAll(), 'id'=>null
                      
                 );
       $form = new Application_Form_PersonnelForm($params);
       $this->view->form = $form;
    }
    
      /**** modifier personnel *******/
    public function editAction() {
       $params = $this->getRequest()->getParams();
       $obj = new Application_Model_DbTable_Personnel();
       $personnel = $obj->find((int)$params['id'])->current()->toArray();
       # Liste deroulante
       $dObj =  new Application_Model_DbTable_Direction(); 
       $eObj = new Application_Model_DbTable_Entite();
       $cObj = new Application_Model_DbTable_Categorie();
       $params = array('directions' =>$dObj->fetchAll(),
                       'entites' => $eObj->fetchAll(), 
                       'categories' => $cObj->fetchAll(), 'id'=>$params['id']
                      
                 );
       $form = new Application_Form_PersonnelForm($params);
       $form->populate($personnel); var_dump($personnel);
       $this->view->form = $form;
    }
    
    
    public function saveFormAction() {
      $request = $this->getRequest();
      $parameters = $request->getParams();
      # Liste deroulante
       $dObj =  new Application_Model_DbTable_Direction(); 
       $eObj = new Application_Model_DbTable_Entite();
       $cObj = new Application_Model_DbTable_Categorie();
       $params = array('directions' =>$dObj->fetchAll(),
                       'entites' => $eObj->fetchAll(), 
                       'categories' => $cObj->fetchAll(), 'id'=>$parameters['id']                      
                 );
        # Form
        $form = new Application_Form_PersonnelForm($params);
        $form->populate($parameters); 
        $this->view->form = $form;
        if ($request->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_Personnel($form->getValues()); 
                $mapper = new Application_Model_PersonnelMapper();
                $newId = $mapper->save($values); var_dump($newId);
                if($newId=='') $newId = $parameters['id'];
                $this->getHelper('FlashMessenger')->addMessage('Salarié enregistré avec succès!');
                $this->_redirect("/personnel/index/id/".$newId);
                return;
            }
        }
        $this->renderScript("personnel/add.phtml");
    }

    

};

