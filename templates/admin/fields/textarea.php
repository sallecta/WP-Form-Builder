<?php
/**
 * Textarea edit panel
 *
 * @var FRMK_TextareaField $this
 *
 * @package FRMK/Admin/Field
 * @author James Collings
 * @created 03/11/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="frmk-field-row">
	<div class="frmk-col frmk-col__half">
		<label for="" class="frmk-label">
			<?php echo esc_html( FRMK()->text->get( 'fields.textarea.default.label' ) ); ?>
			<span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.textarea.default.help' ) ); ?>">?</span>
		</label>

		<input type="text" class="frmk-input" name="field[][rows]" value="<?php echo esc_attr( $this->get_rows() ); ?>">
	</div>
</div>
<div class="frmk-field-row">
	<div class="frmk-col frmk-col__full">
		<label for="" class="frmk-label"><?php echo esc_html( FRMK()->text->get( 'fields.textarea.rows.label' ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.textarea.rows.help' ) ); ?>">?</span></label>
		<textarea class="frmk-input" name="field[][default]"><?php echo esc_textarea( $this->get_default_value() ); ?></textarea>
	</div>
</div>
