<?php


class Application_Form_OrganismeFormationForm extends Zend_Form {
   
    
  private $cabinets;
  private $id_formation;
 // private $id_organisme;



  public function __construct(array $params = array())
  {
    $this->id_formation = $params['id_formation'];
    $this->cabinets = $params['cabinet'];
   // $this->id_organisme = $params['id_organisme'];
    
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
                    
        /**** INIT SELECT 
         ********/
        
        # formation et organisme
        $element = new Zend_Form_Element_Hidden('id_formation', array('value' => $this->id_formation));
        $this->addElement($element);
        
        /*$element = new Zend_Form_Element_Hidden('id_organisme', array('value' => $this->id_organisme));
        $this->addElement($element);*/
        
        # cabinets
        $options = array();
        foreach ($this->cabinets as $c) {
            $options[$c->getId()] = $c->getOrganisme();
        }
        $element = new Zend_Form_Element_Select("id_organisme", array(
                    'label' => 'Selectionner le cabinet',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);

        
        $element = new Zend_Form_Element_Text("formateur", array(
                    "label" => "Formateur",
                   ));
        $element->setRequired(false);
        $this->addElement($element);
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Save',
            'label'    => 'enregistrer cabinet de formation'
       ));        

        
    }

}

