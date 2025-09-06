/**
 * Performance optimization utilities
 */

class PerformanceOptimizer {
    constructor() {
        this.init();
    }

    init() {
        this.lazyLoadImages();
        this.optimizeDataTables();
        this.implementVirtualScrolling();
        this.cacheAjaxRequests();
        this.debounceEvents();
    }

    /**
     * Implement lazy loading for images
     */
    lazyLoadImages() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for older browsers
            document.querySelectorAll('img[data-src]').forEach(img => {
                img.src = img.dataset.src;
            });
        }
    }

    /**
     * Optimize DataTables performance
     */
    optimizeDataTables() {
        // Default optimized settings for DataTables
        $.extend($.fn.dataTable.defaults, {
            "processing": true,
            "serverSide": true,
            "deferRender": true,
            "scroller": true,
            "scrollY": "400px",
            "scrollCollapse": true,
            "stateSave": true,
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            "language": {
                "url": "/js/datatables-arabic.json"
            },
            "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                   '<"row"<"col-sm-12"tr>>' +
                   '<"row"<"col-sm-5"i><"col-sm-7"p>>',
        });

        // Optimize existing DataTables
        $('.dataTable').each(function() {
            if ($.fn.DataTable.isDataTable(this)) {
                $(this).DataTable().draw('page');
            }
        });
    }

    /**
     * Implement virtual scrolling for large lists
     */
    implementVirtualScrolling() {
        const virtualLists = document.querySelectorAll('.virtual-list');
        
        virtualLists.forEach(list => {
            const items = Array.from(list.children);
            const itemHeight = 50; // Adjust based on your item height
            const containerHeight = list.clientHeight;
            const visibleItems = Math.ceil(containerHeight / itemHeight) + 2;
            
            let scrollTop = 0;
            
            const updateVisibleItems = () => {
                const startIndex = Math.floor(scrollTop / itemHeight);
                const endIndex = Math.min(startIndex + visibleItems, items.length);
                
                items.forEach((item, index) => {
                    if (index >= startIndex && index < endIndex) {
                        item.style.display = 'block';
                        item.style.transform = `translateY(${index * itemHeight}px)`;
                    } else {
                        item.style.display = 'none';
                    }
                });
            };
            
            list.addEventListener('scroll', this.throttle(() => {
                scrollTop = list.scrollTop;
                updateVisibleItems();
            }, 16)); // 60fps
            
            updateVisibleItems();
        });
    }

    /**
     * Cache AJAX requests
     */
    cacheAjaxRequests() {
        const cache = new Map();
        const originalAjax = $.ajax;
        
        $.ajax = function(options) {
            if (options.cache !== false && options.type === 'GET') {
                const cacheKey = options.url + JSON.stringify(options.data || {});
                
                if (cache.has(cacheKey)) {
                    const cachedData = cache.get(cacheKey);
                    if (Date.now() - cachedData.timestamp < 300000) { // 5 minutes
                        return $.Deferred().resolve(cachedData.data).promise();
                    }
                }
                
                return originalAjax.call(this, options).done(function(data) {
                    cache.set(cacheKey, {
                        data: data,
                        timestamp: Date.now()
                    });
                });
            }
            
            return originalAjax.call(this, options);
        };
    }

    /**
     * Debounce events for better performance
     */
    debounceEvents() {
        // Debounce search inputs
        $('input[type="search"], .search-input').on('input', this.debounce(function() {
            // Trigger search
            $(this).trigger('search');
        }, 300));

        // Debounce window resize
        $(window).on('resize', this.debounce(function() {
            // Trigger resize handlers
            $(window).trigger('optimized-resize');
        }, 250));

        // Throttle scroll events
        $(window).on('scroll', this.throttle(function() {
            // Trigger scroll handlers
            $(window).trigger('optimized-scroll');
        }, 16));
    }

    /**
     * Debounce function
     */
    debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * Throttle function
     */
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }

    /**
     * Preload critical resources
     */
    preloadResources() {
        const criticalResources = [
            '/css/app.css',
            '/js/app.js',
            '/js/vendor.js'
        ];

        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = resource;
            link.as = resource.endsWith('.css') ? 'style' : 'script';
            document.head.appendChild(link);
        });
    }

    /**
     * Implement service worker for caching
     */
    registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('Service Worker registered successfully:', registration);
                })
                .catch(error => {
                    console.log('Service Worker registration failed:', error);
                });
        }
    }

    /**
     * Monitor performance
     */
    monitorPerformance() {
        if ('performance' in window) {
            // Monitor page load time
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const perfData = performance.timing;
                    const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
                    
                    // Send performance data to server
                    if (pageLoadTime > 3000) { // If load time > 3 seconds
                        $.ajax({
                            url: '/api/performance',
                            method: 'POST',
                            data: {
                                type: 'page_load',
                                duration: pageLoadTime,
                                url: window.location.href
                            }
                        });
                    }
                }, 0);
            });

            // Monitor long tasks
            if ('PerformanceObserver' in window) {
                const observer = new PerformanceObserver((list) => {
                    for (const entry of list.getEntries()) {
                        if (entry.duration > 50) { // Long task > 50ms
                            $.ajax({
                                url: '/api/performance',
                                method: 'POST',
                                data: {
                                    type: 'long_task',
                                    duration: entry.duration,
                                    url: window.location.href
                                }
                            });
                        }
                    }
                });
                observer.observe({entryTypes: ['longtask']});
            }
        }
    }
}

// Initialize performance optimizer when DOM is ready
$(document).ready(function() {
    const optimizer = new PerformanceOptimizer();
    optimizer.preloadResources();
    optimizer.registerServiceWorker();
    optimizer.monitorPerformance();
});

// Export for use in other modules
window.PerformanceOptimizer = PerformanceOptimizer;