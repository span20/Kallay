function contentdis()
{
	var valid = new Array('lead', 'leadnum', 'leadpic', 'leadpicw', 'leadpich', 'newsnum', 'newspic', 'newspicw', 'newspich', 'cnt_picdir');

	for (var i = 0; i < valid.length; i++) {
		var mezo = document.getElementsByName(valid[i]);
		for (var j = 0; j < mezo.length; j++) {
			if (document.frm_system.news.checked == false) {
				mezo[j].disabled = true;
			} else {
				mezo[j].disabled = false;
			}
		}
	}
}
