<?php

/**
 * EVF Contacts Post Type
 *
 * @package             evf-contacts
 * @author              Michiel Uitdehaag
 * @copyright           2023 Michiel Uitdehaag for muis IT
 * @licenses            GPL-3.0-or-later
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

 namespace EVFContacts;

 class Main {
    public function init() {
        register_post_type( 'evfcontact',
            array(
                'labels' => array(
                    'name' => __( 'Contacts' ),
                    'singular_name' => __( 'Contact' ),
                    'menu_name' => __('Contacts'),
                    'all_items' => __('All contacts'),
                    'add_new_item' => __('New contact'),
                    'edit_item' => __('Edit contact'),
                    'update_item' => __('Update contact'),
                    'search_items' => __('Search contact'),                    
                ),
                'label' => 'contact',
                'description' => __('EVF Contact information'),
                'public' => true,
                'publicly_queryable'=> true,
                'has_archive' => false,
                'rewrite' => array('slug' => 'evfcontact'),
                'show_in_rest' => true,
                'hierarchical'=>false,
                'exclude_from_search'=>false,
                'supports'=>array('title','editor','custom-fields','author','comments'),
            )
        );
    }
}