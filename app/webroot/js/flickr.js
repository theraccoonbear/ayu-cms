var flickrController = {};

$(function() {
	
	var callback = function() { };
	if (typeof flickrController.ready == 'function') {
		callback = flickrController.ready;
	}
	
	flickrController = {
		$head: null,
		current_page: 1,
		total_pages: 0,
		photo_tmpl: '',
		$target: null,
		ready: callback,
		api: {
			key: null,
			secret: null,
			initialized: false
		},
		init: function(opts) {
			flickrController.api.key = opts.api_key;
			flickrController.api.secret = opts.api_secret;
			flickrController.$target = $(opts.target);
			flickrController.$head = $('head');
			flickrController.photo_tmpl = opts.photo_tmpl;
			flickrController.api.initialized = true;
		},
		search: function(search_for) {
			if (flickrController.api.initialized) {
				var url = flickrController.searchURL(search_for, 'flickrController.apiCallback', flickrController.current_page);
				flickrController.$head.append('<script id="embeddedFlickrSearch" type="text/javascript" src="' + url + '"></sc' + 'ript>');
			} else {
				alert("Flickr embed not initialized");
			}
		},
		searchURL: function(term, callback, page_number) {
			callback = typeof callback == 'undefined' ? 'jsonFlickrApi' : callback;
			return 'http://api.flickr.com/services/rest/?format=json&method=flickr.photos.search&extra=owner_name&per_page=100&sort=relevance&text=' + term + '&api_key=' + flickrController.api.key + '&page=' + page_number + '&jsoncallback=' + callback;
		},
		error: function(msg) {
			if (console) { console.log('ERROR: ' + msg); }
		},
		apiCallback: function(rsp) {
			
			if (rsp.stat != "ok"){
				flickrController.error('Flickr API call failure');
				return;
			}
			
			var $photo_list = $('<ul></ul>');
			$photo_list.addClass('flickrGallery');
			
			if (rsp.photos && rsp.photos.photo) {
			  flickrController.total_pages = rsp.photos.pages;
				
				for (var i=0; i < rsp.photos.photo.length; i++) {
					var photo = rsp.photos.photo[i];
					
					photo.idx = i;
					photo.thumb = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + "_s.jpg";
					photo.full = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + ".jpg";
					photo.url = 'http://www.flickr.com/photos/' + photo.owner + '/' + photo.id;
					rsp.photos.photo[i] = photo;
					
					var markup = Mustache.render(flickrController.photo_tmpl, photo);
					
					$photo_list.append(markup);
				} // for(;;)
				
				flickrController.$target.append($photo_list);
				
				$("li.photo a:has(img)").fancybox({
					'titlePosition': 'inside',
					'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
						var $elem = $(currentArray[currentIndex]);
						var $info = $elem.find('ul.photoInfo');
						var info = {
						  owner: $info.children('li.owner').html(),
							idx: $info.children('li.idx').html(),
							id:  $info.children('li.id').html()
						};
						
						var lbl = '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + '<br/>';
						lbl += (title.length ? ' &nbsp; ' + title : '') + '<br/>';
						lbl += '<a href="http://www.flickr.com/photos/' + info.owner + '/' + info.id + '" target="_blank" style="color: #fff;">View on Flickr</a>';
						lbl += '</span>'
						return lbl
					}
				}); // fancybox
				
			} // photos?
		}
	}; // flickrController
	
	if (typeof flickrController.ready == 'function') {
		flickrController.ready();
	}
});