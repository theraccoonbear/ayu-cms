var __asset_picker_cnt = 0;

function addAsset(attach, data, selected_asset_id, preview_base_url) {
	var $attach = $(attach);
	var $li = $('<li></li>');

	var parts = data.type.toLowerCase().split(/\//);
	var major = parts[0];
	var minor = parts[1];
	var classes = '';
	var li_class = 'asset';
	var keep_open = false;
	var assetLink = '';
	var rel = '';
	
	if (major == 'video') {
		classes += 'video';
	} else if (major == 'image') {
		classes += 'image';
		//assetLink = ' assetLink';
	} else if (major == 'audio') {
		classes += 'audio';
	} else if (major == 'text') {
		if (/html/.exec(minor)) {
			classes += 'html';
		} else {
			classes += 'text';
		}
	}
	
	$icon = $('<span></span>');
	
	$icon.addClass(classes).addClass('icon');
	
	if (selected_asset_id == data.id) {
		li_class += ' selected';
		keep_open = true;
	}
	
	var d_size = (Math.floor(data.size / 100) / 10) + ' KB';
	
	var view_link = '<a href="' + preview_base_url + '/' + data.id + '" class="btnView" title="View" style="float:none !important; display: inline-block !important;" target="_blank">&nbsp;</a>';
	 
	$li.addClass(li_class).attr('id', 'asset-' + data.id).append($icon).append('<a href="#" class="assetName' + assetLink + '" rel="' + data.id + '">' + data.name + '</a>&nbsp;(<span class="assetSize">' + d_size + '</span>)&nbsp;' + view_link);
	$attach.append($li);
	
	return keep_open;
} 

function buildFileTree(attach, data, selected_asset_id, preview_base_url) {
	
	if (typeof selected_asset_id == 'undefined') {
		selected_asset_id = '';
	}
	
	var $attach = $(attach);
	
	var keep_open = false;
	var $li = $('<li></li>');
	
	$icon = $('<span></span>');
	$icon.addClass('icon folder');
	
	$li.addClass('folder').attr('id', 'folder-' + data.id).append($icon).append('<a class="folderName" href="#">' + data.name + '</a>');
	
	var $ul = $('<ul></ul>');
	
	$li.append($ul);
	
	for (var i = 0; i < data.folders.length; i++) {
		var opened = buildFileTree($ul, data.folders[i], selected_asset_id, preview_base_url);
		keep_open = keep_open || opened;
	}
	
	for (var i = 0; i < data.assets.length; i++) {
		var opened = addAsset($ul, data.assets[i], selected_asset_id, preview_base_url);
		keep_open = keep_open || opened;
	}
	
	if (!keep_open) {
		$li.addClass('closed');
	}
	
	if (data.assets.length == 0 && data.folders.length == 0) {
		$ul.append('<li class="empty"><em>Empty</em></li>');
	}
	
	$attach.append($li);
	return keep_open;
} // buildFileTree()

function registerAssetPickerHandlers(attach, target) {
	$(attach + ' .folder > a.folderName').click(function(e) {
		$ctxt = $(this);
		$parent = $ctxt.parent('.folder');
		
		$parent.toggleClass('closed');
		
		e.preventDefault();
		
	});
	
	$(attach + ' .asset > a.assetName').click(function(e) {
		$ctxt = $(this);
		
		$(attach).parent().find('.selectedAsset').html(' > ' + $ctxt.html());;
		$parent = $ctxt.parent('li');
		
		$(attach + ' li.asset.selected').removeClass('selected');
		$parent.addClass('selected');
		
	  $(target).attr('value', $(this).attr('rel'));
		
		$('#addSlideButton').prop('disabled', false);
		
		e.preventDefault();
	});
	
	$(attach).parent('.assetPicker').find('.deselect').click(function(e) {
		$(attach).parent().find('.selectedAsset').html('');
		$(attach + ' li.asset.selected').removeClass('selected');
		$(target).attr('value', 0);
		e.preventDefault();
	});
} // registerAssetPickerHandlers()

function buildAssetPicker(attach, data, selected_asset_id, target, preview_base_url) {
	buildFileTree(attach, data, selected_asset_id, preview_base_url);
	registerAssetPickerHandlers(attach, target);
} // buildAssetPicker()