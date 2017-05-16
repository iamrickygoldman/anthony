jQuery(function($){
	$(document).ready(function(){
		var options = {
			//events_source: function() { return []; },
			events_source: '/datebook/get',
			view: 'month',
			tmpl_path: '/modules/custom/datebook/assets/tmpls/',
			tmpl_cache: false,
			onAfterEventsLoad: function(events) {
				if(!events) {
					return;
				}
				var list = $('#eventlist');
				list.html('');

				$.each(events, function(key, val) {
					$(document.createElement('li'))
						.html('<h4>' + val.title + '</h4><p>' + val.description + '</p>')
						.appendTo(list);
				});
			},
			onAfterViewLoad: function(view) {
				$('.calendar-title').text(this.getTitle());
				$('button').removeClass('active');
				$('button[data-calendar-view="' + view + '"]').addClass('active');
			},
			classes: {
				months: {
					general: 'label'
				}
			}
		};

		var calendar = $('#calendar').calendar(options);

		$('button[data-calendar-nav]').each(function() {
			var $this = $(this);
			$this.click(function() {
				calendar.navigate($this.data('calendar-nav'));
			});
		});

		$('button[data-calendar-view]').each(function() {
			var $this = $(this);
			$this.click(function() {
				calendar.view($this.data('calendar-view'));
			});
		});
	});
});