<?php
/**
 * The template for displaying the footer
 */

?>

<footer>
<div class="section">
<div class="section-inner footer-content">
<?php
$social_links = carbon_get_theme_option('crb_social_links');

function get_footer_message($number) {
    return [
        'text' => str_replace('{year}', date('Y'), 
            carbon_get_theme_option("crb_footer_message_{$number}_text")),
        'colour' => carbon_get_theme_option("crb_footer_message_{$number}_colour"),
        'size' => carbon_get_theme_option("crb_footer_message_{$number}_size"),
    ];
}

// Footer text
echo '<div class="footer-message">';
for ($i = 1; $i <= 2; $i++) {
    $message = get_footer_message($i);
    
    if (!empty($message['text'])) {
        echo "<p style='color: {$message['colour']};'>{$message['text']}</p>";
    }
}
echo '</div>';

// Social  Links
if (!empty($social_links)) : ?>
    <div class="social-links-container">
        <p>Get in touch:</p>
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
    </div>
<?php endif; ?>

</div>
</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
