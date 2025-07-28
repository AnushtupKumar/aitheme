<?php
/**
 * Contact Page Template
 * 
 * @package Astra-AI
 * @version 1.0.0
 */
?>

<div id="contact-page" class="page-content">
    <!-- Contact Hero -->
    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                <?php esc_html_e('Contact Us', 'astra-ai'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100">
                <?php esc_html_e('We\'d love to hear from you. Get in touch with our team.', 'astra-ai'); ?>
            </p>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">
                        <?php esc_html_e('Send us a message', 'astra-ai'); ?>
                    </h2>
                    <form id="contact-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first-name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php esc_html_e('First name', 'astra-ai'); ?>
                                </label>
                                <input type="text" id="first-name" name="first-name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                            </div>
                            <div>
                                <label for="last-name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php esc_html_e('Last name', 'astra-ai'); ?>
                                </label>
                                <input type="text" id="last-name" name="last-name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php esc_html_e('Email address', 'astra-ai'); ?>
                            </label>
                            <input type="email" id="email" name="email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php esc_html_e('Subject', 'astra-ai'); ?>
                            </label>
                            <select id="subject" name="subject" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                                <option value=""><?php esc_html_e('Select a subject', 'astra-ai'); ?></option>
                                <option value="general"><?php esc_html_e('General Inquiry', 'astra-ai'); ?></option>
                                <option value="support"><?php esc_html_e('Technical Support', 'astra-ai'); ?></option>
                                <option value="billing"><?php esc_html_e('Billing Question', 'astra-ai'); ?></option>
                                <option value="partnership"><?php esc_html_e('Partnership Opportunity', 'astra-ai'); ?></option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php esc_html_e('Message', 'astra-ai'); ?>
                            </label>
                            <textarea id="message" name="message" rows="6" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                                      placeholder="<?php esc_attr_e('Tell us how we can help...', 'astra-ai'); ?>"></textarea>
                        </div>
                        
                        <div>
                            <button type="submit" 
                                    class="w-full bg-primary text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary/90 transition-colors">
                                <?php esc_html_e('Send Message', 'astra-ai'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">
                        <?php esc_html_e('Get in touch', 'astra-ai'); ?>
                    </h2>
                    <p class="text-gray-600 mb-8">
                        <?php esc_html_e('We\'re here to help and answer any question you might have. We look forward to hearing from you.', 'astra-ai'); ?>
                    </p>

                    <div class="space-y-6">
                        <!-- Email -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-primary/10 rounded-lg p-3">
                                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <?php esc_html_e('Email', 'astra-ai'); ?>
                                </h3>
                                <p class="text-gray-600">
                                    support@astra-ai.com
                                </p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-primary/10 rounded-lg p-3">
                                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <?php esc_html_e('Phone', 'astra-ai'); ?>
                                </h3>
                                <p class="text-gray-600">
                                    +1 (555) 123-4567
                                </p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-primary/10 rounded-lg p-3">
                                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <?php esc_html_e('Office', 'astra-ai'); ?>
                                </h3>
                                <p class="text-gray-600">
                                    123 AI Street<br>
                                    Tech City, TC 12345<br>
                                    United States
                                </p>
                            </div>
                        </div>

                        <!-- Hours -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-primary/10 rounded-lg p-3">
                                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <?php esc_html_e('Hours', 'astra-ai'); ?>
                                </h3>
                                <p class="text-gray-600">
                                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                                    Saturday: 10:00 AM - 4:00 PM<br>
                                    Sunday: Closed
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Frequently Asked Questions', 'astra-ai'); ?>
                </h2>
                <p class="text-xl text-gray-600">
                    <?php esc_html_e('Quick answers to common questions', 'astra-ai'); ?>
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow-sm">
                        <button class="faq-toggle w-full px-6 py-4 text-left flex justify-between items-center">
                            <span class="font-medium text-gray-900">
                                <?php esc_html_e('How does the AI recommendation system work?', 'astra-ai'); ?>
                            </span>
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content hidden px-6 pb-4">
                            <p class="text-gray-600">
                                <?php esc_html_e('Our AI system analyzes your browsing behavior, purchase history, and preferences to suggest products that match your interests. The more you interact with our platform, the better our recommendations become.', 'astra-ai'); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm">
                        <button class="faq-toggle w-full px-6 py-4 text-left flex justify-between items-center">
                            <span class="font-medium text-gray-900">
                                <?php esc_html_e('Is my personal data secure?', 'astra-ai'); ?>
                            </span>
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content hidden px-6 pb-4">
                            <p class="text-gray-600">
                                <?php esc_html_e('Yes, we take data security very seriously. All personal information is encrypted and stored securely. We never share your data with third parties without your explicit consent.', 'astra-ai'); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm">
                        <button class="faq-toggle w-full px-6 py-4 text-left flex justify-between items-center">
                            <span class="font-medium text-gray-900">
                                <?php esc_html_e('What payment methods do you accept?', 'astra-ai'); ?>
                            </span>
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-content hidden px-6 pb-4">
                            <p class="text-gray-600">
                                <?php esc_html_e('We accept all major credit cards (Visa, MasterCard, American Express), PayPal, Apple Pay, Google Pay, and bank transfers.', 'astra-ai'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>