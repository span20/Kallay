<div style="padding-left: 62px;">
	<div style="cursor: pointer; width: 730px; height: 355px; background: url('{$theme_dir}/images/contact_map.gif') no-repeat;" onclick="window.open('http://maps.google.com/maps?f=q&source=s_q&hl=hu&geocode=&q=1113+Budapest,+Tarcali+utca+23&sll=47.498406,19.040758&sspn=0.539051,1.234589&ie=UTF8&hq=&hnear=1113+Budapest,+11+ker%C3%BClet,+Tarcali+utca+23,+Magyarorsz%C3%A1g&ll=47.479305,19.032558&spn=0.002979,0.004823&t=h&z=18', 'map');">
		<div style="padding: 180px 0 0 0;">
			<script>
				var contentText = '{$locale.config.contact_text}';
				var lineSplit = contentText.split("<br />");
				{literal}
				for (i = 0; i < lineSplit.length; i++) {
					padding = 560-(i*4);
					if (lineSplit[i] == "") {
						document.write('<br />');
					} else {
						document.write('<div style="padding-left: '+padding+'px">'+lineSplit[i]+'</div>');
					}
				}
				{/literal}
			</script>
		</div>
	</div>
	{if $smarty.request.sent eq 1}
		<div style="padding: 10px 0 10px 0;">
			Kedves látogatónk!<br />
			Köszönjük érdeklõdését, munkatársunk hamarosan felkeresi Önt.<br /><br />
			Üdvözlettel<br />
			Focus Team
		</div>
	{/if}
	<script>
	{literal}
	function check() {
		var valid = true;
		if (document.getElementById('nev').value == "" || document.getElementById('nev').value == "Név") {
			alert("Kérem adja meg a nevét!");
			valid = false;
		}
		if (document.getElementById('email').value == "" || document.getElementById('email').value == "E-mail") {
			alert("Kérem adja meg az e-mail címét!");
			valid = false;
		}
		if (document.getElementById('targy').value == "" || document.getElementById('targy').value == "Tárgy") {
			alert("Kérem adja meg az üzenet tárgyát!");
			valid = false;
		}
		if (document.getElementById('uzenet').value == "" || document.getElementById('uzenet').value == "Üzenet") {
			alert("Kérem adja meg az üzenetet!");
			valid = false;
		}
		
		return valid;
	}
	{/literal}
	</script>
    <div style="padding-top: 20px;">
    	<form method="post" action="{$smarty.server.PHP_SELF}?{$smarty.server.QUERY_STRING}" onsubmit="return check();">
    		<input type="hidden" name="send" value="1">
    		<div style="float: left;">
    			<input id="nev" type="text" name="nev" onfocus="this.value=''" value="Név" style="padding: 0 0 0 5px; font-size: 11px; color: #acaca9; border: none; width: 271px; height: 25px; background: url('{$theme_dir}/images/input_back.gif');"><br /><br />
    			<input id="email" type="text" name="email" onfocus="this.value=''" value="E-mail" style="padding: 0 0 0 5px; font-size: 11px; color: #acaca9; border: none; width: 271px; height: 25px; background: url('{$theme_dir}/images/input_back.gif');"><br /><br />
    			<input id="targy" type="text" name="targy" onfocus="this.value=''" value="Tárgy" style="padding: 0 0 0 5px; font-size: 11px; color: #acaca9; border: none; width: 271px; height: 25px; background: url('{$theme_dir}/images/input_back.gif');"><br /><br />
    		</div>
    		<div style="float: left; clear: both;">
    			<div>
    				<div style="float: left;">
    					<textarea id="uzenet" name="uzenet" onfocus="this.value=''" style="padding: 5px 0 0 5px; font-family: Trebuchet MS; font-size: 11px; color: #acaca9; border: none; width: 271px; height: 86px; background: url('{$theme_dir}/images/textarea_back.gif')">Üzenet</textarea>
    				</div>
    				<div style="float: left; padding: 66px 0 0 10px;">
    					<input type="image" src="{$theme_dir}/images/send.gif" style="border: none;" onmouseover="this.src='{$theme_dir}/images/send_over.gif'" onmouseout="this.src='{$theme_dir}/images/send.gif'" />
    				</div>
    			</div>
    		</div>
    	</form>
    </div>
</div>