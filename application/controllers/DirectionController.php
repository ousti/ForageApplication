<?php

require_once 'BaseController.php';

class DirectionController extends BaseController {

    public function indexAction() {
        $model = new Application_Model_DirectionMapper();
        $list = $model->getListeDirection();
        $listByPole = array();
        foreach($list as $p) {
            $pole = $p['pole'];
            $listByPole[$pole][] = $p;
        }
         $this->view->directions = $listByPole;
         
        # detail d'un salarié
        $param = $this->getRequest()->getParams();
        if(isset($param['id'])) {
          $this->view->direction = $model->find($param['id']);
        }
    }

    

}

