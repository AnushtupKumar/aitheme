<?php
/**
 * The header for Astra-AI theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package Astra-AI
 * @version 1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preconnect to external domains for performance -->
    <link rel="preconnect" href="https://unpkg.com">
    <link rel="dns-prefetch" href="https://unpkg.com">
    
    <!-- Schema.org markup for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
        "url": "<?php echo esc_url(home_url()); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo esc_url(home_url()); ?>?s={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    
    <?php if (is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_product()) : ?>
    <!-- WooCommerce Schema markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Store",
        "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
        "url": "<?php echo esc_url(home_url()); ?>",
        "description": "<?php echo esc_js(get_bloginfo('description')); ?>"
    }
    </script>
    <?php endif; ?>
    
    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo esc_attr(wp_get_document_title()); ?>">
    <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="og:image" content="<?php echo esc_url(get_template_directory_uri() . '/assets/images/og-image.jpg'); ?>">
    
    <!-- Twitter Card meta tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr(wp_get_document_title()); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <meta name="twitter:image" content="<?php echo esc_url(get_template_directory_uri() . '/assets/images/og-image.jpg'); ?>">
    
    <!-- Theme color for mobile browsers -->
    <meta name="theme-color" content="#2563eb">
    <meta name="msapplication-TileColor" content="#2563eb">
    
    <!-- Prevent zoom on mobile for better UX -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="<?php echo esc_url(get_stylesheet_uri()); ?>" as="style">
    
    <?php wp_head(); ?>
    
    <!-- Remove no-js class when JavaScript is enabled -->
    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    </script>
    
    <!-- Critical CSS for above-the-fold content -->
    <style>
        /* Critical CSS for initial page load */
        .spa-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2563eb;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .hidden {
            display: none !important;
        }
        
        /* Prevent flash of unstyled content */
        #astra-ai-app {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        #astra-ai-app.loaded {
            opacity: 1;
        }
    </style>
</head>

<body <?php body_class(); ?>>

<?php
// WordPress 5.2+ wp_body_open hook
if (function_exists('wp_body_open')) {
    wp_body_open();
}
?>

<!-- Skip to content link for accessibility -->
<a class="skip-link screen-reader-text" href="#main-content">
    <?php esc_html_e('Skip to content', 'astra-ai'); ?>
</a>

<!-- Google Tag Manager (noscript) - Add your GTM code here if needed -->
<noscript>
    <!-- GTM noscript code goes here -->
</noscript>