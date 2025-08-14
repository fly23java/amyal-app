/**
 * DataTables Fixes and Enhancements
 * حل مشاكل DataTables وتحسين الأداء
 */

(function() {
    'use strict';

    // ===== DATATABLES CONFIGURATION =====
    const DataTablesConfig = {
        // Default language settings for Arabic
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json',
            // Fallback Arabic translations
            fallback: {
                "sProcessing": "جاري المعالجة...",
                "sLengthMenu": "أظهر _MENU_ سجل",
                "sZeroRecords": "لم يتم العثور على سجلات",
                "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ سجل",
                "sInfoEmpty": "إظهار 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "(تمت التصفية من أصل _MAX_ سجل)",
                "sInfoPostFix": "",
                "sSearch": "بحث:",
                "sUrl": "",
                "sEmptyTable": "لا توجد بيانات متاحة في الجدول",
                "sLoadingRecords": "جاري التحميل...",
                "sInfoThousands": ",",
                "sDecimal": ".",
                "sThousands": ",",
                "sSearchBuilder": {
                    "add": "إضافة شرط",
                    "condition": "الشرط",
                    "clearAll": "مسح الكل",
                    "delete": "حذف",
                    "deleteTitle": "حذف الشرط",
                    "data": "البيانات",
                    "leftTitle": "الشرط الأيسر",
                    "logicAnd": "و",
                    "logicOr": "أو",
                    "rightTitle": "الشرط الأيمن",
                    "title": {
                        "_": "منشئ البحث (%d)",
                        "0": "منشئ البحث"
                    },
                    "value": "القيمة",
                    "conditions": {
                        "date": {
                            "after": "بعد",
                            "before": "قبل",
                            "between": "بين",
                            "empty": "فارغ",
                            "equals": "يساوي",
                            "not": "لا يساوي",
                            "notBetween": "ليس بين",
                            "notEmpty": "غير فارغ"
                        },
                        "number": {
                            "between": "بين",
                            "empty": "فارغ",
                            "equals": "يساوي",
                            "gt": "أكبر من",
                            "gte": "أكبر من أو يساوي",
                            "lt": "أقل من",
                            "lte": "أقل من أو يساوي",
                            "not": "لا يساوي",
                            "notBetween": "ليس بين",
                            "notEmpty": "غير فارغ"
                        },
                        "string": {
                            "contains": "يحتوي على",
                            "empty": "فارغ",
                            "endsWith": "ينتهي بـ",
                            "equals": "يساوي",
                            "not": "لا يساوي",
                            "notEmpty": "غير فارغ",
                            "startsWith": "يبدأ بـ"
                        }
                    }
                },
                "sSearchPanes": {
                    "clearMessage": "مسح الكل",
                    "collapse": {
                        "_": "تصغير (%d)",
                        "0": "تصغير"
                    },
                    "count": "{total}",
                    "countFiltered": "{shown} من {total}",
                    "emptyPanes": "لا توجد لوحات بحث",
                    "loadMessage": "جاري تحميل لوحات البحث...",
                    "title": {
                        "_": "لوحات البحث (%d)",
                        "0": "لوحات البحث"
                    }
                },
                "sSelect": {
                    "rows": {
                        "_": "تم تحديد %d صف",
                        "0": "انقر لتحديد صف",
                        "1": "تم تحديد صف واحد"
                    }
                },
                "sButtons": {
                    "copy": "نسخ",
                    "copySuccess": {
                        "_": "تم نسخ %d صف",
                        "1": "تم نسخ صف واحد"
                    },
                    "copyTitle": "نسخ إلى الحافظة",
                    "excel": "Excel",
                    "pdf": "PDF",
                    "print": "طباعة",
                    "colvis": "إظهار/إخفاء الأعمدة"
                },
                "oPaginate": {
                    "sFirst": "الأول",
                    "sLast": "الأخير",
                    "sNext": "التالي",
                    "sPrevious": "السابق"
                },
                "oAria": {
                    "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                    "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                }
            }
        },

        // Default options
        options: {
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "الكل"]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            order: [[0, 'desc']],
            language: null, // Will be set dynamically
            initComplete: null, // Will be set dynamically
            drawCallback: null, // Will be set dynamically
            createdRow: null, // Will be set dynamically
            columnDefs: [],
            processing: true,
            serverSide: false,
            ajax: null,
            deferRender: true,
            scroller: false,
            scrollX: true,
            scrollY: '400px',
            scrollCollapse: true,
            autoWidth: false,
            fixedHeader: true,
            stateSave: true,
            keys: true,
            select: {
                style: 'multi',
                selector: 'td:first-child'
            }
        }
    };

    // ===== DATATABLES ENHANCEMENTS =====
    const DataTablesEnhancer = {
        // Initialize DataTable with enhanced features
        init: function(selector, customOptions = {}) {
            const table = document.querySelector(selector);
            if (!table) {
                console.warn(`Table with selector "${selector}" not found`);
                return null;
            }

            // Merge options
            const options = this.mergeOptions(customOptions);
            
            // Set language
            options.language = this.getLanguage();
            
            // Add custom callbacks
            options.initComplete = this.createInitCompleteCallback(options.initComplete);
            options.drawCallback = this.createDrawCallbackCallback(options.drawCallback);
            options.createdRow = this.createCreatedRowCallback(options.createdRow);
            
            // Initialize DataTable
            let dataTable = null;
            
            if (typeof $.fn.DataTable !== 'undefined') {
                dataTable = $(table).DataTable(options);
                this.enhanceDataTable(dataTable, table);
            } else {
                console.error('DataTables library not loaded');
            }

            return dataTable;
        },

        // Merge custom options with defaults
        mergeOptions: function(customOptions) {
            const merged = JSON.parse(JSON.stringify(DataTablesConfig.options));
            
            // Deep merge
            Object.keys(customOptions).forEach(key => {
                if (typeof customOptions[key] === 'object' && !Array.isArray(customOptions[key])) {
                    merged[key] = { ...merged[key], ...customOptions[key] };
                } else {
                    merged[key] = customOptions[key];
                }
            });

            return merged;
        },

        // Get language configuration
        getLanguage: function() {
            // Try to load from CDN first
            const language = JSON.parse(JSON.stringify(DataTablesConfig.language));
            
            // Fallback to local translations if CDN fails
            if (!language.url || !this.isUrlAccessible(language.url)) {
                return DataTablesConfig.language.fallback;
            }

            return language;
        },

        // Check if URL is accessible
        isUrlAccessible: function(url) {
            // Simple check - in production you might want to implement a proper check
            return url && url.startsWith('http');
        },

        // Create enhanced initComplete callback
        createInitCompleteCallback: function(customCallback) {
            return function(settings, json) {
                // Add custom styling
                this.api().columns().every(function() {
                    const column = this;
                    const header = column.header();
                    
                    // Skip action columns
                    if (header.textContent.includes('الإجراءات') || 
                        header.textContent.includes('Actions') ||
                        header.textContent.includes('العمليات')) {
                        return;
                    }

                    // Add search input for each column
                    const searchContainer = document.createElement('div');
                    searchContainer.className = 'column-search-container';
                    searchContainer.style.marginTop = '5px';

                    const searchInput = document.createElement('input');
                    searchInput.type = 'text';
                    searchInput.className = 'form-control form-control-sm';
                    searchInput.placeholder = 'بحث...';
                    searchInput.style.fontSize = '12px';
                    searchInput.style.padding = '2px 5px';

                    // Add debounced search
                    let searchTimeout;
                    searchInput.addEventListener('keyup', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        }, 300);
                    });

                    searchContainer.appendChild(searchInput);
                    header.appendChild(searchContainer);
                });

                // Add export buttons
                this.addExportButtons();

                // Add refresh button
                this.addRefreshButton();

                // Call custom callback if provided
                if (customCallback && typeof customCallback === 'function') {
                    customCallback.call(this, settings, json);
                }

                // Add table enhancements
                this.enhanceTableStyling();
            };
        },

        // Create enhanced drawCallback callback
        createDrawCallbackCallback: function(customCallback) {
            return function(settings) {
                // Add row hover effects
                this.api().rows().every(function() {
                    const row = this.node();
                    if (row) {
                        row.style.transition = 'background-color 0.2s ease';
                    }
                });

                // Add loading state removal
                const table = this.api().table().node();
                if (table) {
                    table.classList.remove('loading');
                }

                // Call custom callback if provided
                if (customCallback && typeof customCallback === 'function') {
                    customCallback.call(this, settings);
                }
            };
        },

        // Create enhanced createdRow callback
        createCreatedRowCallback: function(customCallback) {
            return function(row, data, dataIndex) {
                // Add row ID
                row.id = `row-${dataIndex}`;
                
                // Add data attributes
                row.setAttribute('data-index', dataIndex);
                
                // Add row selection checkbox
                if (this.api().select) {
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.className = 'row-checkbox';
                    checkbox.style.margin = '0 5px';
                    
                    const firstCell = row.querySelector('td:first-child');
                    if (firstCell) {
                        firstCell.insertBefore(checkbox, firstCell.firstChild);
                    }
                }

                // Call custom callback if provided
                if (customCallback && typeof customCallback === 'function') {
                    customCallback.call(this, row, data, dataIndex);
                }
            };
        },

        // Enhance DataTable after initialization
        enhanceDataTable: function(dataTable, table) {
            if (!dataTable) return;

            // Add custom methods
            dataTable.enhance = this.enhanceTableStyling.bind(this);
            dataTable.refresh = this.refreshTable.bind(this);
            dataTable.export = this.exportTable.bind(this);

            // Add keyboard shortcuts
            this.addKeyboardShortcuts(dataTable);

            // Add context menu
            this.addContextMenu(dataTable);

            // Add row actions
            this.addRowActions(dataTable);

            // Add bulk actions
            this.addBulkActions(dataTable);

            // Add table info
            this.addTableInfo(dataTable);
        },

        // Add export buttons
        addExportButtons: function() {
            const table = this.api().table().node();
            if (!table) return;

            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'export-buttons';
            buttonContainer.style.marginBottom = '15px';
            buttonContainer.style.textAlign = 'left';

            const buttons = [
                { text: 'تصدير Excel', class: 'btn-success', action: 'excel' },
                { text: 'تصدير PDF', class: 'btn-danger', action: 'pdf' },
                { text: 'طباعة', class: 'btn-info', action: 'print' }
            ];

            buttons.forEach(button => {
                const btn = document.createElement('button');
                btn.className = `btn btn-sm ${button.class}`;
                btn.textContent = button.text;
                btn.style.marginLeft = '5px';
                
                btn.addEventListener('click', () => {
                    this.exportTable(button.action);
                });

                buttonContainer.appendChild(btn);
            });

            table.parentNode.insertBefore(buttonContainer, table);
        },

        // Add refresh button
        addRefreshButton: function() {
            const table = this.api().table().node();
            if (!table) return;

            const refreshBtn = document.createElement('button');
            refreshBtn.className = 'btn btn-sm btn-primary refresh-btn';
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> تحديث';
            refreshBtn.style.marginLeft = '5px';
            
            refreshBtn.addEventListener('click', () => {
                this.api().ajax.reload();
            });

            const buttonContainer = table.parentNode.querySelector('.export-buttons');
            if (buttonContainer) {
                buttonContainer.appendChild(refreshBtn);
            }
        },

        // Enhance table styling
        enhanceTableStyling: function() {
            const table = this.api().table().node();
            if (!table) return;

            // Add custom classes
            table.classList.add('enhanced-table');
            
            // Style headers
            const headers = table.querySelectorAll('th');
            headers.forEach(header => {
                header.classList.add('enhanced-header');
                header.style.position = 'relative';
            });

            // Style rows
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.classList.add('enhanced-row');
                if (index % 2 === 0) {
                    row.classList.add('even-row');
                } else {
                    row.classList.add('odd-row');
                }
            });

            // Add hover effects
            table.addEventListener('mouseover', (e) => {
                if (e.target.closest('tr')) {
                    e.target.closest('tr').classList.add('hover-row');
                }
            });

            table.addEventListener('mouseout', (e) => {
                if (e.target.closest('tr')) {
                    e.target.closest('tr').classList.remove('hover-row');
                }
            });
        },

        // Add keyboard shortcuts
        addKeyboardShortcuts: function(dataTable) {
            document.addEventListener('keydown', (e) => {
                // Ctrl/Cmd + F: Focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    const searchInput = document.querySelector('.dataTables_filter input');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }

                // Ctrl/Cmd + R: Refresh table
                if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                    e.preventDefault();
                    dataTable.api().ajax.reload();
                }

                // Escape: Clear search
                if (e.key === 'Escape') {
                    const searchInput = document.querySelector('.dataTables_filter input');
                    if (searchInput) {
                        searchInput.value = '';
                        dataTable.api().search('').draw();
                    }
                }
            });
        },

        // Add context menu
        addContextMenu: function(dataTable) {
            const table = dataTable.api().table().node();
            if (!table) return;

            table.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                
                const row = e.target.closest('tr');
                if (!row) return;

                const contextMenu = this.createContextMenu(row, dataTable);
                document.body.appendChild(contextMenu);

                // Position menu
                contextMenu.style.left = e.pageX + 'px';
                contextMenu.style.top = e.pageY + 'px';

                // Remove menu on click outside
                setTimeout(() => {
                    document.addEventListener('click', function removeMenu() {
                        contextMenu.remove();
                        document.removeEventListener('click', removeMenu);
                    });
                }, 0);
            });
        },

        // Create context menu
        createContextMenu: function(row, dataTable) {
            const menu = document.createElement('div');
            menu.className = 'context-menu';
            menu.style.cssText = `
                position: fixed;
                background: white;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                z-index: 1000;
                min-width: 150px;
                padding: 5px 0;
            `;

            const actions = [
                { text: 'تعديل', action: 'edit' },
                { text: 'حذف', action: 'delete' },
                { text: 'نسخ', action: 'copy' },
                { text: 'عرض التفاصيل', action: 'view' }
            ];

            actions.forEach(action => {
                const item = document.createElement('div');
                item.className = 'context-menu-item';
                item.textContent = action.text;
                item.style.cssText = `
                    padding: 8px 15px;
                    cursor: pointer;
                    transition: background-color 0.2s;
                `;

                item.addEventListener('mouseover', () => {
                    item.style.backgroundColor = '#f0f0f0';
                });

                item.addEventListener('mouseout', () => {
                    item.style.backgroundColor = 'transparent';
                });

                item.addEventListener('click', () => {
                    this.handleContextMenuAction(action.action, row, dataTable);
                });

                menu.appendChild(item);
            });

            return menu;
        },

        // Handle context menu actions
        handleContextMenuAction: function(action, row, dataTable) {
            const rowData = dataTable.api().row(row).data();
            
            switch (action) {
                case 'edit':
                    console.log('Edit row:', rowData);
                    break;
                case 'delete':
                    if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                        dataTable.api().row(row).remove().draw();
                    }
                    break;
                case 'copy':
                    navigator.clipboard.writeText(JSON.stringify(rowData));
                    break;
                case 'view':
                    console.log('View row details:', rowData);
                    break;
            }
        },

        // Add row actions
        addRowActions: function(dataTable) {
            const table = dataTable.api().table().node();
            if (!table) return;

            // Add action column if not exists
            const headers = table.querySelectorAll('thead th');
            let hasActionColumn = false;
            
            headers.forEach(header => {
                if (header.textContent.includes('الإجراءات') || 
                    header.textContent.includes('Actions')) {
                    hasActionColumn = true;
                }
            });

            if (!hasActionColumn) {
                // Add action header
                const actionHeader = document.createElement('th');
                actionHeader.textContent = 'الإجراءات';
                actionHeader.className = 'text-center';
                table.querySelector('thead tr').appendChild(actionHeader);

                // Add action cells to each row
                dataTable.api().rows().every(function() {
                    const row = this.node();
                    if (row) {
                        const actionCell = document.createElement('td');
                        actionCell.className = 'text-center';
                        actionCell.innerHTML = `
                            <button class="btn btn-sm btn-primary edit-btn" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                        row.appendChild(actionCell);
                    }
                });
            }
        },

        // Add bulk actions
        addBulkActions: function(dataTable) {
            const table = dataTable.api().table().node();
            if (!table) return;

            const bulkContainer = document.createElement('div');
            bulkContainer.className = 'bulk-actions';
            bulkContainer.style.cssText = `
                margin-bottom: 15px;
                padding: 10px;
                background: #f8f9fa;
                border-radius: 4px;
                display: none;
            `;

            bulkContainer.innerHTML = `
                <span class="selected-count">0 سجل محدد</span>
                <button class="btn btn-sm btn-danger bulk-delete" style="margin-left: 10px;">
                    حذف المحدد
                </button>
                <button class="btn btn-sm btn-secondary bulk-export" style="margin-left: 5px;">
                    تصدير المحدد
                </button>
            `;

            table.parentNode.insertBefore(bulkContainer, table);

            // Handle bulk selection
            const selectAllCheckbox = table.querySelector('thead input[type="checkbox"]');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', (e) => {
                    const checkboxes = table.querySelectorAll('tbody input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = e.target.checked;
                    });
                    this.updateBulkActions(dataTable, bulkContainer);
                });
            }

            // Handle individual row selection
            table.addEventListener('change', (e) => {
                if (e.target.type === 'checkbox') {
                    this.updateBulkActions(dataTable, bulkContainer);
                }
            });
        },

        // Update bulk actions
        updateBulkActions: function(dataTable, bulkContainer) {
            const table = dataTable.api().table().node();
            const selectedCheckboxes = table.querySelectorAll('tbody input[type="checkbox"]:checked');
            const selectedCount = selectedCheckboxes.length;

            const countSpan = bulkContainer.querySelector('.selected-count');
            if (countSpan) {
                countSpan.textContent = `${selectedCount} سجل محدد`;
            }

            if (selectedCount > 0) {
                bulkContainer.style.display = 'block';
            } else {
                bulkContainer.style.display = 'none';
            }
        },

        // Add table info
        addTableInfo: function(dataTable) {
            const table = dataTable.api().table().node();
            if (!table) return;

            const infoContainer = document.createElement('div');
            infoContainer.className = 'table-info';
            infoContainer.style.cssText = `
                margin-top: 15px;
                padding: 10px;
                background: #e9ecef;
                border-radius: 4px;
                font-size: 14px;
                color: #6c757d;
            `;

            table.parentNode.appendChild(infoContainer);

            // Update info on draw
            dataTable.api().on('draw', function() {
                const info = dataTable.api().page.info();
                infoContainer.innerHTML = `
                    إجمالي السجلات: ${info.recordsTotal} | 
                    السجلات المعروضة: ${info.recordsDisplay} | 
                    الصفحة ${info.page + 1} من ${info.pages}
                `;
            });
        },

        // Refresh table
        refreshTable: function() {
            if (this.api) {
                this.api().ajax.reload();
            }
        },

        // Export table
        exportTable: function(format = 'excel') {
            if (!this.api) return;

            const data = this.api().data().toArray();
            const headers = this.api().columns().header().toArray().map(h => h.textContent);

            switch (format) {
                case 'excel':
                    this.exportToExcel(data, headers);
                    break;
                case 'pdf':
                    this.exportToPDF(data, headers);
                    break;
                case 'print':
                    this.printTable();
                    break;
            }
        },

        // Export to Excel
        exportToExcel: function(data, headers) {
            // Simple CSV export (in production, use a proper Excel library)
            let csv = headers.join(',') + '\n';
            
            data.forEach(row => {
                const values = Object.values(row).map(value => 
                    typeof value === 'string' ? `"${value}"` : value
                );
                csv += values.join(',') + '\n';
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'export.csv';
            link.click();
        },

        // Export to PDF
        exportToPDF: function(data, headers) {
            // In production, use a proper PDF library like jsPDF
            console.log('PDF export not implemented');
        },

        // Print table
        printTable: function() {
            const table = this.api().table().node();
            if (!table) return;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>طباعة الجدول</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            table { border-collapse: collapse; width: 100%; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
                            th { background-color: #f2f2f2; }
                        </style>
                    </head>
                    <body>
                        ${table.outerHTML}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    };

    // ===== GLOBAL FUNCTIONS =====
    window.DataTablesFix = {
        init: DataTablesEnhancer.init.bind(DataTablesEnhancer),
        enhance: DataTablesEnhancer.enhanceDataTable.bind(DataTablesEnhancer),
        config: DataTablesConfig
    };

    // ===== AUTO-INITIALIZATION =====
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all tables with class 'data-table'
        document.querySelectorAll('.data-table').forEach(table => {
            const tableId = table.id || `table-${Math.random().toString(36).substr(2, 9)}`;
            table.id = tableId;
            
            DataTablesFix.init(`#${tableId}`);
        });

        // Initialize tables with data-* attributes
        document.querySelectorAll('[data-datatable]').forEach(table => {
            const options = {};
            
            // Parse data attributes
            if (table.dataset.pageLength) {
                options.pageLength = parseInt(table.dataset.pageLength);
            }
            
            if (table.dataset.orderable === 'false') {
                options.ordering = false;
            }
            
            if (table.dataset.searchable === 'false') {
                options.searching = false;
            }
            
            if (table.dataset.pageable === 'false') {
                options.paging = false;
            }

            DataTablesFix.init(`#${table.id}`, options);
        });
    });

    // ===== ERROR HANDLING =====
    window.addEventListener('error', function(e) {
        if (e.message.includes('DataTable')) {
            console.error('DataTables error:', e.error);
            
            // Try to recover
            setTimeout(() => {
                document.querySelectorAll('.data-table').forEach(table => {
                    if (!table.DataTable) {
                        DataTablesFix.init(`#${table.id}`);
                    }
                });
            }, 1000);
        }
    });

    console.log('DataTables Fixes loaded successfully!');

})();