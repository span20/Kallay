<div id="form_cnt">
	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="{$lang_title}">{$lang_title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="f_content">
		<div class="f_empty"></div>
		<form {$form.attributes}>
		{$form.hidden}
		<table>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.column.required}<span class="error">*</span>{/if}{$form.column.label}</td>
				<td>{$form.column.html}{if $form.column.error}<span class="error">{$form.column.error}</span>{/if}</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.inside_columns.required}<span class="error">*</span>{/if}{$form.inside_columns.label}</td>
				<td>{$form.inside_columns.html}{if $form.inside_columns.error}<span class="error">{$form.inside_columns.error}</span>{/if}</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.menu_pos.required}<span class="error">*</span>{/if}{$form.menu_pos.label}</td>
				<td>{$form.menu_pos.html}
					<div id="1" style="display: none;">
						<input type="hidden" name="1_col" id="1_col" value="{$menu_pos_sel}">
						<select id="1_newsel" name="menu_pos_sel">
						</select>
					</div>
				{if $form.menu_pos.error}<span class="error">{$form.menu_pos.error}</span>{/if}</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.module_id.required}<span class="error">*</span>{/if}{$form.module_id.label}</td>
				<td>{$form.module_id.html}
					<div id="2" style="display: none;">
						<input type="hidden" name="2_col" id="2_col" value="{$module_id_sel}">
						<select id="2_newsel" name="module_id_sel">
						</select>
					</div>
				{if $form.module_id.error}<span class="error">{$form.module_id.error}</span>{/if}</td>
			</tr>
			{if $form.block.html}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.block.required}<span class="error">*</span>{/if}{$form.block.label}</td>
				<td>{$form.block.html}
					<div id="7" style="display: none;">
						<input type="hidden" name="7_col" id="7_col" value="{$block_sel}">
						<select id="7_newsel" name="block_sel">
						</select>
					</div>
				{if $form.block.error}<span class="error">{$form.block.error}</span>{/if}</td>
			</tr>
			{/if}
			{if $form.content_id.html}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.content_id.required}<span class="error">*</span>{/if}{$form.content_id.label}</td>
				<td>{$form.content_id.html}
					<div id="3" style="display: none;">
						<input type="hidden" name="3_col" id="3_col" value="{$content_id_sel}">
						<select id="3_newsel" name="content_id_sel">
						</select>
					</div>
				{if $form.content_id.error}<span class="error">{$form.content_id.error}</span>{/if}</td>
			</tr>
			{/if}
			{if $form.category_id.html}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.category_id.required}<span class="error">*</span>{/if}{$form.category_id.label}</td>
				<td>{$form.category_id.html}
					<div id="4" style="display: none;">
						<input type="hidden" name="4_col" id="4_col" value="{$category_id_sel}">
						<select id="4_newsel" name="category_id_sel">
						</select>
					</div>
				{if $form.category_id.error}<span class="error">{$form.category_id.error}</span>{/if}</td>
			</tr>
			{/if}
			{if $form.banner_pos.html}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.banner_pos.required}<span class="error">*</span>{/if}{$form.banner_pos.label}</td>
				<td>{$form.banner_pos.html}
					<div id="5" style="display: none;">
						<input type="hidden" name="5_col" id="5_col" value="{$banner_pos_sel}">
						<select id="5_newsel" name="banner_pos_sel">
						</select>
					</div>
				{if $form.banner_pos.error}<span class="error">{$form.banner_pos.error}</span>{/if}</td>
			</tr>
			{/if}
			{if $form.gallery_id.html}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form.gallery_id.required}<span class="error">*</span>{/if}{$form.gallery_id.label}</td>
				<td>{$form.gallery_id.html}
					<div id="6" style="display: none;">
						<input type="hidden" name="6_col" id="6_col" value="{$gallery_id_sel}">
						<select id="6_newsel" name="gallery_id_sel">
						</select>
					</div>
				{if $form.gallery_id.error}<span class="error">{$form.gallery_id.error}</span>{/if}</td>
			</tr>
			{/if}
			<tr class="row1">
				<td class="form" colspan="2">
					{if not $form.frozen}
						{if $form.requirednote}{$form.requirednote}{/if}
						{$form.submit.html}{$form.reset.html}
					{/if}
				</td>
			</tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>
