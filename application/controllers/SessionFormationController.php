<?php


require_once 'BaseController.php';

class SessionFormationController extends BaseController {

   public function init()
    {
        //$this->view->setTitrePage("Gestion des Formations");
       //Zend_Debug::dump($this->view->typesCout, $label = null, $echo = true);
    }

   
    public function indexAction() {
        $params = $this->getRequest()->getParams();
        # formation passée en parametre
        $formationTable = new Application_Model_DbTable_Formation();
        $this->view->formation = $formationTable->find((int)$params['id_formation'])->current();
        # Sessions de cette formation 
        $this->view->sessions = $this->view->formation->findApplication_Model_DbTable_Session(); 
        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
   }
    
    
    /**** enregistrer session formation *******/
    public function addAction() {
       $parameters = $this->getRequest()->getParams();
       $this->view->idFormation = (int)$parameters['id_formation'];
       $form = new Application_Form_SessionFormationForm(array('id_formation' =>$parameters['id_formation'],'id'=>null));
       $this->view->form = $form;
    }
    
    /** modifier session formation ***/
    public function editAction() {
        $params = $this->getRequest()->getParams();
        $this->view->idFormation = (int)$params['id_formation'];
        $obj = new Application_Model_DbTable_Session();
        $session = $obj->find((int)$params['id_session'])->current()->toArray();
        $form = new Application_Form_SessionFormationForm(array('id_formation' =>$params['id_formation'],'id'=>$params['id_session']));
        $form->populate($session);
        $this->view->form = $form;
    }
    
    
     public function saveFormAction() {
       $parameters = $this->getRequest()->getParams();
       $this->view->idFormation = (int)$parameters['id_formation'];
       $form = new Application_Form_SessionFormationForm(array('id_formation' =>$parameters['id_formation'],'id'=>$parameters['id']));
       $request = $this->getRequest();
       $form->populate($request->getParams());
       $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_SessionFormation($form->getValues());
                $mapper = new Application_Model_SessionFormationMapper();
                $newId = $mapper->save($values);
                $this->getHelper('FlashMessenger')->addMessage('Session de formation enregistrée avec succès!');
                $this->_redirect("/session-formation/index/id_formation/".$parameters['id_formation']);
                return;
            }
        }
        $this->renderScript("session-formation/add.phtml");
    }

    
}
