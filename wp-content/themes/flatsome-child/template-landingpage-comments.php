<?php
/**
 * Template Name: Landingpage comments
 *
 */

get_header('landingpage');
?>
<style type="text/css">
	.container-masonry {
		display: flex;
		flex-flow: column wrap;
		align-content: space-between;
		/* Your container needs a fixed height, and it
		* needs to be taller than your tallest column. */
		height: 665px;

		/* Optional */
		background-color: rgba(255, 193, 7, 0.3);
		border-radius: 3px;
		padding: 20px;
		width: 60%;
		margin: 40px auto;
		counter-reset: items;
	}

	.container-masonry .item {
		width: 32%;
		position: relative;
		margin-bottom: 2%;
		background-color: #fff;
		color: #000;
		padding: 15px;
		box-sizing: border-box;
		box-shadow: 3px 3px 3px #ffe293;
	}

	/* Re-order items into 3 rows */
	.container-masonry .item:nth-child(3n+1) { order: 1; }
	.container-masonry .item:nth-child(3n+2) { order: 2; }
	.container-masonry .item:nth-child(3n)   { order: 3; }

	/* Force new columns */
	.container-masonry::before,
	.container-masonry::after {
		content: "";
		flex-basis: 100%;
		width: 0;
		order: 2;
	}
	.container-masonry .item .fa-star {
		color: #e0b952;
	}
	.container-masonry .item p {
		margin-bottom: 2px;
	}
	.container-masonry .item p.comment-text {
		font-size: 13px;
		margin-bottom: 20px;
	}
	@media only screen and (max-width: 450px) {
		.container-masonry {
			width: 100%;
			height: 800px;
		}
		.container-masonry .item {
			width: 48%;
		}
		.container-masonry .item:nth-child(2n+1) { order: 1; }
		.container-masonry .item:nth-child(2n+2) { order: 2; }
		.container-masonry .item:nth-child(2n)   { order: 3; }
	}

</style>
<?php
do_action( 'flatsome_before_page' ); ?>
<div id="content" class="content-area page-wrapper" role="main">
	<div class="row row-main">
		<div class="col">
			<div class="col-inner">

				<?php if(get_theme_mod('default_title', 0)){ ?>
				<header class="entry-header">
					<h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
				</header><!-- .entry-header -->
				<?php } ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php do_action( 'flatsome_before_page_content' ); ?>

					<?php $comments_include = get_field('landingpage_comments');?>

					<?php if( $comments_include == true ):
						if( have_rows('comments') ): ?>
							<div class="container-masonry">
	    						<?php while ( have_rows('comments') ) : the_row(); ?>
									<div class="item" style="height: auto">
										<?php $stars = get_sub_field('comment_rating'); ?>
										<?php for($i=1;$i<=$stars;$i++): ?>
											<i class="fa fa-star"></i>
										<?php endfor; ?>
										<p><?php the_sub_field('comment_author'); ?></p>
										<p class="comment-text"><?php the_sub_field('comment_text'); ?></p>
										<img style="width:100px" src="<?php echo get_sub_field('comment_image'); ?>">
									</div>
	        					<?php endwhile; ?>
        					</div>
						<?php endif; ?>

					<?php endif; ?>

						<?php the_content(); ?>

						<?php if ( comments_open() || '0' != get_comments_number() ){
							comments_template(); } ?>

					<?php do_action( 'flatsome_after_page_content' ); ?>
				<?php endwhile; // end of the loop. ?>
			</div><!-- .col-inner -->
		</div><!-- .large-12 -->
	</div><!-- .row -->
</div>

<?php
do_action( 'flatsome_after_page' );
get_footer();

?>
