<?php



class Application_Form_FilterDashboardFormationForm extends ZendX_JQuery_Form  {
   
 
  public function __construct(array $params = array())
  {
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        $this->setName('dashboardForm');
        
        /**** INIT SELECT ********/           
        # Periode
        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                'date_debut', array('label' =>'', 'class'=>'date')
            );
        $elem->setJQueryParam('dateFormat', 'yy-mm');
        $this->addElement($elem);
        
      
        
        $this->addElement('submit', 'submit-name', array(
            'ignore'   => true,
            'value'   => 'Sort',
            'label'    => 'rechercher',
            'class' =>'submit-filter'
        ));        

       
    }

}

