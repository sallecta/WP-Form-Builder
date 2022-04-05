(function() {
    tinymce.create("tinymce.plugins.frmk_form_btn", {

        //url argument holds the absolute url of our plugin directory
        init : function(editor, url) {

            //add new button
            editor.addButton("frmk", {
                title : "Add Form",
                cmd : "frmk_shortcode"
                // ,
                // image : "https://cdn3.iconfinder.com/data/icons/softwaredemo/PNG/32x32/Circle_Green.png"
            });

            editor.addCommand("frmk_shortcode", function() {

                editor.windowManager.open({
                    title: "Insert Form",
                    width: jQuery( window ).width() * 0.7,
                    // height: (jQuery( window ).height() - 36 - 50) * 0.7,
                    height: 200,
                    inline: 1,
                    id: 'frmk-insert-dialog',
                    buttons: [{
                        text: 'Insert',
                        id: 'frmk-button-insert',
                        class: 'insert',
                        onclick: function( e ) {
                            insertShortcode();
                        }
                    },
                    {
                        text: 'Cancel',
                        id: 'frmk-button-cancel',
                        onclick: 'close'
                    }]
                });

                appendInsertDialog();
            });

            function insertShortcode(){

                var _dialog = jQuery( '#frmk-insert-dialog-body' );

                _dialog.find('#frmk_form_error').hide();

                var _form_select = _dialog.find('#frmk_form_select');
                var _value = _form_select.val();
                var _form_options = _form_select.data('options');

                if( _form_options[_value] !== undefined && _form_options[_value]['type'] !== undefined ){
                    if( _form_options[_value]['type'] == 'form_id'){
                        editor.insertContent( '[wp_form form_id="'+_value+'" ajax="true" /]' );
                    }else{
                        editor.insertContent( '[wp_form form="'+_value+'" ajax="true" /]' );
                    }
                    editor.windowManager.close();
                }else{

                    // Form could not be inserted, something happened
                    var _error = _dialog.find('#frmk_form_error');
                    _error.show();
                    setTimeout(function(){
                        _error.fadeOut();
                    }, 2000);
                }                
            }

        }
    });

    tinymce.PluginManager.add("frmk_form_btn", tinymce.plugins.frmk_form_btn);

    function appendInsertDialog () {
        var dialogBody = jQuery( '#frmk-insert-dialog-body' ).append( '<div class="frmk-dialog-loading">Loading...</div>' );

        // Get the form template from WordPress
        jQuery.post( ajaxurl, {
            action: 'frmk_insert_dialog'
        }, function( response ) {
            template = response;

            dialogBody.children( '.frmk-dialog-loading' ).remove();
            dialogBody.append( template );
            jQuery( '.spinner' ).hide();
        });
    }
})();