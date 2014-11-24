<?php


class Application_Form_FilterOrganismeForm extends ZendX_JQuery_Form  {
   
  private $nationalites;


  public function __construct(array $params = array())
  {
    $this->nationalites = $params['nationalites'];  
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        
        /**** INIT SELECT ********/
        
        # direction
        $options = array();
        $options[0] = 'choisir nationalité';       
        foreach ($this->nationalites as $key=>$value) {
            $options[$key] = $value;
        }
        $element = new Zend_Form_Element_Select("nationalite", array(
                    'label' => '',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Sort',
            'label'    => 'effectuer tri',
            'class' =>'submit-filter'
        ));        

       
    }

}

