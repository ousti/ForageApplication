<?php


require_once 'BaseController.php';

class IndexController extends BaseController {
    
    //protected $exercice;
    private $params;
    

    public function init() {
        parent::init();
        $this->params = $this->getRequest()->getParams();       
        $dObj =  new Application_Model_DbTable_Direction(); 
        $this->directions = $dObj->fetchAll(); 
        $pObj =  new Application_Model_DbTable_Pole(); 
        $this->poles = $pObj->fetchAll();
        $orgObj = new Application_Model_DbTable_Organisme();
        $this->organismes = $orgObj->fetchAll();
        $tfObj = new Application_Model_DbTable_TypesFormation();
        $this->typesFormation = $tfObj->fetchAll();
        // Excel format context
        $excelConfig =
                    array(
                        'excel' => array(
                            'suffix'  => 'excel',
                            'headers' => array(
                                'Content-type' => 'application/vnd.ms-excel; charset=UTF-8'
                                )
                            ),
                    );
        
        // Init the Context Switch Action helper
        $contextSwitch = $this->_helper->contextSwitch();
        // Add the new context
        $contextSwitch->setContexts($excelConfig);
        // Set the new context to the reports action
        $contextSwitch->addActionContext($this->view->action, 'excel');
        // Initializes the action helper
        $contextSwitch->initContext();
        
    }

    
    /*
     * CALENDRIER ACTION DE FORMATION
     */
    public function indexAction() {
        $this->view->count = 0;
         # formation en attente
        $model = new Application_Model_FormationMapper();
        $this->view->formations = $model->getCalendrierFormation($this->params['exercice']);
        $listeFormations =  $this->view->formations;
        $this->view->calendrierActionFormation = array();
        $mois = Misc_Utils::getMoisAnnee();
        foreach ($listeFormations as $list) {
            # get mois formation
            /*$date = date_parse_from_format("Y-m-d", $list['date_debut']);
            echo $m = $date['month'];*/
            $m = date("n",  strtotime($list['date_debut']));
            $this->view->calendrierActionFormation[$m][] = $list;
            $this->view->count++;
        }
        $this->view->exerciceYear = $this->params['exercice'];
        # Flash Message Add or update
        $fm = $this->getHelper('FlashMessenger')->getMessages();
        if($fm)
        $this->view->fm = $fm[0];
   }
   
    
   /*
    * FORMATIONS REALISEES > REPORTING
    */
    public function doneAction() {
        $this->view->count = 0;
        $this->view->exerciceYear = $this->params['exercice'];
        # Formulaire de tri
        $this->view->filterFormationRealiseForm = new Application_Form_FilterFormationRealiseForm(array(
                                    'directions'=>$this->directions, 'poles'=>$this->poles,
                                    'organismes'=>$this->organismes,'typesFormation'=>$this->typesFormation));
        # Chaine d'affichage de Tri
        $this->view->sortString = array();
        # ********************* Tri
        $sortBy = array();
        //$parameters = $this->getRequest()->getParams(); 
        if(isset($this->params['id_direction']) and $this->params['id_direction']) {
            $sortBy['id_direction'] = $this->params['id_direction'];
            foreach($this->directions->toArray() as $key=>$array) {
                if($array['id'] == $this->params['id_direction']) :
                 $this->view->sortString['direction'] = $array['direction'];
                 break;
                endif;
            }
            
        }
        if(isset($this->params['id_pole']) and $this->params['id_pole']) {
            $sortBy['id_pole'] = $this->params['id_pole'];
            foreach($this->poles->toArray() as $key=>$array) {
                if($array['id'] == $this->params['id_pole']) :
                 $this->view->sortString['pole'] = $array['pole'];
                 break;
                endif;
            }
        }
        if(isset($this->params['id_organisme']) and $this->params['id_organisme']) {
            $sortBy['id_organisme'] = $this->params['id_organisme'];
            foreach($this->organismes->toArray() as $key=>$array) {
                if($array['id'] == $this->params['id_organisme']) :
                 $this->view->sortString['organisme'] = $array['organisme'];
                 break;
                endif;
            }
        }
        if(isset($this->params['id_type']) and $this->params['id_type']) {
            $sortBy['id_type'] = $this->params['id_type'];
            foreach($this->typesFormation->toArray() as $key=>$array) {
                if($array['id'] == $this->params['id_type']) :
                 $this->view->sortString['type_formation'] = $array['type_formation'];
                 break;
                endif;
            }
        }
        if(isset($this->params['periode']) and $this->params['periode'])
            $sortBy['periode'] = $this->params['periode'];
        
       
        
       /*************************************************/
        $this->view->filterFormationRealiseForm->populate($this->params);
        $model = new Application_Model_FormationMapper();
        $this->view->formations = $model->getListeFormationRealise($sortBy,$this->params['exercice']);
        $listeFormations =  $this->view->formations;
        $this->view->formationRealise = array();
        $mois = Misc_Utils::getMoisAnnee();
        foreach ($listeFormations as $list) {
            # get mois formation
            /*$date = date_parse_from_format("Y-m-d", $list['date_debut']);
            $m = $date['month'];*/
            $m = date("n",  strtotime($list['date_debut']));
            $this->view->formationRealise[$m][] = $list;
            $this->view->count++;
        }
      
   }
   
   
   /*
    * Changing exercice
    */
   public function exerciceAction() {
     $parameters = $this->getRequest()->getParams();
     $defaultNamespace = new Zend_Session_Namespace('Default') ;
     $defaultNamespace->exercice = $parameters['year'];
     $this->redirect('index/'.$defaultNamespace->exercice);
   }
    
  
   

}

