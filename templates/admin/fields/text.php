<?php
/**
 * Text edit panel
 *
 * @var FRMK_TextField $this
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
		<label for="" class="frmk-label"><?php echo esc_html( FRMK()->text->get( 'fields.text.default.label' ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.text.default.help' ) ); ?>">?</span></label>
		<input type="text" class="frmk-input" name="field[][default]" value="<?php echo esc_attr( $this->get_default_value() ); ?>">
	</div>
</div>
