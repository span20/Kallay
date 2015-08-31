{foreach from=$page_data item=data}
	<div>
		<div class="cont_title">
			<a href="index.php?mid={$smarty.request.mid}&act=show&cid={$data.cid}">{$data.ctitle}</a>
		</div>
		{if $data.cpic}
			<div><img src="files/news/{$data.cpic}" /></div>
		{/if}
		<div>{$data.clead}</div>
	</div>
	<div style="padding: 10px 0;">
		
	</div>
{/foreach}