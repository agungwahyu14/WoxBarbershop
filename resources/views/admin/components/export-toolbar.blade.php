<!-- Export Toolbar Component -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Export {{ $title ?? 'Data' }}</h3>
            <p class="text-sm text-gray-600">Export data dalam berbagai format atau cetak langsung</p>
        </div>

        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <!-- Month Filter -->
            <div class="flex items-center space-x-2">
                <label for="export-month" class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter
                    Bulan:</label>
                <select id="export-month"
                    class="block w-40 pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                    <option value="">Semua Data</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Year Filter -->
            <div class="flex items-center space-x-2">
                <label for="export-year" class="text-sm font-medium text-gray-700">Tahun:</label>
                <select id="export-year"
                    class="block w-24 pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                    @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}" {{ request('year', date('Y')) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Export Buttons -->
            <div class="flex space-x-2">
                <div class="relative inline-block text-left">
                    <div>
                        <button type="button" id="export-dropdown-button"
                            class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            aria-expanded="true" aria-haspopup="true">
                            <i class="fas fa-download mr-1"></i>
                            Export
                            <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div id="export-dropdown-menu"
                        class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="#"
                                class="export-btn text-gray-700 flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                data-type="pdf" role="menuitem" tabindex="-1">
                                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                Export PDF
                            </a>
                            <a href="#"
                                class="export-btn text-gray-700 flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                data-type="excel" role="menuitem" tabindex="-1">
                                <i class="fas fa-file-excel text-green-500 mr-3"></i>
                                Export Excel
                            </a>
                            <a href="#"
                                class="export-btn text-gray-700 flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                data-type="csv" role="menuitem" tabindex="-1">
                                <i class="fas fa-file-csv text-blue-500 mr-3"></i>
                                Export CSV
                            </a>
                            <a href="#"
                                class="export-btn text-gray-700 flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                                data-type="print" role="menuitem" tabindex="-1">
                                <i class="fas fa-print text-purple-500 mr-3"></i>
                                Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle dropdown
        const dropdownButton = document.getElementById('export-dropdown-button');
        const dropdownMenu = document.getElementById('export-dropdown-menu');

        dropdownButton.addEventListener('click', function() {
            dropdownMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // Handle export actions
        const exportButtons = document.querySelectorAll('.export-btn');
        const monthSelect = document.getElementById('export-month');
        const yearSelect = document.getElementById('export-year');

        exportButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const type = this.getAttribute('data-type');
                const month = monthSelect.value;
                const year = yearSelect.value;

                // Build export URL
                let exportUrl = `{{ $baseExportUrl ?? '' }}`;
                if (!exportUrl) {
                    console.error('Base export URL not provided');
                    return;
                }

                exportUrl = exportUrl.replace(':type', type);

                // Add query parameters
                const params = new URLSearchParams();
                if (month) params.append('month', month);
                if (year) params.append('year', year);

                const queryString = params.toString();
                if (queryString) {
                    exportUrl += '?' + queryString;
                }

                // Handle different export types
                if (type === 'print') {
                    // Open print view in new window
                    window.open(exportUrl, '_blank');
                } else {
                    // Direct download for PDF, Excel, CSV
                    window.location.href = exportUrl;
                }

                // Close dropdown
                dropdownMenu.classList.add('hidden');

                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Export Started',
                        text: `${type.toUpperCase()} export sedang diproses...`,
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        });
    });
</script>
