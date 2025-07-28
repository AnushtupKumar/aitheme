<?php
/**
 * Astra-AI Site Builder Admin Interface
 * 
 * @package Astra-AI
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Astra AI Site Builder Class
 */
class Astra_AI_Site_Builder {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_astra_ai_save_page_layout', array($this, 'save_page_layout'));
        add_action('wp_ajax_astra_ai_get_page_layout', array($this, 'get_page_layout'));
        add_action('wp_ajax_astra_ai_get_blocks', array($this, 'get_available_blocks'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Site Builder', 'astra-ai'),
            __('Site Builder', 'astra-ai'),
            'manage_options',
            'astra-ai-site-builder',
            array($this, 'admin_page'),
            'dashicons-layout',
            25
        );
        
        add_submenu_page(
            'astra-ai-site-builder',
            __('Page Builder', 'astra-ai'),
            __('Page Builder', 'astra-ai'),
            'manage_options',
            'astra-ai-page-builder',
            array($this, 'page_builder')
        );
        
        add_submenu_page(
            'astra-ai-site-builder',
            __('Block Library', 'astra-ai'),
            __('Block Library', 'astra-ai'),
            'manage_options',
            'astra-ai-block-library',
            array($this, 'block_library')
        );
        
        add_submenu_page(
            'astra-ai-site-builder',
            __('Templates', 'astra-ai'),
            __('Templates', 'astra-ai'),
            'manage_options',
            'astra-ai-templates',
            array($this, 'templates_page')
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'astra-ai-site-builder') === false) {
            return;
        }
        
        wp_enqueue_script('astra-ai-site-builder', get_template_directory_uri() . '/admin/js/site-builder.js', array('jquery', 'jquery-ui-sortable'), '1.0.0', true);
        wp_enqueue_style('astra-ai-site-builder', get_template_directory_uri() . '/admin/css/site-builder.css', array(), '1.0.0');
        
        // Add Tailwind CSS
        wp_enqueue_script('tailwind-config', 'https://cdn.tailwindcss.com', array(), null);
        
        wp_localize_script('astra-ai-site-builder', 'astraSiteBuilder', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('astra_ai_site_builder_nonce'),
            'blocks' => $this->get_default_blocks(),
        ));
    }
    
    /**
     * Main admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Astra-AI Site Builder', 'astra-ai'); ?></h1>
            
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Page Builder Card -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="h-8 w-8 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                            <h3 class="text-xl font-semibold"><?php esc_html_e('Page Builder', 'astra-ai'); ?></h3>
                        </div>
                        <p class="mb-4"><?php esc_html_e('Create and customize pages using our drag-and-drop interface.', 'astra-ai'); ?></p>
                        <a href="<?php echo admin_url('admin.php?page=astra-ai-page-builder'); ?>" class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors">
                            <?php esc_html_e('Start Building', 'astra-ai'); ?>
                        </a>
                    </div>
                    
                    <!-- Block Library Card -->
                    <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="h-8 w-8 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-xl font-semibold"><?php esc_html_e('Block Library', 'astra-ai'); ?></h3>
                        </div>
                        <p class="mb-4"><?php esc_html_e('Explore our collection of pre-built blocks and components.', 'astra-ai'); ?></p>
                        <a href="<?php echo admin_url('admin.php?page=astra-ai-block-library'); ?>" class="bg-white text-green-600 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors">
                            <?php esc_html_e('Browse Blocks', 'astra-ai'); ?>
                        </a>
                    </div>
                    
                    <!-- Templates Card -->
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="h-8 w-8 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold"><?php esc_html_e('Templates', 'astra-ai'); ?></h3>
                        </div>
                        <p class="mb-4"><?php esc_html_e('Choose from professionally designed page templates.', 'astra-ai'); ?></p>
                        <a href="<?php echo admin_url('admin.php?page=astra-ai-templates'); ?>" class="bg-white text-purple-600 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors">
                            <?php esc_html_e('View Templates', 'astra-ai'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4"><?php esc_html_e('Recent Activity', 'astra-ai'); ?></h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 rounded-full p-2 mr-3">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900"><?php esc_html_e('Home page updated', 'astra-ai'); ?></p>
                                        <p class="text-sm text-gray-600"><?php esc_html_e('2 hours ago', 'astra-ai'); ?></p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500"><?php esc_html_e('Page Builder', 'astra-ai'); ?></span>
                            </div>
                            
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center">
                                    <div class="bg-green-100 rounded-full p-2 mr-3">
                                        <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900"><?php esc_html_e('New block created', 'astra-ai'); ?></p>
                                        <p class="text-sm text-gray-600"><?php esc_html_e('1 day ago', 'astra-ai'); ?></p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500"><?php esc_html_e('Block Library', 'astra-ai'); ?></span>
                            </div>
                            
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 rounded-full p-2 mr-3">
                                        <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900"><?php esc_html_e('Template imported', 'astra-ai'); ?></p>
                                        <p class="text-sm text-gray-600"><?php esc_html_e('3 days ago', 'astra-ai'); ?></p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500"><?php esc_html_e('Templates', 'astra-ai'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Page Builder Interface
     */
    public function page_builder() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Page Builder', 'astra-ai'); ?></h1>
            
            <div id="astra-page-builder" class="bg-white rounded-lg shadow-md mt-6">
                <!-- Builder Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <select id="page-selector" class="px-3 py-2 border border-gray-300 rounded-md">
                                <option value="home"><?php esc_html_e('Home Page', 'astra-ai'); ?></option>
                                <option value="shop"><?php esc_html_e('Shop Page', 'astra-ai'); ?></option>
                                <option value="about"><?php esc_html_e('About Page', 'astra-ai'); ?></option>
                                <option value="contact"><?php esc_html_e('Contact Page', 'astra-ai'); ?></option>
                                <option value="categories"><?php esc_html_e('Categories Page', 'astra-ai'); ?></option>
                            </select>
                            <button id="preview-btn" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">
                                <?php esc_html_e('Preview', 'astra-ai'); ?>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="save-btn" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                                <?php esc_html_e('Save Changes', 'astra-ai'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Builder Content -->
                <div class="flex">
                    <!-- Blocks Sidebar -->
                    <div class="w-1/4 border-r border-gray-200 bg-gray-50">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php esc_html_e('Blocks', 'astra-ai'); ?></h3>
                            
                            <!-- Block Categories -->
                            <div class="space-y-2">
                                <button class="block-category w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md active" data-category="layout">
                                    <?php esc_html_e('Layout', 'astra-ai'); ?>
                                </button>
                                <button class="block-category w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md" data-category="content">
                                    <?php esc_html_e('Content', 'astra-ai'); ?>
                                </button>
                                <button class="block-category w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md" data-category="ecommerce">
                                    <?php esc_html_e('E-commerce', 'astra-ai'); ?>
                                </button>
                                <button class="block-category w-full text-left px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md" data-category="forms">
                                    <?php esc_html_e('Forms', 'astra-ai'); ?>
                                </button>
                            </div>
                            
                            <!-- Available Blocks -->
                            <div id="available-blocks" class="mt-6 space-y-2">
                                <!-- Blocks will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Canvas Area -->
                    <div class="flex-1">
                        <div class="p-6">
                            <div id="canvas-container" class="border-2 border-dashed border-gray-300 rounded-lg min-h-screen">
                                <div id="page-canvas" class="sortable-container">
                                    <div class="text-center py-20 text-gray-500">
                                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <p><?php esc_html_e('Drag blocks here to start building your page', 'astra-ai'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Properties Sidebar -->
                    <div class="w-1/4 border-l border-gray-200 bg-gray-50">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php esc_html_e('Properties', 'astra-ai'); ?></h3>
                            <div id="block-properties">
                                <p class="text-gray-500 text-sm"><?php esc_html_e('Select a block to edit its properties', 'astra-ai'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Block Templates -->
        <script type="text/template" id="hero-block-template">
            <div class="block hero-block bg-gradient-to-r from-blue-500 to-purple-600 text-white py-20" data-block-type="hero">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 editable" data-property="title">Your Amazing Title</h1>
                    <p class="text-xl md:text-2xl mb-8 text-blue-100 editable" data-property="subtitle">Your compelling subtitle goes here</p>
                    <button class="bg-white text-blue-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transition-colors editable" data-property="button_text">Get Started</button>
                </div>
                <div class="block-controls">
                    <button class="edit-block" title="Edit Block">✏️</button>
                    <button class="delete-block" title="Delete Block">🗑️</button>
                    <button class="move-block" title="Move Block">↕️</button>
                </div>
            </div>
        </script>
        
        <script type="text/template" id="text-block-template">
            <div class="block text-block py-16 bg-white" data-block-type="text">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 editable" data-property="heading">Your Heading</h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto editable" data-property="content">Your content goes here. This is a sample text block that you can customize.</p>
                    </div>
                </div>
                <div class="block-controls">
                    <button class="edit-block" title="Edit Block">✏️</button>
                    <button class="delete-block" title="Delete Block">🗑️</button>
                    <button class="move-block" title="Move Block">↕️</button>
                </div>
            </div>
        </script>
        
        <script type="text/template" id="products-block-template">
            <div class="block products-block py-16 bg-gray-50" data-block-type="products">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 editable" data-property="title">Featured Products</h2>
                        <p class="text-xl text-gray-600 editable" data-property="subtitle">Discover our amazing collection</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Product cards will be dynamically loaded -->
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <div class="bg-gray-200 h-48 rounded mb-4"></div>
                            <h3 class="font-semibold mb-2">Sample Product</h3>
                            <p class="text-primary font-bold">$99.99</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <div class="bg-gray-200 h-48 rounded mb-4"></div>
                            <h3 class="font-semibold mb-2">Sample Product</h3>
                            <p class="text-primary font-bold">$99.99</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <div class="bg-gray-200 h-48 rounded mb-4"></div>
                            <h3 class="font-semibold mb-2">Sample Product</h3>
                            <p class="text-primary font-bold">$99.99</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <div class="bg-gray-200 h-48 rounded mb-4"></div>
                            <h3 class="font-semibold mb-2">Sample Product</h3>
                            <p class="text-primary font-bold">$99.99</p>
                        </div>
                    </div>
                </div>
                <div class="block-controls">
                    <button class="edit-block" title="Edit Block">✏️</button>
                    <button class="delete-block" title="Delete Block">🗑️</button>
                    <button class="move-block" title="Move Block">↕️</button>
                </div>
            </div>
        </script>
        <?php
    }
    
    /**
     * Block Library Page
     */
    public function block_library() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Block Library', 'astra-ai'); ?></h1>
            
            <div class="bg-white rounded-lg shadow-md mt-6 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Hero Block -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg h-32 mb-4 flex items-center justify-center text-white">
                            <span class="text-lg font-semibold"><?php esc_html_e('Hero Section', 'astra-ai'); ?></span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('Hero Block', 'astra-ai'); ?></h3>
                        <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Eye-catching hero section with title, subtitle, and call-to-action button.', 'astra-ai'); ?></p>
                        <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            <?php esc_html_e('Add to Page', 'astra-ai'); ?>
                        </button>
                    </div>
                    
                    <!-- Text Block -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="bg-white border rounded-lg h-32 mb-4 flex items-center justify-center">
                            <div class="text-center">
                                <div class="h-4 bg-gray-300 rounded w-24 mx-auto mb-2"></div>
                                <div class="h-2 bg-gray-200 rounded w-32 mx-auto"></div>
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('Text Block', 'astra-ai'); ?></h3>
                        <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Simple text content block with heading and paragraph text.', 'astra-ai'); ?></p>
                        <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            <?php esc_html_e('Add to Page', 'astra-ai'); ?>
                        </button>
                    </div>
                    
                    <!-- Products Block -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="bg-gray-50 rounded-lg h-32 mb-4 p-2">
                            <div class="grid grid-cols-2 gap-1 h-full">
                                <div class="bg-white rounded border"></div>
                                <div class="bg-white rounded border"></div>
                                <div class="bg-white rounded border"></div>
                                <div class="bg-white rounded border"></div>
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('Products Grid', 'astra-ai'); ?></h3>
                        <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Display products in a responsive grid layout.', 'astra-ai'); ?></p>
                        <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            <?php esc_html_e('Add to Page', 'astra-ai'); ?>
                        </button>
                    </div>
                    
                    <!-- Contact Form Block -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="bg-white border rounded-lg h-32 mb-4 p-3 space-y-2">
                            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                            <div class="h-6 bg-gray-100 rounded border"></div>
                            <div class="h-6 bg-gray-100 rounded border"></div>
                            <div class="h-6 bg-blue-200 rounded w-1/3"></div>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('Contact Form', 'astra-ai'); ?></h3>
                        <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Customizable contact form with multiple field types.', 'astra-ai'); ?></p>
                        <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            <?php esc_html_e('Add to Page', 'astra-ai'); ?>
                        </button>
                    </div>
                    
                    <!-- Image Gallery Block -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="bg-gray-50 rounded-lg h-32 mb-4 p-2">
                            <div class="grid grid-cols-3 gap-1 h-full">
                                <div class="bg-gray-300 rounded"></div>
                                <div class="bg-gray-300 rounded"></div>
                                <div class="bg-gray-300 rounded"></div>
                                <div class="bg-gray-300 rounded"></div>
                                <div class="bg-gray-300 rounded"></div>
                                <div class="bg-gray-300 rounded"></div>
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('Image Gallery', 'astra-ai'); ?></h3>
                        <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Responsive image gallery with lightbox functionality.', 'astra-ai'); ?></p>
                        <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            <?php esc_html_e('Add to Page', 'astra-ai'); ?>
                        </button>
                    </div>
                    
                    <!-- Testimonials Block -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="bg-white border rounded-lg h-32 mb-4 p-3">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-gray-300 rounded-full mr-2"></div>
                                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                            </div>
                            <div class="space-y-1">
                                <div class="h-2 bg-gray-100 rounded"></div>
                                <div class="h-2 bg-gray-100 rounded w-3/4"></div>
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('Testimonials', 'astra-ai'); ?></h3>
                        <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Customer testimonials with ratings and profile images.', 'astra-ai'); ?></p>
                        <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            <?php esc_html_e('Add to Page', 'astra-ai'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Templates Page
     */
    public function templates_page() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Templates', 'astra-ai'); ?></h1>
            
            <div class="bg-white rounded-lg shadow-md mt-6 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- E-commerce Template -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-48 relative">
                            <div class="absolute inset-0 bg-white/10 p-4">
                                <div class="bg-white/20 rounded h-8 mb-4"></div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="bg-white/20 rounded h-16"></div>
                                    <div class="bg-white/20 rounded h-16"></div>
                                    <div class="bg-white/20 rounded h-16"></div>
                                    <div class="bg-white/20 rounded h-16"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('E-commerce Store', 'astra-ai'); ?></h3>
                            <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Complete e-commerce template with product showcase, hero section, and testimonials.', 'astra-ai'); ?></p>
                            <div class="flex space-x-2">
                                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                                    <?php esc_html_e('Use Template', 'astra-ai'); ?>
                                </button>
                                <button class="bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors">
                                    <?php esc_html_e('Preview', 'astra-ai'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Business Template -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <div class="bg-gradient-to-r from-green-500 to-teal-600 h-48 relative">
                            <div class="absolute inset-0 bg-white/10 p-4">
                                <div class="bg-white/20 rounded h-12 mb-4"></div>
                                <div class="space-y-2">
                                    <div class="bg-white/20 rounded h-4"></div>
                                    <div class="bg-white/20 rounded h-4 w-3/4"></div>
                                    <div class="bg-white/20 rounded h-8 w-1/3 mt-4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('Business Landing', 'astra-ai'); ?></h3>
                            <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Professional business template with services, about section, and contact form.', 'astra-ai'); ?></p>
                            <div class="flex space-x-2">
                                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                                    <?php esc_html_e('Use Template', 'astra-ai'); ?>
                                </button>
                                <button class="bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors">
                                    <?php esc_html_e('Preview', 'astra-ai'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Portfolio Template -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-48 relative">
                            <div class="absolute inset-0 bg-white/10 p-4">
                                <div class="bg-white/20 rounded h-8 mb-4"></div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="bg-white/20 rounded h-12"></div>
                                    <div class="bg-white/20 rounded h-12"></div>
                                    <div class="bg-white/20 rounded h-12"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2"><?php esc_html_e('Creative Portfolio', 'astra-ai'); ?></h3>
                            <p class="text-sm text-gray-600 mb-4"><?php esc_html_e('Showcase your work with this creative portfolio template featuring image galleries.', 'astra-ai'); ?></p>
                            <div class="flex space-x-2">
                                <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                                    <?php esc_html_e('Use Template', 'astra-ai'); ?>
                                </button>
                                <button class="bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors">
                                    <?php esc_html_e('Preview', 'astra-ai'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get default blocks
     */
    private function get_default_blocks() {
        return array(
            'layout' => array(
                array(
                    'id' => 'hero',
                    'name' => __('Hero Section', 'astra-ai'),
                    'icon' => '🏆',
                    'category' => 'layout'
                ),
                array(
                    'id' => 'columns',
                    'name' => __('Columns', 'astra-ai'),
                    'icon' => '📱',
                    'category' => 'layout'
                ),
                array(
                    'id' => 'container',
                    'name' => __('Container', 'astra-ai'),
                    'icon' => '📦',
                    'category' => 'layout'
                )
            ),
            'content' => array(
                array(
                    'id' => 'text',
                    'name' => __('Text Block', 'astra-ai'),
                    'icon' => '📝',
                    'category' => 'content'
                ),
                array(
                    'id' => 'image',
                    'name' => __('Image', 'astra-ai'),
                    'icon' => '🖼️',
                    'category' => 'content'
                ),
                array(
                    'id' => 'gallery',
                    'name' => __('Gallery', 'astra-ai'),
                    'icon' => '🖼️',
                    'category' => 'content'
                )
            ),
            'ecommerce' => array(
                array(
                    'id' => 'products',
                    'name' => __('Products Grid', 'astra-ai'),
                    'icon' => '🛍️',
                    'category' => 'ecommerce'
                ),
                array(
                    'id' => 'product-categories',
                    'name' => __('Product Categories', 'astra-ai'),
                    'icon' => '📂',
                    'category' => 'ecommerce'
                )
            ),
            'forms' => array(
                array(
                    'id' => 'contact-form',
                    'name' => __('Contact Form', 'astra-ai'),
                    'icon' => '📧',
                    'category' => 'forms'
                ),
                array(
                    'id' => 'newsletter',
                    'name' => __('Newsletter', 'astra-ai'),
                    'icon' => '📬',
                    'category' => 'forms'
                )
            )
        );
    }
    
    /**
     * AJAX: Save page layout
     */
    public function save_page_layout() {
        check_ajax_referer('astra_ai_site_builder_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        $page = sanitize_text_field($_POST['page']);
        $layout = wp_kses_post($_POST['layout']);
        
        // Save layout to database
        update_option('astra_ai_page_layout_' . $page, $layout);
        
        wp_send_json_success(array(
            'message' => __('Page layout saved successfully!', 'astra-ai')
        ));
    }
    
    /**
     * AJAX: Get page layout
     */
    public function get_page_layout() {
        check_ajax_referer('astra_ai_site_builder_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        $page = sanitize_text_field($_POST['page']);
        $layout = get_option('astra_ai_page_layout_' . $page, '');
        
        wp_send_json_success(array(
            'layout' => $layout
        ));
    }
    
    /**
     * AJAX: Get available blocks
     */
    public function get_available_blocks() {
        check_ajax_referer('astra_ai_site_builder_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        wp_send_json_success(array(
            'blocks' => $this->get_default_blocks()
        ));
    }
}

// Initialize the Site Builder
new Astra_AI_Site_Builder();