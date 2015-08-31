<div id="form">
	<div id="form_top">
		<span style="padding-left: 10px;"></span>
	</div>
	<div id="form_cnt">
		<form {$form_class.attributes}>
		{$form_class.hidden}
		<table cellspacing="0" class="table_main">
			{if $form_class.act_code.html}
			<tr>
				<td>{if $form_class.act_code.required}<span class="required">*</span>{/if}{$form_class.act_code.label}</td>
				<td>{$form_class.act_code.html}{if $form_class.act_code.error}<span class="error">{$form_class.act_code.error}</span>{/if}</td>
			</tr>
			{/if}
			{if $form_class.languages.html}
			<tr>
				<td>{if $form_class.languages.required}<span class="required">*</span>{/if}{$form_class.languages.label}</td>
				<td>{$form_class.languages.html}{if $form_class.languages.error}<span class="error">{$form_class.languages.error}</span>{/if}</td>
			</tr>
			{/if}
			{if $form_class.class_autocat.html}
			<tr>
				<td>{if $form_class.class_autocat.required}<span class="required">*</span>{/if}{$form_class.class_autocat.label}</td>
				<td>{$form_class.class_autocat.html}{if $form_class.class_autocat.error}<span class="error">{$form_class.class_autocat.error}</span>{/if}</td>
			</tr>
			{/if}
			<tr>
				<td>{if $form_class.class_category.required}<span class="required">*</span>{/if}{$form_class.class_category.label}</td>
				<td>{$form_class.class_category.html}{if $form_class.class_category.error}<span class="error">{$form_class.class_category.error}</span>{/if}</td>
			</tr>
			<tr>
				<td>{if $form_class.class_section.required}<span class="required">*</span>{/if}{$form_class.class_section.label}</td>
				<td>{$form_class.class_section.html}{if $form_class.class_section.error}<span class="error">{$form_class.class_section.error}</span>{/if}</td>
			</tr>
			<tr>
				<td>{if $form_class.class_name.required}<span class="required">*</span>{/if}{$form_class.class_name.label}</td>
				<td>{$form_class.class_name.html}{if $form_class.class_name.error}<span class="error">{$form_class.class_name.error}</span>{/if}</td>
			</tr>
			<tr>
				<td>{if $form_class.class_phone.required}<span class="required">*</span>{/if}{$form_class.class_phone.label}</td>
				<td>{$form_class.class_phone.html}{if $form_class.class_phone.error}<span class="error">{$form_class.class_phone.error}</span>{/if}</td>
			</tr>
			<tr>
				<td>{if $form_class.class_mail.required}<span class="required">*</span>{/if}{$form_class.class_mail.label}</td>
				<td>{$form_class.class_mail.html}{if $form_class.class_mail.error}<span class="error">{$form_class.class_mail.error}</span>{/if}</td>
			</tr>
			<tr>
				<td>{if $form_class.class_price.required}<span class="required">*</span>{/if}{$form_class.class_price.label}</td>
				<td>{$form_class.class_price.html}{if $form_class.class_price.error}<span class="error">{$form_class.class_price.error}</span>{/if}</td>
			</tr>
			<tr>
				<td>{if $form_class.date_end.required}<span class="required">*</span>{/if}{$form_class.date_end.label}</td>
				<td>{$form_class.date_end.html}{if $form_class.date_end.error}<span class="error">{$form_class.date_end.error}</span>{/if}</td>
			</tr>
			<tr>
				<td>{if $form_class.class_desc.required}<span class="required">*</span>{/if}{$form_class.class_desc.label}</td>
				<td>{$form_class.class_desc.html}{if $form_class.class_desc.error}<span class="error">{$form_class.class_desc.error}</span>{/if}</td>
			</tr>
			{if $smarty.session.site_class_advpicnum}
				{section name=pic loop=$smarty.session.site_class_advpicnum+1 start=1 step=1}
					{assign var=picname value=picture`$smarty.section.pic.index`}
					<tr>
						<td>{if $form_class.$picname.required}<span class="required">*</span>{/if}{$form_class.$picname.label}</td>
						<td>{$form_class.$picname.html}{if $form_class.$picname.error}<span class="error">{$form_class.$picname.error}</span>{/if}</td>
					</tr>
				{/section}
				{section name=oldpic loop=$smarty.session.site_class_advpicnum+1 start=1 step=1}
					{assign var=picname value=pic`$smarty.section.oldpic.index`}
					{assign var=delpic value=delpic`$smarty.section.oldpic.index`}
					<tr>
						<td>{$form_class.$picname.label}</td>
						<td>{$form_class.$picname.html} {$form_class.$delpic.html}</td>
					</tr>
				{/section}
			{/if}
			{if $class_captcha}
				<tr>
					<td></td>
					<td><img src={$class_captcha} border="0" alt="captcha"></td>
				</tr>
				<tr>
					<td>{if $form_class.recaptcha.required}<span class="required">*</span>{/if}{$form_class.recaptcha.label}</td>
					<td>{$form_class.recaptcha.html}{if $form_class.recaptcha.error}<span class="error">{$form_class.recaptcha.error}</span>{/if}</td>
				</tr>
			{/if}
			{if $form_class.requirednote and not $form_class.frozen}
				<tr><td colspan="2"><span class="form_text">{$form_class.requirednote}</span></td></tr>
			{/if}
			<tr><td colpsan="2">{$form_class.submit.html} {$form_class.reset.html}</td></tr>
		</table>
		</form>
	</div><br />
	<div id="form_bottom"></div>
</div>
