function cal_show_hide(event)
{
	var el = document.getElementById(event);

	if (el.style.display == 'none') {
		el.style.display = '';
	} else {
		el.style.display = 'none';
	}
}
