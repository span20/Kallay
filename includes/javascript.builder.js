function showColumnSelect(db,hasab)
{
	if (db > 1){
		for (i = 1; i < 8; i++) {
			div = document.getElementById(i);
			if (div){
				div.style.display = '';
				sel = document.getElementById(i+'_newsel');
				hid = document.getElementById(i+'_col');
				sel.options.length = 0;
				for (k = 1; k <= db; k++) {
					sel[k] = new Option(k+". "+hasab, k);
					if (k == hid.value){
						sel[k].selected = true;
					}
				}
			}

		}
	} else {
		for (i = 1; i < 8; i++) {
			div = document.getElementById(i);
			sel = document.getElementById(i+'_newsel');
			sel.options.length = 0;
			if (div){
				div.style.display = 'none';
			}
		}
	}
}
