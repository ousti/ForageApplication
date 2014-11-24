<?php


require_once 'BaseController.php';

class DashboardController extends BaseController {

    
   /**** init controller *******/
    public function init()
    {  
        // Optional added for consistency
        parent::init();
       
        
    }

    /****
     * Index Action
    *****/
    public function indexAction() {
        $params = $this->getRequest()->getParams();
        $this->view->filterDashboardForm = new Application_Form_FilterDashboardFormationForm();
        if(!isset($params['date_debut']))
            $date = date('Y-m');
        else
            $date = $params['date_debut'];
           
        $this->view->param = array('date' => $date,'year'=>'2013');
        $model = new Application_Model_FormationMapper();
        $rs = $model->getDashboardFormationRealise($this->view->param);
        # Liste des domaines de formation
        $dObj = new Application_Model_DbTable_DomainesFormation();
        $this->view->domaines = $dObj->fetchAll();
        # Liste des entités
        $eObj = new Application_Model_DbTable_Entite();
        $this->view->entites = $eObj->fetchAll('inputable_budget = 1');
        //****** Traitement Action de formation
        $resultat = array();
        foreach($rs as $res) {
            $domaine = $res['domaine_formation'];
            $entite = $res['entite'];
            $resultat[$domaine][$entite][] = $res;
        }
        $this->view->resultat = $resultat;

        //****** Traitement Action de formation
        echo '<br>';
        $tabHeures = array();
        $this->view->heuresFormationMoisEnCours = array();
        $dateWithoutDash = str_replace('-', '', $this->view->param['date']);
        $rsHeure = $model->getDashboardHeureFormation($this->view->param);
        foreach($rsHeure as $r) {
            $domaine = $r['domaine_formation'];
            $entite = $r['entite'];
            if(!array_key_exists($domaine, $tabHeures)) 
                $tabHeures[$domaine] = array();
            if(!array_key_exists($entite, $tabHeures[$domaine]))
                $tabHeures[$domaine][$entite] = 0;
            $tabHeures[$domaine][$entite] += $r['heureTotalEntite'];
            if($r['periode']==$dateWithoutDash)
               $this->view->heuresFormationMoisEnCours[$domaine][$entite] = $r;
        }
        $this->view->heuresFormation = $tabHeures;
       //Zend_Debug::dump($this->view->heuresFormation , $label = 'LABEL', $echo = true);
        
        
        
        # ************* BUDGET DE FORMATION
        $modelBudget = new Application_Model_BudgetMapper();
        $budgetDomaine = $modelBudget->getMontantDomaineAlloue();
        $this->view->budgetDomaine = array();
        foreach($budgetDomaine as $b) :
            $id_entite = $b['id_entite'];
            $id_domaine = $b['id_domaine'];
            $this->view->budgetDomaine[$id_domaine][$id_entite] = $b['allocationDomaine'];
        endforeach;
        
       
       /*  foreach($budgetMois as $b) :
           $this->view->budgetMois[$b['domaine_formation']][$b['entite']] = $b['totalCoutDirect']+$b['totalCoutIndirect'];
        endforeach;
        *
        */
        $rsBudget = $modelBudget->getBudgetFormation($this->view->param); 
        $this->view->budgetAnnuelExecuteParDomaine = array(); // budget depensé du debut de l'année au mois selectionné
        $this->view->budgetDomaineMoisEnCours = array(); // budget depensé mois en cours
         foreach($rsBudget as $b) {
            $domaine = $b['domaine_formation'];
            $entite = $b['entite'];
            if(!array_key_exists($domaine, $this->view->budgetDomaine)) 
                $this->view->budgetAnnuelExecuteParDomaine[$domaine] = array();
            if(!array_key_exists($entite, $this->view->budgetAnnuelExecuteParDomaine[$domaine]))
                $this->view->budgetAnnuelExecuteParDomaine[$domaine][$entite] = 0;
            $this->view->budgetAnnuelExecuteParDomaine[$domaine][$entite] += $b['totalCoutDirect']+$b['totalCoutIndirect'];
            if($r['periode']==$dateWithoutDash)
               $this->view->budgetDomaineMoisEnCours[$domaine][$entite] = $b['totalCoutDirect']+$b['totalCoutIndirect'];
        }
        
        
        # ******** ETAT PARTICIPANT
        $oP = new Application_Model_AuditeurMapper();
        $etatParticipant = $oP->getEtatParticipant($this->view->param);
        $this->view->etatParticipant = array();
        $this->view->participantPresent = array();
        // tableau de repartition des participants par categorie socio-professionnelle
        $repartitionParticipantCategorie = array(); 
        foreach($etatParticipant as $e) {
            $this->view->etatParticipant[$e['id_domaine']][$e['id_entite']][] = $e['statut'];
            if($e['statut']) :
                if(!array_key_exists($e['id_domaine'], $this->view->participantPresent) or 
                   !array_key_exists($e['id_entite'], $this->view->participantPresent[$e['id_domaine']])) 
                   $this->view->participantPresent[$e['id_domaine']][$e['id_entite']] = 0;     
              $this->view->participantPresent[$e['id_domaine']][$e['id_entite']] +=1;
              $repartitionParticipantCategorie[$e['id_domaine']][$e['id_entite']][] = $e;
            endif; 
        }
        $actifYtd = $oP->getActifForYtd($this->view->param);
        $this->view->actifYtd = array();
        foreach($actifYtd as $a)
            $this->view->actifYtd[$a['id_entite']] = $a['salarieForme'];
        
        
        
        # *********** REPARTITION PAR CATEGORIE SOCIO PROFESSIONNELLE
        //$rP = $oP->getRepartitionParticipant($this->view->param);
        $this->view->categories = Misc_Utils::getCategorie();
        $this->view->repartitionParticipantCategorie = array();
        
         foreach($repartitionParticipantCategorie as $cle1=>$tab1) :
             foreach($tab1 as $cle2=>$tab2) : 
                 foreach($tab2 as $cle3=>$tab3) : 
                    $domaine = $tab3['id_domaine'];
                    $entite = $tab3['id_entite'];
                    $genre = $tab3['genre'];
                    $categorie = $tab3['id_categorie'];
                    $this->view->repartitionParticipantCategorie[$domaine][$entite][$genre][$categorie][] = true;
                  endforeach;
             endforeach;
         endforeach;
       //Zend_Debug::dump($this->view->repartitionParticipantCategorie[1][1]['Masculin'], $label = 'LABEL', $echo = true);
        
        
        /* foreach($repartitionParticipantCategorie as $cle1=>$val1) {
             
             foreach ($val1 as $cle2=>$val2) {
                echo 'Domaine  : '.$cle1.'  Entité  : '.$cle2.'<br>';
                foreach ($val2 as $cle3=>$val3)
                echo print_r($val3).'<br><br><br>';
             }
         }*/
    }

    
    
    
    
}
