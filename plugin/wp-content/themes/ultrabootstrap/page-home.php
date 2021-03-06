<?php
/**
 * Template Name: Front Page 
 * The template used for displaying fullwidth page content in fullwidth.php
 *
 * @package ultrabootstrap
 */
get_header(); ?>


<!-- theme-slider -->
<section class="theme-slider">
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

  			<!-- Wrapper for slides -->
  			<div class="carousel-inner" role="listbox">
          <?php
            $cn = get_theme_mod('slider_no_of_posts');
    				$cid = get_theme_mod('slider_category_display');
    				$category_link = get_category_link($cid);
    				$ultrabootstrap_cat = get_category($cid);
    				if ($ultrabootstrap_cat) {
        	?>

        	<?php
            $args = array(
              'posts_per_page' => $cn,
              'paged' => 1,
              'cat' => $cid
            );
            $loop = new WP_Query($args);
            
            $cn = 0;
            if ($loop->have_posts()) :  while ($loop->have_posts()) : $loop->the_post();$cn++;
          ?>

    			<div class="item <?php echo $cn == 1 ? 'active' : ''; ?>">
  						  <?php if(has_post_thumbnail()){
                	$arg =
                  	array(
                      'class' => 'img-responsive',
                      'alt' => ''
                		);
                		the_post_thumbnail('',$arg);
              	  } 
                ?>
            <div class="slide-caption">
              <div class="container">
                <div class="slide-caption-details">
                <h4><?php the_title();?></h4>
                <div class="summary"><?php the_excerpt('ultrabootstrap_excerpt_length');?></div>
                <a href="<?php the_permalink();?>" title="" class="btn btn-danger">View</a>
                </div>
              </div>
            </div>
      		</div> <!-- /.end of item -->
        
    			<?php                 
      			endwhile;
      				wp_reset_postdata();  
      			endif;                             
    				}
    			?> 
        </div>  <!-- /.end of carousel inner -->

        <!-- Controls -->
				<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
  				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"><i class="fa fa-angle-left"></i></span>
  				<span class="sr-only"><?php _e('Previous' , 'ultrabootstrap' ); ?></span>
				</a>
				<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
  				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"><i class="fa fa-angle-right"></i></span>
  				<span class="sr-only"><?php _e('Next' , 'ultrabootstrap' ); ?></span>
				</a>

      </div> <!-- /.end of carousel example -->
</section> <!-- /.end of section -->
<!-- theme-slider -->




<!-- welcome message -->
<section class="welcome">
  	<div class="container">
      <div class="row">
        <div class="col-sm-7 spacer">
          			<h4><?php echo esc_attr(get_theme_mod( 'welcome_textbox1', '' )); ?></h1>
          			<p><?php echo esc_attr(get_theme_mod( 'textarea_setting', '' )); ?></p> 
          			<a href="<?php echo esc_attr(get_theme_mod( 'welcome_button', '' )); ?>" title="Read More" class="btn btn-danger">Read More</a>
        </div> 
        <div class="col-sm-4 col-sm-offset-1">
          <div class="spacer message">
          <h1><?php echo esc_attr(get_theme_mod( 'welcome_textbox2', '' )); ?></h1>
          </div>
        </div>
      </div>          
    	</div>
</section>
<!-- welcome message -->


<!-- Header Image -->
<?php if (has_header_image()): ?>
<div class="text-center">
<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
</div>
<?php endif;?>
<!-- Header Image -->

<section style="padding-top: 50px;">
  <div class="container">
  <div class="row">
    <?php 
      echo do_shortcode( '[userlist number="5"]' );
    ?>
  </div>
  </div>
</section>

<!-- post list  -->
<section class="post-list spacer">
  	<div class="container">
          <?php
            $cid = get_theme_mod('features_display');
            $category_link = get_category_link($cid);
            $ultrabootstrap_cat = get_category($cid);
            if ($ultrabootstrap_cat) {
          ?>

          <div class="clearfix">
            <h4><?php echo esc_attr(get_theme_mod( 'features_title', __('Featured Category' , 'ultrabootstrap') )); ?></h4>
            <a href="<?php echo esc_url( $category_link ); ?>" title="" class="pull-right">View All</a>
          </div>

          <div class="row">

  		    <?php
  		        $args = array(
  		            'posts_per_page' => 8,
  		            'paged' => 1,
  		            'cat' => $cid
  		        );
  		        $loop = new WP_Query($args);                                   
  		        if ($loop->have_posts()) :  while ($loop->have_posts()) : $loop->the_post();
  		    ?>

      		<div class="col-lg-3 col-md-4 col-sm-6 eq-blocks">
              <div class="post-block">
                <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" rel="bookmark" class="featured-image"><?php the_post_thumbnail('home-thumb'); ?></a>
                <?php else : ?>
                <a href="<?php the_permalink(); ?>" rel="bookmark" class="featured-image"><img src="<?php echo get_template_directory_uri(); ?>/images/no-blog-thumbnail.jpg" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" class="img-responsive" /></a>
              <?php endif; ?>  
              <!-- summary -->
              <div class="summary">
          		<h3><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title();?></a></h3>
              
          		<?php the_excerpt('ultrabootstrap_excerpt_length');?>
             
              <a href="<?php the_permalink(); ?>" rel="bookmark" class="readmore">Read More</a>
              </div>
                 <!-- summary -->
              </div>
      		</div>

      		<?php
		        endwhile;
		          	wp_reset_postdata();
		        endif; }
		    ?>
      </div>
		     	</div>  <!-- /.end of container -->
</section>  <!-- /.end of section -->
<!-- post list  -->
<?php get_footer();?>