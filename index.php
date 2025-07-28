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

<div id="astra-ai-app" class="min-h-screen flex flex-col bg-gray-50">
    <!-- SPA Loading Screen -->
    <div class="spa-loading fixed inset-0 bg-white/95 flex justify-center items-center z-50 backdrop-blur-sm" id="spa-loading">
        <div class="spinner border-4 border-gray-200 border-t-primary rounded-full w-12 h-12 animate-spin"></div>
    </div>
    
    <!-- Header Section -->
    <header class="bg-white shadow-lg sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#" class="spa-link text-2xl font-bold text-primary hover:text-primary/80 transition-colors" data-route="home">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            echo esc_html(get_bloginfo('name'));
                        }
                        ?>
                    </a>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="hidden md:flex space-x-8">
                    <a href="#" class="spa-link text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors" data-route="home">
                        <?php esc_html_e('Home', 'astra-ai'); ?>
                    </a>
                    <a href="#" class="spa-link text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors" data-route="shop">
                        <?php esc_html_e('Shop', 'astra-ai'); ?>
                    </a>
                    <a href="#" class="spa-link text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors" data-route="categories">
                        <?php esc_html_e('Categories', 'astra-ai'); ?>
                    </a>
                    <a href="#" class="spa-link text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors" data-route="about">
                        <?php esc_html_e('About', 'astra-ai'); ?>
                    </a>
                    <a href="#" class="spa-link text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors" data-route="contact">
                        <?php esc_html_e('Contact', 'astra-ai'); ?>
                    </a>
                </nav>
                
                <!-- AI-Powered Search -->
                <div class="flex-1 max-w-lg mx-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-full leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition-all duration-200"
                            placeholder="<?php esc_attr_e('Search products with AI...', 'astra-ai'); ?>"
                            id="ai-search-input"
                        >
                        <div id="search-results" class="absolute top-full left-0 right-0 bg-white shadow-lg rounded-lg mt-1 hidden z-50 max-h-96 overflow-y-auto"></div>
                    </div>
                </div>
                
                <!-- Header Actions -->
                <div class="flex items-center space-x-4">
                    <!-- User Account -->
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="text-gray-700 hover:text-primary transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="text-gray-700 hover:text-primary transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Shopping Cart -->
                    <button class="relative text-gray-700 hover:text-primary transition-colors" id="cart-icon">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h7M9 18h7" />
                        </svg>
                        <span class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" id="cart-count">
                            <?php echo (class_exists('WooCommerce') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0; ?>
                        </span>
                    </button>
                    
                    <!-- Mobile menu button -->
                    <button class="md:hidden text-gray-700 hover:text-primary" id="mobile-menu-button">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                <a href="#" class="spa-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50 transition-colors" data-route="home">
                    <?php esc_html_e('Home', 'astra-ai'); ?>
                </a>
                <a href="#" class="spa-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50 transition-colors" data-route="shop">
                    <?php esc_html_e('Shop', 'astra-ai'); ?>
                </a>
                <a href="#" class="spa-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50 transition-colors" data-route="categories">
                    <?php esc_html_e('Categories', 'astra-ai'); ?>
                </a>
                <a href="#" class="spa-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50 transition-colors" data-route="about">
                    <?php esc_html_e('About', 'astra-ai'); ?>
                </a>
                <a href="#" class="spa-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50 transition-colors" data-route="contact">
                    <?php esc_html_e('Contact', 'astra-ai'); ?>
                </a>
            </div>
        </div>
    </header>
    
    <!-- Main Content Area -->
    <main class="flex-1" id="main-content">
        <!-- Content will be dynamically loaded here -->
        <div id="spa-content">
            <!-- Default Home Page Content -->
            <div id="home-page" class="page-content">
                <!-- Hero Section -->
                <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h1 class="text-4xl md:text-6xl font-bold mb-6">
                            <?php esc_html_e('AI-Powered Shopping Experience', 'astra-ai'); ?>
                        </h1>
                        <p class="text-xl md:text-2xl mb-8 text-blue-100">
                            <?php esc_html_e('Discover products tailored just for you with our intelligent recommendations', 'astra-ai'); ?>
                        </p>
                        <button class="spa-link bg-white text-primary px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transition-colors" data-route="shop">
                            <?php esc_html_e('Start Shopping', 'astra-ai'); ?>
                        </button>
                    </div>
                </section>
                
                <!-- AI Recommendations Section -->
                <section class="py-16 bg-white">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-12">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                <?php esc_html_e('Recommended For You', 'astra-ai'); ?>
                            </h2>
                            <p class="text-xl text-gray-600">
                                <?php esc_html_e('Discover products tailored to your preferences with our AI-powered recommendations', 'astra-ai'); ?>
                            </p>
                        </div>
                        <div id="personalized-recommendations" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- AI recommendations will be loaded here -->
                        </div>
                    </div>
                </section>
                
                <!-- Featured Products Section -->
                <section class="py-16 bg-gray-50">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-12">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                <?php esc_html_e('Featured Products', 'astra-ai'); ?>
                            </h2>
                        </div>
                        <div id="featured-products" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Featured products will be loaded here -->
                        </div>
                    </div>
                </section>
                
                <!-- All Products Section -->
                <section class="py-16 bg-white">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-12">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                <?php esc_html_e('All Products', 'astra-ai'); ?>
                            </h2>
                        </div>
                        <div id="all-products" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- All products will be loaded here -->
                        </div>
                        
                        <!-- Load More Button -->
                        <div class="text-center mt-12">
                            <button id="load-more-products" class="bg-primary text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-primary/90 transition-colors">
                                <?php esc_html_e('Load More Products', 'astra-ai'); ?>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
    
    <!-- Product Modal for Quick View -->
    <div id="product-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-xl font-semibold">Product Details</h3>
                <button class="text-gray-400 hover:text-gray-600" id="modal-close">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="modal-product-content" class="p-6">
                <!-- Product details will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Cart Sidebar -->
    <div id="cart-sidebar" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" id="cart-overlay"></div>
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl flex flex-col">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900"><?php esc_html_e('Shopping Cart', 'astra-ai'); ?></h3>
                <button class="text-gray-400 hover:text-gray-600" id="cart-close">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="cart-items" class="flex-1 overflow-y-auto p-6">
                <!-- Cart items will be loaded here -->
            </div>
            <div class="border-t p-6">
                <div id="cart-total" class="mb-4">
                    <!-- Cart total will be displayed here -->
                </div>
                <div class="space-y-3">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="block w-full bg-gray-100 text-gray-900 text-center py-3 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                        <?php esc_html_e('View Cart', 'astra-ai'); ?>
                    </a>
                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="block w-full bg-primary text-white text-center py-3 px-4 rounded-lg hover:bg-primary/90 transition-colors">
                        <?php esc_html_e('Checkout', 'astra-ai'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Fallback content for non-JS users -->
<noscript>
    <div class="p-10 text-center bg-gray-50">
        <h2 class="text-2xl font-bold text-gray-900 mb-4"><?php esc_html_e('JavaScript Required', 'astra-ai'); ?></h2>
        <p class="text-gray-600 mb-8"><?php esc_html_e('This theme requires JavaScript to be enabled for the best experience. Please enable JavaScript in your browser settings.', 'astra-ai'); ?></p>
        
        <!-- Basic product listing for non-JS users -->
        <div class="max-w-7xl mx-auto">
            <?php
            $products = wc_get_products(array(
                'limit' => 12,
                'status' => 'publish',
                'visibility' => 'visible'
            ));
            
            if ($products) {
                echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">';
                foreach ($products as $product) {
                    echo '<div class="bg-white rounded-lg shadow-md overflow-hidden">';
                    echo '<img src="' . esc_url(wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-thumb')[0]) . '" alt="' . esc_attr($product->get_name()) . '" class="w-full h-48 object-cover">';
                    echo '<div class="p-4">';
                    echo '<h3 class="font-semibold text-gray-900 mb-2">' . esc_html($product->get_name()) . '</h3>';
                    echo '<div class="text-primary font-bold mb-3">' . $product->get_price_html() . '</div>';
                    echo '<a href="' . esc_url(get_permalink($product->get_id())) . '" class="block w-full bg-primary text-white text-center py-2 px-4 rounded hover:bg-primary/90 transition-colors">' . esc_html__('View Product', 'astra-ai') . '</a>';
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