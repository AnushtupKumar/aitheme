<?php
/**
 * Shop Page Template
 * 
 * @package Astra-AI
 * @version 1.0.0
 */
?>

<div id="shop-page" class="page-content">
    <!-- Shop Header -->
    <section class="bg-white py-12 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Shop', 'astra-ai'); ?>
                </h1>
                <p class="text-xl text-gray-600">
                    <?php esc_html_e('Discover our amazing collection of products', 'astra-ai'); ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Filters and Sorting -->
    <section class="bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <!-- Filters -->
                <div class="flex flex-wrap items-center space-x-4">
                    <select id="category-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value=""><?php esc_html_e('All Categories', 'astra-ai'); ?></option>
                        <!-- Categories will be populated by JavaScript -->
                    </select>
                    
                    <select id="price-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value=""><?php esc_html_e('All Prices', 'astra-ai'); ?></option>
                        <option value="0-50"><?php esc_html_e('$0 - $50', 'astra-ai'); ?></option>
                        <option value="50-100"><?php esc_html_e('$50 - $100', 'astra-ai'); ?></option>
                        <option value="100-200"><?php esc_html_e('$100 - $200', 'astra-ai'); ?></option>
                        <option value="200+"><?php esc_html_e('$200+', 'astra-ai'); ?></option>
                    </select>
                </div>

                <!-- Sorting -->
                <div class="flex items-center space-x-4">
                    <label class="text-sm text-gray-600"><?php esc_html_e('Sort by:', 'astra-ai'); ?></label>
                    <select id="sort-products" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="default"><?php esc_html_e('Default', 'astra-ai'); ?></option>
                        <option value="price-low"><?php esc_html_e('Price: Low to High', 'astra-ai'); ?></option>
                        <option value="price-high"><?php esc_html_e('Price: High to Low', 'astra-ai'); ?></option>
                        <option value="name"><?php esc_html_e('Name A-Z', 'astra-ai'); ?></option>
                        <option value="rating"><?php esc_html_e('Rating', 'astra-ai'); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Loading State -->
            <div id="shop-loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                <p class="mt-2 text-gray-600"><?php esc_html_e('Loading products...', 'astra-ai'); ?></p>
            </div>

            <!-- Products Grid -->
            <div id="shop-products" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 hidden">
                <!-- Products will be loaded here -->
            </div>

            <!-- No Products Found -->
            <div id="no-products" class="text-center py-12 hidden">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900"><?php esc_html_e('No products found', 'astra-ai'); ?></h3>
                <p class="mt-1 text-sm text-gray-500"><?php esc_html_e('Try adjusting your search or filter criteria.', 'astra-ai'); ?></p>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button id="load-more-shop-products" class="bg-primary text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-primary/90 transition-colors hidden">
                    <?php esc_html_e('Load More Products', 'astra-ai'); ?>
                </button>
            </div>
        </div>
    </section>
</div>