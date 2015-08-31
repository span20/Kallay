function addEvent(text)
{
	var ni        = document.getElementById('myDiv');
	var numi      = document.getElementById('theValue');
	var num       = (document.getElementById("theValue").value -1)+ 2;
	numi.value    = num;
	var divIdName = "my"+num+"Div";
	var newdiv    = document.createElement('span');
	var divIdName = 'my'+num+'Div';
	newdiv.setAttribute("id",divIdName);
	newdiv.innerHTML = '<br><input type="text" name="answer[]"> <a href="#" onClick="removeEvent(\''+divIdName+'\')">'+text+'</a>';
	ni.appendChild(newdiv);
}

function removeEvent(divNum)
{
	var d      = document.getElementById('myDiv');
	var olddiv = document.getElementById(divNum);
	d.removeChild(olddiv);
}
