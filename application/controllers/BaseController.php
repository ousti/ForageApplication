<?php

abstract class BaseController extends Zend_Controller_Action {

    
    /**
     *
     * @param string $method
     * @param string $args
     */
    public function __call($method, $args) {
        $this->_redirect("index");
    }

    public function init() {
        /*if(!Zend_Auth::getInstance()->hasIdentity()) 
            $this->_redirect('auth/login');*/
        $this->view->controller = $this->getRequest()->getControllerName();
        $this->view->action = $this->getRequest()->getActionName();
        // Session exercice
        $defaultNamespace = new Zend_Session_Namespace('Default') ;
        $this->getRequest()->setParam('exercice', $defaultNamespace->exercice);  
    }

    public function preDispatch() {
        parent::preDispatch();
         
        $this->view->render('index/_menu.phtml');
        
    }

    public function postDispatch() {
        $this->view->render('index/_footer.phtml');
    }
    
    

}

