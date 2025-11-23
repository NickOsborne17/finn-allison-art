<?php
/**
 * Template for single project posts
 */

get_header();
?>

<main class="project-content">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <header class="project-header">
                <h1 class="project-title"><?php the_title(); ?></h1>
            </header>

            <div class="project-body">
                <?php the_content(); ?>
            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php
get_footer();
?>