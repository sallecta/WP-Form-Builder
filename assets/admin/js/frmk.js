/**
 * Global Functions
 */

(function($) {

    // reindex rows
    $.fn.frmk_reindex_rows = function () {
        $(this).each(function (rowIndex) {
            /// find each input with a name attribute inside each row
            $(this).find('input[name], select[name], textarea[name]').each(function () {
                var name;
                name = $(this).attr('name');
                name = name.replace(/field\[[0-9]*\]/g, 'field[' + rowIndex + ']');
                $(this).attr('name', name);
            });

            // for test purposes to visually see order num
            var _order_input = $(this).find('input[name$="[order]"]');
            if(_order_input.length > 0){
                _order_input.val(rowIndex);
            }
        });
    };

})(jQuery);

/**
 * Drag & Drop field selector
 */
(function($){

    $(document).ready(function(){

        var debug = false;
        var _sortable_elem = $('#sortable');
        var _draggable_elem = $( ".draggable" );
        var _placeholder_text  = $('#sortable .placeholder').text();
        var templates = {
            text: $('#field-placeholder .frmk-panel[data-field-type="text"]').clone().show(),
            textarea: $('#field-placeholder .frmk-panel[data-field-type="textarea"]').clone().show(),
            select: $('#field-placeholder .frmk-panel[data-field-type="select"]').clone().show(),
            checkbox: $('#field-placeholder .frmk-panel[data-field-type="checkbox"]').clone().show(),
            radio: $('#field-placeholder .frmk-panel[data-field-type="radio"]').clone().show(),
            file: $('#field-placeholder .frmk-panel[data-field-type="file"]').clone().show(),
            number: $('#field-placeholder .frmk-panel[data-field-type="number"]').clone().show()
        };

        _draggable_elem.click(function(){

            // load template for file
            var template = templates[$(this).data('field')].clone();
            template.addClass('frmk-panel--active frmk-panel--new');
            _sortable_elem.append(template);
            _sortable_elem.find('.frmk-panel--new').wrap('<li class="draggable ui-state-highlight ui-draggable ui-draggable-handle frmk-dropped-item"></li>').removeClass('frmk-panel--new');
            $('body').trigger('frmk_element_added', template);

            _sortable_elem.find('.placeholder').hide();

            // re-indexed rows
            _sortable_elem.find('li').frmk_reindex_rows();

            // trigger that element has been changed
            $(document).trigger('frmk_changed');

        });

        _sortable_elem.sortable({
            placeholder: "sortable-placeholder",
            handle: '.frmk-panel__header',
            // toleranceElement: '> a',
            items: 'li:not(.placeholder)',
            over: function() {
                _sortable_elem.find('.placeholder').hide();
            },
            out: function() {

                if( _sortable_elem.find('li:not(.placeholder)').length <= 2){
                    _sortable_elem.find('.placeholder').show();
                }
            },
            stop: function() {
                _sortable_elem.find('.placeholder').hide();
            }
        });

        /**
         * make field buttons draggable to sortable fields
         */
        _draggable_elem.draggable({
            connectToSortable: "#sortable",
            helper: "clone",
            revert: "invalid",
            stop: function() {
                // _sortable_elem.find('.placeholder').remove();
            },
            start: function(event, ui){
                $(ui.helper).width($(this).width());
                $(ui.helper).height($(this).height());
            }
        });

        /**
         * When field is dropped, add class and change contents
         */
        _sortable_elem.on( "sortreceive", function( event, ui ) {

            if(debug){
                console.log('sortreceive');
            }

            // load template for file
            var template = templates[ui.item.data('field')].clone();
            if(template.length > 0){
                $(ui.helper).addClass('frmk-dropped-item');
                $(ui.helper).html(template);
                var panel = $(ui.helper).find('.frmk-panel').addClass('frmk-panel--active');
                $('body').trigger('frmk_element_added', panel);
            }

            // trigger that element has been changed
            $(document).trigger('frmk_changed');
        } );

        /**
         * Remove fixed height when sorting has been stopped
         */
        _sortable_elem.on('sortstop', function(event, ui){

            if(debug){
                console.log('sortstop');
            }

            $(ui.item).height('auto');
            $(ui.item).width('auto');

            // reindex rows
            if(debug){
                console.log('reindex field rows');
            }
            _sortable_elem.find('li').frmk_reindex_rows();

            // trigger that element has been changed
            $(document).trigger('frmk_changed');
        });

        /**
         * Set width and height when sorting has been started,
         * change placeholder to the items height
         */
        _sortable_elem.on('sortstart', function(event, ui){

            if(debug){
                console.log('sortstart');
            }

            if(!$(ui.item).hasClass('frmk-dropped-item')){
                console.log('found class!');
                return;
            }

            // set width and height of element
            var _element_height = $(ui.item).height();
            $(ui.item).height(_element_height);
            $(ui.item).width($('#sortable').width());

            // set height of placeholder
            $('#sortable .sortable-placeholder').height(_element_height).text(_placeholder_text);
        });

        // set height of placeholder
        _sortable_elem.find('.placeholder').height($('.frmk-cols').height() - 40);

        if( _sortable_elem.find('li.ui-state-default').length > 0){
            _sortable_elem.find('.placeholder').hide();
        }

        // reindex rows
        if(debug){
            console.log('reindex field rows');
        }
        _sortable_elem.find('li').frmk_reindex_rows();
    });

    $(document).on('click', '.frmk-del-field', function(e){
        e.preventDefault();
        var result = confirm('Are you sure you want to delete this field?');
        if(result) {
            $(this).parents('li').remove();
            if ($('#sortable li:not(.placeholder)').length == 0) {
                var _placeholder = $('#sortable .placeholder');
                _placeholder.height($('.frmk-cols').height() - 40);
                _placeholder.show();
            }

            // trigger that element has been changed
            $(document).trigger('frmk_changed');
        }
    });

})(jQuery);

/**
 * File Boxed
 */
(function($){

    $(document).ready(function() {

        $(document).on('click', '.frmk-panel__header .frmk-panel__toggle', function () {
            $(this).parents('.frmk-panel').toggleClass('frmk-panel--active');
        });

    });

})(jQuery);

/**
 * Panel Name
 */
(function($){

    $(document).ready(function() {

        $(document).on('keyup change', '.frmk-input--label', function(){
            $(this).parents('.frmk-panel').find('.frmk-panel__header span.name').text( $(this).val() );
        });

    });

})(jQuery);

/**
 * Repeater Block
 */
(function($){

    var repeater_templates = {};
    var repeater_indexs = {};
    var repeater_prefix = {};

    $(document).ready(function(){

        $('.frmk-repeater').each(function(){

            var _repeater = $(this);
            var _template_name = _repeater.data('templateName');
            if(repeater_templates[_template_name] == undefined){
                repeater_templates[_template_name] = $( _repeater.find('script.frmk-repeater-template').html() ).removeClass('frmk-repeater__template');
            }

            var _template_index = _repeater.data('templateIndex');
            if(repeater_indexs[_template_name] == undefined){
                if(_template_index){
                    repeater_indexs[_template_name] = new RegExp(_template_index);
                }else{
                    repeater_indexs[_template_name] = /\[[0-9]*\]$/g;
                }
            }

            var _template_index = _repeater.data('templatePrefix');
            if(repeater_prefix[_template_name] == undefined){
                if(_template_index){
                    repeater_prefix[_template_name] = _repeater.data('templatePrefix');
                }else{
                    repeater_prefix[_template_name] = '';
                }
            }

            // reindex last []
            _repeater.find('.frmk-repeater-row').each(function(elIndex){
                $(this).find('input[name], select[name]').each(function () {
                    var name;
                    name = $(this).attr('name');
                    name = name.replace(repeater_indexs[_template_name], repeater_prefix[_template_name] + '[' + elIndex + ']');
                    $(this).attr('name', name);
                });
            });

            // trigger that element has been changed
            _repeater.trigger('frmk_repeater_init',_repeater);
        });
    });

    $(document).on('click', '.frmk-add-row', function(e){
        e.preventDefault();

        var _repeater = $(this).parents('.frmk-repeater');
        var _template_name = _repeater.data('templateName');

        if(repeater_templates[_template_name] == undefined){
            console.error("Template could not be found, has not been stored in templates array!");
            return;
        }

        var _repeater_container = _repeater.find('.frmk-repeater-container');
        var _elem = repeater_templates[_template_name].clone();

        if(_repeater_container.length > 0){
            _repeater_container.append(_elem);
        }else{
            _repeater.append(_elem);
        }

        // reindex last []
        _repeater.find('.frmk-repeater-row').each(function(elIndex){
            $(this).find('input[name], select[name], textarea[name]').each(function () {
                var name;
                name = $(this).attr('name');
                name = name.replace(repeater_indexs[_template_name], repeater_prefix[_template_name] + '[' + elIndex + ']');
                $(this).attr('name', name);
            });
        });

        _repeater.trigger('frmk_repeater_added');
        $('body').trigger('frmk_element_added', _elem);

        // reindex field it belongs to
        $('#sortable li').frmk_reindex_rows();

        // trigger that element has been changed
        $(document).trigger('frmk_changed');
    });

    $(document).on('click', '.frmk-del-row', function(e){
        e.preventDefault();

        var _repeater = $(this).parents('.frmk-repeater');
        var _min = 0;
        if(_repeater.data('min')) {
            _min = parseInt(_repeater.data('min'));
        }

        if(_repeater.find('.frmk-repeater-row').length > _min) {
            var _row = $(this).parents('.frmk-repeater-row');
            _row.remove();
            _repeater.trigger('frmk_repeater_removed');
        }

        // trigger that element has been changed
        $(document).trigger('frmk_changed');
    });

})(jQuery);

/**
 * Field Validation Rules
 */
(function($){

    var _disabled = {};
    var _validation_templates = {};

    /**
     * Re-Index rules
     */
    var reIndexRules = function(){
        $('.frmk-validation-repeater').each(function () {
            reIndexRule($(this));
        });
    };

    var reIndexRule = function(_elem){
        _elem.find('.frmk-validation-row').each(function(rowIndex){
            $(this).find('input[name], select[name], textarea[name]').each(function(){
                var name;
                name = $(this).attr('name');
                name = name.replace(/field\[(\d*)\]\[validation\]\[(\d*)\]\[(\w*)\]/g, 'field[$1][validation]['+rowIndex+'][$3]');
                $(this).attr('name', name);
            });
        });
    };

    /**
     * Disable / Enable validation rules on type dropdowns
     * @param _repeater
     * @param _validation_selector
     */
    var onRuleTypeChange = function(_repeater, _validation_selector){
        var _validation_type = _validation_selector.val();

        _disabled = {};

        _repeater.find('.frmk-validation-row').each(function(i){
            var _select = $(this).find('.validation_type');
            var _current_val = _select.val();

            // skip empty values or current selectbox
            if(_current_val !== ''){
                if(_disabled[_current_val] !== undefined){
                    // _select.val('');
                    _disabled[_current_val]++;
                }else{
                    _disabled[_current_val] = 1;
                }
            }
        });

        // clear currently selected validation type due to pre existing type
        if(_validation_type !== "" && _disabled[_validation_type] > 1){
            _validation_selector.val('');
        }

        // enable all options
        $('.frmk-validation-repeater .validation_type option').prop('disabled', false);

        if( !$.isEmptyObject(_disabled) ){

            // disable currently selected validation types
            $('.frmk-validation-repeater .validation_type').each(function(){

                var _val = $(this).val();
                for(var validation_rule in _disabled){
                    if(validation_rule != _val){
                        $(this).find('option[value="'+validation_rule+'"]').prop('disabled', true);
                    }
                }
            });

        }

        // todo: load validation type fields
        var _ruleParent = _validation_selector.parents('.frmk-validation-row');
        var _val = _validation_selector.val();
        var _active_rule = _ruleParent.data('rule');

        if(_val != '' && _val != _active_rule){

            _ruleParent.data('rule', _val);
            if(_validation_templates[_val] !== undefined){
                _ruleParent.find('.frmk-validation__rule').html(_validation_templates[_val]);

                $('body').trigger('frmk_element_added', _ruleParent.find('.frmk-validation__rule'));

                // reindex field it belongs to
                $('#sortable li').frmk_reindex_rows();
            }
        }

        if(_val === ''){
            _ruleParent.data('rule', '');
            _ruleParent.find('.frmk-validation__rule').html('');
        }

        reIndexRules();
    }

    $(document).ready(function(){

        $('.frmk-validation__rule').each(function(){
            var _key = $(this).data('rule');
            _validation_templates[_key] = $(this).html();
        });
    });

    $(document).on('frmk_repeater_init', '.frmk-validation-repeater', function(){

        var _repeater = $(this);
        if(_repeater.find('.frmk-validation-row').length > 0){
            reIndexRule($(this));
        }
    });

    $(document).on('frmk_repeater_added', '.frmk-validation-repeater', function(){

        var _repeater = $(this);
        var _validation_selector = $(this);

        onRuleTypeChange(_repeater, _validation_selector);

        // trigger that element has been changed
        $(document).trigger('frmk_changed');
    });

    $(document).on('frmk_repeater_removed', '.frmk-validation-repeater', function(){

        var _repeater = $(this);
        var _validation_selector = $(this);

        onRuleTypeChange(_repeater, _validation_selector);

        // trigger that element has been changed
        $(document).trigger('frmk_changed');
    });

    $(document).on('change', '.frmk-validation-repeater .validation_type', function(){

        var _repeater = $(this).parents('.frmk-validation-repeater');
        var _validation_selector = $(this);
        onRuleTypeChange(_repeater, _validation_selector);
    });

})(jQuery);

/**
 * Help Tooltips
 */
(function($) {

    $(document).ready(function(){

        $('.frmk-tooltip, .frmk-tooltip-blank').each(function(){

            if($(this).attr('title').length > 0) {
                $(this).tipTip({
                    defaultPosition: "right"
                });
            }else{
                $(this).hide();
            }
        });

        $('body').on('frmk_element_added', function(event, elem){
           $(elem).find('.frmk-tooltip, .frmk-tooltip-blank').each(function(){

               if($(this).attr('title').length > 0) {
                   $(this).tipTip({
                       defaultPosition: "right"
                   });
               }else{
                   $(this).hide();
               }
           });
        });

    });

})(jQuery);

/**
 * Field Value Repeater
 */
(function($){

    var _setClass = 'frmk-data__key--set';

    var _sanitizeLabel = function(data){
        return data.toLowerCase().replace(/ /g,"_");
    };

    $(document).on('change', '.frmk-field__values .frmk-data__label', function(){

        var _row = $(this).parents('.frmk-repeater-row');
        var _keyField = _row.find('.frmk-data__key');
        var _defaultField = _row.find('.frmk-data__default');

        if(!_keyField.hasClass(_setClass)){

            var _val = _sanitizeLabel( $(this).val());
            _keyField.val(_val);

            if(_defaultField.length > 0) {
                _defaultField.val(_val);
            }
        }

    });

    $(document).on('change', '.frmk-field__values .frmk-data__key', function(){
        if(!$(this).hasClass(_setClass)){
            $(this).addClass(_setClass);
        }
    });

})(jQuery);

/**
 * Iris Colour Picker
 */
(function($){

    $(document).ready(function(){

        $('.frmk-color-picker-input').iris({
            change: function( event, ui ) {
                $( this ).parent().find( '.frmk-color-pick-preview' ).css({ backgroundColor: ui.color.toString() });
            }
        });
        $(document).click(function (e) {
            if ( $('.frmk-color-picker-input').length > 0 && $(".colour-picker, .iris-picker, .iris-picker-inner").is(':visible') && !$(e.target).is(".colour-picker, .iris-picker, .iris-picker-inner")) {
                $('.frmk-color-picker-input').iris('hide');
                return false;
            }
        });
        $('.frmk-color-picker-input, .frmk-color-pick-preview').click(function (event) {
            $('.frmk-color-picker-input').iris('hide');
            $(this).parent().find('.frmk-color-picker-input').iris('show');
            return false;
        });

    });

})(jQuery);

/**
 * Alert when leaving changes
 */
(function($){

    var _changed = false;

    $(document).on('frmk_changed', function(){
        if ( false === _changed ){
            _changed = true;

            // todo: had to be removed due to auto-complete triggering it
            // var wrap = $('#error-wrapper');
            // if ( wrap.length > 0 ) {
            //     wrap.html('<p class="notice notice-warning frmk-notice">You have unsaved changes, to save these click the update button.</p>');
            // }
        }
    });

    $(document).on('click', '.frmk-form-manager--inputs input, .frmk-form-manager--inputs select, .frmk-form-manager--inputs textarea', function(){

        if( $(this).hasClass('frmk-topnav__submit') ){
            return;
        }

        $(document).trigger('frmk_changed');
    });

    $(document).on('keyup change', '.frmk-form-manager--inputs input, .frmk-form-manager--inputs select, .frmk-form-manager--inputs textarea', function(){
        $(document).trigger('frmk_changed');
    });

    /**
     * If submit is clicked, skip onBeforeunload
     */
    $(document).on('click', '.frmk-topnav__submit', function(){
        _changed = false;
    });

    /**
     * Show browser warning
     *
     * @returns {string}
     */
    window.onbeforeunload = function() {

        if ( true === _changed ){
            return "";
        }
    }

    /**
     * Required field validation
     */
    $(document).ready(function(){
       var _form = $('#frmk-form-fields');
       var _error = false;
       if ( _form.length > 0 ){

           $(this).on('submit', function( e ){

               // deactivate all required fields
               $(this).find('.frmk-col--required').removeClass('frmk-col--required');

               $(this).find('.frmk-fields .frmk-input__required').each(function(){

                   if( $(this).val().trim() == "" ){
                       e.preventDefault();
                       _error = true;
                       $(this).parents('.frmk-panel').addClass('frmk-panel--active');
                       $(this).parents('.frmk-col').addClass('frmk-col--required');
                   }
               });

               if(_error) {
                   var wrap = $('#error-wrapper');
                   if (wrap.length > 0) {
                       wrap.html('<p class="notice notice-error frmk-notice">Please make sure you have filled in all required fields before saving, highlighted in red below.</p>');
                   }
               }
           });
       }
    });

})(jQuery);