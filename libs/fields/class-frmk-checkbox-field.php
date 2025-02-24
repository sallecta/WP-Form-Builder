<?php
/**
 * Checkbox Field
 *
 * @package FRMK/Fields
 * @author James Collings
 * @created 03/11/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class FRMK_CheckboxField
 *
 * Add checkbox field.
 */
class FRMK_CheckboxField extends FRMK_FormField {


	/**
	 * Output field on frontend
	 *
	 * @param FRMK_FormData $form_data Passed form data.
	 */
	public function output( $form_data ) {

		$value = $form_data->get_raw( $this->_name );

		if ( isset( $this->_args['options'] ) && ! empty( $this->_args['options'] ) ) {

			$name = $this->get_input_name();
			if ( $this->is_type( 'checkbox' ) ) {
				$name .= '[]';
			}

			echo '<div class="frmk-choices">';

			foreach ( $this->_args['options'] as $key => $option ) {

				if ( is_array( $value ) ) {
					$checked = in_array( '' . $key, $value, true ) ? 'checked="checked"' : '';
				} else {
					$checked = '' . $key === $value ? 'checked="checked"' : '';
				}

				echo '<label>';
				echo '<input type="' . esc_attr( $this->get_type() ) . '" name="' . esc_attr( $name ) . '"' . $checked . ' value="' . esc_attr( $key ) . '" class="frmk-field">' . esc_html( $option );
				echo '</label>';
			}

			echo '</div>';
		}
	}

	/**
	 * Format field data to store in fields array
	 *
	 * @param array $field Field data to be passed.
	 *
	 * @return array
	 */
	public function save( $field = array() ) {

		$data = parent::save( $field );

		$options  = array();
		$defaults = array();
		foreach ( $field['value_labels'] as $arr_id => $label ) {
			$option_key             = isset( $field['value_keys'][ $arr_id ] ) && ! empty( $field['value_keys'][ $arr_id ] ) ? esc_attr( $field['value_keys'][ $arr_id ] ) : esc_attr( $label );
			$options[ $option_key ] = $label;

			if ( isset( $field['value_default'][ $arr_id ] ) ) {
				$defaults[] = $option_key;
			}
		}

		$data['options'] = $options;
		$data['default'] = $defaults;

		return $data;
	}
}