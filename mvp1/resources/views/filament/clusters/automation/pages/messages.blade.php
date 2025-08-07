<x-filament-panels::page class="automation-messages-page">
    <div class="flex min-h-screen bg-gray-50">
        {{-- Sidebar Navigation --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0">
            <div class="p-6">
                <nav class="space-y-2">
                    {{-- Questions --}}
                    <div class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Questions</span>
                    </div>

                    {{-- All Messages - Active --}}
                    <div class="flex items-center px-3 py-2 bg-teal-700 text-white rounded-lg">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">All Messages</span>
                    </div>
                </nav>

                {{-- Secondary Navigation --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <nav class="space-y-2">
                        <div class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-lg cursor-pointer">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium">Overview</span>
                        </div>

                        <div class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-lg cursor-pointer">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">Automated Message</span>
                        </div>
                    </nav>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-hidden">
            <div class="p-8">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">All Message</h1>
                        <p class="text-gray-600 max-w-2xl">
                            Use AI to automatically detect guest questions and respond instantly. Provide 24/7 support for common inquiries and elevate the guest experience with smart auto-replies.
                        </p>
                    </div>
                    
                    {{-- Add Button --}}
                    <div class="flex-shrink-0">
                        @foreach($this->getHeaderActions() as $action)
                            {{ $action }}
                        @endforeach
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   placeholder="Search messages..." 
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                        </div>
                        
                        <div class="ml-4 flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            0
                        </div>
                    </div>
                </div>

                {{-- Add New Rule Button --}}
                <div class="mb-6">
                    <button class="inline-flex items-center px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Rule
                    </button>
                </div>

                {{-- Table Container --}}
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    @livewire('automation.message-rules-table')
                </div>
            </div>
        </main>
    </div>

    <style>
        .automation-messages-page .fi-header {
            display: none !important;
        }
        .automation-messages-page .fi-main {
            padding: 0 !important;
        }
    </style>
</x-filament-panels::page>