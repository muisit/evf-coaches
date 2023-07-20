<?php
get_header();
?>

<main id="site-content" role="main">
    <?php

    if (have_posts()) {

        while (have_posts()) {
            the_post();
?>
  <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
    <div class='entry-main container'>
      <header class="entry-header">
        <div class='row row-photo'>
          <div class='col-1 photo'>
<?php
            $image = get_field('photo');
            $size = array(400,200); // (thumbnail, medium, large, full or custom size)
            if( $image ) {
                echo wp_get_attachment_image( $image, $size );
            }
?>
          </div>
          <div class='col-8'>         
<?php
            the_title('<h1 class="entry-title">', '</h1>');
?>
            <h3><?php echo esc_html(ucfirst(get_field('type'))); ?>
          </div>
        </div>

        <div class='contact row'>
          <div class='col-1'><label class='coach-label' for='contact'><?php echo __('Contact') ?></label></div>
          <div id='contact' class='col-8'><?php echo esc_html(get_field('contact')); ?></div>
        </div>
        <div class='email row'>
          <div class='col-1'><label class='coach-label' for='email'><?php echo __('E-mail') ?></label></div>
          <?php if(!empty(get_field('contact'))) { ?>
          <div id='email' class='col-8'><a href='mailto:<?php echo esc_url(get_field('contact')); ?>'><?php echo esc_html(get_field('contact')); ?></a></div>
          <?php } ?>
        </div>
        <div class='telephone row'>
          <div class='col-1'><label class='coach-label' for='telephone'><?php echo __('Telephone') ?></label></div>
          <div id='telephone' class='col-8'><?php echo get_field('telephone'); ?></div>
        </div>
        <div class='address row'>
          <div class='col-1'><label class='coach-label' for='address'><?php echo __('Address') ?></label></div>
          <div id='address' class='col-8'><?php echo get_field('address'); ?></div>
        </div>
        <div class='country row'>
          <div class='col-1'><label class='coach-label' for='country'><?php echo __('Country') ?></label></div>
          <div id='country' class='col-8'><?php echo get_field('country'); ?></div>
        </div>
      </header>
    </div>
    <div class="entry-content container">
    
      <?php the_content( ); ?>
    </div>

    <div class="section-inner">
<?php    
            edit_post_link();
?>
    </div>

<?php
            /**
        	 * Output comments wrapper if it's a post, or if comments are open,
        	 * or if there's a comment number – and check for password.
        	 *
             */
	        if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
?>

    <div class="comments-wrapper section-inner">
<?php comments_template(); ?>
    </div>
<?php
             }
?>
  </article>
<?php
        }
    }
?>
</main>
<?php get_template_part('template-parts/footer-menus-widgets'); ?>
<?php get_footer(); ?>