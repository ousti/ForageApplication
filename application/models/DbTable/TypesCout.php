<?php


class Application_Model_DbTable_TypesCout extends Zend_Db_Table_Abstract {

    protected $_name = 'types_cout';
    protected $_primary = array('id');
    protected $_dependentTables = array('Application_Model_DbTable_FormationCouts');
    
     
}

