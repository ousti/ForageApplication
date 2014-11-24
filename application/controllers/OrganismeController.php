<?php

require_once 'BaseController.php';

class OrganismeController extends BaseController {

    public function indexAction() {
        $this->view->count = 0;
        # Formulaire de tri
        $this->view->filterCabinetForm = new Application_Form_FilterOrganismeForm(array('nationalites'=>  Misc_Utils::getNationaliteOrganisme()));
        # Chaine d'affichage de Tri
        $this->view->sortString = array();
        # Tri
        $sortBy = NULL;
        $parameters = $this->getRequest()->getParams(); 
        if(isset($parameters['nationalite']) and $parameters['nationalite']) {
            $sortBy['nationalite'] = $parameters['nationalite'];
            foreach(Misc_Utils::getNationaliteOrganisme() as $key=>$value) {
                if($key == $parameters['nationalite']) :
                 $this->view->sortString['nationalite'] = $value;
                 break;
                endif;
            }
            
        }
        $model = new Application_Model_OrganismeMapper();
        $this->view->organismes = $model->fetchAll($sortBy);
        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
    }

     /**** enregistrer organisme *******/
    public function addAction() {
       $form = new Application_Form_OrganismeForm(array('id'=>''));
       $this->view->form = $form;
    }
    
      /**** modifier organisme *******/
    public function editAction() {
       $params = $this->getRequest()->getParams();
       $obj = new Application_Model_DbTable_Organisme();
       $organisme = $obj->find((int)$params['id'])->current()->toArray();
       $params = array('id'=>$params['id']
                       );
       $form = new Application_Form_OrganismeForm($params);
       $form->populate($organisme);
       $this->view->form = $form;
    }
    
    
    public function saveFormAction() {
      $request = $this->getRequest();
      $parameters = $request->getParams();
      $params = array('id'=>$parameters['id']                      
                 );
        # Form
        $form = new Application_Form_OrganismeForm($params);
        $form->populate($parameters); 
        $this->view->form = $form;
        if ($request->isPost()) {
            if ($form->isValid($_POST)) {
                $values = new Application_Model_Organisme($form->getValues()); 
                $mapper = new Application_Model_OrganismeMapper(); var_dump($values);
                $newId = $mapper->save($values); 
                if($newId=='') $newId = $parameters['id'];
                $this->getHelper('FlashMessenger')->addMessage('Cabinet de formation enregistré avec succès!');
                $this->_redirect("/organisme");
                return;
            }
        }
        $this->renderScript("organisme/add.phtml");
    }
    
    
    
    public function deleteAction() {
       $params = $this->getRequest()->getParams(); 
       if(isset($params['id_organisme'])) {
           $cabinet = new Application_Model_DbTable_Organisme();
           $a = $cabinet->delete('id = '.$params['id_organisme']);
           $this->getHelper('FlashMessenger')->addMessage('Organisme supprimé avec succès!');
           $this->redirect("/organisme");
       }
    }
    
    

}

