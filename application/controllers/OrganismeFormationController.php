<?php


require_once 'BaseController.php';

class OrganismeFormationController extends BaseController {

   public function init()
    {
        //$this->view->setTitrePage("Gestion des Formations");
    }

   
    public function indexAction() {
        $params = $this->getRequest()->getParams();
        # formation passée en parametre
        $formationTable = new Application_Model_DbTable_Formation();
        $this->view->formation = $formationTable->find((int)$params['id_formation'])->current();
        # Formation Externe
        $this->view->formationExterne = $this->view->formation->findApplication_Model_DbTable_FormationExterne()->current();
        if($this->view->formationExterne) :
            $cabinetTable = new Application_Model_DbTable_Organisme();
            $this->view->cabinet = $cabinetTable->find($this->view->formationExterne['id_organisme'])->current();
        endif;

        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
        
        //Zend_Debug::dump($this->view->typesCout, $label = null, $echo = true);
        
    }
    
    
    /**** enregistrer cabinet de formation *******/
    public function addAction() {
       $parameters = $this->getRequest()->getParams();
       $this->view->idFormation = (int)$parameters['id_formation'];
       # Liste deroulante
       $obj =  new Application_Model_OrganismeMapper();
       $res = $obj->fetchAll();
      // Zend_Debug::dump($res, $label = 'Type de formation enregistrable', $echo = true);
       $params = array('cabinet' => $res, 'id_formation' =>$this->view->idFormation);
       $form = new Application_Form_OrganismeFormationForm($params);
       $this->view->form = $form;
    }
    
   /** modifier cabinet de formation ***/
    public function editAction() {
        $params = $this->getRequest()->getParams();
        $this->view->idFormation = (int)$params['id_formation'];
        # Liste deroulante
        $obj =  new Application_Model_OrganismeMapper();
        $res = $obj->fetchAll();
        $obj = new Application_Model_DbTable_FormationExterne();
        $organismeFormation = $obj->find((int)$params['id_formation'])->current()->toArray();
        $form = new Application_Form_OrganismeFormationForm(array('cabinet' => $res, 'id_formation' =>$params['id_formation'],'id_organisme'=>$params['id_organisme']));
        $form->populate($organismeFormation);
        $this->view->form = $form;
    }
        
     public function saveFormAction() {
       # Liste deroulante
       $parameters = $this->getRequest()->getParams();
       $this->view->idFormation = (int)$parameters['id_formation'];
       $obj =  new Application_Model_OrganismeMapper();
       $res = $obj->fetchAll();
       $params = array('cabinet' => $res, 'id_formation' =>$this->view->idFormation);
       $form = new Application_Form_OrganismeFormationForm($params);
       $request = $this->getRequest();
        $form->populate($request->getParams());
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_OrganismeFormation($form->getValues());
                $mapper = new Application_Model_OrganismeFormationMapper();
                $newId = $mapper->save($values);
                $this->getHelper('FlashMessenger')->addMessage('Organisme de formation enregistré avec succès!');
                $this->_redirect("/organisme-formation/index/id_formation/".$parameters['id_formation']);
                return;
            }
        }
        $this->renderScript("organisme-formation/add.phtml");
    }

    
}

