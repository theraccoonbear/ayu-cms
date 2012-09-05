var grTPCtl = grTPCtl || {};

(function() {
	var readyHandler = function() { /* ... */ };
	if (typeof grTPCtl.ready === 'function') {
		readyHandler = grTPCtl.ready;
	}
	
	grTPCtl = {
		ready: readyHandler,
		datePicker: null,
		plan: [],
		chart: null,
		charts: false,
		chartData: null,
		initialized: false,
		common: {
			distances: {
				km: [1,2,3,4,5,6,7,8,9,10,20,30,40],
				miles: [1,2,3,4,5,6,7,8,9,10,13.1,15,20,26.2]
			},
			conversion: {
				kmToMiles: 0.621371192,
				milesToKm: 1.609344
			},
			units: {
				MILES: 1,
				KM: 2
			},
			DOW: ['sun','mon','tue','wed','thu','fri','sat']
		},
		config: {
			units: 1,
			days: {
				mon: true,
				tue: false,
				wed: true,
				thu: false,
				fri: false,
				sat: true,
				sun: false
			},
			distance: 13.1,
			base: 5
		},
		date: {
			start: null,
			end: null,
			elapsed: 0
		},
		setDates: function(start, end) {
			grTPCtl.date.start = start;
			grTPCtl.date.end = end;
			grTPCtl.date.elapsed = Math.floor((end - start) / 1000 / 60 / 60 / 24) + 1; // ms to days (made inclusive)	
			$('#dateRange').html('Training will be from <strong>' + start.format('ddd, mmm. dS, yyyy') + '</strong> to <strong>' + end.format('ddd, mmm dS, yyyy') + '</strong> (<strong>' + grTPCtl.date.elapsed + '</strong> days, <strong>' + Math.ceil(grTPCtl.date.elapsed / 7) + '</strong> weeks)');
		},
		datesChanged: function () {
			var dates = this.getSelectedAsDates();
			if (dates.length < 2) { return; }
			grTPCtl.setDates(dates[0], dates[1]);
			grTPCtl.rebuildPlan();
			this.setSelected([grTPCtl.date.start, grTPCtl.date.end]);
		},
		useParams: function() {
			grTPCtl.initialized = false;
			var hash = grTPCtl.params;
			if (hash.length > 0) {
				hash = hash.substring(1)
				var fields = hash.split('&');
				var setFields = {};
				
				for (var i = 0; i < fields.length; i++) {
					var f = fields[i];
					var fs = f.split('=');
					if (fs.length == 2) {
						var name = fs[0];
						var val = fs[1];
						setFields[name] = val;
					}
				}
				
				if (typeof setFields.useMiles !== 'undefined') {
					var check_it = setFields.useMiles === 'true';
					var is_now = $('#useMiles').prop('checked');
					if (is_now != check_it) {
						$('#useMiles').click();
					}
				}
				if (typeof setFields.programType !== 'undefined') { $('#programType')[0].selectedIndex = parseInt(setFields.programType); }
				if (typeof setFields.raceDistance !== 'undefined') { $('#raceDistance')[0].selectedIndex = parseInt(setFields.raceDistance); }
				if (typeof setFields.baseDistance !== 'undefined') { $('#baseDistance')[0].selectedIndex = parseInt(setFields.baseDistance); }
				if (typeof setFields.taperLength !== 'undefined') { $('#taperLength')[0].selectedIndex = parseInt(setFields.taperLength); }
				if (typeof setFields.startDate !== 'undefined' && typeof setFields.endDate !== 'undefined') {
					var s_date = new Date(setFields.startDate * 1000);
					var e_date = new Date(setFields.endDate * 1000);
					grTPCtl.datePicker.setSelected([s_date, e_date]);
					grTPCtl.setDates(s_date, e_date);
				}
				if (typeof setFields.daysToTrain !== 'undefined') {
					$('.trainingDay').prop('checked', false);
					var days = setFields.daysToTrain.split(',');
					for (var i = 0; i < days.length; i++) {
						var d = days[i];
						$('#train' +  d).prop('checked', true);
					}
				}
				
				
			}
			grTPCtl.initialized = true;
		},
		updateURL: function() {
			var params = '';
			var avail_days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
			var days = [];
			for (var i = 0; i < avail_days.length; i++) {
				var d = avail_days[i];
				if ($('#train' + d).is(':checked')) { days.push(d); }
			}
			
			params += 'programType=' + $('#programType').prop('selectedIndex');
			params += '&raceDistance=' + $('#raceDistance').prop('selectedIndex');
			params += '&baseDistance=' + $('#baseDistance').prop('selectedIndex');
			params += '&useMiles=' + (grTPCtl.config.units === grTPCtl.common.units.MILES ? 'true' : 'false');
			params += '&startDate=' + Math.floor(grTPCtl.date.start.getTime() / 1000);
			params += '&endDate=' + Math.floor(grTPCtl.date.end.getTime() / 1000);
			params += '&taperLength=' + $('#taperLength').prop('selectedIndex');
			params += '&daysToTrain=' + days.join(',');
			
			
			window.location.hash = params;
		},
		rebuildPlan: function() {
			if (!grTPCtl.initialized) { return; }
			grTPCtl.plan = [];
			
			grTPCtl.updateURL();
			
			var program_type = $('#programType').val();
			var total_dist = parseFloat($('#raceDistance').val());
			var base = parseFloat($('#baseDistance').val());
			var dist = parseFloat($('#raceDistance').val()) - (program_type == 'aggressive' ? base : 0);
			
			var in_miles = grTPCtl.config.units == grTPCtl.common.units.MILES;
			var dist_in_miles = !in_miles ? dist * grTPCtl.common.conversion.kmToMiles : dist;
			var dist_mod = 1;
			if (dist_in_miles <= 5) {
				dist_mod = program_type != 'aggressive' ? 1.2 : 1.5;
			} else if (dist_in_miles > 5 && dist_in_miles <= 10) {
				dist_mod = program_type != 'aggressive' ? 0.7 : 1.25;
			} else if (dist_in_miles > 10 && dist_in_miles <= 13.1) {
				dist_mod = program_type != 'aggressive' ? 0.3 : 1;
			} else {
				dist_mod = program_type != 'aggressive' ? 0 : 0.9;
			}
			
			
			var tr_date = new Date(grTPCtl.date.start);
			var elap = grTPCtl.date.elapsed;
			var taper_length = $('#taperLength').val();
			
			var total = 0;
			var weekly = 0;
			var chart_rows = [];
			
			
			var $parameters = $('#parameters');
			var tparams = '<strong>Target Distance:</strong> ' + total_dist.toString() + (in_miles ? ' miles' : 'km') + ', ' +
										'<strong>Base Distance:</strong> ' +  base.toString() + (in_miles ? ' miles' : 'km') + ', ' +
									  '<strong>Training:</strong> ' + elap + ' days (' + Math.ceil(elap / 7) + ' weeks), ' +
									  '<strong>Taper:</strong> ' + taper_length + ' days';
			
			$parameters.html(tparams);
			
			
			for (var i = 0; i < elap - 1; i++) {
				var dow = tr_date.format('ddd').toLowerCase();
				var dow_idx = tr_date.getDay();
				
				if (grTPCtl.config.days[dow] === true) {
					var tpc = i / (elap - 1);
					var days_left = elap - i;
					var taper = days_left < taper_length ? days_left / taper_length : 1;
					var in_taper = taper == 1 ? false : true;
					
					var weekday = (dow != 'sun' && dow != 'sat') ? 0.5 : 1;
					//var dist_today = (tpc * (dist * dist_mod) * weekday * taper) + base;
					//var dist_today = (((dist - base) * dist_mod) * weekday * tpc * taper) + base;
					
					var dist_today = 0;
					if (program_type == 'aggressive') {
						dist_today = (tpc * (dist * dist_mod) * weekday * taper) + base;
					} else {
						dist_today = Math.max(dist * tpc, base) * ((dist_mod * tpc) + 1) * weekday * taper;
					}
					
					dist_today = dist_today < 1 ? 1 : dist_today;
					total += dist_today;
					weekly += dist_today;
			
					var str_date = new Date(tr_date);
					
					grTPCtl.plan.push({'date':tr_date.format("ddd. mmm. d'<sup>'S'</sup>', yyyy"),'dateObj':str_date,'dow':dow_idx,'dist':dist_today,'taper':in_taper});
					
					chart_rows.push([tr_date.format('ddd. m/d'), dist_today, weekly, total]);
					
				}
				
				if (dow_idx == 6) {
					weekly = 0;
				}
				
				tr_date.setDate(tr_date.getDate() + 1);
				
				
			}
			
			if (google && google.visualization && google.visualization.DataTable) {
				grTPCtl.chartData = new google.visualization.DataTable();
				grTPCtl.chartData.addColumn('string', 'Day');
				grTPCtl.chartData.addColumn('number', 'Daily');
				grTPCtl.chartData.addColumn('number', 'Weekly');
				grTPCtl.chartData.addColumn('number', 'Total');
				
				grTPCtl.chartData.addRows(chart_rows);
			}
			
			grTPCtl.iCalBuild();
			grTPCtl.displayPlan();
			
		},
		displayPlan: function() {
			var in_miles = grTPCtl.config.units == grTPCtl.common.units.MILES;
			var units = in_miles ? 'Miles' : 'Km';
			
			var plan_mkup = [];
			plan_mkup.push('<thead><tr><th>&#10004;</th><th>#</th><th>Week</th><th>Date</th><th>Distance</th><th>' + units + '/wk</th><th>' + units + '/total</th></tr></thead>');
			
			var weekly = 0;
			var total = 0;
			var last_dow = -1;
			var week_cnt = 1;
			var last_week = 0;
			var weekly_jump = '';
			var dist = parseFloat($('#raceDistance').val());
			
			plan_mkup.push('<tbody>');
			
			for (var i = 0; i < grTPCtl.plan.length; i++) {
				var tr_classes = i % 2 == 0 ? 'even' : 'odd';
				var day = grTPCtl.plan[i];
				
				
				if (last_dow >= day.dow) {
					last_week = weekly;
					weekly = 0;
					tr_classes += ' weekchange';
					week_cnt++;
				}
				
				if (weekly - last_week > Math.min(dist, 10) && week_cnt > 1) {
					$('#dateNote').addClass('warning');
					weekly_jump = ' jump';
				} else {
					$('#dateNote').removeClass('warning');
					weekly_jump = '';
				}
				
				tr_classes += weekly_jump;
				weekly += day.dist;
				total += day.dist;
				
				if (day.taper) {
					tr_classes += ' tapering';
				}
				var day_units = (in_miles ? 'mile' : 'km') + (day.dist != 1 ? 's' :'');
				var week_units = (in_miles ? 'mile' : 'km') + (weekly != 1 ? 's' :'')
				var total_units = (in_miles ? 'mile' : 'km') + (total != 1 ? 's' :'')
				
				
				
				var daily_fmted = (Math.round(day.dist * 4) / 4).toFixed(2);
				var weekly_fmted = (Math.round(weekly * 4) / 4).toFixed(2);
				var total_fmted = (Math.round(total * 4) / 4).toFixed(2);
				
				plan_mkup.push('<tr class="' + tr_classes + '"><td><span class="paperCheckBox"></span></td><td class="count">' + (i + 1) + '</td><td class="week">' + week_cnt + '</td><td class="date">' + day.date + '</td><td class="distance">' + daily_fmted + ' ' + day_units + '</td><td class="weekly">' + weekly_fmted + ' ' + week_units + '</td><td class="total">' + total_fmted + ' ' + total_units + '</td></tr>');
				last_dow = day.dow;
			}
			
			plan_mkup.push('</tbody>');
			
			var plan_table = '<table class="plan">' + plan_mkup.join("\n") + '</table>';
			var $plan = $('#plan');
			$plan.html(plan_table);
			grTPCtl.drawChart();
		},
		iCalBuild: function() {
			var iCal = '';
			
			var crlf = "\r\n";
			
			iCal += "BEGIN:VCALENDAR" + crlf;
			iCal += "VERSION:2.0" + crlf;
			iCal += "PRODID:GoRun Training Plan" + crlf;
			
			for (var i = 0; i < grTPCtl.plan.length; i++) {
				var day = grTPCtl.plan[i];
				var timestamp = day.dateObj.format(dateFormat.masks.iCalAllDayDateStamp);
				var timestamp_with_time = day.dateObj.format(dateFormat.masks.iCalDateStamp);

				
				iCal += "BEGIN:VEVENT" + crlf;
				iCal += "DTSTAMP:" + timestamp_with_time + crlf;
				iCal += "DTSTART;VALUE=DATE:" + timestamp + crlf;
				iCal += "DTEND;VALUE=DATE:" + timestamp + crlf;
				iCal += "UID:" + timestamp + "-gorun.bhffc.com" + crlf;
				iCal += "SUMMARY:Run " + (Math.round(day.dist * 4) / 4).toFixed(2) + " miles" + crlf;
				iCal += "END:VEVENT" + crlf;
			}
			iCal += "END:VCALENDAR";
			
			var dataURI = "data:text/calendar;base64," + Base64.encode(iCal);
			
			$('#iCalendar').attr('href', dataURI);
			
			
			//BEGIN:VCALENDAR
			//VERSION:2.0
			//PRODID:-//hacksw/handcal//NONSGML v1.0//EN
			//BEGIN:VEVENT
			//UID:uid1@example.com
			//DTSTAMP:19970714T170000Z
			//ORGANIZER;CN=John Doe:MAILTO:john.doe@example.com
			//DTSTART:19970714T170000Z
			//DTEND:19970715T035959Z
			//SUMMARY:Bastille Day Party
			//END:VEVENT
			//END:VCALENDAR
		},
		init: function(opts) {
			grTPCtl.params = window.location.hash;
			
			$.extend(grTPCtl.config, opts);
			
			if (grTPCtl.config.units == grTPCtl.common.units.MILES) {
				$('#useMiles').attr('checked','checked');
			} else {
				$('#useMiles').removeAttr('checked');
			}
			
			grTPCtl.setupRaceDistances();
			grTPCtl.setupBaseDistances();
			grTPCtl.setupTaperLength();
			
			var start = Kalendae.moment();
			var end = Kalendae.moment().add({M:1});
			
			window.moment = Kalendae.moment;
			var kalendae_opts = {
				attachTo: $('#dates').get(0),
				months: 3,
				mode: 'range',
				selected:[start,end],
				subscribe: {
					'change': grTPCtl.datesChanged
				} 
			};
			
			grTPCtl.datePicker = new Kalendae(kalendae_opts);
			//grTPCtl.datePicker = $('#dates').kalendae(kalendae_opts);
			grTPCtl.setDates(start.toDate(), end.toDate());
			
			$('.dayCheckboxes input[type="checkbox"]').change(function() {
				var cnt = $('.dayCheckboxes input[type="checkbox"]:checked').length;
				if (cnt < 3 || cnt > 5) {
					$(this).parent('div').find('.note').addClass('warning');
				} else {
					$(this).parent('div').find('.note').removeClass('warning');
				}
			  var $day = $(this);
				var checked = $day.is(':checked');
				var val = $day.val();
				grTPCtl.config.days[val] = checked;
				grTPCtl.rebuildPlan();
			});
			
			var $useMiles = $('#useMiles');
			$useMiles.change(function() {
				grTPCtl.config.units = ($useMiles.is(':checked')) ? grTPCtl.common.units.MILES : grTPCtl.common.units.KM;
				grTPCtl.setupRaceDistances();
				grTPCtl.setupBaseDistances();
				grTPCtl.rebuildPlan();
			});
			
			$('#baseDistance, #raceDistance, #taperLength, #programType').change(function() {
				grTPCtl.rebuildPlan();
			});
			
			grTPCtl.rebuildPlan();
			
			grTPCtl.useParams();
			
			grTPCtl.rebuildPlan();
			
			$(window).bind('hashchange', function() {
				if (!grTPCtl.initialized) { return; }
				grTPCtl.params = window.location.hash;
				grTPCtl.useParams();
			});
			
			
		},
		setupRaceDistances: function() {
			miles = grTPCtl.config.units != grTPCtl.common.units.KM;
			
			var dlist = (miles ? grTPCtl.common.distances.miles : grTPCtl.common.distances.km);
			var units = miles ? 'mile' : ' km';
			var selected = (typeof grTPCtl.config.distance !== 'undefined' ? grTPCtl.config.distance : (miles ? 13.1 : 5));
			var rdist = '';
			for (var i = 0; i < dlist.length; i++) {
				var sel = (dlist[i] == selected) ? ' selected' : '';
				rdist += '<option value="' + dlist[i] + '"' + sel + '>' + dlist[i] + ' ' + units + (dlist[i] != 1 ? 's' : '') + '</option>';
			}
			
			var $rdsel = $('#raceDistance');
			$rdsel.empty();
			$rdsel.append(rdist);
		},
		setupBaseDistances: function() {
			miles = grTPCtl.config.units != grTPCtl.common.units.KM;
			
			var units = miles ? 'mile' : 'km';
			var max = miles ? 30 : 50;
			var bdist = '';
			for (var i = 0; i < max; i++) {
				var sel = (i == grTPCtl.config.base) ? ' selected' : '';
				bdist += '<option value="' + i + '"' + sel + '>' + i + ' ' + units + (i != 1 ? 's' : '') + '</option>';
			}
			
			var $bdsel = $('#baseDistance');
			$bdsel.empty();
			$bdsel.append(bdist);
		},
		setupTaperLength: function() {
			var topts = '';
			for (var i = 0; i < 20; i++) {
				var sel = (i == grTPCtl.config.taper) ? ' selected' : '';
				topts += '<option value="' + i + '"' +  sel + '>' + i + ' day' + (i != 1 ? 's' : '') + '</option>';
			}
			var $tl = $('#taperLength');
			$tl.empty().append(topts);
		},
		chartsReady: function() {
			grTPCtl.charts = true;
			grTPCtl.chart = new google.visualization.LineChart(document.getElementById('chart'));
		},
		drawChart: function() {
			var options = {
				title: 'Mileage',
				curveType: 'function',
				lineWidth: 2,
				pointSize: 4,
				vAxis: {
					gridlines: {
						color: '#aaa',
						count: 10
					}
				}
			};
			
			
			
			//console.log(grTPCtl.chartData);
			
			grTPCtl.chart.draw(grTPCtl.chartData, options);
		}
	};
	
	grTPCtl.ready();
})();