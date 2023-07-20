<?php

/**
 * EVF Contact Post Type
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

 class Templates {
    public function deregister() {
        wp_deregister_style( 'wp-mp-register-login-bootstrap' );
    }

    public function load($template, $post, $type) {
        $name = $post->post_name;
        switch($type) {
        case 'single':
            // search for a post-type-specific template in the theme template folders
            $specific_template = locate_template(array("single-contact-$name.php",'single-contact.php'));
            if($specific_template !== $template && !empty($specific_template)) {
                return $specific_template;
            }
            // locate_template returns the default template, so return our local version
            return plugin_dir_path(__FILE__) . 'templates/single-contact.php';
            break;
        default:
            break;
        }
        return $template;
    }

    public function shortCode($attributes) {
        //$attributes = shortcode_atts( array(
        //    'foo' => 'default',
        //), $attributes );

        // find all posts of type 'coach'
        $showType = true;
        $args = array(
            'paging' => true,
            'posts_per_page' => -1,
            'post_type' => 'evfcontact',
            'perm' => 'readable',
            'post_status' => 'publish',
            'order' => 'ASC',
            'post_status' => array('publish'),            
        );
        if (!empty($attributes)) {
            $meta = [];
            if (isset($attributes['type']) && $attributes['type'] && in_array($attributes['type'], ['coach', 'referee'])) {
                $meta = ['relation'=>'AND'];
                $meta[] = [
                    'key' => 'type',
                    'value' => $attributes['type'],
                    'compare' => '='
                ];
                $showType = false;
            }
            if (isset($attributes['state']) && $attributes['state'] && in_array($attributes['state'], ['active', 'retired'])) {
                $meta = ['relation'=>'AND'];
                $meta[] = [
                    'key' => 'state',
                    'value' => $attributes['state'],
                    'compare' => '='
                ];
            }
            if (isset($attributes['order']) && $attributes['order'] && in_array($attributes['order'], ['name', 'type', 'country'])) {
                switch($attributes['order']) {
                    case 'name':
                        $args['orderby'] = 'title';
                        $args['order'] = 'ASC';
                        break;
                    case 'type':
                        $args['orderby'] = 'meta_value';
                        $args['meta_key'] = 'type';
                        $args['order'] = 'ASC';
                        break;
                    case 'country':
                        $args['orderby'] = 'meta_value';
                        $args['meta_key'] = 'country';
                        $args['order'] = 'ASC';
                        break;
                }
            }
            if (!empty($meta)) {
                $args['meta_query'] = $meta;
            }
        }
        $posts = new \WP_Query($args);

        $idx=0;
        $output = <<<HEREDOC
        <div class='container contacts-list'>
            <div class='row header'>
                <div class='col-6 col-md-2'></div>
                <div class='col-6 col-md-2'>Name</div>
                <div class='col-6 col-md-1'>Type</div>
                <div class='col-6 col-md-1'>E-mail</div>
                <div class='col-6 col-md-2'>Tel</div>
                <div class='col-6 col-md-3'>Address</div>
                <div class='col-6 col-md-1'>Country</div>
            </div>
        HEREDOC;
        while ( $posts->have_posts() ) {
            $posts->the_post();
            global $post;

            $title = '<a class="title" href="' . apply_filters( 'the_permalink', get_permalink() ) . '">' . get_the_title() . '</a>';

            $photo = get_field('photo');
            if( $photo ) {
                $photo = wp_get_attachment_image( $photo, 'thumbnail', false, ['class' => 'thumbnail-image'] );
            }
            else {
                $photo = 'empty';
            }

            $type = get_field('type');
            $country = get_field('country');
            $email = get_field('email');
            $address = get_field('address');
            $telephone = get_field('telephone');

            if(!empty($email)) {
                $btnimage = wp_get_attachment_image( 68, 'thumbnail', false, ['class'=>'email-image']);
                if (empty($btnimage)) {
                    $btnimage = 'email';
                }
                $email="mailto:$email";
                $email = '<a class="email" href="' . esc_url($email,['mailto']) . '">' . $btnimage . '</a>';
            }

            $oddoreven = ($idx % 2 == 0) ? "even":"odd";
            $idx+=1;
            $output.=<<<HEREDOC
  <div class='row $oddoreven'>
    <div class='col-6 col-md-2 photo'>$photo</div>
    <div class='col-6 col-md-2 title'>$title</div>
    <div class='col-6 col-md-1 type'>$type</div>
    <div class='col-6 col-md-1 email'>$email</div>
    <div class='col-6 col-md-2 telephone'>$telephone</div>
    <div class='col-6 col-md-3 address'>$address</div>
    <div class='col-6 col-md-1 country'>$country</div>
  </div>
HEREDOC;
        }
        $output.="</div>";

        return $output;
    }
}
