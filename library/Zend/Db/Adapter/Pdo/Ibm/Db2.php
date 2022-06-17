<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/** @see Zend_Db_Adapter_Pdo_Ibm */
require_once 'Zend/Db/Adapter/Pdo/Ibm.php';

/** @see Zend_Db_Statement_Pdo_Ibm */
require_once 'Zend/Db/Statement/Pdo/Ibm.php';


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Db_Adapter_Pdo_Ibm_Db2
{
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_adapter = null;
    protected $_typConnex = '';

    /**
     * Construct the data server class.
     *
     * It will be used to generate non-generic SQL
     * for a particular data server
     *
     * @param Zend_Db_Adapter_Abstract $adapter
     */
    public function __construct($adapter, $typConnex="")
    {
        $this->_adapter = $adapter;
        $this->_typConnex = $typConnex;
    }

    /**
     * Returns a list of the tables in the database.
     *
     * @return array
     */
    public function listTables()
    {
        $sql = "SELECT tabname "
        . "FROM SYSCAT.TABLES ";
        return $this->_adapter->fetchCol($sql);
    }

    /**
     * DB2 catalog lookup for describe table
     *
     * @param string $tableName
     * @param string $schemaName OPTIONAL
     * @return array
     */
    public function describeTable($tableName, $schemaName = null)
    {
        // Ensure the connection is made so that _isI5 is set
        //$this->_connect();

        /*
         * patch - Begin
         * patch proposé sur : http://framework.zend.com/issues/browse/ZF-10415
         * (suppression des fonctions UPPER à l'intérieur du code SQL, et
         * utilisation des fonction mb_strtoupper en amont)
         * + mise en variable de la clause DISTINCT qui est inutile si
         * le nom de la base est connu
        */
        $tableName = mb_strtoupper($tableName) ;
        if ($schemaName !== null) {
            $schemaName =  mb_strtoupper($schemaName);
            $distinct = '' ;
        } else {
            $distinct = 'DISTINCT' ;
        }
        $bind_params = [];
        if ($this->_typConnex == 'odbc') {
            // DB2 for i specific query
            $sql = "SELECT {$distinct} C.TABLE_SCHEMA, C.TABLE_NAME, C.COLUMN_NAME, C.ORDINAL_POSITION,
                C.DATA_TYPE, C.COLUMN_DEFAULT, C.NULLS ,C.LENGTH, C.SCALE, LEFT(C.IDENTITY, 1) as identity,
                LEFT(tc.TYPE, 1) AS tabconsttype, k.COLSEQ
                FROM QSYS2.SYSCOLUMNS C
                LEFT JOIN (QSYS2.syskeycst k JOIN QSYS2.SYSCST tc
                    ON (k.TABLE_SCHEMA = tc.TABLE_SCHEMA
                      AND k.TABLE_NAME = tc.TABLE_NAME
                      AND LEFT(tc.type, 1) = 'P'))
                    ON (C.TABLE_SCHEMA = k.TABLE_SCHEMA
                       AND C.TABLE_NAME = k.TABLE_NAME
                       AND C.COLUMN_NAME = k.COLUMN_NAME)
                WHERE C.TABLE_NAME = ?";
            $bind_params[]= $tableName;
            if ($schemaName) {
                $sql .= ' AND C.TABLE_SCHEMA = ?';
                $bind_params[]= $schemaName;
            }
            $sql .= " ORDER BY C.ORDINAL_POSITION FOR FETCH ONLY";
        } else {
            $sql = "SELECT {$distinct} c.tabschema, c.tabname, c.colname, c.colno,
            c.typename, c.default, c.nulls, c.length, c.scale,
            c.identity, tc.type AS tabconsttype, k.colseq
            FROM syscat.columns c
            LEFT JOIN (syscat.keycoluse k JOIN syscat.tabconst tc
             ON (k.tabschema = tc.tabschema
               AND k.tabname = tc.tabname
               AND tc.type = 'P'))
             ON (c.tabschema = k.tabschema
             AND c.tabname = k.tabname
             AND c.colname = k.colname)
                WHERE c.tabname = ?";
                $bind_params[]= $tableName;
            if ($schemaName) {
                $sql .= ' AND c.tabschema = ?';
                $bind_params[]= $schemaName;
            }
        }

        /*
         * patch GJARRIGE - End
        */
        $desc = [];
        $stmt = $this->_adapter->query($sql, $bind_params);

        /**
         * To avoid case issues, fetch using FETCH_NUM
         */
        $result = $stmt->fetchAll(Zend_Db::FETCH_NUM);

        /**
         * The ordering of columns is defined by the query so we can map
         * to variables to improve readability
         */
        $tabschema      = 0;
        $tabname        = 1;
        $colname        = 2;
        $colno          = 3;
        $typename       = 4;
        $default        = 5;
        $nulls          = 6;
        $length         = 7;
        $scale          = 8;
        $identityCol    = 9;
        $tabconstype    = 10;
        $colseq         = 11;

        foreach ($result as $key => $row) {
            [$primary, $primaryPosition, $identity] = [false, null, false];
            if ($row[$tabconstype] == 'P') {
                $primary = true;
                $primaryPosition = $row[$colseq];
            }
            /**
             * In IBM DB2, an column can be IDENTITY
             * even if it is not part of the PRIMARY KEY.
             */
            if ($row[$identityCol] == 'Y') {
                $identity = true;
            }

            $desc[$this->_adapter->foldCase($row[$colname])] = [
            'SCHEMA_NAME'      => $this->_adapter->foldCase($row[$tabschema]),
            'TABLE_NAME'       => $this->_adapter->foldCase($row[$tabname]),
            'COLUMN_NAME'      => $this->_adapter->foldCase($row[$colname]),
            'COLUMN_POSITION'  => $row[$colno]+1,
            'DATA_TYPE'        => $row[$typename],
            'DEFAULT'          => $row[$default],
            'NULLABLE'         => (bool) ($row[$nulls] == 'Y'),
            'LENGTH'           => $row[$length],
            'SCALE'            => $row[$scale],
            'PRECISION'        => ($row[$typename] == 'DECIMAL' ? $row[$length] : 0),
            'UNSIGNED'         => false,
            'PRIMARY'          => $primary,
            'PRIMARY_POSITION' => $primaryPosition,
            'IDENTITY'         => $identity
            ];
        }

        return $desc;
    }

    /**
     * Adds a DB2-specific LIMIT clause to the SELECT statement.
     *
     * @param string $sql
     * @param integer $count
     * @param integer $offset OPTIONAL
     * @throws Zend_Db_Adapter_Exception
     * @return string
     */
    public function limit($sql, $count, $offset = 0)
    {
        $count = (int)$count;
        if ($count < 0) {
            /** @see Zend_Db_Adapter_Exception */
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument count=$count is not valid");
        }

        $offset = (int)$offset;

        if ($offset < 0) {
            /** @see Zend_Db_Adapter_Exception */
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument offset=$offset is not valid");
        }

        if ($offset === 0 && $count > 0) {
            return $sql . " FETCH FIRST $count ROWS ONLY";
        }

        /**
         * DB2 does not implement the LIMIT clause as some RDBMS do.
         * We have to simulate it with subqueries and ROWNUM.
         * Unfortunately because we use the column wildcard "*",
         * this puts an extra column into the query result set.
         */
        $limit_sql = "SELECT z2.*
          FROM (
              SELECT ROW_NUMBER() OVER() AS \"ZEND_DB_ROWNUM\", z1.*
              FROM (
                  " . $sql . "
              ) z1
          ) z2
          WHERE z2.zend_db_rownum BETWEEN " . ($offset+1) . " AND " . ($offset+$count);

        return $limit_sql;
    }

    /**
     * DB2-specific last sequence id
     *
     * @param string $sequenceName
     * @return string
     */
    public function lastSequenceId($sequenceName)
    {
        $sql = 'SELECT PREVVAL FOR '.$this->_adapter->quoteIdentifier($sequenceName).' AS VAL FROM SYSIBM.SYSDUMMY1';

        return $this->_adapter->fetchOne($sql);
    }

    /**
     * DB2-specific sequence id value
     *
     *  @param string $sequenceName
     *  @return string
     */
    public function nextSequenceId($sequenceName)
    {
        $sql = 'SELECT NEXTVAL FOR '.$this->_adapter->quoteIdentifier($sequenceName).' AS VAL FROM SYSIBM.SYSDUMMY1';

        return $this->_adapter->fetchOne($sql);
    }
}
