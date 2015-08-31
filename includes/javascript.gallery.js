var galleries_loaded = [];
var api_images = [], api_titles = [], api_descriptions = [];

$(document).ready(function() {
	
	$.fn.prettyPhoto();
	
	$('.gallery_item a').click(function(e) {
		e.preventDefault();
	});
	
	$('.gallery_item').click(function(e) {
		e.stopPropagation();
		var that = $(this);
		var gal_id = that.data('galleryid');
		
		if (galleries_loaded.indexOf(gal_id) == -1) {		
		
			api_images[gal_id] = [];
			api_titles[gal_id] = [];
			api_descriptions[gal_id] = [];
		
			$.ajax({
				url: 'ajax.php?act=show_gallery&gal_id='+gal_id,
				dataType: 'json',
				success: function(data) {
					
					for (var i in data) {
						//that.append('<a style="display: none;" rel="prettyPhoto[gal'+gal_id+']" href="files/gallery/'+data[i].realname+'"><img src="files/gallery/tn_'+data[i].realname+'"></a>');
						api_images[gal_id].push('files/gallery/'+data[i].realname);
						api_titles[gal_id].push(data[i].realname);
						api_descriptions[gal_id].push(data[i].realname);
					}
					
					galleries_loaded.push(gal_id);
										
					$.prettyPhoto.open(api_images[gal_id],api_titles[gal_id],api_descriptions[gal_id]);
					//$("a[rel^='prettyPhoto']").prettyPhoto();
				}
			});
		} else {
			$.prettyPhoto.open(api_images[gal_id],api_titles[gal_id],api_descriptions[gal_id]);
		}
	});
});

function show_vids(all,act)
{
	for (i = 1; i <= all; i++){
		var viddiv = document.getElementById('vids_'+i);
		if (i == act) {
			if (viddiv.style.display == 'none') {
				viddiv.style.display = '';
			} else {
				viddiv.style.display = 'none';
			}
		} else {
			viddiv.style.display = 'none';
		}
	}
}

function show_hide(div_id) {
	if (document.getElementById( div_id ).style.display == 'none') {
		document.getElementById( div_id ).style.display = 'block';
	} else {
		document.getElementById( div_id ).style.display = 'none';
	}
}

function gallery_popup(gal_id) {
	window.open('gallery_popup.php?gid='+gal_id, 'Galéria', 'width=700, height=600');
}
