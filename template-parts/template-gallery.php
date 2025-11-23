<?php /* Template Name: Gallery */

get_header();

?>
<div class="page">
    <div class="section gallery-filter">
        <div class="section-inner filter-container">
            <div id="gallery-filters"></div>
        </div>
    </div>
    <div class="section gallery-feed">
        <div id="gallery-feed" class="section-inner feed-container">
            <?php
            // Generate placeholders
            $i = 0;
            while($i < 6) {
                echo '<div class="gallery-item loading"></div>';
                $i++;
            }
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
wp_footer();