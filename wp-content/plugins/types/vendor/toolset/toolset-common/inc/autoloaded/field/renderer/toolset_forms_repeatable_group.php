<?php

/**
 * Class Toolset_Field_Renderer_Toolset_Forms_Repeatable_Group
 *
 * This class extends Toolset_Field_Renderer_Toolset_Forms by adding the post_id to the html name attribute of the
 * field input. This way it's easy to store a field for a post wherever the field edit is shown.
 *
 * See:
 * <input name="wpcf[name-of-field]" /> (output of Toolset_Field_Renderer_Toolset_Forms)
 * <input name="wpcf[post-id-the-field-belongs-to][name-of-field] /> (output of Toolset_Field_Renderer_Toolset_Forms_Repeatable_Group)
 *
 * @since 2.3
 */
class Toolset_Field_Renderer_Toolset_Forms_Repeatable_Group extends Toolset_Field_Renderer_Toolset_Forms {

	public function render( $echo = false ) {
		$field_config = $this->get_toolset_forms_config();
		if ( $this->hide_field_title ) {
			$field_config['title'] = '';
		}

		if ( $field_config['type'] == 'wysiwyg' ) {
			$field_config['type'] = 'textarea';
		}

		$field_config['repetitive'] = false;

		// [name] => wpcf[audio-205cb736]
		$field_config['name'] = 'types-repeatable-group[' . $this->field->get_object_id() . '][' . $field_config['slug'] . ']';
		if( isset( $field_config['options'] ) ) {
			foreach ( (array) $field_config['options'] as $option_key => $option_value ) {
				if ( isset( $option_value['name'] ) ) {
					$field_config['options'][$option_key]['name'] = 'types-repeatable-group[' . $this->field->get_object_id() . '][' . $field_config['slug'] . '][' . $option_key . ']';
				}
			}
		}

		$value_in_intermediate_format = $this->field->get_value();
		$output = wptoolset_form_field( $this->get_form_id(), $field_config, $value_in_intermediate_format );

		if ( $echo ) {
			echo $output;
		}

		return $output;
	}
}