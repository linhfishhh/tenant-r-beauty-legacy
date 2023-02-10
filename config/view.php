<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path( 'views' ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => realpath( storage_path( 'framework/views' ) ),

    'ui' => [
        'files'          => [
            'css' => [
                'bootstrap'            =>
                    [
                        'id' => 'bootstrap',
                        'src'  => 'assets/ui/css/bootstrap.min.css',
                    ],
                'limitless_core'       =>
                    [
                        'id' => 'limitless_core',
                        'src'  => 'assets/ui/css/core.min.css',
                    ],
                'limitless_components' =>
                    [
                        'id' => 'limitless_components',
                        'src'  => 'assets/ui/css/components.css',
                    ],
                'limitless_color'      =>
                    [
                        'id' => 'limitless_color',
                        'src'  => 'assets/ui/css/colors.min.css',
                    ],
                'icomoon'              =>
                    [
                        'id' => 'icomoon',
                        'src'  => 'assets/ui/css/icons/icomoon/styles.css',
                    ],

                'fontawesome' =>
                    [
                        'id' => 'fontawesome',
                        'src'  => 'assets/ui/css/icons/fontawesome/styles.min.css',
                    ],
                'animate'     =>
                    [
                        'id' => 'animate',
                        'src'  => 'assets/ui/css/extras/animate.min.css',
                    ],
                'roboto'      =>
                    [
                        'id' => 'roboto',
                        'src'  => 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;subset=latin-ext,vietnamese',
                    ],
                'roboto_local'      =>
	                [
		                'id' => 'roboto_local',
		                'src'  => 'assets/ui/fonts/roboto_local/fonts.css',
	                ],
                'fix'      =>
                    [
                        'id' => 'fix',
                        'src'  => 'assets/ui/css/fix.css',
                    ],
            ],
            'js'  => [
                'jquery'        =>
                    [
                        'id' => 'jquery',
                        'src'  => 'assets/ui/js/core/libraries/jquery.min.js',
                    ],
                'jquery_ui'        =>
                    [
                        'id' => 'jquery_ui',
                        'src'  => 'assets/ui/js/core/libraries/jquery_ui/full.min.js',
                    ],
                'dotdotdot'            =>
                    [
                        'id' => 'dotdotdot',
                        'src'  => 'assets/ui/js/plugins/jquery.dotdotdot.js',
                    ],
                'bootstrap'     =>
                    [
                        'id' => 'bootstrap',
                        'src'  => 'assets/ui/js/core/libraries/bootstrap.min.js',
                    ],
                'limitless_app' =>
                    [
                        'id' => 'limitless_app',
                        'src'  => 'assets/ui/js/core/app.min.js',
                    ],
                'limitless_init' =>
                    [
                        'id' => 'limitless_app',
                        'src'  => 'assets/ui/js/core/init.js',
                    ],
                'hover_dropdown'        =>
                    [
                        'id' => 'hover_dropdown',
                        'src'  => 'assets/ui/js/plugins/buttons/hover_dropdown.min.js',
                    ],
                'ace'        =>
                    [
                        'id' => 'ace',
                        'src'  => 'assets/ui/js/plugins/editors/ace/ace.js',
                    ],
                'codemirror'        =>
                    [
                        'id' => 'codemirror',
                        'src'  => 'assets/ui/js/plugins/editors/codemirror/codemirror.js',
                    ],
                'summernote'        =>
                    [
                        'id' => 'summernote',
                        'src'  => 'assets/ui/js/plugins/editors/summernote/summernote.min.js',
                    ],
                'wysihtml5'        =>
                    [
                        'id' => 'wysihtml5',
                        'src'  => 'assets/ui/js/plugins/editors/wysihtml5/wysihtml5.min.js',
                    ],
                'contextmenu'        =>
                    [
                        'id' => 'contextmenu',
                        'src'  => 'assets/ui/js/plugins/extensions/contextmenu.js',
                    ],
                'cookie'        =>
                    [
                        'id' => 'cookie',
                        'src'  => 'public_html/assets/ui/js/plugins/extensions/cookie.js',
                    ],
                'mockjax'        =>
                    [
                        'id' => 'mockjax',
                        'src'  => 'assets/ui/js/plugins/extensions/mockjax.min.js',
                    ],
                'mousewheel'        =>
                    [
                        'id' => 'mousewheel',
                        'src'  => 'assets/ui/js/plugins/extensions/mousewheel.min.js',
                    ],
                'session_timeout'        =>
                    [
                        'id' => 'session_timeout',
                        'src'  => 'assets/ui/js/plugins/extensions/session_timeout.min.js',
                    ],
                'alpaca'        =>
                    [
                        'id' => 'alpaca',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/alpaca/alpaca.min.js',
                    ],
                'price_format'        =>
                    [
                        'id' => 'price_format',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/alpaca/price_format.min.js',
                    ],
                'handlebars'        =>
                    [
                        'id' => 'handlebars',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/typeahead/handlebars.min.js',
                    ],
                'typeahead'        =>
                    [
                        'id' => 'typeahead',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js',
                    ],
                'autosize'        =>
                    [
                        'id' => 'autosize',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/autosize.min.js',
                    ],
                'duallistbox'        =>
                    [
                        'id' => 'duallistbox',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/duallistbox.min.js',
                    ],
                'formatter'        =>
                    [
                        'id' => 'formatter',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/formatter.min.js',
                    ],
                'maxlength'        =>
                    [
                        'id' => 'maxlength',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/maxlength.min.js',
                    ],
                'passy'        =>
                    [
                        'id' => 'passy',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/passy.js',
                    ],
                'touchspin'        =>
                    [
                        'id' => 'touchspin',
                        'src'  => 'assets/ui/js/plugins/forms/inputs/touchspin.min.js',
                    ],
                'switch'        =>
                    [
                        'id' => 'switch',
                        'src'  => 'assets/ui/js/plugins/forms/styling/switch.min.js',
                    ],
                'switchery'        =>
                    [
                        'id' => 'switchery',
                        'src'  => 'assets/ui/js/plugins/forms/styling/switchery.min.js',
                    ],
                'tagsinput'        =>
                    [
                        'id' => 'tagsinput',
                        'src'  => 'assets/ui/js/plugins/forms/tags/tagsinput.min.js',
                    ],
                'tokenfield'        =>
                    [
                        'id' => 'tokenfield',
                        'src'  => 'assets/ui/js/plugins/forms/tags/tokenfield.min.js',
                    ],
                'validate'        =>
                    [
                        'id' => 'validate',
                        'src'  => 'assets/ui/js/plugins/forms/validation/validate.min.js',
                    ],
                'form'        =>
                    [
                        'id' => 'form',
                        'src'  => 'assets/ui/js/plugins/forms/wizards/form_wizard/form.min.js',
                    ],
                'form_wizard'        =>
                    [
                        'id' => 'form_wizard',
                        'src'  => 'assets/ui/js/plugins/forms/wizards/form_wizard/form_wizard.min.js',
                    ],
                'steps'        =>
                    [
                        'id' => 'steps',
                        'src'  => 'assets/ui/js/plugins/forms/wizards/steps.min.js',
                    ],
                'stepy'        =>
                    [
                        'id' => 'stepy',
                        'src'  => 'assets/ui/js/plugins/forms/wizards/stepy.min.js',
                    ],
                'i18next'        =>
                    [
                        'id' => 'i18next',
                        'src'  => 'assets/ui/js/plugins/internationalization/i18next.min.js',
                    ],
                'progressbar'        =>
                    [
                        'id' => 'progressbar',
                        'src'  => 'assets/ui/js/plugins/loaders/progressbar.min.js',
                    ],
                'cropper'        =>
                    [
                        'id' => 'cropper',
                        'src'  => 'assets/ui/js/plugins/media/cropper.min.js',
                    ],
                'fancybox'        =>
                    [
                        'id' => 'fancybox',
                        'src'  => 'assets/ui/js/plugins/media/fancybox.min.js',
                    ],
                'bootbox'        =>
                    [
                        'id' => 'bootbox',
                        'src'  => 'assets/ui/js/plugins/notifications/bootbox.min.js',
                    ],
                'jgrowl'        =>
                    [
                        'id' => 'jgrowl',
                        'src'  => 'assets/ui/js/plugins/notifications/jgrowl.min.js',
                    ],
                'noty'        =>
                    [
                        'id' => 'noty',
                        'src'  => 'assets/ui/js/plugins/notifications/noty.min.js',
                    ],
                'pnotify'        =>
                    [
                        'id' => 'pnotify',
                        'src'  => 'assets/ui/js/plugins/notifications/pnotify.min.js',
                    ],
                'sweet_alert'        =>
                    [
                        'id' => 'sweet_alert',
                        'src'  => 'assets/ui/js/plugins/notifications/sweet_alert.min.js',
                    ],
                'bootpag'        =>
                    [
                        'id' => 'bootpag',
                        'src'  => 'assets/ui/js/plugins/pagination/bootpag.min.js',
                    ],
                'bs_pagination'        =>
                    [
                        'id' => 'bs_pagination',
                        'src'  => 'assets/ui/js/plugins/pagination/bs_pagination.min.js',
                    ],
                'datepaginator'        =>
                    [
                        'id' => 'datepaginator',
                        'src'  => 'assets/ui/js/plugins/pagination/datepaginator.min.js',
                    ],
                'spectrum'        =>
                    [
                        'id' => 'spectrum',
                        'src'  => 'assets/ui/js/plugins/pickers/color/spectrum.js',
                    ],
                'autocomplete_addresspicker'        =>
                    [
                        'id' => 'autocomplete_addresspicker',
                        'src'  => 'assets/ui/js/plugins/pickers/location/autocomplete_addresspicker.js',
                    ],
                'location'        =>
                    [
                        'id' => 'location',
                        'src'  => 'assets/ui/js/plugins/pickers/location/location.js',
                    ],
                'typeahead_addresspicker'        =>
                    [
                        'id' => 'typeahead_addresspicker',
                        'src'  => 'assets/ui/js/plugins/pickers/location/typeahead_addresspicker.js',
                    ],
                'legacy'        =>
                    [
                        'id' => 'legacy',
                        'src'  => 'assets/ui/js/plugins/pickers/pickadate/legacy.js',
                    ],
                'picker_date'        =>
                    [
                        'id' => 'picker_date',
                        'src'  => 'assets/ui/js/plugins/pickers/pickadate/picker.date.js',
                    ],
                'picker'        =>
                    [
                        'id' => 'picker',
                        'src'  => 'assets/ui/js/plugins/pickers/pickadate/picker.js',
                    ],
                'picker_time'        =>
                    [
                        'id' => 'picker_time',
                        'src'  => 'assets/ui/js/plugins/pickers/pickadate/picker.time.js',
                    ],
                'bootstrap_datetime_picker'        =>
	                [
		                'id' => 'bootstrap_datetime_picker',
		                'src'  => 'assets/ui/js/plugins/pickers/bootstrap-datetimepicker.min.js',
	                ],
                'anytime'        =>
                    [
                        'id' => 'anytime',
                        'src'  => 'assets/ui/js/plugins/pickers/anytime.min.js',
                    ],
                'datepicker'        =>
                    [
                        'id' => 'datepicker',
                        'src'  => 'assets/ui/js/plugins/pickers/datepicker.js',
                    ],
                'daterangepicker'        =>
                    [
                        'id' => 'daterangepicker',
                        'src'  => 'assets/ui/js/plugins/pickers/daterangepicker.js',
                    ],
                'ion_rangeslider'        =>
                    [
                        'id' => 'ion_rangeslider',
                        'src'  => 'assets/ui/js/plugins/sliders/ion_rangeslider.min.js',
                    ],
                'nouislider'        =>
                    [
                        'id' => 'nouislider',
                        'src'  => 'assets/ui/js/plugins/sliders/nouislider.min.js',
                    ],
                'slider_pips'        =>
                    [
                        'id' => 'slider_pips',
                        'src'  => 'assets/ui/js/plugins/sliders/slider_pips.min.js',
                    ],
                'datatables'        =>
                    [
                        'id' => 'datatables',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/datatables.min.js',
                    ],
                'datatables_autofill'        =>
                    [
                        'id' => 'datatables_autofill',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/autofill.min.js',
                    ],
                'datatables_buttons'        =>
                    [
                        'id' => 'datatables_buttons',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/buttons.min.js',
                    ],
                'datatables_col_reorder'        =>
                    [
                        'id' => 'datatables_col_reorder',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/col_reorder.min.js',
                    ],
                'datatables_fixed_columns'        =>
                    [
                        'id' => 'datatables_fixed_columns',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/fixed_columns.min.js',
                    ],
                'datatables_fixed_header'        =>
                    [
                        'id' => 'datatables_fixed_header',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/fixed_header.min.js',
                    ],
                'datatables_key_table'        =>
                    [
                        'id' => 'datatables_key_table',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/key_table.min.js',
                    ],
                'datatables_natural_sort'        =>
                    [
                        'id' => 'datatables_natural_sort',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/natural_sort.js',
                    ],
                'datatables_responsive'        =>
                    [
                        'id' => 'datatables_responsive',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/responsive.min.js',
                    ],
                'datatables_row_reorder'        =>
                    [
                        'id' => 'datatables_row_reorder',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/row_reorder.min.js',
                    ],
                'datatables_scroller'        =>
                    [
                        'id' => 'datatables_scroller',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/scroller.min.js',
                    ],
                'datatables_select'        =>
                    [
                        'id' => 'datatables_select',
                        'src'  => 'assets/ui/js/plugins/tables/datatables/extensions/select.min.js',
                    ],
                'footable'        =>
                    [
                        'id' => 'footable',
                        'src'  => 'assets/ui/js/plugins/tables/footable/footable.min.js',
                    ],
                'handsontable'        =>
                    [
                        'id' => 'handsontable',
                        'src'  => 'assets/ui/js/plugins/tables/handsontable/handsontable.min.js',
                    ],
                'fancytree_all'        =>
                    [
                        'id' => 'fancytree_all',
                        'src'  => 'assets/ui/js/plugins/trees/fancytree_all.min.js',
                    ],
                'fancytree_childcounter'        =>
                    [
                        'id' => 'fancytree_childcounter',
                        'src'  => 'assets/ui/js/plugins/trees/fancytree_childcounter.js',
                    ],
                'prism'        =>
                    [
                        'id' => 'prism',
                        'src'  => 'assets/ui/js/plugins/ui/prism.min.js',
                    ],
                'fab'        =>
                    [
                        'id' => 'fab',
                        'src'  => 'assets/ui/js/plugins/ui/fab.min.js',
                    ],
                'drilldown'        =>
                    [
                        'id' => 'drilldown',
                        'src'  => 'assets/ui/js/plugins/ui/drilldown.js',
                    ],
                'dragula'        =>
                    [
                        'id' => 'dragula',
                        'src'  => 'assets/ui/js/plugins/ui/dragula.min.js',
                    ],
                'moment'        =>
                    [
                        'id' => 'moment',
                        'src'  => 'assets/ui/js/plugins/ui/moment/moment.min.js',
                    ],
                'moment_locales'        =>
                    [
                        'id' => 'moment_locales',
                        'src'  => 'assets/ui/js/plugins/ui/moment/moment_locales.min.js',
                    ],
                'headroom'        =>
                    [
                        'id' => 'headroom',
                        'src'  => 'assets/ui/js/plugins/ui/headroom/headroom.min.js',
                    ],
                'headroom_jquery'        =>
                    [
                        'id' => 'headroom_jquery',
                        'src'  => 'assets/ui/js/plugins/ui/headroom/headroom_jquery.min.js',
                    ],
                'fullcalendar'        =>
                    [
                        'id' => 'fullcalendar',
                        'src'  => 'assets/ui/js/plugins/ui/fullcalendar/fullcalendar.min.js',
                    ],
                'dropzone'        =>
                    [
                        'id' => 'dropzone',
                        'src'  => 'assets/ui/js/plugins/uploaders/dropzone.min.js',
                    ],
                'plupload'        =>
                    [
                        'id' => 'plupload',
                        'src'  => 'assets/ui/js/plugins/uploaders/plupload/plupload.full.min.js',
                    ],
                'plupload_queue'        =>
                    [
                        'id' => 'plupload_queue',
                        'src'  => 'assets/ui/js/plugins/uploaders/plupload/plupload.queue.min.js',
                    ],
                'fileinput'        =>
                    [
                        'id' => 'fileinput',
                        'src'  => 'assets/ui/js/plugins/uploaders/fileinput/fileinput.min.js',
                    ],
                'fileinput_purify'        =>
	                [
		                'id' => 'fileinput_purify',
		                'src'  => 'assets/ui/js/plugins/uploaders/fileinput/plugins/purify.min.js',
	                ],
                'fileinput_sortable'        =>
	                [
		                'id' => 'fileinput_sortable',
		                'src'  => 'assets/ui/js/plugins/uploaders/fileinput/plugins/sortable.min.js',
	                ],
                'velocity'        =>
                    [
                        'id' => 'velocity',
                        'src'  => 'assets/ui/js/plugins/velocity/velocity.min.js',
                    ],
                'velocity.ui'        =>
                    [
                        'id' => 'velocity.ui',
                        'src'  => 'assets/ui/js/plugins/velocity/velocity.ui.min.js',
                    ],
                'sparkline'        =>
                    [
                        'id' => 'sparkline',
                        'src'  => 'assets/ui/js/plugins/visualization/sparkline.min.js',
                    ],
                'echarts'        =>
                    [
                        'id' => 'echarts',
                        'src'  => 'assets/ui/js/plugins/visualization/echarts/echarts.js',
                    ],
                'dimple'        =>
                    [
                        'id' => 'dimple',
                        'src'  => 'assets/ui/js/plugins/visualization/dimple/dimple.min.js',
                    ],
                'd3'        =>
                    [
                        'id' => 'd3',
                        'src'  => 'assets/ui/js/plugins/visualization/d3/d3.min.js',
                    ],
                'd3_tooltip'        =>
                    [
                        'id' => 'd3_tooltip',
                        'src'  => 'assets/ui/js/plugins/visualization/d3/d3_tooltip.js',
                    ],
                'venn'        =>
                    [
                        'id' => 'venn',
                        'src'  => 'assets/ui/js/plugins/visualization/d3/venn.js',
                    ],
                'c3'        =>
                    [
                        'id' => 'c3',
                        'src'  => 'assets/ui/js/plugins/visualization/c3/c3.min.js',
                    ],
                'select2' => [
                    'id' => 'select2',
                    'src' => 'assets/ui/js/plugins/forms/selects/select2.min.js'
                ],
                'selectboxit' => [
                    'id' => 'selectboxit',
                    'src' => 'assets/ui/js/plugins/forms/selects/selectboxit.min.js'
                ],
                'bootstrap_select' => [
                    'id' => 'bootstrap_select',
                    'src' => 'assets/ui/js/plugins/forms/selects/bootstrap_select.min.js'
                ],
                'bootstrap_multiselect' => [
                    'id' => 'bootstrap_multiselect',
                    'src' => 'assets/ui/js/plugins/forms/selects/bootstrap_multiselect.js'
                ],
                'uniform'       =>
                    [
                        'id' => 'uniform',
                        'src'  => 'assets/ui/js/plugins/forms/styling/uniform.min.js',
                    ],
                'address'     =>
                    [
                        'id' => 'address',
                        'src'  => 'public_html/assets/ui/js/plugins/forms/editable/address.js',
                    ],
                'editable'     =>
                    [
                        'id' => 'editable',
                        'src'  => 'assets/ui/js/plugins/forms/editable/editable.min.js',
                    ],
                'blockui'       =>
                    [
                        'id' => 'blockui',
                        'src'  => 'assets/ui/js/plugins/loaders/blockui.min.js',
                    ],
                'pace'          =>
                    [
                        'id' => 'pace',
                        'src'  => 'assets/ui/js/plugins/loaders/pace.min.js',
                    ],
                'ladda'   =>
                    [
                        'id' => 'ladda',
                        'src'  => 'assets/ui/js/plugins/buttons/ladda.min.js',
                    ],
                'spin'   =>
                    [
                        'id' => 'spin',
                        'src'  => 'assets/ui/js/plugins/buttons/spin.min.js',
                    ],
                'nicescroll' =>
                [
                    'id' => 'nicescroll',
                    'src' => 'assets/ui/js/plugins/ui/nicescroll.min.js',
                ],
                'layout_fixed_custom' =>
                [
                    'id' => 'layout_fixed_custom',
                    'src' => 'assets/ui/js/pages/layout_fixed_custom.js',
                ],
                'iframeresizer' =>
                    [
                        'id' => 'iframeresizer',
                        'src' => 'assets/ui/js/plugins/iframe-resizer/iframeResizer.min.js',
                    ],
                'iframeresizer_contentwindow' =>
                    [
                        'id' => 'iframeresizer_contentwindow',
                        'src' => 'assets/ui/js/plugins/iframe-resizer/iframeResizer.contentWindow.min.js',
                    ],
                'tinymce' =>
                    [
                        'id' => 'tinymce',
                        'src' => 'assets/ui/js/plugins/editors/tinymce/tinymce.min.js',
                    ],
                'tinymce_jquery' =>
                    [
                        'id' => 'tinymce_jquery',
                        'src' => 'assets/ui/js/plugins/editors/tinymce/jquery.tinymce.min.js',
                    ],
            ],
        ],
        'bundles' => [
            'limitless' => [
                'id' => 'limitless',
                'css' => [
                    [
                        'src' => 'roboto_local',
                    ],
                    [
                        'src' => 'icomoon',
                    ],
                    [
                        'src' => 'bootstrap',
                    ],
                    [
                        'src' => 'limitless_core',
                    ],
                    [
                        'src' => 'limitless_components',
                    ],
                    [
                        'src' => 'limitless_color',
                    ],
                    [
                        'src' => 'fix',
                    ],
                ],
                'js'  => [
                    [
                        'src' => 'jquery',
                    ],
                    [
                        'src'     => 'pace',
                        'require' => 'jquery',
                    ],
                    [
                        'src'     => 'bootstrap',
                        'require' => 'jquery',
                    ],
                    [
                        'src'     => 'blockui',
                        'require' => 'jquery',
                    ],
                    [
                        'src'     => 'limitless_app',
                        'require' => 'jquery',
                    ],
                    [
                        'src'     => 'limitless_init',
                        'require' => 'limitless_app',
                    ],
                ],
            ],
        ],
    ],
];
