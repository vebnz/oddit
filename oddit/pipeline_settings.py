PIPELINE = {
    'PIPELINE_ENABLED': True,
    'STYLESHEETS': {
        'styles': {
            'source_filenames': (
                'base.scss',
                'bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.css',
            ),
            'output_filename': 'css/styles.css',
            'variant': 'datauri',
            'extra_context': {
                'media': 'screen,projection',
            },
        },
    },
    'JAVASCRIPT': {
        'scripts': {
            'source_filenames': (
                'scripts/modernizr.js',
                'jquery/dist/jquery.js',
                'remarked/dist/remarked.js',
                'chosen/chosen.jquery.js',
                'moment/moment.js',
                'bootstrap-tagsinput/dist/bootstrap-tagsinput.js',
                'bootstrap-sass/assets/javascripts/bootstrap.js',
                'bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
                'bootstrap-validator/dist/validator.js',
                'toastr/toastr.js',
                'scripts/jquery.autocomplete.pack.js',
                'scripts/jquery.pngfix.js',
                'scripts/jcookie.js',
                'trumbowyg/dist/trumbowyg.js',
            ),
            'output_filename': 'js/scripts.js',
            'extra_context': {
                'async': True,
            },
        }
    },
    # 'JS_COMPRESSOR': 'pipeline.compressors.jsmin.JSMinCompressor',
    'JS_COMPRESSOR': 'pipeline.compressors.uglifyjs.UglifyJSCompressor',
    'CSS_COMPRESSOR': 'pipeline.compressors.cssmin.CSSMinCompressor',
    'COMPILERS': (
        'pipeline.compilers.sass.SASSCompiler',
    ),
    'SASS_ARGUMENTS': '--scss --compass',
}
