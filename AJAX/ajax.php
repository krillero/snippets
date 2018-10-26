<?php
// Add to functions.php



// --------------------------------- LOCALIZE SCRIPT --------------------------------- //

// Register the script
wp_register_script( 'pixel-ajax', get_template_directory_uri() . '/dist/ajax.js', array('jquery'), true );

// Localize script
// the $action in wp_create_nonce( $action ) must match the $action being sent by the ajax request!
// here the string used is 'wp_rest' in both files

wp_localize_script( 'pixel-ajax', 'MyAjax',
	array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce( 'wp_rest' )
	)
);

// Enqueue the script with the 'wp_enqueue_scripts' action
function utbildning_js() {
	wp_enqueue_script( 'pixel-ajax' );
}
add_action( 'wp_enqueue_scripts', 'utbildning_js' );



// --------------------------------- AJAX ACTIONS --------------------------------- //

// Receives AJAX POST requests on url /wp-admin/admin-ajax.php?action=name_of_action
function name_of_action() {

	//check nonce, the header is set by xhr.setRequestHeader( 'X-WP-Nonce', MyAjax.nonce ) in ajax.js 
	// 'HTTP_' get added to the requestheader name, all letters are capitalized and all dashes are replaced by underscores
	// 'X-WP-Nonce' becomes 'HTTP_X_WP_NONCE'
	$nonce = $_SERVER['HTTP_X_WP_NONCE'] ?? '';

	// $action must match the $action used with wp_create_nonce($action) during the script localization
	$action = 'wp_rest';

	// check if the nonce is valid, otherwise returns 403 forbidden
	if (! wp_verify_nonce($nonce, $action)) {
		return new WP_Error('rest_security_error', 'Invalid token.', array('status' => 403));
	}

	// get variables from POST
	$input_key  = $_POST['var1'];
	$input_name = $_POST['var2'];

	// Check if variable is set
	if( ! isset( $input_name ) ) {
		
	}

	//Do stuff and return something
	return;
}
// The first string determines the URL of the AJAX action, the second string binds it to the function name
add_action( 'wp_ajax_nopriv_name_of_action', 'name_of_action' );
add_action( 'wp_ajax_name_of_action', 'name_of_action' );

?>