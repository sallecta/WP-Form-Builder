<?php
/**
 * Form Functions
 *
 * @package FRMK
 * @author James Collings
 * @created 04/08/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get uploads directory path
 *
 * @return string
 */
function frmk_get_uploads_dir() {

	$upload_dir = WP_CONTENT_DIR . '/uploads/';
	if ( ! file_exists( $upload_dir ) ) {
		mkdir( $upload_dir );
	}

	$upload_dir = WP_CONTENT_DIR . '/uploads/frmk/';
	if ( ! file_exists( $upload_dir ) ) {
		mkdir( $upload_dir );
	}

	return $upload_dir;
}

/**
 * Get uploads directory url
 *
 * @return string
 */
function frmk_get_uploads_url() {
	return content_url( '/uploads/frmk/' );
}

/**
 * Check if form is submitted form.
 *
 * @param string $form_id Form id.
 *
 * @return bool
 */
function frmk_is_submitted_form( $form_id ) {
	$form = FRMK()->get_current_form();
	if ( false !== $form && $form_id === $form->get_name() ) {
		return true;
	}
	return false;
}

/**
 * Register form
 *
 * @param string $name Form name.
 * @param array  $args Form arguments.
 *
 * @return FRMK_Form
 */
function frmk_register_form( $name, $args = array() ) {
	return FRMK()->register_form( $name, $args );
}

/**
 * Get form
 *
 * @param string $name Form name.
 *
 * @return FRMK_Form
 */
function frmk_get_form( $name ) {
	return FRMK()->get_form( $name );
}

/**
 * Display default output of form
 *
 * @param string $name Form name.
 * @param array $args
 */
function frmk_display_form( $name, $args = array() ) {

	$form = frmk_get_form( $name );

	$form->start($args);

	if ( $form->is_complete() ) {

		// display successful message.
		frmk_display_confirmation( $form );

	} else {

		// display form errors if any.
		if ( $form->has_errors() ) {
			$form->errors();
		}

		$fields = $form->get_fields();
		foreach ( $fields as $field_id => $field ) {
			frmk_display_field( $form, $field_id );
		}

		$form->submit();
	}

	$form->end($args);
}

/**
 * Display field
 *
 * @param FRMK_Form $form Form object.
 * @param string    $field_id Form field.
 */
function frmk_display_field( $form, $field_id ) {

	if ( ! $form->has_valid_token() ) {
		return;
	}

	$id = $form->get_field( $field_id )->get_input_name() . '_' . $form->get_id();
	?>
	<div id="<?php echo esc_attr( $id ); ?>" class="frmk-form-row <?php $form->classes( $field_id, 'validation' ); ?> <?php $form->classes( $field_id, 'type' ); ?>">
		<div class="frmk-label"><?php $form->label( $field_id ); ?></div>
		<div class="frmk-input"><?php $form->input( $field_id ); ?></div>
		<div class="ajax_field_error"><?php $form->error( $field_id ); ?></div>
	</div>
	<?php
}

/**
 * Display form confirmation
 *
 * @param FRMK_Form $form Form object.
 */
function frmk_display_confirmation( $form ) {
	?>
	<div class="frmk-form-confirmation">
		<p><?php echo esc_html( $form->get_confirmation_message() ); ?></p>
	</div>
	<?php
}

/**
 * Get user ip
 *
 * @return string
 */
function frmk_get_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}

/**
 * Override display of file
 *
 * @param string $value
 * @param string $field_id
 * @param FRMK_FormData $data
 *
 * @return mixed
 */
function frmk_file_field_value($value, $field_id, $data){

	$field = $data->get_field($field_id);
	if ( $field->is_type( 'file' ) ) {
		$link = trailingslashit( $data->get_upload_folder() ) . $value;
		$value = frmk_get_uploads_url() . $link;
	}

	return $value;
}
add_filter( 'frmk/display_field_value', 'frmk_file_field_value', 10, 3 );