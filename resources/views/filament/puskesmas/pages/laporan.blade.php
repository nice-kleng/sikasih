<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filter Form --}}
        <x-filament::card>
            <form wire:submit="generate">
                {{ $this->form }}

                <div class="mt-6 flex gap-3">
                    <x-filament::button type="submit">
                        <x-heroicon-o-document-magnifying-glass class="w-5 h-5 mr-2" />
                        Generate Laporan
                    </x-filament::button>

                    <x-filament::button color="success" wire:click="export">
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                        Export
                    </x-filament::button>
                </div>
            </form>
        </x-filament::card>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Ibu Hamil --}}
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Ibu Hamil Aktif</div>
                    <div class="text-3xl font-bold text-primary-600">
                        {{ $statistik['total_ibu_hamil'] ?? 0 }}
                    </div>
                </div>
            </x-filament::card>

            {{-- Total Pemeriksaan --}}
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Pemeriksaan</div>
                    <div class="text-3xl font-bold text-success-600">
                        {{ $statistik['total_pemeriksaan'] ?? 0 }}
                    </div>
                </div>
            </x-filament::card>

            {{-- Cakupan K1 --}}
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Cakupan K1</div>
                    <div class="text-3xl font-bold text-info-600">
                        {{ $statistik['persentase_k1'] ?? 0 }}%
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $statistik['cakupan_k1'] ?? 0 }} dari {{ $statistik['total_ibu_hamil'] ?? 0 }} ibu hamil
                    </div>
                </div>
            </x-filament::card>

            {{-- Cakupan K4 --}}
            <x-filament::card>
                <div class="space-y-2">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Cakupan K4</div>
                    <div class="text-3xl font-bold text-warning-600">
                        {{ $statistik['persentase_k4'] ?? 0 }}%
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $statistik['cakupan_k4'] ?? 0 }} dari {{ $statistik['total_ibu_hamil'] ?? 0 }} ibu hamil
                    </div>
                </div>
            </x-filament::card>
        </div>

        {{-- Skrining Risiko --}}
        <x-filament::card>
            <h2 class="text-lg font-bold mb-4">Skrining Risiko Kehamilan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- KRR --}}
                <div
                    class="p-4 bg-success-50 dark:bg-success-950 rounded-lg border border-success-200 dark:border-success-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-success-700 dark:text-success-300 font-medium">
                                Risiko Rendah (KRR)
                            </div>
                            <div class="text-2xl font-bold text-success-600 dark:text-success-400 mt-1">
                                {{ $statistik['skrining_krr'] ?? 0 }}
                            </div>
                        </div>
                        <x-heroicon-o-check-circle class="w-12 h-12 text-success-400 dark:text-success-600" />
                    </div>
                </div>

                {{-- KRT --}}
                <div
                    class="p-4 bg-warning-50 dark:bg-warning-950 rounded-lg border border-warning-200 dark:border-warning-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-warning-700 dark:text-warning-300 font-medium">
                                Risiko Tinggi (KRT)
                            </div>
                            <div class="text-2xl font-bold text-warning-600 dark:text-warning-400 mt-1">
                                {{ $statistik['skrining_krt'] ?? 0 }}
                            </div>
                        </div>
                        <x-heroicon-o-exclamation-triangle class="w-12 h-12 text-warning-400 dark:text-warning-600" />
                    </div>
                </div>

                {{-- KRST --}}
                <div
                    class="p-4 bg-danger-50 dark:bg-danger-950 rounded-lg border border-danger-200 dark:border-danger-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-danger-700 dark:text-danger-300 font-medium">
                                Risiko Sangat Tinggi (KRST)
                            </div>
                            <div class="text-2xl font-bold text-danger-600 dark:text-danger-400 mt-1">
                                {{ $statistik['skrining_krst'] ?? 0 }}
                            </div>
                        </div>
                        <x-heroicon-o-shield-exclamation class="w-12 h-12 text-danger-400 dark:text-danger-600" />
                    </div>
                </div>
            </div>
        </x-filament::card>

        {{-- Info Tenaga Kesehatan --}}
        <x-filament::card>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Tenaga Kesehatan Aktif</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ $statistik['total_tenaga'] ?? 0 }} Orang
                    </div>
                </div>
                <x-heroicon-o-users class="w-16 h-16 text-gray-300 dark:text-gray-700" />
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>
