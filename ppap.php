<?php
/*
Plugin Name: Password protect all posts
Version: 0.1
Plugin URI: http://wordpress.org/extend/plugins/password-protect-all-posts/
Description: This plugin puts a global password selected by you on all posts. Based on Matt Mullenwegs plugin "Protect old posts".
Author: Arvid Sollenby
Author URI: http://www.arvid.nu
*/

// FUNCTION THAT CHANGES PASSWORD ON POSTS
function mm_something_changed($something) { 
	global $wpdb;
	$password = get_option('ppap_glob_pass');
	$wpdb->query("UPDATE ".$wpdb->posts." SET post_password = '$password' WHERE post_status = 'publish' AND post_type = 'post'");
	return $something;
}

// THE ADMIN PAGE LAYOUT
function ppap_admin() { 
	if(isset($_POST['submit'])){
		update_option('ppap_glob_pass', $_POST['password']); 
		global $wpdb;
		$password = get_option('ppap_glob_pass');
		$wpdb->query("UPDATE ".$wpdb->posts." SET post_password = '$password' WHERE post_status = 'publish' AND post_type = 'post'");
	}
?> 
    <div class="wrap">  
		<?php    echo "<h2>" . __( 'Password Protect all Posts Settings' , 'ppap') . "</h2>"; ?>  
		<form enctype="multipart/form-data" name="ppap_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"> 
		<p><?php _e( 'Choose global password:' , 'ppap'); ?> <input type="text" name="password" value="<?=get_option('ppap_glob_pass');?>"><br>
		<input type="submit" name="submit" class="button-primary" value="<?php _e('Update Options' , 'ppap') ?>" /> </p>  
	</div>
	<?php
} 


// ADDING THE ADMIN MENU
function ppap_admin_actions(){
	$page = add_options_page(__("Password protect all posts"), __("Password protect all posts"), 1, "ppap.php", "ppap_admin");
}

// INSTALL FUNCTION
function ppap_install(){
	update_option("ppap_glob_pass", "changeme");
}

function ppap_init(){
	load_plugin_textdomain( 'ppap', 'wp-content/plugins/' . $plugin_dir.'/', $plugin_dir.'/' );
}

add_action('publish_post', 'mm_something_changed');
add_action('edit_post', 'mm_something_changed');
add_action('delete_post', 'mm_something_changed');
add_action('comment_post', 'mm_something_changed');
add_action('trackback_post', 'mm_something_changed');
add_action('pingback_post', 'mm_something_changed');
add_action('edit_comment', 'mm_something_changed');
add_action('init', 'ppap_init');
register_activation_hook(__FILE__,'ppap_install');
add_action('admin_menu', 'ppap_admin_actions'); 	
?>