<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header avec navigation et description --}}
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            {{-- Navigation sidebar (mobile: horizontal, desktop: vertical) --}}
            <div class="lg:w-64 flex-shrink-0">
                <nav class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="space-y-2">
                        <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Overview
                        </a>
                        <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-white bg-teal-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Auto-Reply Setup
                        </a>
                        <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Automated Message
                        </a>
                    </div>
                </nav>
            </div>

            {{-- Main content area --}}
            <div class="flex-1 min-w-0">
                {{-- Page header --}}
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">All Message</h1>
                            <p class="mt-2 text-sm text-gray-600 max-w-2xl">
                                Use AI to automatically detect guest questions and respond instantly. Provide 24/7 support for common inquiries and elevate the guest experience with smart auto-replies.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            @foreach($this->getHeaderActions() as $action)
                                {{ $action }}
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Table container --}}
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    @livewire('automation.message-rules-table')
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>