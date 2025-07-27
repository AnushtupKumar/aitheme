<?php
/**
 * About Page Template
 * 
 * @package Astra-AI
 * @version 1.0.0
 */
?>

<div id="about-page" class="page-content">
    <!-- About Hero -->
    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                <?php esc_html_e('About Us', 'astra-ai'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100">
                <?php esc_html_e('Revolutionizing e-commerce with AI-powered shopping experiences', 'astra-ai'); ?>
            </p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Our Mission', 'astra-ai'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php esc_html_e('We believe that shopping should be personal, intuitive, and delightful. Our AI-powered platform learns from your preferences to deliver a truly personalized shopping experience.', 'astra-ai'); ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('What Makes Us Different', 'astra-ai'); ?>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary/10 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('AI-Powered Recommendations', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Our advanced AI algorithms analyze your preferences to suggest products you\'ll love.', 'astra-ai'); ?>
                    </p>
                </div>
                <div class="text-center">
                    <div class="bg-primary/10 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Lightning Fast', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Our single-page application delivers instant navigation and seamless user experience.', 'astra-ai'); ?>
                    </p>
                </div>
                <div class="text-center">
                    <div class="bg-primary/10 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Personalized Experience', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Every interaction is tailored to your unique preferences and shopping behavior.', 'astra-ai'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Our Values', 'astra-ai'); ?>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Innovation', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('We constantly push the boundaries of what\'s possible in e-commerce technology.', 'astra-ai'); ?>
                    </p>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Quality', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('We maintain the highest standards in everything we do, from code to customer service.', 'astra-ai'); ?>
                    </p>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Privacy', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Your data is secure and we respect your privacy at every step of your journey.', 'astra-ai'); ?>
                    </p>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Support', 'astra-ai'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('We\'re here to help you succeed with dedicated support and resources.', 'astra-ai'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-primary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                <?php esc_html_e('Ready to Experience the Future?', 'astra-ai'); ?>
            </h2>
            <p class="text-xl mb-8 text-blue-100">
                <?php esc_html_e('Join thousands of satisfied customers who have discovered the power of AI-driven shopping.', 'astra-ai'); ?>
            </p>
            <button class="spa-link bg-white text-primary px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transition-colors" data-route="shop">
                <?php esc_html_e('Start Shopping Now', 'astra-ai'); ?>
            </button>
        </div>
    </section>
</div>