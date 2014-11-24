<?php

include_once 'Zend_Validate_UniqueRefNo.php';
include_once 'Zend_Validate_IsNumber.php';

class Application_Form_AuditeurForm extends Zend_Form {
   
  private $nb;
  private $id_session;
  private $id_formation;

  
  public function __construct(array $params = array())
  {
    $this->id_session = $params['id_session'];
    $this->id_formation = $params['id_formation'];
    $this->nb = $params['nb'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        for($k=0;$k<$this->nb; $k++) : 
            $element = new Zend_Form_Element_Text("matricule_".$k, array(
                        "label" => "Matricule Auditeur ".($k+1),
                    ));
            $element->setRequired(false);
            $this->addElement($element);
        endfor;
        
       $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Save',
            'label'    => 'enregistrer auditeurs',
            'class'    => 'input-submit'
        ));        

        
    }

}

