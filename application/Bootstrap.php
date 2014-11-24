<?php


require_once 'misc/Utils.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    
    protected function _initView() {
        $view = new Zend_View();
        //... code de paramétrage de votre vue : titre, doctype ...
        $view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
        //... paramètres optionnels pour les helpeurs jQuery ....
       /*$view->jQuery()->setLocalPath('/js/jquery-1.7.2.min.js')
                      ->setUILocalPath('/js/jquery-ui-1.8.23.custom.min.js')
                      ->addStyleSheet('/css/smoothness/jquery-ui-1.8.23.custom.css');*/
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        
        $session = new Zend_Session_Namespace('Default');
        if(isset($session->exercice))
            $view->exercice = $session->exercice;
        else
            $view->exercice = date('Y');
        //echo $view->exercice;
        return $view;
    }
    
    
    protected function _initEncoding() {
        //mb_internal_encoding("UTF-8");
        //mb_internal_encoding("UTF-8");
    }

    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

    protected function _initMenu() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->placeholder('menu')
                ->setPrefix("<div id=\"menu\">")
                ->setPostfix("</div>");
    }

    protected function _initfFooter() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->placeholder('footer')
                ->setPrefix("<div id=\"footbox\"><div id=\"foot\">")
                ->setPostfix("</div></div>");
    }
    
    protected function _initUserSession()
    {
        // If not already done, bootstrap the view
        /*$this->bootstrap('view');
        $view = $this->getResource('view');

        // Initialise the session
        $session = new Zend_Session_Namespace('Default');

        // See if the username has been set, and if not at 
        // least make the variable
        if (isset($session->exercice)
            $view->exercice = $session->exercice;
        else
            $view->username = null; 
         * 
         */      
    }

}

