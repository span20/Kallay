<?
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */


/**
 * iShark classes 
 *
 * Minden olyan osztaly, amely minden oldal betoltodesehez szukseges.
 */


/**
 * Breadcrumb - eleresi utat tarolo kontener osztaly 
 * 
 * @package 
 * @version $id$
 * @copyright 2006 Dolphinet Kft.
 * @author Balogh, Tibor <btibor@dolphinet.hu> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Breadcrumb {
    var 
        /**
         * Eleresi utat tartalmazo tomb. 
         */
        $path;

    /**
     * Constructor 
     * 
     * @access public
     * @return void
     */
    function Breadcrumb()
    {
        $this->path = array();
    }

    /**
     * add - Uj szint hozzaadasa a kontenerhez 
     * 
     * @param mixed $title 
     * @param mixed $link 
     * @access public
     * @return void
     */
    function add($title, $link)
    {
        $this->path[] = array('title' => $title, 'link' => $link);
    }

    /**
     * insertBefore - Uj szint beszurasa a kontener elejere
     *
     * @param mixed $title
     * @param mixed $link
     * @access public
     * @return void
     */
    function insertBefore($title, $link) {
        array_unshift($this->path, array('title' => $title, 'link' => $link));
    }
    
    /**
     * getArray - visszaadja a tarolt tombot smarty reszere 
     * 
     * @access public
     * @return array
     */
    function getArray()
    {
        return $this->path;
    }
    
}

?>
