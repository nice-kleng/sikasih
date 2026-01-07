@extends('layouts.app')

@section('title', 'Skrining Mandiri')
@section('page-title', 'Skrining Mandiri')

@section('content')
    <div class="p-4 space-y-6" x-data="{
        selectedFactors: [],
        totalScore: 0,
        kategori: '',
        rekomendasi: '',
    
        factors: {
            'terlalu_muda': { skor: 4, kategori: 'Umur & Paritas' },
            'terlalu_tua': { skor: 4, kategori: 'Umur & Paritas' },
            'anak_lebih_4': { skor: 4, kategori: 'Umur & Paritas' },
            'jarak_kehamilan': { skor: 4, kategori: 'Umur & Paritas' },
            'kurang_gizi': { skor: 4, kategori: 'Status Gizi' },
            'pendek': { skor: 4, kategori: 'Riwayat Kesehatan' },
            'riwayat_sc': { skor: 8, kategori: 'Riwayat Obstetri' },
            'riwayat_perdarahan': { skor: 8, kategori: 'Riwayat Obstetri' },
            'riwayat_preeklamsia': { skor: 8, kategori: 'Riwayat Obstetri' },
            'riwayat_bb_rendah': { skor: 4, kategori: 'Riwayat Obstetri' },
            'riwayat_bb_tinggi': { skor: 4, kategori: 'Riwayat Obstetri' },
            'riwayat_kematian': { skor: 4, kategori: 'Riwayat Obstetri' },
            'riwayat_cacat': { skor: 4, kategori: 'Riwayat Obstetri' },
            'hamil_kembar': { skor: 4, kategori: 'Kondisi Kehamilan' },
            'hidramnion': { skor: 4, kategori: 'Kondisi Kehamilan' },
            'kelainan_letak': { skor: 8, kategori: 'Kondisi Kehamilan' },
            'perdarahan_hamil': { skor: 8, kategori: 'Kondisi Kehamilan' },
            'preeklamsia': { skor: 8, kategori: 'Penyakit Penyerta' },
            'penyakit_kronis': { skor: 4, kategori: 'Penyakit Penyerta' },
            'anemia': { skor: 4, kategori: 'Penyakit Penyerta' },
        },
    
        calculateScore() {
            this.totalScore = this.selectedFactors.reduce((sum, factor) => {
                return sum + (this.factors[factor]?.skor || 0);
            }, 0);
    
            if (this.totalScore <= 2) {
                this.kategori = 'KRR';
                this.rekomendasi = 'Bersalin di Puskesmas/Bidan';
            } else if (this.totalScore <= 6) {
                this.kategori = 'KRT';
                this.rekomendasi = 'Bersalin di Puskesmas PONED atau Rumah Sakit';
            } else {
                this.kategori = 'KRST';
                this.rekomendasi = 'Bersalin di Rumah Sakit';
            }
        }
    }" x-init="$watch('selectedFactors', () => calculateScore())">

        <!-- Info -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1 text-sm text-blue-800 dark:text-blue-200">
                    <p class="font-semibold mb-1">Panduan Skrining Mandiri</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Centang kondisi yang sesuai dengan kondisi Anda</li>
                        <li>Skor akan otomatis dihitung</li>
                        <li>Kategori risiko akan otomatis ditampilkan</li>
                        <li>Anda dapat melakukan skrining berulang kali</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Score Card (Sticky) -->
        <div
            class="sticky top-16 z-30 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-4 shadow-lg text-white">
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-primary-100 text-xs mb-1">Total Skor</p>
                    <p class="text-2xl font-bold" x-text="totalScore"></p>
                </div>
                <div class="text-center">
                    <p class="text-primary-100 text-xs mb-1">Kategori</p>
                    <p class="text-2xl font-bold" x-text="kategori || '-'"></p>
                </div>
                <div class="text-center">
                    <p class="text-primary-100 text-xs mb-1">Status</p>
                    <div class="inline-flex items-center justify-center">
                        <span x-show="kategori === 'KRR'"
                            class="text-xs font-semibold bg-green-100 text-green-800 px-2 py-1 rounded-full">AMAN</span>
                        <span x-show="kategori === 'KRT'"
                            class="text-xs font-semibold bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">PERHATIAN</span>
                        <span x-show="kategori === 'KRST'"
                            class="text-xs font-semibold bg-red-100 text-red-800 px-2 py-1 rounded-full">PRIORITAS</span>
                        <span x-show="!kategori"
                            class="text-xs font-semibold bg-gray-100 text-gray-800 px-2 py-1 rounded-full">-</span>
                    </div>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white/20" x-show="rekomendasi">
                <p class="text-primary-100 text-xs mb-1">Rekomendasi Tempat Bersalin:</p>
                <p class="text-sm font-semibold" x-text="rekomendasi"></p>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('app.skrining.store') }}" class="space-y-4">
            @csrf

            <!-- Umur & Paritas -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span
                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 text-sm font-bold mr-3">1</span>
                    Umur & Paritas
                </h3>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="terlalu_muda" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Terlalu muda (< 16 tahun)</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="terlalu_tua" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Terlalu tua (> 35 tahun)</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="anak_lebih_4" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Anak lebih dari 4</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="jarak_kehamilan" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Jarak kehamilan terakhir < 2
                                    tahun</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Status Gizi -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span
                        class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 dark:text-green-400 text-sm font-bold mr-3">2</span>
                    Status Gizi
                </h3>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="kurang_gizi" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Kurang gizi (KEK)</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="pendek" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Tinggi badan < 145 cm</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Riwayat Obstetri -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span
                        class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-purple-600 dark:text-purple-400 text-sm font-bold mr-3">3</span>
                    Riwayat Obstetri
                </h3>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="riwayat_sc" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Riwayat operasi caesar</p>
                            <p class="text-xs text-red-500 dark:text-red-400 font-semibold">Skor: 8 ⚠️</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="riwayat_perdarahan"
                            x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Riwayat perdarahan</p>
                            <p class="text-xs text-red-500 dark:text-red-400 font-semibold">Skor: 8 ⚠️</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="riwayat_preeklamsia"
                            x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Riwayat preeklamsia/eklamsia</p>
                            <p class="text-xs text-red-500 dark:text-red-400 font-semibold">Skor: 8 ⚠️</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="riwayat_bb_rendah"
                            x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Bayi terakhir BB < 2500 gram</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="riwayat_bb_tinggi"
                            x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Bayi terakhir BB > 4000 gram</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="riwayat_kematian" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Riwayat kematian janin/neonatal
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="riwayat_cacat" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Riwayat bayi cacat bawaan</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Kondisi Kehamilan Saat Ini -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span
                        class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center text-pink-600 dark:text-pink-400 text-sm font-bold mr-3">4</span>
                    Kondisi Kehamilan Saat Ini
                </h3>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="hamil_kembar" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Hamil kembar</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="hidramnion" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Hidramnion/oligohidramnion</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="kelainan_letak" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Kelainan letak janin</p>
                            <p class="text-xs text-red-500 dark:text-red-400 font-semibold">Skor: 8 ⚠️</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="perdarahan_hamil" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Perdarahan pada kehamilan ini</p>
                            <p class="text-xs text-red-500 dark:text-red-400 font-semibold">Skor: 8 ⚠️</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Penyakit Penyerta -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span
                        class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center text-orange-600 dark:text-orange-400 text-sm font-bold mr-3">5</span>
                    Penyakit Penyerta
                </h3>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="preeklamsia" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Preeklamsia/hipertensi</p>
                            <p class="text-xs text-red-500 dark:text-red-400 font-semibold">Skor: 8 ⚠️</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="penyakit_kronis" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Penyakit kronis (jantung, DM,
                                ginjal, dll)</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="faktor_risiko[]" value="anemia" x-model="selectedFactors"
                            class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Anemia (HB < 11 g/dL)</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Skor: 4</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Catatan (Optional) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <label for="catatan" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Catatan (Opsional)
                </label>
                <textarea name="catatan" id="catatan" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="Tuliskan keluhan atau catatan tambahan di sini..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="sticky bottom-20 bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg">
                <button type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-4 rounded-lg transition-colors shadow-lg hover:shadow-xl touch-feedback">
                    Simpan Hasil Skrining
                </button>
            </div>
        </form>
    </div>
@endsection
