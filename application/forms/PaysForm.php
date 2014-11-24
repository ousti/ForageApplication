<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Form_PaysForm extends Zend_Form
{
    public function init()
    {
        $pays = $this->createElement("text","pays");
        $pays->setLabel("Pays : *")
                ->setRequired(true);
        
        $submit = $this->createElement("submit","submit");
        $submit->setLabel("enregistrer pays")
                ->setIgnore(true);
                
        $this->addElements(array(
                        $pays,$submit
        ));
    }
}

?>
