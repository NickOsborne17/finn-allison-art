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
                <div class="project-tags">
                    <?php
                    $post_type = get_post_type();
                    $taxonomies = get_object_taxonomies($post_type);
                    $all_terms = array();

                    foreach ($taxonomies as $taxonomy) {
                        $terms = get_the_terms(get_the_ID(), $taxonomy);
                        
                        if ($terms && !is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                $all_terms[] = $term->name;
                            }
                        }
                    }

                    if (!empty($all_terms)) {
                        echo implode(' / ', $all_terms);
                    }
                    ?>
                </div>
            </header>

            <div class="section">
                <div class="section-inner divider"></div>
            </div>

            <div class="project-body">
                <?php the_content(); ?>
            </div>

            <?php /*
            <div class="section project-pagination">
                <div class="section-inner pagination-controls">
                    <?php next_post_link('%link', '<span class="i-chevron left pag-btn-link"></span> <span class="pag-btn-text">%title</span>'); ?>
                    <a href="#">BACK TO GALLERY</a>
                    <?php previous_post_link('%link', '<span class="pag-btn-text">%title</span> <span class="i-chevron right pag-btn-link"></span>'); ?>
                </div>
            </div>
            */ ?>

        </article>

    <?php endwhile; ?>
</main>

<?php
get_footer();
?>