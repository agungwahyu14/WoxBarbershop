/**
 * Enhanced Admin JavaScript for User Management
 * Provides advanced functionality for user management interface
 */

// Global configuration
window.AdminConfig = {
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
    notificationDuration: 5000,
    loadingDelay: 300,
    debounceDelay: 300
};

/**
 * Notification System
 */
class NotificationManager {
    constructor() {
        this.container = this.createContainer();
        this.notifications = new Map();
    }

    createContainer() {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
        return container;
    }

    show(type, title, message, duration = AdminConfig.notificationDuration) {
        const id = this.generateId();
        const notification = this.createNotification(id, type, title, message);
        
        this.container.appendChild(notification);
        this.notifications.set(id, notification);

        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Auto dismiss
        if (duration > 0) {
            setTimeout(() => {
                this.dismiss(id);
            }, duration);
        }

        return id;
    }

    createNotification(id, type, title, message) {
        const notification = document.createElement('div');
        notification.id = `notification-${id}`;
        notification.className = `notification notification-${type} transform translate-x-full`;
        
        const iconMap = {
            success: 'fas fa-check-circle text-green-500',
            error: 'fas fa-exclamation-circle text-red-500',
            warning: 'fas fa-exclamation-triangle text-yellow-500',
            info: 'fas fa-info-circle text-blue-500'
        };

        notification.innerHTML = `
            <div class="flex items-start p-4">
                <div class="flex-shrink-0">
                    <i class="${iconMap[type]} text-xl"></i>
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900">${title}</p>
                    <p class="mt-1 text-sm text-gray-500">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="notificationManager.dismiss('${id}')" 
                            class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;

        return notification;
    }

    dismiss(id) {
        const notification = this.notifications.get(id);
        if (notification) {
            notification.classList.remove('show');
            notification.classList.add('hide');
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
                this.notifications.delete(id);
            }, 300);
        }
    }

    generateId() {
        return Date.now() + Math.random().toString(36).substr(2, 9);
    }

    clear() {
        this.notifications.forEach((notification, id) => {
            this.dismiss(id);
        });
    }
}

/**
 * Loading Manager
 */
class LoadingManager {
    constructor() {
        this.overlay = null;
        this.isVisible = false;
    }

    show(message = 'Loading...') {
        if (this.isVisible) return;

        this.overlay = document.createElement('div');
        this.overlay.className = 'loading-overlay';
        this.overlay.innerHTML = `
            <div class="bg-white rounded-lg p-8 flex flex-col items-center space-y-4 shadow-xl">
                <div class="loading-spinner"></div>
                <p class="text-gray-600 font-medium">${message}</p>
            </div>
        `;

        document.body.appendChild(this.overlay);
        this.isVisible = true;
        document.body.style.overflow = 'hidden';
    }

    hide() {
        if (!this.isVisible || !this.overlay) return;

        this.overlay.remove();
        this.overlay = null;
        this.isVisible = false;
        document.body.style.overflow = '';
    }
}

/**
 * Data Table Manager
 */
class DataTableManager {
    constructor(tableSelector, options = {}) {
        this.table = document.querySelector(tableSelector);
        this.options = {
            searchable: true,
            sortable: true,
            filterable: true,
            paginated: true,
            ...options
        };
        
        this.currentPage = 1;
        this.pageSize = 10;
        this.searchTerm = '';
        this.sortColumn = '';
        this.sortDirection = 'asc';
        this.filters = {};

        this.init();
    }

    init() {
        if (!this.table) return;

        this.setupSearch();
        this.setupSort();
        this.setupFilters();
        this.setupPagination();
        this.bindEvents();
    }

    setupSearch() {
        if (!this.options.searchable) return;

        const searchInput = document.querySelector('[data-table-search]');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.currentPage = 1;
                this.filterAndDisplay();
            }, AdminConfig.debounceDelay));
        }
    }

    setupSort() {
        if (!this.options.sortable) return;

        const sortButtons = this.table.querySelectorAll('[data-sort]');
        sortButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const column = e.currentTarget.dataset.sort;
                this.sort(column);
            });
        });
    }

    setupFilters() {
        if (!this.options.filterable) return;

        const filterSelects = document.querySelectorAll('[data-table-filter]');
        filterSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                const filterName = e.target.dataset.tableFilter;
                const filterValue = e.target.value;
                
                if (filterValue) {
                    this.filters[filterName] = filterValue;
                } else {
                    delete this.filters[filterName];
                }
                
                this.currentPage = 1;
                this.filterAndDisplay();
            });
        });
    }

    setupPagination() {
        if (!this.options.paginated) return;

        const paginationContainer = document.querySelector('[data-pagination]');
        if (paginationContainer) {
            this.paginationContainer = paginationContainer;
        }
    }

    sort(column) {
        if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = column;
            this.sortDirection = 'asc';
        }

        this.updateSortIndicators();
        this.filterAndDisplay();
    }

    updateSortIndicators() {
        const sortButtons = this.table.querySelectorAll('[data-sort]');
        sortButtons.forEach(button => {
            const icon = button.querySelector('i');
            if (icon) {
                icon.className = 'fas fa-sort text-gray-400';
            }
            
            if (button.dataset.sort === this.sortColumn) {
                icon.className = `fas fa-sort-${this.sortDirection} text-blue-600`;
            }
        });
    }

    filterAndDisplay() {
        const rows = Array.from(this.table.querySelectorAll('tbody tr'));
        let filteredRows = rows;

        // Apply search filter
        if (this.searchTerm) {
            filteredRows = filteredRows.filter(row => {
                const text = row.textContent.toLowerCase();
                return text.includes(this.searchTerm);
            });
        }

        // Apply column filters
        Object.entries(this.filters).forEach(([column, value]) => {
            filteredRows = filteredRows.filter(row => {
                const cell = row.querySelector(`[data-column="${column}"]`);
                return cell && cell.textContent.toLowerCase().includes(value.toLowerCase());
            });
        });

        // Apply sorting
        if (this.sortColumn) {
            filteredRows.sort((a, b) => {
                const aCell = a.querySelector(`[data-column="${this.sortColumn}"]`);
                const bCell = b.querySelector(`[data-column="${this.sortColumn}"]`);
                
                const aValue = aCell ? aCell.textContent.trim() : '';
                const bValue = bCell ? bCell.textContent.trim() : '';
                
                const comparison = aValue.localeCompare(bValue, undefined, { numeric: true });
                return this.sortDirection === 'asc' ? comparison : -comparison;
            });
        }

        this.displayRows(rows, filteredRows);
        this.updatePagination(filteredRows.length);
    }

    displayRows(allRows, filteredRows) {
        // Hide all rows
        allRows.forEach(row => row.style.display = 'none');

        // Calculate pagination
        const startIndex = (this.currentPage - 1) * this.pageSize;
        const endIndex = startIndex + this.pageSize;
        const pageRows = filteredRows.slice(startIndex, endIndex);

        // Show current page rows
        pageRows.forEach(row => row.style.display = '');
    }

    updatePagination(totalRows) {
        if (!this.paginationContainer) return;

        const totalPages = Math.ceil(totalRows / this.pageSize);
        this.paginationContainer.innerHTML = this.generatePaginationHTML(totalPages, totalRows);

        // Bind pagination events
        const paginationButtons = this.paginationContainer.querySelectorAll('[data-page]');
        paginationButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(e.target.dataset.page);
                if (page && page !== this.currentPage) {
                    this.currentPage = page;
                    this.filterAndDisplay();
                }
            });
        });
    }

    generatePaginationHTML(totalPages, totalRows) {
        if (totalPages <= 1) return '';

        const startItem = (this.currentPage - 1) * this.pageSize + 1;
        const endItem = Math.min(this.currentPage * this.pageSize, totalRows);

        let html = `
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing ${startItem} to ${endItem} of ${totalRows} results
                </div>
                <div class="pagination-nav">
        `;

        // Previous button
        html += `
            <button class="pagination-btn ${this.currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" 
                    data-page="${this.currentPage - 1}" ${this.currentPage === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left"></i>
            </button>
        `;

        // Page numbers
        const startPage = Math.max(1, this.currentPage - 2);
        const endPage = Math.min(totalPages, this.currentPage + 2);

        for (let i = startPage; i <= endPage; i++) {
            html += `
                <button class="pagination-btn ${i === this.currentPage ? 'active' : ''}" data-page="${i}">
                    ${i}
                </button>
            `;
        }

        // Next button
        html += `
            <button class="pagination-btn ${this.currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" 
                    data-page="${this.currentPage + 1}" ${this.currentPage === totalPages ? 'disabled' : ''}>
                <i class="fas fa-chevron-right"></i>
            </button>
        `;

        html += `
                </div>
            </div>
        `;

        return html;
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

/**
 * Form Validator
 */
class FormValidator {
    constructor(formSelector, rules = {}) {
        this.form = document.querySelector(formSelector);
        this.rules = rules;
        this.errors = {};
        
        if (this.form) {
            this.init();
        }
    }

    init() {
        this.form.addEventListener('submit', (e) => {
            if (!this.validate()) {
                e.preventDefault();
                this.displayErrors();
            }
        });

        // Real-time validation
        Object.keys(this.rules).forEach(fieldName => {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('blur', () => {
                    this.validateField(fieldName);
                    this.displayFieldError(fieldName);
                });
            }
        });
    }

    validate() {
        this.errors = {};
        let isValid = true;

        Object.keys(this.rules).forEach(fieldName => {
            if (!this.validateField(fieldName)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(fieldName) {
        const field = this.form.querySelector(`[name="${fieldName}"]`);
        const value = field ? field.value.trim() : '';
        const rules = this.rules[fieldName];
        
        let isValid = true;

        rules.forEach(rule => {
            if (!this.applyRule(value, rule)) {
                if (!this.errors[fieldName]) {
                    this.errors[fieldName] = [];
                }
                this.errors[fieldName].push(rule.message);
                isValid = false;
            }
        });

        return isValid;
    }

    applyRule(value, rule) {
        switch (rule.type) {
            case 'required':
                return value !== '';
            case 'email':
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            case 'minLength':
                return value.length >= rule.value;
            case 'maxLength':
                return value.length <= rule.value;
            case 'pattern':
                return new RegExp(rule.value).test(value);
            case 'custom':
                return rule.validator(value);
            default:
                return true;
        }
    }

    displayErrors() {
        Object.keys(this.errors).forEach(fieldName => {
            this.displayFieldError(fieldName);
        });
    }

    displayFieldError(fieldName) {
        const field = this.form.querySelector(`[name="${fieldName}"]`);
        const errorContainer = field ? field.parentNode.querySelector('.field-error') : null;
        
        if (this.errors[fieldName] && this.errors[fieldName].length > 0) {
            field.classList.add('border-red-500');
            
            if (errorContainer) {
                errorContainer.textContent = this.errors[fieldName][0];
            } else {
                const errorElement = document.createElement('p');
                errorElement.className = 'field-error mt-1 text-sm text-red-600';
                errorElement.textContent = this.errors[fieldName][0];
                field.parentNode.appendChild(errorElement);
            }
        } else {
            field.classList.remove('border-red-500');
            if (errorContainer) {
                errorContainer.remove();
            }
        }
    }

    clearErrors() {
        this.errors = {};
        this.form.querySelectorAll('.field-error').forEach(el => el.remove());
        this.form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });
    }
}

/**
 * Initialize global instances
 */
const notificationManager = new NotificationManager();
const loadingManager = new LoadingManager();

/**
 * Global utility functions
 */
window.showNotification = function(type, title, message, duration) {
    return notificationManager.show(type, title, message, duration);
};

window.showLoading = function(message) {
    loadingManager.show(message);
};

window.hideLoading = function() {
    loadingManager.hide();
};

/**
 * AJAX Helper
 */
window.ajaxRequest = function(url, options = {}) {
    const defaults = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': AdminConfig.csrfToken
        },
        credentials: 'same-origin'
    };

    const config = { ...defaults, ...options };
    
    return fetch(url, config)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
};

/**
 * Initialize on DOM ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize data tables
    const userTable = document.querySelector('#users-table');
    if (userTable) {
        new DataTableManager('#users-table', {
            searchable: true,
            sortable: true,
            filterable: true,
            paginated: true
        });
    }

    // Initialize form validators
    const userForm = document.querySelector('#user-form');
    if (userForm) {
        new FormValidator('#user-form', {
            name: [
                { type: 'required', message: 'Name is required' },
                { type: 'minLength', value: 2, message: 'Name must be at least 2 characters' }
            ],
            email: [
                { type: 'required', message: 'Email is required' },
                { type: 'email', message: 'Please enter a valid email address' }
            ],
            no_telepon: [
                { type: 'required', message: 'Phone number is required' }
            ]
        });
    }

    // Setup confirmation dialogs
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            
            const message = this.dataset.confirm;
            const href = this.href || this.dataset.href;
            
            if (confirm(message)) {
                if (href) {
                    window.location.href = href;
                } else if (this.type === 'submit') {
                    this.form.submit();
                }
            }
        });
    });

    // Setup tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.title = element.dataset.tooltip;
    });

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert[data-auto-hide]');
    alerts.forEach(alert => {
        const delay = parseInt(alert.dataset.autoHide) || 5000;
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, delay);
    });
});

/**
 * Export for module systems
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        NotificationManager,
        LoadingManager,
        DataTableManager,
        FormValidator
    };
}