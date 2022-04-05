<?php
/**
 * Display form field panel
 *
 * @var FRMK_FormField|string $field
 * @var FRMK_Form $form
 * @var bool $active
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 12/10/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$field_type = $field->get_type();
?>
<div class="frmk-panel frmk-panel--white <?php echo true === $active ? 'frmk-panel--active' : ''; ?>" data-field-type="<?php echo esc_attr( $field_type ); ?>">
	<div class="frmk-panel__header">
		<?php echo esc_html( ucfirst( $field_type ) ); ?>: <span class="name"><?php echo esc_html( $field->get_label() ); ?></span>
		<a class="frmk-tooltip frmk-panel__delete frmk-del-field" title="<?php echo esc_attr( FRMK()->text->get( 'fields.general.delete.help' ) ); ?>"><?php esc_html_e( 'Delete', 'frmk' ); ?></a>
		<a href="#" class="frmk-panel__toggle frmk-tooltip-blank" title="<?php echo esc_attr( FRMK()->text->get( 'fields.general.toggle.help' ) ); ?>"></a>
	</div>
	<div class="frmk-panel__content">

		<?php
		// hidden fields.
		?>
		<input type="hidden" name="field[][type]" value="<?php echo esc_attr( $field_type ); ?>" />
		<input type="hidden" name="field[][id]" value="<?php echo esc_attr( $field->get_name() ); ?>" />

		<?php
		// general fields.
		?>
		<div class="frmk-field-row">
			<div class="frmk-col frmk-col__half">
				<label for="" class="frmk-label">
					<?php echo esc_html( FRMK()->text->get( 'fields.general.label.label' ) ); ?>
					<span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.general.label.help' ) ); ?>">?</span>
				</label>

				<input type="text" class="frmk-input frmk-input--label frmk-input__required" name="field[][label]" value="<?php echo esc_attr( $field->get_label() ); ?>">
			</div>
			<div class="frmk-col frmk-col__half">
				<label for="" class="frmk-label">
					<?php echo esc_html( FRMK()->text->get( 'fields.general.placeholder.label' ) ); ?>
					<span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.general.placeholder.help' ) ); ?>">?</span>
				</label>
				<input type="text" class="frmk-input" name="field[][placeholder]" value="<?php echo esc_attr( $field->get_placeholder() ); ?>">
			</div>
		</div>

		<div class="frmk-field-row">
			<div class="frmk-col frmk-col__half">
				<label for="" class="frmk-label">
					<?php echo esc_html( FRMK()->text->get( 'fields.general.css_classes.label' ) ); ?>
					<span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.general.css_classes.help' ) ); ?>">?</span>
				</label>

				<input type="text" class="frmk-input" name="field[][css_class]" value="<?php echo esc_attr( $field->get_extra_classes() ); ?>">
			</div>
		</div>

		<?php
		// specific fields based on field type.
		$field->display_settings();

		// validation fields.
		?>
		<div class="frmk-clear"></div>
		<div class="frmk-repeater frmk-validation-repeater" data-min="0" data-template-name="validation_repeater">
			<div class="field-values__header">
			<strong><?php echo esc_html( FRMK()->text->get( 'fields.validation.heading.text' ) ); ?> <span class="frmk-tooltip frmk-tooltip__inline" title="<?php echo esc_attr( FRMK()->text->get( 'fields.validation.heading.help' ) ); ?>">?</span></strong>
			</div>
			<div class="frmk-repeater-container">
				<script type="text/html" class="frmk-repeater-template">
					<?php frmk_display_validation_block(); ?>
				</script>
				<?php
				// load saved validation rules.
				if ( $form ) {
					$rules = $form->get_validation_rules();
					if ( isset( $rules[ $field->get_name() ] ) && ! empty( $rules[ $field->get_name() ] ) ) {
						foreach ( $rules[ $field->get_name() ] as $rule ) {
							$type = $rule['type'];
							frmk_display_validation_block( $type, $rule );
						}
					}
				}
				?>
			</div>
			<a href="#" class="frmk-add-row button button-primary"><?php echo esc_html( FRMK()->text->get( 'fields.validation.button.text' ) ); ?></a>
		</div>
		<?php
		// add-on fields.
		?>
		<div class="frmk-clear"></div>
	</div>
</div>
