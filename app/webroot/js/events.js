var eventsController = {};

$(function() {
	
	var trmv = 'v';	
	$('.' + trmv + 'calendar').remove();
	trmv = 'start'
	$('span.dt' + trmv).remove();
	
	var callback = function() { };
	if (typeof eventsController.ready == 'function') {
		callback = eventsController.ready;
	}
	
	eventsController = {
		$target: null,
		index_tmpl: '',
		entry_tmpl: '',
		tmpl_sel: '',
		events: [],
		ready: callback,
		addEvents: function(e) {
			this.events = e;
		},
		init: function(target, entry_tmpl) {
			this.$target = $(target);
			this.tmpl_sel = entry_tmpl;
			this.makeCalendar();
		},
		makeCalendar: function(options) {
			this.$target.fullCalendar({
				events: eventsController.events,
				eventClick: eventsController.showEvent,
				color: '#fff',
				textColor: '#000'
			});
		},
		showEvent: function(obj, e, mc) {
			document.location = obj.__permalink;
			return;
			var ctxt = eventsController;
			var tmpl = $(ctxt.tmpl_sel).html();
			var pg = Mustache.render(tmpl, obj);

			$.fancybox(
				pg,
				{
					'autoDimensions'	: true,
					'onClosed': function() {
						//document.location.hash = '';
					}
				}
			);
			
		}
	}; // eventsController()
	
	if (typeof eventsController.ready == 'function') {
		eventsController.ready();
	}
});