<?php


class Application_Model_DbTable_Pole extends Zend_Db_Table_Abstract {

    protected $_name = 'pole';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_Direction'
                                       );
    
}

