<?php
/**
 * Checkbox field edit panel
 *
 * @var FRMK_CheckboxField $this
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
	<div class="frmk-col frmk-col__full">

		<div class="field-values__header">
			<strong><?php echo esc_html( FRMK()->text->get( 'fields.checkbox.values.heading.label' ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.heading.help' ) ); ?>">?</span></strong>
		</div>

		<table width="100%" class="frmk-repeater frmk-field__values" data-min="1" data-template-name="field_value_repeater_checkbox">
			<thead>
			<tr>
				<th><?php echo esc_html( FRMK()->text->get( 'fields.checkbox.values.label.label' ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.label.help' ) ); ?>">?</span></th>
				<th><?php echo esc_html( FRMK()->text->get( 'fields.checkbox.values.value.label' ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.value.help' ) ); ?>">?</span></th>
				<th><?php echo esc_html( FRMK()->text->get( 'fields.checkbox.values.default.label' ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.default.help' ) ); ?>">?</span></th>
				<th>_</th>
			</tr>
			</thead>
			<tbody class="frmk-repeater-container">
			<script type="text/html" class="frmk-repeater-template">
				<tr class="frmk-repeater-row frmk-repeater__template">
					<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.label.label' ) ); ?>" type="text" class="frmk-input frmk-data__label" name="field[][value_labels][]"></td>
					<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.value.label' ) ); ?>" type="text" class="frmk-input frmk-data__key" name="field[][value_keys][]"></td>
					<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.default.label' ) ); ?>" type="checkbox" name="field[][value_default][]"></td>
					<td>
						<a href="#" class="frmk-add-row button">+</a>
						<a href="#" class="frmk-del-row button">-</a>
					</td>
				</tr>
			</script>
			<?php
			$options = $this->get_options();
			if ( ! empty( $options ) ) {
				foreach ( $options as $key => $option ) {
					?>
					<tr class="frmk-repeater-row frmk-repeater__template">
						<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.label.label' ) ); ?>" type="text" class="frmk-input frmk-data__label"
						           name="field[][value_labels][]" value="<?php echo esc_attr( $option ); ?>" /></td>
						<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.value.label' ) ); ?>" type="text" class="frmk-input frmk-data__key"
						           name="field[][value_keys][]" value="<?php echo esc_attr( $key ); ?>" /></td>
						<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.default.label' ) ); ?>" type="checkbox"
						           name="field[][value_default][]" <?php
									$default = $this->get_default_value();
									if ( is_array( $default ) ) {
										checked( in_array( $key, $default, true ), true, true );
									} else {
										checked( $key, $default, true );
									}
								?> /></td>
						<td>
							<a href="#" class="frmk-add-row button">+</a>
							<a href="#" class="frmk-del-row button">-</a>
						</td>
					</tr>
					<?php
				}
			} else {
				$defaults = array(
					'one' => 'Option One',
					'two' => 'Option Two',
					'three' => 'Option Three',
				);
				foreach ( $defaults as $value => $label ) : ?>
				<tr class="frmk-repeater-row frmk-repeater__template">
					<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.label.label' ) ); ?>" type="text" class="frmk-input frmk-data__label" name="field[][value_labels][]" value="<?php echo esc_attr( $label ); ?>"></td>
					<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.value.label' ) ); ?>" type="text" class="frmk-input frmk-data__key" name="field[][value_keys][]" value="<?php echo esc_attr( $value ); ?>"></td>
					<td><input title="<?php echo esc_attr( FRMK()->text->get( 'fields.checkbox.values.default.label' ) ); ?>" type="checkbox" name="field[][value_default][]"></td>
					<td>
						<a href="#" class="frmk-add-row button">+</a>
						<a href="#" class="frmk-del-row button">-</a>
					</td>
				</tr>
				<?php
				endforeach;
			}
			?>
			</tbody>
		</table>

	</div>
</div>
