<?php
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
            for ($i = 0; $i < 6; $i++) {
                echo '<div class="gallery-item loading"></div>';
            }
            ?>
        </div>
    </div>
</div>

<?php
get_footer();