<?php
/**
 * Plugin Name: NybInstagram
 * Plugin URI: http://nybblemouse.com
 * Description: NybInstagram implements an instagram photo stream from the instagram API
 * Version: 1.0.0
 * Author: Christopher McKirgan
 * Author URI: http://mckirgan.com
 * License: GPLv2 or later
 */

/**
 * Adds a view to the post being viewed
 *
 * Finds the current views of a post and adds one to it by updating
 * the postmeta. The meta key used is "awepop_views".
 *
 * @global object $post The post object
 * @return integer $new_views The number of views the post has
 *
 */


//add_action("wp_head", "nybinstagram_writetoscreen");
add_action( 'admin_menu', 'nybinstagram_menu' );


// get template and set it to shortcode for template output
add_shortcode( 'nybinstagram', 'get_template_html' );

// Setting panel style sheets
wp_register_style('nybInstagramSettingsStylesheet', plugins_url() . '/nybinstagram/nybinstagram-settings.css');
wp_register_style('nybInstagramFrontendStylesheet', plugins_url() . '/nybinstagram/nybinstagram-frontend.css');
wp_enqueue_style( 'nybInstagramSettingsStylesheet');
wp_enqueue_style( 'nybInstagramFrontendStylesheet');
register_activation_hook( __FILE__, 'nybinstagram_activate' );
register_deactivation_hook( __FILE__, 'nybinstagram_deactivate' );

// wordpress cron job for automatic data rebuild
add_filter( 'cron_schedules', 'cron_add_schedule' );
add_action( 'nybinstagram_docron',  'nybinstagram_docron' );

if (!wp_next_scheduled('nybinstagram_docron')) {
	error_log("no cron schedules, scheduling now");
	wp_schedule_event( time(), 'minute', 'nybinstagram_docron' );
}
// handle form posts
if($_POST && $_GET['page']=='nybinstagram-options') {
   nybinstagram_form_handler($_POST);
}

function create_nybinsta(){
   require_once 'instagram_api/instagram.class.php';
   require_once 'nybinstagram.class.php';

   $nybinsta = new NybInstagram(
      array(
         'apiKey'		=> get_option('nybinstagram_client_id'),
         'apiSecret'	=> get_option('nybinstagram_client_secret'),
         // 3rd option added by class __construct
      ),
      array('wp-options' => array(
         'nybinstagram_client_id'      =>(get_option('nybinstagram_client_id', 'null')     !=='null')   ? get_option('nybinstagram_client_id')        : null,      
         'nybinstagram_client_secret'  =>(get_option('nybinstagram_client_secret', 'null') !=='null')   ? get_option('nybinstagram_client_secret')    : null,  
         'nybinstagram_access_token'   =>(get_option('nybinstagram_access_token', 'null')  !=='null')   ? get_option('nybinstagram_access_token')     : null,   
         'nybinstagram_account_id'     =>(get_option('nybinstagram_account_id', 'null')    !=='null')   ? get_option('nybinstagram_account_id')       : null,     
         'nybinstagram_account_name'   =>(get_option('nybinstagram_account_name', 'null')  !=='null')   ? get_option('nybinstagram_account_name')     : null,   
         'nybinstagram_hashtag'        =>(get_option('nybinstagram_hashtag', 'null')       !=='null')   ? get_option('nybinstagram_hashtag')          : null,        
         'nybinstagram_follow_account' =>(get_option('nybinstagram_follow_account', 'null')!=='null')   ? get_option('nybinstagram_follow_account')   : null, 
         'nybinstagram_follow_hashtag' =>(get_option('nybinstagram_follow_hashtag', 'null')!=='null')   ? get_option('nybinstagram_follow_hashtag')   : null, 
      ))
   ); // aArgs needs to be either string:api-key OR array(client_id, client_secret, api callback)
   return $nybinsta;   
}

function nybinstagram_form_handler($aArgs){
   $nybinsta = create_nybinsta();      
   $nybinsta->form_handler($aArgs);
   $nybinsta->rebuild_data();
}


function get_template_html() {
   error_log("Getting template now.");
	$nybinsta = create_nybinsta();
   //$nybinsta->rebuild_data();
	$nybinsta->get_frontend_template();  
}

function nybinstagram_menu() {
   add_options_page( 'NybInstagram Options', 'NybInstagram', 'manage_options', 'nybinstagram-options', 'nybinstagram_options' );
}

function nybinstagram_options() {
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   } else {
      $nybinsta = create_nybinsta();      
      $nybinsta->get_settings_page();

   }
}

function nybinstagram_activate(){
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   } else {   
      $nybinsta = create_nybinsta();
      $nybinsta->create_options();
      $nybinsta->create_database();
   }
}

function nybinstagram_deactivate(){
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   } else {   
      $nybinsta = create_nybinsta();
      $nybinsta->create_options();
   }
}

function cron_add_schedule( $schedules ) {
   $nybinsta = create_nybinsta();
   // Adds once every minute to the existing schedules.
   $schedules['minute'] = array(
      'interval' => 60,//$nybinsta->aSettings['api-settings']['refresh-interval'],
      'display' => __( 'Once every minute' )
   );
   return $schedules;
}

function nybinstagram_docron(){
   $nybinsta = create_nybinsta();
   $nybinsta->rebuild_data();
   return true;
}

