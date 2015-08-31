<div id="form">
	<div id="form_top">
		<span style="padding-left: 10px;">{$locale.index_shop.account_form_header|upper}</span>
	</div>
	<div id="form_cnt">
        {* REGISZTRACIO *}
		{if $smarty.session.site_userlogin == 1}
		<br /><div>
			<form {$form_shop.attributes}>
			{$form_shop.hidden}
			<dl>
				<dt class="table_th" style="width:793px; background-color: #4A4A4A; margin-bottom: 2px;">{$locale.index_shop.account_notreg_header1}</dt><dd></dd>
				<dt style="padding-left: 10px;">{if $form_shop.name.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop.name.label}</span></dt>
				<dd>{$form_shop.name.html}{if $form_shop.name.error}<span class="error">{$form_shop.name.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop.user_name.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop.user_name.label}</span></dt>
				<dd>{$form_shop.user_name.html}{if $form_shop.user_name.error}<span class="error">{$form_shop.user_name.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop.email.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop.email.label}</span></dt>
				<dd>{$form_shop.email.html}{if $form_shop.email.error}<span class="error">{$form_shop.email.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop.is_public_mail.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop.is_public_mail.label}</span></dt>
				<dd>{$form_shop.is_public_mail.html}{if $form_shop.is_public_mail.error}<span class="error">{$form_shop.is_public_mail.error}</span>{/if}</dd>
			{if $form_shop.modpass}
				<dt style="padding-left: 10px;">{if $form_shop.modpass.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop.modpass.label}</span></dt>
				<dd>{$form_shop.modpass.html}{if $form_shop.modpass.error}<span class="error">{$form_shop.modpass.error}</span>{/if}</dd>
			</dl>
			<dl id="modifypass" style="display:{$none_block};">
				<dt style="padding-left: 10px;">{if $form_shop.oldpass.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop.oldpass.label}</span></dt>
				<dd>{$form_shop.oldpass.html}{if $form_shop.oldpass.error}<span class="error">{$form_shop.oldpass.error}</span>{/if}</dd>
			{/if}
				<dt style="padding-left: 10px;">{if $form_shop.pass1.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop.pass1.label}</span></dt>
				<dd>{$form_shop.pass1.html}{if $form_shop.pass1.error}<span class="error">{$form_shop.pass1.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop.pass2.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop.pass2.label}</span></dt>
				<dd>{$form_shop.pass2.html}{if $form_shop.pass2.error}<span class="error">{$form_shop.pass2.error}</span>{/if}</dd>
			{if $form_shop.subscribe}
				<dt style="padding-left: 10px;">{if $form_shop.subscribe.required}<span class="required">*</font>{/if}<span class="form_text">{$form_shop.subscribe.label}</span></dt>
				<dd>{$form_shop.subscribe.html}{if $form_shop.subscribe.error}<span class="error">{$form_shop.pass2.error}</span>{/if}</dd>
			{/if}
			{if $form_shop.requirednote and not $form_shop.frozen}
				<div style="padding: 3px 0 3px 10px;"><span class="form_text">{$form_shop.requirednote}</span></div>
			{/if}
			<div style="padding: 3px 0 3px 10px;">{$form_shop.submit.html} {$form_shop.reset.html}</div>
			</dl>
			</form>
		</div><br />
		{/if}
        {* REGISZTRACIO VEGE *}

        {* BELEPES AZ OLDALRA *}
		{if $smarty.session.site_userlogin}
		<div>
			<form {$form_shop_login.attributes}>
			{$form_shop_login.hidden}
			<dl>
				<dt class="table_th" style="width: 793px; background-color: #4A4A4A; margin-bottom: 2px;">{$locale.index_shop.account_notreg_header2}</dt><dd></dd>
				<dt style="padding-left: 10px;">{if $form_shop_login.login_name.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_login.login_name.label}</span></dt>
				<dd>{$form_shop_login.login_name.html}{if $form_shop_login.login_name.error}<span class="error">{$form_shop_login.login_name.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_login.login_pass.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_login.login_pass.label}</span></dt>
				<dd>{$form_shop_login.login_pass.html}{if $form_shop_login.login_pass.error}<span class="error">{$form_shop_login.login_pass.error}</span>{/if}</dd>
			<div style="padding: 3px 0 3px 10px;">{$form_shop_login.submit.html}</div>
			</dl>
			</form>
		</div>
		{/if}
        {* BELEPES AZ OLDALRA VEGE *}

        {* NEM REGISZTRALT VASARLO *}
		{if $smarty.session.site_shop_reguserbuy == 0}
		<div>
			<form {$form_shop_notreg.attributes}>
			{$form_shop_notreg.hidden}
			<dl>
				<dt class="table_th" style="width: 793px; background-color: #4A4A4A; margin-bottom: 2px;">{$locale.index_shop.account_notreg_header3}</dt><dd></dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.user_name.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.user_name.label}</span></dt>
				<dd>{$form_shop_notreg.user_name.html}{if $form_shop_notreg.user_name.error}<span class="error">{$form_shop_notreg.user_name.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.email.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.email.label}</span></dt>
				<dd>{$form_shop_notreg.email.html}{if $form_shop_notreg.email.error}<span class="error">{$form_shop_notreg.email.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.phone.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.phone.label}</span></dt>
				<dd>{$form_shop_notreg.phone.html}{if $form_shop_notreg.phone.error}<span class="error">{$form_shop_notreg.phone.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.shipaddress.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.shipaddress.label}</span></dt>
				<dd>{$form_shop_notreg.shipaddress.html}{if $form_shop_notreg.shipaddress.error}<span class="error">{$form_shop_notreg.shipaddress.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.shipzip.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.shipzip.label}</span></dt>
				<dd>{$form_shop_notreg.shipzip.html}{if $form_shop_notreg.shipzip.error}<span class="error">{$form_shop_notreg.shipzip.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.shipcity.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.shipcity.label}</span></dt>
				<dd>{$form_shop_notreg.shipcity.html}{if $form_shop_notreg.shipcity.error}<span class="error">{$form_shop_notreg.shipcity.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.shipcountry.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.shipcountry.label}</span></dt>
				<dd>{$form_shop_notreg.shipcountry.html}{if $form_shop_notreg.shipcountry.error}<span class="error">{$form_shop_notreg.shipcountry.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.shipaddr.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.shipaddr.label}</span></dt>
				<dd>{$form_shop_notreg.shipaddr.html}{if $form_shop_notreg.shipaddr.error}<span class="error">{$form_shop_notreg.shipaddr.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.copyaddr.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.copyaddr.label}</span></dt>
				<dd>{$form_shop_notreg.copyaddr.html}{if $form_shop_notreg.copyaddr.error}<span class="error">{$form_shop_notreg.copyaddr.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.postaddress.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.postaddress.label}</span></dt>
				<dd>{$form_shop_notreg.postaddress.html}{if $form_shop_notreg.postaddress.error}<span class="error">{$form_shop_notreg.postaddress.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.postzip.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.postzip.label}</span></dt>
				<dd>{$form_shop_notreg.postzip.html}{if $form_shop_notreg.postzip.error}<span class="error">{$form_shop_notreg.postzip.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.postcity.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.postcity.label}</span></dt>
				<dd>{$form_shop_notreg.postcity.html}{if $form_shop_notreg.postcity.error}<span class="error">{$form_shop_notreg.postcity.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.postcountry.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.postcountry.label}</span></dt>
				<dd>{$form_shop_notreg.postcountry.html}{if $form_shop_notreg.postcountry.error}<span class="error">{$form_shop_notreg.postcountry.error}</span>{/if}</dd>
				<dt style="padding-left: 10px;">{if $form_shop_notreg.postaddr.required}<span class="required">*</span>{/if}<span class="form_text">{$form_shop_notreg.postaddr.label}</span></dt>
				<dd>{$form_shop_notreg.postaddr.html}{if $form_shop_notreg.postaddr.error}<span class="error">{$form_shop_notreg.postaddr.error}</span>{/if}</dd>
			{if $form_shop_notreg.requirednote and not $form_shop_notreg.frozen}
				<div style="padding: 3px 0 3px 10px;"><span class="form_text">{$form_shop_notreg.requirednote}</span></div>
			{/if}
			<div style="padding: 3px 0 3px 10px;">{$form_shop_notreg.submit.html} {$form_shop_notreg.reset.html}</div>
			</dl>
			</form>
		</div>
		{/if}
        {* NEM REGISZTRALT VASARLO VEGE *}

	</div>
	<div id="form_bottom"></div>
</div>
