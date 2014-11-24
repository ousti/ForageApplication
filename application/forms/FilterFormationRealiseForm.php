<?php



class Application_Form_FilterFormationRealiseForm extends ZendX_JQuery_Form  {
   
  private $directions;
  private $poles;
  private $organismes;
  private $typesFormation;


  public function __construct(array $params = array())
  {
    $this->directions = $params['directions'];
    $this->poles = $params['poles'];
    $this->organismes = $params['organismes'];
    $this->typesFormation = $params['typesFormation'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        
        /**** INIT SELECT ********/           
        # pole 
        $options = array();
        $options[0] = 'tri par pole';
        foreach ($this->poles as $pole) {
            $options[$pole['id']] = $pole['pole'];
        }
        $element = new Zend_Form_Element_Select("id_pole", array(
                    'Pole' => 'Pole',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
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
        
     
        
        
        # Periode
        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                "periode", array("label" => "", "class"=>'date')
            );
        $elem->setJQueryParam('dateFormat', 'yy-mm');
        $this->addElement($elem);
        
        
        # Organismes 
        $options = array();
        $options[0] = 'tri par cabinet';
        foreach ($this->organismes as $org) {
            $options[$org['id']] = $org['organisme'];
        }
        $element = new Zend_Form_Element_Select("id_organisme", array(
                    'Organisme' => 'Organisme',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
         # Types de formation 
        $options = array();
        $options[0] = 'tri par type formation';
        foreach ($this->typesFormation as $tf) {
            $options[$tf['id']] = $tf['type_formation'];
        }
        $element = new Zend_Form_Element_Select("id_type", array(
                    'Type formation' => 'Type formation',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
        
        
        $this->addElement('submit', 'submit-name', array(
            'ignore'   => true,
            'value'   => 'Sort',
            'label'    => 'effectuer tri',
            'class' =>'submit-filter'
        ));        

       
    }

}

