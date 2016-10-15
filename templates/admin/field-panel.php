<?php
/**
 * Display form field panel
 *
 * @@var WPDF_FormField|string $field
 * @var bool $active
 */

$field_type = $field->getType();
?>
<div class="wpdf-panel wpdf-panel--white <?php echo $active == true ? 'wpdf-panel--active' : ''; ?>" data-field-type="<?php echo $field_type; ?>">
	<div class="wpdf-panel__header">
		Field: <?php echo ucfirst($field_type); ?>
		- <a href="#delete" class="wpdf-del-field">Delete</a>
	</div>
	<div class="wpdf-panel__content">

		<?php
		// hidden fields
		?>
		<input type="hidden" name="field[][type]" value="<?php echo $field_type; ?>" />

		<?php
		// general fields
		?>
		<div class="wpdf-field-row">
			<div class="wpdf-col wpdf-col__half">
				<label for="" class="wpdf-label">Label</label>
				<input type="text" class="wpdf-input" name="field[][label]" value="<?php echo $field->getLabel(); ?>">
			</div>
			<div class="wpdf-col wpdf-col__half">
				<label for="" class="wpdf-label">Placeholder</label>
				<input type="text" class="wpdf-input" name="field[][placeholder]" value="<?php echo $field->getPlaceholder(); ?>">
			</div>
		</div>

		<?php
		// specific fields based on field type
		switch($field_type):
			case 'text':
				?>
				<div class="wpdf-field-row">
					<div class="wpdf-col wpdf-col__half">
						<label for="" class="wpdf-label">Default Value</label>
						<input type="text" class="wpdf-input" name="field[][default]">
					</div>
				</div>
				<?php
				break;
			case 'textarea':
				?>
				<div class="wpdf-field-row">
					<div class="wpdf-col wpdf-col__full">
						<label for="" class="wpdf-label">Default Value</label>
						<textarea class="wpdf-input" name="field[][default]"></textarea>
					</div>
				</div>
				<?php
				break;
			case 'select':
			case 'radio':
			case 'checkbox':
				?>
				<div class="wpdf-field-row">
					<div class="wpdf-col wpdf-col__full">

						<strong>Values</strong>

						<table width="100%">
							<thead>
							<tr>
								<th>Label</th>
								<th>Key</th>
								<th>Default?</th>
								<th>_</th>
							</tr>
							</thead>
							<tbody class="wpdf-repeater" data-min="1" data-template-name="field_value_repeater">
							<script type="text/html" class="wpdf-repeater-template">
								<tr class="wpdf-repeater-row wpdf-repeater__template">
									<td><input title="Label" type="text" class="wpdf-input" name="field[][value_labels][]"></td>
									<td><input title="Key" type="text" class="wpdf-input" name="field[][value_keys][]"></td>
									<td><input title="Default?" type="checkbox" name="field[][value_default][]"></td>
									<td>
										<a href="#" class="wpdf-add-row button">+</a>
										<a href="#" class="wpdf-del-row button">-</a>
									</td>
								</tr>
							</script>
							<?php
							$options = $field->getOptions();
							if(!empty($options)) {
								foreach ( $options as $key => $option ) {
									?>
									<tr class="wpdf-repeater-row wpdf-repeater__template">
										<td><input title="Label" type="text" class="wpdf-input"
										           name="field[][value_labels][]" value="<?php echo $option; ?>" /></td>
										<td><input title="Key" type="text" class="wpdf-input"
										           name="field[][value_keys][]" value="<?php echo $key; ?>" /></td>
										<td><input title="Default?" type="checkbox"
										           name="field[][value_default][]" <?php
											$default = $field->getDefaultValue();
											if(is_array($default)){
												checked(in_array($key, $default), true, true);
											}else{
												checked($key, $default, true);
											}
											?> /></td>
										<td>
											<a href="#" class="wpdf-add-row button">+</a>
											<a href="#" class="wpdf-del-row button">-</a>
										</td>
									</tr>
									<?php
								}
							}
							?>
							</tbody>
						</table>

					</div>
				</div>
				<?php
				break;
		endswitch;
		// add-on fields
		?>
		<div class="wpdf-clear"></div>
	</div>
</div>