<?php /* Template Name: Home */

$hero_image = carbon_get_the_post_meta('crb_hero_image');
$profile_image = carbon_get_the_post_meta('crb_profile_image');
$bio_content = carbon_get_the_post_meta('crb_bio_content');

get_header();

?>
<div class="page">
    <div class="hero-logo">
        <svg class="brand-nav" width="100%" height="100%" viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
            <circle class="logo-circle" cx="110" cy="110" r="110"/>
            <image href="/wp-content/uploads/2025/10/logo.png" x="33.5" y="36.5" width="153" height="175"/>
        </svg>
    </div>
    <div class="section home-hero">
        <div class="section-inner home-hero-content">
            <div class="hero-text">
                <h1 class="hero-heading">FINN ALLISON</h1>
                <span class="line"></span>
                <p class="hero-caption">ART PORTFOLIO</p>
            </div>
        </div>
        <div class="home-hero-background" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
            <div class="background-overlay"></div>
        </div>
    </div>
    <div class="header-trigger"></div>
    <div class="section home-bio">
        <div class="section-inner">
            <div class="bio-container">
                <div class="bio-image">
                    <img src="<?php echo esc_url($profile_image); ?>" alt="Profile" class="profile-image">
                </div>
                <div class="bio-content">
                    <?php echo wpautop($bio_content); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();