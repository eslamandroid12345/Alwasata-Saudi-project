
function VoucherSourcetoPrint(source) {
    return "<html><head><script>function step1(){\n" +
            "setTimeout('step2()', 10);}\n" +
            "function step2(){window.print();window.close()}\n" +
            "</scri" + "pt></head><body onload='step1()'>\n" +
            "<img src='" + source + "' /></body></html>";
}
function VoucherPrint(source) {
    Pagelink = "about:blank";
    var pwa = window.open(Pagelink, "_new");
    pwa.document.open();
    pwa.document.write(VoucherSourcetoPrint(source));
    pwa.document.close();
}

function formatErrorMessage(jqXHR, exception) {
    if (jqXHR.status === 0) {
        return '<p>غير متصل. الرجاء التحقق من اتصال الشبكة.</p>';
    } else if (jqXHR.status == 400) {
        return '<p>يفهم الخادم الطلب ، لكن طلب المحتوى غير صالح.</p>';
    } else if (jqXHR.status == 400) {
        return '<p>لا يمكن الوصول إلى الموارد المحرمة.</p>';
    } else if (jqXHR.status == 404) {
        return '<p>الصفحة المطلوبة غير موجودة. [404]</p>';
    } else if (jqXHR.status == 500) {
        return '<p>انتهت صلاحية الجلسة ، يرجى إعادة تحميل الصفحة والمحاولة مرة أخرى.</p>';
    } else if (jqXHR.status == 503) {
        return '<p>الخدمة غير متوفرة.</p>';
    } else if (exception === 'parsererror') {
        return '<p>خطأ. فشل تحليل طلب JSON.</p>';
    } else if (exception === 'timeout') {
        return '<p>طلب مهلة.</p>';
    } else if (exception === 'abort') {
        return '<p>تم إحباط الطلب من قبل الخادم.</p>';
    } else {
        var message = '';
        try {
            var r = jQuery.parseJSON(jqXHR.responseText);
            if (jQuery.isEmptyObject(r) == false) {
                $.each(r, function (key, value) {
                    if (jQuery.isEmptyObject(r) == false) {
                        $.each(value, function (key, row) {
                            message += '<p>' + row + '</p>';
                        });
                    } else {
                        message += '<p>' + value + '</p>';
                    }
                });
            }
        } catch (e) {
            try {
                var r = JSON.parse(jqXHR.responseText);
                return formatErrorMessageFromJSON(r.errors)
            } catch (e) {
                message = 'خطأ غير مسجلين.\n' + jqXHR.responseText;
            }
        }
        return message;
    }
}

function formatErrorMessageFromJSON(jsonData) {
    var message = '';
    try {
        var r = jQuery.parseJSON(JSON.stringify(jsonData));

        if (jQuery.isEmptyObject(r) == false) {
            $.each(r, function (key, value) {
                if (jQuery.isEmptyObject(r) == false) {
                    $.each(value, function (key, row) {
                        message += '<p>' + row + '</p>';
                    });
                } else {
                    message += '<p>' + value + '</p>';
                }
            });
        }
    } catch (e) {
        message = 'خطأ غير مسجلين.\n' + jsonData;
    }
    return message;
}

function showMapResult(result, latitude_key, longitude_key) {
    var latitude = result.geometry.location.lat();
    var longitude = result.geometry.location.lng();
    $('[name=' + latitude_key + ']').val(latitude);
    $('[name=' + longitude_key + ']').val(longitude);
}

function getLatitudeLongitude(callback, address, latitude_key, longitude_key) {
    // If adress is not supplied, use default value 'Noida, Uttar Pradesh, India'
    address = address || 'Noida, Uttar Pradesh, India';
    // Initialize the Geocoder
    geocoder = new google.maps.Geocoder();
    if (geocoder) {
        geocoder.geocode({
            'address': address
        }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                callback(results[0], latitude_key, longitude_key);
            }
        });
    }
}

function active_current_url() {
    var removeLastElement = false;
    var current_url = window.location.href;
    var pathname = current_url.split('/');
    if ($(pathname).last()[0]) {
        if ($.isNumeric($(pathname).last()[0])) {
            removeLastElement = true;
        }
    }
    var segmentArr = [];
    if (pathname.length) {
        $.each(pathname, function (index, value) {
            if (!$.isNumeric(value)) {
                segmentArr.push(value);
            }
        })
    }
    if (removeLastElement) {
        segmentArr.splice(-1, 1);
    }
    current_url = segmentArr.join('/');
    var findAllLink = $('ul.sidebar-menu').find('a');
    if (findAllLink.length) {
        $.each(findAllLink, function (index, value) {
            var href = $(this).attr('href');
            if (current_url == href) {
                $(this).parent('li').addClass('active');
            }
        });
        var $this = $('ul.sidebar-menu').find('li.active');
        $this.parentsUntil(".sidebar-menu").addClass('open active');
    }
}
active_current_url();


if ($('.positive-integer').length > 0) {
    $(".positive-integer").numeric({
        decimal: false,
        negative: false
    });
    disableCopyPaste(".positive-integer");
}

if ($('.positive-decimal').length > 0) {
    $(".positive-decimal").numeric({
        decimal: ".",
        negative: false
    });
    disableCopyPaste(".positive-decimal");
}

if($('.disable-ccp').length > 0) {
    disableCopyPaste($('.disable-ccp'));
}

function disableCopyPaste(el) {
    $(el).bind("cut copy paste", function (e) {
        e.preventDefault();
    });
}

function setMinMaxValidation(el, min, max) {
    $(el).on("keydown keyup change", function(){
        var value = $(this).val();
        if (value < min) {
            $(el).val(min);
        }
        else if (value > max) {
            $(el).val(max);
        }
    });
}


$(document).ready(function () {
    if ($('.date-picker').length > 0) {
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
    }

    if ($('.dob-picker').length > 0) {
        $(".dob-picker").calendarsPicker({
			maxDate: 0,
			dateFormat: 'yyyy-mm-dd',
			pickerClass: 'custom-date-picker',
			calendar: $.calendars.instance('ummalqura'),
		});
    }

    if($('.date-time-picker').length > 0) {
        $('.date-time-picker').datetimepicker({
            format: 'YYYY-MM-DD hh:mm A'    
        });
    }

    
});
