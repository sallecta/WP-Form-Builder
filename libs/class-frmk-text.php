<?php
/**
 * Text library
 *
 * @package FRMK
 * @author James Collings
 * @created 22/11/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class FRMK_Text
 */
class FRMK_Text {

	/**
	 * Default text
	 *
	 * @var array|null
	 */
	protected $_default = null;

	/**
	 * FRMK_Text constructor.
	 */
	public function __construct() {

		$this->_default = array(
			// field validation errors.
			'validation_required'         => __( 'This field is required', 'frmk' ),
			'validation_email'            => __( 'Please enter a valid email address', 'frmk' ),
			'validation_unique'           => __( 'This value has already been previously entered', 'frmk' ),
			'validation_min_length'       => __( 'Please enter a value longer than %d', 'frmk' ),
			'validation_max_length'       => __( 'Please enter a value shorter than %d', 'frmk' ),
			// upload errors.
			'upload_max_size'             => __( 'The uploaded file is to large. (max size: %dmb)', 'frmk' ),
			'upload_general'              => __( 'An error occured when uploading the file.', 'frmk' ),
			'upload_ini_size'             => __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'frmk' ),
			'upload_form_size'            => __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'frmk' ),
			'upload_partial'              => __( 'The uploaded file was only partially uploaded', 'frmk' ),
			'upload_no_file'              => __( 'No file was uploaded', 'frmk' ),
			'upload_no_tmp_dir'           => __( 'Missing a temporary folder', 'frmk' ),
			'upload_cant_write'           => __( 'Failed to write file to disk', 'frmk' ),
			'upload_extension'            => __( 'File upload stopped by extension', 'frmk' ),
			'upload_unknown'              => __( 'Unknown upload error', 'frmk' ),
			'upload_invalid_ext'          => __( 'The upload file type is not allowed', 'frmk' ),
			// menu text.
			'menu_fields'                 => __( 'Fields', 'frmk' ),
			'menu_settings'               => __( 'Settings', 'frmk' ),
			'menu_style'                  => __( 'Style', 'frmk' ),
			'menu_notifications'          => __( 'Notifications', 'frmk' ),
			'menu_submissions'            => __( 'Submissions', 'frmk' ),
			// shortcode.
			'shortcode_error_empty_forms' => __( 'You currently have no forms available, please create one and try again.', 'frmk' ),
			'shortcode_error_selection'   => __( 'An error occurred with your selected, please try again.', 'frmk' ),
			// general.
			'general_form_saved' => __( 'Changes have been saved.', 'frmk' ),

			// General field text.
			'fields.general.label.help' => __( 'Text displayed before the field on the form', 'frmk' ),
			'fields.general.label.label' => __( 'Label', 'frmk' ),
			'fields.general.placeholder.help' => __( 'Text displayed in the field when no value is entered', 'frmk' ),
			'fields.general.placeholder.label' => __( 'Placeholder', 'frmk' ),
			'fields.general.css_classes.help' => __( 'Add custom css classes to field output', 'frmk' ),
			'fields.general.css_classes.label' => __( 'CSS Classes', 'frmk' ),
			'fields.general.delete.help' => __( 'Delete field from form', 'frmk' ),
			'fields.general.toggle.help' => __( 'Toggle display of field settings', 'frmk' ),
			'fields.validation.heading.text' => __( 'Validation Rules', 'frmk' ),
			'fields.validation.heading.help' => __( 'Add rules to validate data entered', 'frmk' ),
			'fields.validation.button.text' => __( 'Add validation Rule', 'frmk' ),
			'fields.validation.type.option.placeholder' => __( 'Choose Validation Type', 'frmk' ),
			'fields.validation.type.option.required' => __( 'Required', 'frmk' ),
			'fields.validation.type.option.email' => __( 'Email', 'frmk' ),
			'fields.validation.type.option.unique' => __( 'Unique', 'frmk' ),
			'fields.validation.message.label' => __( 'Validation Message', 'frmk' ),
			'fields.validation.message.help' => __( 'Enter text to be displayed, or leave blank to display default text.', 'frmk' ),

			// Text field text.
			'fields.text.default.help' => __( 'Default value shown when the form is loaded', 'frmk' ),
			'fields.text.default.label' => __( 'Default Value', 'frmk' ),

			// Textarea field text.
			'fields.textarea.default.help' => __( 'Default value shown when the form is loaded', 'frmk' ),
			'fields.textarea.default.label' => __( 'Default Value', 'frmk' ),
			'fields.textarea.rows.help' => __( 'Changes the height of the text area by how many rows are displayed', 'frmk' ),
			'fields.textarea.rows.label' => __( 'Rows', 'frmk' ),

			// File file text.
			'fields.file.max_file_size.label' => __( 'Maximum file size (Server Limit: %d)', 'frmk' ),
			'fields.file.max_file_size.help' => __( 'Maximum size of file allowed to be uploaded', 'frmk' ),
			'fields.file.allowed_ext.label' => __( 'Allowed Extensions', 'frmk' ),
			'fields.file.allowed_ext.help' => __( 'Comma separated list of allowed file extensions (jpg,jpeg,png)', 'frmk' ),

			// Number field text.
			'fields.number.type.help' => __( 'Field type to display', 'frmk' ),
			'fields.number.type.label' => __( 'Type', 'frmk' ),
			'fields.number.type.option.input' => __( 'Number Input', 'frmk' ),
			'fields.number.type.option.input-range' => __( 'Number Range Input', 'frmk' ),
			'fields.number.type.option.slider' => __( 'Number Slider', 'frmk' ),
			'fields.number.type.option.slider-range' => __( 'Number Range Slider', 'frmk' ),
			'fields.number.min.help' => __( 'Minimum allowed value', 'frmk' ),
			'fields.number.min.label' => __( 'Minimum', 'frmk' ),
			'fields.number.max.help' => __( 'Maximum allowed value', 'frmk' ),
			'fields.number.max.label' => __( 'Maximum', 'frmk' ),
			'fields.number.step.help' => __( 'Number increment', 'frmk' ),
			'fields.number.step.label' => __( 'Number Increment', 'frmk' ),
			'fields.number.default.help' => __( 'Default Value', 'frmk' ),
			'fields.number.default.label' => __( 'Default Value', 'frmk' ),

			// Checkbox field text.
			'fields.checkbox.values.heading.label' => __( 'Values', 'frmk' ),
			'fields.checkbox.values.heading.help' => __( 'List of available options', 'frmk' ),
			'fields.checkbox.values.label.label' => __( 'Label', 'frmk' ),
			'fields.checkbox.values.label.help' => __( 'Text displayed in dropdown', 'frmk' ),
			'fields.checkbox.values.value.label' => __( 'Value', 'frmk' ),
			'fields.checkbox.values.value.help' => __( 'Value stored in when selected, leave blank to auto generate from label', 'frmk' ),
			'fields.checkbox.values.default.label' => __( 'Default', 'frmk' ),
			'fields.checkbox.values.default.help' => __( 'Check value to make default when form is loaded', 'frmk' ),

			// Radio field text.
			'fields.radio.values.heading.label' => __( 'Values', 'frmk' ),
			'fields.radio.values.heading.help' => __( 'List of available options', 'frmk' ),
			'fields.radio.values.label.label' => __( 'Label', 'frmk' ),
			'fields.radio.values.label.help' => __( 'Text displayed in dropdown', 'frmk' ),
			'fields.radio.values.value.label' => __( 'Value', 'frmk' ),
			'fields.radio.values.value.help' => __( 'Value stored in when selected, leave blank to auto generate from label', 'frmk' ),
			'fields.radio.values.default.label' => __( 'Default', 'frmk' ),
			'fields.radio.values.default.help' => __( 'Check value to make default when form is loaded', 'frmk' ),

			// Select field text.
			'fields.select.empty_text.label' => __( 'Empty Text', 'frmk' ),
			'fields.select.empty_text.help' => __( 'Default value shown when the form is loaded, leave empty to not show', 'frmk' ),
			'fields.select.select_type.label' => __( 'Select Type', 'frmk' ),
			'fields.select.select_type.help' => __( 'Multiple selects allow the user to select multiple values instead of only a single value.', 'frmk' ),
			'fields.select.select_type.single.label' => __( 'Single', 'frmk' ),
			'fields.select.select_type.multiple.label' => __( 'Multiple', 'frmk' ),
			'fields.select.values.heading.label' => __( 'Values', 'frmk' ),
			'fields.select.values.heading.help' => __( 'List of available options', 'frmk' ),
			'fields.select.values.label.label' => __( 'Label', 'frmk' ),
			'fields.select.values.label.help' => __( 'Text displayed in dropdown', 'frmk' ),
			'fields.select.values.value.label' => __( 'Value', 'frmk' ),
			'fields.select.values.value.help' => __( 'Value stored in when selected, leave blank to auto generate from label', 'frmk' ),
			'fields.select.values.default.label' => __( 'Default', 'frmk' ),
			'fields.select.values.default.help' => __( 'Check value to make default when form is loaded', 'frmk' ),
		);

	}

	/**
	 * Get text string
	 *
	 * @param string $key String key.
	 * @param string $prefix Prefix form key.
	 *
	 * @return mixed|string
	 */
	public function get( $key, $prefix = '' ) {

		// if prefix provided.
		if ( ! empty( $prefix ) ) {
			$prefix .= '_';
		}

		return isset( $this->_default[ $prefix . $key ] ) ? $this->_default[ $prefix . $key ] : '';
	}

}
