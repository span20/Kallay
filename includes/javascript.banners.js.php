<?php
    /**
     * New Banners:
     */
    chdir("..");
    include_once 'config_paths.php';
    include_once 'config_mdb2.php';
    
    $result =& $mdb2->query("SELECT banner_reload FROM iShark_Configs");
    if (!($row = $result->fetchRow())) {
        $banner_reload = 1000;
    } else {
        $banner_reload = $row["banner_reload"];
    }
    header("Content-type: text/javascript");
?>
/* Globals */
var places    = new Array();
var akt_place = 0;
var tm        = <?php print $banner_reload;?>;



function Place(obj)
{
    this.banners = new Array();
    this.obj = obj;
    this.akt = 0;
}

function getElementsByClassName(className, tag, elm){
	var testClass = new RegExp("(^|\\s)" + className + "(\\s|$)");
	var tag = tag || "*";
	var elm = elm || document;
	var elements = (tag == "*" && elm.all)? elm.all : elm.getElementsByTagName(tag);
	var returnElements = [];
	var current;
	var length = elements.length;
	for(var i=0; i<length; i++){
		current = elements[i];
		if(testClass.test(current.className)){
			returnElements.push(current);
		}
	}
	return returnElements;
}


function Next() {
    if (places.length == 0) {
        return;
    }
    if (akt_place>=places.length) {
        akt_place = 0;
    }
    if (places[akt_place].banners.length<=1) {
        akt_place++;
        window.setTimeout("Next();", 0);
        return;
    }

    obj = places[akt_place].banners[places[akt_place].akt];
    obj.style.zIndex = "0";
    obj.style.display = "none";
    
    places[akt_place].akt++;
    if (places[akt_place].akt >= places[akt_place].banners.length) {
        places[akt_place].akt = 0;
    }
    obj = places[akt_place].banners[places[akt_place].akt];
    obj.style.zIndex="10";
    obj.style.display="block";
    
    akt_place++;
    window.setTimeout("Next();", tm);
    return;
}

function Load2() {
    if (!document.getElementById) { return; }
    classReg = new RegExp("(^|\\s)banner(\\s|$)");
    doc_places = getElementsByClassName("banners", "DIV");
    for (var i = 0; i<doc_places.length; i++) {
        places[i] = new Place(doc_places[i]);
        for (var j=0; j<doc_places[i].childNodes.length; j++) {
            if (classReg.test(doc_places[i].childNodes[j].className)) {
                obj = doc_places[i].childNodes[j];
                places[i].banners.push(obj);
            }
        }
    }
    window.setTimeout("Next();", tm);
}


/**
 * OnLoad Event handling:
 */
if (window.addEventListener) {
    window.addEventListener("load", Load2, false);
} else if (window.attachEvent) {
    window.attachEvent("onload", Load2);
} else {
    window.onload = Load2;
}

