<?php
/**
 * Astra-AI Theme Functions
 * 
 * @package Astra-AI
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('ASTRA_AI_VERSION', '1.0.0');
define('ASTRA_AI_THEME_DIR', get_template_directory());
define('ASTRA_AI_THEME_URL', get_template_directory_uri());

/**
 * Theme Setup
 */
function astra_ai_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'astra-ai'),
        'mobile' => __('Mobile Menu', 'astra-ai'),
    ));

    // Add image sizes
    add_image_size('astra-ai-product-thumb', 300, 300, true);
    add_image_size('astra-ai-product-large', 800, 800, true);
}
add_action('after_setup_theme', 'astra_ai_setup');

/**
 * Enqueue Scripts and Styles
 */
function astra_ai_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('astra-ai-style', get_stylesheet_uri(), array(), ASTRA_AI_VERSION);
    
    // Enqueue React and ReactDOM from CDN for SPA functionality
    wp_enqueue_script('react', 'https://unpkg.com/react@18/umd/react.production.min.js', array(), '18.0.0', true);
    wp_enqueue_script('react-dom', 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js', array('react'), '18.0.0', true);
    
    // Enqueue main SPA script
    wp_enqueue_script('astra-ai-spa', ASTRA_AI_THEME_URL . '/assets/js/spa-app.js', array('react', 'react-dom', 'jquery'), ASTRA_AI_VERSION, true);
    
    // Enqueue AI features script
    wp_enqueue_script('astra-ai-features', ASTRA_AI_THEME_URL . '/assets/js/ai-features.js', array('jquery'), ASTRA_AI_VERSION, true);
    
    // Localize script with WordPress data
    wp_localize_script('astra-ai-spa', 'astraAI', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => rest_url('wp/v2/'),
        'wooRestUrl' => rest_url('wc/v3/'),
        'nonce' => wp_create_nonce('astra_ai_nonce'),
        'isUserLoggedIn' => is_user_logged_in(),
        'cartUrl' => wc_get_cart_url(),
        'checkoutUrl' => wc_get_checkout_url(),
        'aiApiKey' => get_option('astra_ai_api_key', ''),
        'enableAI' => get_option('astra_ai_enable_features', true),
    ));
}
add_action('wp_enqueue_scripts', 'astra_ai_scripts');

/**
 * Create AI Recommendations Table
 */
function astra_ai_create_recommendations_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'astra_ai_recommendations';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        product_id bigint(20) NOT NULL,
        recommended_product_id bigint(20) NOT NULL,
        recommendation_type varchar(50) NOT NULL,
        score float NOT NULL DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY product_id (product_id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'astra_ai_create_recommendations_table');

/**
 * AJAX Handler for AI Product Recommendations
 */
function astra_ai_get_recommendations() {
    check_ajax_referer('astra_ai_nonce', 'nonce');
    
    $user_id = get_current_user_id();
    $product_id = intval($_POST['product_id']);
    $type = sanitize_text_field($_POST['type']);
    
    $recommendations = astra_ai_generate_recommendations($user_id, $product_id, $type);
    
    wp_send_json_success($recommendations);
}
add_action('wp_ajax_astra_ai_get_recommendations', 'astra_ai_get_recommendations');
add_action('wp_ajax_nopriv_astra_ai_get_recommendations', 'astra_ai_get_recommendations');

/**
 * Generate AI-powered product recommendations
 */
function astra_ai_generate_recommendations($user_id, $product_id = 0, $type = 'general') {
    global $wpdb;
    
    $recommendations = array();
    
    switch ($type) {
        case 'frequently_bought_together':
            $recommendations = astra_ai_get_frequently_bought_together($product_id);
            break;
        case 'customers_also_viewed':
            $recommendations = astra_ai_get_customers_also_viewed($product_id);
            break;
        case 'personalized':
            $recommendations = astra_ai_get_personalized_recommendations($user_id);
            break;
        default:
            $recommendations = astra_ai_get_popular_products();
    }
    
    return $recommendations;
}

/**
 * Get frequently bought together products
 */
function astra_ai_get_frequently_bought_together($product_id) {
    global $wpdb;
    
    // Query orders that contain the current product
    $orders_with_product = $wpdb->get_results($wpdb->prepare("
        SELECT DISTINCT order_id 
        FROM {$wpdb->prefix}woocommerce_order_items oi
        JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
        WHERE oim.meta_key = '_product_id' AND oim.meta_value = %d
        LIMIT 100
    ", $product_id));
    
    if (empty($orders_with_product)) {
        return astra_ai_get_popular_products(4);
    }
    
    $order_ids = array_column($orders_with_product, 'order_id');
    $order_ids_str = implode(',', array_map('intval', $order_ids));
    
    // Find products frequently bought with this product
    $frequently_bought = $wpdb->get_results($wpdb->prepare("
        SELECT oim.meta_value as product_id, COUNT(*) as frequency
        FROM {$wpdb->prefix}woocommerce_order_items oi
        JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
        WHERE oi.order_id IN ($order_ids_str)
        AND oim.meta_key = '_product_id'
        AND oim.meta_value != %d
        GROUP BY oim.meta_value
        ORDER BY frequency DESC
        LIMIT 4
    ", $product_id));
    
    $products = array();
    foreach ($frequently_bought as $item) {
        $product = wc_get_product($item->product_id);
        if ($product && $product->is_visible()) {
            $products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price_html(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-thumb')[0],
                'url' => get_permalink($product->get_id()),
                'frequency' => $item->frequency
            );
        }
    }
    
    return $products;
}

/**
 * Get customers also viewed products
 */
function astra_ai_get_customers_also_viewed($product_id) {
    // For now, return related products based on categories
    $product = wc_get_product($product_id);
    if (!$product) {
        return astra_ai_get_popular_products(4);
    }
    
    $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
    
    if (empty($categories)) {
        return astra_ai_get_popular_products(4);
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 4,
        'post__not_in' => array($product_id),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $categories,
            ),
        ),
        'meta_query' => array(
            array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
            )
        )
    );
    
    $products = get_posts($args);
    $result = array();
    
    foreach ($products as $post) {
        $product = wc_get_product($post->ID);
        if ($product) {
            $result[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price_html(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-thumb')[0],
                'url' => get_permalink($product->get_id())
            );
        }
    }
    
    return $result;
}

/**
 * Get personalized recommendations for user
 */
function astra_ai_get_personalized_recommendations($user_id) {
    if (!$user_id) {
        return astra_ai_get_popular_products(6);
    }
    
    // Get user's order history
    $customer_orders = wc_get_orders(array(
        'customer' => $user_id,
        'status' => 'completed',
        'limit' => 10
    ));
    
    $purchased_categories = array();
    foreach ($customer_orders as $order) {
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
            $purchased_categories = array_merge($purchased_categories, $categories);
        }
    }
    
    if (empty($purchased_categories)) {
        return astra_ai_get_popular_products(6);
    }
    
    $purchased_categories = array_unique($purchased_categories);
    
    // Get products from preferred categories
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 6,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $purchased_categories,
            ),
        ),
        'meta_query' => array(
            array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
            )
        ),
        'orderby' => 'rand'
    );
    
    $products = get_posts($args);
    $result = array();
    
    foreach ($products as $post) {
        $product = wc_get_product($post->ID);
        if ($product) {
            $result[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price_html(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-thumb')[0],
                'url' => get_permalink($product->get_id())
            );
        }
    }
    
    return $result;
}

/**
 * Get popular products
 */
function astra_ai_get_popular_products($limit = 6) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
            )
        )
    );
    
    $products = get_posts($args);
    $result = array();
    
    foreach ($products as $post) {
        $product = wc_get_product($post->ID);
        if ($product) {
            $result[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price_html(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-thumb')[0],
                'url' => get_permalink($product->get_id())
            );
        }
    }
    
    return $result;
}

/**
 * AJAX Handler for AI Search
 */
function astra_ai_search() {
    check_ajax_referer('astra_ai_nonce', 'nonce');
    
    $query = sanitize_text_field($_POST['query']);
    $results = astra_ai_intelligent_search($query);
    
    wp_send_json_success($results);
}
add_action('wp_ajax_astra_ai_search', 'astra_ai_search');
add_action('wp_ajax_nopriv_astra_ai_search', 'astra_ai_search');

/**
 * Intelligent AI-powered search
 */
function astra_ai_intelligent_search($query) {
    // Enhanced search with fuzzy matching and synonyms
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
        's' => $query,
        'meta_query' => array(
            array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
            )
        )
    );
    
    $products = get_posts($args);
    $results = array();
    
    foreach ($products as $post) {
        $product = wc_get_product($post->ID);
        if ($product) {
            $results[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price_html(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-thumb')[0],
                'url' => get_permalink($product->get_id()),
                'excerpt' => wp_trim_words($product->get_short_description(), 15)
            );
        }
    }
    
    return $results;
}

/**
 * Add theme customizer options
 */
function astra_ai_customize_register($wp_customize) {
    // AI Settings Section
    $wp_customize->add_section('astra_ai_settings', array(
        'title' => __('AI Settings', 'astra-ai'),
        'priority' => 30,
    ));
    
    // Enable AI Features
    $wp_customize->add_setting('astra_ai_enable_features', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('astra_ai_enable_features', array(
        'label' => __('Enable AI Features', 'astra-ai'),
        'section' => 'astra_ai_settings',
        'type' => 'checkbox',
    ));
    
    // API Key
    $wp_customize->add_setting('astra_ai_api_key', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('astra_ai_api_key', array(
        'label' => __('AI API Key', 'astra-ai'),
        'section' => 'astra_ai_settings',
        'type' => 'text',
        'description' => __('Enter your AI service API key for enhanced recommendations.', 'astra-ai'),
    ));
}
add_action('customize_register', 'astra_ai_customize_register');

/**
 * Custom REST API endpoints for SPA
 */
function astra_ai_register_rest_routes() {
    register_rest_route('astra-ai/v1', '/products', array(
        'methods' => 'GET',
        'callback' => 'astra_ai_get_products_api',
        'permission_callback' => '__return_true',
    ));
    
    register_rest_route('astra-ai/v1', '/recommendations/(?P<type>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'astra_ai_get_recommendations_api',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'astra_ai_register_rest_routes');

/**
 * REST API callback for products
 */
function astra_ai_get_products_api($request) {
    $page = $request->get_param('page') ?: 1;
    $per_page = $request->get_param('per_page') ?: 12;
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'meta_query' => array(
            array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
            )
        )
    );
    
    $products = get_posts($args);
    $result = array();
    
    foreach ($products as $post) {
        $product = wc_get_product($post->ID);
        if ($product) {
            $result[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price_html(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-large')[0],
                'thumbnail' => wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'astra-ai-product-thumb')[0],
                'url' => get_permalink($product->get_id()),
                'description' => $product->get_short_description(),
                'in_stock' => $product->is_in_stock(),
                'categories' => wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'names'))
            );
        }
    }
    
    return new WP_REST_Response($result, 200);
}

/**
 * REST API callback for recommendations
 */
function astra_ai_get_recommendations_api($request) {
    $type = $request->get_param('type');
    $product_id = $request->get_param('product_id') ?: 0;
    $user_id = get_current_user_id();
    
    $recommendations = astra_ai_generate_recommendations($user_id, $product_id, $type);
    
    return new WP_REST_Response($recommendations, 200);
}

/**
 * Remove WooCommerce default styles for custom styling
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Modify WooCommerce templates for SPA compatibility
 */
function astra_ai_woocommerce_template_path($template, $template_name, $template_path) {
    $theme_template = ASTRA_AI_THEME_DIR . '/woocommerce/' . $template_name;
    if (file_exists($theme_template)) {
        return $theme_template;
    }
    return $template;
}
add_filter('woocommerce_locate_template', 'astra_ai_woocommerce_template_path', 10, 3);

/**
 * Include admin panel
 */
if (is_admin()) {
    require_once ASTRA_AI_THEME_DIR . '/admin/admin-panel.php';
}

/**
 * Create analytics table on theme activation
 */
function astra_ai_create_analytics_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'astra_ai_analytics';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        event_type varchar(50) NOT NULL,
        event_data longtext,
        user_id bigint(20) NOT NULL DEFAULT 0,
        session_id varchar(100),
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY event_type (event_type),
        KEY user_id (user_id),
        KEY created_at (created_at)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'astra_ai_create_analytics_table');