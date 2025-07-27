<?php
/**
 * The main template file for Astra-AI Theme
 * 
 * This is the most generic template file in a WordPress theme
 * and serves as the SPA entry point for the Astra-AI theme.
 *
 * @package Astra-AI
 * @version 1.0.0
 */

get_header(); ?>

<div id="astra-ai-app">
    <!-- SPA Loading Screen -->
    <div class="spa-loading" id="spa-loading">
        <div class="spinner"></div>
    </div>
    
    <!-- Header Section -->
    <header class="astra-header">
        <div class="header-content">
            <!-- Logo -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo esc_html(get_bloginfo('name'));
                }
                ?>
            </a>
            
            <!-- AI-Powered Search -->
            <div class="search-container">
                <div class="search-icon">🔍</div>
                <input 
                    type="text" 
                    class="ai-search-input" 
                    placeholder="<?php esc_attr_e('Search products with AI...', 'astra-ai'); ?>"
                    id="ai-search-input"
                >
                <div id="search-results" class="search-results hidden"></div>
            </div>
            
            <!-- Header Actions -->
            <div class="header-actions">
                <!-- User Account -->
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="user-account">
                        👤
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="user-account">
                        🔐
                    </a>
                <?php endif; ?>
                
                <!-- Shopping Cart -->
                <div class="cart-icon" id="cart-icon">
                    🛒
                    <span class="cart-count" id="cart-count">
                        <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                    </span>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content Area -->
    <main class="main-content" id="main-content">
        
        <!-- AI Recommendations Section -->
        <section class="ai-recommendations">
            <div class="recommendations-container">
                <h2 class="section-title"><?php esc_html_e('Recommended For You', 'astra-ai'); ?></h2>
                <p class="section-subtitle"><?php esc_html_e('Discover products tailored to your preferences with our AI-powered recommendations', 'astra-ai'); ?></p>
                <div id="personalized-recommendations" class="products-grid">
                    <!-- AI recommendations will be loaded here -->
                </div>
            </div>
        </section>
        
        <!-- Featured Products Section -->
        <section class="featured-products">
            <div class="products-container">
                <h2 class="section-title"><?php esc_html_e('Featured Products', 'astra-ai'); ?></h2>
                <div id="featured-products" class="products-grid">
                    <!-- Featured products will be loaded here -->
                </div>
            </div>
        </section>
        
        <!-- All Products Section -->
        <section class="all-products">
            <div class="products-container">
                <h2 class="section-title"><?php esc_html_e('All Products', 'astra-ai'); ?></h2>
                <div id="all-products" class="products-grid">
                    <!-- All products will be loaded here -->
                </div>
                
                <!-- Load More Button -->
                <div class="load-more-container" style="text-align: center; margin: 40px 0;">
                    <button id="load-more-products" class="load-more-btn" style="background: #2563eb; color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 16px; cursor: pointer;">
                        <?php esc_html_e('Load More Products', 'astra-ai'); ?>
                    </button>
                </div>
            </div>
        </section>
        
    </main>
    
    <!-- Product Modal for Quick View -->
    <div id="product-modal" class="product-modal hidden">
        <div class="modal-overlay" id="modal-overlay"></div>
        <div class="modal-content">
            <button class="modal-close" id="modal-close">×</button>
            <div id="modal-product-content">
                <!-- Product details will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Cart Sidebar -->
    <div id="cart-sidebar" class="cart-sidebar hidden">
        <div class="cart-overlay" id="cart-overlay"></div>
        <div class="cart-content">
            <div class="cart-header">
                <h3><?php esc_html_e('Shopping Cart', 'astra-ai'); ?></h3>
                <button class="cart-close" id="cart-close">×</button>
            </div>
            <div id="cart-items" class="cart-items">
                <!-- Cart items will be loaded here -->
            </div>
            <div class="cart-footer">
                <div class="cart-total" id="cart-total">
                    <!-- Cart total will be displayed here -->
                </div>
                <div class="cart-actions">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="view-cart-btn">
                        <?php esc_html_e('View Cart', 'astra-ai'); ?>
                    </a>
                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-btn">
                        <?php esc_html_e('Checkout', 'astra-ai'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Fallback content for non-JS users -->
<noscript>
    <div class="no-js-fallback" style="padding: 40px 20px; text-align: center; background: #f8fafc;">
        <h2><?php esc_html_e('JavaScript Required', 'astra-ai'); ?></h2>
        <p><?php esc_html_e('This theme requires JavaScript to be enabled for the best experience. Please enable JavaScript in your browser settings.', 'astra-ai'); ?></p>
        
        <!-- Basic product listing for non-JS users -->
        <div class="basic-products" style="margin-top: 40px;">
            <?php
            $products = wc_get_products(array(
                'limit' => 12,
                'status' => 'publish',
                'visibility' => 'visible'
            ));
            
            if ($products) {
                echo '<div class="products-grid">';
                foreach ($products as $product) {
                    echo '<div class="product-card">';
                    echo '<img src="' . esc_url(wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-thumb')[0]) . '" alt="' . esc_attr($product->get_name()) . '" class="product-image">';
                    echo '<div class="product-info">';
                    echo '<h3 class="product-title">' . esc_html($product->get_name()) . '</h3>';
                    echo '<div class="product-price">' . $product->get_price_html() . '</div>';
                    echo '<a href="' . esc_url(get_permalink($product->get_id())) . '" class="view-product-btn">' . esc_html__('View Product', 'astra-ai') . '</a>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
</noscript>

<?php get_footer(); ?>