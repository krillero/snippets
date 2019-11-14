<?php
/**
 * @package Kinsta_users
 * @version 1.0
 */
/*
Plugin Name: Kinsta users
Plugin URI: http://wordpress.org/extend/plugins/#
Description: This is an example plugin 
Author: Carlo Daniele
Version: 1.0
Author URI: http://carlodaniele.it/en/
*/

/**
 * Register a custom post type
 */
function kinsta_project_post_type(){

	// define an array of labels
	$post_type_labels = array(
		'name' 					=> __( 'Projects' ),
		'singular_name'			=> __( 'Project' ),
		'add_new_item'			=> __( 'Add New Project' ),
		'edit_item'				=> __( 'Edit Project' ),
		'new_item'				=> __( 'New Project' ),
		'view_item'				=> __( 'View Project' ),
		'view_items'			=> __( 'View Projects' ),
		'not_found'				=> __( 'No Projects found' ),
		'not_found_in_trash'	=> __( 'No Projects found in Thrash' ),
		'all_items'				=> __( 'All Projects' ),
		'archives'				=> __( 'Project Archives' ),
		'insert_into_item'		=> __( 'Insert into Project' ),
		'uploaded_to_this_item'	=> __( 'Uploaded to this Project' )
		);

	// define an array of arguments
	$post_type_args = array(
		'labels' => $post_type_labels,
		'public' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-media-document',
		//'capability_type' => 'student_project',
		'capabilities' => array(
			'read_post'					=> 'read_student_project',
			'read_private_posts' 		=> 'read_private_student_projects',
			'edit_post'					=> 'edit_student_project',
			'edit_posts'				=> 'edit_student_projects',
			'edit_others_posts'			=> 'edit_others_student_projects',
			'edit_published_posts'		=> 'edit_published_student_projects',
			'edit_private_posts'		=> 'edit_private_student_projects',
			'delete_post'				=> 'delete_student_project',
			'delete_posts'				=> 'delete_student_projects',
			'delete_others_posts'		=> 'delete_others_student_projects',
			'delete_published_posts'	=> 'delete_published_student_projects',
			'delete_private_posts'		=> 'delete_private_student_projects',
			'publish_posts'				=> 'publish_student_projects',
			'moderate_comments'			=> 'moderate_student_project_comments',
			),
		'map_meta_cap' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'author', 'excerpt', 'custom-fields', 'comments' ),
		'taxonomies' => array( 'subject' ),
		'has_archive' => true,
		);

	register_post_type( 'student_project', $post_type_args );

	$taxonomy_labels = array(
		'name'                       => __( 'Subjects' ),
		'singular_name'              => __( 'Subject' ),
		'search_items'               => __( 'Search Subjects' ),
		'popular_items'              => __( 'Popular Subjects' ),
		'all_items'                  => __( 'All Subjects' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Subject' ),
		'update_item'                => __( 'Update Subject' ),
		'add_new_item'               => __( 'Add New Subject' ),
		'new_item_name'              => __( 'New Subject Name' ),
		'separate_items_with_commas' => __( 'Separate subjects with commas' ),
		'add_or_remove_items'        => __( 'Add or remove subjects' ),
		'choose_from_most_used'      => __( 'Choose from the most used subjects' ),
		'not_found'                  => __( 'No subjects found.' ),
		'menu_name'                  => __( 'Subjects' ),
		);

	$taxonomy_args = array(
		'hierarchical'          => false,
		'labels'                => $taxonomy_labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'subject' ),
	);

	register_taxonomy( 'subject', 'student_project', $taxonomy_args );
}
add_action( 'init', 'kinsta_project_post_type' );

/**
 * Add Caps to roles
 */
function kinsta_add_caps(){
	$admin = get_role( 'administrator' );
	$admin->add_cap( 'read_student_project' );
	$admin->add_cap( 'read_private_student_project' );
	$admin->add_cap( 'edit_student_project' );
	$admin->add_cap( 'edit_student_projects' );
	$admin->add_cap( 'edit_others_student_projects' );
	$admin->add_cap( 'edit_published_student_projects' );
	$admin->add_cap( 'edit_private_student_projects' );
	$admin->add_cap( 'delete_student_projects' );
	$admin->add_cap( 'delete_student_project' );
	$admin->add_cap( 'delete_others_student_projects' );
	$admin->add_cap( 'delete_published_student_project' );
	$admin->add_cap( 'delete_student_project' );
	$admin->add_cap( 'delete_private_student_project' );
	$admin->add_cap( 'publish_student_projects' );
	$admin->add_cap( 'moderate_student_project_comments' );

	$student = get_role( 'student' );
	$student->add_cap( 'read_student_project' );
	$student->add_cap( 'edit_student_project' );
	$student->add_cap( 'edit_student_projects' );
	$student->add_cap( 'delete_student_project' );
	$student->add_cap( 'delete_student_projects' );

	$teacher = get_role( 'teacher' );
	$teacher->add_cap( 'read_student_project' );
	$teacher->add_cap( 'read_private_student_project' );
	$teacher->add_cap( 'edit_student_project' );
	$teacher->add_cap( 'edit_student_projects' );
	$teacher->add_cap( 'edit_others_student_projects' );
	$teacher->add_cap( 'edit_published_student_projects' );
	$teacher->add_cap( 'edit_private_student_projects' );
	$teacher->add_cap( 'delete_student_project' );
	$teacher->add_cap( 'delete_student_projects' );
	$teacher->add_cap( 'delete_others_student_projects' );
	$teacher->add_cap( 'delete_published_student_projects' );
	$teacher->add_cap( 'delete_private_student_project' );
	$teacher->add_cap( 'publish_student_projects' );
	$teacher->add_cap( 'moderate_student_project_comments' );
}
add_action( 'admin_init', 'kinsta_add_caps');

function kinsta_add_roles() {
	add_role( 'student', 'Student', array( 
		'read' => true, 
		'edit_posts'   => true, 
		'delete_posts' => true,
		'upload_files' => true ) );
	add_role( 'teacher', 'Teacher', array( 
		'read' => true, 
		'edit_posts'   => true, 
		'delete_posts' => true,
		'delete_published_posts' => true,
		'publish_posts' => true,
		'upload_files' => true,
		'edit_published_posts' => true,
		'manage_categories' => true ) );
}
register_activation_hook( __FILE__, 'kinsta_add_roles' );

function kinsta_remove_roles(){
	//check if role exist before removing it
	if( get_role('student') ){
		remove_role( 'student' );
	}
	if( get_role('teacher') ){
		remove_role( 'teacher' );
	}

	$admin = get_role( 'administrator' );

	$caps = array(
		'read_student_project',
		'read_private_student_project',
		'edit_student_project',
		'edit_student_projects',
		'edit_others_student_projects',
		'edit_published_student_projects',
		'edit_private_student_projects',
		'delete_student_projects',
		'delete_student_project',
		'delete_others_student_projects',
		'delete_published_student_project',
		'delete_student_project',
		'delete_private_student_project',
		'publish_student_projects',
		'moderate_student_project_comments'
	);

	foreach ( $caps as $cap ) {
		$admin->remove_cap( $cap );
	}	
}
register_deactivation_hook( __FILE__, 'kinsta_remove_roles' );