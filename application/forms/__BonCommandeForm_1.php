<?php


include_once 'Zend_Validate_UniqueRefNo.php';
include_once 'Zend_Validate_IsNumber.php';

class Application_Form_BonCommandeForm extends Zend_Form {
   
  private $id; 
  private $budgets;
  private $id_formation;


  public function __construct(array $params = array())
  {
    $this->id = $params['id'];
    $this->budgets = $params['budgets'];
    $this->id_formation = $params['id_formation'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        /** Identifiant ***/
        $element = new Zend_Form_Element_Hidden('id', array('value' => $this->id));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Hidden('id_formation', array('value' => $this->id_formation));
        $this->addElement($element);
        
        
        /**** INIT SELECT 
         ********/
        
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
        
        
        $element = new Zend_Form_Element_Text("numero_commande", array(
                    "label" => "Bon de commande",
                ));
        $element->setRequired(false);
        $this->addElement($element);
           
        
        $element = new Zend_Form_Element_Text("montant", array(
                    "label" => "Montant",
                ));
        $element->setRequired(false);
        $this->addElement($element);
           
        
        $element = new Zend_Form_Element_TextArea("observation", array(
                    "label" => "Observation",
                ));
        $element->setRequired(false);
        $this->addElement($element);
           
        
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Save',
            'label'    => 'enregistrer',
        ));        

      
    }

}

