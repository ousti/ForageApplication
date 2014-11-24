<?php



class Application_Form_FilterReportingFormationForm extends ZendX_JQuery_Form  {
   
  private $directions;
  private $poles;
  private $entites;
  private $domaines;
 


  public function __construct(array $params = array())
  {
    $this->directions = $params['directions'];
    $this->poles = $params['poles'];
    $this->entites = $params['entites'];
    $this->domaines = $params['domaines'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        $this->setName('reportingForm');
        
        /**** INIT SELECT ********/           
        # Periode
        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                'date_debut', array('label' =>'', 'class'=>'date')
            );
        $elem->setJQueryParam('dateFormat', 'yy-mm');
        $this->addElement($elem);
        
        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                'date_fin', array('label' =>'', 'class'=>'date')
            );
        $elem->setJQueryParam('dateFormat', 'yy-mm');
        $this->addElement($elem);
        
        
        
        # entites 
        $options = array();
        $options[0] = 'tri par entité';
        foreach ($this->entites as $entite) {
            $options[$entite['id']] = $entite['entite'];
        }
        $element = new Zend_Form_Element_Select("id_entite", array(
                    'Entité' => 'entite',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
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
        
        
        # domaine
        $options = array();
        $options[0] = 'tri par domaine';
        foreach ($this->domaines as $domaine) {
            $options[$domaine['id']] = $domaine['domaine_formation'];
        }
        $element = new Zend_Form_Element_Select("id_domaine", array(
                    'label' => '',
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

