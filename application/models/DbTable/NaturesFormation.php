<?php


class Application_Model_DbTable_NaturesFormation extends Zend_Db_Table_Abstract {

    protected $_name = 'natures_formation';
    protected $_primary = 'id';
     protected $_dependentTables = array('Application_Model_DbTable_Formation'
                                        );
  
}

