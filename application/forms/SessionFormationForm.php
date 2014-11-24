<?php


include_once 'Zend_Validate_UniqueRefNo.php';
include_once 'Zend_Validate_IsNumber.php';

class Application_Form_SessionFormationForm extends ZendX_JQuery_Form {
   
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
        
        # session formation 
        $element = new Zend_Form_Element_Hidden('id', array('value' => $this->id));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Hidden('id_formation', array('value' => $this->id_formation));
        $this->addElement($element);
        
        
      // Add Element Date Picker
        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                "date_debut", array("label" => "Date début")
            );
        $elem->setJQueryParam('dateFormat', 'yy-mm-dd');
        $this->addElement($elem);
        
        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                "date_fin", array("label" => "Date fin")
            );
        $elem->setJQueryParam('dateFormat', 'yy-mm-dd');
        $this->addElement($elem);
        
 
        $element = new Zend_Form_Element_Text("nombre_jour", array(
                    "label" => "Nombre de Jour",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
        $elem = new ZendX_JQuery_Form_Element_Spinner(
                        "nombre_heure_jour", array('label' => 'Nb Heure/J')
                    );
        $elem->setJQueryParams(array('min' => 1, 'max' => 8, 'start' => 1));
        $elem->setRequired(true);
        $this->addElement($elem);
        
        
        $element = new Zend_Form_Element_Text("localisation", array(
                    "label" => "Localisation",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
          
        $element = new Zend_Form_Element_Text("auditeur_prevu", array(
                    "label" => "Nombre d'auditeur prévu",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
        # Status de formation
        $options = array(0=>'En attente', 1=>'Terminé', -1=>'En cours');
        
        $element = new Zend_Form_Element_Select("status", array(
                    'label' => 'Statut Session',
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

