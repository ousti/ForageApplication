<?php


class Application_Model_DbTable_Organisme extends Zend_Db_Table_Abstract {

    protected $_name = 'organisme';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_FormationExterne'
                                        );

}

