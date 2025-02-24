<?php
/**
 * Core Form Class
 *
 * @package FRMK
 * @author James Collings
 * @created 06/08/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class FRMK_Form
 */
class FRMK_Form {

	/**
	 * List of form templates
	 *
	 * @var array
	 */
	protected $_templates = null;

	/**
	 * List of form messages
	 *
	 * @var array
	 */
	protected $_messages = null;

	/**
	 * Form data
	 *
	 * @var FRMK_FormData
	 */
	protected $_data = null;

	/**
	 * Form id
	 *
	 * @var int
	 */
	protected $ID = null;

	/**
	 * Form name
	 *
	 * @var string
	 */
	protected $_name = null;

	/**
	 * Form content
	 *
	 * @var string
	 */
	protected $_content = null;

	/**
	 * Individual errors
	 *
	 * @var array
	 */
	protected $_errors = null;

	/**
	 * Form error
	 *
	 * @var string
	 */
	protected $_error = false;

	/**
	 * Form validation rules
	 *
	 * @var FRMK_Validation
	 */
	protected $_validation = null;

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	protected $_rules = null;

	/**
	 * Form submitted flag
	 *
	 * @var bool
	 */
	protected $_submitted = false;

	/**
	 * Form session token
	 *
	 * @var bool
	 */
	protected $_token = false;

	/**
	 * Form settings
	 *
	 * @var array
	 */
	protected $_settings = null;

	/**
	 * Default form settings
	 *
	 * @var array
	 */
	protected $_settings_default = array();

	/**
	 * Form confirmations
	 *
	 * @var array
	 */
	protected $_confirmation = array();

	/**
	 * Location to display form confirmation message
	 *
	 * @var string
	 */
	protected $_confirmation_location = 'after';

	/**
	 * Flag to see if form has file field
	 *
	 * @var bool
	 */
	protected $_has_file_field = false;

	/**
	 * List of form notifications
	 *
	 * @var FRMK_Notification[]
	 */
	protected $_notifications = null;

	/**
	 * Form email manager
	 *
	 * @var FRMK_EmailManager
	 */
	protected $_email_manager = null;

	/**
	 * List of form fields
	 *
	 * @var FRMK_FormField[]
	 */
	protected $_fields = array();

	/**
	 * Field display conditions
	 *
	 * @var array
	 */
	protected $_field_display_conds = array();

	/**
	 * Is request ajax
	 *
	 * @var bool
	 */
	protected $_is_ajax = false;

	/**
	 * FRMK_Form constructor.
	 *
	 * @param string $name Form name.
	 * @param array $fields Form fields.
	 * @param array $settings
	 */
	public function __construct( $name, $fields = array(), $settings = array() ) {

		// Setup default values.
		$this->_name             = $name;
		$this->_fields           = array();
		$this->_errors           = array();
		$this->_rules            = array();
		$this->_notifications    = array();
		$this->_settings_default = array(
			'database'          => 'yes',
			'labels'            => array(
				'submit' => __( 'Send', 'frmk' ),
			),
			'enable_layout_css' => 'enabled',
			'enable_style'      => 'enabled',
			'error' => array(
				'general_message' => 'Please make sure you have corrected any errors below before resubmitting the form.',
				'show_fields' => 'yes',
			),
		);
		$this->_confirmation     = array(
			'type'    => 'message',
			'message' => __( 'Form has been successfully submitted!', 'frmk' ),
		);

		// set ajax
		$this->_is_ajax = isset( $_POST['frmk_ajax'] ) && 'iframe' === $_POST['frmk_ajax'] ? true : false;

		// load default settings.
		$this->_settings = $this->_settings_default;
		if ( ! empty( $settings ) ) {
			$this->settings( $settings );
		}
		$this->_settings = apply_filters( 'frmk/form_settings', $this->_settings, $this->get_id() );

		// mark form as submitted.
		$this->_submitted = isset( $_POST['frmk_action'] ) && $_POST['frmk_action'] == $this->get_name() ? true : false;
		if ( $this->_submitted ) {
			$this->get_token( false );
		}

		// setup fields.
		if ( ! empty( $fields ) ) :
			foreach ( $fields as $field_id => $field ) :

				$type = $field['type'];
				switch ( $type ) {
					case 'text':
						$this->_fields[ $field_id ] = new FRMK_TextField( $field_id, $type, $field );
						break;
					case 'textarea':
						$this->_fields[ $field_id ] = new FRMK_TextareaField( $field_id, $type, $field );
						break;
					case 'select':
						$this->_fields[ $field_id ] = new FRMK_SelectField( $field_id, $type, $field );
						break;
					case 'radio':
						$this->_fields[ $field_id ] = new FRMK_RadioField( $field_id, $type, $field );
						break;
					case 'checkbox':
						$this->_fields[ $field_id ] = new FRMK_CheckboxField( $field_id, $type, $field );
						break;
					case 'file':
						$this->_fields[ $field_id ] = new FRMK_FileField( $field_id, $type, $field );
						break;
					case 'number':
						$this->_fields[ $field_id ] = new FRMK_NumberField( $field_id, $type, $field );
						break;
				}

				if ( 'file' === $type ) {
					$this->_has_file_field = true;
				}

				// get display rules.
				$this->extract_display_conditions( $field_id, $field );

				// add validation rules to rules array.
				if ( isset( $field['validation'] ) && ! empty( $field['validation'] ) ) {

					/**
					 * Validation should end up like:
					 * [ [ 'type' => 'required'], ['type' => 'email'] ].
					 */
					if ( is_array( $field['validation'] ) ) {

						if ( isset( $field['validation']['type'] ) ) {
							$rule = array(
								$field['validation']
							);
						} else {
							$rule = $field['validation'];
						}
					} else {
						// if only validation type string was give, convert to full.
						$rule = array(
							array(
								'type' => $field['validation'],
							),
						);
					}

					// todo: check to see if this is a valid rule.
					$this->_rules[ $field_id ] = $rule;
				}
			endforeach;
		endif;

		// now all fields have been initialized.
		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field_id => $field ) {

				// setup display conditions.
				$this->extract_display_conditions( $field_id, $field );
			}
		}

		$this->_data = new FRMK_FormData( $this);

		do_action('frmk/form_init', $this);
	}

	/**
	 * Build array of field display conditions
	 *
	 * @param string $field_id String name of field.
	 * @param array  $field Field name.
	 */
	private function extract_display_conditions( $field_id, $field ) {

		if ( isset( $field['display_conditions'] ) && is_array( $field['display_conditions'] ) && ! empty( $field['display_conditions'] ) ) {

			$name                                = $this->_fields[ $field_id ]->get_input_name();
			$this->_field_display_conds[ $name ] = array();

			foreach ( $field['display_conditions'] as $f => $v ) {

				$operator = '=';
				$value    = $v;

				if ( is_array( $v ) ) {
					$operator = isset( $v['operator'] ) && '!=' === $v['operator'] ? '!=' : $operator;
					$value    = isset( $v['value'] ) ? $v['value'] : $value;
				}

				$target                                = $this->_fields[ $f ];
				$this->_field_display_conds[ $name ][] = array(
					'field'      => $target->get_input_name(),
					'field_type' => $target->get_type(),
					'operator'   => $operator,
					'value'      => $value,
				);
			}
		}
	}

	/**
	 * Process form submission request
	 */
	public function process() {

		$this->_data->form_data($_POST, $_FILES);

		if ( ! wp_verify_nonce( $_POST['frmk_nonce'], 'frmk_submit_form_' . $this->get_name() ) ) {
			$this->set_error( __( 'An Error occurred when submitting the form, please retry.', 'frmk' ) );
		}

		if ( intval( $_SERVER['CONTENT_LENGTH'] ) > 0 && count( $_POST ) === 0 ) {
			$this->set_error( __( 'An Error occurred: PHP discarded POST data because of request exceeding post_max_size.', 'frmk' ) );
		}

		// clear data array.
		$this->_submitted  = true;
		$this->_validation = new FRMK_Validation( $this->_rules );

		// make sure valid token is present.
		$token = $this->get_token( false );
		if ( ! isset( $token ) || ! $this->verify_token( $token ) ) {
			$this->set_error( __( 'Your session has expired', 'frmk' ) );
		}

		$form_data = $this->_data->to_array();

		foreach ( $this->_fields as $field_id => $field ) {

			if ( $field->is_type( 'file' ) ) {

				// check for file upload errors, before checking against field validation rules.
				if ( isset( $_FILES[ $field->get_input_name() ] ) ) {
					$file_data = $_FILES[ $field->get_input_name() ];

					if ( UPLOAD_ERR_NO_FILE !== $file_data['error'] && ! $field->is_valid_ext( $file_data ) ) {
						$this->_errors[ $field_id ] = FRMK()->text->get( 'invalid_ext', 'upload' );
					} elseif ( UPLOAD_ERR_NO_FILE !== $file_data['error'] && ! $field->is_allowed_size( $file_data ) ) {
						$this->_errors[ $field_id ] = sprintf( FRMK()->text->get( 'max_size', 'upload' ), $field->get_max_filesize() );
					} elseif ( UPLOAD_ERR_OK !== $file_data['error'] && UPLOAD_ERR_NO_FILE !== $file_data['error'] ) {
						$this->_errors[ $field_id ] = $this->_validation->get_upload_error( $file_data['error'], $field->get_max_filesize() );
					}
				}
			}

			if ( ! isset( $this->_errors[ $field_id ] ) && ! $this->_validation->validate( $field, $form_data ) ) {
				$this->_errors[ $field_id ] = $this->_validation->get_error();
			}
		}

		if ( ! $this->has_errors() ) :

			// validate reCaptcha.
			if ( ! $this->verify_recaptcha() ) {
				$this->set_error( __( "The reCAPTCHA wasn't entered correctly. Go back and try it again.", 'frmk' ) );
				return;
			}

			$submit = apply_filters( 'frmk/process_form', true, $this, $this->_data );
			if ( ! $submit ) {
				return;
			}

			do_action( 'frmk/form_processed', $this, $this->_data);

			// store form data in database.
			if ( $this->get_setting( 'database' ) === 'yes' && ! defined( 'FRMK_PREVIEW' ) ) {
				$db = new FRMK_DatabaseManager();
				$db->save_entry( $this->get_name(), $this->_data );
			}

			// send email notifications with template tags.
			if ( ! empty( $this->_notifications ) ) {
				$this->_email_manager = new FRMK_EmailManager( $this, $this->_notifications );
				$this->_email_manager->send( $this->_data );
			}

			// redirect now if needed, but not if ajax.
			if ( 'redirect' === $this->_confirmation['type'] && ! $this->is_ajax() ) {
				wp_redirect( $this->_confirmation['redirect_url'] );
				exit;
			}

			$this->clear_token();

			// on form complete.
			do_action( 'frmk/form_complete', $this, $this->_data );
		endif;
	}

	public function load_submission($submission_id){

		$db          = new FRMK_DatabaseManager();
		$submission_data = $db->get_submission_data( $submission_id );
		$this->_data->submission_data($submission_data);
	}

	/**
	 * Load form settings
	 *
	 * @param array $settings Form settings.
	 */
	public function settings( $settings ) {

		$this->_settings = array_replace_recursive( $this->_settings, $settings );
	}

	/**
	 * Get form settings
	 *
	 * @param string $setting Settings key.
	 * @param string $section Settings section key.
	 *
	 * @return bool|mixed
	 */
	public function get_setting( $setting, $section = '' ) {

		if ( ! empty( $section ) && isset( $this->_settings[ $section ] ) ) {
			return isset( $this->_settings[ $section ][ $setting ] ) ? $this->_settings[ $section ][ $setting ] : false;
		}

		return isset( $this->_settings[ $setting ] ) ? $this->_settings[ $setting ] : false;
	}

	/**
	 * Add form confirmation
	 *
	 * @param string $type Confirmation type.
	 * @param string $value Conformation message / redirect url based on type.
	 *
	 * @return bool
	 */
	public function add_confirmation( $type, $value ) {

		if ( 'redirect' === $type ) {

			$this->_confirmation = array(
				'type'         => 'redirect',
				'redirect_url' => $value,
			);

			return true;

		} elseif ( 'message' === $type ) {

			$this->_confirmation = array(
				'type'    => 'message',
				'message' => $value,
			);

			return true;
		}

		return false;
	}

	/**
	 * Add form notification
	 *
	 * @param string $to Email recipients.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param array  $args Extra email arguments such as cc, bcc.
	 */
	public function add_notification( $to, $subject, $message, $args = array() ) {

		$notification = array(
			'to'      => $to,
			'subject' => $subject,
			'message' => $message,
		);

		if ( isset( $args['cc'] ) ) {
			$notification['cc'] = $args['cc'];
		}

		if ( isset( $args['bcc'] ) ) {
			$notification['bcc'] = $args['bcc'];
		}

		if ( isset( $args['from'] ) ) {
			$notification['from'] = $args['from'];
		}

		if ( isset( $args['conditions'] ) ) {
			$notification['conditions'] = $args['conditions'];
		}

		$this->_notifications[] = new FRMK_Notification( $notification );

	}

	/**
	 * Add field error
	 *
	 * @param string $field Field id.
	 * @param string $message Field message.
	 */
	public function add_field_error( $field, $message ) {
		$this->_errors[ $field ] = $message;
	}

	/**
	 * Add form error
	 *
	 * @param string $message Error message.
	 */
	public function add_error( $message ) {
		$this->_error = $message;
	}

	/**
	 * Display form opening tag
	 *
	 * @param array $args Form opening tag arguments.
	 */
	public function start( $args = array() ) {

		// if is file upload form need to add.
		$attrs = ' method="post"';
		if ( $this->_has_file_field ) {
			$attrs .= ' enctype="multipart/form-data"';
		}

		if ( isset( $args['id'] ) ) {
			$attrs .= sprintf( ' id="%s"', esc_attr( $args['id'] ) );

			if ( ! isset( $args['action'] ) ) {
				$attrs .= sprintf( ' action="#%s"', esc_attr( $args['id'] ) );
			}
		}else{
			$attrs .= sprintf( ' id="frmk_form_%s"', esc_attr( $this->get_id() ) );
		}

		if ( isset( $args['action'] ) ) {
			$attrs .= sprintf( ' action="#%s"', esc_attr( $args['action'] ) );
		}

		$classes = 'frmk-form ';

		if ( isset( $args['class'] ) ) {
			$classes .= esc_attr( $args['class'] );
		}

		if( $args['ajax'] === true){
			$classes .= 'frmk-form__ajax';
		}

		if ( 'enabled' === $this->get_setting( 'enable_layout_css' ) ) {
			$classes .= ' frmk-form__layout';
		}

		$attrs .= sprintf( ' class="%s"', esc_attr( $classes ) );

		// todo: output js data attributes.
		if ( ! empty( $this->_field_display_conds ) ) {
			$attrs .= sprintf( " data-frmk-display='%s'", json_encode( $this->_field_display_conds ) );
		}

		echo '<form ' . $attrs . '>';
		?>
		<?php if($args['title'] === true): ?>
		<div class="frmk-form-title">
			<h1><?php echo esc_html( $this->get_label() ); ?></h1>
		</div>
		<?php endif; ?>
		<div class="frmk-form-copy">
			<?php echo wpautop( esc_html( $this->get_content() ) ); ?>
		</div>
		<?php
	}

	/**
	 * Get field name
	 *
	 * @param string $field_id Field id.
	 *
	 * @return bool|string
	 */
	public function get_field_name( $field_id ) {

		$field = isset( $this->_fields[ $field_id ] ) ? $this->_fields[ $field_id ] : false;
		if ( $field ) {
			return $field->get_input_name();
		}

		return false;
	}

	/**
	 * Get field value
	 *
	 * @param string $field_id Field id.
	 * @param bool   $default Flag to return default value or current value.
	 *
	 * @return string
	 */
	public function get_field_value( $field_id, $default = false ) {

		$data = $this->_data->get( $field_id );
		if ( $data ) {
			return $data;
		}

		return $default;
	}

	/**
	 * Get list of validation rules
	 *
	 * @return array
	 */
	public function get_validation_rules() {
		return $this->_rules;
	}

	/**
	 * Get form data
	 *
	 * @return FRMK_FormData
	 */
	public function get_data(){
		return $this->_data;
	}

	/**
	 * Display form field input
	 *
	 * @param string $name Input id.
	 */
	public function input( $name ) {

		$field = isset( $this->_fields[ $name ] ) ? $this->_fields[ $name ] : false;
		if ( $field ) {
			$field->output( $this->_data );
		}
	}

	/**
	 * Get form field label
	 *
	 * @param string $name Field id.
	 */
	public function label( $name ) {
		$required = '';
		if ( $this->field_has_validation( $name ) ) {
			$required_text = apply_filters('frmk/field_required_text', '*', $this, $name);
			$required = ' <span>' . esc_html( $required_text ) . '</span>';
		}

		echo '<label for="' . esc_attr( $name ) . '" >' . esc_html( $this->_fields[ $name ]->get_label() ) . $required . '</label>';
	}

	/**
	 * Get field classes
	 *
	 * @param string $field_id Field id.
	 * @param string $type Field type.
	 */
	public function classes( $field_id, $type ) {

		$classes   = array();
		$classes[] = $this->_fields[ $field_id ]->get_extra_classes();

		switch ( $type ) {
			case 'validation':

				if ( isset( $this->_errors[ $field_id ] ) ) {
					$classes[] = 'frmk-has-error';
				}
				break;
			case 'type':
				$classes[] = sprintf( 'frmk-input-%s', $this->_fields[ $field_id ]->get_type() );
				break;
		}

		echo esc_attr( implode( ' ', $classes ) );
	}

	/**
	 * Get field input error
	 *
	 * @param string $field_id Field id.
	 */
	public function error( $field_id ) {

		if ( isset( $this->_errors[ $field_id ] ) ) {
			echo '<span class="frmk-field-error">' . esc_html( $this->_errors[ $field_id ] ) . '</span>';
		}
	}

	/**
	 * Display submit button for form
	 *
	 * @param string $label Submit button text.
	 * @param array  $args Submit button display arguments.
	 */
	public function submit( $label = '', $args = array() ) {

		if ( ! $this->has_valid_token() ) {
			return;
		}

		// output recaptcha.
		$this->output_recaptcha();

		if ( empty( $label ) ) {
			$label = $this->_settings['labels']['submit'];
		}
		echo '<input type="submit" value="' . esc_attr( $label ) . '" class="frmk-button frmk-submit-button" /><div class="frmk-spinner"></div>';
	}

	/**
	 * Display form closing tag
	 *
	 * @param array $args
	 */
	public function end($args = array()) {

		// hidden fields.
		wp_nonce_field( 'frmk_submit_form_' . $this->get_name(), 'frmk_nonce' );
		echo '<input type="hidden" name="frmk_action" value="' . esc_attr( $this->get_name() ) . '" />';
		echo '<input type="hidden" name="frmk_token" value="' . esc_attr( $this->get_token() ) . '" />';

		// output arguments
		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				echo '<input type="hidden" name="frmk_attr_' . esc_attr($key) . '" value="' . esc_attr( $value ) . '" />';
			}
		}

		echo '</form>';
	}

	/**
	 * Set form error
	 *
	 * @param string $error Error message.
	 */
	public function set_error( $error ) {
		$this->_error = $error;
	}

	/**
	 * Check to see if form has errors
	 *
	 * @return bool
	 */
	public function has_errors() {

		if ( ! empty( $this->_errors ) || false !== $this->_error ) {
			return true;
		}

		return false;
	}

	/**
	 * Check to see if form has valid token
	 *
	 * @return bool
	 */
	public function has_valid_token() {

		$token = $this->get_token( false );
		if ( ! $this->_submitted || $this->verify_token( $token ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Display form error message
	 */
	public function errors() {

		echo '<div class="frmk-form-error">';

		if ( $this->_error ) {
			echo '<p>' . esc_html( $this->_error ) . '</p>';
		} else {

			// display general message.
			$general_msg = $this->get_setting( 'general_message', 'error' );
			if ( ! empty( $general_msg ) ) {
				echo sprintf( '<p>%s</p>', esc_html( $general_msg ) );
			}

			// Display list of field errors.
			$show_errors = $this->get_setting( 'show_fields', 'error' );
			if ( 'no' !== $show_errors ) {

				echo '<ul class="frmk-form-errors">';
				foreach ( $this->_errors as $field_id => $error ) {
					echo '<li>' . esc_html( $this->_fields[ $field_id ]->get_label() ) . ' - ' . esc_html( $error ) . '</li>';
				}
				echo '</ul>';
			}
		}
		echo '</div>';
	}

	/**
	 * Check to see if form is complete
	 *
	 * @return bool
	 */
	public function is_complete() {

		// no data has been submitted.
		if ( true === $this->_submitted && ! $this->has_errors() ) {
			$this->get_confirmation_message();
			return true;
		}

		return false;
	}

	/**
	 * Has form been submitted
	 *
	 * @return bool
	 */
	public function is_submitted() {
		return $this->_submitted;
	}

	/**
	 * Output form complete/confirmation/thank you message
	 */
	public function get_confirmation_message() {

		if ( 'message' === $this->_confirmation['type']   ) {
			return $this->_confirmation['message'];
		}

		return __( 'Form submitted successfully', 'frmk' );
	}

	/**
	 * Get form name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->_name;
	}

	/**
	 * Get form label
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->_name;
	}

	/**
	 * Get form content
	 *
	 * @return null|string
	 */
	public function get_content() {

		// don't show form content if confirmation location is set to replace.
		if ( $this->_submitted  && !$this->has_errors() && 'replace' === $this->get_confirmation_location() ) {
			return '';
		}

		return $this->_content;
	}

	/**
	 * Get form id
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->ID;
	}

	/**
	 * Get field label
	 *
	 * @param string $field Field id.
	 * @param string $fallback Fallback label.
	 *
	 * @return null|string
	 */
	public function get_field_label( $field, $fallback = null ) {

		if ( ! is_null( $fallback ) ) {
			return isset( $this->_fields[ $field ] ) ? $this->_fields[ $field ]->get_label() : $fallback;
		}

		return isset( $this->_fields[ $field ] ) ? $this->_fields[ $field ]->get_label() : $field;
	}

	/**
	 * Get form fields
	 *
	 * @return FRMK_FormField[]|FRMK_FileField[]|FRMK_TextareaField[]|FRMK_TextField[]|FRMK_RadioField[]|FRMK_SelectField[]|FRMK_CheckboxField[]
	 */
	public function get_fields() {
		return $this->_fields;
	}

	/**
	 * Get form field
	 *
	 * @param string $field Field id.
	 *
	 * @return bool|FRMK_FormField
	 */
	public function get_field( $field ) {
		return isset( $this->_fields[ $field ] ) ? $this->_fields[ $field ] : false;
	}

	/**
	 * Get form confirmation location
	 *
	 * @return string
	 */
	protected function get_confirmation_location() {
		return $this->_confirmation_location;
	}

	/**
	 * Get current form token
	 *
	 * @param bool $generate Flag to allow token generation.
	 *
	 * @return string
	 */
	public function get_token( $generate = true ) {

		// form has not been submitted.
		if ( $generate && ! $this->_submitted && ! $this->_token ) {

			// generate fresh token.
			do {
				$this->_token = wp_generate_password( 12, false );
			} while ( get_transient( 'frmk_token_' . $this->_token ) !== false );

			// store transient token.
			set_transient( 'frmk_token_' . $this->_token, array(
				'ip'   => frmk_get_ip(),
				'time' => time(),
			), HOUR_IN_SECONDS );
		} elseif ( $this->_submitted && isset( $_REQUEST['frmk_token'] ) ) {
			$this->_token = $_REQUEST['frmk_token'];
		}

		return $this->_token;
	}

	/**
	 * Get upload folder for current session
	 *
	 * @return string
	 */
	public function get_upload_folder() {

		$token = $this->get_token( false );

		$transient_key = sprintf( 'frmk_token_%s', $token );
		$transient = get_transient( 'frmk_token_' . $token );
		if ( ! isset( $transient['upload_dir'] ) )
		{
			$transient['upload_dir'] = md5( $token . $transient['time'] );
			set_transient( $transient_key, $transient );
		}

		return $transient['upload_dir'];
	}

	/**
	 * Make sure token is valid
	 *
	 * @param string $token Token string.
	 *
	 * @return bool
	 */
	public function verify_token( $token ) {

		if ( $token && ! empty( $token ) ) {

			$transient = get_transient( 'frmk_token_' . $token );
			if ( $transient ) {
				if ( isset( $transient['ip'] ) && frmk_get_ip() === $transient['ip'] ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Clear active token
	 *
	 * @return bool
	 */
	public function clear_token() {
		if ( $this->verify_token( $this->_token ) ) {
			return delete_transient( 'frmk_token_' . $this->_token );
		}

		return false;
	}

	/**
	 * Check to see if recaptcha has been setup form the form
	 *
	 * @return bool
	 */
	public function recaptcha_setup() {
		if ( $this->get_setting( 'recaptcha_private' ) && $this->get_setting( 'recaptcha_public' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check to see if recaptcha is a valid response
	 *
	 * @return bool
	 */
	public function verify_recaptcha() {

		// escape if recaptcha is not setup.
		if ( ! $this->recaptcha_setup() ) {
			return true;
		}

		$secretKey = $this->get_setting( 'recaptcha_private' );
		$captcha   = $_POST['g-recaptcha-response'];
		$response  = json_decode( file_get_contents( "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR'] ), true );
		if ( isset( $response['success'] ) && true === $response['success'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Display recaptcha field
	 */
	public function output_recaptcha() {

		// escape if recaptcha is not setup.
		if ( ! $this->recaptcha_setup() ) {
			return;
		}

		$public_key = $this->get_setting( 'recaptcha_public' );
		?>
		<div class="frmk-form-row frmk-input-captcha">
			<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $public_key ); ?>"></div>
		</div>
		<?php
	}

	/**
	 * Check to see if field has validation rule
	 *
	 * @param string $name Field name.
	 * @param string $rule Validation type.
	 *
	 * @return bool
	 */
	public function field_has_validation( $name, $rule = 'required' ) {

		if ( isset( $this->_rules[ $name ] ) ) {
			foreach ( $this->_rules[ $name ] as $row_rule ) {
				if ( $rule === $row_rule['type'] ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Check if current request is ajax
	 *
	 * @return bool
	 */
	public function is_ajax() {
		return $this->_is_ajax;
	}

	/**
	 * Output ajax response
	 *
	 * @return void
	 */
	public function render_ajax() {

		$attrs = array(
			'frmk_attr_title' => 'title'
		);

		// load arguments passed to shortcode
		$args = array(
			'ajax' => true,
		);
		foreach( $attrs as $attr => $key ) {
			if(isset($_POST[$attr])){
				$args[$key] = $_POST[$attr];
			}
		};

		header( 'Content-Type: application/json' );

		$json = array(
			'status' => 'OK',
		);

		if ( $this->has_errors() ) {
			$json['status'] = 'ERR';
		} else {
			if ( 'redirect' === $this->_confirmation['type'] ) {
				$json['redirect'] = $this->_confirmation['redirect_url'];
			}
		}

		ob_start();
		frmk_display_form( 'FRMK_FORM_' . $this->get_id(), $args);
		$confirmation = ob_get_clean();
		$json['message'] = $confirmation;

		echo wp_json_encode( $json );
		exit;
	}
}
