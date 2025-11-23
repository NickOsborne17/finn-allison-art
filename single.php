<?php
/**
 * The template for displaying all single posts
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->

                <footer class="entry-footer">
                    <?php wp_print_footer_scripts(); ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->

        <?php
		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();