jQuery(function($){
	$(document).ready(function(){
		// Datebook Calendar
		var options = {
			//events_source: function() { return []; },
			events_source: '/datebook/get',
			modal: '#events-modal',
			modal_type: 'ajax',
			modal_title: function(event) { return event.title },
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

		// Datetimepicker
		$('.datetimepicker').datetimepicker({
			step: 30,
			theme: 'dark',
			yearStart: 2010,
			formatTime:'g:i A', 
			format: 'm/d/Y h:i A',
		});
	});
});