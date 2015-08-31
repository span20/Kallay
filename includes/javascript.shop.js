function addAddressActivate()
{
	modif = document.getElementById('addaddress');
	p1 = document.getElementById('shipzip');
	p2 = document.getElementById('shipcity');
	p3 = document.getElementById('country');
	p4 = document.getElementById('shipaddr');

	if (document.getElementById('add_address').checked) {
		modif.style.display = 'block';
	} else {
		modif.style.display = 'none';
	}
	p1.value = '';
	p2.value = '';
	p3.value = '';
	p4.value = '';
}

function modAddress(zip, city, country, address, aid)
{
	document.getElementById('addaddress').style.display = '';
	p1 = document.getElementById('shipzip');
	p2 = document.getElementById('shipcity');
	p3 = document.getElementById('country');
	p4 = document.getElementById('shipaddr');
	p5 = document.getElementById('aid');
	p6 = document.getElementById('add_address');

	p1.value = zip;
	p2.value = city;
	for (i = 0; i < p3.length; i++) {
		if (p3.options[i].value == country) {
			p3.options[i].selected = true;
		}
	}
	p4.value = address;
	p5.value = aid;
	p6.disabled = "disabled";
	if (p6.checked == 1) {
		p6.checked = 0;
	}
}

function removeJS()
{
	document.getElementById('addaddress').style.display = 'none';
	p1 = document.getElementById('shipzip');
	p2 = document.getElementById('shipcity');
	p3 = document.getElementById('country');
	p4 = document.getElementById('shipaddr');
	p5 = document.getElementById('aid');
	p6 = document.getElementById('add_address');

	p1.value = '';
	p2.value = '';
	p4.value = '';
	p5.value = '';
	p6.disabled = '';
	if (p6.checked == 1) {
		p6.checked = 0;
	}
}

function saveAddress(form)
{
	shipzip     = form.shipzip.value;
	shipcity    = form.shipcity.value;
	shipaddr    = form.shipaddr.value;
	shipcountry = form.shipcountry.value;
}

function copyAddress(form)
{
	if (form.copyaddr.checked) {
		saveAddress(form);
		form.postzip.value     = form.shipzip.value;
		form.postcity.value    = form.shipcity.value;
		form.postaddr.value    = form.shipaddr.value;
		form.postcountry.value = form.shipcountry.value;
	} else {
		form.postzip.value     = "";
		form.postcity.value    = "";
		form.postaddr.value    = "";
		form.postcountry.value = "";
	}
}

function fixpercent(price)
{
	perc     = document.getElementById('percent');
	fix      = document.getElementById('fix');
	myspan   = document.getElementById('mySpan');
	products = document.getElementById('products');

	if (price != 'null') {
		var prices = price.split(",");
	} else {
		var prices = new Array();
	}

	if (document.getElementById('frm_action').actionradio[1].checked == true) {
		perc.style.display = "";
		fix.style.display  = "none";
	} else {
		perc.style.display = "none";

		myspan.innerHTML = "";
		if (products.value != "") {
			fix.style.display = "";
			var fityfasz = 0;
			for (i = 0; i < products.length; i++) {
				if (products[i].selected == true) {
					var brokenstring = products[i].value.split("_");

					if (products[i].value == prices[fityfasz]) {
						var kutykurutty = prices[fityfasz+1];
					} else {
						var kutykurutty = '';
					}
					myspan.innerHTML += '<input size="5" type="text" name="price['+products[i].value+']" value="'+kutykurutty+'"/> '+brokenstring[1]+'<br />';
					fityfasz = fityfasz+2;
				}
			}
		}
	}
}
