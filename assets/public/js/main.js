/**
 * Created by james on 01/10/2016.
 */
(function($){

    var field_conditions_met = function(form, conditionArr){

        // loop through all conditional arrays, return true if found
        var found = false;

        $.each(conditionArr, function (index, condition) {

            var equals = condition['operator'] === '!=' ? false : true;

            switch(condition['field_type']){
                case 'checkbox':
                case 'radio':

                    var _elem = form.find('[name="'+condition['field']+'[]"][value="'+condition['value']+'"], [name="'+condition['field']+'"][value="'+condition['value']+'"]');
                    if(_elem !== undefined) {
                        if (( equals && _elem.prop('checked') == true ) || (!equals && _elem.prop('checked') == false)) {
                            found = true;
                            return false;
                        }
                    }

                    break;
                case 'select':

                    //todo: Multi select .val() brings back array
                    var _elem = form.find('select[name="'+condition['field']+'[]"], [name="'+condition['field']+'"]');
                    if(_elem !== undefined){
                        if (( equals && ""+_elem.val() == ""+condition['value'] ) || (!equals && ""+_elem.val() !== ""+condition['value'])) {
                            found = true;
                            return false;
                        }
                    }

                    break;
            }
        });

        return found;
    };

    function init_field_conditions(event, form){

        var _forms = $('.frmk-form');

        if(form != undefined){
            _forms = form;
        }

        _forms.each(function(){
            var _form = $(this);

            // process display data
            var _frmk_display = _form.data('frmk-display');
            if(_frmk_display !== undefined){

                _form.on('input change frmk_display_check', 'input, select', function(){
                    $.each(_frmk_display, function(key, conditionArr){

                        // find either multiple element "name[]", or single element "name"
                        var _elem = _form.find('[name="'+key+'[]"],[name="'+key+'"]');
                        if( field_conditions_met(_form, conditionArr) == true){
                            _elem.parents('.form-row').show();
                        }else{
                            _elem.parents('.form-row').hide();
                        }
                    });
                } );

                // trigger conditional check on all inputs
                _form.find('input, select').trigger('frmk_display_check');
            }
        })
    }

    $('body').on('frmk_form_init', init_field_conditions);

})(jQuery);

(function($){

    function init_range_sliders(event, form){
        var _number_fields = $('.frmk-input-number');
        if(form != undefined){
            form.find('.frmk-input-number');
        }

        if(_number_fields.length == 0){
            return;
        }

        _number_fields.each(function(){

            var _slider = $(this).find('.frmk-range-slider');
            if(_slider.length > 0){
                var _min = _slider.data('min');
                var _max = _slider.data('max');
                var _step = _slider.data('step');
                var _range = _slider.data('range');

                var _config = {
                    min: _min,
                    max: _max,
                    step: _step,
                    values: []
                };

                var inputs = [];

                if ( _range == 'yes' ) {
                    _config.range = true;
                    inputs.push($(this).find('input[name$="[min]"]'));
                    inputs.push($(this).find('input[name$="[max]"]'));

                    _config.values.push(inputs[0].val());
                    _config.values.push(inputs[1].val());

                }else{
                    inputs.push($(this).find('input'));

                    _config.values.push(inputs[0].val());
                }

                // set input values depending on slider type
                _config.slide = function( event, ui ) {
                    if ( _range == 'yes' ) {
                        // ui.values [low, high]
                        inputs[0].val(ui.values[0]);
                        inputs[1].val(ui.values[1]);
                    }else{
                        // ui.value
                        inputs[0].val(ui.value);
                    }
                };

                _slider.slider(_config).each(function() {

                    // Add labels to slider whose values
                    // are specified by min, max

                    // Get the options for this slider (specified above)
                    var opt = $(this).data().uiSlider.options;

                    // Get the number of possible values
                    var vals = opt.max - opt.min;

                    // Position the labels
                    for (var i = 0; i <= vals; i++) {

                        // Create a new element and position it with percentages
                        var el = $('<label>' + (i + opt.min) + '</label>').css('left', (i/vals*100) + '%');

                        // Add the element inside #slider
                        _slider.append(el);

                    }

                });
            }

        });
    }

    $('body').on('frmk_form_init', init_range_sliders);

})(jQuery);

(function($){

    function frmk_ajax_form($form)
    {
        var id = $form.attr('id');
        var iframe;

        if (!$form.attr('target'))
        {
            //create a unique iframe for the form
            iframe = $("<iframe></iframe>").attr('name', 'frmk_ajax_form_' + Math.floor(Math.random() * 999999)).hide().appendTo($('body'));
            $form.attr('target', iframe.attr('name'));
            $form.append('<input type="hidden" name="frmk_ajax" value="iframe" />');
        }

        iframe = iframe || $('iframe[name=" ' + $form.attr('target') + ' "]');
        iframe.load(function ()
        {
            var iframeBody = iframe[0].contentDocument.body,
                json = iframeBody.textContent || iframeBody.innerText;

            json = $.parseJSON(json);
            if(json.status == 'ERR'){

                $form.replaceWith( json.message );

                // re-fetch form
                $form = $('#' + id);

                $form.attr('target', iframe.attr('name'));
                $form.append('<input type="hidden" name="frmk_ajax" value="iframe" />');
                $('body').trigger('frmk_form_init',[ $form ]);

            }else{

                if( json.redirect != undefined ){

                    window.location.replace( json.redirect );

                }else if( json.message != undefined ){

                    $form.replaceWith( json.message );
                    $form.attr('target', iframe.attr('name'));
                    $form.append('<input type="hidden" name="frmk_ajax" value="iframe" />');
                }
            }

        });
    }

    /**
     * Ajax forms
     */
    $(document).ready(function(){

        $('.frmk-form__ajax').each(function(){
            frmk_ajax_form($(this));
        });

        $('body').on('submit', '.frmk-form__ajax', function(){
            $(this).addClass('frmk-ajax-loading');
            $(this).find('.frmk-submit-button').prop('disabled', true);
        });
    });

})(jQuery);

(function($){

    // initialize forms
    $(document).ready(function(){
        $('body').trigger('frmk_form_init');
    });

})(jQuery);
