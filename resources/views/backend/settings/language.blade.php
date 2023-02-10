@unique('language_tool')
<script type="text/javascript">
            @if (isMultipleLanguage())
    var $language_tool = {
            languages: {!! json_encode(config('app.locales')) !!},
            language_metas: {!! json_encode(config('app.locale_metas')) !!},
            getCodes: function (return_string) {
                var rs = [];
                $.each(this.languages, function (code, title) {
                    rs.push(code);
                });
                if (return_string) {
                    rs = rs.join(',');
                }
                return rs;
            },
            isSupported: function (lang_code) {
                return this.languages.hasOwnProperty(lang_code);
            },
            getMeta: function (lang_code) {
                return this.language_metas[lang_code];
            },
            getSelect2Format: function () {
                var rs = [];
                $.each(this.languages, function (i,v) {
                    rs.push(
                        {
                            id: i,
                            text: v
                        }
                    );
                });
                return rs;
            },
            getTitle:function (lang_code) {
                var rs = '{{__('Không hổ trợ')}} - '+lang_code;
                if (this.languages.hasOwnProperty(lang_code)){
                    rs = this.languages[lang_code];
                }
                return rs;
            }
        };
    @endif
</script>
@endunique
