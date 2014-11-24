<?php


class Application_Form_OrganismeForm extends  ZendX_JQuery_Form{
   
  private $id; 
  

  public function __construct(array $params = array())
  {
    $this->id = $params['id'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        /** Identifiant ***/
        $element = new Zend_Form_Element_Hidden('id', array('value' => $this->id));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text("organisme", array(
                    "label" => "Nom cabinet",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text("tel1", array(
                    "label" => "Tel 1",
                   ));
        $this->addElement($element);
     
        
        
        $element = new Zend_Form_Element_Text("tel2", array(
                    "label" => "Tel2",
                   ));
        $this->addElement($element);
        
        
        $element = new Zend_Form_Element_Text("fax", array(
                    "label" => "Fax",
                   ));
        $this->addElement($element);
        
       
        $element = new Zend_Form_Element_Text("email", array(
                    "label" => "Email",
                   ));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text("localisation", array(
                    "label" => "Localisation",
                   ));
        $this->addElement($element);
        
        # Salarié externe ou interne
        $options = Misc_Utils::getNationaliteOrganisme();
        $options += array(0=>'ND');
        $element = new Zend_Form_Element_Select("nationalite", array(
                    'label' => 'Nationalité',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
           
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Save',
            'label'    => 'enregistrer',
        ));        

      
    }

}

