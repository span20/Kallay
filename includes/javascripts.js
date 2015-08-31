var animRunning = false;
var actQ = 1;
var mins = 15;  //Set the number of minutes you need
var secs = mins * 60;
var currentSeconds = 0;
var currentMinutes = 0;
var timer;
var allAnswers = 0;

$(document).ready(function(){
	$('.cont_text img').addClass("img-responsive");
});

function startTest() {
	timer = setTimeout('Decrement()',1000);
}

function switchQ(way) {

	if (!animRunning) {
		animRunning = true;
		var prevQ;
		
		if (way == 'prev') {
			prevQ = actQ;
			actQ--; 
		}
		if (way == 'next') {
			prevQ = actQ;
			actQ++; 
		}
		
		if (actQ > 1) {
			$('#prev_q').show();
		} else {
			$('#prev_q').hide();
		}
		
		if (actQ == 14) {
			$('#next_q').hide();
		} else {
			$('#next_q').show();
		}
		
		$('#question_'+prevQ).fadeOut(200, function() {
			$('#question_'+actQ).fadeIn(200, function() {
				animRunning = false;
			});
		});
	}
}

function checkTest() {
	
	$('#q_pager').hide();
	clearTimeout(timer);
	
	var postdata = "a[7]="+$('#a_8').val();
	
	$('input:radio').each(function (i) {
        if (this.checked) {
			var radioname = $(this).attr('name');
			var actradio = radioname.split('_');
            postdata += "&a["+(parseInt(actradio[1])-1)+"]="+$(this).val();
        }
    });
	
	$.ajax({
		type: 'post',
		url: 'ajax.php?act=saveResult',
		data: "secs="+secs+"&"+postdata,
		success: function(data) {
			if (parseInt(data) < 10) {
				//nem sikerült
				$('#teszt_cont').html("Pontszám: "+data+"<br /><br />Nem sikerült elérned a minimális pontszámot (10 pont), ezúttal sajnos nem tudunk pozíciót ajánlani számodra!");
			} else {
				//sikerült
				$('#teszt_cont').html('Pontszám: '+data+'<br /><br />Gratulálunk! Nincs más dolgod, mint feltölteni a CV-det! Amennyiben most nem tudsz feltölteni, csak kattints a jelentkezés elküldése gombra.<div><form action="index.php?p=test" method="post" enctype="multipart/form-data"><input type="hidden" name="cv_sent" value="1"><input type="hidden" name="answers" value="'+postdata+'">Önéletrajz: <input type="file" name="cv_file" /><br /><input type="submit" name="meh" value="Jelentkezés elküldése"></form></div>');
			}
		}
	});
}

function Decrement() {
	if (allAnswers < 14) {
		allAnswers = 0;
		
		$('input:radio').each(function (i) {
			if (this.checked) {
				allAnswers++;
			}
		});
		
		if ($('#a_8').val() != "") {
			allAnswers++;
		}
	}
	
	if (allAnswers == 14) {
		$('#ertekel').removeAttr('disabled');
	}
	
	currentMinutes = Math.floor(secs / 60);
	currentSeconds = secs % 60;
	if(currentMinutes <= 9) currentMinutes = "0" + currentMinutes;
	if(currentSeconds <= 9) currentSeconds = "0" + currentSeconds;
	secs--;
	$("#countdown").html(currentMinutes + ":" + currentSeconds); //Set the element id you need the time put into.
	if(secs !== -1) {
		timer = setTimeout('Decrement()',1000);
	} else {
		clearTimeout(timer);
		$("#countdown").html("00:00");
		checkTest();
	}
}

function deltimer(type)
{
	document.getElementById(type).value = "";
}

function addSrcToDestList(valt) {
	var destList = document.getElementById("destList"+valt);
	var srcList  = document.getElementById("srcList");
	var len = destList.length;
	for(var i = 0; i < srcList.length; i++) {
		if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
			var found = false;
			for(var count = 0; count < len; count++)
			{
				if (destList.options[count] != null) {
					if (srcList.options[i].text == destList.options[count].text) {
						found = true;
						break;
					}
				}
			}
			if (found != true) {
				destList.options[len] = new Option(srcList.options[i].text, srcList.options[i].value);
				len++;
			}
    }
  }
}

function deleteFromDestList(valt)
{
	var destList = document.getElementById("destList"+valt);
	var len = destList.options.length;
	for(var i = (len-1); i >= 0; i--)
	{
		if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
			destList.options[i] = null;
		}
  }
}

function searchSelectBox(in_sFormName, in_sInputName, in_sSelectName)
{
	sSearchString = document.forms[in_sFormName].elements[in_sInputName].value.toUpperCase();
	iSearchTextLength = sSearchString.length;
	for (j=0; j < document.forms[in_sFormName].elements[in_sSelectName].options.length; j++)
	{
		sOptionText = document.forms[in_sFormName].elements[in_sSelectName].options[j].text;
		sOptionComp = sOptionText.substr(0, iSearchTextLength).toUpperCase();
		if(sSearchString == sOptionComp) {
			document.forms[in_sFormName].elements[in_sSelectName].selectedIndex = j;
			break;
		}
	}
}

function SelectAll(f)
{
	for(var j = 0; j < 7; j++)
	{
		if (document.getElementById('destList'+j)) {
			var s = f.elements['destList'+j+'[]'];
			for(var i = 0; i < s.options.length; i++)
			{
				s.options[i].selected = true;
			}
		}
	}
	return true;
}

var checkflag = "false";
function doNow()
{
	d  = document;
	el = d.getElementsByTagName('INPUT');

	if (checkflag == "false") {
		for(i = 0; i < el.length; i++)
		{
			if (el[i].disabled == 0) el[i].checked = 1;
		}
		checkflag = "true";
	} else {
		for(i = 0; i < el.length; i++)
		{
			el[i].checked = 0;
		}
		checkflag = "false";
	}
}

function show_news(show_id) {
    var news_divs = document.getElementsByTagName('div');
    for (i = 1; i < news_divs.length; i++) {
        if (news_divs[i].getAttribute('rel') == 'news') {
            news_divs[i].style.display = 'none';
        }
        if (news_divs[i].getAttribute('rel') == 'pager') {
            news_divs[i].style.backgroundImage = 'none';
        }
    }
    
    var newsToShow = document.getElementById('news_'+show_id);
    var pager = document.getElementById('pager_'+show_id);
    pager.style.backgroundImage = "url('themes/ishark/images/pager_arrow.png')";
    pager.style.backgroundPosition = 'bottom center';
    pager.style.backgroundRepeat = 'no-repeat';
    
    newsToShow.style.display = 'block';
}

function show_content_pages(show_id) {
    var content_divs = document.getElementsByTagName('div');
    for (i = 1; i < content_divs.length; i++) {
        if (content_divs[i].getAttribute('rel') == 'content') {
            content_divs[i].style.display = 'none';
        }
        if (content_divs[i].getAttribute('rel') == 'pager') {
            content_divs[i].style.backgroundImage = 'none';
        }
    }
    
    var contentToShow = document.getElementById('content_'+show_id);
    var pager = document.getElementById('pager_'+show_id);
    pager.style.backgroundImage = "url('themes/ishark/images/pager_arrow.png')";
    pager.style.backgroundPosition = 'bottom center';
    pager.style.backgroundRepeat = 'no-repeat';
    
    contentToShow.style.display = 'block';
}

var temp = 0;

function opacity(id, opacStart, opacEnd, millisec) {
    //speed for each frame
    var speed = Math.round(millisec / 100);
    var timer = 0;

    //determine the direction for the blending, if start and end are the same nothing happens
    if(opacStart > opacEnd) {
        for(i = opacStart; i >= opacEnd; i--) {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    } else if(opacStart < opacEnd) {
        for(i = opacStart; i <= opacEnd; i++)
            {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    }
}

//change the opacity for different browsers
function changeOpac(opacity, id) {
    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";
}

function pausecomp(millis)
{
var date = new Date();
var curDate = null;

do { curDate = new Date(); }
while(curDate-date < millis);
} 

function loadBigPic(url) {
    opacity('big_pic', 100, 0, 600);
    setTimeout("loadBigPic2('"+url+"')", 600);
}

function loadBigPic2(url) {
    var big_pic_div = document.getElementById('big_pic');
    big_pic_div.innerHTML = '<img src="'+url+'" />';
    opacity('big_pic', 0, 100, 600)
}

function ajaxRequest(divid, url, width, scriptDiv) {
	if (width == 'img') {
		newW = 650;
	} else {
		newW = width;
	}
	//document.getElementById(divid).innerHTML='<div style="width: '+newW+'px; text-align: center;">Betöltés...<br><img src="loader.gif" \/><\/div>';
	
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if (width == 'img') {
                var splitted = xmlhttp.responseText.split("#");
                if (splitted[0] != "") {
                    document.getElementById(divid).onclick= function() { ajaxRequest('big_pic', 'ajax.php?act=getPicture&id='+splitted[0], 'img', 'scriptDiv'); }
                }
				document.getElementById(divid).src=splitted[1];
			} else {
				document.getElementById(divid).innerHTML=xmlhttp.responseText;
			}
		}
	}
	
	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}

function changePage(id, way, all) {
	var act = document.getElementById(id).value;

	if (way == 'prev') {
		if (act > 0) {
			act = parseInt(act)-1;
		}
	}
	if (way == 'next') {
		if (act < all) {
			act = parseInt(act)+1;
		}
	}
	if (act == 0) {
		document.getElementById(id+'_pLeft').style.display = 'none';
	} else {
		document.getElementById(id+'_pLeft').style.display = '';
	}
	
	if (act == parseInt(all)-1) {
		document.getElementById(id+'_pRight').style.display = 'none';
	} else {
		document.getElementById(id+'_pRight').style.display = '';
	}
	
	document.getElementById(id).value = act;
}

function showHideGals(id) {
	var picdiv = document.getElementById('pic_gal');
	var slidediv = document.getElementById('slide_gal');
	var videodiv = document.getElementById('video_gal');
	
	switch (id) {
		case 'pic_gal':
			picdiv.style.display = '';
			slidediv.style.display = 'none';
			videodiv.style.display = 'none';
		break;
		case 'slide_gal':
			picdiv.style.display = 'none';
			slidediv.style.display = '';
			videodiv.style.display = 'none';
		break;
		case 'video_gal':
			picdiv.style.display = 'none';
			slidediv.style.display = 'none';
			videodiv.style.display = '';
		break;
	}
}

var padd = new Array("256", "252", "248", "252", "248", "244", "240", "236", "232", "228");
var actStatus;

function showHide(id, text, num) {
	var anchors = document.getElementsByTagName("a");
	for(i = 0; i < div_ids.length; i++) {
		if (div_ids[i] != id) {
			document.getElementById(div_ids[i]).style.display = "none";
			
		}
		if (div_ids[i] == id) {
			if (document.getElementById(id).style.display == "block") {
				document.getElementById(id).style.display = "none";
				actStatus = 1;
			} else {
				document.getElementById(id).style.display = "block";
				actStatus = 2;	
			}
		}
	}
	for(i = 0; i < anchors.length; i++) {
		if(anchors[i].hasAttribute("rel")) { 
			if (anchors[i].getAttribute("rel") == id) {
				if (actStatus == 1) {
					anchors[i].innerHTML = "+ "+text;		
				} else {
					anchors[i].innerHTML = "- "+text;
				}
			} else {
				var sign = anchors[i].innerHTML.substr(2);
				anchors[i].innerHTML = "+ "+sign;
			}
		}
	}
	document.getElementById('nyithato_2_1').style.paddingLeft = "256px";
	document.getElementById('nyithato_3_1').style.paddingLeft = "252px";
	document.getElementById('nyithato_4_1').style.paddingLeft = "248px";	   
	if (actStatus == 1) {
		//if (num == 1) {
			document.getElementById('nyithato_2_1').style.paddingLeft = "256px";
			document.getElementById('nyithato_3_1').style.paddingLeft = "252px";
			document.getElementById('nyithato_4_1').style.paddingLeft = "248px";
			
			document.getElementById('szoveg_lent_1').style.paddingLeft = "252px";
			document.getElementById('szoveg_lent_2').style.paddingLeft = "248px";
			document.getElementById('szoveg_lent_3').style.paddingLeft = "244px";
			document.getElementById('szoveg_lent_4').style.paddingLeft = "240px";
			document.getElementById('szoveg_lent_5').style.paddingLeft = "236px";
			document.getElementById('szoveg_lent_6').style.paddingLeft = "232px";
			document.getElementById('szoveg_lent_7').style.paddingLeft = "228px";
		//}
	}
}

function pushContent(num) {
	if (actStatus == 2) {
		if (num == 1) {
			document.getElementById('nyithato_2_1').style.paddingLeft = "244px";
			document.getElementById('nyithato_3_1').style.paddingLeft = "240px";
			document.getElementById('nyithato_4_1').style.paddingLeft = "236px";
			
			document.getElementById('szoveg_lent_1').style.paddingLeft = "232px";
			document.getElementById('szoveg_lent_2').style.paddingLeft = "228px";
			document.getElementById('szoveg_lent_3').style.paddingLeft = "224px";
			document.getElementById('szoveg_lent_4').style.paddingLeft = "220px";
			document.getElementById('szoveg_lent_5').style.paddingLeft = "214px";
			document.getElementById('szoveg_lent_6').style.paddingLeft = "210px";
			document.getElementById('szoveg_lent_7').style.paddingLeft = "206px";
		}
		if (num == 2) {
			document.getElementById('nyithato_3_1').style.paddingLeft = "228px";
			document.getElementById('nyithato_4_1').style.paddingLeft = "224px";
			
			document.getElementById('szoveg_lent_1').style.paddingLeft = "220px";
			document.getElementById('szoveg_lent_2').style.paddingLeft = "214px";
			document.getElementById('szoveg_lent_3').style.paddingLeft = "210px";
			document.getElementById('szoveg_lent_4').style.paddingLeft = "206px";
			document.getElementById('szoveg_lent_5').style.paddingLeft = "202px";
			document.getElementById('szoveg_lent_6').style.paddingLeft = "198px";
			document.getElementById('szoveg_lent_7').style.paddingLeft = "194px";
		}
		if (num == 3) {
			document.getElementById('nyithato_4_1').style.paddingLeft = "228px";
			
			document.getElementById('szoveg_lent_1').style.paddingLeft = "224px";
			document.getElementById('szoveg_lent_2').style.paddingLeft = "220px";
			document.getElementById('szoveg_lent_3').style.paddingLeft = "216px";
			document.getElementById('szoveg_lent_4').style.paddingLeft = "212px";
			document.getElementById('szoveg_lent_5').style.paddingLeft = "208px";
			document.getElementById('szoveg_lent_6').style.paddingLeft = "204px";
			document.getElementById('szoveg_lent_7').style.paddingLeft = "200px";
		}
		if (num == 4) {
			document.getElementById('szoveg_lent_1').style.paddingLeft = "208px";
			document.getElementById('szoveg_lent_2').style.paddingLeft = "204px";
			document.getElementById('szoveg_lent_3').style.paddingLeft = "200px";
			document.getElementById('szoveg_lent_4').style.paddingLeft = "196px";
			document.getElementById('szoveg_lent_5').style.paddingLeft = "192px";
			document.getElementById('szoveg_lent_6').style.paddingLeft = "188px";
			document.getElementById('szoveg_lent_7').style.paddingLeft = "184px";
		}
	}
}

function NiftyCheck(){
if(!document.getElementById || !document.createElement)
    return(false);
isXHTML=/html\:/.test(document.getElementsByTagName('body')[0].nodeName);
if(Array.prototype.push==null){Array.prototype.push=function(){
      this[this.length]=arguments[0]; return(this.length);}}
return(true);
}

function Rounded(selector,wich,bk,color,opt){
var i,prefixt,prefixb,cn="r",ecolor="",edges=false,eclass="",b=false,t=false;

if(color=="transparent"){
    cn=cn+"x";
    ecolor=bk;
    bk="transparent";
    }
else if(opt && opt.indexOf("border")>=0){
    var optar=opt.split(" ");
    for(i=0;i<optar.length;i++)
        if(optar[i].indexOf("#")>=0) ecolor=optar[i];
    if(ecolor=="") ecolor="#666";
    cn+="e";
    edges=true;
    }
else if(opt && opt.indexOf("smooth")>=0){
    cn+="a";
    ecolor=Mix(bk,color);
    }
if(opt && opt.indexOf("small")>=0) cn+="s";
prefixt=cn;
prefixb=cn;
if(wich.indexOf("all")>=0){t=true;b=true}
else if(wich.indexOf("top")>=0) t="true";
else if(wich.indexOf("tl")>=0){
    t="true";
    if(wich.indexOf("tr")<0) prefixt+="l";
    }
else if(wich.indexOf("tr")>=0){
    t="true";
    prefixt+="r";
    }
if(wich.indexOf("bottom")>=0) b=true;
else if(wich.indexOf("bl")>=0){
    b="true";
    if(wich.indexOf("br")<0) prefixb+="l";
    }
else if(wich.indexOf("br")>=0){
    b="true";
    prefixb+="r";
    }
var v=getElementsBySelector(selector);
var l=v.length;
for(i=0;i<l;i++){
    if(edges) AddBorder(v[i],ecolor);
    if(t) AddTop(v[i],bk,color,ecolor,prefixt);
    if(b) AddBottom(v[i],bk,color,ecolor,prefixb);
    }
}

function AddBorder(el,bc){
var i;
if(!el.passed){
    if(el.childNodes.length==1 && el.childNodes[0].nodeType==3){
        var t=el.firstChild.nodeValue;
        el.removeChild(el.lastChild);
        var d=CreateEl("span");
        d.style.display="block";
        d.appendChild(document.createTextNode(t));
        el.appendChild(d);
        }
    for(i=0;i<el.childNodes.length;i++){
        if(el.childNodes[i].nodeType==1){
            el.childNodes[i].style.borderLeft="1px solid "+bc;
            el.childNodes[i].style.borderRight="1px solid "+bc;
            }
        }
    }
el.passed=true;
}
    
function AddTop(el,bk,color,bc,cn){
var i,lim=4,d=CreateEl("b");

if(cn.indexOf("s")>=0) lim=2;
if(bc) d.className="artop";
else d.className="rtop";
d.style.backgroundColor=bk;
for(i=1;i<=lim;i++){
    var x=CreateEl("b");
    x.className=cn + i;
    x.style.backgroundColor=color;
    if(bc) x.style.borderColor=bc;
    d.appendChild(x);
    }
el.style.paddingTop=0;
el.insertBefore(d,el.firstChild);
}

function AddBottom(el,bk,color,bc,cn){
var i,lim=4,d=CreateEl("b");

if(cn.indexOf("s")>=0) lim=2;
if(bc) d.className="artop";
else d.className="rtop";
d.style.backgroundColor=bk;
for(i=lim;i>0;i--){
    var x=CreateEl("b");
    x.className=cn + i;
    x.style.backgroundColor=color;
    if(bc) x.style.borderColor=bc;
    d.appendChild(x);
    }
el.style.paddingBottom=0;
el.appendChild(d);
}

function CreateEl(x){
if(isXHTML) return(document.createElementNS('http://www.w3.org/1999/xhtml',x));
else return(document.createElement(x));
}

function getElementsBySelector(selector){
var i,selid="",selclass="",tag=selector,f,s=[],objlist=[];

if(selector.indexOf(" ")>0){  //descendant selector like "tag#id tag"
    s=selector.split(" ");
    var fs=s[0].split("#");
    if(fs.length==1) return(objlist);
    f=document.getElementById(fs[1]);
    if(f) return(f.getElementsByTagName(s[1]));
    return(objlist);
    }
if(selector.indexOf("#")>0){ //id selector like "tag#id"
    s=selector.split("#");
    tag=s[0];
    selid=s[1];
    }
if(selid!=""){
    f=document.getElementById(selid);
    if(f) objlist.push(f);
    return(objlist);
    }
if(selector.indexOf(".")>0){  //class selector like "tag.class"
    s=selector.split(".");
    tag=s[0];
    selclass=s[1];
    }
var v=document.getElementsByTagName(tag);  // tag selector like "tag"
if(selclass=="")
    return(v);
for(i=0;i<v.length;i++){
    if(v[i].className.indexOf(selclass)>=0){
        objlist.push(v[i]);
        }
    }
return(objlist);
}

function Mix(c1,c2){
var i,step1,step2,x,y,r=new Array(3);
if(c1.length==4)step1=1;
else step1=2;
if(c2.length==4) step2=1;
else step2=2;
for(i=0;i<3;i++){
    x=parseInt(c1.substr(1+step1*i,step1),16);
    if(step1==1) x=16*x+x;
    y=parseInt(c2.substr(1+step2*i,step2),16);
    if(step2==1) y=16*y+y;
    r[i]=Math.floor((x*50+y*50)/100);
    }
return("#"+r[0].toString(16)+r[1].toString(16)+r[2].toString(16));
}
