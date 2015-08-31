<div id="form">
	<div id="form_top">
		<h1>Regisztráció</h1>
	</div>
    <script type="text/javascript" src="includes/date.js"></script>
    <script type="text/javascript" src="includes/jquery.datePicker.js"></script>
    <link rel="stylesheet" type="text/css" media="screen, projection" href="includes/datePicker.css" />
	<div id="form_cnt">
		<form {$form_account.attributes}>
			{$form_account.hidden}
            <div style="float: left; width: 200px;">{if $form_account.email.required}<span class="required"><sup>*</sup></span>{/if}<span class="form_text">{$form_account.email.label}</span></div>
			<div style="float: left;">{$form_account.email.html}{if $form_account.email.error}<span class="error">{$form_account.email.error}</span>{/if}</div>

            {if $form_account.modpass}
            <div style="clear: both;">
				<div style="float: left; width: 200px;">{if $form_account.modpass.required}<span class="required">*</span>{/if}<span class="form_text">{$form_account.modpass.label}</span></div>
				<div style="float: left;">{$form_account.modpass.html}{if $form_account.modpass.error}<span class="error">{$form_account.modpass.error}</span>{/if}</div>
			</div>
			<div id="modifypass" style="display:{$none_block}; clear: both;">
				<div style="float: left; width: 200px;">{if $form_account.oldpass.required}<span class="required">*</span>{/if}<span class="form_text">{$form_account.oldpass.label}</span></div>
				<div style="float: left;">{$form_account.oldpass.html}{if $form_account.oldpass.error}<span class="error">{$form_account.oldpass.error}</span>{/if}</div>
            </div>
			{/if}


			<div style="float: left; width: 200px; clear: both;">{if $form_account.pass1.required}<span class="required"><sup>*</sup></span>{/if}<span class="form_text">{$form_account.pass1.label}</span></div>
			<div style="float: left;">{$form_account.pass1.html}{if $form_account.pass1.error}<span class="error">{$form_account.pass1.error}</span>{/if}</div>

			<div style="float: left; width: 200px; clear: both;">{if $form_account.pass2.required}<span class="required"><sup>*</sup></span>{/if}<span class="form_text">{$form_account.pass2.label}</span></div>
			<div style="float: left;">{$form_account.pass2.html}{if $form_account.pass2.error}<span class="error">{$form_account.pass2.error}</span>{/if}</div>

            <div style="float: left; width: 200px; clear: both;">{if $form_account.cegnev.required}<span class="required"><sup>*</sup></span>{/if}<span class="form_text">{$form_account.cegnev.label}</span></div>
			<div style="float: left;">{$form_account.cegnev.html}{if $form_account.cegnev.error}<span class="error">{$form_account.cegnev.error}</span>{/if}</div>

            <div style="float: left; width: 200px; clear: both;">{if $form_account.ertesito.required}<span class="required"><sup>*</sup></span>{/if}<span class="form_text">{$form_account.ertesito.label}</span></div>
			<div style="float: left;">{$form_account.ertesito.html}{if $form_account.ertesito.error}<span class="error">{$form_account.ertesito.error}</span>{/if}</div>

            
			<div style="clear: both; padding-top: 10px;">{$form_account.submit.html}</div>
		</form>
	</div>
	<div id="form_bottom"></div>
</div>

{if $form_account.modpass}
{literal}<script type="text/javascript">//<[CDATA[
function modPassActivate()
{
	modif = document.getElementById('modifypass');
	p1 = document.getElementById('pass1');
	p2 = document.getElementById('pass2');
	if (document.getElementById('modpass').checked) {
		modif.style.display = 'block';
	} else {
		modif.style.display = 'none';
		p1.value='';p2.value='';
	}
}
//]]></script>{/literal}
{/if}
