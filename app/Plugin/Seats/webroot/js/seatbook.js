function xloadSeat(is_after_ajax) {
	var so = (is_after_ajax) ? ':not(.xltriggered)': '';
	$.fn.fseattimer('.seat-clock' + so);
	$.fn.fseatgrid('.seat-grid' + so);
}
$.fn.fseattimer = function(selector) {	
	$(selector).each(function(e) {
		var url = $('.seat-clock').attr('data_url');
		var secs = $('.seat-clock').attr('data_time');
		if(secs != undefined) {
			var clock = $('.seat-clock').FlipClock(secs, {
				countdown: true,
				clockFace: 'MinuteCounter',
				callbacks: {
					stop: function() {
						$('.payOrder').attr('disabled', 'disabled');
						window.location.href = url;
					}
				}
			});
		}		
	}).addClass('xltriggered');
};
//Seat Selection Page
$.fn.fseatgrid = function(selector) {
	$(selector).each(function(e) {
		var selValue = [];
		if(typeof seat_map != "undefined"){
			selValue = selected_arr;
			var $cart = $('#selected-seats'),
				sc = $('#seat-map').seatCharts({
				map: seat_map,
				seats: {
					a: {
						classes : 'available', 
						category: 'Available'
					},
					u: {
						classes : 'unavailable',
						category: 'Unavailable'
					},
					s: {
						classes : 'selected',
						category: 'Selected'
					},
					b: {
						classes : 'booked', 
						category: 'Booked'
					}
				
				},
				naming : {
					top : false,
					left: true,
					rows: row_name,
					getLabel : function (character, row, column) {
						return firstSeatLabel++;
					},
				},
				legend : {
					node : $('#legend'),
					items : [
						[ 'a', 'available',   __cfg('Available')],
						[ 'u', 'unavailable', __cfg('Unavailable')],
						[ 'l', 'blocked', __cfg('Blocked')],
						[ 'w', 'waitingforacceptance', __cfg('Booking')],
						[ 'b', 'booked', __cfg('Booked')],
						[ 's', 'selected', __cfg('Selected')],
						[ 'ns', 'noseats', __cfg('No seats')]
						
					]
				},
				click: function () {				
					var selSeats = parseInt($('#sel_seats').text());
					var reqSeats = parseInt($('#js-req_seats').text());
					if (this.status() == 'available') {
						//let's create a new <li> which we'll add to the cart items
						if(selSeats < reqSeats) {
							$('#sel_seats').html(sc.find('selected').length+1);
							selSeats++;
							selValue.push(parseInt(this.settings.id));
							$('#save_sel_seats').val(selValue.toString());	
							if(selSeats == reqSeats) {
								$('.payOrder').removeAttr('disabled');
							}	else {
								$('.payOrder').attr('disabled', 'disabled');
							}						
							return 'selected';
						} else {
							return 'available';
						}
					} else if (this.status() == 'selected') {
						$('#sel_seats').text(sc.find('selected').length-1);
						selSeats--;
						var index = selValue.indexOf(parseInt(this.settings.id));	
						if (index > -1) {
							selValue.splice(index, 1);
						}					
						if(selSeats == reqSeats) {
							$('.payOrder').removeAttr('disabled');
						}else{
							$('.payOrder').attr('disabled', 'disabled');
						}
						$('#save_sel_seats').val(selValue.toString());
						return 'available';
					} else if (this.status() == 'unavailable') {
						//seat has been unavailable
						return 'unavailable';
					} else if (this.status() == 'blocked') {
						//seat has been blocked
						return 'blocked';
					} else if (this.status() == 'waitingforacceptance') {
						//seat has been waiting for acceptance
						return 'waitingforacceptance';
					} else if (this.status() == 'booked') {
						//seat has been booked
						return 'booked';
					}else {
						return this.style();
					}
					
				}
			});	
			//let's pretend some seats have already been booked
			sc.get(available_arr).status('available');
			
			//Blocked
			sc.get(unavailable_arr).status('unavailable');
			
			//Booked
			sc.get(booked_arr).status('booked');
			
			//Booked
			sc.get(noseat_arr).status('noseats');
			//selected_arr
			sc.get(selected_arr).status('selected');
			//blocked_arr
			sc.get(blocked_arr).status('blocked');		
			//booking_arr
			sc.get(booking_arr).status('waitingforacceptance');
			
		}
	}).addClass('xltriggered');
}
var firstSeatLabel = 1;
var $dc = $(document);
$dc.ready(function(){
	// Todo: xloadSeat is set to true temporarily
	xloadSeat(true); 
	$dc.on('click', '.seat-row li.seat', function(){		
		var sid = $(this).attr('id');		
		chk = $('#'+sid+'.selected').length;
		var data_class = $(this).attr('data-class');	
		if($(this).hasClass('booked') || $(this).hasClass('blocked') || $(this).hasClass('waitingforacceptance')) {
			return false;
		} else {
			if(chk==0){
				$('#'+sid).addClass('selected').removeClass('unavailable no-seat');			
			} else {
				$('#'+sid).removeClass('selected').addClass(data_class);								
			}		
			if(($('.seat-row li.selected').length) > 0) {
				$('.seatOption').show();
			} else {
				$('.seatOption').hide();
			}
		}
	})
	.on('click', '.seat-row li.row-selector', function(){		
		var xid = $(this).attr('act');		
		$(this).addClass('row-deselector').removeClass('row-selector');
		seatcols = $('#seatCols').val();
		if(seatcols == undefined) {
			seatcols = $('.seat-grid').attr('data-cols');
		}
		for(v = 1;v <= seatcols; ++v){
			var seat = $('#'+xid+'-'+v);
			if(!(seat.hasClass('blocked')) && !(seat.hasClass('booked')) && !(seat.hasClass('waitingforacceptance')) ) {
				seat.addClass('selected').removeClass('unavailable no-seat');
			}
		}		
		if(($('.seat-row li.selected').length) > 0) {
			$('.seatOption').show();
		} else {
			$('.seatOption').hide();
		}
	})
	.on('click', '.seat-row li.row-deselector', function(){
		var xid = $(this).attr('act');
		$(this).addClass('row-selector').removeClass('row-deselector');
		seatcols = $('#seatCols').val();
		if(seatcols == undefined) {
			seatcols = $('.seat-grid').attr('data-cols');
		}
		for(v = 1;v <= seatcols; ++v){	
			data_class = $('#'+xid+'-'+v).attr('data-class');
			$('#'+xid+'-'+v).removeClass('selected').addClass(data_class);
		}						
		if(($('.seat-row li.selected').length) > 0) {
			$('.seatOption').show();
		} else {
			$('.seatOption').hide();
		}
	})
	.on('click', '.blank-row li.col-deselector', function(){		
		$('.seat').removeClass('seat-hovered');
		var cid = $(this).attr('act');	
		seatrows = $('#seatRows').val();
		if(seatrows == undefined) {
			seatrows = $('.seat-grid').attr('data-rows');
		}
		for(v = 1;v <= seatrows; ++v){
			data_class = $('#'+v+'-'+cid).attr('data-class');
			$('#'+v+'-'+cid).removeClass('selected').addClass(data_class);
		}
		$('.col-deselector[act="'+cid+'"]').addClass('col-selector').removeClass('col-deselector');
		if(($('.seat-row li.selected').length) > 0) {
			$('.seatOption').show();
		} else {
			$('.seatOption').hide();
		}
	})
	.on('click', '.blank-row li.col-selector', function(){		
		$('.seat').removeClass('seat-hovered');
		var cid = $(this).attr('act');
		seatrows = $('#seatRows').val();
		if(seatrows == undefined) {
			seatrows = $('.seat-grid').attr('data-rows');
		}
		for(v = 1;v <= seatrows; ++v){
			var seat = $('#'+v+'-'+cid);			
			if(!(seat.hasClass('blocked')) && !(seat.hasClass('booked')) && !(seat.hasClass('waitingforacceptance'))) {
				seat.addClass('selected').removeClass('unavailable no-seat');
			}
		}
		$('.col-selector[act="'+cid+'"]').addClass('col-deselector').removeClass('col-selector');
		if(($('.seat-row li.selected').length) > 0) {
			$('.seatOption').show();
		} else {
			$('.seatOption').hide();
		}
	})
	.on('click', '.blank-row li.seat-all-toggle', function(){
		seatrows = $('#seatRows').val();
		seatcols = $('#seatCols').val();
		if(seatrows == undefined) {
			seatrows = $('.seat-grid').attr('data-rows');
		}
		if(seatcols == undefined) {
			seatcols = $('.seat-grid').attr('data-cols');
		}
		for(var i = 1; i <= seatrows; i++) {
			for(var j = 1; j <= seatcols; j++) {				
				var seat = $('#'+i+'-'+j);
				if(!(seat.hasClass('blocked')) && !(seat.hasClass('booked')) && !(seat.hasClass('waitingforacceptance'))) {
					seat.addClass('selected').removeClass('unavailable no-seat');
				}
			}
		}		
		$('.seat-all-toggle').addClass('seat-all-toggled').removeClass('seat-all-toggle');
		if(($('.seat-row li.selected').length) > 0) {
			$('.seatOption').show();
		} else {
			$('.seatOption').hide();
		}
	})
	.on('click', '.blank-row li.seat-all-toggled', function(){
		seatrows = $('#seatRows').val();
		seatcols = $('#seatCols').val();
		if(seatrows == undefined) {
			seatrows = $('.seat-grid').attr('data-rows');
		}
		if(seatcols == undefined) {
			seatcols = $('.seat-grid').attr('data-cols');
		}
		for(var i = 1; i <= seatrows; i++) {
			for(var j = 1; j <= seatcols; j++) {				
				var seat = $('#'+i+'-'+j);
				data_class = $('#'+i+'-'+j).attr('data-class');
				$('#'+i+'-'+j).removeClass('selected').addClass(data_class);
			}
		}		
		$('.seat-all-toggled').addClass('seat-all-toggle').removeClass('seat-all-toggled');
		if(($('.seat-row li.selected').length) > 0) {
			$('.seatOption').show();
		} else {
			$('.seatOption').hide();
		}
	})
	.on('click', '#mark_seats', function() {
		var stype = $('#seat-marker').val();		
		var selClass;
		if(stype == 1) selClass = 'available';
		if(stype == 2) selClass = 'unavailable';
		if(stype == 5) selClass = 'no-seat';
		$('.seat.selected').each(function(index){
			var sid = $(this).attr('id');
			$(this).addClass(selClass);
			$(this).attr('data-class', selClass);
			$('#class-'+sid).attr("value", stype);
		});
		$('.row-deselector').addClass('row-selector').removeClass('row-deselector');
		$('.seat.selected').removeClass('selected');
		$('.seatOption').hide();
	})
	.on('click', '#generateGrid', function() {
		var naming_type = $('#namingType').val();
		var direction = $('#direction').val();
		var seatrows = $('#seatRows').val();
		var seatcols = $('#seatCols').val();
		var url = __cfg('path_relative') + 'seats/generate/' + 'rows:'+seatrows+'/cols:'+seatcols+'/naming:'+naming_type+'/direction:'+direction;
		if(seatrows != '' && seatcols != '' && naming_type != '' && direction != '') {
			$('.js-seat-generate-responses').block();	
			$.get(url, function(data) {		
				$('.js-seat-generate-responses').html(data).unblock();
				$('#partitionForm').removeClass('hide');
				var k = parseInt(seatcols) + 1;	
				$('.seat-row-col').css('width', (k*2.9)+'em');
			});
		}else{
			$('#namingType').trigger('blur');
			$('#direction').trigger('blur');
			$('#seatRows').trigger('blur');
			$('#seatCols').trigger('blur');			
		}
		return false;
	})
	.on('submit', '#js-show-add-form-save', function() {
		var str = JSON.stringify($( "form.js-partition-temp-add-form" ).serializeObject());
		console.log(str);
		$( "#js-result" ).text( str );
	})
	.on('submit', '.js-partition-temp-add-form', function() {
		return false;
	})	
	.on('change', '#stage_position', function() {
		stage_pos = $(this).val();
		if(stage_pos == 1) {
			$('.bottom-bar-img').hide();
			$('.top-bar-img').show();
			$('.top-screen').show();
			$('.bottom-screen').hide();
		}
		if(stage_pos == 2) {
			$('.top-bar-img').hide();
			$('.bottom-bar-img').show();
			$('.bottom-screen').show();
			$('.top-screen').hide();
		}
		
	});
}).ajaxStop(function() {	
	xloadSeat(true);	
}); 
// end document ready



