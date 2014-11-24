<?php


class Application_Model_DbTable_DomainesFormation extends Zend_Db_Table_Abstract {

    protected $_name = 'domaines_formation';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_Formation'
                                        );
    
    
}

