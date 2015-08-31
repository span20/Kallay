function textCounter(field_id,cntfield_id,maxlimit)
{
	field = document.getElementById(field_id);
	cntfield = document.getElementById(cntfield_id);

	if (field.value.length > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
	// otherwise, update 'characters left' counter
	} else {
		cntfield.value = maxlimit - field.value.length;
	}
}

function trSwitcher( id ) {
	tr = document.getElementById( id );
	if(navigator.appName == "Microsoft Internet Explorer") {
		if ( tr.style.display == 'none' ) tr.style.display = 'inline';
		else tr.style.display = 'none';
	}
	else {
		if ( tr.style.display == 'none' ) tr.style.display = 'table-row';
		else tr.style.display = 'none';
	}
}