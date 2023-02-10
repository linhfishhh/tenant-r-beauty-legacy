@enqueueCSS('rev-slider-settings', asset('assets/slider/css/settings.css'))
@enqueueJS('rev-slider-tools', asset('assets/slider/js/jquery.themepunch.tools.min.js'), JS_LOCATION_DEFAULT ,'jquery')
@enqueueJS('rev-slider-revolution', asset('assets/slider/js/jquery.themepunch.revolution.min.js'), JS_LOCATION_DEFAULT, 'jquery')
@isset($alias)
    @php
        /** @var \App\RevSlider $slider */
    $key = rand();
    $slider = getThemeSlider($alias);
    @endphp
    @if($slider)
        <div class="wa-slider-placeholder" data-alias="{!! $alias !!}" data-key="{!! $key !!}"></div>
        @push('page_footer_js')
            @unique('wa-slider-handler')
            <script type="text/javascript">
                if (!String.prototype.endsWith) {
                    String.prototype.endsWith = function (searchString, position) {
                        var subjectString = this.toString();
                        if (typeof position !== 'number' || !isFinite(position)
                            || Math.floor(position) !== position || position > subjectString.length) {
                            position = subjectString.length;
                        }
                        position -= searchString.length;
                        var lastIndex = subjectString.indexOf(searchString, position);
                        return lastIndex !== -1 && lastIndex === position;
                    };
                }
                jQuery.getRevsliderScripts = function (arr, path) {
                    var _arr = jQuery.map(arr, function (scr) {
                        return jQuery.getScript((path || "") + scr);
                    });

                    _arr.push(jQuery.Deferred(function (deferred) {
                        jQuery(deferred.resolve);
                    }));

                    return jQuery.when.apply(jQuery, _arr);
                };
            </script>
            @endunique
            @unique('wa-slider-loader-ajax')
            <script type="text/javascript">
                var $RevsliderAjax = new function() {
                    var t = this;
                    var cssSelector = '.wa-slider-placeholder';
                    var apiUrl = '{!! url('apps/revslider/index.php') !!}?c=embed';
                    var loadedAssets = [];

                    t.init = function() {

                        if (typeof punchgs != 'undefined')
                            loadedAssets.push('jquery.themepunch.tools.min.js');
                        if (typeof jQuery().revolution != 'undefined')
                            loadedAssets.push('jquery.themepunch.revolution.min.js');
                        if (typeof document.styleSheets != "undefined") {
                            for (var i = 0; i < document.styleSheets.length; i++) {
                                if (document.styleSheets[i].href && document.styleSheets[i].href.indexOf('settings.css') != -1) {
                                    loadedAssets.push('settings.css');
                                    break;
                                }
                            }
                        }

                        initSliders();
                    }

                    var initSliders = function() {
                        jQuery(cssSelector).each(function(key, item) {
                            loadSlider(item, key);
                        });
                    }

                    var loadSlider = function(placeholder, key, loaded) {
                        var slider = jQuery(placeholder);
                        jQuery.ajax({
                            type:		'post',
                            url:		slider.data('api_url') ? slider.data('api_url') : apiUrl,
                            dataType:	'json',
                            data:		{
                                alias:	slider.data('alias'),
                                key:	key
                            },
                            success:	function(response) {
                                var content = '',
                                    revsliderScripts = [];
                                console.log(response.assets);
                                if (typeof response.assets != 'undefined') {
                                    for (var i = 0; i < response.assets.length; i++) {
                                        if (loadedAssets.indexOf(response.assets[i].file) == -1) {
                                            if ( response.assets[i].file.endsWith('.js') ) {
                                                revsliderScripts.push(response.assets[i].file);
                                            } else {
                                                loadedAssets.push(response.assets[i].file);
                                                content += response.assets[i].include;
                                            }
                                        }
                                    }
                                }
                                if (typeof response.slider != 'undefined') {
                                    content += response.slider;
                                }
                                jQuery.getRevsliderScripts(revsliderScripts).done(function() {
                                    slider.html(content);
                                }).fail(function(error) {
                                    console.log('Revslider scripts load error');
                                    slider.remove();
                                });
                            }
                        });
                    }

                }
                jQuery(document).ready($RevsliderAjax.init);
            </script>
            @endunique
        @endpush
    @endif
@endisset