<?php


class Application_Form_SessionCoutIndirectForm extends Zend_Form {
   
    
  private $typesCout;
  private $id_session;
  private $action;
  private $budgets;



  public function __construct(array $params = array())
  {
    $this->id_session = $params['id_session'];
    $this->typesCout = $params['typesCout'];
    $this->action = $params['action'];
    $this->budgets = $params['budgets'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
                    
        /**** INIT SELECT 
         ********/
        
        # Session de formation 
        $element = new Zend_Form_Element_Hidden('id_session', array('value' => $this->id_session));
        $this->addElement($element);
        
        # types de cout
        $options = array();
        foreach($this->typesCout as $tc) {
            $options[$tc->getId()] = $tc->getLibelle();
        }
        $element = new Zend_Form_Element_Select("id_type_cout", array(
                    'label' => 'Selectionner le type de coût',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);

        
        $element = new Zend_Form_Element_Text("valeur", array(
                    "label" => "Montant",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
         # bugdet
        $options = array();
        foreach ($this->budgets as $budget) {
            $options[$budget['id']] = $budget['entite'].' '.$budget['annee'];
        }
        $element = new Zend_Form_Element_Select("id_budget", array(
                    'label' => 'Inputé sur budget',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
        
        $element = new Zend_Form_Element_Text("bon_commande", array(
                    "label" => "Bon de commande",
                   ));
        $element->setRequired(false);
        $this->addElement($element);
        
       $element = new Zend_Form_Element_Text("demande_achat", array(
                    "label" => "N° DA",
                   ));
        $element->setRequired(false);
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text("observation", array(
                    "label" => "Observation",
                ));
        $element->setRequired(false);
        $this->addElement($element);
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Save',
            'label'    => 'enregistrer cout indirect',
            'class'    => 'input-submit'
        ));        

        
    }

}

