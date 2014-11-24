<?php


include_once 'Zend_Validate_UniqueRefNo.php';
include_once 'Zend_Validate_IsNumber.php';

class Application_Form_PersonnelForm extends  ZendX_JQuery_Form{
   
  private $id; 
  private $directions;
  private $entites;
  private $categories;

  public function __construct(array $params = array())
  {
    $this->id = $params['id'];
    $this->directions = $params['directions'];
    $this->entites = $params['entites'];
    $this->categories = $params['categories'];
    parent::__construct(); 
  }
    
  
  public function init() {
        
        $this->setMethod("post");
        
        /** Identifiant ***/
        $element = new Zend_Form_Element_Hidden('id', array('value' => $this->id));
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text("matricule", array(
                    "label" => "Matricule",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text("nom", array(
                    "label" => "nom",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
     
        $element = new Zend_Form_Element_Text("prenoms", array(
                    "label" => "Prenoms",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
        
        $element = new Zend_Form_Element_Radio("genre", array(
                    "label" => "Genre",
                   ));
        $element->addMultiOption('Masculin','Homme');
        $element->addMultiOption('Féminin','Femme');
        $element->setSeparator('');
        $element->setRequired(true);
        $this->addElement($element);
        
        
        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                "date_naissance", array("label" => "Date de naissance")
            );
        $elem->setJQueryParam('dateFormat', 'yy-mm-dd');
        $this->addElement($elem);
        
        
        $element = new Zend_Form_Element_Text("nationalite", array(
                    "label" => "Nationalité",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
       
        $element = new Zend_Form_Element_Text("fonction", array(
                    "label" => "fonction",
                   ));
        $element->setRequired(true);
        $this->addElement($element);
        
      
        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                "date_embauche", array("label" => "Date embauche")
            );
        $elem->setJQueryParam('dateFormat', 'yy-mm-dd');
        $this->addElement($elem);
        
        
        $element = new Zend_Form_Element_Text("cnps", array(
                    "label" => "N° CNPS",
                   ));
        $element->setRequired(false);
        $this->addElement($element);
        
        
        
        /**** INIT SELECT 
         ********/

        # Entites 
        $options = array();
        foreach ($this->entites as $entite) {
            $options[$entite['id']] = $entite['entite'];
        }
        $element = new Zend_Form_Element_Select("id_entite", array(
                    'label' => 'Entité',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        

      
        # direction
        $options = array();
        foreach ($this->directions as $direction) {
            $options[$direction['id']] = $direction['direction'];
        }
        $element = new Zend_Form_Element_Select("id_direction", array(
                    'label' => 'Direction salarié',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);
        
        
        # Categories 
        $options = array();
        foreach ($this->categories as $categorie) {
            $options[$categorie['id']] = $categorie['categorie'];
        }
        $element = new Zend_Form_Element_Select("id_categorie", array(
                    'label' => 'Catégorie salarié',
                ));
        $element->setMultiOptions($options);
        $this->addElement($element);

        
        $element = new Zend_Form_Element_Text("superieur_hierarchique", array(
                    "label" => "Matricule Supérieur hiérarchique",
                   ));
        $element->setRequired(false);
        $this->addElement($element);
        
        
        # Salarié externe ou interne
        $options = array(1=>'OCIT', 2=>'Externe');
        $element = new Zend_Form_Element_Select("type_salarie", array(
                    'label' => 'Type salarié',
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

