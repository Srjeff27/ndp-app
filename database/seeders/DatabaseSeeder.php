<?php

namespace Database\Seeders;

use App\Models\AiRecommendation;
use App\Models\AtlasEntry;
use App\Models\AuditLog;
use App\Models\Lab;
use App\Models\LabPost;
use App\Models\Node;
use App\Models\Policy;
use App\Models\PolicyHistory;
use App\Models\PolicyReview;
use App\Models\SimulationResult;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run roles and permissions seeder first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create users for each role
        $globalAdmin = User::factory()->create([
            'name' => 'Dr. Sarah Johnson',
            'email' => 'admin@dpp.test',
            'password' => bcrypt('password'),
        ]);
        $globalAdmin->assignRole('global_admin');

        $nodeAdmin1 = User::factory()->create([
            'name' => 'Prof. Ahmad Rizki',
            'email' => 'node@dpp.test',
            'password' => bcrypt('password'),
        ]);
        $nodeAdmin1->assignRole('node_admin');

        $nodeAdmin2 = User::factory()->create([
            'name' => 'Dr. Maria Santos',
            'email' => 'maria@dpp.test',
            'password' => bcrypt('password'),
        ]);
        $nodeAdmin2->assignRole('node_admin');

        $student1 = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'student@dpp.test',
            'password' => bcrypt('password'),
        ]);
        $student1->assignRole('student');

        $student2 = User::factory()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@dpp.test',
            'password' => bcrypt('password'),
        ]);
        $student2->assignRole('student');

        $student3 = User::factory()->create([
            'name' => 'Andi Wijaya',
            'email' => 'andi@dpp.test',
            'password' => bcrypt('password'),
        ]);
        $student3->assignRole('student');

        // Create governance nodes
        $node1 = Node::create([
            'name' => 'Lab Pemerintahan Jakarta',
            'institution' => 'Universitas Indonesia - Ilmu Politik',
            'country' => 'Indonesia',
            'lat' => -6.2088,
            'lng' => 106.8456,
            'user_id' => $nodeAdmin1->id,
        ]);

        $node2 = Node::create([
            'name' => 'Pusat Demokrasi Surabaya',
            'institution' => 'Universitas Airlangga - Administrasi Publik',
            'country' => 'Indonesia',
            'lat' => -7.2575,
            'lng' => 112.7521,
            'user_id' => $nodeAdmin2->id,
        ]);

        $node3 = Node::create([
            'name' => 'Institut Kebijakan Bandung',
            'institution' => 'Institut Teknologi Bandung',
            'country' => 'Indonesia',
            'lat' => -6.9175,
            'lng' => 107.6191,
            'user_id' => $globalAdmin->id,
        ]);

        $node4 = Node::create([
            'name' => 'Pusat Riset Sipil Yogyakarta',
            'institution' => 'Universitas Gadjah Mada - Ilmu Politik',
            'country' => 'Indonesia',
            'lat' => -7.7956,
            'lng' => 110.3695,
            'user_id' => $nodeAdmin1->id,
        ]);

        $node5 = Node::create([
            'name' => 'Lab Demokrasi Makassar',
            'institution' => 'Universitas Hasanuddin',
            'country' => 'Indonesia',
            'lat' => -5.1477,
            'lng' => 119.4327,
            'user_id' => $nodeAdmin2->id,
        ]);

        // Create policies (Kebijakan Pemerintah)
        $policy1 = Policy::create([
            'node_id' => $node1->id,
            'title' => 'Pembangunan MRT Jakarta Fase 3',
            'description' => 'Pembangunan jalur MRT dari Lebak Bulus hingga Kota dengan total panjang 25 km untuk mengurangi kemacetan di Jakarta.',
            'category' => 'Infrastruktur',
            'budget' => 15000000000000, // 15 Triliun
            'implementation_date' => '2025-06-01',
            'status' => 'proposed',
            'budget_breakdown' => [
                'konstruksi' => 10000000000000,
                'pengadaan_kereta' => 3000000000000,
                'operasional' => 2000000000000,
            ],
        ]);

        $policy2 = Policy::create([
            'node_id' => $node2->id,
            'title' => 'Program Beasiswa Pendidikan Gratis',
            'description' => 'Memberikan beasiswa penuh untuk 10,000 siswa berprestasi dari keluarga kurang mampu untuk jenjang SMA dan Perguruan Tinggi.',
            'category' => 'Pendidikan',
            'budget' => 500000000000, // 500 Miliar
            'implementation_date' => '2025-07-01',
            'status' => 'proposed',
            'budget_breakdown' => [
                'biaya_pendidikan' => 300000000000,
                'biaya_hidup' => 150000000000,
                'administrasi' => 50000000000,
            ],
        ]);

        $policy3 = Policy::create([
            'node_id' => $node3->id,
            'title' => 'Digitalisasi Layanan Publik',
            'description' => 'Mengintegrasikan semua layanan pemerintah dalam satu aplikasi mobile untuk meningkatkan efisiensi dan transparansi.',
            'category' => 'Teknologi',
            'budget' => 200000000000, // 200 Miliar
            'implementation_date' => '2025-03-01',
            'status' => 'active',
            'budget_breakdown' => [
                'pengembangan_aplikasi' => 100000000000,
                'infrastruktur_server' => 70000000000,
                'pelatihan_sdm' => 30000000000,
            ],
        ]);

        $policy4 = Policy::create([
            'node_id' => $node4->id,
            'title' => 'Pembangunan Rumah Sakit Rujukan',
            'description' => 'Membangun 5 rumah sakit rujukan baru di daerah terpencil untuk meningkatkan akses layanan kesehatan.',
            'category' => 'Kesehatan',
            'budget' => 2000000000000, // 2 Triliun
            'implementation_date' => '2025-09-01',
            'status' => 'proposed',
            'budget_breakdown' => [
                'pembangunan_gedung' => 1200000000000,
                'peralatan_medis' => 600000000000,
                'rekrutmen_tenaga_medis' => 200000000000,
            ],
        ]);

        $policy5 = Policy::create([
            'node_id' => $node5->id,
            'title' => 'Kenaikan Pajak Kendaraan Bermotor',
            'description' => 'Menaikkan pajak kendaraan bermotor sebesar 20% untuk mengurangi polusi udara dan kemacetan.',
            'category' => 'Lingkungan',
            'budget' => 0, // Tidak ada anggaran (kebijakan regulasi)
            'implementation_date' => '2025-01-01',
            'status' => 'proposed',
        ]);

        // Create policy reviews (Penilaian Masyarakat)

        // Policy 1 (MRT) - Skor tinggi (disetujui)
        PolicyReview::create(['policy_id' => $policy1->id, 'user_id' => $student1->id, 'score' => 95, 'comment' => 'Sangat diperlukan untuk mengatasi kemacetan Jakarta!', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy1->id, 'user_id' => $student2->id, 'score' => 92, 'comment' => 'Bagus, tapi perlu perhatian dampak lingkungan saat konstruksi.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy1->id, 'user_id' => $student3->id, 'score' => 88, 'comment' => 'Setuju, asalkan tepat waktu dan tidak ada korupsi.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy1->id, 'user_id' => $nodeAdmin1->id, 'score' => 94, 'comment' => 'Investasi jangka panjang yang sangat baik.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy1->id, 'user_id' => $nodeAdmin2->id, 'score' => 96, 'comment' => 'Akan meningkatkan produktivitas ekonomi Jakarta.', 'sentiment' => 'positive']);

        // Policy 2 (Beasiswa) - Skor tinggi (disetujui)
        PolicyReview::create(['policy_id' => $policy2->id, 'user_id' => $student1->id, 'score' => 98, 'comment' => 'Sangat membantu! Saya mendukung penuh kebijakan ini.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy2->id, 'user_id' => $student2->id, 'score' => 97, 'comment' => 'Ini akan membuka akses pendidikan untuk banyak anak Indonesia.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy2->id, 'user_id' => $student3->id, 'score' => 95, 'comment' => 'Luar biasa! Harap seleksi benar-benar transparan.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy2->id, 'user_id' => $globalAdmin->id, 'score' => 99, 'comment' => 'Investasi terbaik untuk masa depan bangsa.', 'sentiment' => 'positive']);

        // Policy 3 (Digitalisasi) - Skor tinggi (disetujui)
        PolicyReview::create(['policy_id' => $policy3->id, 'user_id' => $student1->id, 'score' => 91, 'comment' => 'Sudah waktunya! Tapi pastikan keamanan data terjaga.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy3->id, 'user_id' => $student2->id, 'score' => 93, 'comment' => 'Akan sangat memudahkan urusan administrasi.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy3->id, 'user_id' => $nodeAdmin1->id, 'score' => 92, 'comment' => 'Perlu pelatihan SDM yang memadai.', 'sentiment' => 'positive']);

        // Policy 4 (Rumah Sakit) - Skor tinggi (disetujui)
        PolicyReview::create(['policy_id' => $policy4->id, 'user_id' => $student1->id, 'score' => 94, 'comment' => 'Sangat penting untuk pemerataan akses kesehatan.', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy4->id, 'user_id' => $student2->id, 'score' => 96, 'comment' => 'Daerah terpencil sangat membutuhkan ini!', 'sentiment' => 'positive']);
        PolicyReview::create(['policy_id' => $policy4->id, 'user_id' => $globalAdmin->id, 'score' => 95, 'comment' => 'Prioritas yang tepat untuk kesehatan masyarakat.', 'sentiment' => 'positive']);

        // Policy 5 (Pajak Kendaraan) - Skor rendah (ditolak)
        PolicyReview::create(['policy_id' => $policy5->id, 'user_id' => $student1->id, 'score' => 45, 'comment' => 'Terlalu memberatkan rakyat kecil. Tidak setuju!', 'sentiment' => 'negative']);
        PolicyReview::create(['policy_id' => $policy5->id, 'user_id' => $student2->id, 'score' => 35, 'comment' => 'Ini akan menambah beban ekonomi masyarakat.', 'sentiment' => 'negative']);
        PolicyReview::create(['policy_id' => $policy5->id, 'user_id' => $student3->id, 'score' => 50, 'comment' => 'Ada cara lain yang lebih baik untuk mengurangi polusi.', 'sentiment' => 'neutral']);
        PolicyReview::create(['policy_id' => $policy5->id, 'user_id' => $nodeAdmin1->id, 'score' => 40, 'comment' => 'Perlu dikaji ulang dampak sosial ekonominya.', 'sentiment' => 'negative']);
        PolicyReview::create(['policy_id' => $policy5->id, 'user_id' => $nodeAdmin2->id, 'score' => 55, 'comment' => 'Tujuannya baik tapi implementasinya perlu diperbaiki.', 'sentiment' => 'neutral']);

        // Generate AI Recommendations
        $this->generateAiRecommendation($policy1);
        $this->generateAiRecommendation($policy2);
        $this->generateAiRecommendation($policy3);
        $this->generateAiRecommendation($policy4);
        $this->generateAiRecommendation($policy5);

        // Create policy history (Riwayat Kebijakan 5 Tahun)
        PolicyHistory::create([
            'node_id' => $node1->id,
            'title' => 'Pembangunan MRT Jakarta Fase 1',
            'description' => 'Pembangunan jalur MRT pertama di Indonesia dari Lebak Bulus ke Bundaran HI.',
            'year' => 2019,
            'budget' => 16000000000000,
            'outcome' => 'successful',
            'impact_summary' => 'Berhasil mengurangi waktu tempuh 30% dan meningkatkan mobilitas warga Jakarta.',
        ]);

        PolicyHistory::create([
            'node_id' => $node2->id,
            'title' => 'Program Kartu Indonesia Pintar',
            'description' => 'Bantuan pendidikan untuk siswa dari keluarga kurang mampu.',
            'year' => 2020,
            'budget' => 8000000000000,
            'outcome' => 'successful',
            'impact_summary' => 'Meningkatkan angka partisipasi sekolah sebesar 15%.',
        ]);

        PolicyHistory::create([
            'node_id' => $node3->id,
            'title' => 'Implementasi e-Government',
            'description' => 'Digitalisasi layanan administrasi pemerintahan.',
            'year' => 2021,
            'budget' => 150000000000,
            'outcome' => 'partially_successful',
            'impact_summary' => 'Berhasil di kota besar, tapi masih terkendala di daerah.',
        ]);

        PolicyHistory::create([
            'node_id' => $node4->id,
            'title' => 'Program BPJS Kesehatan Universal',
            'description' => 'Jaminan kesehatan untuk seluruh rakyat Indonesia.',
            'year' => 2022,
            'budget' => 50000000000000,
            'outcome' => 'partially_successful',
            'impact_summary' => 'Cakupan luas tapi masih ada masalah kualitas layanan.',
        ]);

        PolicyHistory::create([
            'node_id' => $node5->id,
            'title' => 'Pembatasan Kendaraan Ganjil-Genap',
            'description' => 'Kebijakan pembatasan kendaraan berdasarkan plat nomor.',
            'year' => 2023,
            'budget' => 0,
            'outcome' => 'failed',
            'impact_summary' => 'Tidak efektif mengurangi kemacetan, banyak pelanggaran.',
        ]);

        // Create atlas entries (governance indicators)
        $indicators = [
            'Transparansi Pemerintah',
            'Partisipasi Sipil',
            'Integritas Pemilu',
            'Kebebasan Berekspresi',
            'Supremasi Hukum',
            'Tindakan Anti-Korupsi',
            'Demokrasi Digital',
            'Akuntabilitas Publik',
        ];

        foreach ([$node1, $node2, $node3, $node4, $node5] as $node) {
            foreach ($indicators as $indicator) {
                AtlasEntry::create([
                    'node_id' => $node->id,
                    'indicator' => $indicator,
                    'score' => rand(60, 95),
                    'source' => 'Indeks Pemerintahan Dunia 2024',
                    'meta' => json_encode([
                        'year' => 2024,
                        'methodology' => 'Survei Ahli',
                        'sample_size' => rand(500, 2000),
                    ]),
                ]);
            }
        }

        // Create Civic Labs discussions
        $lab1 = Lab::create([
            'title' => 'Meningkatkan Sistem Pemungutan Suara Digital',
            'description' => 'Bagaimana kita dapat memanfaatkan teknologi blockchain dan kriptografi untuk menciptakan sistem pemungutan suara digital yang lebih aman dan transparan? Diskusi ini mengeksplorasi tantangan teknis, hukum, dan sosial dalam mengimplementasikan e-voting dalam proses demokratis.',
            'node_id' => $node1->id,
            'user_id' => $globalAdmin->id,
        ]);

        $lab2 = Lab::create([
            'title' => 'Memerangi Misinformasi dalam Pemilu',
            'description' => 'Strategi apa yang dapat diadopsi pemerintah dan masyarakat sipil untuk melawan penyebaran misinformasi selama kampanye pemilu sambil menjaga kebebasan berbicara? Mari diskusikan mekanisme fact-checking, program literasi media, dan kerangka regulasi.',
            'node_id' => $node2->id,
            'user_id' => $nodeAdmin1->id,
        ]);

        $lab3 = Lab::create([
            'title' => 'Partisipasi Pemuda dalam Pembuatan Kebijakan',
            'description' => 'Bagaimana kita dapat menciptakan peluang yang bermakna bagi kaum muda untuk berpartisipasi dalam proses pembuatan kebijakan? Diskusi ini berfokus pada model keterlibatan inovatif, platform digital, dan reformasi institusional.',
            'node_id' => $node3->id,
            'user_id' => $student1->id,
        ]);

        $lab4 = Lab::create([
            'title' => 'Transparansi dalam Pengadaan Publik',
            'description' => 'Apa saja praktik terbaik untuk memastikan transparansi dan akuntabilitas dalam proses pengadaan pemerintah? Mari kita eksplorasi standar kontrak terbuka, perlindungan whistleblower, dan alat pemantauan digital.',
            'node_id' => null,
            'user_id' => $nodeAdmin2->id,
        ]);

        $lab5 = Lab::create([
            'title' => 'Demokrasi Iklim dan Majelis Warga',
            'description' => 'Bagaimana majelis warga dan penganggaran partisipatif dapat digunakan untuk mengatasi perubahan iklim di tingkat lokal? Diskusi ini mengkaji studi kasus dari seluruh dunia dan mengeksplorasi tantangan skalabilitas.',
            'node_id' => $node4->id,
            'user_id' => $student2->id,
        ]);

        // Create lab posts (comments/discussions)
        LabPost::create([
            'lab_id' => $lab1->id,
            'user_id' => $student1->id,
            'content' => 'Saya rasa pemungutan suara berbasis blockchain bisa menjadi terobosan, tapi kita perlu mengatasi kesenjangan digital terlebih dahulu. Tidak semua orang memiliki akses teknologi yang setara.',
        ]);

        LabPost::create([
            'lab_id' => $lab1->id,
            'user_id' => $nodeAdmin1->id,
            'content' => 'Poin yang bagus! Estonia telah menjadi pelopor e-voting sejak 2005. Pengalaman mereka menunjukkan bahwa infrastruktur identitas digital yang kuat sangat penting. Kita harus mempelajari model mereka.',
        ]);

        LabPost::create([
            'lab_id' => $lab1->id,
            'user_id' => $student2->id,
            'content' => 'Keamanan adalah kekhawatiran utama saya. Apa yang terjadi jika sistem diretas? Surat suara kertas memiliki jejak audit fisik. Bagaimana kita memastikan tingkat verifikasi yang sama dengan sistem digital?',
        ]);

        LabPost::create([
            'lab_id' => $lab2->id,
            'user_id' => $globalAdmin->id,
            'content' => 'Pendidikan literasi media harus dimulai di sekolah. Finlandia telah berhasil mengintegrasikan pemikiran kritis tentang sumber informasi ke dalam kurikulum mereka.',
        ]);

        LabPost::create([
            'lab_id' => $lab2->id,
            'user_id' => $student3->id,
            'content' => 'Saya setuju, tapi kita juga perlu fact-checking real-time selama kampanye. Platform media sosial harus bertanggung jawab atas misinformasi yang viral.',
        ]);

        LabPost::create([
            'lab_id' => $lab3->id,
            'user_id' => $nodeAdmin2->id,
            'content' => 'Dewan pemuda di tingkat kota telah menunjukkan hasil yang menjanjikan di beberapa kota Eropa. Mereka memberi kaum muda masukan langsung ke dalam alokasi anggaran.',
        ]);

        LabPost::create([
            'lab_id' => $lab3->id,
            'user_id' => $student1->id,
            'content' => 'Platform digital seperti Decidim (digunakan di Barcelona) adalah alat yang sangat baik untuk demokrasi partisipatif. Mereka memudahkan kaum muda untuk mengusulkan dan memilih kebijakan.',
        ]);

        LabPost::create([
            'lab_id' => $lab4->id,
            'user_id' => $nodeAdmin1->id,
            'content' => 'Open Contracting Data Standard (OCDS) adalah kerangka kerja yang hebat. Ini membuat data pengadaan dapat dibaca mesin dan lebih mudah dianalisis untuk pola korupsi.',
        ]);

        LabPost::create([
            'lab_id' => $lab5->id,
            'user_id' => $student2->id,
            'content' => 'Paris telah berhasil menggunakan majelis warga untuk mengembangkan rencana aksi iklim. Kuncinya adalah memastikan representasi yang beragam dan menyediakan sumber daya ahli bagi peserta.',
        ]);

        LabPost::create([
            'lab_id' => $lab5->id,
            'user_id' => $globalAdmin->id,
            'content' => 'Penganggaran partisipatif di Porto Alegre, Brasil, adalah contoh inspiratif lainnya. Warga secara langsung memutuskan bagaimana mengalokasikan bagian dari anggaran kota untuk inisiatif iklim.',
        ]);

        // Create simulation results
        SimulationResult::create([
            'node_id' => $node1->id,
            'user_id' => $globalAdmin->id,
            'indicator' => 'Transparansi Pemerintah',
            'old_score' => 75.0,
            'new_score' => 82.5,
            'change_percent' => 10,
        ]);

        SimulationResult::create([
            'node_id' => $node1->id,
            'user_id' => $student1->id,
            'indicator' => 'Demokrasi Digital',
            'old_score' => 68.0,
            'new_score' => 74.8,
            'change_percent' => 10,
        ]);

        SimulationResult::create([
            'node_id' => $node2->id,
            'user_id' => $nodeAdmin1->id,
            'indicator' => 'Partisipasi Sipil',
            'old_score' => 72.0,
            'new_score' => 64.8,
            'change_percent' => -10,
        ]);

        SimulationResult::create([
            'node_id' => $node3->id,
            'user_id' => $student2->id,
            'indicator' => 'Integritas Pemilu',
            'old_score' => 88.0,
            'new_score' => 92.4,
            'change_percent' => 5,
        ]);

        SimulationResult::create([
            'node_id' => $node4->id,
            'user_id' => $nodeAdmin2->id,
            'indicator' => 'Tindakan Anti-Korupsi',
            'old_score' => 65.0,
            'new_score' => 71.5,
            'change_percent' => 10,
        ]);

        SimulationResult::create([
            'node_id' => $node5->id,
            'user_id' => $student3->id,
            'indicator' => 'Akuntabilitas Publik',
            'old_score' => 70.0,
            'new_score' => 63.0,
            'change_percent' => -10,
        ]);

        // Create audit logs
        AuditLog::create([
            'user_id' => $globalAdmin->id,
            'action' => 'created',
            'table_name' => 'nodes',
            'before' => null,
            'after' => json_encode(['name' => 'Institut Kebijakan Bandung']),
        ]);

        AuditLog::create([
            'user_id' => $nodeAdmin1->id,
            'action' => 'updated',
            'table_name' => 'atlas_entries',
            'before' => json_encode(['score' => 70]),
            'after' => json_encode(['score' => 75]),
        ]);
    }

    private function generateAiRecommendation(Policy $policy): void
    {
        $reviews = $policy->reviews;
        $avgScore = $reviews->avg('score');
        $totalReviews = $reviews->count();

        // Analisis sentimen
        $sentimentBreakdown = [
            'positive' => $reviews->where('sentiment', 'positive')->count(),
            'neutral' => $reviews->where('sentiment', 'neutral')->count(),
            'negative' => $reviews->where('sentiment', 'negative')->count(),
        ];

        // Kritik yang sering muncul
        $commonCriticisms = $reviews
            ->whereNotNull('comment')
            ->pluck('comment')
            ->take(5)
            ->toArray();

        // Rekomendasi
        $recommendation = $avgScore >= 91 ? 'approve' : ($avgScore >= 50 ? 'revise' : 'reject');

        // Generate AI analysis
        if ($avgScore >= 91) {
            $aiAnalysis = "Kebijakan ini mendapat dukungan kuat dari masyarakat dengan skor rata-rata {$avgScore}% dari {$totalReviews} penilaian. Mayoritas responden memberikan sentimen positif. Rekomendasi: LANJUTKAN implementasi kebijakan ini.";
        } elseif ($avgScore >= 50) {
            $aiAnalysis = "Kebijakan ini mendapat respon campuran dengan skor rata-rata {$avgScore}% dari {$totalReviews} penilaian. Diperlukan revisi untuk meningkatkan dukungan masyarakat. Rekomendasi: REVISI kebijakan berdasarkan masukan masyarakat.";
        } else {
            $aiAnalysis = "Kebijakan ini mendapat penolakan dari masyarakat dengan skor rata-rata {$avgScore}% dari {$totalReviews} penilaian. Mayoritas responden memberikan sentimen negatif. Rekomendasi: TINJAU ULANG atau BATALKAN kebijakan ini.";
        }

        AiRecommendation::create([
            'policy_id' => $policy->id,
            'average_score' => $avgScore,
            'total_reviews' => $totalReviews,
            'recommendation' => $recommendation,
            'ai_analysis' => $aiAnalysis,
            'sentiment_breakdown' => $sentimentBreakdown,
            'common_criticisms' => $commonCriticisms,
            'analyzed_at' => now(),
        ]);
    }
}
