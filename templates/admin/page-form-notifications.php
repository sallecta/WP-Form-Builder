<?php
/**
 * Form notifications page
 *
 * @var FRMK_Form $form
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 12/10/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$form_id = '';
if ( false !== $form ) {
	$form_id = $form->get_id();
}

$fields    = $form->get_fields();
$field_keys = array();
if ( ! empty( $fields ) ) {
	foreach ( $fields as $field_id => $field ) {
		$field_keys[] = sprintf( '%s <code>{{field_%s}}</code>', esc_html( $field->get_label() ), esc_html( $field_id ) );
	}
}

$settings          = $form->export();
$blank_notification = array(
	'to'      => '',
	'subject' => '',
	'message' => '',
	'from'    => '',
	'cc'      => '',
	'bcc'     => '',
);
$notifications     = isset( $settings['notifications'] ) && ! empty( $settings['notifications'] ) ? $settings['notifications'] : array( $blank_notification );
?>
<form action="" method="post">

	<input type="hidden" name="frmk-action" value="edit-form-notifications"/>
	<input type="hidden" name="frmk-form" value="<?php echo esc_attr( $form_id ); ?>"/>
	<div class="frmk-form-manager frmk-form-manager--inputs">

		<?php $this->display_form_header( 'notifications', $form ); ?>

		<div class="frmk-cols">

			<div class="frmk-full">

				<div class="frmk-left__inside  frmk-repeater" data-min="0" data-template-name="notification_repeater"
				     data-template-index="notification\[[0-9]*\]" data-template-prefix="notification">

					<div id="error-wrapper">
						<?php
						if ( $this->get_success() > 0 ) {
							?>
							<p class="notice notice-success frmk-notice frmk-notice--success"><?php echo esc_html( FRMK()->text->get( 'form_saved', 'general' ) ); ?></p>
							<?php
						}
						?>
					</div>

					<ul class="frmk-notifications frmk-repeater-container">
						<script type="text/html" class="frmk-repeater-template">
							<?php frmk_display_notification_settings( $blank_notification, '', $field_keys ); ?>
						</script>
						<?php foreach ( $notifications as $i => $notification ) : ?>
							<?php frmk_display_notification_settings( $notification, $i, $field_keys ); ?>
						<?php endforeach; ?>
					</ul>

					<a href="#" class="frmk-add-row button button-primary">Add Notification</a>
					&nbsp;
				</div>
			</div>

		</div>

		<div class="frmk-clear"></div>
	</div>
</form>
