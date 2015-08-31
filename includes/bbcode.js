function addEvent(obj, evType, fn) {
  if (obj.addEventListener) {
      obj.addEventListener(evType, fn, true);
      return true;
    } else if (obj.attachEvent) {
      var r = obj.attachEvent("on"+evType, fn);
      return r;
    } else {
      return false;
    }
}

function bbcode_help() 
{ 
	document.getElementById('forum_help').innerHTML="<p>[ <a href=\"javascript: bbcode('b');\">Vastag</a> ] [ <a href=\"javascript:bbcode('u');\">Aláhúzott</a> ] [ <a href=\"javascript:bbcode('i');\">Dõlt</a> ] [ <a href=\"javascript:bbcode('s');\">Áthúzott</a> ] [ <a href=\"javascript:bbcode('url');\">Link</a> ] [ <a href=\"javascript:bbcode('mail');\">E-mail</a> ]  [ <a href=\"javascript:bbcode('quote');\">Idézet</a> ]</p>"; 
}

function bbcode(code)
{
	switch(code)
	{
		case "b": 
			bbcode1="[b]"; bbcode2="[/b]";
			break;
		case "u": 
			bbcode1="[u]"; bbcode2="[/u]";
			break;
		case "i": 
			bbcode1="[i]"; bbcode2="[/i]";
			break;
		case "s": 
			bbcode1="[s]"; bbcode2="[/s]";
			break;
		case "url": 
			bbcode1="[url="+prompt("url:","http://")+"]"; bbcode2="[/url]";
			break;
		case "mail": 
			bbcode1="[email="+prompt("E-mail:")+"]"; bbcode2="[/email]";
			break;
		case "quote":
			bbcode1="[quote]"; bbcode2 = "[/quote]";
			break;
	}
	var ta=document.getElementById('forum_text');
	if(navigator.appName == "Microsoft Internet Explorer")
	{
		range.text=bbcode1+str+bbcode2;
	}
	else
	{
		ta.value=ta.value.substring(0,ta.selectionStart)+bbcode1+ta.value.substring(ta.selectionStart,ta.selectionEnd)+bbcode2+ta.value.substring(ta.selectionEnd,ta.value.length);
		ta.focus();
	}
}
function domload()
{
addEvent(document.getElementById('forum_text'),'mouseup',iehelp);
addEvent(document.getElementById('forum_text'),'focus',bbcode_help);
}
function iehelp()
{
	if (navigator.appName == "Microsoft Internet Explorer")
	{
		range = document.selection.createRange();
	str = range.text;
	ta.focus();
	}
}

addEvent(window,'load',domload);

