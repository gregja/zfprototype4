<?php
class PrmPersonne extends Zend_Db_Table {
    protected $_name   = 'prm_personne';
    protected $_schema = '';

    function __construct($config = [], $definition = null) {
        $this->_schema = Zend_Registry::getInstance()->CURRENT_DB;
        parent::__construct($config, $definition);
    }

}
