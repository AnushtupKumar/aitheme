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
        isLoading: false,
        products: [],
        cart: [],
        
        // Initialize the SPA
        init: function() {
            this.bindEvents();
            this.loadInitialData();
            this.initializeCart();
            this.setupIntersectionObserver();
        },
        
        // Bind event listeners
        bindEvents: function() {
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
            });
        },
        
        // Load all products
        loadAllProducts: function() {
            if (this.isLoading) return;
            this.isLoading = true;
            
            fetch(`${astraAI.restUrl}astra-ai/v1/products?page=${this.currentPage}&per_page=12`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                this.products = [...this.products, ...data];
                this.renderProducts(this.products, 'all-products');
                this.isLoading = false;
            })
            .catch(error => {
                console.log('Error loading products:', error);
                this.isLoading = false;
            });
        },
        
        // Load more products
        loadMoreProducts: function() {
            this.currentPage++;
            this.loadAllProducts();
        },
        
        // Load popular products as fallback
        loadPopularProducts: function(containerId) {
            fetch(`${astraAI.restUrl}astra-ai/v1/recommendations/popular`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                this.renderProducts(data, containerId);
            })
            .catch(error => {
                console.log('Error loading popular products:', error);
            });
        },
        
        // Render products
        renderProducts: function(products, containerId) {
            const container = document.getElementById(containerId);
            if (!container || !products.length) return;
            
            // Clear container if it's not the all-products grid (for pagination)
            if (containerId !== 'all-products') {
                container.innerHTML = '';
            }
            
            products.forEach(product => {
                const productCard = this.createProductCard(product);
                container.appendChild(productCard);
            });
            
            // Add animation
            const cards = container.querySelectorAll('.product-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, index * 100);
            });
        },
        
        // Create product card element
        createProductCard: function(product) {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.setAttribute('data-product-id', product.id);
            
            const imageUrl = product.thumbnail || product.image || '/wp-content/themes/astra-ai/assets/images/placeholder.jpg';
            
            card.innerHTML = `
                <img src="${imageUrl}" alt="${this.escapeHtml(product.name)}" class="product-image" loading="lazy">
                <div class="product-info">
                    <h3 class="product-title">${this.escapeHtml(product.name)}</h3>
                    <div class="product-price">${product.price}</div>
                    <button class="add-to-cart-btn" data-product-id="${product.id}">
                        Add to Cart
                    </button>
                </div>
            `;
            
            // Add click event for product details
            card.addEventListener('click', (e) => {
                if (!e.target.classList.contains('add-to-cart-btn')) {
                    this.showProductModal(product.id);
                }
            });
            
            // Add to cart functionality
            const addToCartBtn = card.querySelector('.add-to-cart-btn');
            addToCartBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.addToCart(product.id);
            });
            
            return card;
        },
        
        // Show product modal
        showProductModal: function(productId) {
            const modal = document.getElementById('product-modal');
            const modalContent = document.getElementById('modal-product-content');
            
            if (!modal || !modalContent) return;
            
            // Show loading
            modalContent.innerHTML = '<div class="spinner" style="margin: 40px auto;"></div>';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Load product details
            this.loadProductDetails(productId, modalContent);
        },
        
        // Load product details
        loadProductDetails: function(productId, container) {
            // For now, show basic product info
            // In a real implementation, you'd fetch detailed product data
            const product = this.findProductById(productId);
            if (!product) return;
            
            container.innerHTML = `
                <div class="modal-product-details">
                    <div class="product-image-large">
                        <img src="${product.image || product.thumbnail}" alt="${this.escapeHtml(product.name)}">
                    </div>
                    <div class="product-details">
                        <h2>${this.escapeHtml(product.name)}</h2>
                        <div class="product-price-large">${product.price}</div>
                        <div class="product-description">
                            ${product.description || 'Product description coming soon...'}
                        </div>
                        <div class="product-actions">
                            <button class="add-to-cart-btn-large" data-product-id="${product.id}">
                                Add to Cart
                            </button>
                        </div>
                        
                        <!-- AI Recommendations in Modal -->
                        <div class="modal-recommendations">
                            <h3>Frequently Bought Together</h3>
                            <div id="modal-recommendations" class="recommendations-grid">
                                <!-- Recommendations will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add event listener for add to cart in modal
            const addToCartBtn = container.querySelector('.add-to-cart-btn-large');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', () => {
                    this.addToCart(productId);
                });
            }
            
            // Load AI recommendations for this product
            this.loadProductRecommendations(productId, 'modal-recommendations');
        },
        
        // Load product recommendations
        loadProductRecommendations: function(productId, containerId) {
            if (!astraAI.enableAI) return;
            
            fetch(`${astraAI.restUrl}astra-ai/v1/recommendations/frequently_bought_together?product_id=${productId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                this.renderMiniProducts(data, containerId);
            })
            .catch(error => {
                console.log('Error loading product recommendations:', error);
            });
        },
        
        // Render mini products for recommendations
        renderMiniProducts: function(products, containerId) {
            const container = document.getElementById(containerId);
            if (!container || !products.length) return;
            
            container.innerHTML = '';
            
            products.forEach(product => {
                const miniCard = document.createElement('div');
                miniCard.className = 'mini-product-card';
                miniCard.innerHTML = `
                    <img src="${product.thumbnail || product.image}" alt="${this.escapeHtml(product.name)}" class="mini-product-image">
                    <div class="mini-product-info">
                        <div class="mini-product-name">${this.escapeHtml(product.name)}</div>
                        <div class="mini-product-price">${product.price}</div>
                        <button class="mini-add-to-cart" data-product-id="${product.id}">Add</button>
                    </div>
                `;
                
                // Add to cart functionality
                const addBtn = miniCard.querySelector('.mini-add-to-cart');
                addBtn.addEventListener('click', () => {
                    this.addToCart(product.id);
                });
                
                container.appendChild(miniCard);
            });
        },
        
        // Close product modal
        closeProductModal: function() {
            const modal = document.getElementById('product-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        },
        
        // Perform AI search
        performSearch: function(query) {
            if (!query.trim()) {
                this.hideSearchResults();
                return;
            }
            
            const searchResults = document.getElementById('search-results');
            if (!searchResults) return;
            
            // Show loading
            searchResults.innerHTML = '<div class="search-loading">Searching...</div>';
            searchResults.classList.remove('hidden');
            
            // Perform search via AJAX
            jQuery.post(astraAI.ajaxUrl, {
                action: 'astra_ai_search',
                query: query,
                nonce: astraAI.nonce
            })
            .done((response) => {
                if (response.success) {
                    this.renderSearchResults(response.data);
                } else {
                    this.showSearchError();
                }
            })
            .fail(() => {
                this.showSearchError();
            });
        },
        
        // Render search results
        renderSearchResults: function(results) {
            const searchResults = document.getElementById('search-results');
            if (!searchResults) return;
            
            if (!results.length) {
                searchResults.innerHTML = '<div class="no-results">No products found</div>';
                return;
            }
            
            let html = '<div class="search-results-list">';
            results.forEach(product => {
                html += `
                    <div class="search-result-item" data-product-id="${product.id}">
                        <img src="${product.image}" alt="${this.escapeHtml(product.name)}" class="search-result-image">
                        <div class="search-result-info">
                            <div class="search-result-name">${this.escapeHtml(product.name)}</div>
                            <div class="search-result-price">${product.price}</div>
                            <div class="search-result-excerpt">${product.excerpt || ''}</div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            searchResults.innerHTML = html;
            
            // Add click events
            searchResults.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', () => {
                    const productId = item.getAttribute('data-product-id');
                    this.showProductModal(productId);
                    this.hideSearchResults();
                });
            });
        },
        
        // Hide search results
        hideSearchResults: function() {
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.classList.add('hidden');
            }
        },
        
        // Show search error
        showSearchError: function() {
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.innerHTML = '<div class="search-error">Search error. Please try again.</div>';
            }
        },
        
        // Initialize cart
        initializeCart: function() {
            this.updateCartDisplay();
        },
        
        // Add to cart
        addToCart: function(productId) {
            const product = this.findProductById(productId);
            if (!product) return;
            
            // Add to local cart array
            const existingItem = this.cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.cart.push({
                    id: productId,
                    name: product.name,
                    price: product.regular_price || product.price,
                    image: product.thumbnail || product.image,
                    quantity: 1
                });
            }
            
            this.updateCartDisplay();
            this.showAddToCartFeedback(product.name);
            
            // Also add to WooCommerce cart via AJAX
            this.addToWooCommerceCart(productId);
        },
        
        // Add to WooCommerce cart
        addToWooCommerceCart: function(productId) {
            jQuery.post(astraAI.ajaxUrl, {
                action: 'woocommerce_add_to_cart',
                product_id: productId,
                quantity: 1
            })
            .done((response) => {
                // Handle WooCommerce response if needed
            });
        },
        
        // Update cart display
        updateCartDisplay: function() {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                const totalItems = this.cart.reduce((sum, item) => sum + item.quantity, 0);
                cartCount.textContent = totalItems;
                
                if (totalItems > 0) {
                    cartCount.style.display = 'flex';
                } else {
                    cartCount.style.display = 'none';
                }
            }
        },
        
        // Show add to cart feedback
        showAddToCartFeedback: function(productName) {
            // Create and show a temporary notification
            const notification = document.createElement('div');
            notification.className = 'cart-notification';
            notification.innerHTML = `${productName} added to cart!`;
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                background: #10b981;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                z-index: 10000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Remove after delay
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        },
        
        // Toggle cart sidebar
        toggleCartSidebar: function() {
            const cartSidebar = document.getElementById('cart-sidebar');
            if (cartSidebar) {
                if (cartSidebar.classList.contains('hidden')) {
                    this.openCartSidebar();
                } else {
                    this.closeCartSidebar();
                }
            }
        },
        
        // Open cart sidebar
        openCartSidebar: function() {
            const cartSidebar = document.getElementById('cart-sidebar');
            if (cartSidebar) {
                cartSidebar.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                this.renderCartItems();
            }
        },
        
        // Close cart sidebar
        closeCartSidebar: function() {
            const cartSidebar = document.getElementById('cart-sidebar');
            if (cartSidebar) {
                cartSidebar.classList.add('hidden');
                document.body.style.overflow = '';
            }
        },
        
        // Render cart items
        renderCartItems: function() {
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');
            
            if (!cartItems || !cartTotal) return;
            
            if (!this.cart.length) {
                cartItems.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
                cartTotal.innerHTML = '';
                return;
            }
            
            let html = '';
            let total = 0;
            
            this.cart.forEach(item => {
                const itemTotal = parseFloat(item.price) * item.quantity;
                total += itemTotal;
                
                html += `
                    <div class="cart-item" data-product-id="${item.id}">
                        <img src="${item.image}" alt="${this.escapeHtml(item.name)}" class="cart-item-image">
                        <div class="cart-item-details">
                            <div class="cart-item-name">${this.escapeHtml(item.name)}</div>
                            <div class="cart-item-price">$${item.price}</div>
                            <div class="cart-item-quantity">
                                <button class="quantity-decrease" data-product-id="${item.id}">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="quantity-increase" data-product-id="${item.id}">+</button>
                            </div>
                        </div>
                        <button class="remove-item" data-product-id="${item.id}">×</button>
                    </div>
                `;
            });
            
            cartItems.innerHTML = html;
            cartTotal.innerHTML = `<strong>Total: $${total.toFixed(2)}</strong>`;
            
            // Add event listeners for quantity changes and removal
            cartItems.querySelectorAll('.quantity-decrease').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-product-id'));
                    this.updateCartItemQuantity(productId, -1);
                });
            });
            
            cartItems.querySelectorAll('.quantity-increase').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-product-id'));
                    this.updateCartItemQuantity(productId, 1);
                });
            });
            
            cartItems.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-product-id'));
                    this.removeFromCart(productId);
                });
            });
        },
        
        // Update cart item quantity
        updateCartItemQuantity: function(productId, change) {
            const item = this.cart.find(item => item.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    this.removeFromCart(productId);
                } else {
                    this.updateCartDisplay();
                    this.renderCartItems();
                }
            }
        },
        
        // Remove from cart
        removeFromCart: function(productId) {
            this.cart = this.cart.filter(item => item.id !== productId);
            this.updateCartDisplay();
            this.renderCartItems();
        },
        
        // Setup intersection observer for lazy loading
        setupIntersectionObserver: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    });
                });
                
                // Observe lazy images
                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        },
        
        // Utility functions
        findProductById: function(productId) {
            return this.products.find(product => product.id == productId);
        },
        
        escapeHtml: function(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    };
    
})();