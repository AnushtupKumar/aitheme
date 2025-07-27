/**
 * Astra-AI Features
 * Advanced AI functionality for the theme
 * 
 * @package Astra-AI
 * @version 1.0.0
 */

(function() {
    'use strict';
    
    // AI Features object
    window.AstraAIFeatures = {
        
        // User behavior tracking
        userBehavior: {
            pageViews: [],
            productViews: [],
            searchQueries: [],
            cartActions: [],
            timeSpent: {}
        },
        
        // Initialize AI features
        init: function() {
            this.trackUserBehavior();
            this.initializeSmartSearch();
            this.setupPriceOptimization();
            this.initializePersonalization();
            this.setupAnalytics();
        },
        
        // Track user behavior for AI learning
        trackUserBehavior: function() {
            // Track page views
            this.trackPageView();
            
            // Track product views
            this.trackProductViews();
            
            // Track scroll behavior
            this.trackScrollBehavior();
            
            // Track time spent on page
            this.trackTimeSpent();
            
            // Track cart interactions
            this.trackCartInteractions();
        },
        
        // Track page views
        trackPageView: function() {
            const pageData = {
                url: window.location.href,
                title: document.title,
                timestamp: new Date().toISOString(),
                referrer: document.referrer,
                userAgent: navigator.userAgent
            };
            
            this.userBehavior.pageViews.push(pageData);
            this.sendAnalyticsData('page_view', pageData);
        },
        
        // Track product views
        trackProductViews: function() {
            // Observer for product cards
            const productObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const productId = entry.target.getAttribute('data-product-id');
                        if (productId) {
                            this.trackProductView(productId);
                        }
                    }
                });
            }, { threshold: 0.5 });
            
            // Observe all product cards
            document.querySelectorAll('.product-card').forEach(card => {
                productObserver.observe(card);
            });
        },
        
        // Track individual product view
        trackProductView: function(productId) {
            const viewData = {
                productId: productId,
                timestamp: new Date().toISOString(),
                duration: 0,
                source: 'grid'
            };
            
            this.userBehavior.productViews.push(viewData);
            this.sendAnalyticsData('product_view', viewData);
        },
        
        // Track scroll behavior
        trackScrollBehavior: function() {
            let scrollDepth = 0;
            let maxScrollDepth = 0;
            
            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset;
                const docHeight = document.body.scrollHeight - window.innerHeight;
                scrollDepth = Math.round((scrollTop / docHeight) * 100);
                
                if (scrollDepth > maxScrollDepth) {
                    maxScrollDepth = scrollDepth;
                }
            });
            
            // Send scroll data when user leaves page
            window.addEventListener('beforeunload', () => {
                this.sendAnalyticsData('scroll_depth', {
                    maxDepth: maxScrollDepth,
                    timestamp: new Date().toISOString()
                });
            });
        },
        
        // Track time spent on page
        trackTimeSpent: function() {
            const startTime = Date.now();
            
            window.addEventListener('beforeunload', () => {
                const timeSpent = Date.now() - startTime;
                this.sendAnalyticsData('time_spent', {
                    duration: timeSpent,
                    url: window.location.href,
                    timestamp: new Date().toISOString()
                });
            });
        },
        
        // Track cart interactions
        trackCartInteractions: function() {
            // Override the original addToCart function to add tracking
            if (window.AstraAISPA) {
                const originalAddToCart = window.AstraAISPA.addToCart;
                window.AstraAISPA.addToCart = (productId) => {
                    // Track the action
                    this.trackCartAction('add', productId);
                    
                    // Call original function
                    return originalAddToCart.call(window.AstraAISPA, productId);
                };
            }
        },
        
        // Track cart action
        trackCartAction: function(action, productId, quantity = 1) {
            const actionData = {
                action: action,
                productId: productId,
                quantity: quantity,
                timestamp: new Date().toISOString()
            };
            
            this.userBehavior.cartActions.push(actionData);
            this.sendAnalyticsData('cart_action', actionData);
        },
        
        // Initialize smart search with AI
        initializeSmartSearch: function() {
            const searchInput = document.getElementById('ai-search-input');
            if (!searchInput) return;
            
            // Add smart search suggestions
            this.addSearchSuggestions(searchInput);
            
            // Add search analytics
            this.trackSearchBehavior(searchInput);
            
            // Add voice search if supported
            this.addVoiceSearch(searchInput);
        },
        
        // Add search suggestions
        addSearchSuggestions: function(searchInput) {
            let suggestionsContainer = document.getElementById('search-suggestions');
            
            if (!suggestionsContainer) {
                suggestionsContainer = document.createElement('div');
                suggestionsContainer.id = 'search-suggestions';
                suggestionsContainer.className = 'search-suggestions hidden';
                searchInput.parentNode.appendChild(suggestionsContainer);
            }
            
            searchInput.addEventListener('focus', () => {
                this.showPopularSearches(suggestionsContainer);
            });
            
            searchInput.addEventListener('input', (e) => {
                if (e.target.value.length > 2) {
                    this.generateSmartSuggestions(e.target.value, suggestionsContainer);
                }
            });
            
            searchInput.addEventListener('blur', () => {
                setTimeout(() => {
                    suggestionsContainer.classList.add('hidden');
                }, 200);
            });
        },
        
        // Show popular searches
        showPopularSearches: function(container) {
            const popularSearches = [
                'wireless headphones',
                'smart watch',
                'laptop',
                'phone case',
                'bluetooth speaker'
            ];
            
            let html = '<div class="suggestions-header">Popular Searches</div>';
            popularSearches.forEach(search => {
                html += `<div class="suggestion-item" data-search="${search}">${search}</div>`;
            });
            
            container.innerHTML = html;
            container.classList.remove('hidden');
            
            // Add click events
            container.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', () => {
                    const searchTerm = item.getAttribute('data-search');
                    document.getElementById('ai-search-input').value = searchTerm;
                    window.AstraAISPA.performSearch(searchTerm);
                });
            });
        },
        
        // Generate smart suggestions
        generateSmartSuggestions: function(query, container) {
            // This would integrate with an AI service for better suggestions
            // For now, we'll use a simple matching algorithm
            
            const suggestions = this.getSmartSuggestions(query);
            
            if (suggestions.length === 0) {
                container.classList.add('hidden');
                return;
            }
            
            let html = '<div class="suggestions-header">Suggestions</div>';
            suggestions.forEach(suggestion => {
                html += `<div class="suggestion-item" data-search="${suggestion}">${suggestion}</div>`;
            });
            
            container.innerHTML = html;
            container.classList.remove('hidden');
            
            // Add click events
            container.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', () => {
                    const searchTerm = item.getAttribute('data-search');
                    document.getElementById('ai-search-input').value = searchTerm;
                    window.AstraAISPA.performSearch(searchTerm);
                });
            });
        },
        
        // Get smart suggestions (would be enhanced with AI)
        getSmartSuggestions: function(query) {
            const commonSuggestions = {
                'phone': ['phone case', 'phone charger', 'phone screen protector'],
                'laptop': ['laptop bag', 'laptop stand', 'laptop charger'],
                'watch': ['smart watch', 'watch band', 'fitness watch'],
                'headphone': ['wireless headphones', 'bluetooth headphones', 'gaming headphones'],
                'speaker': ['bluetooth speaker', 'smart speaker', 'portable speaker']
            };
            
            const queryLower = query.toLowerCase();
            let suggestions = [];
            
            Object.keys(commonSuggestions).forEach(key => {
                if (queryLower.includes(key)) {
                    suggestions = [...suggestions, ...commonSuggestions[key]];
                }
            });
            
            return suggestions.slice(0, 5);
        },
        
        // Track search behavior
        trackSearchBehavior: function(searchInput) {
            searchInput.addEventListener('input', (e) => {
                const searchData = {
                    query: e.target.value,
                    timestamp: new Date().toISOString(),
                    length: e.target.value.length
                };
                
                // Only track queries with 3+ characters
                if (searchData.length >= 3) {
                    this.userBehavior.searchQueries.push(searchData);
                }
            });
        },
        
        // Add voice search
        addVoiceSearch: function(searchInput) {
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                return; // Voice recognition not supported
            }
            
            const voiceButton = document.createElement('button');
            voiceButton.innerHTML = '🎤';
            voiceButton.className = 'voice-search-btn';
            voiceButton.style.cssText = `
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                font-size: 18px;
                cursor: pointer;
                z-index: 10;
            `;
            
            searchInput.parentNode.appendChild(voiceButton);
            
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';
            
            voiceButton.addEventListener('click', () => {
                recognition.start();
                voiceButton.style.color = '#ef4444';
            });
            
            recognition.addEventListener('result', (e) => {
                const transcript = e.results[0][0].transcript;
                searchInput.value = transcript;
                window.AstraAISPA.performSearch(transcript);
                voiceButton.style.color = '';
            });
            
            recognition.addEventListener('error', () => {
                voiceButton.style.color = '';
            });
            
            recognition.addEventListener('end', () => {
                voiceButton.style.color = '';
            });
        },
        
        // Setup price optimization
        setupPriceOptimization: function() {
            if (!astraAI.enableAI) return;
            
            // Monitor competitor prices (would integrate with external APIs)
            this.monitorCompetitorPrices();
            
            // Dynamic pricing based on demand
            this.implementDynamicPricing();
            
            // A/B test different price displays
            this.setupPriceABTesting();
        },
        
        // Monitor competitor prices
        monitorCompetitorPrices: function() {
            // This would integrate with price monitoring APIs
            console.log('Price monitoring initialized');
        },
        
        // Implement dynamic pricing
        implementDynamicPricing: function() {
            // This would analyze demand patterns and adjust prices
            console.log('Dynamic pricing initialized');
        },
        
        // Setup price A/B testing
        setupPriceABTesting: function() {
            // Test different price display formats
            const priceElements = document.querySelectorAll('.product-price');
            
            priceElements.forEach(priceEl => {
                if (Math.random() > 0.5) {
                    // Variant A: Show savings
                    this.enhancePriceDisplay(priceEl, 'savings');
                } else {
                    // Variant B: Show urgency
                    this.enhancePriceDisplay(priceEl, 'urgency');
                }
            });
        },
        
        // Enhance price display
        enhancePriceDisplay: function(priceElement, variant) {
            const currentPrice = priceElement.textContent;
            
            if (variant === 'savings') {
                // Add savings indicator
                const savings = document.createElement('div');
                savings.className = 'price-savings';
                savings.textContent = 'Save 20%';
                savings.style.cssText = 'color: #10b981; font-size: 12px; font-weight: bold;';
                priceElement.appendChild(savings);
            } else if (variant === 'urgency') {
                // Add urgency indicator
                const urgency = document.createElement('div');
                urgency.className = 'price-urgency';
                urgency.textContent = 'Limited time!';
                urgency.style.cssText = 'color: #ef4444; font-size: 12px; font-weight: bold;';
                priceElement.appendChild(urgency);
            }
        },
        
        // Initialize personalization
        initializePersonalization: function() {
            // Personalize content based on user behavior
            this.personalizeRecommendations();
            
            // Customize UI based on preferences
            this.customizeUserInterface();
            
            // Personalize email notifications
            this.setupPersonalizedNotifications();
        },
        
        // Personalize recommendations
        personalizeRecommendations: function() {
            // This would use machine learning to improve recommendations
            console.log('Personalization engine initialized');
        },
        
        // Customize user interface
        customizeUserInterface: function() {
            // Adapt UI based on user behavior patterns
            const userPreferences = this.getUserPreferences();
            
            if (userPreferences.prefersDarkMode) {
                this.enableDarkMode();
            }
            
            if (userPreferences.prefersLargeText) {
                this.enableLargeText();
            }
        },
        
        // Get user preferences
        getUserPreferences: function() {
            // Analyze user behavior to determine preferences
            return {
                prefersDarkMode: window.matchMedia('(prefers-color-scheme: dark)').matches,
                prefersLargeText: false,
                preferredCategories: []
            };
        },
        
        // Enable dark mode
        enableDarkMode: function() {
            document.body.classList.add('dark-mode');
        },
        
        // Enable large text
        enableLargeText: function() {
            document.body.classList.add('large-text');
        },
        
        // Setup personalized notifications
        setupPersonalizedNotifications: function() {
            // Setup intelligent notifications based on user behavior
            this.setupAbandonedCartReminders();
            this.setupRecommendationNotifications();
        },
        
        // Setup abandoned cart reminders
        setupAbandonedCartReminders: function() {
            let cartTimeout;
            
            // Monitor cart changes
            const originalAddToCart = window.AstraAISPA?.addToCart;
            if (originalAddToCart) {
                window.AstraAISPA.addToCart = function(productId) {
                    clearTimeout(cartTimeout);
                    
                    // Set reminder for 30 minutes
                    cartTimeout = setTimeout(() => {
                        AstraAIFeatures.showAbandonedCartReminder();
                    }, 30 * 60 * 1000);
                    
                    return originalAddToCart.call(this, productId);
                };
            }
        },
        
        // Show abandoned cart reminder
        showAbandonedCartReminder: function() {
            if (window.AstraAISPA?.cart?.length > 0) {
                this.showNotification(
                    'Don\'t forget your items!',
                    'Complete your purchase and get free shipping.',
                    'cart'
                );
            }
        },
        
        // Setup recommendation notifications
        setupRecommendationNotifications: function() {
            // Show personalized recommendations after browsing
            setTimeout(() => {
                this.showRecommendationNotification();
            }, 5 * 60 * 1000); // After 5 minutes
        },
        
        // Show recommendation notification
        showRecommendationNotification: function() {
            if (this.userBehavior.productViews.length > 3) {
                this.showNotification(
                    'Based on your browsing...',
                    'We found some products you might love!',
                    'recommendations'
                );
            }
        },
        
        // Show notification
        showNotification: function(title, message, type) {
            // Check if notifications are supported and permitted
            if (!('Notification' in window)) return;
            
            if (Notification.permission === 'granted') {
                new Notification(title, {
                    body: message,
                    icon: '/wp-content/themes/astra-ai/assets/images/notification-icon.png',
                    tag: type
                });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        new Notification(title, {
                            body: message,
                            icon: '/wp-content/themes/astra-ai/assets/images/notification-icon.png',
                            tag: type
                        });
                    }
                });
            }
        },
        
        // Setup analytics
        setupAnalytics: function() {
            // Initialize conversion tracking
            this.setupConversionTracking();
            
            // Setup heatmap tracking
            this.setupHeatmapTracking();
            
            // Setup performance monitoring
            this.setupPerformanceMonitoring();
        },
        
        // Setup conversion tracking
        setupConversionTracking: function() {
            // Track conversion funnel
            this.trackConversionFunnel();
            
            // Track goal completions
            this.trackGoalCompletions();
        },
        
        // Track conversion funnel
        trackConversionFunnel: function() {
            const funnelSteps = [
                'product_view',
                'add_to_cart',
                'checkout_start',
                'purchase_complete'
            ];
            
            // This would integrate with analytics platforms
            console.log('Conversion funnel tracking initialized');
        },
        
        // Track goal completions
        trackGoalCompletions: function() {
            // Define and track custom goals
            const goals = {
                newsletter_signup: 'Newsletter Subscription',
                account_creation: 'Account Creation',
                first_purchase: 'First Purchase'
            };
            
            // Track goal events
            Object.keys(goals).forEach(goalId => {
                this.setupGoalTracking(goalId, goals[goalId]);
            });
        },
        
        // Setup goal tracking
        setupGoalTracking: function(goalId, goalName) {
            // This would set up event listeners for specific goals
            console.log(`Goal tracking setup for: ${goalName}`);
        },
        
        // Setup heatmap tracking
        setupHeatmapTracking: function() {
            // Track clicks, scrolls, and mouse movements
            this.trackClickHeatmap();
            this.trackScrollHeatmap();
        },
        
        // Track click heatmap
        trackClickHeatmap: function() {
            document.addEventListener('click', (e) => {
                const clickData = {
                    x: e.clientX,
                    y: e.clientY,
                    element: e.target.tagName,
                    timestamp: new Date().toISOString()
                };
                
                this.sendAnalyticsData('click_heatmap', clickData);
            });
        },
        
        // Track scroll heatmap
        trackScrollHeatmap: function() {
            let scrollData = [];
            
            window.addEventListener('scroll', () => {
                scrollData.push({
                    scrollY: window.pageYOffset,
                    timestamp: new Date().toISOString()
                });
            });
            
            // Send data periodically
            setInterval(() => {
                if (scrollData.length > 0) {
                    this.sendAnalyticsData('scroll_heatmap', scrollData);
                    scrollData = [];
                }
            }, 30000); // Every 30 seconds
        },
        
        // Setup performance monitoring
        setupPerformanceMonitoring: function() {
            // Monitor page load times
            window.addEventListener('load', () => {
                const perfData = performance.timing;
                const loadTime = perfData.loadEventEnd - perfData.navigationStart;
                
                this.sendAnalyticsData('performance', {
                    loadTime: loadTime,
                    domContentLoaded: perfData.domContentLoadedEventEnd - perfData.navigationStart,
                    firstPaint: performance.getEntriesByType('paint')[0]?.startTime || 0
                });
            });
        },
        
        // Send analytics data
        sendAnalyticsData: function(eventType, data) {
            // This would send data to your analytics service
            if (astraAI.enableAI) {
                jQuery.post(astraAI.ajaxUrl, {
                    action: 'astra_ai_analytics',
                    event_type: eventType,
                    data: JSON.stringify(data),
                    nonce: astraAI.nonce
                });
            }
        }
    };
    
    // Initialize AI features when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        AstraAIFeatures.init();
    });
    
})();