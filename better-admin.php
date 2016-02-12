<?php
/**
 * Plugin Name: Better Admin
 * Description: An opinionated set of mods to improve the WP admin panel.
 * Version:     1.0.0
 * Author:      Nickolas Kenyeres
 * Author URI:  http://www.knicklabs.com
 * License:     GPLv2 or later
 * Text Domain: better-admin
 */

class BetterAdmin {
  public function __construct() {
    $this->plugin_file = __FILE__;
    $this->plugin_basename = plugin_basename($this->plugin_file);

    $this->add_actions();
    $this->add_filters();

    $this->remove_actions();
  }

  public function add_actions() {
    add_action('wp_dashboard_setup', array($this, 'remove_dashboard_widgets'));
    add_action('admin_menu', array($this, 'remove_version_number'));
  }

  public function add_filters() {
    add_filter('admin_bar_menu', array($this, 'replace_howdy'), 100, 1);
    add_filter('admin_bar_menu', array($this, 'remove_comments'), 100, 1);
    add_filter('admin_bar_menu', array($this, 'remove_logo'), 100, 1);
    add_filter('admin_footer_text', array($this, 'remove_thank_you'), 100, 1);
    add_filter('contextual_help', array($this, 'remove_contextual_help'), 100, 3 );
    add_filter('screen_options_show_screen', '__return_false');
  }

  public function remove_actions() {
    remove_action('welcome_panel', 'wp_welcome_panel');
  }

  public function remove_dashboard_widgets() {
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');       // Right Now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Incoming Links
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');         // Plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');       // Quick Press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');     // Recent Drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'side');           // WordPress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');         // Other WordPress News
  }

  public function remove_version_number() {
    remove_filter('update_footer', 'core_update_footer');
  }

  public function replace_howdy($wp_admin_bar) {
    $account = $wp_admin_bar->get_node('my-account');
    $title   = str_replace('Howdy', 'Welcome', $account->title);

    $wp_admin_bar->add_node(array(
      'id'    => 'my-account',
      'title' => $title,
    ));
  }

  public function remove_comments($wp_admin_bar) {
     $wp_admin_bar->remove_menu('comments');
  }

  public function remove_logo($wp_admin_bar) {
    $wp_admin_bar->remove_menu('wp-logo');
  }

  public function remove_thank_you($text) {
    return '';
  }

  public function remove_contextual_help($old_help, $screen_id, $screen) {
    $screen->remove_help_tabs();
    return $old_help;
  }
}

$better_admin = new BetterAdmin();
