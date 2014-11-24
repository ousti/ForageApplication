<?php


class Application_Model_DbTable_Entite extends Zend_Db_Table_Abstract {

    protected $_name = 'entite';
    protected $_primary = 'id';
    protected $_dependentTables = array(
                                            'Application_Model_DbTable_Budget',
                                            'Application_Model_DbTable_Personnel'
                                        );

}

