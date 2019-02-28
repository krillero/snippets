<?php


/*--------------------------------------------------------------
    Ge editor/redaktör extra capabilities för att hantera users
--------------------------------------------------------------*/

function add_editor_caps() {
    // gets the author role
    $role = get_role( 'editor' );
 
    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap( 'list_users' );
    $role->add_cap( 'create_users' );
    $role->add_cap( 'edit_users' );
    $role->add_cap( 'delete_users' );
}
add_action( 'admin_init', 'add_editor_caps');


/**
 * Deny access to 'administrator' for other roles
 * Else anyone, with the edit_users capability, can edit others
 * to be administrators - even if they are only editors or authors
 * 
 * @since   0.1
 * @param   (array) $all_roles
 * @return  (array) $all_roles
 */
function deny_change_to_admin( $all_roles )
{
    if ( !current_user_can('administrator') ){
        unset( $all_roles['administrator'] );
        // unset( $all_roles['editor'] );
        // unset( $all_roles['author'] );
        // unset( $all_roles['contributor'] );
    }

    // if ( !current_user_can('administrator')
    //     OR !current_user_can('edit_pages') ){
    //     unset( $all_roles['editor'] );
    // }

    // if ( !current_user_can('administrator')
    //     OR !current_user_can('edit_pages')
    //     OR !current_user_can('edit_posts') ){
    //     unset( $all_roles['author'] );
    // }

    return $all_roles;
}

function deny_rolechange()
{
    add_filter( 'editable_roles', 'deny_change_to_admin' );
}
add_action( 'after_setup_theme', 'deny_rolechange' );





/*--------------------------------------------------------------
	CUSTOM EDITOR
--------------------------------------------------------------*/
// Customizes 'Editor' role to have the ability to modify menus, add new users
// and more.
class Custom_Admin {
    // Add our filters
    public function __construct(){
        // Allow editor to edit theme options (ie Menu)
        add_action('init', array($this, 'init'));
        add_filter('editable_roles', array($this, 'editable_roles'));
        add_filter('map_meta_cap', array($this, 'map_meta_cap'), 10, 4);
    }

    public function init() {
        if ( $this->is_client_admin() ) {
        	
            // Disable access to the theme/widget pages if not admin
            add_action('admin_head', array($this, 'modify_menus'));
            add_action('load-themes.php', array($this, 'wp_die'));
            add_action('load-widgets.php', array($this, 'wp_die'));
            add_action('load-customize.php', array($this, 'wp_die'));

            add_filter('user_has_cap', array($this, 'user_has_cap'));
        }
    }

    public function wp_die() {
        _default_wp_die_handler(__('You do not have sufficient permissions to access this page.'));
    }

    public function modify_menus() {
        remove_submenu_page( 'themes.php', 'themes.php' ); // hide the theme selection submenu
        remove_submenu_page( 'themes.php', 'widgets.php' ); // hide the widgets submenu

        // Appearance Menu
        global $menu;
        global $submenu;
        if (isset($menu[60][0])) {
            $menu[60][0] = "Menus"; // Rename Appearance to Menus
        }
        unset($submenu['themes.php'][6]); // Customize
    }

    // Remove 'Administrator' from the list of roles if the current user is not an admin
    public function editable_roles( $roles ) {
        if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
            unset( $roles['administrator']);
        }
        return $roles;
    }

    public function user_has_cap( $caps ){
        $caps['list_users'] = true;
        $caps['create_users'] = true;

        $caps['edit_users'] = true;
        $caps['promote_users'] = true;

        $caps['delete_users'] = true;
        $caps['remove_users'] = true;

        $caps['edit_theme_options'] = true;
        return $caps;
    }

    // If someone is trying to edit or delete an admin and that user isn't an admin, don't allow it
    public function map_meta_cap( $caps, $cap, $user_id, $args ){
        // $args[0] == other_user_id
        foreach($caps as $key => $capability)
        {
            switch ($cap)
            {
                case 'edit_user':
                case 'remove_user':
                case 'promote_user':
                    if(isset($args[0]) && $args[0] == $user_id) {
                        break;
                    }
                    else if(!isset($args[0])) {
                        $caps[] = 'do_not_allow';
                    }
                    // Do not allow non-admin to edit admin
                    $other = new WP_User( absint( $args[0] ) );
                    if( $other->has_cap( 'administrator' ) ) {
                        if( !current_user_can( 'administrator' ) ) {
                            $caps[] = 'do_not_allow';
                        }
                    }
                    break;

                case 'delete_user':
                case 'delete_users':
                    if( !isset( $args[0] ) ) {
                        break;
                    }
                    // Do not allow non-admin to delete admin
                    $other = new WP_User( absint( $args[0] ) );
                    if( $other->has_cap( 'administrator' ) ) {
                        if( !current_user_can( 'administrator' ) ) {
                            $caps[] = 'do_not_allow';
                        }
                    }
                    break;

                break;
            }
        }
        return $caps;
    }

    // If current user is called admin or administrative and is an editor
    protected function is_client_admin() {
        $current_user = wp_get_current_user();
        $is_editor = isset( $current_user->caps['editor'] ) ? $current_user->caps['editor'] : false;
        return ( $is_editor );
    }
}
new Custom_Admin();

/*--------------------------------------------------------------
	END OF CUSTOM EDITOR
--------------------------------------------------------------*/

?>
