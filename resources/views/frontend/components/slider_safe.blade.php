@enqueueCSS('rev-slider-settings', asset('assets/slider/css/settings.css'))
@enqueueJS('rev-slider-tools', asset('assets/slider/js/jquery.themepunch.tools.min.js'), JS_LOCATION_DEFAULT ,'jquery')
@enqueueJS('rev-slider-revolution', asset('assets/slider/js/jquery.themepunch.revolution.min.js'), JS_LOCATION_DEFAULT, 'jquery')
@isset($alias)
    @php
        /** @var \App\RevSlider $slider */
    $slider = getThemeSlider($alias);
    $uid = uniqid();
    $slug = str_slug($alias, '_');
    @endphp
    @if($slider)
        <div data-wa-slider-id="{!! $alias !!}" id="wa-rev-slider-{!! $uid !!}"></div>
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
            @unique('wa-slider-loader')
            <script type="text/javascript">
                var $Revslider = new function () {
                    var loadedAssets = [];

                    this.loadSlider = function (placeholder, response) {
                        var slider = jQuery(placeholder);
                        var content = '',
                            revsliderScripts = [];

                        if (typeof response.assets != 'undefined') {
                            for (var i = 0; i < response.assets.length; i++) {
                                if (loadedAssets.indexOf(response.assets[i].file) == -1) {
                                    if (response.assets[i].file.endsWith('.js')) {
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

                        jQuery.getRevsliderScripts(revsliderScripts).done(function () {
                            slider.html(content);
                        }).fail(function (error) {
                            console.log('Revslider scripts load error');
                            slider.remove();
                        });
                    }

                }
            </script>
            @endunique
            <script type="text/javascript">
                $(function () {
                    $(document).ready(function () {
                        var data = {!! $slider->getFrontEndData() !!};
                        $Revslider.loadSlider('#wa-rev-slider-{!! $uid !!}', data );
                    });
                });
            </script>
        @endpush
    @endif
@endisset