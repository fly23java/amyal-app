/**
 * User Experience Enhancements
 * تحسينات تجربة المستخدم
 */

(function() {
    'use strict';

    // ===== USER EXPERIENCE CONFIGURATION =====
    const UXConfig = {
        animationDuration: 300,
        debounceDelay: 250,
        notificationDuration: 5000,
        loadingTimeout: 10000,
        autoSaveInterval: 30000,
        maxRetries: 3
    };

    // ===== FORM ENHANCEMENTS =====
    const FormEnhancer = {
        // Auto-save form data
        initAutoSave: function() {
            const forms = document.querySelectorAll('form[data-autosave]');
            
            forms.forEach(form => {
                const formId = form.id || `form-${Math.random().toString(36).substr(2, 9)}`;
                form.id = formId;
                
                // Load saved data
                this.loadSavedData(form);
                
                // Save data on input change
                form.addEventListener('input', Utils.debounce(() => {
                    this.saveFormData(form);
                }, UXConfig.autoSaveInterval));
                
                // Save data before form submission
                form.addEventListener('submit', () => {
                    this.clearSavedData(form);
                });
            });
        },

        // Save form data to localStorage
        saveFormData: function(form) {
            const formData = new FormData(form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            localStorage.setItem(`form_${form.id}`, JSON.stringify(data));
            
            // Show auto-save indicator
            this.showAutoSaveIndicator(form, true);
        },

        // Load saved form data
        loadSavedData: function(form) {
            const savedData = localStorage.getItem(`form_${form.id}`);
            
            if (savedData) {
                try {
                    const data = JSON.parse(savedData);
                    
                    Object.keys(data).forEach(key => {
                        const field = form.querySelector(`[name="${key}"]`);
                        if (field && field.type !== 'file') {
                            field.value = data[key];
                        }
                    });
                    
                    // Show restored data indicator
                    this.showAutoSaveIndicator(form, false, true);
                } catch (e) {
                    console.error('Error loading saved form data:', e);
                }
            }
        },

        // Clear saved form data
        clearSavedData: function(form) {
            localStorage.removeItem(`form_${form.id}`);
        },

        // Show auto-save indicator
        showAutoSaveIndicator: function(form, isSaving = false, isRestored = false) {
            let indicator = form.querySelector('.auto-save-indicator');
            
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.className = 'auto-save-indicator';
                indicator.style.cssText = `
                    position: absolute;
                    top: -25px;
                    right: 0;
                    font-size: 0.8rem;
                    color: #6b7280;
                    transition: opacity 0.3s ease;
                `;
                form.style.position = 'relative';
                form.appendChild(indicator);
            }
            
            if (isSaving) {
                indicator.textContent = 'جاري الحفظ...';
                indicator.style.color = '#059669';
            } else if (isRestored) {
                indicator.textContent = 'تم استعادة البيانات المحفوظة';
                indicator.style.color = '#059669';
                setTimeout(() => {
                    indicator.style.opacity = '0';
                }, 3000);
            } else {
                indicator.textContent = 'تم الحفظ';
                indicator.style.color = '#059669';
                setTimeout(() => {
                    indicator.style.opacity = '0';
                }, 2000);
            }
        },

        // Enhance form validation
        enhanceValidation: function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                // Real-time validation
                const inputs = form.querySelectorAll('input, select, textarea');
                
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        this.validateField(input);
                    });
                    
                    input.addEventListener('input', () => {
                        this.clearFieldError(input);
                    });
                });
                
                // Form submission enhancement
                form.addEventListener('submit', (e) => {
                    if (!this.validateForm(form)) {
                        e.preventDefault();
                        this.showFormErrors(form);
                    }
                });
            });
        },

        // Validate single field
        validateField: function(field) {
            const value = field.value.trim();
            let isValid = true;
            let errorMessage = '';

            // Required validation
            if (field.hasAttribute('required') && !value) {
                isValid = false;
                errorMessage = 'هذا الحقل مطلوب';
            }

            // Email validation
            if (field.type === 'email' && value && !this.isValidEmail(value)) {
                isValid = false;
                errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
            }

            // Phone validation
            if (field.hasAttribute('data-phone') && value && !this.isValidPhone(value)) {
                isValid = false;
                errorMessage = 'يرجى إدخال رقم هاتف صحيح';
            }

            // Length validation
            if (field.hasAttribute('minlength') && value.length < field.getAttribute('minlength')) {
                isValid = false;
                errorMessage = `يجب أن يكون الطول على الأقل ${field.getAttribute('minlength')} أحرف`;
            }

            if (field.hasAttribute('maxlength') && value.length > field.getAttribute('maxlength')) {
                isValid = false;
                errorMessage = `يجب أن يكون الطول على الأكثر ${field.getAttribute('maxlength')} أحرف`;
            }

            if (!isValid) {
                this.showFieldError(field, errorMessage);
            }

            return isValid;
        },

        // Validate entire form
        validateForm: function(form) {
            const fields = form.querySelectorAll('input, select, textarea');
            let isValid = true;

            fields.forEach(field => {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            });

            return isValid;
        },

        // Show field error
        showFieldError: function(field, message) {
            this.clearFieldError(field);
            
            field.classList.add('is-invalid');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            
            field.parentNode.appendChild(errorDiv);
        },

        // Clear field error
        clearFieldError: function(field) {
            field.classList.remove('is-invalid');
            
            const errorDiv = field.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) {
                errorDiv.remove();
            }
        },

        // Show form errors summary
        showFormErrors: function(form) {
            const errors = form.querySelectorAll('.invalid-feedback');
            
            if (errors.length > 0) {
                const message = `يرجى تصحيح ${errors.length} خطأ في النموذج`;
                if (window.ModernUI && window.ModernUI.Notification) {
                    window.ModernUI.Notification.error(message);
                } else {
                    alert(message);
                }
            }
        },

        // Utility functions
        isValidEmail: function(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        isValidPhone: function(phone) {
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
            return phoneRegex.test(phone);
        }
    };

    // ===== NAVIGATION ENHANCEMENTS =====
    const NavigationEnhancer = {
        // Initialize navigation enhancements
        init: function() {
            this.enhanceBreadcrumbs();
            this.enhanceSidebar();
            this.enhanceTabs();
            this.addKeyboardShortcuts();
        },

        // Enhance breadcrumbs
        enhanceBreadcrumbs: function() {
            const breadcrumbs = document.querySelectorAll('.breadcrumb');
            
            breadcrumbs.forEach(breadcrumb => {
                const items = breadcrumb.querySelectorAll('.breadcrumb-item');
                
                items.forEach((item, index) => {
                    if (index === items.length - 1) {
                        item.classList.add('active');
                    }
                    
                    // Add click tracking
                    item.addEventListener('click', () => {
                        this.trackNavigation('breadcrumb', item.textContent);
                    });
                });
            });
        },

        // Enhance sidebar navigation
        enhanceSidebar: function() {
            const sidebar = document.querySelector('.main-menu');
            const menuItems = sidebar.querySelectorAll('.navigation-main .nav-item');
            
            menuItems.forEach(item => {
                const link = item.querySelector('a');
                
                if (link) {
                    // Add hover effects
                    item.addEventListener('mouseenter', () => {
                        item.classList.add('hover');
                    });
                    
                    item.addEventListener('mouseleave', () => {
                        item.classList.remove('hover');
                    });
                    
                    // Add click tracking
                    link.addEventListener('click', () => {
                        this.trackNavigation('sidebar', link.textContent);
                    });
                }
            });
        },

        // Enhance tabs
        enhanceTabs: function() {
            const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    this.trackNavigation('tab', tab.textContent);
                });
            });
        },

        // Add keyboard shortcuts
        addKeyboardShortcuts: function() {
            document.addEventListener('keydown', (e) => {
                // Alt + 1: Dashboard
                if (e.altKey && e.key === '1') {
                    e.preventDefault();
                    this.navigateTo('/dashboard');
                }
                
                // Alt + 2: Shipments
                if (e.altKey && e.key === '2') {
                    e.preventDefault();
                    this.navigateTo('/shipments');
                }
                
                // Alt + 3: Drivers
                if (e.altKey && e.key === '3') {
                    e.preventDefault();
                    this.navigateTo('/drivers');
                }
                
                // Alt + 4: Vehicles
                if (e.altKey && e.key === '4') {
                    e.preventDefault();
                    this.navigateTo('/vehicles');
                }
                
                // Alt + 5: Users
                if (e.altKey && e.key === '5') {
                    e.preventDefault();
                    this.navigateTo('/users');
                }
                
                // Alt + 6: Reports
                if (e.altKey && e.key === '6') {
                    e.preventDefault();
                    this.navigateTo('/reports');
                }
            });
        },

        // Navigate to URL
        navigateTo: function(url) {
            if (window.location.pathname !== url) {
                window.location.href = url;
            }
        },

        // Track navigation
        trackNavigation: function(type, item) {
            // You can integrate with analytics here
            console.log(`Navigation: ${type} - ${item}`);
        }
    };

    // ===== CONTENT ENHANCEMENTS =====
    const ContentEnhancer = {
        // Initialize content enhancements
        init: function() {
            this.enhanceTables();
            this.enhanceCards();
            this.enhanceButtons();
            this.addLoadingStates();
            this.enhanceImages();
        },

        // Enhance tables
        enhanceTables: function() {
            const tables = document.querySelectorAll('table');
            
            tables.forEach(table => {
                // Add hover effects
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    row.addEventListener('mouseenter', () => {
                        row.classList.add('table-hover');
                    });
                    
                    row.addEventListener('mouseleave', () => {
                        row.classList.remove('table-hover');
                    });
                });
                
                // Add sort indicators
                const headers = table.querySelectorAll('th[data-sortable]');
                
                headers.forEach(header => {
                    header.addEventListener('click', () => {
                        this.sortTable(table, header);
                    });
                });
            });
        },

        // Enhance cards
        enhanceCards: function() {
            const cards = document.querySelectorAll('.card');
            
            cards.forEach(card => {
                // Add hover effects
                card.addEventListener('mouseenter', () => {
                    card.classList.add('card-hover');
                });
                
                card.addEventListener('mouseleave', () => {
                    card.classList.remove('card-hover');
                });
                
                // Add click tracking
                card.addEventListener('click', () => {
                    this.trackCardClick(card);
                });
            });
        },

        // Enhance buttons
        enhanceButtons: function() {
            const buttons = document.querySelectorAll('.btn');
            
            buttons.forEach(button => {
                // Add loading states
                if (button.type === 'submit') {
                    button.addEventListener('click', () => {
                        this.showButtonLoading(button);
                    });
                }
                
                // Add ripple effect
                button.addEventListener('click', (e) => {
                    this.createRippleEffect(e, button);
                });
            });
        },

        // Add loading states
        addLoadingStates: function() {
            // Add loading skeleton for content
            const contentAreas = document.querySelectorAll('.content-body');
            
            contentAreas.forEach(area => {
                if (area.children.length === 0) {
                    this.showLoadingSkeleton(area);
                }
            });
        },

        // Enhance images
        enhanceImages: function() {
            const images = document.querySelectorAll('img');
            
            images.forEach(img => {
                // Add lazy loading
                if (!img.hasAttribute('loading')) {
                    img.setAttribute('loading', 'lazy');
                }
                
                // Add error handling
                img.addEventListener('error', () => {
                    this.handleImageError(img);
                });
                
                // Add loading indicator
                if (!img.complete) {
                    this.showImageLoading(img);
                }
            });
        },

        // Sort table
        sortTable: function(table, header) {
            const column = Array.from(header.parentNode.children).indexOf(header);
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Toggle sort direction
            const isAscending = header.classList.contains('sort-asc');
            
            // Clear previous sort indicators
            header.parentNode.querySelectorAll('th').forEach(th => {
                th.classList.remove('sort-asc', 'sort-desc');
            });
            
            // Add sort indicator
            header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
            
            // Sort rows
            rows.sort((a, b) => {
                const aValue = a.children[column].textContent.trim();
                const bValue = b.children[column].textContent.trim();
                
                if (isAscending) {
                    return bValue.localeCompare(aValue, 'ar');
                } else {
                    return aValue.localeCompare(bValue, 'ar');
                }
            });
            
            // Reorder rows
            rows.forEach(row => tbody.appendChild(row));
        },

        // Show button loading
        showButtonLoading: function(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري المعالجة...';
            button.disabled = true;
            
            // Restore button after 3 seconds (or when form is submitted)
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        },

        // Create ripple effect
        createRippleEffect: function(event, button) {
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            button.style.position = 'relative';
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        },

        // Show loading skeleton
        showLoadingSkeleton: function(container) {
            container.innerHTML = `
                <div class="loading-skeleton">
                    <div class="skeleton-card"></div>
                    <div class="skeleton-text long"></div>
                    <div class="skeleton-text medium"></div>
                    <div class="skeleton-text short"></div>
                </div>
            `;
        },

        // Handle image error
        handleImageError: function(img) {
            img.src = '/images/placeholder.png';
            img.alt = 'صورة غير متوفرة';
            img.classList.add('img-error');
        },

        // Show image loading
        showImageLoading: function(img) {
            const wrapper = document.createElement('div');
            wrapper.className = 'image-loading-wrapper';
            wrapper.style.cssText = `
                position: relative;
                display: inline-block;
            `;
            
            const loading = document.createElement('div');
            loading.className = 'image-loading';
            loading.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            loading.style.cssText = `
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: #6b7280;
                font-size: 1.5rem;
            `;
            
            img.parentNode.insertBefore(wrapper, img);
            wrapper.appendChild(img);
            wrapper.appendChild(loading);
            
            img.addEventListener('load', () => {
                loading.remove();
            });
        },

        // Track card click
        trackCardClick: function(card) {
            const title = card.querySelector('.card-title')?.textContent || 'Unknown Card';
            console.log(`Card clicked: ${title}`);
        }
    };

    // ===== PERFORMANCE ENHANCEMENTS =====
    const PerformanceEnhancer = {
        // Initialize performance enhancements
        init: function() {
            this.optimizeImages();
            this.addIntersectionObserver();
            this.optimizeScrollEvents();
            this.addServiceWorker();
        },

        // Optimize images
        optimizeImages: function() {
            const images = document.querySelectorAll('img[data-src]');
            
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
            
            images.forEach(img => imageObserver.observe(img));
        },

        // Add intersection observer for animations
        addIntersectionObserver: function() {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, { threshold: 0.1 });
            
            animatedElements.forEach(el => animationObserver.observe(el));
        },

        // Optimize scroll events
        optimizeScrollEvents: function() {
            let ticking = false;
            
            function updateScroll() {
                // Handle scroll-based animations
                ticking = false;
            }
            
            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateScroll);
                    ticking = true;
                }
            }
            
            window.addEventListener('scroll', requestTick, { passive: true });
        },

        // Add service worker for caching
        addServiceWorker: function() {
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(registration => {
                            console.log('ServiceWorker registered:', registration);
                        })
                        .catch(error => {
                            console.log('ServiceWorker registration failed:', error);
                        });
                });
            }
        }
    };

    // ===== UTILITY FUNCTIONS =====
    const Utils = {
        // Debounce function
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

        // Throttle function
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

        // Format date
        formatDate: function(date) {
            return new Intl.DateTimeFormat('ar-SA', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(new Date(date));
        },

        // Format number
        formatNumber: function(num) {
            return new Intl.NumberFormat('ar-SA').format(num);
        },

        // Copy to clipboard
        copyToClipboard: function(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    this.showToast('تم نسخ النص إلى الحافظة', 'success');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                this.showToast('تم نسخ النص إلى الحافظة', 'success');
            }
        },

        // Show toast notification
        showToast: function(message, type = 'info') {
            if (window.ModernUI && window.ModernUI.Notification) {
                window.ModernUI.Notification.show(message, type);
            } else {
                // Fallback toast
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.textContent = message;
                toast.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                    color: white;
                    padding: 1rem;
                    border-radius: 0.5rem;
                    z-index: 9999;
                    animation: slideInRight 0.3s ease-out;
                `;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        }
    };

    // ===== INITIALIZATION =====
    const init = function() {
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            initializeUX();
        }
    };

    const initializeUX = function() {
        // Initialize all enhancements
        FormEnhancer.initAutoSave();
        FormEnhancer.enhanceValidation();
        NavigationEnhancer.init();
        ContentEnhancer.init();
        PerformanceEnhancer.init();
        
        // Add CSS animations
        addCSSAnimations();
        
        console.log('User Experience enhancements initialized successfully!');
    };

    // Add CSS animations
    const addCSSAnimations = function() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .table-hover {
                background-color: #f3f4f6 !important;
            }
            
            .card-hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }
            
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease;
            }
            
            .animate-on-scroll.animated {
                opacity: 1;
                transform: translateY(0);
            }
            
            .sort-asc::after {
                content: ' ▲';
                color: #059669;
            }
            
            .sort-desc::after {
                content: ' ▼';
                color: #dc2626;
            }
        `;
        document.head.appendChild(style);
    };

    // ===== EXPOSE TO GLOBAL SCOPE =====
    window.UXEnhancer = {
        FormEnhancer,
        NavigationEnhancer,
        ContentEnhancer,
        PerformanceEnhancer,
        Utils,
        init
    };

    // Auto-initialize
    init();

})();