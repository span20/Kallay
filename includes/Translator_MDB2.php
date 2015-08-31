<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */

require_once 'PEAR.php';
require_once 'MDB2.php';
require_once 'Translator_XMLParser.php';

/**
 * Translator_MDB2
 *
 * usage:
 *
 * <code>
 * <?php
 *
 * require_once 'PEAR.php';
 * require_once 'Translator.php';
 *
 * $config = array(
 *
 *      // table name settings:
 *      'table_locales'     => 'Locales',             // optional
 *      'table_variables'   => 'Locales_Variables',     // optional
 *      'table_expressions' => 'Locales_Expressions',  // optional
 *      'table_areas'       => 'Locales_Areas',        // optional
 *
 *      // database settings - one of these is required:
 *      'dsn'               => $dsn,         // Database DSN
 * //   'conn'              => &$mdb2,       // or an existing mdb2 connection
 *
 *      // Language settings
 *      'lang'              => 'en',         // Language code
 * //   'lang_name'         => 'english'     // or language name  - optional
 *      'fallback'          => 'hu'          // Fallback language code (optional).
 *
 * );
 *
 * $lang =& Translator_MDB2::factory($config);
 * if (PEAR::isError($lang)) {
 *      die($lang->getMessage());
 * }
 *
 * // Adding new locale:
 * $lang->addLocale('de', 'deutsch');
 *
 * // Adding a new area:
 * $lang->addArea('testarea');
 *
 * // Adding new variable, and expression to the area:
 * $lang->addExpression('hu', 'testarea', 'expression', 'Ez egy teszt mondat.');
 * $lang->addExpression('de', 'testarea', 'expression', 'Das ist ein test Satz');
 *
 *
 * // getting an expression from the object:
 * print $lang->get('testarea', 'expression');
 *
 * // setting a default area:
 * $lang->useArea('testarea');
 * print $lang->get('expression');
 *
 *
 * // Smarty:
 *
 * $tpl =& new Smarty();
 *
 * // ...
 *
 * $lang->initSmarty($tpl);
 * $lang->addExpression('hu', 'testarea', 'smarty_test', 'Ez {$name} tesztje');
 *
 * $tpl->assign('name', 'Tibcsi');
 *
 * print $lang->getBySmarty('testarea', 'smarty_test');
 * // output: Ez Tibcsi tesztje
 *
 * ?>
 * </code>
 *
 * @package
 * @version $id$
 * @copyright 2006 Dolphinet Kft.
 * @author Balogh, Tibor <btibor@dolphinet.hu>
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Translator_MDB2 extends PEAR {

    /**
     * table_locales
     * name of the table containing locale codes, and locale names.
     *
     * @var string
     * @access public
     */
    var $table_locales     = 'Locales';

    /**
     * table_variables
     * name of the table containing variable keys for expressions
     *
     * @var string
     */
    var $table_variables   = 'Locales_Variables';

    /**
     * table_expressions
     * Name of the table containing expressions.
     *
     * @var string
     * @access public
     */
    var $table_expressions = 'Locales_Expressions';

    /**
     * table_areas
     * Name of the table containing site areas
     *
     * @var string
     * @access public
     */
    var $table_areas       = 'Locales_Areas';


    /**
     * active_area
     *
     * @var string
     */
    var $active_area;

    /**
     * smarty
     * Smarty object reference
     *
     * @var mixed
     * @access public
     */
    var $smarty = NULL;

    /**
     * timestamps
     * Timestamps array for smarty resource handler
     * @var mixed
     * @access public
     */
    var $timestamps;

    /**
     * mdb2
     * MDB2 object reference
     *
     * @var mixed
     * @access public
     */
    var $mdb2;

    /**
     * lang
     * Main language code
     *
     * @var string
     * @access public
     */
    var $lang  = 'hu';

    /**
     * locale_data
     * @var array
     */
    var $locale_data;

    /**
     * locales_array
     *
     * @var array
     */
    var $locales_array;

    /**
     * fallback
     * Fallback language code
     * @var string
     * @access public
     */
    var $fallback = '';

    /**
     * expressions
     * stores translated expressions, or templates.
     *
     * @var mixed
     * @access public
     */
    var $expressions;

    /**
     * charsets
     *
     * @var String
     */
    var $charsets = array();

    /**
     * &factory
     * Creates and returns a new Translator object
     *
     * @param mixed $options
     * @access public
     * @return void
     */
    function &factory($options) {
        if (isset($options['dsn'])) {
            $options['conn'] =& MDB2::connect($options['dsn']);
            if (PEAR::isError($options['conn'])) {
                return $options['conn'];
            }
            unset($options['dsn']);
        } elseif (!isset($options['conn'])) {
            return PEAR::RaiseError("Translator::factory - Option 'conn' or 'dsn' must be given in Translator options parameter array");
        }

        $obj =& new Translator_MDB2();
        $obj->init($options);
        return $obj;
    }


    /**
     * init
     * this must be called first by factory method;
     *
     * @param mixed $options
     * @access protected
     * @return void
     */
    function init($options)
    {
        $this->mdb2 =& $options['conn'];
        if (!isset($this->mdb2->extended)) {
            $this->mdb2->loadModule('Extended');
        }
        if (isset($options['table_locales'])) {
            $this->table_locales = $options['table_locales'];
        }
        if (isset($options['table_expressions'])) {
            $this->table_expressions = $options['table_expressions'];
        }
        if (isset($options['table_areas'])) {
            $this->table_areas = $options['table_areas'];
        }
        if (isset($options['table_variables'])) {
            $this->table_variables = $options['table_variables'];
        }

        $this->getLocales();

        if (isset($options['lang'])) {
            $this->lang = $options['lang'];
        } elseif (isset($options['lang_name']) &&
            (($l = $this->getLocaleIDByName($options['lang_name']))!==FALSE)) {
            $this->lang = $l;
        }

        if (isset($options['fallback']) && ($this->lang != $options['fallback'])) {
            $this->fallback = $options['fallback'];
        }
        $this->expressions = array();

    }

    /**
     * addLocale
     *
     * @param mixed $locale_id
     * @param mixed $name
     * @param string $charset
     * @access public
     * @return int
     */
    function addLocale($locale_id, $name, $charset='UTF-8')
    {
        $locales = $this->table_locales;
        $this->getLocales();
        if (!isset($this->locales_array[$locale_id])) {
            $affected = $this->mdb2->exec("INSERT INTO ${locales} VALUES ('${locale_id}', '${name}', '${charset}')");
            return $affected;
        }
        $affected = $this->mdb2->exec("UPDATE ${locales} SET locale_name='${name}', locale_charset='${charset}' WHERE locale_id='${locale_id}'");
        return $affected;
    }


    /**
     * delLocale
     *
     * @param string $locale_id
     * @return int
     */
    function delLocale($locale_id) {
        $locales = $this->table_locales;
        $expressions = $this->table_expressions;
        $affected = $this->mdb2->exec("DELETE FROM ${expressions} WHERE locale_id='${locale_id}'");
        if (PEAR::isError($affected)) {
            return $affected;
        }
        $affected = $this->mdb2->exec("DELETE FROM ${locales} WHERE locale_id='${locale_id}'");
        return $affected;
    }

    /**
     * getLocales
     *
     * @return array
     */
    function &getLocales()
    {
        $locales = $this->table_locales;
        if (!is_array($this->locales_array)) {
            $this->locales_array = array();
            $result =& $this->mdb2->query("SELECT locale_id, locale_name, locale_charset FROM ${locales}");
            if (PEAR::isError($result)) {
                return $result;
            }
            while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
                $this->locales_array[$row['locale_id']] = $row['locale_name'];
                $this->charsets[$row['locale_id']]      = $row['locale_charset'];
            }
            $result->free();
        }
        return $this->locales_array;
    }

    function getCharset($locale_id = NULL) {
        if ($locale_id === NULL && isset($this->charsets[$this->lang])) {
            return $this->charsets[$this->lang];
        }
        if (isset($this->charsets[$locale_id])) {
            return $this->charsets[$locale_id];
        }
        return "UTF-8";
    }

    /**
     * getAreas
     *
     * @return array
     */
    function &getAreas($sortby='', $mode='')
    {
        $areas  =  $this->table_areas;
        $orders = array('area_name', 'area_id');
        $modes  = array('ASC', 'DESC');
        $order  = '';
        if (!empty($sortby) && in_array($sortby, $orders)) {
            $order = "ORDER BY $sortby";
            if (!empty($mode) && in_array(strtoupper($mode), $modes)) {
                $order .= ' '.strtoupper($mode);
            }
        }

        $result =& $this->mdb2->query("SELECT area_id, area_name FROM ${areas} ".$order);
        $rows   = $result->fetchAll();
        return $rows;
    }

    /**
     * getArea
     *
     * @param int $area_id
     * @return array
     */
    function getArea($area_id) {
        $areas = $this->table_areas;
        $result =& $this->mdb2->query("SELECT * FROM ${areas} WHERE area_id=${area_id}");
        return $result->fetchRow(MDB2_FETCHMODE_ASSOC);
    }

    function getAreaByName($area_name) {
        $areas = $this->table_areas;
        $result =& $this->mdb2->query("SELECT * FROM ${areas} WHERE area_name='${area_name}'");
        return $result->fetchRow(MDB2_FETCHMODE_ASSOC);
    }

    /**
     * getLocaleIDByName
     *
     * @param string $name
     * @return string
     */
    function getLocaleIDByName($name)
    {
        $this->getLocales();
        return array_search($name, $this->locales_array);
    }

    /**
     * getLocaleNameByID
     *
     * @param string $locale_id
     * @return string
     */
    function getLocaleNameByID($locale_id) {
        $this->getLocales();
        if (isset($this->locales_array[$locale_id])) {
            return $this->locales_array[$locale_id];
        }
        return '';
    }

    function getFallback() {
        return empty($this->fallback) ? $this->lang : $this->fallback;
    }

    /**
     * addArea
     *
     * @param mixed $area_name
     * @access public
     * @return void
     */
    function addArea($area_name)
    {
        $areas = $this->table_areas;
        $res =& $this->mdb2->query("SELECT * FROM ${areas} WHERE area_name='$area_name'");
        if (!$row = $res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            $id    = $this->mdb2->extended->getBeforeID($areas, 'area_id', TRUE, TRUE);
            if (PEAR::isError($id)) {
                return $id;
            }
            $affected = $this->mdb2->exec("INSERT INTO ${areas} (area_id, area_name) VALUES ($id, '$area_name')");
            $id       = $this->mdb2->extended->getAfterID($id, $areas, 'area_id');
            return $id;
        }
        return FALSE;
    }

    /**
     * modArea
     *
     * @param mixed $area_id
     * @param mixed $area_name
     * @access public
     * @return void
     */
    function modArea($area_id, $area_name)
    {
        $areas  = $this->table_areas;
        $query = "UPDATE ${areas} SET area_name='${area_name}' WHERE area_id=${area_id}";
        return $this->mdb2->exec($query);
    }

    /**
     * delArea
     *
     * @param int $area_id
     */
    function delArea($area_id)
    {
        $areas          = $this->table_areas;
        $expressions    = $this->table_expressions;
        $variables      = $this->table_variables;
        $this->mdb2->exec("DELETE FROM ${expressions} WHERE area_id=${area_id}");
        $this->mdb2->exec("DELETE FROM ${variables} WHERE area_id=${area_id}");
        $this->mdb2->exec("DELETE FROM ${areas} WHERE area_id=${area_id}");
    }

    /**
     * getVariable
     *
     * @param int $variable_id
     * @return array
     */
    function getVariable($variable_id) {
        $variables = $this->table_variables;
        $result =& $this->mdb2->query("SELECT * FROM ${variables} WHERE variable_id=${variable_id}");
        return $result->fetchRow(MDB2_FETCHMODE_ASSOC);
    }

    /**
     * delVariable
     *
     * @param int $variable_id
     */
    function delVariable($variable_id) {
        $variables   = $this->table_variables;
        $expressions = $this->table_expressions;
        $this->mdb2->exec("DELETE FROM ${expressions} WHERE variable_id=${variable_id}");
        $this->mdb2->exec("DELETE FROM ${variables} WHERE variable_id=${variable_id}");
    }


    /**
     * _expressionExists
     *
     * @param mixed $locale_id
     * @param mixed $area_id
     * @param mixed $expr_code
     * @access protected
     * @return void
     */
    function _expressionExists($locale_id, $area_id, $variable_id)
    {
        $expressions = $this->table_expressions;
        $query = "
            SELECT variable_id
            FROM ${expressions}
            WHERE locale_id='${locale_id}' AND area_id=${area_id} AND variable_id='${variable_id}'
        ";
        $result =& $this->mdb2->query($query);
        if (PEAR::isError($result)) {
            return FALSE;
        }
        return ($result->numRows()>0);
    }

    function _variableExists($area_id, $variable_name)
    {
        $variables = $this->table_variables;
        $result = $this->mdb2->query(
            "SELECT variable_id, variable_name FROM ${variables} WHERE area_id=$area_id AND variable_name='${variable_name}'"
        );
        if ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            return $row['variable_id'];
        }
        return FALSE;
    }

    /**
     * variableExists
     *
     * @param unknown_type $area
     * @param unknown_type $expr
     * @return unknown
     */
    function variableExists($area, $expr = NULL) {
    	if ($expr === NULL) {
            if (empty($this->active_area)) {
                return FALSE;
            }
            $expr = $area;
            $area = $this->active_area;
        }
        if (!isset($this->expressions[$area])) {
            $this->initArea($area);
        }
        return isset($this->expressions[$area][$expr]);
    }

    /**
     * addExpression
     * Inserts an expression into the expressions table;
     *
     * @param mixed $locale_id
     * @param mixed $area_name
     * @param mixed $variable_name
     * @param mixed $expression
     * @access public
     * @return void
     */
    function addExpression($locale_id, $area_name, $variable_name, $expression)
    {
        $expressions = $this->table_expressions;
        $variables   = $this->table_variables;
        $areas       = $this->table_areas;
        $result =& $this->mdb2->query("SELECT area_id, area_name FROM ${areas} WHERE area_name='$area_name'");
        if (!$area = $result->fetchRow()) {
            return PEAR::raiseError("Translator::saveExpression cannot find the requested area");
        }
        $area_id = $area['area_id'];
        if (!$variable_id=$this->_variableExists($area['area_id'], $variable_name)) {
            $variable_id = $this->mdb2->extended->getBeforeID($variables, 'variable_id', TRUE, TRUE);
            $this->mdb2->exec("INSERT INTO ${variables} (variable_id, area_id, variable_name) VALUES (${variable_id}, ${area_id}, '${variable_name}')");
            $variable_id=$this->mdb2->extended->getAfterID($variable_id, $variables, 'variable_id');
        }
        if ($this->_expressionExists($locale_id, $area_id, $variable_id)) {
            $query = "
                UPDATE ${expressions} SET expression='${expression}', smarty_timestamp=".time()."
                WHERE locale_id='${locale_id}' AND area_id=${area_id} AND variable_id=${variable_id}
            ";
        } else {
            $query = "
                INSERT INTO ${expressions} (locale_id, area_id, variable_id, expression, smarty_timestamp)
                VALUES ('${locale_id}', $area_id, '${variable_id}', '${expression}', ".time().")
                ";
        }
        return $this->mdb2->exec($query);
    }

    /**
     * getExpression
     *
     * @param String $locale_id
     * @param int $variable_id
     * @return string
     */
    function getExpression($locale_id, $variable_id) {
        $expressions = $this->table_expressions;
        $result = $this->mdb2->query("SELECT expression FROM ${expressions} WHERE locale_id='${locale_id}' AND variable_id=${variable_id}");
        if ($row = $result->fetchRow()) {
            return $row['expression'];
        }
        return '';
    }

    /**
     * getExpressions
     *
     * @param String $locale_id
     * @param int $area_id
     * @return array
     */
    function &getExpressions($locale_id, $area_id)
    {
        $expressions = $this->table_expressions;
        $variables   = $this->table_variables;
        $result =& $this->mdb2->query("
            SELECT V.variable_name as variable_name, V.variable_id as variable_id, E.expression AS expression
            FROM ${variables} V
            LEFT JOIN ${expressions} E ON E.locale_id='${locale_id}' AND E.variable_id=V.variable_id
            WHERE V.area_id=${area_id}
            ORDER BY V.variable_name
        ");
        $rows = $result->fetchAll();
        $result->free();
        return $rows;
    }


    /**
     * &initArea
     * Fetches all the expressions assigned by $area_name into the $expressions array.
     *
     * @param mixed $area_name
     * @access public
     * @return void
     */
    function &initArea($area_name) {
        $expressions    = $this->table_expressions;
        $locales        = $this->table_locales;
        $variables      = $this->table_variables;
        $locale_id      = $this->lang;
        $areas          = $this->table_areas;
        $fallback_query = '';
        if (!empty($this->fallback)) {
            $fallback_lang = $this->fallback;
            $fallback_query = "
                UNION
                (SELECT V.variable_name as variable_name,
                        E1.expression as expression,
                        E1.smarty_timestamp as smarty_timestamp
                FROM ${areas} A, ${variables} V, ${expressions} E1
                LEFT JOIN ${expressions} E2 ON
                    E2.locale_id='${locale_id}' AND
                    E1.area_id=E2.area_id AND
                    E1.variable_id=E2.variable_id
                WHERE
                    V.variable_id=E1.variable_id AND
                    A.area_name = '${area_name}' AND
                    E1.area_id = A.area_id AND
                    E1.locale_id = '${fallback_lang}' AND
                    E2.variable_id IS NULL)
            ";
        }
        $query = "
            SELECT variable_name, expression, smarty_timestamp
            FROM ${expressions} E, ${areas} A, ${variables} V
            WHERE
                V.variable_id=E.variable_id AND
                A.area_name = '${area_name}' AND
                A.area_id=V.area_id AND
                E.locale_id='${locale_id}' ${fallback_query}
        ";
        $result =& $this->mdb2->query($query);
        if (PEAR::isError($result)) {
            return $result;
        }
        while ($row = $result->fetchRow()) {
            $this->expressions[$area_name][$row['variable_name']] = $row['expression'];
            $this->timestamps[$area_name][$row['variable_name']] = $row['smarty_timestamp'];
        }
        $result->free();
        return $this->expressions;
    }

    /**
     * useArea
     *
     * @param string $area_name
     */
    function useArea($area_name)
    {
        if (!isset($this->expressions[$area_name])) {
            $this->initArea($area_name);
            if (!isset($this->expressions[$area_name])) {
                return;
            }
        }
        $this->active_area = $area_name;
    }

    /**
     * get
     * Gets an expression, or smarty timestamp from the database by $area, and $expr code.
     *
     * @param mixed $area
     * @param mixed $expr
     * @param string $type
     * @access public
     * @return void
     */
    function get($area, $expr=NULL, $type="expression")
    {
        if ($expr === NULL) {
            if (empty($this->active_area)) {
                return PEAR::raiseError("Translator::get - wrong parameters");
            }
            $expr = $area;
            $area = $this->active_area;
        }
        if (!isset($this->expressions[$area])) {
            $this->initArea($area);
        }
        if (isset($this->expressions[$area][$expr])) {
            switch ($type) {
                case "expression":
                    return $this->expressions[$area][$expr];
                    break;
                case "timestamp":
                    return $this->timestamps[$area][$expr];
                    break;
            }
        }
        return $type == 'expression' ? $expr : time();
    }

    /**
     * getBySmarty
     * fetch an expression as a template with smarty->fetch
     *
     * @param mixed $area
     * @param mixed $expr
     * @access public
     * @return void
     */
    function getBySmarty($area, $expr=NULL)
    {
        if ($expr == NULL) {
            if (empty($this->active_area)) {
                return PEAR::raiseError("Translator::getBySmarty - wrong parameters");
            }
            $expr = $area;
            $area = $this->active_area;
        }
        if ($this->smarty === NULL) {
            return PEAR::raiseError('Translator::getBySmarty - initSmarty method must be called first');
        }
        return $this->smarty->fetch("translator:${area}/${expr}");
    }

    /**
     * _tr_get_template
     * Smarty repository - get an expression like a smarty template
     *
     * @param mixed $tpl_name
     * @param mixed $tpl_source
     * @param mixed $smarty_obj
     * @access protected
     * @return void
     */
    function _tr_get_template($tpl_name, &$tpl_source, &$smarty_obj)
    {
        if (!preg_match('|^([^/]+)/([^$]+)$|', $tpl_name, $matches)) {
            return FALSE;
        }
        $area       = $matches[1];
        $expr_code  = $matches[2];
        $tpl_source = $this->get($area, $expr_code);
        return TRUE;
    }

    /**
     * _tr_get_timestamp
     * Gets an expression's smarty timestamp
     *
     * @param mixed $tpl_name
     * @param mixed $tpl_timestamp
     * @param mixed $smarty_obj
     * @access protected
     * @return void
     */
    function _tr_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj)
    {
        if (!preg_match('|^([^/]+)/([^$]+)$|', $tpl_name, $matches)) {
            return FALSE;
        }
        $area       = $matches[1];
        $expr_code  = $matches[2];
        $tpl_timestamp = $this->get($area, $expr_code, 'timestamp');
        return TRUE;
    }

    /**
     * _tr_get_secure
     * is the given template secure or not
     *
     * @param mixed $tpl_name
     * @param mixed $smarty_obj
     * @access protected
     * @return void
     */
    function _tr_get_secure($tpl_name, &$smarty_obj)
    {
        // assume all templates are secure
        return TRUE;
    }

    /**
     * _tr_get_trusted
     * is the given template trusted?
     *
     * @param mixed $tpl_name
     * @param mixed $smarty_obj
     * @access protected
     * @return void
     */
    function _tr_get_trusted($tpl_name, &$smarty_obj)
    {
        // not used for templates
    }

    function initSmarty(&$smarty)
    {
        $this->smarty =& $smarty;
        $this->smarty->register_resource("translator", array(
            array(&$this, "_tr_get_template"),
            array(&$this, "_tr_get_timestamp"),
            array(&$this, "_tr_get_secure"),
            array(&$this, "_tr_get_trusted")));
    }


    function assignToSmarty($name)
    {
        if ($this->smarty === NULL) {
            return PEAR::raiseError('Translator::assignToSmarty - initSmarty method must be called first');
        }
        $this->smarty->assign($name, $this->expressions);
        $this->smarty->assign($name.'_charset', $this->getCharset());
        return TRUE;
    }


    function parseXML($fileName)
    {
        $parser =& new Translator_XMLParser($this);
        $parser->setInputFile($fileName);
        $success = $parser->parse();
        return $success;
    }

}


?>
