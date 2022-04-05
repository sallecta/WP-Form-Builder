<?php
/**
 * Form Style page
 *
 * @var FRMK_DB_Form $form
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 21/11/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$form_id = '';
if ( false !== $form ) {
	$form_id = $form->get_id();
}
?>
<form action="" method="post">

	<input type="hidden" name="frmk-action" value="edit-form-style"/>
	<input type="hidden" name="frmk-form" value="<?php echo esc_attr( $form_id ); ?>"/>
	<div class="frmk-form-manager frmk-form-manager--inputs">

		<?php $this->display_form_header( 'style', $form ); ?>
		<div class="frmk-cols">

			<div class="frmk-full">
				<div class="frmk-left__inside">

					<div id="error-wrapper">
						<?php
						if ( $this->get_success() > 0 ) {
							?>
							<p class="notice notice-success frmk-notice frmk-notice--success"><?php echo esc_html( FRMK()->text->get( 'form_saved', 'general' ) ); ?></p>
							<?php
						}
						?>
					</div>

					<h2 class="frmk-settings__header">
						Form Styles
					</h2>
					<table class="frmk-form-table frmk-form-table--style">

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Background Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[form_bg_colour]', $form->get_style( 'form_bg_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="form_bg_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'form_bg_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[form_text_colour]', $form->get_style( 'form_text_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="form_text_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'form_text_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

					</table>

					<h3 class="frmk-settings__header">
						Form Error Styles
					</h3>
					<table class="frmk-form-table frmk-form-table--style">

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Background Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[form_bg_error_colour]', $form->get_style( 'form_bg_error_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="form_bg_error_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'form_bg_error_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[form_text_error_colour]', $form->get_style( 'form_text_error_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="form_text_error_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'form_text_error_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

					</table>

					<h3 class="frmk-settings__header">
						Form Success Styles
					</h3>
					<table class="frmk-form-table frmk-form-table--style">

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Background Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[form_bg_success_colour]', $form->get_style( 'form_bg_success_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="form_bg_success_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'form_bg_success_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[form_text_success_colour]', $form->get_style( 'form_text_success_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]"
								              value="form_text_success_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'form_text_success_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

					</table>

					<h3 class="frmk-settings__header">
						Field Styles
					</h3>
					<table class="frmk-form-table frmk-form-table--style">

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Label Background Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[field_label_bg_colour]', $form->get_style( 'field_label_bg_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="field_label_bg_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'field_label_bg_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Label Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[field_label_text_colour]', $form->get_style( 'field_label_text_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]"
								              value="field_label_text_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'field_label_text_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Field Background Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[field_input_bg_colour]', $form->get_style( 'field_input_bg_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="field_input_bg_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'field_input_bg_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Field Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[field_input_text_colour]', $form->get_style( 'field_input_text_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]"
								              value="field_input_text_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'field_input_text_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Field Border Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[field_border_colour]', $form->get_style( 'field_border_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="field_border_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'field_border_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Field Border Error Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[field_error_border_colour]', $form->get_style( 'field_error_border_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]"
								              value="field_error_border_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'field_error_border_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Error Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[field_error_text_colour]', $form->get_style( 'field_error_text_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]"
								              value="field_error_text_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'field_error_text_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>


					</table>

					<h3 class="frmk-settings__header">
						Checkbox &amp; Radio Styles
					</h3>
					<table class="frmk-form-table frmk-form-table--style">
						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Option Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[checkbox_text_colour]', $form->get_style( 'checkbox_text_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="checkbox_text_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'checkbox_text_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>
					</table>

					<h3 class="frmk-settings__header">
						Button Styles
					</h3>
					<table class="frmk-form-table frmk-form-table--style">

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Background Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[button_bg_colour]', $form->get_style( 'button_bg_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="button_bg_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'button_bg_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[button_text_colour]', $form->get_style( 'button_text_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="button_text_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'button_text_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Hover Background Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[button_hover_bg_colour]', $form->get_style( 'button_hover_bg_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]" value="button_hover_bg_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'button_hover_bg_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

						<tr>
							<td class="frmk-tooltip__wrapper">
								<label for="form_label">Hover Text Colour</label>
								<span class="frmk-tooltip" title="">?</span>
							</td>
							<td class="">
								<?php frmk_iris_picker( 'frmk_style[button_hover_text_colour]', $form->get_style( 'button_hover_text_colour', true ) ); ?>
							</td>
							<td>
								<label><input type="checkbox" name="frmk_style_disable[]"
								              value="button_hover_text_colour"
								              class="frmk-checkbox" <?php checked( true, $form->is_style_disabled( 'button_hover_text_colour' ), true ); ?>/>
									Disable</label>
							</td>
						</tr>

					</table>

				</div>
			</div>

		</div>

		<div class="frmk-clear"></div>
	</div>
</form>
