<?php
/**
 * Template part for displaying page content in page.php
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="page-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'finnallison' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
