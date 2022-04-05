<?php
/**
 * Form fields page
 *
 * @var FRMK_Form $form
 * @var FRMK_Admin $this
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 12/10/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$available_fields = array( 'text', 'textarea', 'number', 'select', 'checkbox', 'radio', 'file' );
$form_id          = '';
$fields           = array();
if ( false !== $form ) {
	$form_id = $form->get_id();
	$fields  = $form->get_fields();
}
?>
	<form id="frmk-form-fields" action="" method="post">

		<input type="hidden" name="frmk-action" value="edit-form-fields"/>
		<input type="hidden" name="frmk-form" value="<?php echo esc_attr( $form_id ); ?>"/>
		<div class="frmk-form-manager frmk-form-manager--inputs">

			<?php $this->display_form_header( 'fields', $form ); ?>
			<div class="frmk-cols">
				<div class="frmk-left">
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

						<div class="frmk-fields">
							<ul id="sortable">

								<li class="placeholder"
								    <?php if ( ! empty( $fields ) ) : ?>style="display: none;"<?php endif; ?>>Drop field
									here to add to the form
								</li>


								<?php
								if ( ! empty( $fields ) ) :
									foreach ( $fields as $field ) :
										?>
										<li class="ui-state-highlight ui-draggable ui-draggable-handle frmk-dropped-item"
										    data-field="text"
										    style="width: auto; height: auto; right: auto; bottom: auto;">
											<?php $this->display_field_panel( $field, $form ); ?>
										</li>
										<?php
									endforeach;
								endif;
								?>
							</ul>
						</div>
					</div>

				</div>
				<div class="frmk-right">
					<div class="frmk-right__inside">
						<div class="frmk-panel frmk-panel--active">
							<div class="frmk-panel__header">
								<p class="frmk-panel__title">Available Fields <span
											class="frmk-tooltip frmk-tooltip__inline"
											title="Hover over the type of field you want to add and drag into the left dropzone.">?</span>
								</p>
							</div>
							<div class="frmk-panel__content">
								<ul class="frmk-field-list">
									<?php foreach ( $available_fields as $field ) : ?>
										<li class="draggable ui-state-highlight" data-field="<?php echo esc_attr( $field ); ?>"><a
													href="#"><?php echo esc_html( ucfirst( $field ) ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="frmk-clear"></div>
		</div>
	</form>

	<div id="field-placeholder" style="display:none;">
		<?php
		foreach ( $available_fields as $field ) {
			$this->display_field_panel( $field );
		}
		?>
	</div>
<?php
$rules = frmk_get_validation_rules();
foreach ( $rules as $rule_id => $rule_label ) : ?>
	<script type="text/html" class="frmk-validation__rule" data-rule="<?php echo esc_attr( $rule_id ); ?>">
		<?php frmk_display_validation_block_section( $rule_id ); ?>
	</script>
<?php endforeach; ?>
