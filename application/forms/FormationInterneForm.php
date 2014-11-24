<?php


class Application_Form_FormationInterneForm extends Zend_Form {
   
    
  private $formateurs;
  private $id_session;
  private $op;
  private $id_formateur_old;



  public function __construct(array $params = array())
  {
    $this->id_session = $params['id_session'];
    $this->formateurs = $params['formateur'];
    $this->op = $params['op'];
    if($this->op=='edit')
        $this->id_formateur_old = $params['id_formateur_old'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
                    
        /**** INIT SELECT 
         ********/
        
        # operation 
        $element = new Zend_Form_Element_Hidden('op', array('value' => $this->op));
        $this->addElement($element);

        # formation 
        $element = new Zend_Form_Element_Hidden('id_session', array('value' => $this->id_session));
        $this->addElement($element);
        
        if($this->op=='edit') :
        $element = new Zend_Form_Element_Hidden('id_formateur_old', array('value' => $this->id_formateur_old));
        $this->addElement($element);
        endif;
        
        # formateurs
        $options = array();
        foreach ($this->formateurs as $c) {
            $options[$c['id']] = trim($c['nom']).' '.trim($c['prenoms']).' ('.$c['matricule'].')';
        }
        $element = new Zend_Form_Element_Select("id_personnel", array(
                    'label' => 'Selectionner le formateur',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);

        
        $element = new Zend_Form_Element_Text("montant_horaire", array(
                    "label" => "Montant Horaire",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Save',
            'label'    => 'enregistrer formateur interne',
            'class'    => 'input-submit'
        ));        

        
    }

}

