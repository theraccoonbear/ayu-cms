var blogsController = {};

	
$(function() {
	
	var callback = function() { };
	if (typeof blogsController.ready == 'function') {
		callback = blogsController.ready;
	}
	
	blogsController = {
		entries: [],
		$target: null,
		$index_tmpl: null,
		$entry_tmpl: null,
		idx_sel: '',
		ent_sel: '',
		ready: callback,
		init: function(target, idx_sel, ent_sel) {
			this.$target = $(target);
			this.idx_sel = idx_sel;
			this.ent_sel = ent_sel;
			this.$index_tmpl = $(idx_sel);
			this.$entry_tmpl = $(ent_sel);

		},
		addEntries: function(posts) {
			this.entries = posts;
		},
		blogIndex: function() {
			var ctxt = this;
			document.location.hash = '';
			
			
			
			try {
				//var idx = ctxt.$index_tmpl.jqote([ctxt.entries]);
				
				
				var ctxt = blogsController;
				var tmpl = $(ctxt.idx_sel).html();
				var pg = Mustache.render(tmpl, {'posts':ctxt.entries});
				ctxt.$target.html(pg);
			} catch(e) {
				console.log("ERROR!");
				console.log(e);
				console.log(e.stack);
				console.log(tmpl);
				console.log(ctxt.entries[0]);
			}
		},
		getEntryBy: function(field, val) {
			for (var i = 0; i < this.entries.length; i++) {
				if (this.entries[i][field] == val) {
					return this.entries[i];
				}
			}
			return {failed:true};
		},
		showEntry: function(id) {
			var ctxt = this;
			var post = this.getEntryBy('id', id);
			var tmpl;
			var pg;
			if (!post.failed) {
				try {
					tmpl = $(ctxt.ent_sel).html();
					pg = Mustache.render(tmpl, post);
					ctxt.$target.html(pg);
				} catch(e) {
					console.log("ERROR!");
					console.log(e);
					console.log(e.stack);
					console.log(tmpl);
					console.log(post);
				}
			}
		}
	}; // blogsController
	
	if (typeof blogsController.ready == 'function') {
		blogsController.ready();
	}
	
});