<?php


require_once 'BaseController.php';

class AuthController extends BaseController {

    
   public function loginAction()
    {
        $users = new Application_Model_DbTable_Utilisateur();
        $form = new Application_Form_LoginForm();
        $this->view->form = $form;
        
        if($this->getRequest()->isPost()){
            if($form->isValid($_POST)){
                $wsdl_uri = 'http://ws.ldap.ocit.ci/';
                $url = 'http://10.242.70.229:28080/ci.orange.ldap/ldapWSService?wsdl';
                $client = new Zend_Soap_Client($url);
                $client->setUri($wsdl_uri);
                $client->setSoapVersion(1);
                $data = $form->getValues();
                $resultat = $client->authenticate(array('username'=>$data['login'],'password'=>$data['pwd']));
               
                if($resultat->return->check){
                    $auth = Zend_Auth::getInstance();
                    $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'utilisateur','login','role','? or 1 = 1');
                    $authAdapter->setIdentityColumn('login');
                               // ->setCredentialColumn('any');
                    $authAdapter->setIdentity($data['login'])
                                ->setCredential('any');
                    // Récupérer l'objet select (par référence)
                    $select = $authAdapter->getDbSelect();
                    $select->where('statut = 1');
                    $result = $auth->authenticate($authAdapter);
                    if($result->isValid()) {
                        $storage = new Zend_Auth_Storage_Session();
                        $storage->write($authAdapter->getResultRowObject());
                        // Exercice année courante par defaut
                        $defaultNamespace = new Zend_Session_Namespace('Default') ;
                        $defaultNamespace->exercice = date('Y');
                        $this->_redirect('index/index');
                    } else {
                       $this->view->errorMessage = "Vous n'avez pas le droit d'accéder à l'application! Contactez l'adminustrateur";  
                    }
                } else {
                    $this->view->errorMessage = "Login/Mot de passe incorrect! Veuillez réessayer!";
                }         
            }
        }
        
        $this->_helper->layout()->setLayout('auth_layout');
        //$this->_helper->layout()->disableLayout(); 
        //$this->_helper->viewRenderer->setNoRender(true);
    } 
    
    public function logoutAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('auth/login');
    }
    
}

