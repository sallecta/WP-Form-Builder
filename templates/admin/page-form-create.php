<?php
/**
 * Create new form screen
 *
 * @var FRMK_Admin $this
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 21/11/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$form_name = isset( $_POST['form-name'] ) ? sanitize_text_field( $_POST['form-name'] ) : '';
?>
<div class="frmk-form-create">
	<h1>FrMk: Add new  form</h1>

	<?php
	if ( $this->has_errors() ) {
		$errors = $this->get_errors();
		echo '<ul class="frmk-errors">';
		foreach ( $errors as $error ) {
			echo '<li>' . esc_html( $error ) . '</li>';
		}
		echo '</ul>';
	}
	?>

	<form action="" method="post">

		<input type="hidden" name="frmk-action" value="create-form"/>

		<h2>Form Details</h2>
		<label for="form-name">Name</label>
		<input type="text" name="form-name" id="form-name" value="<?php echo esc_attr( $form_name ); ?>"/>

		<br/><br/>
		<input type="submit" class="button button-primary" value="Create Form"/>

	</form>
</div>

