<?php


class Application_Model_DbTable_Session extends Zend_Db_Table_Abstract {

    protected $_name = 'session';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_SessionCoutIndirect',
                                        'Application_Model_DbTable_FormationExterne'
                                        );
    
    # Reference Session
    protected $_referenceMap = array(
                'Formation' => array(
                    'columns'           => array('id_formation'),
                    'refTableClass'     => 'Application_Model_DbTable_Formation',
                    'refColumns'        => array('id'),
                    'onDelete'      => self::CASCADE 
                 ),
   );

}

