<?php
/**
 * Categories Page Template
 * 
 * @package Astra-AI
 * @version 1.0.0
 */
?>

<div id="categories-page" class="page-content">
    <!-- Categories Hero -->
    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                <?php esc_html_e('Product Categories', 'astra-ai'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100">
                <?php esc_html_e('Explore our wide range of product categories', 'astra-ai'); ?>
            </p>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Loading State -->
            <div id="categories-loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                <p class="mt-2 text-gray-600"><?php esc_html_e('Loading categories...', 'astra-ai'); ?></p>
            </div>

            <!-- Categories Grid -->
            <div id="categories-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 hidden">
                <!-- Categories will be loaded here -->
            </div>

            <!-- No Categories Found -->
            <div id="no-categories" class="text-center py-12 hidden">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900"><?php esc_html_e('No categories found', 'astra-ai'); ?></h3>
                <p class="mt-1 text-sm text-gray-500"><?php esc_html_e('Categories will appear here once they are created.', 'astra-ai'); ?></p>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Popular Categories', 'astra-ai'); ?>
                </h2>
                <p class="text-xl text-gray-600">
                    <?php esc_html_e('Discover our most popular product categories', 'astra-ai'); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Electronics -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer category-card" data-category="electronics">
                    <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?php esc_html_e('Electronics', 'astra-ai'); ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php esc_html_e('Latest gadgets, smartphones, laptops, and tech accessories', 'astra-ai'); ?>
                        </p>
                        <div class="flex items-center text-primary font-medium">
                            <?php esc_html_e('Browse Electronics', 'astra-ai'); ?>
                            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Fashion -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer category-card" data-category="fashion">
                    <div class="h-48 bg-gradient-to-r from-pink-500 to-rose-600 flex items-center justify-center">
                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?php esc_html_e('Fashion', 'astra-ai'); ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php esc_html_e('Trendy clothing, shoes, accessories for men and women', 'astra-ai'); ?>
                        </p>
                        <div class="flex items-center text-primary font-medium">
                            <?php esc_html_e('Browse Fashion', 'astra-ai'); ?>
                            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Home & Garden -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer category-card" data-category="home-garden">
                    <div class="h-48 bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center">
                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?php esc_html_e('Home & Garden', 'astra-ai'); ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php esc_html_e('Furniture, decor, garden tools, and home improvement items', 'astra-ai'); ?>
                        </p>
                        <div class="flex items-center text-primary font-medium">
                            <?php esc_html_e('Browse Home & Garden', 'astra-ai'); ?>
                            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sports & Outdoors -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer category-card" data-category="sports">
                    <div class="h-48 bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center">
                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?php esc_html_e('Sports & Outdoors', 'astra-ai'); ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php esc_html_e('Sports equipment, outdoor gear, fitness accessories', 'astra-ai'); ?>
                        </p>
                        <div class="flex items-center text-primary font-medium">
                            <?php esc_html_e('Browse Sports', 'astra-ai'); ?>
                            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Books & Media -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer category-card" data-category="books">
                    <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?php esc_html_e('Books & Media', 'astra-ai'); ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php esc_html_e('Books, e-books, movies, music, and educational content', 'astra-ai'); ?>
                        </p>
                        <div class="flex items-center text-primary font-medium">
                            <?php esc_html_e('Browse Books', 'astra-ai'); ?>
                            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Health & Beauty -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer category-card" data-category="health-beauty">
                    <div class="h-48 bg-gradient-to-r from-emerald-500 to-cyan-600 flex items-center justify-center">
                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?php esc_html_e('Health & Beauty', 'astra-ai'); ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php esc_html_e('Skincare, cosmetics, health supplements, wellness products', 'astra-ai'); ?>
                        </p>
                        <div class="flex items-center text-primary font-medium">
                            <?php esc_html_e('Browse Health & Beauty', 'astra-ai'); ?>
                            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Stats -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Why Choose Our Categories?', 'astra-ai'); ?>
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary/10 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Quality Products', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('All products are carefully curated and quality-checked before listing.', 'astra-ai'); ?>
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary/10 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Fast Delivery', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Quick and reliable shipping across all product categories.', 'astra-ai'); ?>
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary/10 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Best Prices', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Competitive pricing with regular discounts and special offers.', 'astra-ai'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>