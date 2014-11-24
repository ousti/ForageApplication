<?php


include_once 'Zend_Validate_UniqueRefNo.php';
include_once 'Zend_Validate_IsNumber.php';

class Application_Form_ObjectifFormationForm extends ZendX_JQuery_Form {
   
    private $id_formation;
    private $id;
    
   
    public function __construct(array $params = array())
  {
    $this->id= $params['id'];
    $this->id_formation = $params['id_formation'];
     parent::__construct(); 
  }

 
  public function init() {
        
        $this->setMethod("post");
        
        # objectif formation 
        $element = new Zend_Form_Element_Hidden('id', array('value' => $this->id));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Hidden('id_formation', array('value' => $this->id_formation));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text("objectif", array(
                    "label" => "objectif",
                   ));
        $this->addElement($element);
        
        # retenu de formation
        $options = array(0=>'non retenu', 1=>'retenu');
        
        $element = new Zend_Form_Element_Select("evaluation", array(
                    'label' => 'Retenu pour objectif',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
  
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Save',
            'label'    => 'enregistrer session',
            'class'    => 'input-submit'
        ));        

        
    }

}

