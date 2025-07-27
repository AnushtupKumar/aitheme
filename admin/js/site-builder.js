/**
 * Astra-AI Site Builder JavaScript
 * 
 * @package Astra-AI
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Site Builder Object
    const AstraSiteBuilder = {
        currentPage: 'home',
        blocks: {},
        
        // Initialize
        init: function() {
            this.loadBlocks();
            this.bindEvents();
            this.initSortable();
            this.loadPageLayout();
        },
        
        // Load available blocks
        loadBlocks: function() {
            if (typeof astraSiteBuilder !== 'undefined' && astraSiteBuilder.blocks) {
                this.blocks = astraSiteBuilder.blocks;
                this.renderBlockCategories();
            }
        },
        
        // Render block categories
        renderBlockCategories: function() {
            const $availableBlocks = $('#available-blocks');
            
            // Show layout blocks by default
            this.renderBlocksForCategory('layout');
        },
        
        // Render blocks for specific category
        renderBlocksForCategory: function(category) {
            const $availableBlocks = $('#available-blocks');
            $availableBlocks.empty();
            
            if (this.blocks[category]) {
                this.blocks[category].forEach(block => {
                    const blockHtml = `
                        <div class="block-item bg-white border border-gray-200 rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" 
                             data-block-type="${block.id}" 
                             draggable="true">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">${block.icon}</span>
                                <span class="text-sm font-medium text-gray-700">${block.name}</span>
                            </div>
                        </div>
                    `;
                    $availableBlocks.append(blockHtml);
                });
            }
        },
        
        // Bind events
        bindEvents: function() {
            // Block category selection
            $('.block-category').on('click', (e) => {
                $('.block-category').removeClass('active bg-blue-100 text-blue-700').addClass('text-gray-700');
                $(e.target).removeClass('text-gray-700').addClass('active bg-blue-100 text-blue-700');
                
                const category = $(e.target).data('category');
                this.renderBlocksForCategory(category);
            });
            
            // Page selector change
            $('#page-selector').on('change', (e) => {
                this.currentPage = $(e.target).val();
                this.loadPageLayout();
            });
            
            // Save button
            $('#save-btn').on('click', () => {
                this.savePageLayout();
            });
            
            // Preview button
            $('#preview-btn').on('click', () => {
                this.previewPage();
            });
            
            // Block drag start
            $(document).on('dragstart', '.block-item', (e) => {
                const blockType = $(e.target).data('block-type');
                e.originalEvent.dataTransfer.setData('text/plain', blockType);
            });
            
            // Canvas drop events
            $('#page-canvas').on('dragover', (e) => {
                e.preventDefault();
                $(e.currentTarget).addClass('drag-over');
            });
            
            $('#page-canvas').on('dragleave', (e) => {
                $(e.currentTarget).removeClass('drag-over');
            });
            
            $('#page-canvas').on('drop', (e) => {
                e.preventDefault();
                $(e.currentTarget).removeClass('drag-over');
                
                const blockType = e.originalEvent.dataTransfer.getData('text/plain');
                this.addBlockToCanvas(blockType);
            });
            
            // Block controls
            $(document).on('click', '.edit-block', (e) => {
                e.stopPropagation();
                const $block = $(e.target).closest('.block');
                this.editBlock($block);
            });
            
            $(document).on('click', '.delete-block', (e) => {
                e.stopPropagation();
                const $block = $(e.target).closest('.block');
                this.deleteBlock($block);
            });
            
            // Block selection
            $(document).on('click', '.block', (e) => {
                $('.block').removeClass('selected');
                $(e.currentTarget).addClass('selected');
                this.showBlockProperties($(e.currentTarget));
            });
            
            // Editable content
            $(document).on('blur', '.editable', (e) => {
                const $element = $(e.target);
                const property = $element.data('property');
                const value = $element.text();
                
                // Update block data
                const $block = $element.closest('.block');
                if (!$block.data('block-data')) {
                    $block.data('block-data', {});
                }
                const blockData = $block.data('block-data');
                blockData[property] = value;
                $block.data('block-data', blockData);
            });
        },
        
        // Initialize sortable
        initSortable: function() {
            $('#page-canvas').sortable({
                handle: '.move-block',
                placeholder: 'block-placeholder',
                tolerance: 'pointer',
                cursor: 'move'
            });
        },
        
        // Add block to canvas
        addBlockToCanvas: function(blockType) {
            const template = $(`#${blockType}-block-template`).html();
            if (template) {
                const $canvas = $('#page-canvas');
                
                // Remove empty state if it exists
                $canvas.find('.text-center.py-20').remove();
                
                // Add the block
                $canvas.append(template);
                
                // Show success message
                this.showNotification('Block added successfully!', 'success');
            } else {
                console.warn('Template not found for block type:', blockType);
                this.showNotification('Block template not found!', 'error');
            }
        },
        
        // Edit block
        editBlock: function($block) {
            const blockType = $block.data('block-type');
            this.showBlockProperties($block);
            
            // Make editable elements contenteditable
            $block.find('.editable').attr('contenteditable', 'true').focus();
        },
        
        // Delete block
        deleteBlock: function($block) {
            if (confirm('Are you sure you want to delete this block?')) {
                $block.remove();
                $('#block-properties').html('<p class="text-gray-500 text-sm">Select a block to edit its properties</p>');
                this.showNotification('Block deleted successfully!', 'success');
                
                // Show empty state if no blocks left
                if ($('#page-canvas .block').length === 0) {
                    $('#page-canvas').html(`
                        <div class="text-center py-20 text-gray-500">
                            <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <p>Drag blocks here to start building your page</p>
                        </div>
                    `);
                }
            }
        },
        
        // Show block properties
        showBlockProperties: function($block) {
            const blockType = $block.data('block-type');
            const blockData = $block.data('block-data') || {};
            
            let propertiesHtml = `<h4 class="font-semibold text-gray-900 mb-4">${blockType.charAt(0).toUpperCase() + blockType.slice(1)} Block</h4>`;
            
            // Get editable elements
            const $editables = $block.find('.editable');
            
            if ($editables.length > 0) {
                propertiesHtml += '<div class="space-y-4">';
                
                $editables.each(function() {
                    const $element = $(this);
                    const property = $element.data('property');
                    const value = $element.text();
                    const label = property.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    
                    propertiesHtml += `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">${label}</label>
                            <input type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent property-input" 
                                   data-property="${property}" 
                                   value="${value}">
                        </div>
                    `;
                });
                
                propertiesHtml += '</div>';
            } else {
                propertiesHtml += '<p class="text-gray-500 text-sm">No editable properties available for this block.</p>';
            }
            
            $('#block-properties').html(propertiesHtml);
            
            // Bind property input events
            $('.property-input').on('input', (e) => {
                const property = $(e.target).data('property');
                const value = $(e.target).val();
                
                // Update the block element
                $block.find(`[data-property="${property}"]`).text(value);
                
                // Update block data
                if (!$block.data('block-data')) {
                    $block.data('block-data', {});
                }
                const blockData = $block.data('block-data');
                blockData[property] = value;
                $block.data('block-data', blockData);
            });
        },
        
        // Load page layout
        loadPageLayout: function() {
            $.ajax({
                url: astraSiteBuilder.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'astra_ai_get_page_layout',
                    page: this.currentPage,
                    nonce: astraSiteBuilder.nonce
                },
                success: (response) => {
                    if (response.success && response.data.layout) {
                        $('#page-canvas').html(response.data.layout);
                    } else {
                        // Show empty state
                        $('#page-canvas').html(`
                            <div class="text-center py-20 text-gray-500">
                                <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <p>Drag blocks here to start building your page</p>
                            </div>
                        `);
                    }
                },
                error: () => {
                    this.showNotification('Error loading page layout!', 'error');
                }
            });
        },
        
        // Save page layout
        savePageLayout: function() {
            const layout = $('#page-canvas').html();
            
            $.ajax({
                url: astraSiteBuilder.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'astra_ai_save_page_layout',
                    page: this.currentPage,
                    layout: layout,
                    nonce: astraSiteBuilder.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showNotification(response.data.message, 'success');
                    } else {
                        this.showNotification('Error saving page layout!', 'error');
                    }
                },
                error: () => {
                    this.showNotification('Error saving page layout!', 'error');
                }
            });
        },
        
        // Preview page
        previewPage: function() {
            const previewUrl = `${window.location.origin}/#${this.currentPage}`;
            window.open(previewUrl, '_blank');
        },
        
        // Show notification
        showNotification: function(message, type = 'info') {
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            
            const notification = $(`
                <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 notification">
                    ${message}
                </div>
            `);
            
            $('body').append(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.fadeOut(() => {
                    notification.remove();
                });
            }, 3000);
        }
    };
    
    // Initialize Site Builder
    if ($('#astra-page-builder').length > 0) {
        AstraSiteBuilder.init();
    }
});