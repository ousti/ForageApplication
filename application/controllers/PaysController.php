<?php

require_once 'BaseController.php';

class PaysController extends BaseController {

    public function indexAction() {
        $model = new Application_Model_DbTable_Pays();
        $this->view->pays = $model->fetchAll();
    }
    
    /**** ajouter pays ******/
    public function addAction() {
       $form = new Application_Form_PaysForm();
       $this->view->form = $form;
    }

     public function saveFormAction() {
      $request = $this->getRequest();
      # Form
      $form = new Application_Form_PaysForm();
      $form->populate($request->getParams()); 
      $this->view->form = $form;
        if($request->isPost()) {
            if ($form->isValid($_POST)) {
                $pays = new Application_Model_DbTable_Pays(); 
                $pays->insert($form->getValues());
                $this->_redirect("/pays/index");
                return;
            }
        }
        $this->renderScript("pays/add.phtml");
    }

}

