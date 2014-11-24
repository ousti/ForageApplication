<?php


class Application_Model_DbTable_Exercice extends Zend_Db_Table_Abstract {

    protected $_name = 'exercice';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_Budget'
                                        );

}

