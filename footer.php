<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Astra-AI
 * @version 1.0.0
 */

?>

<!-- Footer Section -->
<footer class="astra-footer" style="background: #1f2937; color: white; padding: 60px 20px 20px;">
    <div class="footer-content" style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Footer Top -->
        <div class="footer-top" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
            
            <!-- Company Info -->
            <div class="footer-section">
                <h3 style="color: #2563eb; margin-bottom: 20px; font-size: 20px;"><?php echo esc_html(get_bloginfo('name')); ?></h3>
                <p style="margin-bottom: 15px; line-height: 1.6; color: #d1d5db;">
                    <?php echo esc_html(get_bloginfo('description')); ?>
                </p>
                <p style="color: #d1d5db; line-height: 1.6;">
                    <?php esc_html_e('Experience the future of e-commerce with AI-powered personalization and lightning-fast performance.', 'astra-ai'); ?>
                </p>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section">
                <h4 style="color: white; margin-bottom: 20px; font-size: 18px;"><?php esc_html_e('Quick Links', 'astra-ai'); ?></h4>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;">
                        <a href="<?php echo esc_url(home_url('/')); ?>" style="color: #d1d5db; text-decoration: none; transition: color 0.3s ease;">
                            <?php esc_html_e('Home', 'astra-ai'); ?>
                        </a>
                    </li>
                    <li style="margin-bottom: 10px;">
                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" style="color: #d1d5db; text-decoration: none; transition: color 0.3s ease;">
                            <?php esc_html_e('Shop', 'astra-ai'); ?>
                        </a>
                    </li>
                    <li style="margin-bottom: 10px;">
                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" style="color: #d1d5db; text-decoration: none; transition: color 0.3s ease;">
                            <?php esc_html_e('My Account', 'astra-ai'); ?>
                        </a>
                    </li>
                    <li style="margin-bottom: 10px;">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" style="color: #d1d5db; text-decoration: none; transition: color 0.3s ease;">
                            <?php esc_html_e('Cart', 'astra-ai'); ?>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Customer Service -->
            <div class="footer-section">
                <h4 style="color: white; margin-bottom: 20px; font-size: 18px;"><?php esc_html_e('Customer Service', 'astra-ai'); ?></h4>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;">
                        <a href="#" style="color: #d1d5db; text-decoration: none; transition: color 0.3s ease;">
                            <?php esc_html_e('Contact Us', 'astra-ai'); ?>
                        </a>
                    </li>
                    <li style="margin-bottom: 10px;">
                        <a href="#" style="color: #d1d5db; text-decoration: none; transition: color 0.3s ease;">
                            <?php esc_html_e('FAQ', 'astra-ai'); ?>
                        </a>
                    </li>
                    <li style="margin-bottom: 10px;">
                        <a href="#" style="color: #d1d5db; text-decoration: none; transition: color 0.3s ease;">
                            <?php esc_html_e('Shipping Info', 'astra-ai'); ?>
                        </a>
                    </li>
                    <li style="margin-bottom: 10px;">
                        <a href="#" style="color: #d1d5db; text-decoration: none; transition: color 0.3s ease;">
                            <?php esc_html_e('Returns', 'astra-ai'); ?>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Newsletter Signup -->
            <div class="footer-section">
                <h4 style="color: white; margin-bottom: 20px; font-size: 18px;"><?php esc_html_e('Stay Updated', 'astra-ai'); ?></h4>
                <p style="color: #d1d5db; margin-bottom: 15px; line-height: 1.6;">
                    <?php esc_html_e('Subscribe to get AI-powered product recommendations and exclusive offers.', 'astra-ai'); ?>
                </p>
                <div class="newsletter-form" style="display: flex; gap: 10px;">
                    <input 
                        type="email" 
                        placeholder="<?php esc_attr_e('Enter your email', 'astra-ai'); ?>"
                        style="flex: 1; padding: 12px; border: none; border-radius: 6px; background: #374151; color: white;"
                        id="newsletter-email"
                    >
                    <button 
                        type="button"
                        style="background: #2563eb; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;"
                        id="newsletter-subscribe"
                    >
                        <?php esc_html_e('Subscribe', 'astra-ai'); ?>
                    </button>
                </div>
            </div>
            
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom" style="border-top: 1px solid #374151; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            
            <div class="copyright" style="color: #9ca3af;">
                <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'astra-ai'); ?></p>
            </div>
            
            <div class="footer-links" style="display: flex; gap: 20px; flex-wrap: wrap;">
                <a href="#" style="color: #9ca3af; text-decoration: none; font-size: 14px;">
                    <?php esc_html_e('Privacy Policy', 'astra-ai'); ?>
                </a>
                <a href="#" style="color: #9ca3af; text-decoration: none; font-size: 14px;">
                    <?php esc_html_e('Terms of Service', 'astra-ai'); ?>
                </a>
                <a href="#" style="color: #9ca3af; text-decoration: none; font-size: 14px;">
                    <?php esc_html_e('Cookie Policy', 'astra-ai'); ?>
                </a>
            </div>
            
        </div>
        
    </div>
</footer>

<!-- Back to Top Button -->
<button id="back-to-top" class="back-to-top" style="position: fixed; bottom: 30px; right: 30px; background: #2563eb; color: white; border: none; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; display: none; z-index: 1000; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);">
    ↑
</button>

<!-- Performance Metrics (for development) -->
<?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
<div id="performance-metrics" style="position: fixed; top: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; font-size: 12px; z-index: 10000;">
    <div>Queries: <?php echo get_num_queries(); ?></div>
    <div>Load Time: <span id="page-load-time"></span>ms</div>
</div>
<script>
window.addEventListener('load', function() {
    const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
    document.getElementById('page-load-time').textContent = loadTime;
});
</script>
<?php endif; ?>

<?php wp_footer(); ?>

<!-- Initialize SPA after all scripts are loaded -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the SPA
    if (typeof AstraAISPA !== 'undefined') {
        AstraAISPA.init();
    }
    
    // Show the app after initialization
    const app = document.getElementById('astra-ai-app');
    if (app) {
        app.classList.add('loaded');
    }
    
    // Hide loading screen
    const loading = document.getElementById('spa-loading');
    if (loading) {
        setTimeout(() => {
            loading.classList.add('hidden');
        }, 500);
    }
    
    // Back to top functionality
    const backToTop = document.getElementById('back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Newsletter subscription
    const newsletterBtn = document.getElementById('newsletter-subscribe');
    if (newsletterBtn) {
        newsletterBtn.addEventListener('click', function() {
            const email = document.getElementById('newsletter-email').value;
            if (email && email.includes('@')) {
                // Here you would typically send the email to your newsletter service
                alert('Thank you for subscribing!');
                document.getElementById('newsletter-email').value = '';
            } else {
                alert('Please enter a valid email address.');
            }
        });
    }
});
</script>

<!-- Schema.org Organization markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
    "url": "<?php echo esc_url(home_url()); ?>",
    "description": "<?php echo esc_js(get_bloginfo('description')); ?>",
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service"
    }
}
</script>

</body>
</html>