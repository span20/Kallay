{if $smarty.session.user_id}
	
	<div style="text-align: center;">
		Bel�pve: {$smarty.session.usermail} | <a href="index.php?p=account&act=account_out">Kil�p�s</a>
		<br />
		<br />
		{if $smarty.request.error}
			{if $smarty.request.error == 4}
				<div style="color: #5A9700; padding: 10px;">Sikeres felt�lt�s!</div>
			{/if}
			{if $smarty.request.error == 1}
				<div style="color: #ff0000; padding: 10px;">V�lassz f�jlt!</div>
			{/if}
			{if $smarty.request.error == 2}
				<div style="color: #ff0000; padding: 10px;">Hiba a felt�lt�s sor�n!</div>
			{/if}
			{if $smarty.request.error == 3}
				<div style="color: #ff0000; padding: 10px;">Hib�s file form�tum! (avi, mp4, flv, mpg, wmv enged�lyezett)</div>
			{/if}
		{/if}
		<form method="post" enctype="multipart/form-data" action="">
			Vide� felt�lt�s:<br /><br />
			<input type="file" name="vidfile" /><br /><br />
			<input type="submit" name="submitted" value="Felt�lt�s" />
		</form>
		{if $uvids}
			<div style="padding: 10px;">
				Felt�lt�tt vide�k:<br /><br />
				{foreach from=$uvids item=data}
					<div>{$data.videofile}</div>
				{/foreach}
			</div>
		{/if}
	</div>
{else}
	<div style="clear: both; text-align: center;">
		Vide� felt�lt�s�hez jelentkezz be!
	</div>
	{assign var="prevpage" value="video"}
	{include file="block_account.tpl"}
{/if}