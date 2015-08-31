<?php
/* vim: set expandtab softtabstop=4 shiftwidth=4 tabstop=4: */

require_once 'XML/Parser.php';

class Translator_XMLParser extends XML_Parser {
    var $translator;
    var $lang;
    var $area;
    var $variable;

    var $folding = false;

    function Translator_XMLParser(&$translator)
    {
        parent::XML_Parser('iso-8859-1', 'event', 'iso-8859-1');
        $this->translator =& $translator;
    }

    function startHandler($parser, $name, $attribs)
    {
        switch ($name) {
            case "area":
                $this->area = $attribs['name'];
                $this->translator->addArea($this->area);
                $this->lang = $attribs['lang'];
                break;
            case "variable":
                $this->variable = $attribs['name'];
                break;
        }
    }

    function endHandler($parser, $name)
    {
        switch ($name) {
            case "variable":
                $this->variable = NULL;
                break;
            case "area":
                $this->area = NULL;
                break;
        }
    }

    function cdataHandler($parser, $data)
    {
        if (!empty($this->variable) && !empty($this->lang) && !empty($this->area)) {
            $this->translator->addExpression($this->lang, $this->area, $this->variable, $this->translator->mdb2->escape($data));
        }
    }
}

?>