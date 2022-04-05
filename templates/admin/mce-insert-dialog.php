<?php
/**
 * Tiny mce form selector modal
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 20/11/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$forms = FRMK()->get_forms();

$options = array();
if ( ! empty( $forms ) ) {
	foreach ( $forms as $form_id => $form ) {

		if ( is_array( $form ) ) {
			$form = FRMK()->get_form( $form_id );
		}

		$id = $form->get_id();
		if ( $id ) {
			$options[ $id ] = array(
				'label' => $form->get_label(),
				'type'  => 'form_id',
			);
		} else {
			$options[ $form->get_name() ] = array(
				'label' => $form->get_label(),
				'type'  => 'form',
			);
		}
	}
}
?>

<div class="frmk-dialog">
	<?php if ( empty( $options ) ) : ?>
		<div id="frmk_form_error" style="display:block;">
			<p><?php echo esc_html( FRMK()->text->get( 'empty_forms', 'shortcode_error' ) ); ?></p>
		</div>
	<?php else : ?>
		<div id="frmk_form_error" style="display:none;">
			<p><?php echo esc_html( FRMK()->text->get( 'selection', 'shortcode_error' ) ); ?></p>
		</div>

		<table class="frmk-dialog-table">
			<tr>
				<th class="notification__label">
					<label for="frmk_form_select"><?php esc_html_e( 'Form', 'frmk' ); ?>:</label>
				</th>
				<td class="notification__input">
					<select name="frmk_form_select" id="frmk_form_select"
					        data-options='<?php echo esc_attr( wp_json_encode( $options ) ); ?>'>
						<?php
						foreach ( $options as $id => $option ) :
							?>
							<option value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $option['label'] ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
	<?php endif; ?>
</div>



