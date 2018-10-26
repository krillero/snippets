<?php

//todo figure out how and why !( defined( 'DOING_AJAX' ) && DOING_AJAX works

/*--------------------------------------------------------------
	Block access to wp-admin for specific roles
--------------------------------------------------------------*/
// authors and above has the capability to publish_posts, everyone else gets redirected
// admin_init is triggered before any other hook when a user accesses the admin area.
     
add_action( 'admin_init', 'blockusers_init' );
function blockusers_init() {
	if (
		is_admin() &&
		!current_user_can( 'publish_posts' ) &&
		!( defined( 'DOING_AJAX' ) && DOING_AJAX )
	) {
		wp_redirect( home_url() );
		exit;
	}
}

?>