/**
 * Astra-AI SPA Application
 * Main JavaScript file for the single-page application functionality
 * 
 * @package Astra-AI
 * @version 1.0.0
 */

(function() {
    'use strict';
    
    // Global SPA object
    window.AstraAISPA = {
        currentPage: 1,
        currentRoute: 'home',
        isLoading: false,
        products: [],
        cart: [],
        templates: {},
        
        // Initialize the SPA
        init: function() {
            this.bindEvents();
            this.initRouter();
            this.loadInitialData();
            this.initializeCart();
            this.setupIntersectionObserver();
            this.loadTemplates();
        },
        
        // Load template files
        loadTemplates: function() {
            const templates = ['shop', 'about', 'contact', 'categories'];
            
            templates.forEach(template => {
                fetch(`${astraAI.restUrl}astra-ai/v1/template/${template}`)
                    .then(response => response.text())
                    .then(html => {
                        this.templates[template] = html;
                    })
                    .catch(error => {
                        console.log(`Error loading ${template} template:`, error);
                    });
            });
        },
        
        // Initialize router
        initRouter: function() {
            // Handle browser back/forward buttons
            window.addEventListener('popstate', (e) => {
                if (e.state && e.state.route) {
                    this.navigateTo(e.state.route, false);
                }
            });
            
            // Set initial route
            const hash = window.location.hash.substring(1);
            if (hash) {
                this.navigateTo(hash, false);
            } else {
                this.navigateTo('home', false);
            }
        },
        
        // Navigate to route without page reload
        navigateTo: function(route, pushState = true) {
            if (this.currentRoute === route) return;
            
            this.showLoading();
            this.currentRoute = route;
            
            // Update URL without reload
            if (pushState) {
                history.pushState({route: route}, '', `#${route}`);
            }
            
            // Update active navigation
            this.updateActiveNav(route);
            
            // Load page content
            this.loadPageContent(route);
        },
        
        // Update active navigation
        updateActiveNav: function(route) {
            // Remove active class from all nav links
            document.querySelectorAll('.spa-link').forEach(link => {
                link.classList.remove('text-primary');
                link.classList.add('text-gray-700');
            });
            
            // Add active class to current route
            document.querySelectorAll(`[data-route="${route}"]`).forEach(link => {
                link.classList.remove('text-gray-700');
                link.classList.add('text-primary');
            });
        },
        
        // Load page content
        loadPageContent: function(route) {
            const contentDiv = document.getElementById('spa-content');
            
            switch(route) {
                case 'home':
                    this.loadHomePage();
                    break;
                case 'shop':
                    this.loadShopPage();
                    break;
                case 'categories':
                    this.loadCategoriesPage();
                    break;
                case 'about':
                    this.loadAboutPage();
                    break;
                case 'contact':
                    this.loadContactPage();
                    break;
                default:
                    this.loadHomePage();
            }
        },
        
        // Load home page
        loadHomePage: function() {
            const contentDiv = document.getElementById('spa-content');
            const homePageContent = document.getElementById('home-page');
            
            if (homePageContent) {
                contentDiv.innerHTML = homePageContent.outerHTML;
                this.loadInitialData();
            }
            
            this.hideLoading();
        },
        
        // Load shop page
        loadShopPage: function() {
            if (this.templates.shop) {
                document.getElementById('spa-content').innerHTML = this.templates.shop;
                this.initShopPage();
            } else {
                // Fallback content
                this.loadShopPageFallback();
            }
            this.hideLoading();
        },
        
        // Load shop page fallback
        loadShopPageFallback: function() {
            const content = `
                <div id="shop-page" class="page-content">
                    <section class="bg-white py-12 border-b">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="text-center">
                                <h1 class="text-4xl font-bold text-gray-900 mb-4">Shop</h1>
                                <p class="text-xl text-gray-600">Discover our amazing collection of products</p>
                            </div>
                        </div>
                    </section>
                    <section class="py-12">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div id="shop-products" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                <!-- Products will be loaded here -->
                            </div>
                        </div>
                    </section>
                </div>
            `;
            document.getElementById('spa-content').innerHTML = content;
            this.loadAllProducts('shop-products');
        },
        
        // Initialize shop page
        initShopPage: function() {
            this.loadAllProducts('shop-products');
            this.bindShopEvents();
        },
        
        // Load categories page
        loadCategoriesPage: function() {
            if (this.templates.categories) {
                document.getElementById('spa-content').innerHTML = this.templates.categories;
                this.initCategoriesPage();
            } else {
                this.loadCategoriesPageFallback();
            }
            this.hideLoading();
        },
        
        // Load categories page fallback
        loadCategoriesPageFallback: function() {
            const content = `
                <div id="categories-page" class="page-content">
                    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                            <h1 class="text-4xl md:text-6xl font-bold mb-6">Product Categories</h1>
                            <p class="text-xl md:text-2xl text-blue-100">Explore our wide range of product categories</p>
                        </div>
                    </section>
                    <section class="py-16 bg-white">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div id="categories-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                                <!-- Categories will be loaded here -->
                            </div>
                        </div>
                    </section>
                </div>
            `;
            document.getElementById('spa-content').innerHTML = content;
        },
        
        // Initialize categories page
        initCategoriesPage: function() {
            this.loadCategories();
            this.bindCategoryEvents();
        },
        
        // Load about page
        loadAboutPage: function() {
            if (this.templates.about) {
                document.getElementById('spa-content').innerHTML = this.templates.about;
            } else {
                this.loadAboutPageFallback();
            }
            this.hideLoading();
        },
        
        // Load about page fallback
        loadAboutPageFallback: function() {
            const content = `
                <div id="about-page" class="page-content">
                    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                            <h1 class="text-4xl md:text-6xl font-bold mb-6">About Us</h1>
                            <p class="text-xl md:text-2xl text-blue-100">Revolutionizing e-commerce with AI-powered shopping experiences</p>
                        </div>
                    </section>
                    <section class="py-16 bg-white">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="text-center">
                                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Mission</h2>
                                <p class="text-xl text-gray-600 max-w-3xl mx-auto">We believe that shopping should be personal, intuitive, and delightful. Our AI-powered platform learns from your preferences to deliver a truly personalized shopping experience.</p>
                            </div>
                        </div>
                    </section>
                </div>
            `;
            document.getElementById('spa-content').innerHTML = content;
        },
        
        // Load contact page
        loadContactPage: function() {
            if (this.templates.contact) {
                document.getElementById('spa-content').innerHTML = this.templates.contact;
                this.initContactPage();
            } else {
                this.loadContactPageFallback();
            }
            this.hideLoading();
        },
        
        // Load contact page fallback
        loadContactPageFallback: function() {
            const content = `
                <div id="contact-page" class="page-content">
                    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                            <h1 class="text-4xl md:text-6xl font-bold mb-6">Contact Us</h1>
                            <p class="text-xl md:text-2xl text-blue-100">We'd love to hear from you. Get in touch with our team.</p>
                        </div>
                    </section>
                    <section class="py-16 bg-white">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="text-center">
                                <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a message</h2>
                                <p class="text-gray-600">We're here to help and answer any question you might have.</p>
                            </div>
                        </div>
                    </section>
                </div>
            `;
            document.getElementById('spa-content').innerHTML = content;
        },
        
        // Initialize contact page
        initContactPage: function() {
            this.bindContactEvents();
            this.bindFAQEvents();
        },
        
        // Show loading state
        showLoading: function() {
            const loading = document.getElementById('spa-loading');
            if (loading) {
                loading.classList.remove('hidden');
            }
        },
        
        // Hide loading state
        hideLoading: function() {
            const loading = document.getElementById('spa-loading');
            if (loading) {
                loading.classList.add('hidden');
            }
            
            const app = document.getElementById('astra-ai-app');
            if (app) {
                app.classList.add('loaded');
            }
        },
        
        // Bind event listeners
        bindEvents: function() {
            // SPA navigation links
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('spa-link') || e.target.closest('.spa-link')) {
                    e.preventDefault();
                    const link = e.target.classList.contains('spa-link') ? e.target : e.target.closest('.spa-link');
                    const route = link.getAttribute('data-route');
                    if (route) {
                        this.navigateTo(route);
                    }
                }
            });
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Search functionality
            const searchInput = document.getElementById('ai-search-input');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.performSearch(e.target.value);
                    }, 300);
                });
            }
            
            // Cart icon click
            const cartIcon = document.getElementById('cart-icon');
            if (cartIcon) {
                cartIcon.addEventListener('click', () => {
                    this.toggleCartSidebar();
                });
            }
            
            // Cart close
            const cartClose = document.getElementById('cart-close');
            const cartOverlay = document.getElementById('cart-overlay');
            if (cartClose) cartClose.addEventListener('click', () => this.closeCartSidebar());
            if (cartOverlay) cartOverlay.addEventListener('click', () => this.closeCartSidebar());
            
            // Load more products
            const loadMoreBtn = document.getElementById('load-more-products');
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', () => {
                    this.loadMoreProducts();
                });
            }
            
            // Modal close
            const modalClose = document.getElementById('modal-close');
            const modalOverlay = document.getElementById('modal-overlay');
            if (modalClose) modalClose.addEventListener('click', () => this.closeProductModal());
            if (modalOverlay) modalOverlay.addEventListener('click', () => this.closeProductModal());
        },
        
        // Bind shop page events
        bindShopEvents: function() {
            const categoryFilter = document.getElementById('category-filter');
            const priceFilter = document.getElementById('price-filter');
            const sortProducts = document.getElementById('sort-products');
            
            if (categoryFilter) {
                categoryFilter.addEventListener('change', () => this.filterProducts());
            }
            if (priceFilter) {
                priceFilter.addEventListener('change', () => this.filterProducts());
            }
            if (sortProducts) {
                sortProducts.addEventListener('change', () => this.sortProducts());
            }
        },
        
        // Bind category events
        bindCategoryEvents: function() {
            document.querySelectorAll('.category-card').forEach(card => {
                card.addEventListener('click', () => {
                    const category = card.getAttribute('data-category');
                    this.navigateTo('shop');
                    // Filter by category after navigation
                    setTimeout(() => {
                        this.filterByCategory(category);
                    }, 100);
                });
            });
        },
        
        // Bind contact events
        bindContactEvents: function() {
            const contactForm = document.getElementById('contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.submitContactForm(contactForm);
                });
            }
        },
        
        // Bind FAQ events
        bindFAQEvents: function() {
            document.querySelectorAll('.faq-toggle').forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const content = toggle.nextElementSibling;
                    const icon = toggle.querySelector('svg');
                    
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        content.classList.add('hidden');
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        },
        
        // Load initial data
        loadInitialData: function() {
            this.loadPersonalizedRecommendations();
            this.loadFeaturedProducts();
            this.loadAllProducts();
        },
        
        // Load personalized recommendations
        loadPersonalizedRecommendations: function() {
            if (!astraAI.enableAI) return;
            
            fetch(`${astraAI.restUrl}astra-ai/v1/recommendations/personalized`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                this.renderProducts(data, 'personalized-recommendations');
            })
            .catch(error => {
                console.log('Error loading personalized recommendations:', error);
                // Fallback to popular products
                this.loadPopularProducts('personalized-recommendations');
            });
        },
        
        // Load featured products
        loadFeaturedProducts: function() {
            fetch(`${astraAI.restUrl}astra-ai/v1/products?per_page=8&featured=true`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                this.renderProducts(data, 'featured-products');
            })
            .catch(error => {
                console.log('Error loading featured products:', error);
                this.loadPopularProducts('featured-products');
            });
        },
        
        // Load all products
        loadAllProducts: function(containerId = 'all-products') {
            const container = document.getElementById(containerId);
            if (!container) return;
            
            fetch(`${astraAI.restUrl}astra-ai/v1/products?per_page=12&page=${this.currentPage}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (this.currentPage === 1) {
                    this.products = data;
                } else {
                    this.products = [...this.products, ...data];
                }
                this.renderProducts(this.products, containerId);
                
                // Hide loading states
                const loading = document.getElementById('shop-loading');
                if (loading) loading.classList.add('hidden');
                
                const productsGrid = document.getElementById(containerId);
                if (productsGrid) productsGrid.classList.remove('hidden');
            })
            .catch(error => {
                console.log('Error loading products:', error);
                this.loadSampleProducts(containerId);
            });
        },
        
        // Load sample products as fallback
        loadSampleProducts: function(containerId) {
            const sampleProducts = [
                {
                    id: 1,
                    name: 'Premium Wireless Headphones',
                    price: '$199.99',
                    image: 'https://via.placeholder.com/300x300/2563eb/ffffff?text=Headphones',
                    rating: 4.5
                },
                {
                    id: 2,
                    name: 'Smart Fitness Watch',
                    price: '$299.99',
                    image: 'https://via.placeholder.com/300x300/10b981/ffffff?text=Watch',
                    rating: 4.8
                },
                {
                    id: 3,
                    name: 'Eco-Friendly Water Bottle',
                    price: '$24.99',
                    image: 'https://via.placeholder.com/300x300/f59e0b/ffffff?text=Bottle',
                    rating: 4.3
                },
                {
                    id: 4,
                    name: 'Wireless Charging Pad',
                    price: '$49.99',
                    image: 'https://via.placeholder.com/300x300/8b5cf6/ffffff?text=Charger',
                    rating: 4.6
                }
            ];
            
            this.renderProducts(sampleProducts, containerId);
        },
        
        // Render products
        renderProducts: function(products, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            
            container.innerHTML = products.map(product => `
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer product-card" data-product-id="${product.id}">
                    <div class="aspect-w-1 aspect-h-1">
                        <img src="${product.image || 'https://via.placeholder.com/300x300/e5e7eb/6b7280?text=Product'}" 
                             alt="${product.name}" 
                             class="w-full h-48 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">${product.name}</h3>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-primary">${product.price}</span>
                            ${product.rating ? `
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        ${'★'.repeat(Math.floor(product.rating))}${'☆'.repeat(5 - Math.floor(product.rating))}
                                    </div>
                                    <span class="ml-1 text-sm text-gray-600">(${product.rating})</span>
                                </div>
                            ` : ''}
                        </div>
                        <button class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/90 transition-colors add-to-cart-btn" data-product-id="${product.id}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            `).join('');
            
            // Bind product events
            this.bindProductEvents(container);
        },
        
        // Bind product events
        bindProductEvents: function(container) {
            container.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', (e) => {
                    if (!e.target.classList.contains('add-to-cart-btn')) {
                        const productId = card.getAttribute('data-product-id');
                        this.showProductModal(productId);
                    }
                });
            });
            
            container.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const productId = btn.getAttribute('data-product-id');
                    this.addToCart(productId);
                });
            });
        },
        
        // Initialize cart
        initializeCart: function() {
            this.updateCartCount();
        },
        
        // Add to cart
        addToCart: function(productId) {
            // Add cart functionality here
            console.log('Adding to cart:', productId);
            this.updateCartCount();
        },
        
        // Update cart count
        updateCartCount: function() {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = this.cart.length;
            }
        },
        
        // Toggle cart sidebar
        toggleCartSidebar: function() {
            const cartSidebar = document.getElementById('cart-sidebar');
            if (cartSidebar) {
                cartSidebar.classList.toggle('hidden');
            }
        },
        
        // Close cart sidebar
        closeCartSidebar: function() {
            const cartSidebar = document.getElementById('cart-sidebar');
            if (cartSidebar) {
                cartSidebar.classList.add('hidden');
            }
        },
        
        // Show product modal
        showProductModal: function(productId) {
            const modal = document.getElementById('product-modal');
            if (modal) {
                modal.classList.remove('hidden');
                // Load product details
                this.loadProductDetails(productId);
            }
        },
        
        // Close product modal
        closeProductModal: function() {
            const modal = document.getElementById('product-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        },
        
        // Load product details
        loadProductDetails: function(productId) {
            const content = document.getElementById('modal-product-content');
            if (content) {
                content.innerHTML = `
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
                        <div class="h-32 bg-gray-200 rounded mb-4"></div>
                    </div>
                `;
                
                // Simulate loading product details
                setTimeout(() => {
                    content.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <img src="https://via.placeholder.com/400x400/2563eb/ffffff?text=Product" alt="Product" class="w-full rounded-lg">
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Sample Product</h2>
                                <p class="text-3xl font-bold text-primary mb-4">$99.99</p>
                                <p class="text-gray-600 mb-6">This is a sample product description. In a real implementation, this would be loaded from your product database.</p>
                                <button class="w-full bg-primary text-white py-3 px-6 rounded-lg hover:bg-primary/90 transition-colors">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    `;
                }, 1000);
            }
        },
        
        // Perform search
        performSearch: function(query) {
            if (!query.trim()) {
                this.hideSearchResults();
                return;
            }
            
            // Simulate search results
            const results = [
                { id: 1, name: 'Search Result 1', price: '$29.99' },
                { id: 2, name: 'Search Result 2', price: '$39.99' },
            ];
            
            this.showSearchResults(results);
        },
        
        // Show search results
        showSearchResults: function(results) {
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.innerHTML = results.map(result => `
                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b">
                        <div class="font-medium">${result.name}</div>
                        <div class="text-sm text-gray-600">${result.price}</div>
                    </div>
                `).join('');
                searchResults.classList.remove('hidden');
            }
        },
        
        // Hide search results
        hideSearchResults: function() {
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.classList.add('hidden');
            }
        },
        
        // Setup intersection observer for lazy loading
        setupIntersectionObserver: function() {
            // Implementation for lazy loading and infinite scroll
        },
        
        // Load more products
        loadMoreProducts: function() {
            this.currentPage++;
            this.loadAllProducts();
        }
    };
    
    // Initialize SPA when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        window.AstraAISPA.init();
    });
    
})();