(function ($) {
    $.fn.extend({
        ipAddressCard: function (settings) {
            var $this = $(this);

            function IpAddress(settings) {
                var options = {
                    ip_address: '8.8.4.4',
                    country_name: false,
                    country_code: false,
                    country_flag: false,
                    city_name: false,
                    region_name: false,
                    zip_code: false,
                    time_zone: false,
                    map: false,
                    style: false
                };

                var template = $('<div class="ip-address-card">' +
                        '<div class="ip-address-card-header"></div>' +
                        '<div class="ip-address-card-sub-header"></div>' +
                        '<div class="ip-address-card-content"></div>' +
                        '<div class="ip-address-card-footer"></div>' +
                        '</div>');
                var before = function () {
                    $this.html('<div class="ip-address-card-spinner">  <div class="ip-address-card-rect1"></div>  <div class="ip-address-card-rect2"></div>  <div class="ip-address-card-rect3"></div>  <div class="ip-address-card-rect4"></div>  <div class="ip-address-card-rect5"></div></div>');
                };

                var readData = function () {
                    before();
                    $.ajax({
                        url: 'http://ip-api.com/json/' + options.ip_address,
                        type: 'GET',
                        dataType: 'json',
                        async: true
                    }).done(function (response) {

                        if (options.style != false) {
                            template.addClass(options.style);
                        }
                        if (options.ip_address != false) {
                            template.find('.ip-address-card-content').text(response.query);
                        }
                        var address = $('<div/>');
                        if (options.country_name != false && response.country != null) {
                            address.append(response.country);
                            if (options.country_flag != false && response.country_flag != null) {
                                address.append($('<img/>').attr('src', response.country_flag));
                            }
                        }
                        if (options.city_name != false && response.city != null) {
                            address.prepend(response.city + " ,");
                        }
                        if (options.region_name != false && response.regionName != null) {
                            address.prepend(response.regionName + " ,");
                        }
                        template.find('.ip-address-card-header').append(address);

                        if (options.zip_code != false && response.zip != null) {
                            template.find('.ip-address-card-sub-header').append('<span>' + options.zip_code + ' ' + response.zip + '</span>');
                        }
                        if (options.time_zone != false && response.timezone != null) {
                            template.find('.ip-address-card-sub-header').append('<span>' + options.time_zone + ' ' + response.timezone + '</span>');
                        }
                        if (options.map != false) {
                            template.find('.ip-address-card-footer').append('<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' + response.lat + ',' + response.lon + '&hl=es;z=14&amp;output=embed"></iframe>')
                        }
                        $this.html(template);

                    }).fail(function (response) {
                        console.log(response);
                    });
                };

                var setDefaults = function (settings) {
                    $.extend(options, settings);
                    return this;
                };
                setDefaults(settings || {});

                readData();
            }
            ;

            return this.each(function () {
                new IpAddress(settings);
                return this;
            });
        }
    });
})(jQuery);