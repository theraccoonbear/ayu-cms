var grSWCtl = grSWCtl || {};

(function() {
	var readyHandler = function() { /* ... */ };
	if (typeof grSWCtl.ready === 'function') {
		readyHandler = grSWCtl.ready;
	}
	
	grSWCtl = {
		ready: readyHandler,
		datePicker: null,
		plan: [],
		chart: null,
		charts: false,
		chartData: null,
		common: {
			distances: {
				km: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],
				miles: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
			},
			intervals: [1,2,3,4,5,6,7,8,9,10],
			conversion: {
				kmToMiles: 0.621371192,
				milesToKm: 1.609344,
				milesToFeet: 5280
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
			}
		},
		init: function(opts) {
			$.extend(grSWCtl.config, opts);
			
			if (grSWCtl.config.units == grSWCtl.common.units.MILES) {
				$('#useMiles').attr('checked','checked');
			} else {
				$('#useMiles').removeAttr('checked');
			}
			
			grSWCtl.setuprunDistances();
			grSWCtl.setupIntervalCount();
			
			var $useMiles = $('#useMiles');
			$useMiles.change(function() {
				grSWCtl.config.units = ($useMiles.is(':checked')) ? grSWCtl.common.units.MILES : grSWCtl.common.units.KM;
				grSWCtl.setuprunDistances();
				grSWCtl.rebuildPlan();
			});
			
			$('#runDistance, #intervalCount').change(function() {
				grSWCtl.rebuildPlan();
			});
			
			grSWCtl.rebuildPlan();
		},
		setuprunDistances: function() {
			miles = grSWCtl.config.units != grSWCtl.common.units.KM;
			
			var int_list = (miles ? grSWCtl.common.distances.miles : grSWCtl.common.distances.km);
			var units = miles ? 'mile' : ' km';
			var selected = (typeof grSWCtl.config.distance !== 'undefined' ? grSWCtl.config.distance : (miles ? 13.1 : 5));
			var rdist = '';
			for (var i = 0; i < int_list.length; i++) {
				var sel = (int_list[i] == selected) ? ' selected' : '';
				rdist += '<option value="' + int_list[i] + '"' + sel + '>' + int_list[i] + ' ' + units + (int_list[i] != 1 ? 's' : '') + '</option>';
				rdist += '<option value="' + int_list[i] + '.5">' + int_list[i] + '.5 ' + units + (int_list[i] != 1 ? 's' : '') + '</option>';
			}
			
			var $rdsel = $('#runDistance');
			$rdsel.empty();
			$rdsel.append(rdist);
		},
		setupIntervalCount: function() {
			var int_list = grSWCtl.common.intervals;
			var selected = (typeof grSWCtl.config.intervals !== 'undefined' ? grSWCtl.config.intervals : 4);
			var rdist = '';
			for (var i = 0; i < int_list.length; i++) {
				var sel = (int_list[i] == selected) ? ' selected' : '';
				rdist += '<option value="' + int_list[i] + '"' + sel + '>' + int_list[i] + '</option>';
			}
			
			var $rdsel = $('#intervalCount');
			$rdsel.empty();
			$rdsel.append(rdist);
		},
		rebuildPlan: function() {
			var tot_dist = parseFloat($('#runDistance').val());
			var int_cnt = parseInt($('#intervalCount').val());
			var int_dist = tot_dist / int_cnt;
			var divisor = 4;
			var pace_dist = int_dist * ((divisor - 1) / divisor);
			var recovery_dist = int_dist * (1 / divisor);
			
			grSWCtl.plan = [];
			
			for (var i = 0; i < int_cnt; i++) {
				grSWCtl.plan.push({'type':'ON','dist':pace_dist,'pace':'0:00'});
				grSWCtl.plan.push({'type':'OFF','dist':recovery_dist,'pace':'0:00'});
			}
			
			grSWCtl.drawChart();
			//$('#dbg').html('Run ' + pace_dist + ' on with ' + recovery_dist + ' recovery');
		},
		drawChart: function() {
			var tot_dist = parseFloat($('#runDistance').val());
			var $chart = $('#intChart');
			var mkup = '<table><tr>';
			var total = 0;
			var units = grSWCtl.config.units == 1 ? 'miles' : 'km';
			
			$chart.html('');
			for (var i = 0; i < grSWCtl.plan.length; i++) {
				var act = grSWCtl.plan[i];
				var width = Math.floor(act.dist / tot_dist * parseInt($chart.width()));
				var dist = (Math.round(act.dist * 4) / 4).toFixed(2);
				dist = dist < 0.25 ? 0.25 : dist;
				total = total + parseFloat(dist);
				mkup += '<td class="activity act' + act.type + '" style="width: ' + width + 'px;" >' + (act.type == 'ON' ? 'RUN' : 'REC') + '<br/>'  + dist + '<br/>' + units + '</td>';
			}
			mkup += '</tr></table>';
			
			$chart.html(mkup);
			$('#details').html('Total Distance: ' + total);
		}
	};
	
	grSWCtl.ready();
})();