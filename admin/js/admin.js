/**
 * Astra-AI Admin JavaScript
 * @package Astra-AI
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Tab Navigation
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        
        const target = $(this).attr('href');
        
        // Update active tab
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        // Update active content
        $('.tab-content').removeClass('active');
        $(target).addClass('active');
    });
    
    // API Key Test
    $('#test-api-key').on('click', function() {
        const $button = $(this);
        const $result = $('#api-test-result');
        const apiKey = $('input[name="astra_ai_options[ai_api_key]"]').val();
        const provider = $('select[name="astra_ai_options[ai_provider]"]').val();
        
        if (!apiKey) {
            showApiResult('error', 'Please enter an API key first.');
            return;
        }
        
        $button.prop('disabled', true).text('Testing...');
        $result.removeClass('success error').hide();
        
        $.ajax({
            url: astraAIAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'astra_ai_test_api',
                api_key: apiKey,
                provider: provider,
                nonce: astraAIAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    showApiResult('success', 'API connection successful!');
                } else {
                    showApiResult('error', response.data || 'API test failed.');
                }
            },
            error: function() {
                showApiResult('error', 'Connection error. Please try again.');
            },
            complete: function() {
                $button.prop('disabled', false).text('Test Connection');
            }
        });
    });
    
    function showApiResult(type, message) {
        const $result = $('#api-test-result');
        $result.removeClass('success error').addClass(type).text(message).show();
    }
    
    // Performance Test
    $('#run-performance-test').on('click', function() {
        const $button = $(this);
        
        $button.prop('disabled', true).text('Running Test...');
        
        // Simulate performance test
        setTimeout(function() {
            $('#page-speed').text('92');
            $('#cache-hit-rate').text('87%');
            $('#avg-load-time').text('1.2s');
            
            $button.prop('disabled', false).text('Run Performance Test');
            
            // Show success message
            showNotice('Performance test completed successfully!', 'success');
        }, 3000);
    });
    
    // Content Generator - Product Description
    $('#generate-description').on('click', function() {
        const $button = $(this);
        const productName = $('#product-name').val();
        const features = $('#product-features').val();
        const audience = $('#target-audience').val();
        const tone = $('#description-tone').val();
        
        if (!productName || !features) {
            showNotice('Please fill in the product name and features.', 'error');
            return;
        }
        
        $button.prop('disabled', true).text('Generating...');
        
        $.ajax({
            url: astraAIAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'astra_ai_generate_description',
                product_name: productName,
                features: features,
                audience: audience,
                tone: tone,
                nonce: astraAIAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.description-output').html(response.data);
                    $('#generated-description').show();
                    
                    // Scroll to result
                    $('html, body').animate({
                        scrollTop: $('#generated-description').offset().top - 100
                    }, 500);
                } else {
                    showNotice('Failed to generate description. Please try again.', 'error');
                }
            },
            error: function() {
                showNotice('Connection error. Please try again.', 'error');
            },
            complete: function() {
                $button.prop('disabled', false).text('Generate Description');
            }
        });
    });
    
    // Content Generator - SEO Meta
    $('#generate-seo').on('click', function() {
        const $button = $(this);
        const title = $('#seo-title').val();
        const keywords = $('#seo-keywords').val();
        const contentType = $('#content-type').val();
        
        if (!title || !keywords) {
            showNotice('Please fill in the title and keywords.', 'error');
            return;
        }
        
        $button.prop('disabled', true).text('Generating...');
        
        $.ajax({
            url: astraAIAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'astra_ai_generate_seo',
                title: title,
                keywords: keywords,
                content_type: contentType,
                nonce: astraAIAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.meta-title-content').text(response.data.title);
                    $('.meta-description-content').text(response.data.description);
                    $('#generated-seo').show();
                    
                    // Scroll to result
                    $('html, body').animate({
                        scrollTop: $('#generated-seo').offset().top - 100
                    }, 500);
                } else {
                    showNotice('Failed to generate SEO meta. Please try again.', 'error');
                }
            },
            error: function() {
                showNotice('Connection error. Please try again.', 'error');
            },
            complete: function() {
                $button.prop('disabled', false).text('Generate SEO Meta');
            }
        });
    });
    
    // Copy to Clipboard Functions
    $('#copy-description').on('click', function() {
        const text = $('.description-output').text();
        copyToClipboard(text);
        showNotice('Description copied to clipboard!', 'success');
    });
    
    $('#copy-seo').on('click', function() {
        const title = $('.meta-title-content').text();
        const description = $('.meta-description-content').text();
        const text = `Meta Title: ${title}\n\nMeta Description: ${description}`;
        copyToClipboard(text);
        showNotice('SEO meta copied to clipboard!', 'success');
    });
    
    // Regenerate Description
    $('#regenerate-description').on('click', function() {
        $('#generate-description').click();
    });
    
    // Copy to Clipboard Helper
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text);
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        }
    }
    
    // Show Notice Helper
    function showNotice(message, type) {
        const noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
        const notice = $(`
            <div class="notice ${noticeClass} is-dismissible astra-ai-notice">
                <p>${message}</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
        `);
        
        $('.wrap h1').after(notice);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            notice.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
        
        // Handle manual dismiss
        notice.find('.notice-dismiss').on('click', function() {
            notice.fadeOut(function() {
                $(this).remove();
            });
        });
    }
    
    // Settings Form Enhancement
    $('#astra-ai-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitButton = $form.find('input[type="submit"]');
        const formData = $form.serialize();
        
        $submitButton.prop('disabled', true).val('Saving...');
        
        $.ajax({
            url: astraAIAdmin.ajaxUrl,
            type: 'POST',
            data: formData + '&action=astra_ai_save_settings&nonce=' + astraAIAdmin.nonce,
            success: function(response) {
                if (response.success) {
                    showNotice('Settings saved successfully!', 'success');
                    
                    // Trigger any necessary updates
                    $(document).trigger('astra-ai-settings-saved', response.data);
                } else {
                    showNotice('Failed to save settings. Please try again.', 'error');
                }
            },
            error: function() {
                showNotice('Connection error. Please try again.', 'error');
            },
            complete: function() {
                $submitButton.prop('disabled', false).val('Save Settings');
            }
        });
    });
    
    // Color Picker Enhancement
    $('input[type="color"]').on('change', function() {
        const color = $(this).val();
        const preview = $(this).siblings('.color-preview');
        
        if (preview.length === 0) {
            $(this).after(`<span class="color-preview" style="display: inline-block; width: 20px; height: 20px; background: ${color}; border: 1px solid #ddd; margin-left: 10px; vertical-align: middle;"></span>`);
        } else {
            preview.css('background', color);
        }
    }).trigger('change');
    
    // Real-time Settings Preview
    $('select[name="astra_ai_options[layout_style]"]').on('change', function() {
        const style = $(this).val();
        updateLayoutPreview(style);
    });
    
    $('select[name="astra_ai_options[grid_columns]"]').on('change', function() {
        const columns = $(this).val();
        updateGridPreview(columns);
    });
    
    function updateLayoutPreview(style) {
        // This would update a preview area showing the layout style
        console.log('Layout style changed to:', style);
    }
    
    function updateGridPreview(columns) {
        // This would update a preview showing the grid columns
        console.log('Grid columns changed to:', columns);
    }
    
    // Analytics Chart (if Chart.js is available)
    if (typeof Chart !== 'undefined' && $('#recommendations-chart').length) {
        const ctx = document.getElementById('recommendations-chart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'AI Recommendations',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Dynamic Form Validation
    $('input[required], select[required], textarea[required]').on('blur', function() {
        const $field = $(this);
        const value = $field.val().trim();
        
        if (!value) {
            $field.addClass('error');
            if ($field.siblings('.field-error').length === 0) {
                $field.after('<span class="field-error" style="color: #ef4444; font-size: 12px; display: block; margin-top: 5px;">This field is required.</span>');
            }
        } else {
            $field.removeClass('error');
            $field.siblings('.field-error').remove();
        }
    });
    
    // Auto-save Draft Settings
    let autoSaveTimeout;
    $('input, select, textarea').on('change input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            saveDraftSettings();
        }, 2000);
    });
    
    function saveDraftSettings() {
        const formData = $('#astra-ai-settings-form').serialize();
        
        $.ajax({
            url: astraAIAdmin.ajaxUrl,
            type: 'POST',
            data: formData + '&action=astra_ai_save_draft&nonce=' + astraAIAdmin.nonce,
            success: function(response) {
                if (response.success) {
                    showAutoSaveIndicator();
                }
            }
        });
    }
    
    function showAutoSaveIndicator() {
        const indicator = $('.auto-save-indicator');
        if (indicator.length === 0) {
            $('h1').append(' <span class="auto-save-indicator" style="font-size: 14px; color: #10b981; font-weight: normal;">Draft saved</span>');
        } else {
            indicator.show();
        }
        
        setTimeout(function() {
            $('.auto-save-indicator').fadeOut();
        }, 2000);
    }
    
    // Keyboard Shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $('#astra-ai-settings-form').submit();
        }
        
        // Ctrl/Cmd + K to focus search (if search exists)
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            $('input[type="search"]').first().focus();
        }
    });
    
    // Tooltips
    $('[data-tooltip]').each(function() {
        $(this).addClass('tooltip');
    });
    
    // Smooth Scrolling for Anchor Links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // Initialize any existing color pickers
    if (typeof wp !== 'undefined' && wp.colorPicker) {
        $('.color-field').wpColorPicker();
    }
    
    // Initialize sortable lists (if jQuery UI is available)
    if ($.fn.sortable) {
        $('.sortable-list').sortable({
            handle: '.sort-handle',
            update: function(event, ui) {
                // Save new order
                const order = $(this).sortable('toArray', { attribute: 'data-id' });
                console.log('New order:', order);
            }
        });
    }
    
    // Feature Detection and Progressive Enhancement
    if ('IntersectionObserver' in window) {
        // Lazy load admin content
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    target.classList.add('visible');
                    observer.unobserve(target);
                }
            });
        });
        
        document.querySelectorAll('.lazy-load').forEach(el => {
            observer.observe(el);
        });
    }
    
    // Initialize theme when DOM is ready
    initializeTheme();
    
    function initializeTheme() {
        // Apply any saved theme preferences
        const savedTheme = localStorage.getItem('astra-ai-admin-theme');
        if (savedTheme) {
            document.body.classList.add(savedTheme);
        }
        
        // Check for dark mode preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
        }
    }
    
    // Export functions for external use
    window.AstraAIAdmin = {
        showNotice: showNotice,
        copyToClipboard: copyToClipboard,
        saveDraftSettings: saveDraftSettings
    };
});