<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Your_Theme_Name
 */

get_header();
?>

<main id="primary" class="site-main">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( '404', 'underscores_child' ); ?></h1>
        </header>

        <div class="page-content">
            <p><?php esc_html_e( 'It looks like nothing was found at this location.', 'underscores_child' ); ?></p>

            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
                <?php esc_html_e( 'Return to homepage', 'underscores_child' ); ?>
            </a>
        </div>
    </section>
</main>

<?php
get_footer();