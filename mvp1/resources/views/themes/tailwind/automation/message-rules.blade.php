<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header Section --}}
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">All Messages</h1>
                <p class="text-lg text-gray-600 max-w-3xl">
                    Use AI to automatically detect guest questions and respond instantly. Provide 24/7 support for common inquiries and elevate the guest experience with smart auto-replies.
                </p>
            </div>
            
            {{-- Action Button --}}
            <div class="flex-shrink-0">
                <button type="button" class="inline-flex items-center px-6 py-3 bg-teal-700 hover:bg-teal-800 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Automated Message
                </button>
            </div>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="mb-6">
        <nav class="flex space-x-8 border-b border-gray-200">
            <a href="#" class="py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm transition-colors duration-200">
                Overview
            </a>
            <a href="#" class="py-2 px-1 border-b-2 border-teal-500 text-teal-600 font-medium text-sm">
                Auto-Reply Setup
            </a>
            <a href="#" class="py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm transition-colors duration-200">
                Automated Message
            </a>
        </nav>
    </div>

    {{-- Main Content --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @livewire('automation.message-rules-table')
    </div>

    {{-- Stats Footer --}}
    <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
        <div>
            Showing 1 to 10 of 80 results
        </div>
        <div class="flex items-center space-x-2">
            <span>Per page</span>
            <select class="border border-gray-300 rounded px-2 py-1 text-sm">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
        </div>
    </div>
</div>