<div id="form_cnt">
    {include file="admin/dynamic_tabs.tpl"}
	<div id="f_content">
		<div class="t_filter">
            <h3 style="margin:0;">{$lang_title|upper}</h3>
        </div>
		<div class="pager"></div>
		<table style="clear: both;">
            <tr class="row2">
                <td class="form" style="width: 150px;">{$locale.admin_contents.mtinews_field_id}</td>
                <td>{$smarty.get.cid}</td>
            </tr>
			<tr class="row1">
				<td class="form" style="width: 150px;">{$locale.admin_contents.mtinews_field_title}</td>
				<td>{$elements.title}</td>
			</tr>
            <tr class="row2">
                <td class="form">{$locale.admin_contents.mtinews_field_section}</td>
                <td>{$elements.mainsection}</td>
            </tr>
            <tr class="row1">
                <td class="form">{$locale.admin_contents.mtinews_field_cdate}</td>
                <td>{$elements.createdate}</td>
            </tr>
            <tr class="row2">
                <td class="form">{$locale.admin_contents.mtinews_field_mdate}</td>
                <td>{$elements.modifieddate}</td>
            </tr>
			<tr class="row1">
				<td class="form">{$locale.admin_contents.mtinews_field_lead}</td>
				<td>{$elements.lead|nl2br}</td>
			</tr>
            <tr class="row2">
                <td class="form">{$locale.admin_contents.mtinews_field_picture}</td>
                <td>
                    {if $elements.image != ""}
                        <img src="admin.php?p=contents&amp;act=mtinews&amp;sub_act=show&amp;pic={$elements.image|urlencode|htmlspecialchars}" alt="{$elements.title}" />
                    {else}
                    -
                    {/if}
                </td>
            </tr>
            <tr class="row1">
                <td class="form">{$locale.admin_contents.mtinews_field_body}</td>
                <td>{$elements.body|nl2br}</td>
            </tr>
		</table>
        <div class="pager"></div>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>