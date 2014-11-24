<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Form_FilterDataByExerciceForm extends Zend_Form
{
    
  private $startYear;
  private $endYear;
  
  
  public function __construct(array $params = array())
  {
    $this->startYear = $params['startYear'];
    echo $this->endYear = date('Y');
    parent::__construct(); 
  }
  
  
    public function init()
    {
        
        # Année en cours
        for($i=$this->endYear; $i>=$this->startYear; $i--)
            $options[$i] = $i;
        
        //$options = array(2013=>'2013',2014=>'2014');
        $element = new Zend_Form_Element_Select("exercice", array(
                    'label' => 'Exercice',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
                
        $this->addElements(array(
                        $element,
                        
        ));
    }
}

?>
