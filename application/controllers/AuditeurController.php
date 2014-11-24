<?php


require_once 'BaseController.php';

class AuditeurController extends BaseController {

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
        # Liste des sessions d'une formation
        $this->view->sessions = $this->view->formation->findApplication_Model_DbTable_Session(); //var_dump($sessionTable);
        # Participants de cette formation 
        $audMapper = new Application_Model_AuditeurMapper();
        $cond = " id_formation = " . (int) $params['id_formation'];
        # filtrer liste par entité
        if(isset($params['filterByEntite']) and $params['filterByEntite']<>-1)
            $cond .= " AND p.id_entite = ".(int) $params['filterByEntite'];
        # filtrer liste par presence, absence
        if(isset($params['filterByStatusPresence'])and $params['filterByStatusPresence']<>-1)
            $cond .= " AND a.statut = ".(int) $params['filterByStatusPresence']; 
        # appel requete
        $auditeurs = $audMapper->fetchAll($cond)->toArray(); 
        $this->view->auditeurs = array();
        foreach($auditeurs as $a) {
            $this->view->auditeurs[$a['id_session']][] = $a;
        } 
        # Flash Message Add or update
        $fm =  $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
   }
    
    
    /**** enregistrer auditeur *******/
    public function addAction() {
       $parameters = $this->getRequest()->getParams();
       $this->view->idFormation = (int)$parameters['id_formation'];
       $form = new Application_Form_AuditeurForm(array('id_formation'=>$parameters['id_formation'],
                                                       'id_session'=>(int)$parameters['id_session'],
                                                       'nb'=>5
                                                ));
       $this->view->form = $form;
    }
    
     public function saveFormAction() {
       $parameters = $this->getRequest()->getParams();
       $this->view->idFormation = (int)$parameters['id_formation'];
       $form = new Application_Form_AuditeurForm(array('id_formation'=>$parameters['id_formation'],
                                                       'id_session'=>(int)$parameters['id_session'],
                                                       'nb'=>5));
       $request = $this->getRequest();
       $form->populate($request->getParams());
       $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $formValues = $form->getValues();
                foreach ($formValues as $value) :
                  if($value) : 
                    $pTable = new Application_Model_DbTable_Personnel();
                    $p = $pTable->fetchAll(" matricule = '".$value."'")->toArray();
                    if($p[0]) : 
                        $valeur = array('id_session'=>(int)$parameters['id_session'],
                                        'id_personnel' =>  $p[0]['id'],
                                        'statut' => 1
                                        );

                        $values = new Application_Model_Auditeur($valeur);
                        $mapper = new Application_Model_AuditeurMapper();
                        $newId = $mapper->save($values);
                     endif;
                  endif;                   
               endforeach;
                $this->getHelper('FlashMessenger')->addMessage('Auditeurs enregistrés avec succès!');
                $this->_redirect("/auditeur/index/id_formation/".$parameters['id_formation']);
               return;
            }
        }
        $this->renderScript("auditeur/add.phtml");
    }

   public function deleteAction() {
       $params = $this->getRequest()->getParams(); 
       if(isset($params['id_formation'])) {
           $auditeur = new Application_Model_DbTable_Auditeur();
           $a = $auditeur->delete('id = '.$params['id_auditeur']);
           $this->getHelper('FlashMessenger')->addMessage('Auditeur supprimé avec succès!');
           $this->redirect("auditeur/index/id_formation/".$params['id_formation']);
       }
    }
    
   public function missingAction() {
       $params = $this->getRequest()->getParams(); 
       if(isset($params['id_formation'])) {
           $auditeur = new Application_Model_DbTable_Auditeur();
           $a = $auditeur->update(array('statut'=>0), 'id = '.$params['id_auditeur']);
           $this->getHelper('FlashMessenger')->addMessage('Auditeur confirmé absent avec succès!');
           $this->redirect("auditeur/index/id_formation/".$params['id_formation']);
       }
    }
    
}

