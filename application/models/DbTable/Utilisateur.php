<?php


class Application_Model_DbTable_Utilisateur extends Zend_Db_Table_Abstract {

    protected $_name = 'utilisateur';
    protected $_primary = 'id';

    
    // Accès utilisateur à l'application
    function checkAcces($login)
    {
        $select = $this->_db->select()
                            ->from($this->_name,array('login'))
                            ->where('login = ? ',$login);
        $result = $this->getAdapter()->fetchOne($select);
        if($result){
            return true;
        }
        return false;
    }
    
    
}

