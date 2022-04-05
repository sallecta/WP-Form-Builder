<?php
/**
 * Form global header
 *
 * @var FRMK_Form $form
 * @var string $active Currently active page
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 30/11/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<h1>FrMk</h1>
<div class="frmk-topnav">
	<?php if ( $form ) : ?>
		<ul class="frmk-topnav__links">
			<?php if ( $form->get_id() ) : ?>
				<li class="<?php echo 'fields' === $active ? 'active' : ''; ?>"><a
							href="<?php echo esc_url( admin_url( 'admin.php?page=frmk&action=fields&form_id=' . $form->get_id() ) ); ?>"><?php echo esc_html( FRMK()->text->get( 'fields', 'menu' ) ); ?></a>
				</li>
				<li class="<?php echo 'settings' === $active ? 'active' : ''; ?>"><a
							href="<?php echo esc_url( admin_url( 'admin.php?page=frmk&action=settings&form_id=' . $form->get_id() ) ); ?>"><?php echo esc_html( FRMK()->text->get( 'settings', 'menu' ) ); ?></a>
				</li>
				<?php if ( 'enabled' === $form->get_setting( 'enable_style' ) ) : ?>
					<li class="<?php echo 'style' === $active ? 'active' : ''; ?>"><a
								href="<?php echo esc_url( admin_url( 'admin.php?page=frmk&action=style&form_id=' . $form->get_id() ) ); ?>"><?php echo esc_html( FRMK()->text->get( 'style', 'menu' ) ); ?></a>
					</li>
				<?php endif; ?>
				<li class="<?php echo 'notifications' === $active ? 'active' : ''; ?>"><a
							href="<?php echo esc_url( admin_url( 'admin.php?page=frmk&action=notifications&form_id=' . $form->get_id() ) ); ?>"><?php echo esc_html( FRMK()->text->get( 'notifications', 'menu' ) ); ?></a>
				</li>
				<li class="<?php echo 'submissions' === $active ? 'active' : ''; ?>"><a
							href="<?php echo esc_url( admin_url( 'admin.php?page=frmk&action=submissions&form_id=' . $form->get_id() ) ); ?>"><?php echo esc_html( FRMK()->text->get( 'submissions', 'menu' ) ); ?></a>
				</li>
			<?php else : ?>
				<li class="<?php echo 'submissions' === $active ? 'active' : ''; ?>"><a
							href="<?php echo esc_url( admin_url( 'admin.php?page=frmk&action=submissions&form=' . $form->get_name() ) ); ?>"><?php echo esc_html( FRMK()->text->get( 'submissions', 'menu' ) ); ?></a>
				</li>
			<?php endif; ?>

		</ul>
	<?php endif; ?>

	<div class="frmk-topnav__actions">
		<?php if ( ! $form || $form->get_id() ) : ?>
			<a class="frmk-topnav__btn frmk-topnav__preview"
			   href="<?php echo esc_url( admin_url( 'admin.php?page=frmk&action=preview-form&form_id=' . $form->get_id() ) ); ?>"
			   target="_blank">Preview</a>
			<input type="submit" class="frmk-topnav__submit" value="<?php esc_attr_e( 'Update', 'frmk' ); ?>"/>
		<?php endif; ?>
	</div>
</div>
<div class="frmk-subheader">
	<div class="frmk-subheader__left">
		<?php if ( $form ) : ?>
			<p class="frmk-subheader__form">Form: <?php echo esc_html( $form->get_label() ); ?></p>
		<?php else : ?>
			<p class="frmk-subheader__form"><?php echo esc_html( 'New Form', 'frmk' ); ?></p>
		<?php endif; ?>
	</div>
</div>
