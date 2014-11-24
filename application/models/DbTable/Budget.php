<?php


class Application_Model_DbTable_Budget extends Zend_Db_Table_Abstract {

    protected $_name = 'budget';
    protected $_primary = array('id');
    
     protected $_referenceMap    = array(
        'Exercice' => array(
            'columns'           => array('id_exercice'),
            'refTableClass'     => 'Application_Model_DbTable_Exercice',
            'refColumns'        => array('id')
        ),
        'Entite' => array(
            'columns'           => array('id_entite'),
            'refTableClass'     => 'Application_Model_DbTable_Entite',
            'refColumns'        => array('id')
        )
    );

}

