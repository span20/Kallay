{foreach from=$picgals item=data}
	<div class="col-md-4 gallery_item" data-galleryid="{$data.gallery_id}">
		<a href="files/gallery/{$data.realname}" rel="prettyPhoto[gal{$data.gallery_id}]"><img src="files/gallery/tn_{$data.realname}" class="img-responsive" /></a>
		<div class="gal_title">
			{$data.name}
		</div>
	</div>
{/foreach}