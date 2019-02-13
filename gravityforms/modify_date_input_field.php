<?php

// Adds autocomplete="off" to the input tags of datepicker fields to prevent the autocomplete dropdown from covering the datepicker dropdown
add_filter( 'gform_field_content', 'disable_autocomplete_for_datepicker_2', 10, 2 );
function disable_autocomplete_for_datepicker_2( $field_content, $field ) {

	// Only targets fields of type 'date'
	if( $field->type === 'date' ) {
		return str_replace( 'type=', "autocomplete='off' type=", $field_content );
	}

	return $field_content;
}

?>