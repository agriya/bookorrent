function CalanderLoad(id) {
    var view = 'month';
    var DATA_FEED_URL = __cfg('path_relative') + 'items/datafeed/item_id:' + id;
    var startday = __cfg('week_start_day');
    var op = {
        view: view,
        theme: 3,
        showday: new Date(),
        EditCmdhandler: Edit,
        DeleteCmdhandler: Delete,
        ViewCmdhandler: View,
        weekstartday: startday,
        onWeekOrMonthToDay: wtd,
        onBeforeRequestData: cal_beforerequest,
        onAfterRequestData: cal_afterrequest,
        onRequestDataError: cal_onerror,
        autoload: true,
        enableDrag: false,
        url: DATA_FEED_URL + '.json?method=list',
        quickAddUrl: DATA_FEED_URL + '.json?method=add',
        quickUpdateUrl: DATA_FEED_URL + '.json?method=update',
        quickDeleteUrl: DATA_FEED_URL + '.json?method=remove'
    };
    var $dv = $('#calhead');
    var _MH = document.documentElement.scrollHeight;
    var dvH = $dv.height() + 2;
    op.height = _MH - dvH;
    op.eventItems = [];
    var p = $('#gridcontainer').bcalendar(op).BcalGetOp();
    if (p && p.datestrshow) {
        $('#txtdatetimeshow').text(p.datestrshow);
    }
    $('#caltoolbar').noSelect();
    $('#hdtxtshow').datepicker( {
        picker: '#txtdatetimeshow',
        showtarget: $('#txtdatetimeshow'),
        onReturn: function(r) {
            var p = $('#gridcontainer').gotoDate(r).BcalGetOp();
            if (p && p.datestrshow) {
                $('#txtdatetimeshow').text(p.datestrshow);
            }
        }
    });
    function cal_beforerequest(type) {
        var t = __l('Loading data')+'...';
        switch(type) {
            case 1: 
				t = __l('Loading data')+'...';				
            break;
            case 2: case 3: case 4: t = __l('The request is being processed')+' ...';
            break;
        }
        $('#errorpannel').hide();
        $('#loadingpannel').html(t).show();
    }
    function cal_afterrequest(type) {
        switch(type) {
            case 1: $('#loadingpannel').hide();
            break;
            case 2: case 3: case 4: $('#loadingpannel').html('Success!');
            window.setTimeout(function() {
                $('#loadingpannel').hide();
            }, 2000);
            break;
        }
    }
    function cal_onerror(type, data) {
        $('#errorpannel').show();
    }
    function Edit(data) {
        var eurl = __cfg('path_absolute') + 'items/calendar_edit?id={0}&amp;start={2}&amp;end={3}&amp;isallday={4}&amp;title={1}&amp;model={12}&amp;item_id={10}&amp;current_status={9}&amp;price={13}';
        if (data) {
            var url = StrFormat(eurl, data);
            OpenModelWindow(url, {
                width: 600,
                height: 400,
                caption: 'Manage The Calendar',
                onClose: function() {
                    $('#gridcontainer').reload();
                }
            });
        }
    }
    function View(data) {
        var str = '';
        $.each(data, function(i, item) {
            str += '[' + i + ']: ' + item + '\n';
        });
    }
    function Delete(data, callback) {
        $.alerts.okButton = 'Ok';
        $.alerts.cancelButton = 'Cancel';
        hiConfirm('Are You Sure to Delete this Event', 'Confirm', function(r) {
            r && callback(0);
        });
    }
    function wtd(p) {
        if (p && p.datestrshow) {
            $('#txtdatetimeshow').text(p.datestrshow);
        }
        $('#caltoolbar div.fcurrent').each(function() {
            $(this).removeClass('fcurrent');
        })
            $('#showdaybtn').addClass('fcurrent');
    }
    //to show day view
    $('#showdaybtn').click(function(e) {
        $('#caltoolbar div.fcurrent').each(function() {
            $(this).removeClass('fcurrent');
        })
            $(this).addClass('fcurrent');
        var p = $('#gridcontainer').swtichView('day').BcalGetOp();
        if (p && p.datestrshow) {
            $('#txtdatetimeshow').text(p.datestrshow);
        }
        $('#gridcontainer').css('width', '100%');
        $('#gridcontainer1').css('width', '0%');
        $('#gridcontainer1').hide();
    });
    //to show week view
    $('#showweekbtn').click(function(e) {
        $('#caltoolbar div.fcurrent').each(function() {
            $(this).removeClass('fcurrent');
        })
            $(this).addClass('fcurrent');
        var p = $('#gridcontainer').swtichView('week').BcalGetOp();
        if (p && p.datestrshow) {
            $('#txtdatetimeshow').text(p.datestrshow);
        }
        $('#gridcontainer').css('width', '100%');
        $('#gridcontainer1').css('width', '0%');
        $('#gridcontainer1').hide();
    });
    //to show month view
    $('#showmonthbtn').click(function(e) {
        $('#caltoolbar div.fcurrent').each(function() {
            $(this).removeClass('fcurrent');
        })
            $(this).addClass('fcurrent');
        var p = $('#gridcontainer').swtichView('month').BcalGetOp();
        if (p && p.datestrshow) {
            $('#txtdatetimeshow').text(p.datestrshow);
        }
        $('#gridcontainer').css('width', '87%');
        $('#gridcontainer1').css('width', '13%');
        $('#gridcontainer1').show();
    });
    $('#showreflashbtn').click(function(e) {
        $('#gridcontainer').reload();
    });
    //Add a new event
    $('#faddbtn').click(function(e) {
        var url = 'edit.php';
        OpenModelWindow(url, {
            width: 500,
            height: 400,
            caption: 'Create New Calendar'
        });
    });
    //go to today
    $('#showtodaybtn').click(function(e) {
        var p = $('#gridcontainer').gotoDate().BcalGetOp();
        if (p && p.datestrshow) {
            $('#txtdatetimeshow').text(p.datestrshow);
        }
    });
    //previous date range
    $('#sfprevbtn').click(function(e) {
        var p = $('#gridcontainer').previousRange().BcalGetOp();
        if (p && p.datestrshow) {
            $('#txtdatetimeshow').text(p.datestrshow);
        }
    });
    //next date range
    $('#sfnextbtn').click(function(e) {
        var p = $('#gridcontainer').nextRange().BcalGetOp();
        if (p && p.datestrshow) {
            $('#txtdatetimeshow').text(p.datestrshow);
        }
    });
}
function loadStreetMap() {
    var lat = $('.js-street-view').metadata().lat;
    var lang = $('.js-street-view').metadata().lng;
    var fenway = new google.maps.LatLng(lat, lang);
    var panoramaOptions = {
        position: fenway,
        pov: {
            heading: 34,
            pitch: 10,
            zoom: 1
        }
    };
    var panorama = new google.maps.StreetViewPanorama(document.getElementById('js-street-view'), panoramaOptions);
}
function selectLink() {
    $('div.select-block').on('click', '.js-select-all', function() {
        $('.js-checkbox-list').attr('checked', 'checked');
        return false;
    });
    $('div.select-block').on('click', '.js-select-none', function() {
        $('.js-checkbox-list').attr('checked', false);
        return false;
    });
    $('form#MessageMoveToForm').on('click', '.js-select-read', function() {
        $('.checkbox-message').attr('checked', false);
        $('.checkbox-read').attr('checked', 'checked');
        return false;
    });
    $('form#MessageMoveToForm').on('click', '.js-select-unread', function() {
        $('.checkbox-message').attr('checked', false);
        $('.checkbox-unread').attr('checked', 'checked');
        return false;
    });
    $('form#MessageMoveToForm').on('click', '.js-select-starred', function() {
        $('.checkbox-message').attr('checked', false);
        $('.checkbox-starred').attr('checked', 'checked');
        return false;
    });
    $('form#MessageMoveToForm').on('click', '.js-select-unstarred', function() {
        $('.checkbox-message').attr('checked', false);
        $('.checkbox-unstarred').attr('checked', 'checked');
        return false;
    });
}
var markerClusterer = null;
var markers = [];
var styles = [];
function refreshMap() {
    if (markerClusterer) {
        markerClusterer.clearMarkers();
    }
    $.getJSON(__cfg('json_data_url'), function(data) {
        if (data) {
            for (var i = 0; i < data['Items']['Count']; i ++ ) {
                updateClusterMarker(data['Items'][i]['Item'].latitude, data['Items'][i]['Item'].longitude, data['Items'][i]['Item'].id, i, 'Item');
            }
            for (var i = 0; i < data['Requests']['Count']; i ++ ) {
                updateClusterMarker(data['Requests'][i]['Request'].latitude, data['Requests'][i]['Request'].longitude, data['Requests'][i]['Request'].id, i, 'Request');
            }
            var zoom = null;
            var size = null;
            var style = null;
            markerClusterer = new MarkerClusterer(map, markers, {
                maxZoom: zoom,
                gridSize: size,
                styles: styles[style]
                });
        }
    });
}
function updateClusterMarker(lat, lang, id, count, type) {
    var imageUrl = __cfg('path_relative') + 'img/R.png';
    if (type == 'Item') {
        var imageUrl = __cfg('path_relative') + 'img/P.png';
    }
    var markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(32, 32));
    var latLng = new google.maps.LatLng(lat, lang);
    eval('var marker' + count + ' = new google.maps.Marker({position: latLng,draggable: false,icon: markerImage});');
    eval('marker' + count + '.count=1');
    markers.push(eval('marker' + count));
    var embed_url = __cfg('path_relative') + 'requests/get_info/' + id;
    if (type == 'Item') {
        var embed_url = __cfg('path_relative') + 'items/get_info/' + id;
    }
    var contentString = '<iframe src="' + embed_url + '" width="279" height="120" frameborder = "0" scrolling="no">Loading...</iframe>';
    eval('var infowindow' + count + ' = new google.maps.InfoWindow({ content: contentString,  maxWidth: 300});');
    var infowindow_obj = eval('infowindow' + count);
    var marker_obj = eval('marker' + count);
    google.maps.event.addListener(marker_obj, 'click', function() {
        infowindow_obj.open(map, marker_obj);
    });
}
function xload(is_after_ajax) {
	$('#bg-stretch-autoresize img#bg-image' + so).addClass('xltriggered').fullBg();
	if(!$.browser.mobile) {
		$('#bg-stretch-autoresize img#bg-image' + so + ', #bg-stretch img#bg-image' + so).each(function() {
			var $this = $(this);
			var highResImage = new Image();
			var highResImageUrl = $this.metadata().highResImage;
			highResImage.onload = function() {
				$this.prop('src', highResImageUrl);
				$this.fullBg();
			}
			highResImage.src = highResImageUrl;
		}).addClass('xltriggered');
	}
    selectLink();
    $('.blink').cyclicFade();
    var so = (is_after_ajax) ? ':not(.xltriggered)': '';
    $('#myCarousel').carousel();
	// thumb images
	var total_list = $('.thumb-box ol li').length;
	$('.thumb-box ol').width(total_list * ($('.thumb-box ol li:eq(0)').width() + 100));
	var thumb_width = $(window).width() - 86;
	$('.js-right-carousel').click(function() {
		if ($('.thumb-box ol li:last').offset().left < thumb_width) {
			return;
		}
		$('.thumb-box ol').stop().animate( {
			right: '+=100%'
		}, 1000);
		return false;
	});
	$('.js-left-carousel').click(function() {
		if ($('.thumb-box ol li:first').offset().left > 0) {
			return;
		}
		$('.thumb-box ol').stop().animate( {
			right: (parseInt($('.thumb-box ol').get(0).style.right) >= 100) ? '-=100%' : '0%'
		}, 1000);
		return false;
	});
    $('#myCarousel').bind('slid', function() {
		current_id = $('#myCarousel .item.active').index();
		per_page = parseInt(Math.round($('.thumb-box').width() / ($('.thumb-box ol li').width() + 3)));
		if (current_id == 1) {
			$('.thumb-box ol').stop().animate( {
				right: '0%'
			}, 1000);
		} else if (current_id > per_page) {
			$('.thumb-box ol').stop().animate( {
				right: ((parseInt(current_id / per_page)) * 100) + '%'
			}, 1000);
		}
        $('.thumb-box ol li:eq(' + (current_id - 1) + ')').addClass('active');
    });
	// small to big image
	if(!$.browser.mobile) {
		$('#myCarousel .bg-image').each(function() {
			var $this = $(this);
			var highResImage = new Image();
			var highResImageUrl = $this.data('high_res_image');
			highResImage.onload = function() {
				$this.css('background-image', 'url(' + highResImageUrl + ')');
			}
			highResImage.src = highResImageUrl;
		});
	}
    $('.js-street-link' + so).each(function() {
        var script = document.createElement('script');
        var google_map_key = '//maps.google.com/maps/api/js?sensor=false&callback=loadStreetMap';
        script.setAttribute('src', google_map_key);
        script.setAttribute('type', 'text/javascript');
        document.documentElement.firstChild.appendChild(script);
    }).addClass('xltriggered');
    $('.social_marketings-import_friends' + so).each(function(e) {
        $.getScript('http://connect.facebook.net/en_US/all.js', function(data) {
            FB.init( {
                appId: $('#facebook').data('fb_app_id'),
                status: true,
                cookie: true
            });
            FB.getLoginStatus(function(response) {
                $('#facebook').removeClass('loader');
                if (response.status == 'connected') {
                    $('#js-fb-invite-friends-btn').remove();
                    $('#js-fb-login-check').show();
                } else {
                    $('#js-fb-login-check').remove();
                    $('#js-fb-invite-friends-btn').show();
                }
            });
        });
    }).addClass('xltriggered');
    $('.social_marketings-publish' + so).each(function(e) {
        var loader = $('#js-loader');
        var fb_connect = loader.data('fb_connect');
        var fb_app_id = loader.data('fb_app_id');
        var item_url = loader.data('item_url');
        var item_image = loader.data('item_image');
        var item_name = $('#js-FB-Share-title').text();
        var item_caption = $('#js-FB-Share-caption').text();
        var item_description = $('#js-FB-Share-description').text();
        var redirect_url = loader.data('redirect_url');
        var sitename = __cfg('site_name');
        var type = loader.data('type');
        $.getScript('http://connect.facebook.net/en_US/all.js', function(data) {
            FB.init( {
                appId: fb_app_id,
                status: true,
                cookie: true
            });
            FB.getLoginStatus(function(response) {
                var publish = {
                    method: 'feed',
                    display: type,
                    access_token: FB.getAccessToken(),
                    redirect_uri: redirect_url,
                    link: item_url,
                    picture: item_image,
                    name: item_name,
                    caption: item_caption,
                    description: item_description
                };
                loader.removeClass('loader');
                setTimeout(function() {
                    $('.js-skip-show').slideDown('slow');
                }, 1000);
                if (response.status === 'connected') {
                    if (fb_connect == '1') {
                        FB.ui(publish, publishCallBack);
                        $('div#js-FB-Share-iframe').removeClass('hide');
                    } else {
                        $('div#js-FB-Share-beforelogin').removeClass('hide');
                    }
                } else {
                    $('div#js-FB-Share-beforelogin').removeClass('hide');
                }
            });
        });
    }).addClass('xltriggered');
    $('div.js-calander-load' + so).each(function() {
        id = $('.js-calander-load').metadata().id;
        CalanderLoad(id);
    }).addClass('xltriggered');
    $('#js-inlineDatepicker' + so).each(function() {
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
        $this.datepick( {
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
                    $('.js-date-picker-info').removeClass('default');
                    $('.js-date-picker-info').addClass('started');
                    $('.js-date-picker-info').addClass('blink');
                    $('.js-date-picker-info').css('color', '');
                    $('.js-date-picker-info').html('<i class="icon-question-sign"></i>' + __l('Select to date in calendar'));
                    $('.blink').cyclicFade();
                } else if ($('.js-date-picker-info').hasClass('started')) {
                    $('.js-date-picker-info').removeClass('started');
                    $('.js-date-picker-info').addClass('selected');
                    var no_of_days = days_between(dates[0], dates[1]);
                    if (__cfg('days_calculation_mode') == 'Day') {
                        no_of_days ++ ;
                    }
                    var day_caption = 'days';
                    if (no_of_days == 1) {
                        day_caption = 'day';
                    }
                    if (__cfg('days_calculation_mode') == 'Night' && no_of_days == 0) {
                        $('.js-date-picker-info').addClass('started');
                        $('.js-date-picker-info').addClass('blink');
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
                    $('.js-date-picker-info').removeClass('default');
                    $('.js-date-picker-info').addClass('started');
                    $('.js-date-picker-info').addClass('blink');
                    $('.blink').cyclicFade();
                    $('.js-date-picker-info').css('color', '');
                    $('.js-date-picker-info').html(__l('Select check-out date in calendar'));
                } else {
                    $('.js-date-picker-info').addClass('default');
                    $('.js-date-picker-info').removeClass('blink');
                    $('.js-date-picker-info').css('color', '');
                    $('.js-date-picker-info').html('<i class=" icon-question-sign "></i>' + __l('Select from date in calendar'));
                }
            }
        });
        dates = Array();
        for (var i = 0; i < 2; i ++ ) {
            dates[i] = $('#js-inlineDatepicker-calender').find("select[id$='Month']").eq(i).val() + '/' + $('#js-inlineDatepicker-calender').find("select[id$='Day']").eq(i).val() + '/' + $('#js-inlineDatepicker-calender').find("select[id$='Year']").eq(i).val();
        }
        $this.datepick('setDate', dates);
    }).addClass('xltriggered');
} (function() {
    var $dc = $(document);
    $dc.ready(function($) {
        xload(false);
        $dc.on('click', 'a.js-bookitaffix', function(e) {
            $this = $(this);
            tmp = $this.data('trigger');
            $(window).scrollTop($(tmp).offset().top);
            if ( ! $(tmp).parent('div').hasClass('open'))
                $(tmp).trigger('click');
            return false;
        }).on('click', '.js-expand', function(e) {
			$('[data-spy="affix"]').toggleClass('affix');
			$('#main').toggleClass('expand');
			$('.expand-circle, .controls').toggleClass('hide');
			$('.js-bookitaffix').toggleClass('js-expand');
			if ($('.controls').hasClass('hide')) {
				$('.bg-image').css('background-size', 'cover');
			}
			if ($('.controls').hasClass('hide')) {
				$('.item').toggleClass('item-full-screen');
			} else {
				full_screen = setTimeout(function() {
					$('.item').toggleClass('item-full-screen');
					clearTimeout(full_screen);
				}, 1000);
			}
        }).on('click', '.js-cover', function(e) {
			if ($('#main').hasClass('expand')) {
				if ($('.bg-image').css('background-size') == 'cover') {
					$('.bg-image').css('background-size', 'contain');
					$('.js-icon-class').addClass('icon-resize-full').removeClass('icon-resize-small');
				} else {
					$('.bg-image').css('background-size', 'cover');
					$('.js-icon-class').removeClass('icon-resize-full').addClass('icon-resize-small');
				}
			}
            return false;
        }).on('click', 'a#bookit, a#hostpanel, a#checkavailability, a.js-ratedetails', function(e) {
            $this = $(this);
            var tmp = $this.data('trigger');
            var is_show_calendar = $this.data('calendar');
            if ($(this).parent('div').hasClass('open')) {
                $(tmp).html("<div class='row dc hor-space'><img src='" + __cfg('path_absolute') + "/img/throbber.gif' class='js-loader'/><p class=''>  Loading....</p></div>");
                $.get($this.prop('href'), function(data) {
                    $(tmp).html(data);
                });
            }
            return false;
        }).on('click', '.js-notification', function(e) {
            $this = $(this);
            $.get($this.prop('href'), function(data) {
                $('.js-notification-list').html(data);
            });
        }).on('click', '.js-share-close', function(e) {
            $(this).parent().slideToggle('slow');
            return false;
        }).on('click', '.js-pagination a', function(e) {
            $this = $(this);
            var scroll_to = $this.parents().closest('.js-pagination').metadata().scroll;
            if (typeof(scroll_to) == 'undefined') {
                scroll_to = 'main';
            }
            $.scrollTo('#' + scroll_to, 1500);
            $this.parents('div.js-response').block();
            $.get($this.attr('href'), function(data) {
                $this.parents('div.js-response').html(data);
                if ($('.items-index-page', 'body').is('.items-index-page')) {
					initializeSearch();
                    $('form#KeywordsSearchForm').on('click', '.js-mapsearch-button', function() {
                        searchmapaction();
                    });
                }
                if ($('.request-index-page', 'body').is('.request-index-page')) {
					initializeSearch();
                }
                selectLink();
                $this.parents('div.js-response').unblock();
                return false;
            });
            return false;
        }).on('click', '.js-captcha-reload', function(e) {
            captcha_img_src = $(this).parents('.js-captcha-container').find('.captcha-img').attr('src');
            captcha_img_src = captcha_img_src.substring(0, captcha_img_src.lastIndexOf('/'));
            $(this).parents('.js-captcha-container').find('.captcha-img').attr('src', captcha_img_src + '/' + Math.random());
            return false;
        }).on('click', '.js-transaction-filter', function(e) {
            var val = $(this).val();
            if (val == __l('custom')) {
                $('.js-filter-window').show();
                return true;
            } else {
                $('.js-filter-window').hide();
            }
            $('.js-responses').block();
            $.ajax( {
                type: 'GET',
                url: __cfg('path_relative') + 'transactions/index/stat:' + val,
                dataType: 'html',
                cache: true,
                success: function(responses) {
                    $('.js-responses').html(responses);
                    $('.js-responses').unblock();
                }
            });
        }).on('change', '.js-contact-purpose', function(e) {
            var val = $(this).val();
            var negotiable = $(this).metadata().negotiable;
            if (val == 4) {
                $('.js-contactus-container').slideDown();
                $('.js-response').html('');
            } else if (val == 1) {
                $('.js-contactus-container').slideUp();
                $('.js-response').html('<span class="page-information">' + __l('You can check "Availablity" in item page') + '</span>');
            } else if (val == 2) {
                $('.js-contactus-container').slideUp();
                $('.js-response').html('<span class="page-information">' + __l('You can check "Facilities" in item page') + '</span>');
            } else if (val == 3) {
                $('.js-contactus-container').slideUp();
                if (negotiable == 1) {
                    var html = '<span class="page-information">' + __l('You can check "Pricing" details in item page, also you can do price discussion.') + '</span>';
                } else {
                    var html = '<span class="page-information">' + __l('You can check "Pricing" details in item page, also price is fixed. Negotiation is not possible.') + '</span>';
                }
                $('.js-response').html(html);
            }
        }).on('submit', 'form.js-comment-form', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    if (responseText.indexOf($this.metadata().container) != '-1') {
                        $('.' + $this.metadata().container).html(responseText);
                    } else {
                        $('.js-comment-responses').prepend(responseText);
                        $('.' + $this.metadata().container + ' div.input').removeClass('error');
                        $('.error-message', $('.' + $this.metadata().container)).remove();
                        $('.no-message', $('.js-comment-responses')).remove();
                    }
                    if (typeof($('.js-captcha-container').find('.captcha-img').attr('src')) != 'undefined') {
                        captcha_img_src = $('.js-captcha-container').find('.captcha-img').attr('src');
                        captcha_img_src = captcha_img_src.substring(0, captcha_img_src.lastIndexOf('/'));
                        $('.js-captcha-container').find('.captcha-img').attr('src', captcha_img_src + '/' + Math.random());
                    }
                    $this.unblock();
                },
                clearForm: true
            });
            return false;
        }).on('submit', 'form.js-ajax-search-form', function(e) {
            var $this = $(this);
            $('.js-responses').block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    redirect = responseText.split('*');
                    if (redirect[0] == 'redirect') {
                        location.href = redirect[1];
                    } else if ($this.metadata().container) {
                        $('.' + $this.metadata().container).html(responseText);
                    } else {
                        $('.js-responses').html(responseText);
                    }
                    $('.js-responses').unblock();
					initializeSearch();
                    $('form#KeywordsSearchForm').on('click', '.js-mapsearch-button', function() {
                        searchmapaction();
                    });
                    $('form#KeywordsSearchForm').on('click', '.js-submit-button', function() {
                        $(this).parents('form').submit();
                        return false;
                    });
                    $('form#KeywordsSearchForm').on('click', '.js-toggle-items-types', function() {
                        $('.' + $(this).metadata().typetoggle).toggle();
                        if ($(this).is('.minus')) {
                            $(this).addClass('plus');
                            $(this).removeClass('minus');
                        } else {
                            $(this).addClass('minus');
                            $(this).removeClass('plus');
                        }
                        return false;
                    });
                }
            });
            return false;
        }).on('click', 'a.js-like', function(e) {
            var $this = $(this);
            $(this).html('<img src="' + __cfg('path_absolute') + '/img/star-load.gif" style="margin:-4px 0 0 -2px">');
            $.get($this.prop('href'), null, function(data) {
                $this.parent().html(data);
            });
            return false;
        }).on('click', '.js-follow', function(e) {
            $this = $(this);
            var user_id = $this.metadata().user_id;
            $.get(__cfg('path_relative') + 'user_followers/add/' + user_id, function(data) {
                split_data = data.split('|');
                $('#js-follow-id').removeClass();
                $('#js-follow-id').removeAttr('data-toggle');
                $('#js-follow-id').addClass('btn span3 js-add-remove-followers js-bootstrap-tooltip js-unfollow');
                $('#js-follow-id').prop( {
                    alt: 'Following',
                    title: 'Unfollow',
                    href: split_data[1]
                    });
                $('.js-social-link-div').load(split_data[2]);
            });
        }).on('click', 'a.js-add-friend', function(e) {
            $this = $(this);
            $parent = $this.parent();
            $parent.block();
            $.get($this.attr('href'), function(data) {
                $parent.append(data);
                $this.hide();
                $parent.unblock();
            });
            return false;
        }).on('click', '.js-connect', function(e) {
            $.oauthpopup( {
                path: $(this).metadata().url,
                callback: function() {
                    var href = window.location.href;
                    if (href.indexOf('users/register') != -1) {
                        location.href = __cfg('path_absolute') + 'users/login';
                    } else {
                        window.location.reload();
                    }
                }
            });
            return false;
        }).on('click', '.js-request_invite', function(e) {
            $('div.js-responses').eq(0).block();
            $.get(__cfg('path_absolute') + 'subscriptions/add/type:invite_request', function(data) {
                $('div.js-responses').html(data);
                $('div.js-responses').unblock();
            });
            return false;
        }).on('keyup', 'input.js-negotiate-discount', function(e) {
            val = parseFloat($(this).val());
            if (val > 0) {
                $('span.js-gross-host-amount').html((($('span.js-gross-host-amount').metadata().price - ($('span.js-gross-host-amount').metadata().price * (val / 100))) - $('span.js-gross-host-amount').metadata().service_amount).toFixed(2));
            } else {
                $('span.js-gross-host-amount').html(($('span.js-gross-host-amount').metadata().price - $('span.js-gross-host-amount').metadata().service_amount).toFixed(2));
            }
        }).on('click', '.js-calender-prev, .js-calender-next', function(e) {
            var $this = $(this);
            var url = $this.metadata().url;
            $('.js-calendar-response').block();
            $.get(url, function(data) {
                $('.js-calendar-response').html(data);
                if (data.indexOf('js-disable_monthly') != -1) {
                    if ($('#ItemUserBookingOptionPricePerMonth').is(':checked')) {
                        $('#ItemUserBookingOptionPricePerNight').attr('checked', 'checked');
                    }
                    $('#ItemUserBookingOptionPricePerMonth').attr('disabled', 'disabled');
                } else {
                    $('#ItemUserBookingOptionPricePerMonth').removeAttr('disabled');
                }
                if ($('#ItemUserBookingOptionPricePerMonth').is(':checked')) {
                    var monthstart = $('.js-monthstart-date', data).text().split('-');
                    var monthend = $('.js-monthend-date', data).text().split('-');
                    $('#ItemUserCheckinDay').val(monthstart[2]);
                    $('#ItemUserCheckinMonth').val(monthstart[1]);
                    $('#ItemUserCheckinYear').val(monthstart[0]);
                    $('#ItemUserCheckoutDay').val(monthend[2]);
                    $('#ItemUserCheckoutMonth').val(monthend[1]);
                    $('#ItemUserCheckoutYear').val(monthend[0]);
                }
                $('.js-calendar-response').unblock();
                return false;
            });
            return false;
        });
    }).ajaxStop(function() {
        xload(true);
    });
})
();