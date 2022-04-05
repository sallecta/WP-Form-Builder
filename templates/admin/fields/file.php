<?php
/**
 * File field edit panel
 *
 * @var FRMK_FileField $this
 *
 * @package FRMK/Admin/Field
 * @author James Collings
 * @created 23/11/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="frmk-field-row">
	<div class="frmk-col frmk-col__half">
		<label for="" class="frmk-label"><?php echo esc_html( sprintf( FRMK()->text->get( 'fields.file.max_file_size.label' ) , $this->get_server_limit() ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.file.max_file_size.help' ) ); ?>">?</span></label>
		<input type="number" class="frmk-input" name="field[][max_file_size]" value="<?php echo esc_attr( intval( $this->get_max_filesize() ) ); ?>">
	</div>
	<div class="frmk-col frmk-col__half">
		<label for="" class="frmk-label"><?php echo esc_html( FRMK()->text->get( 'fields.file.allowed_ext.label' ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.file.allowed_ext.help' ) ); ?>">?</span></label>
		<input type="text" class="frmk-input" name="field[][allowed_ext]" value="<?php echo esc_attr( $this->get_allowed_ext() ); ?>">
	</div>
</div>
