function split(val) {
    return val.split(/,\s*/);
}
function extractLast(term) {
    return split(term).pop();
}
function __l(str, lang_code) {
    //TODO: lang_code = lang_code || 'en_us';
    return(cfg && cfg.cfg.lang && cfg.cfg.lang[str]) ? cfg.cfg.lang[str]: str;
}
function __cfg(c) {
    return(cfg && cfg.cfg && cfg.cfg[c]) ? cfg.cfg[c]: false;
}
function publishCallBack(response) {
    window.location.href = $('#js-loader').data('redirect_url');
}
function calcTime(offset) {
    d = new Date();
    utc = d.getTime() + (d.getTimezoneOffset() * 60000);
    return date('Y-m-d', new Date(utc + (3600000 * offset)));
}
function clearCache() {
    $.each(cacheMapping, function(id, item) {
        if (cacheMapping[id].url.indexOf('/messages') != -1) {
            delete cacheMapping[id];
        }
    });
}
function days_between(date1, date2) {
    // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24;
    // Convert both dates to milliseconds
    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();
    // Calculate the difference in milliseconds
    var difference_ms = Math.abs(date1_ms - date2_ms);
    // Convert back to days and return
    return Math.round(difference_ms / ONE_DAY);
}
function xloadGeoAutocomplete() {
    $('div.js-calander-load').each(function() {
        id = $('.js-calander-load').metadata().id;
        CalanderLoad(id);
    }).addClass('xltriggered');
    $('#js-inlineDatepicker').each(function() {
        var $this = $(this);
        var year_ranges = $('#js-inlineDatepicker-calender').find("select[id$='Year']").eq(0).text();
        var each_year = year_ranges.split('\n');
        var startyear = endyear = '';
        for (var i = 0; i < each_year.length; i ++ ) {
            if (each_year[i] != '' && each_year[i] != '\n' && startyear == '') {
                startyear = parseInt(each_year[i]);
            }
            if (each_year[i] != '' && each_year[i] != '\n') {
                endyear = parseInt(each_year[i]);
                if (endyear < startyear) {
                    tmp = startyear;
                    startyear = endyear;
                    endyear = tmp;
                }
            }
        }
        var maxdate = endyear - startyear;
        $this.datepick($.extend( {
            renderer: $.datepick.themeRollerRenderer,
            rangeSelect: true,
            monthsToShow: [1, 2],
            minDate: 0,
            maxDate: '12/31/' + endyear,
            onSelect: function(dates) {
                var today_date = new Date(calcTime(__cfg('timezone')).replace('-', '/').replace('-', '/'));
                var t1 = today_date.getTime();
                for (var i = 0; i < dates.length; i ++ ) {
                    var t2 = dates[i].getTime();
                    date_diff = parseInt((t2 - t1) / (24 * 3600 * 1000));
                    if (date_diff >= 0) {
                        var newDate = $.datepick.formatDate(dates[i]).split('/');
                        $('#js-inlineDatepicker-calender').find("select[id$='Day']").eq(i).val(newDate[1]);
                        $('#js-inlineDatepicker-calender').find("select[id$='Month']").eq(i).val(newDate[0]);
                        $('#js-inlineDatepicker-calender').find("select[id$='Year']").eq(i).val(newDate[2]);
                    }
                }
                if ($('.js-date-picker-info').hasClass('default')) {
                    $('.js-date-picker-info').removeClass('default').addClass('started blink');
                    $('.js-date-picker-info').css('color', '');
                    $('.js-date-picker-info').html('<i class="icon-question-sign"></i>' + __l('Select end date in calendar'));
                    $('.blink').cyclicFade();
                } else if ($('.js-date-picker-info').hasClass('started')) {
                    $('.js-date-picker-info').removeClass('started').addClass('selected');
                    var no_of_days = days_between(dates[0], dates[1]);
                    if (__cfg('days_calculation_mode') == 'Day') {
                        no_of_days ++ ;
                    }
                    var day_caption = 'days';
                    if (no_of_days == 1) {
                        day_caption = 'day';
                    }
                    if (__cfg('days_calculation_mode') == 'Night' && no_of_days == 0) {
                        $('.js-date-picker-info').addClass('started blink');
                        $('.js-date-picker-info').css('color', 'red');
                        $('.js-date-picker-info').html(__l('Select check-out greater than the check-in date'));
                        $('.blink').cyclicFade();
                    } else {
                        var selected_dates = date('F d, Y', dates[0]) + ' to ' + date('F d, Y', dates[1]) + ' (' + no_of_days + ' ' + day_caption + ')';
                        $('.blink').cyclicFade('stop');
                        $('.js-date-picker-info').css('opacity', 9);
                        $('.js-date-picker-info').css('color', '');
                        $('.js-date-picker-info').html(selected_dates);
						$('#ItemCityNameSearch').trigger('blur');
                    }
                } else if ($('.js-date-picker-info').hasClass('selected')) {
                    $('.js-date-picker-info').removeClass('default').addClass('started blink');
                    $('.blink').cyclicFade();
                    $('.js-date-picker-info').css('color', '');
                    $('.js-date-picker-info').html(__l('Select check-out date in calendar'));
                } else {
                    $('.js-date-picker-info').addClass('default').removeClass('blink');
                    $('.js-date-picker-info').css('color', '');
                    $('.js-date-picker-info').html('<i class="icon-question-sign"></i>' + __l('Select from date in calendar'));
                }
            }
        }, $.datepick.regional["'" +__cfg('site_lang')+"'"]));
        dates = Array();
        for (var i = 0; i < 2; i ++ ) {
            dates[i] = $('#js-inlineDatepicker-calender').find("select[id$='Month']").eq(i).val() + '/' + $('#js-inlineDatepicker-calender').find("select[id$='Day']").eq(i).val() + '/' + $('#js-inlineDatepicker-calender').find("select[id$='Year']").eq(i).val();
        }
        $this.datepick('setDate', dates);
    }).addClass('xltriggered');
}
function loadAdminPanel() {
    if ($.cookie('_gz') != null && (window.location.href.indexOf('/item/') != -1 || window.location.href.indexOf('/user/') != -1 || window.location.href.indexOf('/request/') != -1)) {
        $('.js-alab').html('');
        $('header').removeClass('show-panel');
        var url = '';
        if (typeof($('.js-user-view').data('user-id')) != 'undefined' && $('.js-user-view').data('user-id') != '' && $('.js-user-view').data('user-id') != null) {
            var uid = $('.js-user-view').data('user-id');
            var url = 'users/show_admin_control_panel/view_type:user/id:' + uid;
        }
        if (typeof($('.js-request-view').data('request-id')) != 'undefined' && $('.js-request-view').data('request-id') != '' && $('.js-request-view').data('request-id') != null) {
            var rid = $('.js-request-view').data('request-id');
            var url = 'requests/show_admin_control_panel/view_type:request/id:' + rid;
        }
        if (typeof($('.js-item-view').data('item-id')) != 'undefined' && $('.js-item-view').data('item-id') != '' && $('.js-item-view').data('item-id') != null) {
            var pid = $('.js-item-view').data('item-id');
            var url = 'items/show_admin_control_panel/view_type:item/id:' + pid;
        }
        if (url != '') {
            $.get(__cfg('path_relative') + url, function(data) {
                $('.js-alab').html(data).removeClass('hide').show();
            });
        }
    } else {
        $('.js-alab').hide();
    }
}
var mapInitialized = 0;
function initializeSearch() {
	mapInitialized = 1;
	var updateMap = 0;
	var id = '';
	if (document.getElementById('ItemCityNameAddressSearch')) {
		var hiddenIds = Array();
		hiddenIds['lat'] = 'sh_latitude';
		hiddenIds['lng'] = 'sh_longitude';
		hiddenIds['ne_lat'] = 'sh_ne_latitude';
		hiddenIds['ne_lng'] = 'sh_ne_longitude';
		hiddenIds['sw_lat'] = 'sh_sw_latitude';
		hiddenIds['sw_lng'] = 'sh_sw_longitude';
		hiddenIds['address'] = 'sh_js-street_id';
		hiddenIds['city'] = 'sh_CityName';
		hiddenIds['state'] = 'sh_StateName';
		hiddenIds['country'] = 'sh_js-country_id';
		var input = document.getElementById('ItemCityNameAddressSearch');
		var options = {
			types: []
		};
		var autocomplete = new google.maps.places.Autocomplete(input, options);
		id = 'ItemCityNameAddressSearch';
		geoAutocomplete(id, autocomplete, hiddenIds);
	}
	var input = '';
	if (document.getElementById('ItemCityNameSearch')) {
		input = document.getElementById('ItemCityNameSearch');
		id = 'ItemCityNameSearch';
		loadSearchMap();
	} else if (document.getElementById('RequestCityName')) {
		input = document.getElementById('RequestCityName');
		id = 'RequestCityName';
		loadSearchMap();
	} else if (document.getElementById('ItemAddressSearch')) {
		input = document.getElementById('ItemAddressSearch');
		id = 'ItemAddressSearch';
		updateMap = 1;
		loadMap();
		loadGeoAddress('#ItemAddressSearch');
	} else if (document.getElementById('UserProfileAddress')) {
		input = document.getElementById('UserProfileAddress');
		id = 'UserProfileAddress';
	} else if(document.getElementById('CollectionCityNameSearch')) {
		id = 'CollectionCityNameSearch';
		loadSearchMap();
	}
	if (input != '') {
		var hiddenIds = Array();
		hiddenIds['lat'] = 'latitude';
		hiddenIds['lng'] = 'longitude';
		hiddenIds['ne_lat'] = 'ne_latitude';
		hiddenIds['ne_lng'] = 'ne_longitude';
		hiddenIds['sw_lat'] = 'sw_latitude';
		hiddenIds['sw_lng'] = 'sw_longitude';
		hiddenIds['address'] = 'js-street_id';
		hiddenIds['city'] = 'CityName';
		hiddenIds['state'] = 'StateName';
		hiddenIds['country'] = 'js-country_id';
		var autocomplete = new google.maps.places.Autocomplete(input, options);
		geoAutocomplete(id, autocomplete, hiddenIds, updateMap);
	}
	if (document.getElementById('cluster_map')) {
		map = new google.maps.Map(document.getElementById('cluster_map'), {
			zoom: 2,
			center: new google.maps.LatLng(0.314082, 0.695313),
			mapTypeId: google.maps.MapTypeId.TERRAIN
		});
		refreshMap();
	}
}
function loadMap() {
	if (document.getElementById('js-map-container')) {
		lat = 0;
		lng = 0;
		if ($('.js-search-lat', 'body').is('.js-search-lat')) {
			lat = $('.js-search-lat').metadata().cur_lat;
			lng = $('.js-search-lat').metadata().cur_lng;
		}
		if ((lat == 0 && lng == 0) || (lat == '' && lng == '')) {
			if ($('.js-map-data', 'body').is('.js-map-data')) {
				lat = $('.js-map-data').metadata().lat;
				lng = $('.js-map-data').metadata().lng;
			} else if($('#latitude').val() != '' && $('#longitude').val() != '') {
				lat = $('#latitude').val();
				lng = $('#longitude').val();
			} else {
				lat = 13.314082;
				lng = 77.695313;
			}
		}
		var zoom = 9;
		latlng = new google.maps.LatLng(lat, lng);
		var myOptions = {
			zoom: zoom,
			center: latlng,
			zoomControl: true,
			draggable: true,
			disableDefaultUI: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById('js-map-container'), myOptions);
		map.setCenter(latlng);
		if (lat != 0 && lng != 0) {
			var imageUrl = __cfg('path_relative') + 'img/center_point.png';
			var markerImage = new google.maps.MarkerImage(imageUrl);
			var j = 0;
			eval('var marker' + j + ' = new google.maps.Marker({ position: latlng,  map: map, icon: markerImage, zIndex: i});');
			var marker_obj = eval('marker' + j);
		}
		var i = 1;
		$('a.js-map-data', document.body).each(function() {
			lat = $(this).metadata().lat;
			lng = $(this).metadata().lng;
			url = $(this).attr('href');
			title = $(this).attr('title');
			updateMarker(lat, lng, url, i, title);
			i ++ ;
		});
	}
}
function loadSearchMap() {
    lat = 0;
    lng = 0;
    if ($('.js-search-lat', 'body').is('.js-search-lat')) {
        lat = $('.js-search-lat').metadata().cur_lat;
        lng = $('.js-search-lat').metadata().cur_lng;
    }
    if ((lat == 0 && lng == 0) || (lat == '' && lng == '')) {
        if ($('.js-map-data', 'body').is('.js-map-data')) {
            lat = $('.js-map-data').metadata().lat;
            lng = $('.js-map-data').metadata().lng;
        } else {
            lat = 13.314082;
            lng = 77.695313;
        }
    }
    var zoom = 9;
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: zoom,
        center: latlng,
        zoomControl: true,
        draggable: true,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById('js-map-container'), myOptions);
    map.setCenter(latlng);
    if (lat != 0 && lng != 0) {
        var imageUrl = __cfg('path_relative') + 'img/center_point.png';
        var markerImage = new google.maps.MarkerImage(imageUrl);
        var j = 0;
        eval('var marker' + j + ' = new google.maps.Marker({ position: latlng,  map: map, icon: markerImage, zIndex: i});');
        var marker_obj = eval('marker' + j);
    }
    var i = 1;
    $('a.js-map-data', document.body).each(function() {
        lat = $(this).metadata().lat;
        lng = $(this).metadata().lng;
        url = $(this).attr('href');
        title = $(this).attr('title');
        updateMarker(lat, lng, url, i, title);
        i ++ ;
    });
}
function loadGeoAddress(selector) {
    geocoder = new google.maps.Geocoder();
    var address = $(selector).val();
    geocoder.geocode( {
        'address': address
    }, function(results, status) {
        $.map(results, function(results) {
            var components = results.address_components;
            if (components.length) {
                var k = 0;
                for (var j = 0; j < components.length; j ++ ) {
                    if (components[j].types[0] == 'locality' || components[j].types[0] == 'administrative_area_level_2') {
                        if (k == 0) {
                            city = components[j].long_name;
                            $('#CityName').val(city);
                        }
                        if (components[j].types[0] == 'locality') {
                            k = 1;
                        }
                    }
                    if (components[j].types[0] == 'administrative_area_level_1') {
                        state = components[j].long_name;
                        $('#StateName').val(state);
                    }
                    if (components[j].types[0] == 'country') {
                        country = components[j].short_name;
                        $('#js-country_id').val(country);

                    }
                }
            }
        });
    });
}
function geoAutocomplete(id, autocomplete, hiddenIds, updateMap) {
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        if ( ! place.geometry) {
            return;
        }
        var area = '';
        var street = '';
        var city = '';
        var state = '';
        var country = '';
		var components = place.address_components;
		if (components.length) {
			for(var j=0; j < components.length; j++) {
				if (components[j].types[0] == 'point_of_interest' || components[j].types[0] == 'route' || components[j].types[0] == 'street_number') {
					if (street) {
						street = street + ' ' + components[j].long_name;
					} else {
						street = components[j].long_name;
					}
				}
				if (components[j].types[0] == 'sublocality' || components[j].types[0] == 'sublocality_level_1') {
					if (area == '') {
						if (street == '') {
							area = components[j].long_name;
						} else {
							area = street + ', ' + components[j].long_name;
						}
					}
				}
				if (components[j].types[0] == 'locality' || components[j].types[0] == 'administrative_area_level_2' || components[j].types[0] == 'administrative_area_level_3') {
					if ( ! city) {
						city = components[j].long_name;
					}
				}
				if (components[j].types[0] == 'administrative_area_level_1') {
					state = components[j].long_name;
				}
				if (components[j].types[0] == 'country') {
					country = components[j].short_name;
					country_lang = components[j].long_name;
				}
				if (components[j].types[0] == 'postal_code') {
					postal_code = components[j].long_name;
				}
			}
		}
		if (area == '' && street != '') {
			area = street;
		}
		if (document.getElementById(hiddenIds['lat']) != null) {
	        document.getElementById(hiddenIds['lat']).value = place.geometry.location.lat();
		}
		if (document.getElementById(hiddenIds['lng']) != null) {
			document.getElementById(hiddenIds['lng']).value = place.geometry.location.lng();
		}
		if (components.length <= 2) {
			if (document.getElementById(hiddenIds['ne_lat']) != null && place.geometry.viewport.getSouthWest().lat() != undefined) {
				if (document.getElementById(hiddenIds['ne_lat']) != null) {
					document.getElementById(hiddenIds['ne_lat']).value = place.geometry.viewport.getNorthEast().lat();
				}
				if (document.getElementById(hiddenIds['ne_lng']) != null) {
					document.getElementById(hiddenIds['ne_lng']).value = place.geometry.viewport.getNorthEast().lng();
				}
				if (document.getElementById(hiddenIds['sw_lat']) != null) {
					document.getElementById(hiddenIds['sw_lat']).value = place.geometry.viewport.getSouthWest().lat();
				}
				if (document.getElementById(hiddenIds['sw_lng']) != null) {
					document.getElementById(hiddenIds['sw_lng']).value = place.geometry.viewport.getSouthWest().lng();
				}
			}
		}
		if (document.getElementById(hiddenIds['address']) != null) {
			document.getElementById(hiddenIds['address']).value = area;
		}
		if (document.getElementById(hiddenIds['city']) != null) {
			document.getElementById(hiddenIds['city']).value = city;
		}
		if (document.getElementById(hiddenIds['state']) != null) {
			document.getElementById(hiddenIds['state']).value = state;
		}
		if (document.getElementById(hiddenIds['country']) != null) {
			document.getElementById(hiddenIds['country']).value = country;
		}
		if (updateMap) {
			loadMap();
		}
        searchHandler(id);
    });
}
function searchHandler(id) {
	var is_disable = false;
    if($('#'+id).val() == "") {
		if(id == 'ItemCityNameAddressSearch') {
			$('#sh_latitude, #sh_longitude').val('');
			is_disable = true;
		} else {
			$('#longitude, #longitude').val('');
			is_disable = true;
		}
	} else {
		if(id == 'ItemCityNameAddressSearch' && $('#sh_latitude').val() == "" && $('#sh_longitude').val() == "") {
			is_disable = true;
		} else {
			if($('#longitude').val() == "" && $('#longitude').val() == "") {
				is_disable = true;
			}
		}
	}
	var sub_btn = $('#'+id).parents('form').find("#js-sub");
    if(is_disable) {
		sub_btn.attr('disabled', 'disabled');
		sub_btn.removeClass('active-search');
	} else {
		sub_btn.removeAttr('disabled');
        sub_btn.addClass('active-search');
	}
}
function searchmapaction() {
    if (map.getZoom() > 13) {
        map.setZoom(13);
    }
    bounds = (map.getBounds());
    var southWestLan = '';
    var northEastLng = '';
    var southWest = bounds.getSouthWest();
    var northEast = bounds.getNorthEast();
    var center = bounds.getCenter();
    $('#ItemLatitude, #RequestLatitude').val(center.lat());
    $('#ItemLongitude, #RequestLongitude').val(center.lng());
    $('.js-search-lat').metadata().cur_lat = center.lat();
    $('.js-search-lat').metadata().cur_lng = center.lng();
    $('#ne_latitude_index').val(northEast.lat());
    $('#sw_latitude_index').val(southWest.lat());
    if (isNaN(northEast.lng())) {
        northEastLng = '0';
    } else {
        northEastLng = northEast.lng();
    }
    $('#ne_longitude_index').val(northEastLng);
    if (isNaN(southWest.lng())) {
        southWestLan = '0';
    } else {
        southWestLan = southWest.lng();
    }
    $('#sw_longitude_index').val(southWestLan);
    $('#KeywordsSearchForm').submit();
}
function updateMarker(lat, lnt, url, i, title) {
    var store_count = i;
    if (lat != null) {
        myLatLng = new google.maps.LatLng(lat, lnt);
        var imageUrl = __cfg('path_relative') + 'img/red/' + store_count + '.png';
        var markerImage = new google.maps.MarkerImage(imageUrl);
        eval('var marker' + i + ' = new google.maps.Marker({ position: myLatLng,  map: map, icon: markerImage, zIndex: i});');
        var marker_obj = eval('marker' + i);
        marker_obj.title = title;
        var li_obj = '.js-map-num' + i;
        //one time map listener to handle the zoom
        google.maps.event.addListenerOnce(map, 'resize', function() {
            map.setCenter(center);
            map.setZoom(zoom);
        });
        //items marker hover, point the items list active
        $(li_obj).bind('mouseenter', function() {
            var imagehover = __cfg('path_relative') + 'img/black/' + store_count + '.png';
            marker_obj.setIcon(imagehover);
        });
        $(li_obj).bind('mouseleave', function() {
            var imageUrlhout = __cfg('path_relative') + 'img/red/' + store_count + '.png';
            marker_obj.setIcon(imageUrlhout);
        });
        //items list mouse over/leave changing the hover marker icon
        google.maps.event.addListener(marker_obj, 'mouseenter', function() {
            li_obj.addClass('active');
        });
        google.maps.event.addListener(marker_obj, 'mouseleave', function() {
            li_obj.removeClass('active');
        });
        var li_obj_request = '.js-map-request-num' + i;
        //requests
        $(li_obj_request).bind('mouseenter', function() {
            var imagehover = __cfg('path_relative') + 'img/black/' + store_count + '.png';
            marker_obj.setIcon(imagehover);
        });
        $(li_obj_request).bind('mouseleave', function() {
            var imageUrlhout = __cfg('path_relative') + 'img/red/' + store_count + '.png';
            marker_obj.setIcon(imageUrlhout);
        });
        google.maps.event.addListener(marker_obj, 'click', function() {
            window.location.href = url;
        });
    }
}
function geocodePosition(position, marker, map) {
	geocoder = new google.maps.Geocoder();
    geocoder.geocode( {
        latLng: position
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if(map != null) {
				map.setCenter(results[0].geometry.location);
			} else if(map1 != null) {
				map1.setCenter(results[0].geometry.location);
			}
            $('#latitude').val(results[0].geometry.location.d);
            $('#longitude').val(results[0].geometry.location.e);
        }
    });
}
function customDateFunction(input) {
    if (input.id == 'ItemCheckin') {
        if ($('#ItemCheckout').val() != 'yyyy-mm-dd') {
            if ($('#ItemCheckout').datepicker('getDate') != null) {
                dateMin = $('#ItemCheckout').datepicker('getDate', '-1d');
                dateMin.setDate(dateMin.getDate() - 1);
                return {
                    maxDate: dateMin,
                    inline: true
                };
            }
        }
    } else if (input.id == 'ItemCheckout') {
        if ($('#ItemCheckin').datepicker('getDate') != null) {
            dateMin = $('#ItemCheckin').datepicker('getDate', '+1d');
        }
        dateMin.setDate(dateMin.getDate() + 1);
        return {
            minDate: dateMin,
            inline: true
        };
    }
}
function checkStreetViewStatus() {
    var lat = $('#latitude').val();
    var lang = $('#longitude').val();
    //var fenway = new google.maps.LatLng(42.345573,-71.098326);
    var fenway = new google.maps.LatLng(lat, lang);
    // Define how far to search for an initial pano from a location, in meters.
    var panoSearchRadius = 50;
    // Create a StreetViewService object.
    var client = new google.maps.StreetViewService();
    // Compute the nearest panorama to the Google Sydney office
    // using the service and store that pano ID. Once that value
    // is determined, load the application.
    client.getPanoramaByLocation(fenway, panoSearchRadius, function(result, status) {
        if (status == google.maps.StreetViewStatus.OK) {
            $('.js-street-container').removeClass('hide');
        } else {
            $('.js-street-container').addClass('hide');
        }
    });
}
var filesList = [],
	paramNames = [];
function file_upload() {
    if ($('.fileUpload', 'body').is('.fileUpload')) {
        $('.js-normal-fileupload').fileupload( {
			forceIframeTransport: false,
            maxNumberOfFiles: $('#AttachmentFilename').data('maximum-number-of-photos'),
			acceptFileTypes:  /(\.|\/)(gif|jpe?g|png)$/i,
			dataType: '',
			singleFileUploads: true,
			autoUpload: true,
            submit: function(e, data) {
				$_this = $(this);
				data.url = __cfg('path_relative') + 'items/add_attachment';
				var item_id = $('#ItemId').val();
				var session_key = $('#ItemSessionKey').val();
				if (item_id == '') {
					var url = __cfg('path_relative') + 'items/add_simple';
					var param = {'title': $('#ItemTitle').val(), 'description': $('#ItemDescription').val(), 'category_id':$('#ItemSubCategoryId').val(), 'key': session_key};
					$.ajax( {
						type: 'POST',
						url: url,
						dataType: 'script',
						data: param,
						cache: false,
						success: function(responses) {
							$('#ItemId').val(responses);
							data.formData = {id: responses}
							$_this.fileupload('send', data);
							return false;
						}
					});
				} else {
					setTimeout(function() {
						var item_id = $('#ItemId').val();
						data.formData = {id: item_id}
						$_this.fileupload('send', data);
					}, 2000);
				}
				return false;
            },
            done: function(e, data) {
				$('.progress .bar').css('width', '100%');
				$('.js-image-block').append(data.result);
				data.context.remove();
            }
        }).bind('fileuploadadd', function(e, data) {
            if (data.files[0].name != null) {
				// Fix for chrome
                $('#browseFile').attr('title', data.files[0].name);
            }
			filesList.push(data.files[0]);
			paramNames.push($('#AttachmentFilename').prop("name"));
			// jquery.fileupload 5.40.0 -> support below line, now above '#AttachmentFilename' attachment element id hard coded.
			// paramNames.push(e.delegatedEvent.target.name);
        }).bind('fileuploadfail', function(e, data) {
			$.each(filesList, function (index, file) {
				if((data.files[0].size == file.size) && (data.files[0].name == file.name) && (data.files[0].type == file.type)){
					filesList.splice(index, 1);
					return false;
				}
            });
        });
    }
}
function j_validate(that) {
    var $this = that;
    if (($('div.error', $this).length == 0) && ($('span.label-danger', $this).length == 0)) {
        // return true when there's no error in form
        return '';
    } else {
        return 'error';
    }
}
//Function End
 (function() {
    jQuery('html').addClass('js');
	$.fn.serializeObject=function(){
			"use strict";
			var a={},b=function(b,c){var d=a[c.name];"undefined"!=typeof d&&d!==null?$.isArray(d)?d.push(c.value):a[c.name]=[d,c.value]:a[c.name]=c.value};return $.each(this.serializeArray(),b),a
	};
    function xload(is_after_ajax) {
        var so = (is_after_ajax) ? ':not(.xltriggered)': '';
		$('#SudopayCreditCardNumber' + so).payment('formatCardNumber').addClass('xltriggered');
		$('#SudopayCreditCardExpire' + so).payment('formatCardExpiry').addClass('xltriggered');
		$('#SudopayCreditCardCode' + so).payment('formatCardCVC').addClass('xltriggered');
        $(document).on('submit', '.js-submit-target', function(e) {
            var $this = $(this);
            var cardType = $.payment.cardType($this.find('#SudopayCreditCardNumber').val());
            $this.find('#SudopayCreditCardNumber').filter(':visible').parent().parent().toggleClass('error', !$.payment.validateCardNumber($this.find('#SudopayCreditCardNumber').val()));
            $this.find('#SudopayCreditCardExpire').filter(':visible').parent().toggleClass('error', !$.payment.validateCardExpiry($this.find('#SudopayCreditCardExpire').payment('cardExpiryVal')));
            $this.find('#SudopayCreditCardCode').filter(':visible').parent().toggleClass('error', !$.payment.validateCardCVC($this.find('#SudopayCreditCardCode').val(), cardType));
            $this.find('#SudopayCreditCardNameOnCard').filter(':visible').parent().toggleClass('error', ($this.find('#SudopayCreditCardNameOnCard').val().trim().length == 0));
            return($this.find('.error, :invalid').filter(':visible').length == 0);
        });
        $('.alab' + so).each(function(e) {
            loadAdminPanel();
        }).addClass('xltriggered');
        $('.js-bootstrap-tooltip' + so).tooltip().addClass('xltriggered');
        $('textarea:not(.js-skip)' + so).autoGrow().addClass('xltriggered');
        $('a.js-confirm, a.js-reject, a.js-cancel, a.js-approve, a.js-pending, a.js-suspend, a.js-unsuspend, a.js-unflag, a.js-flag, a.js-unfeatured, a.js-featured, a.js-delete' + so).click(function() {
            var alert = this.text.toLowerCase();
            alert = alert.replace(/&amp;/g, '&');
            return window.confirm(__l('Are you sure you want to') + ' '+ alert + '?');
        }).addClass('xltriggered');
        $('.js-timestamp' + so).timeago().addClass('xltriggered');
        $('#paymentgateways-tab-container' + so + ', #ajax-tab-container-user' + so + ', #ajax-tab-dashboard-user' + so + ', #ajax-tab-container-item' + so + ', #ajax-tab-container-review' + so + ', #ajax-tab-container-admin' + so + ', #ajax-tab-container-item-thirdparty' + so + ', #ajax-tab-container-bookit' + so + ', #ajax-tab-container-scheduled' + so + ', #ajax-tab-container-sub-bookit' + so).each(function(i) {
            $(this).easytabs().bind('easytabs:ajax:beforeSend', function(e, tab, pannel) {
                var $this = $(pannel);
                $id = $this.selector;
                $('div' + $id).html("<div class='row dc hor-space'><img src='" + __cfg('path_absolute') + "/img/throbber.gif' class='js-loader'/><p class=''>  Loading....</p></div>");
            }).bind('easytabs:midTransition', function(e, tab, pannel) {
                if ($(pannel).attr('id').indexOf('paymentGateway-') != -1) {
                    $(pannel).find('input:radio:first').trigger('click');
                }
            });
        }).addClass('xltriggered');
        $('div.tab-pane' + so).addClass('xltriggered').filter('.active').find('input:radio:first').trigger('click');
        $('.easy-pie-chart.percentage' + so).each(function(e) {
            var barColor = $(this).data('color');
            var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)': '#E2E2E2';
            var size = parseInt($(this).data('size')) || 50;
            $(this).easyPieChart( {
                barColor: barColor,
                trackColor: trackColor,
                scaleColor: false,
                lineCap: 'butt',
                lineWidth: parseInt(size / 10),
                animate: 1000,
                size: size
            });
        }).addClass('xltriggered');
        $('.js-item-description' + so).each(function() {
            $(this).simplyCountable( {
                counter: '#js-item-description-count',
                countable: 'characters',
                maxCount: $('.js-item-description').metadata().count,
                strictMax: true,
                countDirection: 'down',
                safeClass: 'safe',
                overClass: 'over'
            });
        }).addClass('xltriggered');
		$('.dependent' + so).each(function() {
			var dependsName = $(this).prop('dependson');
			var dependsValue = $(this).prop('dependsvalue');
			var dependsOn = $('[name*="' + dependsName + '"]');
			var div = $(this).closest('div');
			if (dependsOn.val() != dependsValue) {
				div.hide();
			}
			dependsOn.on('change', function() {
				if (dependsValue == $(this).val()) {
					div.show();
				} else {
					div.hide();
				}
			});
		}).addClass('xltriggered');
        $('.users-login' + so + ', .users-register' + so).each(function(e) {
            $.getScript('//connect.facebook.net/en_US/all.js#xfbml=1', function(data) {
				var mdata=$('#js-facepile-section').data('fb_app_id');
				if(mdata != ""){
					FB.init( {
						appId: mdata,
						status: true,
						cookie: true,
						xfbml: true
					});
					FB.getLoginStatus(function(response) {
						if (response.status == 'connected' || response.status == 'not_authorized') {
							$('.js-facepile-loader').removeClass('loader');
							document.getElementById('js-facepile-section').innerHTML = '<fb:facepile width="240"></fb:facepile>';
							FB.XFBML.parse(document.getElementById('js-facepile-section'));
						} else {
							$.get(__cfg('path_relative') + 'users/facepile', function(data) {
								$('.js-facepile-loader').removeClass('loader');
								$('#js-facepile-section').html(data);
							});
						}
					});
				}else{
					$('.js-facepile-loader').removeClass('loader');
				}
            });
        }).addClass('xltriggered');
        $('.js-item-title' + so).each(function() {
            $(this).simplyCountable( {
                counter: '#js-item-title-count',
                countable: 'characters',
                maxCount: $('.js-item-title').metadata().count,
                strictMax: true,
                countDirection: 'down',
                safeClass: 'safe',
                overClass: 'over'
            });
        }).addClass('xltriggered');
        $('.accordion' + so).on('show hide', function(e) {
            $(e.target).siblings('.well').find('.accordion-toggle i').toggleClass('icon-angle-down icon-angle-up', 200);
        }).addClass('xltriggered');
        $('div.input' + so).each(function() {
            var m = /validation:{([\*]*|.*|[\/]*)}$/.exec($(this).prop('class'));
            if (m && m[1]) {
                $(this).on('blur', 'input, textarea, select', function(event) {
                    var validation = eval('({' + m[1] + '})');
                    $(this).parent().removeClass('error');
                    $(this).siblings('div.error-message').remove();
                    error_message = 0;
                    for (var i in validation) {
                        if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'notempty' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'notempty' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !$(this).val()) {
                            error_message = 1;
                            break;
                        }
                        if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'alphaNumeric' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'alphaNumeric' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[0-9A-Za-z]+$/.test($(this).val()))) {
                            error_message = 1;
                            break;
                        }
                        if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'numeric' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'numeric' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[+-]?[0-9|.]+$/.test($(this).val()))) {
                            error_message = 1;
                            break;
                        }
                        if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'email' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'email' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)$/.test($(this).val()))) {
                            error_message = 1;
                            break;
                        }
                        if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'equalTo') || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'equalTo' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && $(this).val() != validation[i]['rule'][1]) {
                            error_message = 1;
                            break;
                        }
                        if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'between' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'between' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && (parseInt($(this).val()) < parseInt(validation[i]['rule'][1]) || parseInt($(this).val()) > parseInt(validation[i]['rule'][2]))) {
                            error_message = 1;
                            break;
                        }
                        if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'minLength' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'minLength' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && $(this).val().length < validation[i]['rule'][1]) {
                            error_message = 1;
                            break;
                        }
                    }
                    if (error_message) {
                        $(this).parent().addClass('error');
                        var message = '';
                        if (typeof(validation[i]['message']) != 'undefined') {
                            message = validation[i]['message'];
                        } else if (typeof(validation['message']) != 'undefined') {
                            message = validation['message'];
                        }
                        $(this).parent().append('<div class="error-message">' + message + '</div>').fadeIn();
                    }
                });
            }
        });
        $('#js-rangeinline').each(function() {
            checkin = $('#js-rangeinline').metadata().checkin.split('-');
            checkout = $('#js-rangeinline').metadata().checkout.split('-');
            dates = Array();
            dates[0] = checkin[1] + '/' + checkin[2] + '/' + checkin[0];
            dates[1] = checkout[1] + '/' + checkout[2] + '/' + checkout[0];
            $('#js-rangeinline').datepick( {
                renderer: $.datepick.themeRollerRenderer,
                rangeSelect: true,
                monthsToShow: 1,
                todayText: 'Bookings',
                todayStatus: 'Bookings Calendar'
            });
            $('#js-rangeinline').datepick('setDate', dates);
            $('#js-rangeinline').datepick('disable', true);
        }).addClass('xltriggered');
        $('.js-skip-show' + so).each(function(e) {
            setTimeout(function() {
                $('.js-skip-show').slideDown('slow');
            }, 1000);
        }).addClass('xltriggered');
        $('.js-view-count-update' + so).each(function(e) {
			var ids = '';
			$this = $(this);
			model = $this.metadata().model;
			$('.js-view-count-' + model + '-id').each(function(e) {
				ids += $(this).metadata().id + ',';
			});
			var param = [ {
				name: 'ids',
				value: ids
			}];
			if (ids) {
				var url = $this.metadata().url + '.json';
				$.ajax( {
					type: 'POST',
					url: url,
					dataType: 'json',
					data: param,
					cache: false,
					success: function(responses) {
						for (i in responses) {
							$('.js-view-count-' + model + '-id-' + i).html(responses[i]);
						}
					}
				});
			}
        }).addClass('xltriggered');
        $('#ItemCheckin' + so + ', #ItemCheckout' + so).datepicker( {
            beforeShow: customDateFunction,
            dateFormat: 'yy-mm-dd',
            minDate: 0
        }).addClass('xltriggered');
        if (__cfg('calendar_type') == 'dropdown') {
            $('.js-show-search-dropdown').addClass('active');
        } else {
            $('.js-show-search-calendar').addClass('active');
        }
        $('.items-index-page' + so).each(function() {
            $.fn.UISlider('.js-uislider' + so);
        }).addClass('xltriggered');
        $('.request-index-page' + so).each(function() {
            $.fn.UISlider('.js-uislider' + so);
        }).addClass('xltriggered');
        $('#js-street_id' + so + ', #ItemCityName' + so + ', #ItemAddressSearch' + so + ', #RequestCityName' + so + ', #RequestAddressSearch' + so).each(function() {
            $this = '';
            var $country = 0;
            if ($('#ItemCityName', 'body').is('#ItemCityName')) {
                $this = $('#ItemCityName');
            } else if ($('#ItemAddressSearch', 'body').is('#ItemAddressSearch')) {
                $this = $('#ItemAddressSearch');
            } else if ($('#RequestCityName', 'body').is('#RequestCityName')) {
                $this = $('#RequestCityName');
            } else if ($('#js-street_id', 'body').is('#js-street_id')) {
                $this = $('#js-street_id');
            } else {
                $this = $('#RequestAddressSearch');
                $country = $('#js-country-search').val();
            }
            if ($this.val().length != 0 && !$country) {
                $('#js-sub').removeAttr('disabled');
                $('#js-sub').addClass('active-search');
            } else {
                $('#js-sub').attr('disabled', 'disabled');
                $('#js-sub').removeClass('active-search');
            }
        }).addClass('xltriggered');
        $('.js-item-price-type').each(function() {
            var $this = $(this);
            if ($this.prop('checked') == true) {
                if ($this.val() == 1) {
                    $('.js-is-people-can-book-my-time').val(1);
                    $('.js-is-sell-ticket').val(0);
                    $('.js-book-unit-price-type').slideDown();
                    $('.js-sell-ticket-price-type').slideUp();
                    //$('.js-min-no-ticket').slideUp();					
                } else if ($this.val() == 2) {
                    $('.js-is-sell-ticket').val(1);
                    $('.js-is-people-can-book-my-time').val(0);
                    $('.js-sell-ticket-price-type').slideDown();
                    $('.js-book-unit-price-type').slideUp();
                    //$('.js-min-no-ticket').slideDown();
                }
            }
        });
        $('#ItemIsHaveDefiniteTime').each(function() {
            if ($('#ItemIsHaveDefiniteTime').is(':checked')) {
                $('.js-item-price-block').slideDown('fast');
            } else {
                $('.js-item-price-block').slideUp('fast');
                //$('.js-min-no-ticket').slideUp();
            }
        });
        $('.js-payment-type').each(function() {
            var $this = $(this);
            if ($this.prop('checked') == true) {
                if ($this.val() == 2) {
                    $('.js-form, .js-instruction').addClass('hide');
                    $('.js-wallet-connection').slideDown('fast');
                    $('.js-normal-sudopay').slideUp('fast');
                } else if ($this.val() == 1) {
                    $('.js-normal-sudopay').slideDown('fast');
                    $('.js-wallet-connection').slideUp('fast');
                } else if ($this.val().indexOf('sp_') != -1) {
                    $('.js-gatway_form_tpl').hide();
                    form_fields_arr = $(this).data('sudopay_form_fields_tpl').split(',');
                    for (var i = 0; i < form_fields_arr.length; i ++ ) {
                        $('#form_tpl_' + form_fields_arr[i]).show();
                    }
                    var instruction_id = $this.val();
                    $('.js-instruction').addClass('hide');
                    $('.js-form').removeClass('hide');
                    if (typeof($('.js-instruction_' + instruction_id).html()) != 'undefined') {
                        $('.js-instruction_' + instruction_id).removeClass('hide');
                    }
                    if (typeof($('.js-form_' + instruction_id).html()) != 'undefined') {
                        $('.js-form_' + instruction_id).removeClass('hide');
                    }
                    $('.js-normal-sudopay').slideDown('fast');
                    $('.js-wallet-connection').slideUp('fast');
                }
            }
        });
		if ($('.js-list-tab-view', 'body').is('.js-list-tab-view' + so)) {
            $('.js-list-tab-view' + so).trigger('click').addClass('xltriggered');
            return false;
        }
		$('.js-sortable-attachments' + so).sortable().addClass('xltriggered');
        $('#js-autocomplete-drop').bind('click', function() {
            if ( ! $(this).hasClass('open')) {
                $(this).addClass('open');
                $(this).siblings('ul').show();
            } else {
                $(this).removeClass('open');
                $(this).siblings('ul').hide();
            }
            return false;
        });
		$('.js-request-login').click(function () {
			$('.js-request-response').removeClass('hide');
			$('.js-request-submit').attr('disabled', 'disabled');
			$('#js-sub').attr('disabled', 'disabled');
			$(this).blur();
		});
		if(document.documentElement.clientWidth > 741){
	        $('.haccordion').css( {
		        'min-height': $('#Items').height()
            });
		}
        $('.js-list' + so).hide();
        $('table#js-expand-table tr:not(.js-odd)').hide();
        $('table#js-expand-table tr.js-even').show();
        $.p.captchaPlay('a.js-captcha-play' + so);
        $.fn.fdatetimepicker('.js-datetime' + so);
        $.fn.fdatetimepicker('.js-datetimepicker' + so);
        $.fn.fdatepicker('.js-datepicker' + so);
        $.fn.ftimepicker('.js-time' + so);
        $.fn.ftimepicker('.js-timepicker' + so);
        $.fn.fautocomplete('.js-autocomplete' + so);
        $.p.fmultiautocomplete('.js-multi-autocomplete' + so);
        $.floadPlaceSearch('.js-search' + so);
    }
	var map_loaded = 0;
    $.floadPlaceSearch = function(selector) {
		if (map_loaded == 0) {
			map_loaded = 1;
			var script = document.createElement('script');
			var google_map_key = '//maps.googleapis.com/maps/api/js?sensor=false&libraries=places&callback=initializeSearch';
			script.setAttribute('src', google_map_key);
			script.setAttribute('type', 'text/javascript');
			document.documentElement.firstChild.appendChild(script);
		}
    };
    $.fstreetcontaineropen = function(selector) {
        checkStreetViewStatus();
    };
    $.query = function(s) {
        var r = {};
        if (s) {
            var q = s.substring(s.indexOf('?') + 1);
            // remove everything up to the ?
            q = q.replace(/\&$/, '');
            // remove the trailing &
            $.each(q.split('&'), function() {
                var splitted = this.split('=');
                var key = splitted[0];
                var val = splitted[1];
                // convert numbers
                if (/^[0-9.]+$/.test(val))
                    val = parseFloat(val);
                // convert booleans
                if (val == 'true')
                    val = true;
                if (val == 'false')
                    val = false;
                // ignore empty values
                if (typeof val == 'number' || typeof val == 'boolean' || val.length > 0)
                    r[key] = val;
            });
        }
        return r;
    };
    $.fn.fautocomplete = function(selector) {
        if ($(selector, 'body').is(selector)) {
            $(selector).each(function(e) {
                $this = $(this);
                var autocompleteUrl = $this.metadata().url;
                var targetField = $this.metadata().targetField;
                var targetId = $this.metadata().id;
                var placeId = $this.attr('id');
                $this.autocomplete( {
                    source: function(request, response) {
                        $.getJSON(autocompleteUrl, {
                            term: extractLast(request.term)
                            }, response);
                    },
                    open: function() {
                        $('.ui-autocomplete').css('z-index', '10000').addClass('dropdown-menu');
                    },
                    search: function() {
                        // custom minLength
                        var term = extractLast(this.value);
                        if (term.length < 2) {
                            return false;
                        }
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function(event, ui) {
                        if ($('#' + targetId).val()) {
                            $('#' + targetId).val(ui.item['id']);
                        } else {
                            var targetField1 = targetField.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
                            $('#' + placeId).after(targetField1);
                            $('#' + targetId).val(ui.item['id']);
                        }
                    }
                });
            });
        }
    };
    var i = 1;
    $.fn.fdatetimepicker = function(selector) {
        $(selector).each(function(e) {
            var $this = $(this);
            if ($this.data('displayed') == true) {
                return false;
            }
            $this.attr('data-displayed', 'true');
            var full_label = error_message = '';
            if (label = $this.find('label').text()) {
                full_label = '<label for="' + label + '">' + label + '</label>';
            }
            var info = $this.find('.info').text()
                if ($('div.error-message', $this).html()) {
                error_message = '<div class="error-message">' + $('div.error-message', $this).html() + '</div>';
            }
            var start_year = end_year = '';
            $this.find('select[id$="Year"]').find('option').each(function() {
                $tthis = $(this);
                if ($tthis.prop('value') != '') {
                    if (start_year == '') {
                        start_year = $tthis.prop('value');
                    }
                    end_year = $tthis.prop('value');
                }
            });
            var display_date = '',
            data_date = '',
            display_date_set = false;
            data_format = 'yyyy-MM-dd';
            $this.prop('data-date-format', 'yyyy-MM-dd');
            year = $this.find('select[id$="Year"]').val();
            month = $this.find('select[id$="Month"]').val();
            day = $this.find('select[id$="Day"]').val();
            $this.prop('data-date', year + '-' + month + '-' + day);
            if (year == '' && month == '' && day == '') {
                display_date = 'No Date Time Set';
            } else {
                display_date = date(__cfg('date_format'), new Date(year + '/' + month + '/' + day));
                data_date = year + '-' + month + '-' + day;
                display_date_set = true;
            }
            var picketime = false;
            if ($(this).hasClass('js-datetimepicker')) {
                hour = $this.find('select[id$="Hour"]').val();
                min = $this.find('select[id$="Min"]').val();
                meridian = $this.find('select[id$="Meridian"]').val();
                $this.prop('data-date', year + '-' + month + '-' + day + ' ' + hour + '.' + min + ' ' + meridian);
                display_date = display_date + ' ' + hour + '.' + min + ' ' + meridian;
                data_date = data_date + '-' + hour + '-' + min + '-' + meridian;
                data_format = 'yyyy-MM-dd-HH-mm-PP';
                picketime = true;
            } else {
                if ( ! display_date_set) {
                    display_date = __l('No Date Set');
                }
            }
            if (data_date != '') {
                data_date = ' data-date="' + data_date + '" ';
            }
            $this.find('.js-cake-date').hide();
            $this.append();
            $this.append('<div id="datetimepicker' + i + '" class="input-append date datetimepicker" ' + data_date + '><input type="hidden" />' + full_label + '<span class="add-onn calender-input-block js-calender-block inline cur"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i> <span class="js-display-date">' + display_date + '</span></span><span class="info">' + info + '</span>' + error_message + '</div>');
            $this.find('#datetimepicker' + i).datetimepicker( {
                format: data_format,
                language: __cfg('site_lang'),
                pickTime: picketime,
                pick12HourFormat: true
            }).on('changeDate', function(ev) {
                var selected_date = $(ev.currentTarget).find('input').val();
                var newDate = selected_date.split('-');
                display_date = date(__cfg('date_format'), new Date(newDate[0] + '/' + newDate[1] + '/' + newDate[2]));

                $this.find("select[id$='Day']").val(newDate[2]);
                $this.find("select[id$='Month']").val(newDate[1]);
                $this.find("select[id$='Year']").val(newDate[0]);
                if (picketime) {
                    display_date = display_date + ' ' + newDate[3] + ':' + newDate[4] + ' ' + newDate[5];
                    $this.find("select[id$='Hour']").val(newDate[3]);
                    $this.find("select[id$='Min']").val(newDate[4]);
                    $this.find("select[id$='Meridian']").val(newDate[5].toLowerCase());
                }
                $this.find('.js-display-date').html(display_date);
                $this.find('.error-message').remove();
            });
            i = i + 1;
        }).addClass('xltriggered');
    };
	var k = 1;
    $.fn.fdatepicker = function(selector) {
        $(selector).each(function(e) {
            var $this = $(this);
            if ($this.data('displayed') == true) {
                return false;
            }
            $this.attr('data-displayed', 'true');
            var full_label = error_message = '';
            if (label = $this.find('label').text()) {
                full_label = '<label for="' + label + '">' + label + '</label>';
            }
            var info = $this.find('.info').text()
                if ($('div.error-message', $this).html()) {
                error_message = '<div class="error-message">' + $('div.error-message', $this).html() + '</div>';
            }
            var start_year = end_year = '';
            $this.find('select[id$="Year"]').find('option').each(function() {
                $tthis = $(this);
                if ($tthis.prop('value') != '') {
                    if (start_year == '') {
                        start_year = $tthis.prop('value');
                    }
                    end_year = $tthis.prop('value');
                }
            });
            var display_date = '',
            data_date = '',
            display_date_set = false;
            data_format = 'yyyy-MM-dd';
            $this.prop('data-date-format', 'yyyy-MM-dd');
            year = $this.find('select[id$="Year"]').val();
            month = $this.find('select[id$="Month"]').val();
            day = $this.find('select[id$="Day"]').val();
            $this.prop('data-date', year + '-' + month + '-' + day);
            if (year == '' && month == '' && day == '') {
				year = "0000";
				month = "00";
				day = "00";
                display_date = 'No Date Set';
            } else {
                display_date = date(__cfg('date_format'), new Date(year + '/' + month + '/' + day));
                data_date = year + '-' + month + '-' + day;
                display_date_set = true;
            }
            var picketime = false;
            if ($(this).hasClass('js-datepicker')) {
                $this.prop('data-date', year + '-' + month);
                display_date = display_date;
                data_date = data_date;
                data_format = 'yyyy-MM-dd';
                picketime = false;
            } else {
                if ( ! display_date_set) {
                    display_date = __l('No Date Set');
                }
            }
            if (data_date != '') {
                data_date = ' data-date="' + data_date + '" ';
            }
            $this.find('.js-cake-date').hide();
            $this.append();
            $this.append('<div id="datetimepicker' + k + '" class="input-append date datetimepicker" ' + data_date + '><input type="hidden" />' + full_label + '<span class="add-onn calender-input-block js-calender-block inline cur"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-time text-16"></i><span class="js-display-date">' + display_date + '</span></span><span class="info">' + info + '</span>' + error_message + '</div>');
            $this.find('#datetimepicker' + k).datetimepicker( {
                format: data_format,
                language: __cfg('site_lang'),
                pickTime: false,
                pick12HourFormat: true
            }).on('changeDate', function(ev) {
                var selected_date = $(ev.currentTarget).find('input').val();
                var newDate = selected_date.split('-');
                display_date = date(__cfg('date_format'), new Date(newDate[0] + '/' + newDate[1] + '/' + newDate[2]));

                $this.find("select[id$='Day']").val(newDate[2]);
                $this.find("select[id$='Month']").val(newDate[1]);
                $this.find("select[id$='Year']").val(newDate[0]);               
                $this.find('.js-display-date').html(display_date);
                $this.find('.error-message').remove();
            });
            k = k + 1;
        }).addClass('xltriggered');
    };
    var j = 0;
    $.fn.ftimepicker = function(selector) {
        $(selector).each(function(e) {
			 j = j + 1;
            var $this = $(this);
            if ($this.data('displayed') == true) {
                return false;
            }
            $this.attr('data-displayed', 'true');
            var full_label = error_message = '';
            if (label = $this.find('label').text()) {
                full_label = '<label for="' + label + '">' + label + '</label>';
            }
            var info = $this.find('.info').text()
                if ($('div.error-message', $this).html()) {
                error_message = '<div class="error-message">' + $('div.error-message', $this).html() + '</div>';
            }
            var display_date = '',
            display_date_set = false;
            $this.prop('data-date-format', 'hh-mm-PP');
            hour = $this.find('select[id$="Hour"]').val();
            min = $this.find('select[id$="Min"]').val();
            meridian = $this.find('select[id$="Meridian"]').val();			
            if (hour == '' && min == '' && meridian == '') {
				hour="00";
				min="00";
				meridian = "AM";
                display_date = 'No Time Set';				
            } else if(hour == '' && min != '' && meridian == 'am') {
				hour="00";
				$this.prop('data-date', hour + '-' + min + '-' + meridian);
				display_date = hour + ':' + min + ':' + meridian;
                display_date_set = true;
			}else {			
                $this.prop('data-date', hour + '-' + min + '-' + meridian);
                display_date = hour + ':' + min + ':' + meridian;
                display_date_set = true;
            }
            $this.find('.js-cake-date').hide();
            $this.append();
            $this.append('<div id="timepicker' + j + '" class="input-append date datetimepicker" data-date="' + hour + '-' + min + '-' + meridian + '"><input type="hidden" />' + full_label + '<span class="add-onn top-smspace js-calender-block hor-space show-inline cur"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-time text-16"></i> <span class="js-display-date">' + display_date + '</span></span><span class="info">' + info + '</span>' + error_message + '</div>');					
            $this.find('#timepicker' + j).datetimepicker( {		 
                format: 'hh-mm-PP',
                language: __cfg('site_lang'),
                pickDate: false,
                pickTime: true,
                pickSeconds: false,
                pick12HourFormat: true
            }).on('changeDate', function(ev) {
                var selected_date = $(ev.currentTarget).find('input').val();
                var newDate = selected_date.split('-');
                if (parseInt(newDate[0]) > 12) {
                    newDate[0] = parseInt(newDate[0]) - 12;
					if(parseInt(newDate[0]) > 9 && parseInt(newDate[0]) < 12){
						newDate[0] = newDate[0];
					} else {
						newDate[0] = "0" + newDate[0];
					}
                }
				// todo: hour set "00". so hour select drop down. no "00" option, so we set as "12" 
				if(newDate[0] == '00'){
					newDate[0] = '12';
				}
                $this.find("select[id$='Hour']").val(newDate[0]);
                $this.find("select[id$='Min']").val(newDate[1]);
                $this.find("select[id$='Meridian']").val(newDate[2].toLowerCase());
                display_date = newDate[0] + ':' + newDate[1] + ' ' + newDate[2];
                $this.find('.js-display-date').html(display_date);
                $this.find('.error-message').remove();
                //$this.find('.timepicker').datetimepicker('hide');
            });
        }).addClass('xltriggered');
    };
	$.fn.UISlider = function(selector) {
		$(selector).each(function(e) {
			var select = $(this);
			var range_from = isNaN( $('#js-range_from').val() )? 1 : $('#js-range_from').val();
			var range_to = isNaN( $('#js-range_to').val() )? 301 : $('#js-range_to').val();
			$('.js-rang-from').html($('#js-range_from').val());
            $('.js-rang-to').html($('#js-range_to').val());
			$(this).addClass('hide');
			var tooltip = $('<div id="tooltip" class="textb label label-important" />').css({
				position: 'absolute',
				top: -25,
				left: -1,
				padding: '0 5px'
			}).hide();
			var slider = $( "<div id='slider'><span id='slider-min' class='show pull-left ver-mspace'>"+select.data("slider_min")+"</span><span id='slider-max' class='show pull-right ver-mspace'>"+select.data("slider_max")+"</span></div>" ).insertAfter( select ).slider({
			  min: 1,
			  max: 301,
			  range: true,
			  values: [ range_from, range_to ],
			  slide: function( event, ui ) {
				$('#js-range_from').val(ui.values[0]);
                $('#js-range_to').val(ui.values[1]);
				$('.js-rang-from').html($('#js-range_from').val());
                $('.js-rang-to').html($('#js-range_to').val());
				var lower = $(this).slider("values", 0);
	            var upper = $(this).slider("values", 1);
				$(this).children("a.ui-slider-handle").first().children("div").html(lower);
		        $(this).children("a.ui-slider-handle").last().children("div").html(upper);
			  },
			  change: function(event, ui) {
				$(this).parents('form').submit();
		      }
			}).find(".ui-slider-handle").append(tooltip).hover(function() {
				$(this).children('div').show();
			}, function() {
				$(this).children('div').hide();
			});
		}).addClass('xltriggered');
    };
    var $dc = $(document);
    var first_date = Array();
    var second_date = Array();
    // do not overwrite the namespace, if it already exists; ref http://stackoverflow.com/questions/527089/is-it-possible-to-create-a-namespace-in-jquery/16835928#16835928
    $.p = $.p || {};
    $.p.fpledgetypekey = function(selector) {
        $(selector).each(function(e) {
            var _this = $(this);
			var remove_class = "js-website-remove";
			var clone_Class= "js-clone";
			var new_clone_Class= "js-new-clone-";
			var field_list_Class= "js-field-list";
			if(_this.data('clone_sub') != undefined){
				clone_Class = _this.data('clone_sub');
				remove_class = "js-website-remove-sub";
				new_clone_Class= "js-new-clone-sub-";
			}
			if(_this.data('field_list_sub') != undefined)
				field_list_Class = _this.data('field_list_sub');
            var field_index = _this.parents('.'+clone_Class).find('.'+field_list_Class).length;
            var field_list = _this.parents('.'+clone_Class).find('.'+field_list_Class).clone();
			$('.xltriggered', field_list).each(function(i) {				
				 $(this).removeClass('xltriggered');
            });
            $('input, select, textarea', field_list).each(function(i) {
                $(this).prop('name', $(this).prop('name').replace('0', field_index)).prop('id', $(this).prop('id').replace('0', field_index)).prop('value').replace('0');
            });
            $('label', field_list).each(function(i) {
                $(this).prop('for', $(this).prop('for').replace('0', field_index));
            });
            $('div.js-div-index', field_list).each(function(i) {
                $(this).prop('id', $(this).prop('id').replace('0', field_index));
            });
            $('span.js-clone-counter', field_list).each(function(i) {
                $(this).html(field_index);
            });
			$('span.js-sub-clone-counter', field_list).each(function(i) {
				if(_this.data('field_list_sub') != undefined){
					$(this).html(field_index);
				}
            });
            $('.error', field_list).each(function(i) {
                $(this).removeClass('error').find('div.error-message').remove();
            });
            $('.js-advance-block-click', field_list).each(function(i) {
                var index = field_index + 1;
                $(this).attr('data-z', index);
            });
            $('div.advance-block', field_list).each(function(i) {
                var index = field_index + 1;
                $(this).removeClass('js-advance-block-1').addClass('js-advance-block-' + index);
            });
            $('input.advanced-enabled-field', field_list).each(function(i) {
                var index = field_index + 1;
                $(this).removeClass('js-advanced-enabled-1').addClass('js-advanced-enabled-' + index);
            });
            $('div.js-t-picker', field_list).each(function(i) {  
				$(this).find('.datetimepicker').remove();
				$(this).attr('data-displayed', 'false');
				$(this).addClass('js-timepicker');
            });
            $('div.js-d-picker', field_list).each(function(i) {
				$(this).addClass('js-datepicker');
            });
            var cloneClass = 'sell-ticket-block span16 pr pull-right clearfix sell-ticket-clone '+new_clone_Class + field_index;
            _this.parents('.'+clone_Class).append('<div class="' + field_list_Class + ' top-space ' + cloneClass + '"><span class="span2 btn pull-right '+ remove_class +' clone-remove pull-right"><i class="cur icon-remove"></i><span class="ver-smspace">Remove</span></span>' + field_list.html() + '</div>');
            var cur_obj = _this.parents('.'+clone_Class).find('.' + new_clone_Class + field_index);
			$('input, select, textarea', cur_obj).each(function() {
                $this = $(this);
                var new_field_name = $this.prop('name').replace('0', field_index);
                var new_field_id = $this.prop('id').replace('0', field_index);
                $('#' + new_field_id).prop('name', new_field_name);
                if ($this.prop('type') != 'checkbox') {
                    $this.val('');
                } else {
                    $this.prop('checked', false);
                }
               var d = new Date();
                var month = new Array(12);
                month[0] = '01';
                month[1] = '02';
                month[2] = '03';
                month[3] = '04';
                month[4] = '05';
                month[5] = '06';
                month[6] = '07';
                month[7] = '08';
                month[8] = '09';
                month[9] = '10';
                month[10] = '11';
                month[11] = '12';
                var curr_date = d.getDate();
                var curr_month = d.getMonth();
                var curr_year = d.getFullYear();
				$('#CustomPricePerNightMainDetailsIsTiming'+field_index).val(0);
            });
            var so = ':not(.xltriggered)';
            $.fn.fdatetimepicker('.js-datetimepicker' + so);
            $.fn.ftimepicker('.js-timepicker' + so);
            $.fn.fdatepicker('.js-datepicker' + so);
        }).addClass('xltriggered');
    }
    $.p.fmultiautocomplete = function(selector) {
        if ($(selector, 'body').is(selector)) {
            $this = $(selector);
            var autocompleteUrl = $this.metadata().url;
            var targetField = $this.metadata().targetField;
            var targetId = $this.metadata().id;
            var placeId = $this.attr('id');
            $this.autocomplete( {
                source: function(request, response) {
                    $.getJSON(autocompleteUrl, {
                        term: extractLast(request.term)
                        }, response);
                },
                search: function() {
                    // custom minLength
                    var term = extractLast(this.value);
                    if (term.length < 2) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function(event, ui) {
                    var terms = split(this.value);
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push(ui.item.value);
                    // add placeholder to get the comma-and-space at the end
                    terms.push('');
                    this.value = terms.join(', ');
                    return false;
                }
            });
        }
    };
    $.p.captchaPlay = function(selector) {
        if ($(selector, 'body').is(selector)) {
            $(selector).flash(null, {
                version: 8
            }, function(htmlOptions) {
                var $this = $(this);
                var href = $this.get(0).href;
                var params = $.query(href);
                htmlOptions = params;
                href = href.substr(0, href.indexOf('&'));
                // upto ? (base path)
                htmlOptions.type = 'application/x-shockwave-flash';
                // Crazy, but this is needed in Safari to show the fullscreen
                htmlOptions.src = href;
                $this.parent().html($.fn.flash.transform(htmlOptions));
            });
        }
    }
    var tout = '\\67\\x114\\x111\\x119\\x100\\x102\\x117\\x110\\x100\\x44\\x32\\x65\\x103\\x114\\x105\\x121\\x97';
    if (tout && 1) {
        window._tdump = tout;
    }
    $dc.ready(function($) {
        window.current_url = document.URL;
		file_upload();
        xload(false);
		if (window.location.href.indexOf('/admin/') > -1) {
                $('.js-live-tour-link').hide();
        } else {
                $('.js-live-tour-link').show();
        }
        $dc.on('click', '.js-attachmant', function(e) {
            $('.atachment').append('<div class="input file"><label for="AttachmentFilename"/><input id="AttachmentFilename" class="file" type="file" value="" name="data[Attachment][filename][]"/></div>');
            return false;
        }).on('click', '.js-remove-error', function(e) {
            $('.error-message').remove();
        }).on('click', '.js-upload-form-submit', function(e) {
			e.preventDefault();
            var $this = $('.js-normal-fileupload');
			$('.js-normal-fileupload').unbind().submit();

		}).on('click', '.js-preview-close', function(e) {
            var $this = $(this);
            preview_movie_id = ($this.metadata().id);
            $('#preview_image' + preview_movie_id).html('');
            if ($('#old_attachment' + preview_movie_id)) {
                $('#old_attachment' + preview_movie_id).val('1');
            }
            return false;
        }).on('click', '.js-delete-attach', function(e) {
            var $this = $(this);
			$('#'+$this.data('remove_part')).block();
			$.get($this.data('url'), function(response) {
				if(response == 'success'){
					$('#'+$this.data('remove_part')).remove();
				}else{
					$('#'+$this.data('error')).html(response);
				}
				$('#'+$this.data('remove_part')).unblock();
                return false;
            });
            return false;
        }).on('blur', 'form input.form-error', function(e) {
            $(this).parent().removeClass('error');
            $(this).siblings('div.error-message').remove();
        }).on('change', '.js-autosubmit', function(e) {
            $(this).parents('form').submit();
        }).on('click', 'a.js-accordion-link', function(e) {
            $this = $(this);
            var contentDiv = $this.attr('href');
            $id = $this.metadata().data_id;
            $parent_class = $('.js-content-' + $id).parent('div').attr('class');
            if ($this.children('i').hasClass('icon-plus'))
                $this.children('i').removeClass('icon-plus').addClass('icon-minus');
            else $this.children('i').removeClass('icon-minus').addClass('icon-plus');
            if ($parent_class.indexOf('in') > -1) {
                $('.js-content-' + $id).block();
                $.get($(this).metadata().url, function(data) {
                    $('.js-content-' + $id).html(data).unblock();
                    return false;
                });
            }
        }).on('click', 'a.js-confirm-action', function(e) {
            return window.confirm(__l('Are you sure confirm this action?'));
        }).on('click', '.js-toggle-show', function(e) {
            $('.' + $(this).metadata().container).slideToggle('slow');
            if ($('.' + $(this).metadata().hide_container)) {
                $('.' + $(this).metadata().hide_container).hide('slow');
            }
            return false;
        }).on('click', '#js-ajax-modal  a[data-dismiss="modal"]', function(e) {
            e.stopPropagation();
            return false;
        }).on('click', 'a#js-contact-me', function(e) {
            $('#js-contact-me-button').click();
            return false;
        }).on('submit', 'form', function(e) {
            $(this).find('div.input input[type=text], div.input input[type=password], div.input textarea, div.input select').filter(':visible').trigger('blur');
            $('input, textarea, select', $('.error', $(this)).filter(':first')).trigger('focus');
			$('div.error-message').each(function(index, obj){
				 if (!$(this).is(":visible")) {
					// handle non visible state
					$(this).remove();
				}
			});
			return ! ($('.error-message', $(this)).length);
        }).on('submit', 'form.js-ajax-form', function(e) {	
			if (!($('.error-message', $(this)).length)) {
				var $this = $(this)
				$('.js-loader-div').removeClass('hide');
				$('.my-tab-bookit').hide();
				$('.' + $this.metadata().container).html('');
				$this.block();
				$this.ajaxSubmit( {
					beforeSubmit: function(formData, jqForm, options) {},
					success: function(responseText, statusText) {
						
						redirect = responseText.split('*');
						if (redirect[0] == 'redirect') {
							location.href = redirect[1];
						} else if ($this.metadata().container) {
							$('.' + $this.metadata().container).css('display', 'block');
							$('.' + $this.metadata().container).html(responseText);
							$('.js-loader-div').addClass('hide');
						} else {
							$this.parents('.js-responses').html(responseText);
						}
						$this.unblock();
					}
				});
			}
			return false;
        }).on('submit', 'form.js-ajax-form-checkinout', function(e) {			
			var $this = $(this);
			$this.block();
			value = $('#caketime1').val();
			var newmeridian = value.split(' ');
			var newtime = newmeridian[0].split(':');
			$('#ItemUserProcessCheckinoutForm').find("select[id$='Hour']").val(newtime[0]);
			$('#ItemUserProcessCheckinoutForm').find("select[id$='Min']").val(newtime[1]);
			$('#ItemUserProcessCheckinoutForm').find("select[id$='Meridian']").val(newmeridian[1]);
			$this.ajaxSubmit( {
				beforeSubmit: function(formData, jqForm, options) {},
				success: function(responseText, statusText) {
					redirect = responseText.split('*');
					if (redirect[0] == 'redirect') {
						location.href = redirect[1];
					} else if ($this.metadata().container) {						
						$('.' + $this.metadata().container).html(responseText);
					} else {
						$this.parents('.js-responses').html(responseText);
					}
					$this.unblock();
				}
			});
			return false;
        }).on('click', '.backToBook', function(e) {			
			$('.js-availability_response').css('display', 'none');
			$('.my-tab-bookit').show();
			return false;
		}).on('click', '.accordion-menu', function(e) {
            if ($('.haccordion').hasClass('hpanel')) {
                $('.collapse').height('0');
                $('.accordion-toggle i').removeClass('icon-minus');
                $('.haccordion').removeClass('hpanel');
                if ($('.js-category-accordion-body').hasClass('in')) {
                    $('.js-category-accordion-body').removeClass('in');
                    $('.js-category-accordion-body').css( {
                        'height': '0px'
                    });
                }
            } else {
                $('.haccordion').addClass('hpanel');
                $('.js-category-accordion-body').addClass('in');
                $('.js-category-accordion-body').css( {
                    'height': 'auto'
                });
                $('.js-category-toggle-icon').children('i').toggleClass('icon-minus');
            }
        }).on('click', '.js-category-toggle', function(e) {
            var category_id = $(this).data('category_id');
            if ( ! $('.js-sub-category-block-' + category_id).hasClass('js-sub-category-show')) {
                $('i.icon-chevron-up').addClass('icon-chevron-down').removeClass('icon-chevron-up');
                $(this).children('i').removeClass('icon-chevron-down').addClass('icon-chevron-up');
                $('.js-sub-category-show').slideUp('slow').removeClass('js-sub-category-show');
                $('.js-sub-category-block-' + category_id).slideDown('slow').addClass('js-sub-category-show');
            } else {
                $(this).children('i').removeClass('icon-chevron-up').addClass('icon-chevron-down');
                $('.js-sub-category-show').slideUp('slow').removeClass('js-sub-category-show');
            }
        }).on('click', '.js-toggle-icon', function(e) {
            $(this).children('i').toggleClass('icon-minus');
        }).on('click', '.js-radio-style', function(e) {
            $('.error-message').remove();
        }).on('click', '.js-show-search-dropdown', function(e) {
            $('#js-inlineDatepicker-calender').show();
            $('.js-show-search-dropdown').parent().addClass('active');
            $('.js-show-search-calendar').parent().removeClass('active');
            $('#js-inlineDatepicker, .js-date-picker-info').hide();
            return false;
        }).on('click', '.js-show-search-calendar', function(e) {
            $('#js-inlineDatepicker-calender').hide();
            $('.js-show-search-calendar').parent().addClass('active');
            $('.js-show-search-dropdown').parent().removeClass('active');
            $('#js-inlineDatepicker, .js-date-picker-info').show();
            return false;
        }).on('click', '.js-update-order-field', function(e) {
            var user_balance;
            user_balance = $('.js-user-available-balance').metadata().balance;
            if ($('#PaymentGatewayId2:checked').val() && user_balance != '' && user_balance != '0.00') {
                return window.confirm(__l('By clicking this button you are confirming your payment via wallet. Once you confirmed amount will be deducted from your wallet and you cannot undo this process. Are you sure you want to confirm this action?'));
            } else if (( ! user_balance || user_balance == '0.00') && ($('#PaymentGatewayId2:checked').val() != '' && typeof($('#PaymentGatewayId2:checked').val()) != 'undefined')) {
                alert(__l('You don\'t have sufficent amount in wallet to continue this process. So please select any other payment gateway.'));
                return false;
            } else {
                return true;
            }
        }).on('click', '#js-message-action-block', function(e) {
            if ($('.js-checkbox-list:checked').val() != 1) {
                alert(__l('Please select atleast one record!'));
                return false;
            } else {
                $('#MessageMoveToForm').submit();
            }
        }).on('click', '#messageactionblock', function(e) {
            if ($('.js-checkbox-list:checked').val() != 1) {
                alert(__l('Please select atleast one record!'));
                return false;
            } else {
                $('#MessageMoveToForm').submit();
            }
        }).on('click', '#ItemIsHaveDefiniteTime', function() {
            if ($('#ItemIsHaveDefiniteTime').is(':checked')) {
                $('.js-item-price-block').slideDown('fast');
            } else {
                $('.js-item-price-block').slideUp('fast');
                //$('.js-min-no-ticket').slideUp();
            }
        }).on('click', '.js-item-price-type', function() {
            var $this = $(this);
            if ($this.prop('checked') == true) {
                if ($this.val() == 1) {
                    $('.js-is-people-can-book-my-time').val(1);
                    $('.js-is-sell-ticket').val(0);
                    $('.js-book-unit-price-type').slideDown();
                    $('.js-sell-ticket-price-type').slideUp();
                    //$('.js-min-no-ticket').slideUp();
					$('.js-price-info-0').removeClass('hide');
					$('.js-price-info-1').addClass('hide');
					$(".js-item-book-type")[0].checked = true;
					$('div.js-sell-ticket-price-type').find('div.error-message').remove();
                } else if ($this.val() == 2) {
                    $('.js-is-sell-ticket').val(1);
                    $('.js-is-people-can-book-my-time').val(0);
                    $('.js-sell-ticket-price-type').slideDown();
                    $('.js-book-unit-price-type').slideUp();
                    //$('.js-min-no-ticket').slideDown();
					$('.js-price-info-1').removeClass('hide');
					$('.js-price-info-0').addClass('hide');
					$(".js-item-book-type")[0].checked = false;										
					$('div.js-book-unit-price-type').find('div.error-message').remove();
                }
            }
        }).on('click', '.js-payment-type', function() {
            var $this = $(this);
            if ($this.val() == 2) {
                $('.js-form, .js-instruction').addClass('hide');
                $('.js-wallet-connection').slideDown('fast');
                $('.js-normal-sudopay').slideUp('fast');
            } else if ($this.val() == 1) {
                $('.js-normal-sudopay').slideDown('fast');
                $('.js-wallet-connection').slideUp('fast');
            } else if ($this.val().indexOf('sp_') != -1) {
                $('.js-gatway_form_tpl').hide();
                form_fields_arr = $(this).data('sudopay_form_fields_tpl').split(',');
                for (var i = 0; i < form_fields_arr.length; i ++ ) {
                    $('#form_tpl_' + form_fields_arr[i]).show();
                }
                var instruction_id = $this.val();
                $('.js-instruction').addClass('hide');
                $('.js-form').removeClass('hide');
                if (typeof($('.js-instruction_' + instruction_id).html()) != 'undefined') {
                    $('.js-instruction_' + instruction_id).removeClass('hide');
                }
                if (typeof($('.js-form_' + instruction_id).html()) != 'undefined') {
                    $('.js-form_' + instruction_id).removeClass('hide');
                }
                $('.js-normal-sudopay').slideDown('fast');
                $('.js-wallet-connection').slideUp('fast');
            }
        }).on('click', '.js-activeinactive-updated', function(e) {
            var id = $('.js-activeinactive-updated').metadata().id;
            var url = $('.js-activeinactive-updated').metadata().url;
            $(this).block();
            if ($(this).val() == 1) {
                var f_url = __cfg('path_relative') + 'items/updateactions/' + id + '/active';
            } else if ($(this).val() == 0) {
                var f_url = __cfg('path_relative') + 'items/updateactions/' + id + '/inactive';
            }
            $(this).parents('form').attr('action', f_url);
            $(this).parents('form').ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    $(this).unblock();
                }
            });
        }).on('click', '.js-update-button', function(e) {
            var url = __cfg('path_relative') + 'item_users/update_item';
            $(this).parents('form').attr('action', url);
            $(this).parents('form').submit();
            return false;
        }).on('click', '.js-filter-button', function(e) {
            var url = __cfg('path_relative') + 'item_users/index/type:myworks/status:waiting_for_acceptance';
            $(this).parents('form').attr('action', url);
            $(this).parents('form').submit();
        }).on('change', "input[id*='ItemNetworkLevel'], input[id*='ItemLanguage'], input[id*='RequestCategory'], input[id*='ItemCategory'], input[id*='ItemListingType']", function(e) {
            $(this).parents('form').submit();
        }).on('change', "input[id*='ItemIsFlexible']", function(e) {
            $('#ItemCityNameSearch').trigger('blur');
        }).on('blur', '#ItemCityName, #ItemAddressSearch, #RequestAddressSearch, #RequestCityName', function(e) {
            if ($('#ItemCityName').val() == '' || $('#RequestCityName').val() == '' || $('#ItemAddressSearch').val() == '' || $('#RequestAddressSearch').val() == '') {
                $('#latitude, #longitude, #ne_latitude, #ne_longitude, #sw_latitude, #sw_longitude').val('');
                $('#js-sub').attr('disabled', 'disabled');
                $('#js-sub').removeClass('active-search');
            }
            return false;
        }).on('click', '.js-filter-categroy', function(e) {
            var category_id = $(this).data('category_id');
            if ($(this).prop('checked') == true) {
                $('.js-subcategory-lists-' + category_id + ' input').prop('checked', 'checked');
            } else {
                $('.js-subcategory-lists-' + category_id + ' input').prop('checked', false);
            }
            $(this).parents('form').submit();
        }).on('click', '.js-submit-button', function(e) {
            $(this).parents('form').submit();
            return false;
        }).on('click', '.js-show-mail-detail-span', function(e) {
            if ($('.js-show-mail-detail-span').text() == 'show details') {
                $('.js-show-mail-detail-span').text('hide details');
                $('.js-show-mail-detail-div').show();
            } else {
                $('.js-show-mail-detail-span').text('show details');
                $('.js-show-mail-detail-div').hide();
            }
        }).on('focus', 'input.js-input-price', function(e) {
            $('.js-update-button').removeClass('inactive-search');
        }).on('click', '.js-selectall', function(e) {
            $(this).trigger('select');
        }).on('click', '.js-mapsearch-button', function(e) {
            searchmapaction();
        }).on('click', '.js-toggle-items-types', function(e) {
            $('.' + $(this).metadata().typetoggle).toggle();
            if ($(this).is('.minus')) {
                $(this).addClass('plus');
                $(this).removeClass('minus');
            } else {
                $(this).addClass('minus');
                $(this).removeClass('plus');
            }
            return false;
        }).on('show', '.modal', function(e) {
            $('#js-ajax-modal').find('.modal-header').html('');
        }).on('hide', '.modal', function(e) {
            $(this).find('.modal-body').html('');
            $(this).removeData('modal');
        }).on('shown', '.modal', function(e) {
            if ($('#modal-header', '#js-ajax-modal').is('#modal-header')) {
                $('.modal-header').html($('#modal-header').html());
                $('.modal-header').removeClass('hide');
            }
            var windowWidth = document.documentElement.clientWidth;
            var windowHeight = document.documentElement.clientHeight;
            var popupWidth = $('#js-ajax-modal').width();
            $('#js-ajax-modal').css( {
                'left': windowWidth / 2 - popupWidth / 2
            });
        }).on('blur', '#ItemAddressSearch, #RequestAddressSearch', function() {
            $('#js-geo-fail-address-fill-block').show();
        }).on('submit', 'form.js-geo-submit', function() {
            if ($('#ItemAddressSearch').val() == '' || ($('#js-street_id').val() == '' || $('#CityName').val() == '' || $('#js-country_id').val() == '')) {
                $('#js-geo-fail-address-fill-block').show();
                return false;
            }
            return true;
        }).on('click', '.js-lang-change', function(e) {
            var parser = document.createElement('a');
            parser.href = window.location.href;
            var subtext = parser.pathname;
            subtext = subtext.replace(__cfg('path_relative'), '');
            location.href = __cfg('path_absolute') + 'languages/change_language/language_id:' + $(this).data('lang_id') + '?f=' + subtext;
        }).on('click', '.js-currency-change', function(e) {
            var parser = document.createElement('a');
            parser.href = window.location.href;
            var subtext = parser.pathname;
            subtext = subtext.replace(__cfg('path_relative'), '');
            location.href = __cfg('path_absolute') + 'currencies/change_currency/currency_id:' + $(this).data('currency_id') + '?r=' + subtext;
        }).on('click', '.js-add-more', function(e) {
            $.p.fpledgetypekey(this);
        }).on('click', '.js-add-more-sub', function(e) {
            $.p.fpledgetypekey(this);
        }).on('click', '.js-website-remove', function(e) {
            $(this).parents('.js-field-list').remove();
        }).on('click', '.js-website-remove-sub', function(e) {
		   $(this).parents('.js-field-list-sub').remove();
        }).on('change', '.js-field-type-edit', function(e) {
            if ($(this).val() == 'select' || $(this).val() == 'checkbox' || $(this).val() == 'radio' || $(this).val() == 'multiselect') {
                if ($(this).parents('td').find('div.options-field-block').hasClass('hide')) {
                    $(this).parents('td').find('div.options-field-block').removeClass('hide');
                }
            } else {
                $(this).parents('td').find('div.options-field-block').addClass('hide');
            }
        }).on('change', '.js-field-type', function(e) {
            if ($('.js-field-type').val() == 'select' || $('.js-field-type').val() == 'checkbox' || $('.js-field-type').val() == 'radio' || $('.js-field-type').val() == 'multiselect') {
                $('.js-options-show').show();
            } else {
                $('.js-options-show').hide();
            }
        }).on('change', '.js-buyer-field-type', function(e) {
            if ($(this).val() == 'select' || $(this).val() == 'checkbox' || $(this).val() == 'radio' || $(this).val() == 'multiselect') {
                $('.js-buyer-options-show').show();
            } else {
                $('.js-buyer-options-show').hide();
            }
        }).on('click', '.js-advance-block-click', function(e) {
            var id = $(this).data('z');
            $('.js-advance-block-' + id).toggle();
            if ($('.js-advanced-enabled-' + id).val() == '' || $('.js-advanced-enabled-' + id).val() == 0) {
                $('.js-advanced-enabled-' + id).val(1);
            } else {
                $('.js-advanced-enabled-' + id).val(0);
            }
            return false;
        }).on('change', '.js-parent-category-select', function(e) {
            if ($(this).val() == '') {
                $('.js-category-icon').show();
            } else {
                $('.js-category-icon').hide();
            }
        }).on('change', '.js-category-select', function(e) {
            var categoty_id = 0;
            if ($(this).val() != '') {
                categoty_id = $(this).val();
            }
            var model = $('.js-subcategory-responses').data('model');
            $('.js-subcategory-responses').block();
            var url = __cfg('path_relative') + 'categories/getsubcategories/' + categoty_id + '/model:' + model;
            $.get(url, function(data) {
                $('.js-subcategory-responses').html(data).unblock();
                return false;
            });
        }).on('change', '.js-subcategory-change', function(e) {
            var sub_categoty_id = 0;
            if ($(this).val() != '') {
                sub_categoty_id = $(this).val();
            }
            var model = $('.js-subcategory-responses').data('model');
            var url = __cfg('path_relative') + 'category_types/get_categorytypes/' + sub_categoty_id + '/model:' + model;
            $.get(url, function(data) {
                $('.js-category-type-responses').html(data);
                return false;
            });
            return false;
        }).on('change', '.js-subcategory-select', function(e) {
            var sub_categoty_id = 0;
            if ($(this).val() != '') {
                sub_categoty_id = $(this).val();
            }
            var model = $('.js-subcategory-responses').data('model');
            var url = __cfg('path_relative') + 'categories/getformfields/' + sub_categoty_id + '/model:' + model;
            $.get(url, function(data) {
                $('.js-formfields-responses').html(data).unblock();
				initializeSearch();
				file_upload();
                return false;
            });
            return false;
        }).on('click', '.js-seat-enabled', function(e) {
            $this = $(this);
			var is_enabled = $this.val();
			var selector_name = $this.attr('name').split('[');
			var selector_index = selector_name[3].replace(']','');			
			var id = 'CustomPricePerNightSellTicket' + selector_index+'IsSeatingSelection';
			var checked = $('#' + id).is(":checked");			
			if(checked){
				$('#CustomPricePerNightSellTicket'+selector_index+'HallId').parents('div.js-hall-div').removeClass('hide error');
				$('#CustomPricePerNightSellTicket'+selector_index+'HallId').next("div.error-message").eq(0).remove();
				$('.js-field-list-sub').each(function(index, obj){
					$('#CustomPricePerType'+selector_index+index+'PartitionId').parents('div.js-partition-div').removeClass('hide error');
					$('#CustomPricePerType'+selector_index+index+'PartitionId').next("div.error-message").eq(0).remove();
					$('#CustomPricePerType'+selector_index+index+'MaxNumberOfQuantity').parents('div.js-qty-div').addClass('hide').removeClass("error");
				});
			} else {
				$('#CustomPricePerNightSellTicket'+selector_index+'HallId').parents('div.js-hall-div').addClass('hide').removeClass('error');
				$('#CustomPricePerNightSellTicket'+selector_index+'HallId').next("div.error-message").eq(0).remove();
				$('.js-field-list-sub').each(function(index, obj){
					$('#CustomPricePerType'+selector_index+index+'PartitionId').parents('div.js-partition-div').addClass('hide').removeClass('error');
					$('#CustomPricePerType'+selector_index+index+'PartitionId').next("div.error-message").eq(0).remove();
					$('#CustomPricePerType'+selector_index+index+'MaxNumberOfQuantity').parents('div.js-qty-div').removeClass('hide error');	
				});
			}		
        }).on('click', '.js-repeat-checkbox', function(e) {
			var checked_count = $(this).parents('div.js-repeat-days').find('input:checkbox:checked').length;
			if(checked_count > 0){
				$(this).parents('div.js-repeat-days').next('div.js-repeat-end').removeClass('hide');
				$(this).parents('div.js-repeat-days').next('div.js-repeat-end').find('div.error-message').remove();
			} else {
				$(this).parents('div.js-repeat-days').next('div.js-repeat-end').addClass('hide');
				$(this).parents('div.js-repeat-days').next('div.js-repeat-end').find('div.error-message').remove();
			}
		}).on('click', '.js-repeat-checkbox-flex', function(e) {
			var checked_count = $(this).parents('div.js-repeat-days-div').find('.js-repeat-checkbox-flex:checkbox:checked').length;
			if(checked_count > 0){
				$(this).parents('div.js-repeat-days-div').next('div.js-repeat-end-flex').removeClass('hide');
				$(this).parents('div.js-repeat-days-div').next('div.js-repeat-end-flex').find('div.error-message').remove();
			} else {
				$(this).parents('div.js-repeat-days-div').next('div.js-repeat-end-flex').addClass('hide');
				$(this).parents('div.js-repeat-days-div').next('div.js-repeat-end-flex').find('div.error-message').remove();
			}
		}).on('change', '.js-hall-select', function(e) {
			var hall_id = $(this).val();
			var selector_name = $(this).attr('name').split('[');
			var selector_index = selector_name[3].replace(']','');
			var url = __cfg('path_relative') + 'partitions/getpartitions/' + hall_id;
            $.get(url, function(data) {
				var resp = jQuery.parseJSON(data);
				 $('.js-partition-select').each(function(index, obj){
					var part_index = index;
					var part_id = 'CustomPricePerType' + selector_index + part_index + 'PartitionId';
					$('#' + part_id).empty();
					$('#' + part_id).append('<option value="">Please Select</option>');
					$.each(resp, function(key, value) {
						$('#' + part_id)
							.append('<option value='+key+'>'+value+'</option>')
							.find('option:first')
							.attr("selected","selected");
					}); 
				});
            });
                return false;
        }).on('change','select.js-partition-select', function(e) {
			$(this).parent('div.js-partition-div').removeClass('error');
			$(this).parent('div.js-partition-div').find('div.error-message').remove();
		}).on('click','.js-select-custom-price-per-type', function(e) {
			var target_id = $(this).attr('id');
			 $('.js-select-custom-price-per-type').each(function(index, obj){
				var id = $(this).attr('id');
				var selector_name = $(this).attr('name').split('[');
				var selector_index = selector_name[3].replace(']','');
				if(id == target_id){
					$('#'+target_id).attr('checked','checked');
					$('#ItemUserCustomPricePerType'+selector_index).attr('disabled',false);
				} else {
					$('#'+id).attr('checked',false);
					$('#ItemUserCustomPricePerType'+selector_index).attr('disabled','disabled');
				}
			 });
		}).on('change', '#js-additional-fee-to-buyer', function(e) {
            if ($(this).val() == '' || $(this).val() == 0) {
                $('.js-additional-fee-block').hide();
            } else {
                $('.js-additional-fee-block').show();
            }
        }).on('click', '.js-select', function(e) {
            $this = $(this);
            if (unchecked = $this.metadata().unchecked) {
                $('.' + unchecked).prop('checked', false);
            }
            if (checked = $this.metadata().checked) {
                $('.' + checked).prop('checked', 'checked');
            }
            return false;
        }).on('click', '.js-select-day-calendar', function (e) {
			var date = $(this).data('date');
			$('.js-bookit-calendar-block').slideUp('slow');
			$('.js-calendar-list-on-' + date).show();
			$('.js-bookit-calendar-list-block').slideDown('slow');
			return false;
		}).on('click', '.js-list-tab-view, .js-list-back-tab', function (e) {
            $('.js-bookit-list-block').html('<div class="loader" ></div>').show();
            var url = __cfg('path_relative') + 'items/get_itemtime/item_id:' + $(this).data('item_id');
            $.get(url, function(data) {
                $('.js-bookit-list-block').html(data);
                return false;
            });
            return false;
        }).on('click', '.js-cal-view-click', function(e) {
			$('.js-bookit-calendar-list-block').slideUp('slow');
			$('.js-bookit-calendar-block').slideDown('slow');
			$('.js-cal-list-on').hide();
			return false;
        }).on('click', '.js-filter-link', function(e) {
            $this = $(this);
            $('.js-response').block();
            $.get($this.attr('href'), function(data) {
                $('.js-response').html(data);
                $('.js-response').unblock();
                return false;
            });
            return false;
        }).on('click', '.js-select-custom-price-per-night', function(e) {
			var id_val = $(this).val();
			var custom_id = $(this).data('custom_id');
			if(custom_id != 0 && custom_id != undefined){
				id_val = custom_id;
			}
            $('.js-bookit-list-block').html('<div class="loader" ></div>');
            var url = __cfg('path_relative') + 'items/get_itemprices/custom_price_per_night_id:' + id_val;
            $.get(url, function(data) {
                $('.js-bookit-list-block').html(data);
				$('#myTab2 a:first').tab('show');
                return false;
            });
            return false;
        }).on('click', '#js-trigger-search', function(e) {
            $('.navbar.site-header').toggleClass('site-menu');
            $('#nav-search').toggleClass('search-open');
            $('#main').toggleClass('main-close');
            $('#footer').toggleClass('footer-close');
            $('.icon-search').toggleClass('icon-remove');
            return false;
        }).on('mouseenter mouseleave', '#js-getstarted', function(e) {
            $('#banner-trans-bg').toggleClass('anim-show');
        }).on('change', '.js-admin-index-autosubmit', function(e) {
            if ($('.js-checkbox-list:checked').val() != 1) {
                alert(__l('Please select atleast one record!'));
                $('.js-admin-index-autosubmit').val('');
                return false;
            } else {
                if ($(this).val() == 44) {
                    if (window.confirm(__l('Are you sure you want to do this action?'))) {
                        $(this).parents('form').attr('action', __cfg('path_relative') + 'admin/items/manage_collections');
                        $(this).parents('form').submit();
                    } else {
                        $('.js-admin-index-autosubmit').val('');
                    }
                } else {
                    if ((window.confirm(__l('Are you sure you want to do this action?')))) {
                        $(this).parents('form').submit();
                    } else {
                        $('.js-admin-index-autosubmit').val('');
                    }
                }
            }
        }).on('blur', '#ItemCityNameAddressSearch, #ItemCityNameSearch, #RequestCityName', function(e) {
			searchHandler($(this).attr('id'));
		}).on('keypress', '.js-geo-autocomplete', function(e) {
            $this = $(this);
            if (e.keyCode == 9 || e.keyCode == 13) {
                return true;
            }
            $this.addClass("geo-autocomplete-loader");
            setTimeout(function() {
                $this.removeClass("geo-autocomplete-loader");
            }, 1000);
        }).on('click', '.js-calendar-tab', function (e) {
			$('#myTab2 a:last').tab('show');
        }).on('click', '#myTab a, #myTab2 a', function (e) {
			e.preventDefault();
			var loadurl = $(this).attr('href');
			var targ = $(this).attr('data-target');
			if (targ) {
				$.get(loadurl, function(data) {
					$(targ).html(data);
				});
			}
			$(this).tab('show');
			return false;
		}).on('blur', '.js-post-item-description', function (e) {
			var url = __cfg('path_relative') + 'items/add_simple';
			var item_id = $('#ItemId').val();
			var session_key = $('#ItemSessionKey').val();
			if(item_id == '') {
				var param = {'title': $('#ItemTitle').val(), 'description': $('#ItemDescription').val(), 'category_id':$('#ItemSubCategoryId').val(), 'key': session_key};
				$.ajax( {
					type: 'POST',
					url: url,
					dataType: 'script',
					data: param,
					cache: false,
					success: function(responses) {
						$('#ItemId').val(responses);
						return false;
					}
				});
			}
			return false;
		}).on('sortupdate', '.js-sortable-attachments', function(e) {
			var data = $('input[name*="data[Attachment]"][name*="[id]"]').serialize();
			$.post(__cfg('path_relative') + 'items/sort_attachments', data, function(response) {});
		}).on('dragleave', '.js-attachment-files', function(e) {
			e.preventDefault();
			$(this).removeClass('drag-hover');
		}).on('dragover', '.js-attachment-files', function(e) {
			e.preventDefault();
			$(this).addClass('drag-hover');
		}).on('click', '.js-attachment-files', function(e) {
			e.preventDefault();
			$('#AttachmentFilename').click();
		}).on('click', 'a:not(.js-no-pjax, .close):not([href^=http], [href=#], #adcopy-link-refresh, #adcopy-link-audio, #adcopy-link-image, #adcopy-link-info)', function(e) {
            if ( ! $.support.pjax) {
                return;
            }
			$('.navbar.site-header').removeClass('site-menu');
            $('#nav-search').removeClass('search-open');
            $('#main').removeClass('main-close');
            $('#footer').removeClass('footer-close');
            $('.icon-search').removeClass('icon-remove');
			$('.js-bootstrap-tooltip').tooltip('hide');
            $.pjax.click(e, {
                container: '#pjax-body',
                fragment: '#pjax-body'
            });
            var link = $(this).prop('href');
            var current_url = window.current_url;
            if (link.indexOf('admin') < 0 && current_url.indexOf('admin') > 0) {
                window.location.href = link;
            }
            if (link.indexOf('admin') >= 0) {
                $('.admin-menu li').removeClass('active');
                $(this).parents('li').addClass('active');
            }
        }).on('pjax:start', 'body', function(e) {
            if ( ! $.support.pjax) {
                return;
            }
            if ($('#progress').length === 0) {
                $('body').append($('<div><dt/><dd/></div>').attr('id', 'progress'));
                $('#progress').width((50 + Math.random() * 30) + '%');
            }
            $(this).addClass('loading');
        }).on('pjax:timeout', 'body', function(e) {
            if ( ! $.support.pjax) {
                return;
            }
            e.preventDefault();
        }).on('pjax:end', 'body', function() {
            $(this).removeClass('loading');
            $('#progress').width('101%').delay(200).fadeOut(400, function() {
                $(this).remove();
            });
            if (document.location.pathname == __cfg('path_relative')) {
                $('#header .js-header-menu').addClass('site-header site-menu z-top');
            } else {
                $('#header .js-header-menu').removeClass('site-header site-menu z-top');
            }
            if (window.location.href.indexOf('/admin/') > -1) {
                $('.js-live-tour-link').hide();
            } else {
                $('.js-live-tour-link').show();
            }
			xloadGeoAutocomplete();
			initializeSearch();
            loadAdminPanel();
        }).on('change', '.js-price-type-input', function(e) {
		   if ($(this).val() == '' || $(this).val() == 0) {
			    $(this).parent('.input').next().removeClass('hide');
                //$('.pricin_details').removeClass('hide');
            } else {
				$(this).parent('.input').next().addClass('hide');
                //$('.pricin_details').addClass('hide');
            }
		});
         if ($.cookie('_geo') === undefined || $.cookie('_geo') === null) {
        $.ajax( {
            type: 'GET',
            url: '//freegeoip.net/json/',
            dataType: 'json',
            cache: true,
            success: function(data) {
				if(data.region === undefined || data.region === null){
					data.region = '';
				}
				if(data.city === undefined || data.city === null){
					data.city = '';
				}
				if(data.country_code === undefined || data.country_code === null){
					data.country_code = '';
				}
                var geo = data.country_code + '|' + data.region + '|' + data.city +  '|' + data.latitude + '|' + data.longitude;
                $.cookie('_geo', geo, {
                    expires: 100,
                    path: '/'
                });
            }
        });
    }

    }).ajaxStop(function() {
        $.fn.fdatetimepicker('.js-datetimepicker');
		$.fn.fdatepicker('.js-datepicker');
        xload(true);
		file_upload();
    });
})
();