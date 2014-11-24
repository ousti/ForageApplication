<?php

class Application_Form_FilterPersonnelForm extends ZendX_JQuery_Form  {
   
  private $directions;


  public function __construct(array $params = array())
  {
      $this->directions = $params['directions'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        
        /**** INIT SELECT ********/
        
        # direction
        $options = array();
        $options[0] = 'tri par direction';
        foreach ($this->directions as $direction) {
            $options[$direction['id']] = $direction['direction'];
        }
        $element = new Zend_Form_Element_Select("id_direction", array(
                    'label' => '',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
        $element = new Zend_Form_Element_Text("byField", array(
                    "label" => "", "value"=>""
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Sort',
            'label'    => 'rechercher',
            'class' =>'submit-filter'
        ));        

       
    }

}

