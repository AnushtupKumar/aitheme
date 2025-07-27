<?php
/**
 * Astra-AI Admin Panel
 * 
 * @package Astra-AI
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Astra AI Admin Panel Class
 */
class Astra_AI_Admin_Panel {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_astra_ai_save_settings', array($this, 'save_settings'));
        add_action('wp_ajax_astra_ai_analytics', array($this, 'handle_analytics'));
        add_action('wp_ajax_nopriv_astra_ai_analytics', array($this, 'handle_analytics'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Astra-AI Settings', 'astra-ai'),
            __('Astra-AI', 'astra-ai'),
            'manage_options',
            'astra-ai-settings',
            array($this, 'admin_page')
        );
        
        // Add AI Analytics submenu
        add_submenu_page(
            'astra-ai-settings',
            __('AI Analytics', 'astra-ai'),
            __('Analytics', 'astra-ai'),
            'manage_options',
            'astra-ai-analytics',
            array($this, 'analytics_page')
        );
        
        // Add AI Content Generator submenu
        add_submenu_page(
            'astra-ai-settings',
            __('AI Content Generator', 'astra-ai'),
            __('Content Generator', 'astra-ai'),
            'manage_options',
            'astra-ai-content-generator',
            array($this, 'content_generator_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('astra_ai_settings', 'astra_ai_options');
        
        // AI Settings Section
        add_settings_section(
            'astra_ai_general',
            __('AI Settings', 'astra-ai'),
            array($this, 'general_section_callback'),
            'astra-ai-settings'
        );
        
        // Enable AI Features
        add_settings_field(
            'enable_ai_features',
            __('Enable AI Features', 'astra-ai'),
            array($this, 'enable_ai_features_callback'),
            'astra-ai-settings',
            'astra_ai_general'
        );
        
        // AI API Key
        add_settings_field(
            'ai_api_key',
            __('AI API Key', 'astra-ai'),
            array($this, 'ai_api_key_callback'),
            'astra-ai-settings',
            'astra_ai_general'
        );
        
        // Recommendation Engine
        add_settings_field(
            'recommendation_engine',
            __('Recommendation Engine', 'astra-ai'),
            array($this, 'recommendation_engine_callback'),
            'astra-ai-settings',
            'astra_ai_general'
        );
        
        // Performance Settings Section
        add_settings_section(
            'astra_ai_performance',
            __('Performance Settings', 'astra-ai'),
            array($this, 'performance_section_callback'),
            'astra-ai-settings'
        );
        
        // Enable Caching
        add_settings_field(
            'enable_caching',
            __('Enable AI Caching', 'astra-ai'),
            array($this, 'enable_caching_callback'),
            'astra-ai-settings',
            'astra_ai_performance'
        );
        
        // Cache Duration
        add_settings_field(
            'cache_duration',
            __('Cache Duration (hours)', 'astra-ai'),
            array($this, 'cache_duration_callback'),
            'astra-ai-settings',
            'astra_ai_performance'
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'astra-ai') !== false) {
            wp_enqueue_script('astra-ai-admin', get_template_directory_uri() . '/admin/js/admin.js', array('jquery'), ASTRA_AI_VERSION, true);
            wp_enqueue_style('astra-ai-admin', get_template_directory_uri() . '/admin/css/admin.css', array(), ASTRA_AI_VERSION);
            
            wp_localize_script('astra-ai-admin', 'astraAIAdmin', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('astra_ai_admin_nonce'),
            ));
        }
    }
    
    /**
     * Main admin page
     */
    public function admin_page() {
        $options = get_option('astra_ai_options', array());
        ?>
        <div class="wrap astra-ai-admin">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="astra-ai-admin-header">
                <div class="astra-ai-logo">
                    <h2>🤖 Astra-AI Theme</h2>
                    <p><?php esc_html_e('AI-Powered E-commerce Experience', 'astra-ai'); ?></p>
                </div>
                <div class="astra-ai-stats">
                    <div class="stat-card">
                        <h3><?php echo $this->get_total_products(); ?></h3>
                        <p><?php esc_html_e('Products', 'astra-ai'); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $this->get_ai_recommendations_count(); ?></h3>
                        <p><?php esc_html_e('AI Recommendations', 'astra-ai'); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $this->get_conversion_rate(); ?>%</h3>
                        <p><?php esc_html_e('Conversion Rate', 'astra-ai'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="astra-ai-tabs">
                <nav class="nav-tab-wrapper">
                    <a href="#general" class="nav-tab nav-tab-active"><?php esc_html_e('General Settings', 'astra-ai'); ?></a>
                    <a href="#ai-features" class="nav-tab"><?php esc_html_e('AI Features', 'astra-ai'); ?></a>
                    <a href="#performance" class="nav-tab"><?php esc_html_e('Performance', 'astra-ai'); ?></a>
                    <a href="#design" class="nav-tab"><?php esc_html_e('Design', 'astra-ai'); ?></a>
                </nav>
                
                <form method="post" action="options.php" id="astra-ai-settings-form">
                    <?php settings_fields('astra_ai_settings'); ?>
                    
                    <!-- General Settings Tab -->
                    <div id="general" class="tab-content active">
                        <div class="settings-section">
                            <h3><?php esc_html_e('AI Configuration', 'astra-ai'); ?></h3>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php esc_html_e('Enable AI Features', 'astra-ai'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="astra_ai_options[enable_ai_features]" value="1" <?php checked(isset($options['enable_ai_features']) ? $options['enable_ai_features'] : 0, 1); ?>>
                                            <?php esc_html_e('Enable AI-powered recommendations and search', 'astra-ai'); ?>
                                        </label>
                                        <p class="description"><?php esc_html_e('Turn on AI features for personalized product recommendations and intelligent search.', 'astra-ai'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('AI Service Provider', 'astra-ai'); ?></th>
                                    <td>
                                        <select name="astra_ai_options[ai_provider]">
                                            <option value="openai" <?php selected(isset($options['ai_provider']) ? $options['ai_provider'] : 'openai', 'openai'); ?>>OpenAI</option>
                                            <option value="google" <?php selected(isset($options['ai_provider']) ? $options['ai_provider'] : 'openai', 'google'); ?>>Google AI</option>
                                            <option value="azure" <?php selected(isset($options['ai_provider']) ? $options['ai_provider'] : 'openai', 'azure'); ?>>Azure AI</option>
                                            <option value="custom" <?php selected(isset($options['ai_provider']) ? $options['ai_provider'] : 'openai', 'custom'); ?>>Custom API</option>
                                        </select>
                                        <p class="description"><?php esc_html_e('Choose your preferred AI service provider.', 'astra-ai'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('API Key', 'astra-ai'); ?></th>
                                    <td>
                                        <input type="password" name="astra_ai_options[ai_api_key]" value="<?php echo esc_attr(isset($options['ai_api_key']) ? $options['ai_api_key'] : ''); ?>" class="regular-text">
                                        <button type="button" class="button" id="test-api-key"><?php esc_html_e('Test Connection', 'astra-ai'); ?></button>
                                        <p class="description"><?php esc_html_e('Enter your AI service API key. This is required for AI features to work.', 'astra-ai'); ?></p>
                                        <div id="api-test-result"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- AI Features Tab -->
                    <div id="ai-features" class="tab-content">
                        <div class="settings-section">
                            <h3><?php esc_html_e('Recommendation Settings', 'astra-ai'); ?></h3>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php esc_html_e('Recommendation Algorithm', 'astra-ai'); ?></th>
                                    <td>
                                        <select name="astra_ai_options[recommendation_algorithm]">
                                            <option value="collaborative" <?php selected(isset($options['recommendation_algorithm']) ? $options['recommendation_algorithm'] : 'collaborative', 'collaborative'); ?>>Collaborative Filtering</option>
                                            <option value="content_based" <?php selected(isset($options['recommendation_algorithm']) ? $options['recommendation_algorithm'] : 'collaborative', 'content_based'); ?>>Content-Based</option>
                                            <option value="hybrid" <?php selected(isset($options['recommendation_algorithm']) ? $options['recommendation_algorithm'] : 'collaborative', 'hybrid'); ?>>Hybrid Approach</option>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Maximum Recommendations', 'astra-ai'); ?></th>
                                    <td>
                                        <input type="number" name="astra_ai_options[max_recommendations]" value="<?php echo esc_attr(isset($options['max_recommendations']) ? $options['max_recommendations'] : 6); ?>" min="1" max="20" class="small-text">
                                        <p class="description"><?php esc_html_e('Maximum number of products to recommend per section.', 'astra-ai'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Enable Dynamic Pricing', 'astra-ai'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="astra_ai_options[enable_dynamic_pricing]" value="1" <?php checked(isset($options['enable_dynamic_pricing']) ? $options['enable_dynamic_pricing'] : 0, 1); ?>>
                                            <?php esc_html_e('Allow AI to suggest optimal pricing based on demand and competition', 'astra-ai'); ?>
                                        </label>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Search Intelligence Level', 'astra-ai'); ?></th>
                                    <td>
                                        <select name="astra_ai_options[search_intelligence]">
                                            <option value="basic" <?php selected(isset($options['search_intelligence']) ? $options['search_intelligence'] : 'advanced', 'basic'); ?>>Basic</option>
                                            <option value="advanced" <?php selected(isset($options['search_intelligence']) ? $options['search_intelligence'] : 'advanced', 'advanced'); ?>>Advanced</option>
                                            <option value="ai_powered" <?php selected(isset($options['search_intelligence']) ? $options['search_intelligence'] : 'advanced', 'ai_powered'); ?>>AI-Powered</option>
                                        </select>
                                        <p class="description"><?php esc_html_e('Choose the level of AI intelligence for search functionality.', 'astra-ai'); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="settings-section">
                            <h3><?php esc_html_e('Personalization Settings', 'astra-ai'); ?></h3>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php esc_html_e('User Behavior Tracking', 'astra-ai'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="astra_ai_options[track_user_behavior]" value="1" <?php checked(isset($options['track_user_behavior']) ? $options['track_user_behavior'] : 1, 1); ?>>
                                            <?php esc_html_e('Track user behavior for better personalization', 'astra-ai'); ?>
                                        </label>
                                        <p class="description"><?php esc_html_e('Collect anonymous user behavior data to improve AI recommendations.', 'astra-ai'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Personalization Level', 'astra-ai'); ?></th>
                                    <td>
                                        <select name="astra_ai_options[personalization_level]">
                                            <option value="low" <?php selected(isset($options['personalization_level']) ? $options['personalization_level'] : 'medium', 'low'); ?>>Low</option>
                                            <option value="medium" <?php selected(isset($options['personalization_level']) ? $options['personalization_level'] : 'medium', 'medium'); ?>>Medium</option>
                                            <option value="high" <?php selected(isset($options['personalization_level']) ? $options['personalization_level'] : 'medium', 'high'); ?>>High</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Performance Tab -->
                    <div id="performance" class="tab-content">
                        <div class="settings-section">
                            <h3><?php esc_html_e('Caching & Performance', 'astra-ai'); ?></h3>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php esc_html_e('Enable AI Caching', 'astra-ai'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="astra_ai_options[enable_caching]" value="1" <?php checked(isset($options['enable_caching']) ? $options['enable_caching'] : 1, 1); ?>>
                                            <?php esc_html_e('Cache AI recommendations for better performance', 'astra-ai'); ?>
                                        </label>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Cache Duration', 'astra-ai'); ?></th>
                                    <td>
                                        <input type="number" name="astra_ai_options[cache_duration]" value="<?php echo esc_attr(isset($options['cache_duration']) ? $options['cache_duration'] : 24); ?>" min="1" max="168" class="small-text">
                                        <span><?php esc_html_e('hours', 'astra-ai'); ?></span>
                                        <p class="description"><?php esc_html_e('How long to cache AI recommendations (1-168 hours).', 'astra-ai'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Lazy Loading', 'astra-ai'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="astra_ai_options[enable_lazy_loading]" value="1" <?php checked(isset($options['enable_lazy_loading']) ? $options['enable_lazy_loading'] : 1, 1); ?>>
                                            <?php esc_html_e('Enable lazy loading for images and content', 'astra-ai'); ?>
                                        </label>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Preload Critical Resources', 'astra-ai'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="astra_ai_options[preload_resources]" value="1" <?php checked(isset($options['preload_resources']) ? $options['preload_resources'] : 1, 1); ?>>
                                            <?php esc_html_e('Preload critical CSS and JavaScript', 'astra-ai'); ?>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="performance-metrics">
                                <h4><?php esc_html_e('Current Performance Metrics', 'astra-ai'); ?></h4>
                                <div class="metrics-grid">
                                    <div class="metric">
                                        <span class="metric-value" id="page-speed">--</span>
                                        <span class="metric-label"><?php esc_html_e('Page Speed Score', 'astra-ai'); ?></span>
                                    </div>
                                    <div class="metric">
                                        <span class="metric-value" id="cache-hit-rate">--</span>
                                        <span class="metric-label"><?php esc_html_e('Cache Hit Rate', 'astra-ai'); ?></span>
                                    </div>
                                    <div class="metric">
                                        <span class="metric-value" id="avg-load-time">--</span>
                                        <span class="metric-label"><?php esc_html_e('Avg Load Time', 'astra-ai'); ?></span>
                                    </div>
                                </div>
                                <button type="button" class="button" id="run-performance-test"><?php esc_html_e('Run Performance Test', 'astra-ai'); ?></button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Design Tab -->
                    <div id="design" class="tab-content">
                        <div class="settings-section">
                            <h3><?php esc_html_e('Theme Customization', 'astra-ai'); ?></h3>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php esc_html_e('Primary Color', 'astra-ai'); ?></th>
                                    <td>
                                        <input type="color" name="astra_ai_options[primary_color]" value="<?php echo esc_attr(isset($options['primary_color']) ? $options['primary_color'] : '#2563eb'); ?>">
                                        <p class="description"><?php esc_html_e('Choose your theme\'s primary color.', 'astra-ai'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Layout Style', 'astra-ai'); ?></th>
                                    <td>
                                        <select name="astra_ai_options[layout_style]">
                                            <option value="modern" <?php selected(isset($options['layout_style']) ? $options['layout_style'] : 'modern', 'modern'); ?>>Modern</option>
                                            <option value="classic" <?php selected(isset($options['layout_style']) ? $options['layout_style'] : 'modern', 'classic'); ?>>Classic</option>
                                            <option value="minimal" <?php selected(isset($options['layout_style']) ? $options['layout_style'] : 'modern', 'minimal'); ?>>Minimal</option>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Product Grid Columns', 'astra-ai'); ?></th>
                                    <td>
                                        <select name="astra_ai_options[grid_columns]">
                                            <option value="2" <?php selected(isset($options['grid_columns']) ? $options['grid_columns'] : '4', '2'); ?>>2 Columns</option>
                                            <option value="3" <?php selected(isset($options['grid_columns']) ? $options['grid_columns'] : '4', '3'); ?>>3 Columns</option>
                                            <option value="4" <?php selected(isset($options['grid_columns']) ? $options['grid_columns'] : '4', '4'); ?>>4 Columns</option>
                                            <option value="5" <?php selected(isset($options['grid_columns']) ? $options['grid_columns'] : '4', '5'); ?>>5 Columns</option>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Enable Dark Mode', 'astra-ai'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="astra_ai_options[enable_dark_mode]" value="1" <?php checked(isset($options['enable_dark_mode']) ? $options['enable_dark_mode'] : 0, 1); ?>>
                                            <?php esc_html_e('Allow users to switch to dark mode', 'astra-ai'); ?>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php submit_button(__('Save Settings', 'astra-ai'), 'primary', 'submit', false); ?>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Analytics page
     */
    public function analytics_page() {
        ?>
        <div class="wrap astra-ai-admin">
            <h1><?php esc_html_e('AI Analytics Dashboard', 'astra-ai'); ?></h1>
            
            <div class="analytics-dashboard">
                <div class="analytics-cards">
                    <div class="analytics-card">
                        <h3><?php esc_html_e('Total Page Views', 'astra-ai'); ?></h3>
                        <div class="analytics-number"><?php echo $this->get_total_page_views(); ?></div>
                        <div class="analytics-change positive">+12% from last month</div>
                    </div>
                    
                    <div class="analytics-card">
                        <h3><?php esc_html_e('AI Recommendations Clicked', 'astra-ai'); ?></h3>
                        <div class="analytics-number"><?php echo $this->get_recommendation_clicks(); ?></div>
                        <div class="analytics-change positive">+8% from last month</div>
                    </div>
                    
                    <div class="analytics-card">
                        <h3><?php esc_html_e('Search Queries', 'astra-ai'); ?></h3>
                        <div class="analytics-number"><?php echo $this->get_search_queries(); ?></div>
                        <div class="analytics-change negative">-3% from last month</div>
                    </div>
                    
                    <div class="analytics-card">
                        <h3><?php esc_html_e('Conversion Rate', 'astra-ai'); ?></h3>
                        <div class="analytics-number"><?php echo $this->get_conversion_rate(); ?>%</div>
                        <div class="analytics-change positive">+15% from last month</div>
                    </div>
                </div>
                
                <div class="analytics-charts">
                    <div class="chart-container">
                        <h3><?php esc_html_e('AI Recommendations Performance', 'astra-ai'); ?></h3>
                        <canvas id="recommendations-chart"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <h3><?php esc_html_e('Popular Search Terms', 'astra-ai'); ?></h3>
                        <div class="search-terms-list">
                            <?php $this->display_popular_search_terms(); ?>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-tables">
                    <div class="table-container">
                        <h3><?php esc_html_e('Top Performing Products', 'astra-ai'); ?></h3>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Product', 'astra-ai'); ?></th>
                                    <th><?php esc_html_e('Views', 'astra-ai'); ?></th>
                                    <th><?php esc_html_e('AI Recommendations', 'astra-ai'); ?></th>
                                    <th><?php esc_html_e('Conversion Rate', 'astra-ai'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $this->display_top_products(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Content generator page
     */
    public function content_generator_page() {
        ?>
        <div class="wrap astra-ai-admin">
            <h1><?php esc_html_e('AI Content Generator', 'astra-ai'); ?></h1>
            
            <div class="content-generator">
                <div class="generator-section">
                    <h3><?php esc_html_e('Product Description Generator', 'astra-ai'); ?></h3>
                    <p><?php esc_html_e('Generate compelling product descriptions using AI.', 'astra-ai'); ?></p>
                    
                    <form id="product-description-form">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Product Name', 'astra-ai'); ?></th>
                                <td><input type="text" id="product-name" class="regular-text" placeholder="<?php esc_attr_e('Enter product name', 'astra-ai'); ?>"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Key Features', 'astra-ai'); ?></th>
                                <td><textarea id="product-features" rows="3" class="large-text" placeholder="<?php esc_attr_e('List key features separated by commas', 'astra-ai'); ?>"></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Target Audience', 'astra-ai'); ?></th>
                                <td>
                                    <select id="target-audience">
                                        <option value="general"><?php esc_html_e('General', 'astra-ai'); ?></option>
                                        <option value="professionals"><?php esc_html_e('Professionals', 'astra-ai'); ?></option>
                                        <option value="students"><?php esc_html_e('Students', 'astra-ai'); ?></option>
                                        <option value="families"><?php esc_html_e('Families', 'astra-ai'); ?></option>
                                        <option value="tech-enthusiasts"><?php esc_html_e('Tech Enthusiasts', 'astra-ai'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Tone', 'astra-ai'); ?></th>
                                <td>
                                    <select id="description-tone">
                                        <option value="professional"><?php esc_html_e('Professional', 'astra-ai'); ?></option>
                                        <option value="friendly"><?php esc_html_e('Friendly', 'astra-ai'); ?></option>
                                        <option value="exciting"><?php esc_html_e('Exciting', 'astra-ai'); ?></option>
                                        <option value="luxury"><?php esc_html_e('Luxury', 'astra-ai'); ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="button" id="generate-description" class="button button-primary">
                                <?php esc_html_e('Generate Description', 'astra-ai'); ?>
                            </button>
                        </p>
                    </form>
                    
                    <div id="generated-description" class="generated-content" style="display: none;">
                        <h4><?php esc_html_e('Generated Description:', 'astra-ai'); ?></h4>
                        <div class="description-output"></div>
                        <p>
                            <button type="button" class="button" id="copy-description"><?php esc_html_e('Copy to Clipboard', 'astra-ai'); ?></button>
                            <button type="button" class="button" id="regenerate-description"><?php esc_html_e('Regenerate', 'astra-ai'); ?></button>
                        </p>
                    </div>
                </div>
                
                <div class="generator-section">
                    <h3><?php esc_html_e('SEO Meta Generator', 'astra-ai'); ?></h3>
                    <p><?php esc_html_e('Generate SEO-optimized meta titles and descriptions.', 'astra-ai'); ?></p>
                    
                    <form id="seo-meta-form">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Page/Product Title', 'astra-ai'); ?></th>
                                <td><input type="text" id="seo-title" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Primary Keywords', 'astra-ai'); ?></th>
                                <td><input type="text" id="seo-keywords" class="regular-text" placeholder="<?php esc_attr_e('Comma-separated keywords', 'astra-ai'); ?>"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Content Type', 'astra-ai'); ?></th>
                                <td>
                                    <select id="content-type">
                                        <option value="product"><?php esc_html_e('Product Page', 'astra-ai'); ?></option>
                                        <option value="category"><?php esc_html_e('Category Page', 'astra-ai'); ?></option>
                                        <option value="blog"><?php esc_html_e('Blog Post', 'astra-ai'); ?></option>
                                        <option value="homepage"><?php esc_html_e('Homepage', 'astra-ai'); ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="button" id="generate-seo" class="button button-primary">
                                <?php esc_html_e('Generate SEO Meta', 'astra-ai'); ?>
                            </button>
                        </p>
                    </form>
                    
                    <div id="generated-seo" class="generated-content" style="display: none;">
                        <h4><?php esc_html_e('Generated SEO Meta:', 'astra-ai'); ?></h4>
                        <div class="seo-output">
                            <div class="meta-title">
                                <strong><?php esc_html_e('Meta Title:', 'astra-ai'); ?></strong>
                                <div class="meta-title-content"></div>
                            </div>
                            <div class="meta-description">
                                <strong><?php esc_html_e('Meta Description:', 'astra-ai'); ?></strong>
                                <div class="meta-description-content"></div>
                            </div>
                        </div>
                        <p>
                            <button type="button" class="button" id="copy-seo"><?php esc_html_e('Copy All', 'astra-ai'); ?></button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Handle analytics data
     */
    public function handle_analytics() {
        if (!wp_verify_nonce($_POST['nonce'], 'astra_ai_nonce')) {
            wp_die('Security check failed');
        }
        
        $event_type = sanitize_text_field($_POST['event_type']);
        $data = json_decode(stripslashes($_POST['data']), true);
        
        // Store analytics data in database
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'astra_ai_analytics';
        
        $wpdb->insert(
            $table_name,
            array(
                'event_type' => $event_type,
                'event_data' => json_encode($data),
                'user_id' => get_current_user_id(),
                'session_id' => session_id(),
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%d', '%s', '%s')
        );
        
        wp_send_json_success();
    }
    
    /**
     * Save settings via AJAX
     */
    public function save_settings() {
        if (!wp_verify_nonce($_POST['nonce'], 'astra_ai_admin_nonce')) {
            wp_send_json_error('Security check failed');
        }
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $settings = $_POST['settings'];
        update_option('astra_ai_options', $settings);
        
        wp_send_json_success('Settings saved successfully');
    }
    
    // Helper methods for analytics
    private function get_total_products() {
        return wp_count_posts('product')->publish;
    }
    
    private function get_ai_recommendations_count() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'astra_ai_recommendations';
        return $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    }
    
    private function get_conversion_rate() {
        // This would calculate actual conversion rate
        return '3.2';
    }
    
    private function get_total_page_views() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'astra_ai_analytics';
        return $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE event_type = 'page_view' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    }
    
    private function get_recommendation_clicks() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'astra_ai_analytics';
        return $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE event_type = 'recommendation_click' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    }
    
    private function get_search_queries() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'astra_ai_analytics';
        return $wpdb->get_var("SELECT COUNT(DISTINCT JSON_EXTRACT(event_data, '$.query')) FROM $table_name WHERE event_type = 'search' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    }
    
    private function display_popular_search_terms() {
        // This would display actual popular search terms
        $terms = array('laptop', 'headphones', 'smartphone', 'tablet', 'smartwatch');
        foreach ($terms as $term) {
            echo '<div class="search-term">' . esc_html($term) . '</div>';
        }
    }
    
    private function display_top_products() {
        $products = wc_get_products(array('limit' => 5, 'orderby' => 'popularity'));
        foreach ($products as $product) {
            echo '<tr>';
            echo '<td>' . esc_html($product->get_name()) . '</td>';
            echo '<td>245</td>';
            echo '<td>18</td>';
            echo '<td>4.2%</td>';
            echo '</tr>';
        }
    }
    
    // Callback functions for settings fields
    public function general_section_callback() {
        echo '<p>' . esc_html__('Configure the core AI settings for your theme.', 'astra-ai') . '</p>';
    }
    
    public function performance_section_callback() {
        echo '<p>' . esc_html__('Optimize performance and caching settings.', 'astra-ai') . '</p>';
    }
    
    public function enable_ai_features_callback() {
        $options = get_option('astra_ai_options');
        $value = isset($options['enable_ai_features']) ? $options['enable_ai_features'] : 0;
        echo '<input type="checkbox" name="astra_ai_options[enable_ai_features]" value="1" ' . checked(1, $value, false) . '>';
    }
    
    public function ai_api_key_callback() {
        $options = get_option('astra_ai_options');
        $value = isset($options['ai_api_key']) ? $options['ai_api_key'] : '';
        echo '<input type="password" name="astra_ai_options[ai_api_key]" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function recommendation_engine_callback() {
        $options = get_option('astra_ai_options');
        $value = isset($options['recommendation_engine']) ? $options['recommendation_engine'] : 'collaborative';
        echo '<select name="astra_ai_options[recommendation_engine]">';
        echo '<option value="collaborative" ' . selected($value, 'collaborative', false) . '>Collaborative Filtering</option>';
        echo '<option value="content_based" ' . selected($value, 'content_based', false) . '>Content-Based</option>';
        echo '<option value="hybrid" ' . selected($value, 'hybrid', false) . '>Hybrid</option>';
        echo '</select>';
    }
    
    public function enable_caching_callback() {
        $options = get_option('astra_ai_options');
        $value = isset($options['enable_caching']) ? $options['enable_caching'] : 1;
        echo '<input type="checkbox" name="astra_ai_options[enable_caching]" value="1" ' . checked(1, $value, false) . '>';
    }
    
    public function cache_duration_callback() {
        $options = get_option('astra_ai_options');
        $value = isset($options['cache_duration']) ? $options['cache_duration'] : 24;
        echo '<input type="number" name="astra_ai_options[cache_duration]" value="' . esc_attr($value) . '" min="1" max="168" class="small-text"> hours';
    }
}

// Initialize the admin panel
new Astra_AI_Admin_Panel();