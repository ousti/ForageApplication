<?php

require_once 'BaseController.php';

class UtilisateurController extends BaseController {

    public function indexAction() {
        $model = new Application_Model_DbTable_Utilisateur();
        $this->view->utilisateurs = $model->fetchAll();
       
    }

    

}

