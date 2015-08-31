<?php
 
class myParser extends XML_Parser_Simple
{
    //function myParser($srcenc, $mode, $tgtenc)
    function myParser()
    {
        $this->XML_Parser_Simple();
    }

   /**
    * handle the element
    *
    * The element will be handled, once it's closed
    *
    * @access   private
    * @param    string      name of the element
    * @param    array       attributes of the element
    * @param    string      character data of the element
    */
    function handleElement($name, $attribs, $data)
    {
		global $mtidata, $parser_counter;

		if ($name == "TITLE") {
			$mtidata[$parser_counter]['title'] = $data;
		}
		if ($name == "LEAD") {
			$mtidata[$parser_counter]['lead'] = $data;
		}
		if ($name == "BODY") {
			$mtidata[$parser_counter]['body'] = $data;
		}
		if ($name == "MAINSECTION") {
			$mtidata[$parser_counter]['mainsection'] = $data;
		}
		if ($name == "CREATEDATE") {
			$mtidata[$parser_counter]['createdate'] = $data;
		}
		if ($name == "MODIFIEDDATE") {
			$mtidata[$parser_counter]['modifieddate'] = $data;
		}
		if ($name == "ID") {
			$mtidata[$parser_counter]['id'] = $data;
		}
		if ($name == "IMAGE") {
			$mtidata[$parser_counter]['image'] = $data;
		}
		if ($name == "NEWS") {
			$parser_counter++;
		}
    }
}

?>
