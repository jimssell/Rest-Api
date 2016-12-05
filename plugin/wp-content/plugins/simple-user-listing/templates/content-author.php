<?php
/**
 * The Template for displaying Author listings
 *
 * Override this template by copying it to yourtheme/authors/content-author.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $user;

$user_info = get_userdata( $user->ID );
$num_posts = count_user_posts( $user->ID );
?>

  <div class="col-sm-6 col-md-4" >
    <div class="thumbnail">
    <div id="myimg">
      <?php echo get_avatar( $user->ID, 200); ?>
     </div>
      <div class="caption" id="user-<?php echo $user->ID; ?>">
        <h3>			<?php if ( $num_posts > 0 ) { 

						printf( '<a href="%s" title="%s">%s</a> <span class="post-count"><span class="hyphen">-</span> %s</span>', 

							get_author_posts_url( $user->ID ),
							sprintf( esc_attr__( 'Read posts by %s', 'simple-user-listing' ), $user_info->display_name ),
							$user_info->display_name,
							sprintf( _nx( '1 post', '%s posts', $num_posts, 'number of posts', 'simple-user-listing' ), $num_posts )

						);

					?>	
				<?php } else {
						echo $user_info->display_name;
					} ?>	
		</h3>
        <p><?php if( $user_info->description ): ?>
	<p><?php echo $user_info->description; ?></p>
<?php endif; ?></p>
<p>posted by <?php the_author(); ?> at <?php the_time(); ?></p>
      </div>
    </div>
  </div>
