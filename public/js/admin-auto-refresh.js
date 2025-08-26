// Admin Auto-Refresh Utility
// Utility untuk menangani auto-refresh pada halaman admin dengan pengecekan autentikasi

window.AdminAutoRefresh = {
    intervals: new Map(),
    isLoggingOut: false,

    /**
     * Membuat auto-refresh interval dengan pengecekan autentikasi
     * @param {string} name - Nama unique untuk interval
     * @param {string} checkUrl - URL untuk pengecekan autentikasi
     * @param {Function} refreshCallback - Callback function untuk refresh data
     * @param {number} intervalTime - Waktu interval dalam milidetik (default: 30000)
     */
    create: function (name, checkUrl, refreshCallback, intervalTime = 30000) {
        // Clear existing interval with same name
        this.clear(name);

        const intervalId = setInterval(() => {
            // Skip if user is logging out
            if (this.isLoggingOut) {
                this.clear(name);
                return;
            }

            // Check authentication
            fetch(checkUrl, {
                method: 'HEAD',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (response.ok && !this.isLoggingOut) {
                    // User is authenticated, execute refresh callback
                    try {
                        refreshCallback();
                    } catch (error) {
                        console.warn(`Error in refresh callback for ${name}:`, error);
                    }
                } else {
                    // Not authenticated, clear interval and redirect
                    this.clear(name);
                    if (response.status === 401 || response.status === 419) {
                        window.location.href = window.LOGIN_URL || '/login';
                    }
                }
            }).catch(error => {
                // Network error, clear interval
                if (!this.isLoggingOut) {
                    this.clear(name);
                    console.warn(`Auto-refresh "${name}" stopped due to connection error:`, error.message);
                }
            });
        }, intervalTime);

        this.intervals.set(name, intervalId);
        console.log(`Auto-refresh "${name}" started with ${intervalTime}ms interval`);
    },

    /**
     * Menghentikan specific interval
     * @param {string} name - Nama interval yang akan dihentikan
     */
    clear: function (name) {
        const intervalId = this.intervals.get(name);
        if (intervalId) {
            clearInterval(intervalId);
            this.intervals.delete(name);
            console.log(`Auto-refresh "${name}" stopped`);
        }
    },

    /**
     * Menghentikan semua interval
     */
    clearAll: function () {
        this.intervals.forEach((intervalId, name) => {
            clearInterval(intervalId);
            console.log(`Auto-refresh "${name}" stopped`);
        });
        this.intervals.clear();
    },

    /**
     * Set status logout untuk menghentikan semua refresh
     */
    setLoggingOut: function () {
        this.isLoggingOut = true;
        this.clearAll();
    },

    /**
     * Reset status logout
     */
    resetLoggingOut: function () {
        this.isLoggingOut = false;
    }
};
