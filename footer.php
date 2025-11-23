<?php
/**
 * The template for displaying the footer
 */

?>

<footer>

<?php
$social_links = carbon_get_theme_option('crb_social_links');

if (!empty($social_links)) : ?>
    <ul class="social-links">
        <?php foreach ($social_links as $link) : ?>
            <li>
                <a href="<?php echo esc_url($link['url']); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   <?php if (!empty($link['label'])) : ?>
                       aria-label="<?php echo esc_attr($link['label']); ?>"
                   <?php endif; ?>>
                    <i class="<?php echo esc_attr($link['icon']); ?>"></i>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p>Copyright &copy; Finn Allison 2024</p>

</footer>

<?php wp_footer(); ?>

</body>
</html>
