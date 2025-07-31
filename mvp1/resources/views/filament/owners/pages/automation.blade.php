<x-filament::page class="!p-0 !m-0">
    <div class="flex min-h-screen flex-col bg-white border-t-2 border-gray-300">
        <div class="flex flex-1">
            {{-- Sidebar --}}
            <aside class="w-1/5 bg-gray-50 border-r-2 border-gray-300 p-4">
                <nav>
                    <div id="questions-section" class="flex items-center py-1 text-gray-700 bg-green-800 text-white rounded px-2 cursor-pointer">
                        <i data-feather="help-circle" class="mr-2 w-5 h-5"></i>
                        <div class="flex flex-col">
                            <span>Questions</span>
                            <span class="text-xs text-gray-500 italic">À implémenter</span>
                        </div>
                    </div>

                    <div id="messages-section" class="flex items-center py-1 font-medium text-gray-700 hover:bg-gray-100 rounded px-2 cursor-pointer">
                        <i data-feather="mail" class="mr-2 w-5 h-5"></i>
                        <span>Messages</span>
                    </div>

                    <div id="myaccount-section" class="flex items-center py-1 text-gray-700 hover:bg-gray-100 rounded px-2 cursor-pointer">
                        <i data-feather="user" class="mr-2 w-5 h-5"></i>
                        <div class="flex flex-col">
                            <span>My account</span>
                            <span class="text-xs text-gray-500 italic">À implémenter</span>
                        </div>
                    </div>

                    <div id="settings-section" class="flex items-center py-1 text-gray-700 hover:bg-gray-100 rounded px-2 cursor-pointer">
                        <i data-feather="settings" class="mr-2 w-5 h-5"></i>
                        <div class="flex flex-col">
                            <span>Settings</span>
                            <span class="text-xs text-gray-500 italic">À implémenter</span>
                        </div>
                    </div>
                </nav>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 p-6" id="main-content">
                <div class="flex items-center justify-center h-64">
                    <p class="text-lg text-gray-600">À implémenter</p>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"></script>
        <script>
            feather.replace();  // Remplacer les icônes

            // Fonction pour gérer la section active (fond vert) et le contenu de la zone principale
            function setActiveSection(sectionId) {
                // Liste des IDs des sections
                const sectionIds = ['questions-section', 'messages-section', 'myaccount-section', 'settings-section'];

                // Réinitialiser toutes les sections
                sectionIds.forEach(id => {
                    const element = document.getElementById(id);
                    if (id === sectionId) {
                        // Appliquer le fond vert à la section cliquée
                        element.classList.remove('text-gray-700', 'hover:bg-gray-100');
                        element.classList.add('bg-green-800', 'text-white');
                    } else {
                        // Remettre les autres sections à leur état initial
                        element.classList.remove('bg-green-800', 'text-white');
                        element.classList.add('text-gray-700', 'hover:bg-gray-100');
                    }
                });

                // Changer le contenu de la zone principale
                const mainContent = document.getElementById('main-content');
                if (sectionId === 'messages-section') {
                    // Afficher le tableau pour Messages
                    mainContent.innerHTML = `
                        <div class="flex gap-6 mb-4 text-sm">
                            <div class="font-medium text-blue-600">Before booking</div>
                        </div>

                        <p class="text-sm text-gray-600 mb-6">
                            Optimize contact with guests by creating and enforcing automated messaging rules. Set up auto-responses for booking events and AI-raised questions, and also schedule essential messages for your guests.
                        </p>


                        @livewire('automation.message-rules-table')
                    `;
                } else {
                    // Afficher "À implémenter" pour les autres sections
                    mainContent.innerHTML = `
                        <div class="flex items-center justify-center h-64">
                            <p class="text-lg text-gray-600">À implémenter</p>
                        </div>
                    `;
                }

                // Réinitialiser les icônes Feather après avoir changé le contenu
                feather.replace();
            }

            // Attacher les événements click après le chargement du DOM
            document.addEventListener('DOMContentLoaded', function () {
                const sectionIds = ['questions-section', 'messages-section', 'myaccount-section', 'settings-section'];
                sectionIds.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener('click', () => setActiveSection(id));
                    }
                });
            });
        </script>
    @endpush
</x-filament::page>
