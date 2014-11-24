<?php


class Application_Model_DbTable_TypesFormation extends Zend_Db_Table_Abstract {

    protected $_name = 'types_formation';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_Formation'
                                        );

}

