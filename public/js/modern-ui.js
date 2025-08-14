/**
 * Modern UI JavaScript Library
 * حل مشاكل JavaScript وتحسين تجربة المستخدم
 */

(function() {
    'use strict';

    // ===== GLOBAL CONFIGURATION =====
    const CONFIG = {
        animationDuration: 300,
        debounceDelay: 250,
        throttleDelay: 100,
        breakpoints: {
            mobile: 768,
            tablet: 1024,
            desktop: 1200
        }
    };

    // ===== UTILITY FUNCTIONS =====
    const Utils = {
        // Debounce function for performance optimization
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        // Throttle function for scroll events
        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },

        // Check if element is in viewport
        isInViewport: function(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        // Smooth scroll to element
        scrollToElement: function(element, offset = 0) {
            const elementPosition = element.offsetTop - offset;
            window.scrollTo({
                top: elementPosition,
                behavior: 'smooth'
            });
        },

        // Format numbers with commas
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        // Format currency
        formatCurrency: function(amount, currency = 'SAR') {
            return new Intl.NumberFormat('ar-SA', {
                style: 'currency',
                currency: currency
            }).format(amount);
        },

        // Get device type
        getDeviceType: function() {
            const width = window.innerWidth;
            if (width < CONFIG.breakpoints.mobile) return 'mobile';
            if (width < CONFIG.breakpoints.tablet) return 'tablet';
            return 'desktop';
        },

        // Generate unique ID
        generateId: function() {
            return Date.now().toString(36) + Math.random().toString(36).substr(2);
        }
    };

    // ===== DOM MANIPULATION HELPERS =====
    const DOM = {
        // Create element with attributes
        createElement: function(tag, attributes = {}, children = []) {
            const element = document.createElement(tag);
            
            // Set attributes
            Object.keys(attributes).forEach(key => {
                if (key === 'className') {
                    element.className = attributes[key];
                } else if (key === 'textContent') {
                    element.textContent = attributes[key];
                } else {
                    element.setAttribute(key, attributes[key]);
                }
            });

            // Add children
            children.forEach(child => {
                if (typeof child === 'string') {
                    element.appendChild(document.createTextNode(child));
                } else {
                    element.appendChild(child);
                }
            });

            return element;
        },

        // Add event listener with options
        addEvent: function(element, event, handler, options = {}) {
            element.addEventListener(event, handler, options);
        },

        // Remove event listener
        removeEvent: function(element, event, handler) {
            element.removeEventListener(event, handler);
        },

        // Toggle class with animation
        toggleClass: function(element, className, animate = true) {
            if (animate) {
                element.style.transition = `all ${CONFIG.animationDuration}ms ease-in-out`;
            }
            element.classList.toggle(className);
        },

        // Show/hide elements with animation
        showElement: function(element, animate = true) {
            if (animate) {
                element.style.transition = `opacity ${CONFIG.animationDuration}ms ease-in-out`;
            }
            element.style.display = 'block';
            element.style.opacity = '1';
        },

        hideElement: function(element, animate = true) {
            if (animate) {
                element.style.transition = `opacity ${CONFIG.animationDuration}ms ease-in-out`;
            }
            element.style.opacity = '0';
            setTimeout(() => {
                element.style.display = 'none';
            }, animate ? CONFIG.animationDuration : 0);
        }
    };

    // ===== FORM VALIDATION =====
    const FormValidator = {
        // Validate email format
        isValidEmail: function(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        // Validate phone number
        isValidPhone: function(phone) {
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
            return phoneRegex.test(phone);
        },

        // Validate required fields
        validateRequired: function(value) {
            return value && value.trim().length > 0;
        },

        // Show validation error
        showError: function(element, message) {
            const errorElement = element.parentNode.querySelector('.form-text.text-danger') || 
                               DOM.createElement('div', { className: 'form-text text-danger' });
            errorElement.textContent = message;
            
            if (!element.parentNode.querySelector('.form-text.text-danger')) {
                element.parentNode.appendChild(errorElement);
            }
            
            element.classList.add('is-invalid');
            element.classList.remove('is-valid');
        },

        // Show validation success
        showSuccess: function(element) {
            const errorElement = element.parentNode.querySelector('.form-text.text-danger');
            if (errorElement) {
                errorElement.remove();
            }
            
            element.classList.remove('is-invalid');
            element.classList.add('is-valid');
        },

        // Validate form
        validateForm: function(form) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!this.validateRequired(field.value)) {
                    this.showError(field, 'هذا الحقل مطلوب');
                    isValid = false;
                } else {
                    this.showSuccess(field);
                }
            });

            return isValid;
        }
    };

    // ===== DATA TABLE ENHANCEMENTS =====
    const DataTable = {
        // Initialize DataTable with modern styling
        init: function(selector, options = {}) {
            const table = document.querySelector(selector);
            if (!table) return;

            const defaultOptions = {
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
                },
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                initComplete: function() {
                    // Add custom styling
                    this.api().columns().every(function() {
                        const column = this;
                        const header = column.header();
                        
                        // Add search input for each column
                        if (header.textContent !== 'الإجراءات') {
                            const input = DOM.createElement('input', {
                                type: 'text',
                                className: 'form-control form-control-sm',
                                placeholder: 'بحث...'
                            });
                            
                            input.addEventListener('keyup', Utils.debounce(function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            }, CONFIG.debounceDelay));
                            
                            header.appendChild(input);
                        }
                    });
                }
            };

            const finalOptions = { ...defaultOptions, ...options };
            
            // Initialize DataTable
            if (typeof $.fn.DataTable !== 'undefined') {
                return $(table).DataTable(finalOptions);
            }
        },

        // Refresh table data
        refresh: function(tableSelector) {
            const table = $(tableSelector);
            if (table.length && table.DataTable) {
                table.DataTable().ajax.reload();
            }
        }
    };

    // ===== MODAL SYSTEM =====
    const Modal = {
        // Create and show modal
        show: function(options = {}) {
            const {
                title = 'تنبيه',
                content = '',
                type = 'info',
                size = 'md',
                showCloseButton = true,
                showConfirmButton = true,
                confirmText = 'تأكيد',
                cancelText = 'إلغاء',
                onConfirm = null,
                onCancel = null
            } = options;

            // Remove existing modals
            this.hideAll();

            // Create modal HTML
            const modal = DOM.createElement('div', {
                className: 'modal fade show d-block',
                id: 'dynamicModal'
            });

            const modalDialog = DOM.createElement('div', {
                className: `modal-dialog modal-${size}`
            });

            const modalContent = DOM.createElement('div', {
                className: 'modal-content'
            });

            // Modal header
            const modalHeader = DOM.createElement('div', {
                className: 'modal-header'
            });

            const modalTitle = DOM.createElement('h5', {
                className: 'modal-title',
                textContent: title
            });

            if (showCloseButton) {
                const closeButton = DOM.createElement('button', {
                    type: 'button',
                    className: 'btn-close',
                    'data-bs-dismiss': 'modal'
                });
                modalHeader.appendChild(closeButton);
            }

            modalHeader.appendChild(modalTitle);

            // Modal body
            const modalBody = DOM.createElement('div', {
                className: 'modal-body',
                textContent: content
            });

            // Modal footer
            const modalFooter = DOM.createElement('div', {
                className: 'modal-footer'
            });

            if (onCancel) {
                const cancelButton = DOM.createElement('button', {
                    type: 'button',
                    className: 'btn btn-secondary',
                    textContent: cancelText
                });
                cancelButton.addEventListener('click', () => {
                    this.hide('dynamicModal');
                    if (onCancel) onCancel();
                });
                modalFooter.appendChild(cancelButton);
            }

            if (showConfirmButton) {
                const confirmButton = DOM.createElement('button', {
                    type: 'button',
                    className: `btn btn-${type}`,
                    textContent: confirmText
                });
                confirmButton.addEventListener('click', () => {
                    this.hide('dynamicModal');
                    if (onConfirm) onConfirm();
                });
                modalFooter.appendChild(confirmButton);
            }

            // Assemble modal
            modalContent.appendChild(modalHeader);
            modalContent.appendChild(modalBody);
            if (modalFooter.children.length > 0) {
                modalContent.appendChild(modalFooter);
            }

            modalDialog.appendChild(modalContent);
            modal.appendChild(modalDialog);

            // Add backdrop
            const backdrop = DOM.createElement('div', {
                className: 'modal-backdrop fade show'
            });

            // Add to DOM
            document.body.appendChild(modal);
            document.body.appendChild(backdrop);

            // Add event listeners
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.hide('dynamicModal');
                }
            });

            // Show modal
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        },

        // Hide specific modal
        hide: function(modalId) {
            const modal = document.getElementById(modalId);
            const backdrop = document.querySelector('.modal-backdrop');
            
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => modal.remove(), CONFIG.animationDuration);
            }
            
            if (backdrop) {
                backdrop.classList.remove('show');
                setTimeout(() => backdrop.remove(), CONFIG.animationDuration);
            }
        },

        // Hide all modals
        hideAll: function() {
            const modals = document.querySelectorAll('.modal');
            const backdrops = document.querySelectorAll('.modal-backdrop');
            
            modals.forEach(modal => modal.remove());
            backdrops.forEach(backdrop => backdrop.remove());
        }
    };

    // ===== NOTIFICATION SYSTEM =====
    const Notification = {
        // Show notification
        show: function(message, type = 'info', duration = 5000) {
            const notification = DOM.createElement('div', {
                className: `alert alert-${type} alert-dismissible fade show position-fixed`,
                style: 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;'
            });

            const closeButton = DOM.createElement('button', {
                type: 'button',
                className: 'btn-close',
                'data-bs-dismiss': 'alert'
            });

            notification.textContent = message;
            notification.appendChild(closeButton);

            document.body.appendChild(notification);

            // Auto remove after duration
            if (duration > 0) {
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, duration);
            }

            return notification;
        },

        // Show success notification
        success: function(message, duration) {
            return this.show(message, 'success', duration);
        },

        // Show error notification
        error: function(message, duration) {
            return this.show(message, 'danger', duration);
        },

        // Show warning notification
        warning: function(message, duration) {
            return this.show(message, 'warning', duration);
        }
    };

    // ===== AJAX HELPER =====
    const Ajax = {
        // Make AJAX request
        request: function(options) {
            const {
                url,
                method = 'GET',
                data = null,
                headers = {},
                onSuccess = null,
                onError = null,
                onComplete = null
            } = options;

            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const xhr = new XMLHttpRequest();
            
            xhr.open(method, url, true);
            
            // Set headers
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            if (token) {
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
            
            Object.keys(headers).forEach(key => {
                xhr.setRequestHeader(key, headers[key]);
            });

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (onSuccess) onSuccess(response, xhr);
                    } catch (e) {
                        if (onSuccess) onSuccess(xhr.responseText, xhr);
                    }
                } else {
                    if (onError) onError(xhr);
                }
                
                if (onComplete) onComplete(xhr);
            };

            xhr.onerror = function() {
                if (onError) onError(xhr);
                if (onComplete) onComplete(xhr);
            };

            if (data) {
                xhr.send(JSON.stringify(data));
            } else {
                xhr.send();
            }

            return xhr;
        },

        // GET request
        get: function(url, onSuccess, onError) {
            return this.request({
                url: url,
                method: 'GET',
                onSuccess: onSuccess,
                onError: onError
            });
        },

        // POST request
        post: function(url, data, onSuccess, onError) {
            return this.request({
                url: url,
                method: 'POST',
                data: data,
                onSuccess: onSuccess,
                onError: onError
            });
        }
    };

    // ===== SIDEBAR TOGGLE =====
    const Sidebar = {
        // Toggle sidebar
        toggle: function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (sidebar && mainContent) {
                sidebar.classList.toggle('show');
                
                if (sidebar.classList.contains('show')) {
                    mainContent.style.marginRight = '0';
                } else {
                    mainContent.style.marginRight = '280px';
                }
            }
        },

        // Close sidebar on mobile
        closeOnMobile: function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (Utils.getDeviceType() === 'mobile' && sidebar && mainContent) {
                sidebar.classList.remove('show');
                mainContent.style.marginRight = '0';
            }
        }
    };

    // ===== INITIALIZATION =====
    const init = function() {
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            initializeApp();
        }
    };

    const initializeApp = function() {
        // Initialize DataTables
        document.querySelectorAll('.data-table').forEach(table => {
            DataTable.init('#' + table.id);
        });

        // Initialize form validation
        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!FormValidator.validateForm(this)) {
                    e.preventDefault();
                    Notification.error('يرجى تصحيح الأخطاء في النموذج');
                }
            });
        });

        // Initialize sidebar toggle
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', Sidebar.toggle);
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            if (Utils.getDeviceType() === 'mobile' && 
                !e.target.closest('.sidebar') && 
                !e.target.closest('.sidebar-toggle')) {
                Sidebar.closeOnMobile();
            }
        });

        // Add smooth scrolling to anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    Utils.scrollToElement(target, 80);
                }
            });
        });

        // Add loading states to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري الإرسال...';
                }
            });
        });

        // Initialize tooltips
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Add fade-in animation to cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card, .stat-card').forEach(card => {
            observer.observe(card);
        });

        // Console log for debugging
        console.log('Modern UI initialized successfully!');
    };

    // ===== EXPOSE TO GLOBAL SCOPE =====
    window.ModernUI = {
        Utils,
        DOM,
        FormValidator,
        DataTable,
        Modal,
        Notification,
        Ajax,
        Sidebar,
        init
    };

    // Auto-initialize
    init();

})();