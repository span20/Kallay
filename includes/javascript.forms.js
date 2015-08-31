function end_dis(value, field)
{
	div = document.getElementById('answer_fields');
	if (value == 'radio' || value == 'checkbox' || value == 'select') {
		document.getElementById(field).style.display = '';
	} else {
		document.getElementById(field).style.display = 'none';
		div.innerHTML = "";
	}
}

function create_fields(values)
{
	div = document.getElementById('answer_fields');
	num = document.getElementById('fields_num');
	if (values) {
		value = values.split(',');
		num.value = parseInt(num.value)+parseInt(value.length)-1;
		for (i=1; i<value.length; i++) {
			var newdiv = document.createElement('div');
			newdiv.setAttribute("id",i);
			newdiv.innerHTML += '<input type="text" name="answer_'+i+'" value="'+value[i-1]+'" /> <a href="javascript:void(0);" onclick="remove_fields('+i+')">eltávolít</a>';
			div.appendChild(newdiv);
		}
	} else {
		num.value = parseInt(num.value)+1;
		var newdiv = document.createElement('div');
		newdiv.setAttribute("id",num.value);
		newdiv.innerHTML = '<input id="input_'+num.value+'" type="text" name="answer_'+num.value+'" /> <a href="javascript:void(0);" onclick="remove_fields('+num.value+')">eltávolít</a>';
		div.appendChild(newdiv);
	}
}

function remove_fields(div_id)
{
	parent = document.getElementById('answer_fields');
	remove = document.getElementById(div_id);
	parent.removeChild(remove);
}

function email_dis(value)
{
    ifi = document.getElementById('mailchk');
	efi = document.getElementById('email_field');
	if (ifi.checked == true) {
		efi.disabled = false;
	} else {
		efi.disabled = true;
	}
}