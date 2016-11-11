<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 07/08/2016
 * Time: 18:06
 */

/**
 * Shortcode to display form
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function wpdf_shortcode_form( $atts, $content = null ){

	$a = shortcode_atts( array(
		'form' => '',
		'form_id' => 0
		// ...etc
	), $atts );

	$form_id = intval($a['form_id']);
	if($form_id > 0){
		$form = new WPDF_DB_Form($form_id);
		$form_key = $form->getName();
	}else{
		$form_key = $a['form'];
		$form = wpdf_get_form($form_key);
	}

	if(!$form){
		return sprintf('<p>%s</p>', __( "Shortcode Error: Form could not be displayed!", "wpdf"));
	}

	// output form
	ob_start();
	wpdf_display_form($form_key);
	return ob_get_clean();
}
add_shortcode('wp_form', 'wpdf_shortcode_form');
