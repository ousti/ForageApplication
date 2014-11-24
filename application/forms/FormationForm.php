<?php


include_once 'Zend_Validate_UniqueRefNo.php';
include_once 'Zend_Validate_IsNumber.php';

class Application_Form_FormationForm extends Zend_Form {
   
  private $id; 
  private $directions;
  private $natures;
  private $types;
  private $pays;
  private $domaines;


  public function __construct(array $params = array())
  {
    $this->id = $params['id'];
    $this->directions = $params['directions'];
    $this->natures = $params['natures'];
    $this->types = $params['types'];
    $this->pays = $params['pays'];
    $this->domaines = $params['domaines'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        /** Identifiant ***/
        $element = new Zend_Form_Element_Hidden('id', array('value' => $this->id));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text("intitule", array(
                    "label" => "Intitule de la formation",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
        
        $element = new Zend_Form_Element_Text("objectif", array(
                    "label" => "Objectifs pedagogique",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
      
        
        
        /**** INIT SELECT 
         ********/
        
      
        # direction
        $options = array();
        foreach ($this->directions as $direction) {
            $options[$direction['id']] = $direction['direction'];
        }
        $element = new Zend_Form_Element_Select("id_direction", array(
                    'label' => 'Selectionner la direction',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
        # nature 
        $options = array();
        foreach ($this->natures as $nature) {
            $options[$nature['id']] = $nature['nature_formation'];
        }
        $element = new Zend_Form_Element_Select("id_nature", array(
                    'label' => 'Nature de la formation',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        # Domaine 
        $options = array();
        foreach ($this->domaines as $domaine) {
            $options[$domaine['id']] = $domaine['domaine_formation'];
        }
        $element = new Zend_Form_Element_Select("id_domaine", array(
                    'label' => 'Domaine formation',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        # type 
        $options = array();
        foreach ($this->types as $type) {
            $options[$type['id']] = $type['type_formation'];
        }
        $element = new Zend_Form_Element_Select("id_type", array(
                    'label' => 'Type de la formation',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
         # Type de formateur
        $options = array(0=>'Formateur interne', 1=>'Cabinet de formation');
        $element = new Zend_Form_Element_Select("type_formateur", array(
                    'label' => 'Type formateur',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
       
        # lieux de formation
        $options = array();
        foreach ($this->pays as $p) {
            $options[$p['id']] = $p['pays'];
        }
        $element = new Zend_Form_Element_Select("id_pays", array(
                    'label' => 'Lieu de formation',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
       
         
        # Statut de la formation
        $options = array(0=>'En attente', -1=>'En cours', 1=>'Achevé');
        $element = new Zend_Form_Element_Select("statut", array(
                    'label' => 'Niveau d\'éxécution',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
           
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'value'   => 'Save',
            'label'    => 'enregistrer',
        ));        

        /*
        $this->setMethod("post");

        $element = new Zend_Form_Element_Text("reference_no", array(
                    "label" => "Ref. No",
                ));
        $element->addValidator(new Zend_Validate_UniqueRefNo(), true);
        $element->setRequired(true);
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $this->addElement($element);


        $element = new Zend_Form_Element_Text("price", array(
                    "label" => "Price",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->setRequired(true);
        $element->addValidator(new Zend_Validate_IsNumber(), true);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("title", array(
                    "label" => "Title",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->setRequired(true);
        $this->addElement($element);


        $locations = new Application_Model_PropertyLocationMapper();
        foreach ($locations->fetchAll() as $value) {
            $options[$value->getId()] = $value->getCity() . " - " . $value->getCityPart();
        }

        $options = array();
        $type = new Application_Model_PropertyBuildTypeMapper();
        $options[''] = '';
        foreach ($type->fetchAll() as $value) {
            $options[$value->getId()] = $value->getText();
        }
        $element = new Zend_Form_Element_Select("property_build_id", array(
                    'label' => 'Building type',
                ));
        $element->setMultiOptions($options);
        $element->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => 'formElements/property/_select.phtml'
                )
            )
        ));

        $element->setRequired(true);
        $element->addValidator(new Zend_Validate_NotEmpty());
        $this->addElement($element);

        $options = array();

        for ($i = 0; $i < 25; $i++) {
            $options[$i] = $i;
        }
        $element = new Zend_Form_Element_Select("floor", array(
                    'label' => 'Floor',
                ));
        $element->setMultiOptions($options);
        $element->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => 'formElements/property/_select.phtml'
                )
            )
        ));

        $element->setRequired(true);
        $element->addValidator(new Zend_Validate_NotEmpty());
        $this->addElement($element);

        $options = array();
        $dis = new Application_Model_DispositionMapper();
        $options[''] = '';
        foreach ($dis->fetchAll() as $value) {
            $options[$value->getId()] = $value->getText();
        }
        $element = new Zend_Form_Element_Select("disposition_id", array(
                    'label' => 'Disposition',
                ));
        $element->setMultiOptions($options);
        $element->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => 'formElements/property/_select.phtml'
                )
            )
        ));
        $element->setRequired(true);
        $element->addValidator(new Zend_Validate_NotEmpty());
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("area", array(
                    "label" => "Area (m&sup2;)",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->addValidator(new Zend_Validate_IsNumber(), true);
        $this->addElement($element);


        $element = new Zend_Form_Element_Text("cellar", array(
                    "label" => "Cellar (m&sup2;)",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->addValidator(new Zend_Validate_IsNumber(), true);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("balcony", array(
                    "label" => "Balcony (m&sup2;)",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->addValidator(new Zend_Validate_IsNumber(), true);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("terace", array(
                    "label" => "Terrace (m&sup2;)",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->addValidator(new Zend_Validate_IsNumber(), true);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("loggia", array(
                    "label" => "Loggia (m&sup2;)",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->addValidator(new Zend_Validate_IsNumber(), true);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("garage", array(
                    "label" => "Garage (m&sup2;)",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->addValidator(new Zend_Validate_IsNumber(), true);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("garden", array(
                    "label" => "Garden (m&sup2;)",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $element->addValidator(new Zend_Validate_IsNumber(), true);
        $this->addElement($element);

        $element = new Zend_Form_Element_Checkbox("lift", array(
                    "label" => "Lift"
                ));
        $this->addElement($element);

        $element = new Zend_Form_Element_Checkbox("parking_place", array(
                    "label" => "Parking place:"
                ));
        $this->addElement($element);


        $options = array();
        $dis = new Application_Model_PropertyLocationMapper();
        $options[''] = '';
        foreach ($dis->fetchAll() as $value) {
            $options[$value->getId()] = $value->getCity() . " - " . $value->getCityPart();
        }
        $element = new Zend_Form_Element_Select("location_id", array(
                    'label' => 'Location',
                ));
        $element->setMultiOptions($options);
        $element->setDecorators(array(
            array('ViewScript', array(
                    'viewScript' => 'formElements/property/_select.phtml'
                )
            )
        ));

        $element->setRequired(true);
        $element->addValidator(new Zend_Validate_NotEmpty());
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("street", array(
                    "label" => "Address",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textInput.phtml'
                )
            )
        ));
        $this->addElement($element);

        $element = new Zend_Form_Element_Text("c1", array());
        $element->setDecorators(array(array('ViewScript', array('viewScript' => 'formElements/property/_clear.phtml'))));
        $this->addElement($element);


        $element = new Zend_Form_Element_Textarea("text", array(
                    "label" => "Description",
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_textArea.phtml'
                )
            )
        ));
        $element->setRequired(true);
        $this->addElement($element);


        $element = new Zend_Form_Element_Submit("submit", array(
                    "value" => "Save",
                    "class" => "button"
                ));
        $element->setDecorators(array(
            array('ViewScript',
                array(
                    'viewScript' => 'formElements/property/_submit.phtml'
                )
            )
        ));
        $this->addElement($element);
         
         */
    }

}

