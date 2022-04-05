<?php
/**
 * Display list of all forms submissions within admin
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 06/08/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get list of available validation rules
 *
 * @return array
 */
function frmk_get_validation_rules() {
	$rules = array(
		'required' => 'Required',
		'email'    => 'Email',
		'unique'   => 'Unique',
	);

	return $rules;
}

/**
 * Display validation settings block on field panel
 *
 * @param string $type Validation rule type.
 * @param array  $data Validation settings.
 */
function frmk_display_validation_block( $type = '', $data = array() ) {
	$rules = frmk_get_validation_rules();
	?>
	<div class="frmk-validation-row frmk-repeater-row" data-rule="<?php echo esc_attr( $type ); ?>">

		<div class="frmk-validation__selector">
			<select name="field[][validation][][type]" class="validation_type">
				<option value=""><?php echo esc_html( FRMK()->text->get( 'fields.validation.type.option.placeholder' ) ); ?></option>
				<?php foreach ( $rules as $id => $label ) : ?>
					<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $id, $type, true ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<a href="#" class="frmk-del-row">Remove</a>
		</div>

		<div class="frmk-validation__rule">
			<?php
			// display validation options.
			if ( ! empty( $type ) ) {
				frmk_display_validation_block_section( $type, $data );
			}
			?>
		</div>
	</div>
	<?php

}

/**
 * Display a single validation block type
 *
 * @param string $type Validation rule type.
 * @param array  $data Validation settings.
 */
function frmk_display_validation_block_section( $type = '', $data = array() ) {
	?>
	<div class="frmk-col">
		<label class="frmk-label" for="">
			<?php echo esc_html( FRMK()->text->get( 'fields.validation.message.label' ) ); ?>
			<span class="frmk-tooltip frmk-tooltip__inline"
			      title="<?php echo esc_attr( FRMK()->text->get( 'fields.validation.message.help' ) ); ?>">?</span>
		</label>
		<input type="text" name="field[][validation][][msg]"
		       value="<?php echo isset( $data['msg'] ) ? esc_attr( $data['msg'] ) : ''; ?>"
		       placeholder="<?php echo esc_attr( FRMK()->text->get( 'validation_' . $type ) ); ?>" class="frmk-input"/>
	</div>
	<?php
}

/**
 * Display form notifications setting panel
 *
 * @param array      $notification Notification settings.
 * @param int|string $i Notification index.
 * @param array      $field_keys List of all avaliable merge tags for fields.
 */
function frmk_display_notification_settings( $notification, $i = '', $field_keys = array() ) {
	?>
	<li class="frmk-notification frmk-repeater-row">
		<div class="frmk-panel frmk-panel--white frmk-panel--active">
			<div class="frmk-panel__header">
				Notification
				<a class="frmk-tooltip frmk-panel__delete frmk-del-field" title="Delete notification">Delete</a>
				<a href="#" class="frmk-panel__toggle frmk-tooltip-blank"
				   title="Toggle display of notification settings"></a>
			</div>
			<div class="frmk-panel__content">

				<table class="frmk-form-table">
					<tr>
						<td class="notification__label frmk-tooltip__wrapper"><label for="to">Send To <span
										class="frmk-tooltip"
										title="Email addresses to notify, seperated by ','">?</span></label></td>
						<td class="notification__input"><input id="to" type="text"
						                                       name="notification[<?php echo esc_attr( $i ); ?>][to]"
						                                       value="<?php echo esc_attr( $notification['to'] ); ?>"/></td>
						<td></td>
					</tr>
					<tr>
						<td class="notification__label frmk-tooltip__wrapper"><label for="subject">Subject <span
										class="frmk-tooltip" title="Email subject line">?</span></label></td>
						<td class="notification__input"><input id="subject" type="text"
						                                       name="notification[<?php echo esc_attr( $i ); ?>][subject]"
						                                       value="<?php echo esc_attr( $notification['subject'] ); ?>"/></td>
						<td></td>
					</tr>
					<tr>
						<td class="notification__label frmk-tooltip__wrapper"><label for="message">Message <span
										class="frmk-tooltip" title="Email message text">?</span></label></td>
						<td class="notification__input"><textarea name="notification[<?php echo esc_attr( $i ); ?>][message]"
						                                          id="message" cols="30"
						                                          rows="10"><?php echo esc_textarea( $notification['message'] ); ?></textarea>
						</td>
						<td>
							Admin Email: <code>admin_email</code><br />
							Site Name: <code>site_name</code><br />
							Site Url: <code>site_url</code><br />
							Form Name: <code>form_name</code><br /><br />

							Form data can be displayed in the message using merge tags, to display all fields <code>{{fields}}</code>,
							to display individual fields you can use the following merge tags: <?php
							echo '<br />' . implode( ',<br /> ', $field_keys );
							?>
						</td>
					</tr>
					<tr>
						<td class="notification__label frmk-tooltip__wrapper"><label for="from">From <span
										class="frmk-tooltip" title="Sent from email address">?</span></label></td>
						<td class="notification__input"><input id="from" type="text"
						                                       name="notification[<?php echo esc_attr( $i ); ?>][from]"
						                                       value="<?php echo isset( $notification['from'] ) ? esc_attr( $notification['from'] ) : ''; ?>"/>
						</td>
						<td></td>
					</tr>
					<tr>
						<td class="notification__label frmk-tooltip__wrapper"><label for="cc">Cc <span
										class="frmk-tooltip" title="Email addresses to cc to, seperated by ','">?</span></label>
						</td>
						<td class="notification__input"><input id="cc" type="text"
						                                       name="notification[<?php echo esc_attr( $i ); ?>][cc]"
						                                       value="<?php echo isset( $notification['cc'] ) ? esc_attr( $notification['cc'] ) : ''; ?>"/>
						</td>
						<td></td>
					</tr>
					<tr>
						<td class="notification__label frmk-tooltip__wrapper"><label for="bcc">Bcc <span
										class="frmk-tooltip"
										title="Email addresses to bcc to, seperated by ','">?</span></label></td>
						<td class="notification__input"><input id="bcc" type="text"
						                                       name="notification[<?php echo esc_attr( $i ); ?>][bcc]"
						                                       value="<?php echo isset( $notification['bcc'] ) ? esc_attr( $notification['bcc'] ) : ''; ?>"/>
						</td>
						<td></td>
					</tr>


				</table>

			</div>
		</div>
	</li>
	<?php
}

/**
 * Display form input using iris colour picker
 *
 * @param string $name input name.
 * @param string $colour default colour.
 */
function frmk_iris_picker( $name, $colour = '#FFFFFF' ) {
	?>
	<div class="frmk-iris-wrap">
		<span class="frmk-color-pick-preview" style="background: <?php echo esc_attr( $colour ); ?>"></span>
		<input type="text" class="frmk-color-picker-input" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $colour ); ?>"/>
	</div>
	<?php
}