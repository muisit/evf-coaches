<?php
/**
 * evf-contacts
 *
 * @package             evf-contacts
 * @author              Michiel Uitdehaag
 * @copyright           2023 Michiel Uitdehaag for muis IT
 * @licenses            GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:         evf-contacts
 * Plugin URI:          https://github.com/muisit/evf-contacts
 * Description:         EVF specific plugin for a contacts-post-type
 * Version:             1.0.1
 * Requires at least:   5.4
 * Requires PHP:        7.2
 * Author:              Michiel Uitdehaag
 * Author URI:          https://www.muisit.nl
 * License:             GNU GPLv3
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:         evf-contacts
 * Domain Path:         /languages
 *
 * This file is part of evf-contacts.
 *
 * evf-contacts is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * evf-contacts is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with evf-contacts.  If not, see <https://www.gnu.org/licenses/>.
 */


function evfcontacts_activate() {
    require_once(__DIR__.'/activate.php');
    $activator = new \EVFContacts\Activator();
    $activator->activate();
}

function evfcontacts_deactivate() {
    require_once(__DIR__.'/activate.php');
    $activator = new \EVFContacts\Activator();
    $activator->deactivate();
}

function evfcontacts_init() {
    require_once(__DIR__.'/main.php');
    $actor = new \EVFContacts\Main();
    $actor->init();
}

function evfcontacts_save($postid) {
    $editor = new \EVFContacts\Editor();
    $editor->save($postid);
}

function evfcontacts_loadpost() {
    require_once(__DIR__.'/editor.php');
}

function evfcontacts_load_single($template) {
    global $post;

    if ('evfcontact' === $post->post_type) {
        require_once(__DIR__ . '/templates.php');
        $actor = new \EVFContacts\Templates();
        return $actor->load($template, $post, "single");
    }
    return $template;
}

function evfcontacts_shortcode($atts)
{
    require_once(__DIR__ . '/templates.php');
    $actor = new \EVFContacts\Templates();
    return $actor->shortCode($atts);
}

function evfcontacts_print_styles()
{
    require_once(__DIR__ . '/templates.php');
    $actor = new \EVFContacts\Templates();
    $actor->deregister();
}

function evfcontacts_filter_authors($args)
{
    if ( isset($args['who'])) {
        $args['role__in'] = ['author', 'editor', 'contributor', 'administrator', 'evfcontact'];
        unset($args['who']);
    }
    return $args;
} 


if (defined('ABSPATH')) {
    register_activation_hook( __FILE__, 'evfcontacts_activate' );
    register_deactivation_hook( __FILE__, 'evfcontacts_deactivate' );

    add_action('init', 'evfcontacts_init' );
    add_filter('single_template', 'evfcontacts_load_single', 99);

    // action to adjust the edit screen
    add_action('load-post.php', 'evfcontacts_loadpost');
    add_action('load-post-new.php', 'evfcontacts_loadpost');

    // add the listing short code
    add_shortcode( 'evf-contacts', 'evfcontacts_shortcode' );
    add_action( 'wp_print_styles', 'evfcontacts_print_styles', 100 );
    add_filter('wp_dropdown_users_args', 'evfcontacts_filter_authors');
}
