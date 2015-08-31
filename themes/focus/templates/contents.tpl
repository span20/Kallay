<div class="cont_text {if $heading_color}{$heading_color}{/if}">
	{if !empty($content_content2)}
		<div class="col-md-6">
			{$content_content}
		</div>
		<div class="col-md-6">
			{$content_content2}
		</div>
	{else}
		<div class="col-md-12">
			{$content_content}
		</div>
	{/if}
</div>