<?php


require_once 'BaseController.php';

class FormationInterneController extends BaseController {

   public function init()
    {
        //$this->view->setTitrePage("Gestion des Formations");
    }

   
    public function indexAction() {
        $params = $this->getRequest()->getParams();
        # formation passée en parametre
        $formationTable = new Application_Model_DbTable_Formation();
        $this->view->formation = $formationTable->find((int)$params['id_formation'])->current();
        # Liste des sessions d'une formation
        $this->view->sessions = $this->view->formation->findApplication_Model_DbTable_Session(); 
        # Formation Interne
       //$this->view->formationInterne = $this->view->formation->findApplication_Model_DbTable_FormationInterne()->current();
        $fiMapper = new Application_Model_FormationInterneMapper();
        $formationInterne = $fiMapper->fetchAll("id_formation=".(int)$params['id_formation'])->toArray(); 
        $this->view->formationInterne = array();
        foreach($formationInterne as $f) {
            $this->view->formationInterne[$f['id_session']][] = $f;
        } 
        /*
        if($this->view->formationInterne) :
            $pTable = new Application_Model_DbTable_Personnel();
            $this->view->formateur = $pTable->find($this->view->formationInterne['id_personnel'])->current();
            $dTable = new Application_Model_DbTable_Direction();
            $this->view->directionFormateur = $dTable->find($this->view->formateur['id_direction'])->current();
            # recuperer session pr calcul volume total horaire
            $session = $this->view->formation->findApplication_Model_DbTable_Session(); 
            $this->view->volumeHoraireTotal = 0;
            foreach($session as $s) {
                $this->view->volumeHoraireTotal += $s['nombre_jour']*$s['nombre_heure_jour'];
            }
        endif;*/

        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
        
        //Zend_Debug::dump($this->view->typesCout, $label = null, $echo = true);
        
    }
    
    
    /**** enregistrer formateur interne *******/
    public function addAction() {
       $parameters = $this->getRequest()->getParams();
       $this->view->idFormation = (int)$parameters['id_formation'];
       # Liste deroulante
       $obj =  new Application_Model_PersonnelMapper();
       $res = $obj->getNotLinkedToSession((int)$parameters['id_session']);
       $params = array('op'=>'add', 'formateur' => $res, 'id_session'=>(int)$parameters['id_session']);
       $form = new Application_Form_FormationInterneForm($params);
       $this->view->form = $form;
    }
    
    /**** modifier formateur *******/
    public function editAction() {
       $parameters = $this->getRequest()->getParams();
       $this->view->idFormation = (int)$parameters['id_formation'];
       # Liste deroulante
       $obj =  new Application_Model_PersonnelMapper();
       $params = array('formateur' => $obj->fetchAll(), 'id_formation' =>$this->view->idFormation,'id_session' =>$parameters['id_session'],'op'=>'edit','id_formateur_old'=>$parameters['id_formateur']);
       $form = new Application_Form_FormationInterneForm($params);
       $formateurInterne = new Application_Model_DbTable_FormationInterne();
       $res = $formateurInterne->find($parameters['id_session'],$parameters['id_formateur'])->current()->toArray();
       $form->populate($res);
       $this->view->form = $form;
    }
    
     public function saveFormAction() {
       $parameters = $this->getRequest()->getParams();   
       $this->view->idFormation = (int)$parameters['id_formation'];
       $params = array('op'=>$parameters['op'],'id_session' =>(int)$parameters['id_session']);
       # Liste deroulante
       if($parameters['op'] == 'add') {
        $obj =  new Application_Model_PersonnelMapper();
        $res = $obj->getNotLinkedToSession((int)$parameters['id_session']);
       }
       else if($parameters['op'] == 'edit') {
        $obj =  new Application_Model_DbTable_Personnel();
        $res = array($obj->find($parameters['id_personnel'])->current());
        $params += array('id_formateur_old'=>$parameters['id_formateur_old']); 
       }
       $params += array('formateur' => $res);
       $form = new Application_Form_FormationInterneForm($params);
       $request = $this->getRequest();
       $form->populate($request->getParams());
       $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_FormationInterne($form->getValues()); 
                $mapper = new Application_Model_FormationInterneMapper(); 
                $id_formateur_old = isset($parameters['id_formateur_old']) ? $parameters['id_formateur_old'] : null;
                $newId = $mapper->save($values,$parameters['op'],$id_formateur_old);
                $this->getHelper('FlashMessenger')->addMessage('Formateur interne enregistré avec succès!');
                $this->_redirect("/formation-interne/index/id_formation/".$parameters['id_formation']);
                return;
            }
        }
        $this->renderScript("formation-interne/add.phtml");
    }

    
   public function deleteAction() {
       $params = $this->getRequest()->getParams(); 
       if(isset($params['id_formation'])) {
           $fi = new Application_Model_DbTable_FormationInterne();
           $a = $fi->delete('id_session = '.$params['id_session'].' AND id_personnel = '.$params['id_formateur']);
           $this->getHelper('FlashMessenger')->addMessage('Formateur supprimé avec succès!');
           $this->redirect("formation-interne/index/id_formation/".$params['id_formation']);
       }
    }
  
    
    
    
}

