{if $smarty.session.user_id}
	
	<div style="text-align: center;">
		Belépve: {$smarty.session.usermail} | <a href="index.php?p=account&act=account_out">Kilépés</a>
		<br />
		<br />
		{if $smarty.request.error}
			{if $smarty.request.error == 4}
				<div style="color: #5A9700; padding: 10px;">Sikeres feltöltés!</div>
			{/if}
			{if $smarty.request.error == 1}
				<div style="color: #ff0000; padding: 10px;">Válassz fájlt!</div>
			{/if}
			{if $smarty.request.error == 2}
				<div style="color: #ff0000; padding: 10px;">Hiba a feltöltés során!</div>
			{/if}
			{if $smarty.request.error == 3}
				<div style="color: #ff0000; padding: 10px;">Hibás file formátum! (avi, mp4, flv, mpg, wmv engedélyezett)</div>
			{/if}
		{/if}
		<form method="post" enctype="multipart/form-data" action="">
			Videó feltöltés:<br /><br />
			<input type="file" name="vidfile" /><br /><br />
			<input type="submit" name="submitted" value="Feltöltés" />
		</form>
		{if $uvids}
			<div style="padding: 10px;">
				Feltöltött videók:<br /><br />
				{foreach from=$uvids item=data}
					<div>{$data.videofile}</div>
				{/foreach}
			</div>
		{/if}
	</div>
{else}
	<div style="clear: both; text-align: center;">
		Videó feltöltéséhez jelentkezz be!
	</div>
	{assign var="prevpage" value="video"}
	{include file="block_account.tpl"}
{/if}