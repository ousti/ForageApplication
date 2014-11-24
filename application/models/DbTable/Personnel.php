<?php


class Application_Model_DbTable_Personnel extends Zend_Db_Table_Abstract {

    protected $_name = 'personnel';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_FormationInterne'
                                        );
    

}

