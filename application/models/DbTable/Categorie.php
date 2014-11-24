<?php


class Application_Model_DbTable_Categorie extends Zend_Db_Table_Abstract {

    protected $_name = 'categorie';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_Personnel'
                                        );

}

