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

        // Create more users for reviews
        $users = collect();
        $users->push($globalAdmin);
        $users->push($nodeAdmin1);
        $users->push($nodeAdmin2);

        $studentNames = [
            'Budi Santoso',
            'Siti Nurhaliza',
            'Andi Wijaya',
            'Dewi Kartika',
            'Rudi Hartono',
            'Maya Putri',
            'Agus Setiawan',
            'Lina Anggraini',
            'Hendra Gunawan',
            'Rina Susanti',
            'Dedi Kurniawan',
            'Wati Sulistyowati',
            'Joko Prasetyo',
            'Sri Wahyuni',
            'Bambang Sudirman',
            'Ani Rahayu',
            'Fahmi Abdullah',
            'Ratna Dewi',
            'Yusuf Rahman',
            'Fitriani Hasanah',
        ];

        foreach ($studentNames as $i => $name) {
            $student = User::factory()->create([
                'name' => $name,
                'email' => 'user' . ($i + 1) . '@dpp.test',
                'password' => bcrypt('password'),
            ]);
            $student->assignRole('student');
            $users->push($student);
        }

        // Data 38 Provinsi Indonesia dengan koordinat
        $provinces = [
            ['name' => 'Aceh', 'capital' => 'Banda Aceh', 'lat' => 5.5483, 'lng' => 95.3238, 'institution' => 'Universitas Syiah Kuala'],
            ['name' => 'Sumatera Utara', 'capital' => 'Medan', 'lat' => 3.5952, 'lng' => 98.6722, 'institution' => 'Universitas Sumatera Utara'],
            ['name' => 'Sumatera Barat', 'capital' => 'Padang', 'lat' => -0.9471, 'lng' => 100.4172, 'institution' => 'Universitas Andalas'],
            ['name' => 'Riau', 'capital' => 'Pekanbaru', 'lat' => 0.5071, 'lng' => 101.4478, 'institution' => 'Universitas Riau'],
            ['name' => 'Kepulauan Riau', 'capital' => 'Tanjung Pinang', 'lat' => 0.9186, 'lng' => 104.4582, 'institution' => 'Universitas Maritim Raja Ali Haji'],
            ['name' => 'Jambi', 'capital' => 'Jambi', 'lat' => -1.6101, 'lng' => 103.6131, 'institution' => 'Universitas Jambi'],
            ['name' => 'Sumatera Selatan', 'capital' => 'Palembang', 'lat' => -2.9761, 'lng' => 104.7754, 'institution' => 'Universitas Sriwijaya'],
            ['name' => 'Bengkulu', 'capital' => 'Bengkulu', 'lat' => -3.7928, 'lng' => 102.2608, 'institution' => 'Universitas Bengkulu'],
            ['name' => 'Lampung', 'capital' => 'Bandar Lampung', 'lat' => -5.4500, 'lng' => 105.2667, 'institution' => 'Universitas Lampung'],
            ['name' => 'Bangka Belitung', 'capital' => 'Pangkal Pinang', 'lat' => -2.1316, 'lng' => 106.1169, 'institution' => 'Universitas Bangka Belitung'],
            ['name' => 'DKI Jakarta', 'capital' => 'Jakarta', 'lat' => -6.2088, 'lng' => 106.8456, 'institution' => 'Universitas Indonesia'],
            ['name' => 'Jawa Barat', 'capital' => 'Bandung', 'lat' => -6.9175, 'lng' => 107.6191, 'institution' => 'Institut Teknologi Bandung'],
            ['name' => 'Banten', 'capital' => 'Serang', 'lat' => -6.1204, 'lng' => 106.1503, 'institution' => 'Universitas Sultan Ageng Tirtayasa'],
            ['name' => 'Jawa Tengah', 'capital' => 'Semarang', 'lat' => -6.9666, 'lng' => 110.4196, 'institution' => 'Universitas Diponegoro'],
            ['name' => 'DI Yogyakarta', 'capital' => 'Yogyakarta', 'lat' => -7.7956, 'lng' => 110.3695, 'institution' => 'Universitas Gadjah Mada'],
            ['name' => 'Jawa Timur', 'capital' => 'Surabaya', 'lat' => -7.2575, 'lng' => 112.7521, 'institution' => 'Institut Teknologi Sepuluh Nopember'],
            ['name' => 'Bali', 'capital' => 'Denpasar', 'lat' => -8.6705, 'lng' => 115.2126, 'institution' => 'Universitas Udayana'],
            ['name' => 'Nusa Tenggara Barat', 'capital' => 'Mataram', 'lat' => -8.5833, 'lng' => 116.1167, 'institution' => 'Universitas Mataram'],
            ['name' => 'Nusa Tenggara Timur', 'capital' => 'Kupang', 'lat' => -10.1787, 'lng' => 123.6070, 'institution' => 'Universitas Nusa Cendana'],
            ['name' => 'Kalimantan Barat', 'capital' => 'Pontianak', 'lat' => -0.0263, 'lng' => 109.3425, 'institution' => 'Universitas Tanjungpura'],
            ['name' => 'Kalimantan Tengah', 'capital' => 'Palangkaraya', 'lat' => -2.2136, 'lng' => 113.9108, 'institution' => 'Universitas Palangka Raya'],
            ['name' => 'Kalimantan Selatan', 'capital' => 'Banjarmasin', 'lat' => -3.3167, 'lng' => 114.5833, 'institution' => 'Universitas Lambung Mangkurat'],
            ['name' => 'Kalimantan Timur', 'capital' => 'Samarinda', 'lat' => -0.5022, 'lng' => 117.1536, 'institution' => 'Universitas Mulawarman'],
            ['name' => 'Kalimantan Utara', 'capital' => 'Tanjung Selor', 'lat' => 2.8500, 'lng' => 117.3667, 'institution' => 'Universitas Kaltara'],
            ['name' => 'Sulawesi Utara', 'capital' => 'Manado', 'lat' => 1.4748, 'lng' => 124.8421, 'institution' => 'Universitas Sam Ratulangi'],
            ['name' => 'Gorontalo', 'capital' => 'Gorontalo', 'lat' => 0.5412, 'lng' => 123.0595, 'institution' => 'Universitas Negeri Gorontalo'],
            ['name' => 'Sulawesi Tengah', 'capital' => 'Palu', 'lat' => -0.9003, 'lng' => 119.8779, 'institution' => 'Universitas Tadulako'],
            ['name' => 'Sulawesi Barat', 'capital' => 'Mamuju', 'lat' => -2.6748, 'lng' => 118.8885, 'institution' => 'Universitas Sulawesi Barat'],
            ['name' => 'Sulawesi Selatan', 'capital' => 'Makassar', 'lat' => -5.1477, 'lng' => 119.4327, 'institution' => 'Universitas Hasanuddin'],
            ['name' => 'Sulawesi Tenggara', 'capital' => 'Kendari', 'lat' => -3.9675, 'lng' => 122.5947, 'institution' => 'Universitas Halu Oleo'],
            ['name' => 'Maluku', 'capital' => 'Ambon', 'lat' => -3.6554, 'lng' => 128.1903, 'institution' => 'Universitas Pattimura'],
            ['name' => 'Maluku Utara', 'capital' => 'Sofifi', 'lat' => 0.7333, 'lng' => 127.3667, 'institution' => 'Universitas Khairun'],
            ['name' => 'Papua', 'capital' => 'Jayapura', 'lat' => -2.5333, 'lng' => 140.7167, 'institution' => 'Universitas Cenderawasih'],
            ['name' => 'Papua Barat', 'capital' => 'Manokwari', 'lat' => -0.8614, 'lng' => 134.0620, 'institution' => 'Universitas Papua'],
            ['name' => 'Papua Selatan', 'capital' => 'Merauke', 'lat' => -8.4932, 'lng' => 140.4018, 'institution' => 'Universitas Musamus'],
            ['name' => 'Papua Tengah', 'capital' => 'Nabire', 'lat' => -3.3634, 'lng' => 135.5130, 'institution' => 'Politeknik Negeri Nabire'],
            ['name' => 'Papua Pegunungan', 'capital' => 'Wamena', 'lat' => -4.0894, 'lng' => 138.9459, 'institution' => 'Institut Ilmu Kesehatan Wamena'],
            ['name' => 'Papua Barat Daya', 'capital' => 'Sorong', 'lat' => -0.8761, 'lng' => 131.2558, 'institution' => 'Universitas Muhammadiyah Sorong'],
        ];

        // Kategori kebijakan dan template
        $policyTemplates = [
            'Infrastruktur' => [
                ['title' => 'Pembangunan Jalan Tol Trans %s', 'budget_range' => [5000000000000, 20000000000000], 'desc' => 'Pembangunan jalan tol untuk meningkatkan konektivitas antar wilayah di %s.'],
                ['title' => 'Revitalisasi Pelabuhan %s', 'budget_range' => [1000000000000, 5000000000000], 'desc' => 'Modernisasi pelabuhan untuk mendukung perdagangan dan ekspor daerah %s.'],
                ['title' => 'Pembangunan Bandara Baru %s', 'budget_range' => [3000000000000, 15000000000000], 'desc' => 'Pembangunan bandara untuk meningkatkan akses transportasi udara di %s.'],
            ],
            'Pendidikan' => [
                ['title' => 'Program Beasiswa Unggulan %s', 'budget_range' => [100000000000, 500000000000], 'desc' => 'Memberikan beasiswa kepada 5,000 siswa berprestasi dari %s untuk pendidikan tinggi.'],
                ['title' => 'Digitalisasi Pendidikan %s', 'budget_range' => [50000000000, 200000000000], 'desc' => 'Penyediaan tablet dan internet gratis untuk siswa di daerah terpencil %s.'],
                ['title' => 'Pembangunan SMK Vokasional %s', 'budget_range' => [200000000000, 800000000000], 'desc' => 'Membangun sekolah vokasi untuk meningkatkan keterampilan pemuda %s.'],
            ],
            'Kesehatan' => [
                ['title' => 'Pembangunan RSUD Type A %s', 'budget_range' => [500000000000, 2000000000000], 'desc' => 'Membangun rumah sakit rujukan untuk layanan kesehatan terbaik di %s.'],
                ['title' => 'Program Vaksinasi Gratis %s', 'budget_range' => [50000000000, 200000000000], 'desc' => 'Vaksinasi gratis untuk seluruh masyarakat %s.'],
                ['title' => 'Puskesmas 24 Jam %s', 'budget_range' => [100000000000, 400000000000], 'desc' => 'Mengoperasikan puskesmas 24 jam di seluruh kecamatan %s.'],
            ],
            'Lingkungan' => [
                ['title' => 'Program Penghijauan Kota %s', 'budget_range' => [20000000000, 100000000000], 'desc' => 'Penanaman 1 juta pohon untuk mengurangi polusi udara di %s.'],
                ['title' => 'Pengelolaan Sampah Terpadu %s', 'budget_range' => [100000000000, 500000000000], 'desc' => 'Pembangunan TPA modern dan sistem daur ulang di %s.'],
                ['title' => 'Rehabilitasi Daerah Aliran Sungai %s', 'budget_range' => [50000000000, 300000000000], 'desc' => 'Pemulihan DAS untuk mencegah banjir dan kekeringan di %s.'],
            ],
            'Ekonomi' => [
                ['title' => 'Program UMKM Digital %s', 'budget_range' => [50000000000, 200000000000], 'desc' => 'Pelatihan dan pendanaan UMKM go digital di %s.'],
                ['title' => 'Kawasan Industri Terpadu %s', 'budget_range' => [1000000000000, 5000000000000], 'desc' => 'Pembangunan kawasan industri untuk menarik investasi di %s.'],
                ['title' => 'Kartu Prakerja Daerah %s', 'budget_range' => [100000000000, 400000000000], 'desc' => 'Program pelatihan kerja dan bantuan sosial untuk pengangguran di %s.'],
            ],
            'Pertanian' => [
                ['title' => 'Irigasi Modern %s', 'budget_range' => [200000000000, 800000000000], 'desc' => 'Pembangunan sistem irigasi modern untuk meningkatkan produktivitas pertanian %s.'],
                ['title' => 'Program Ketahanan Pangan %s', 'budget_range' => [100000000000, 500000000000], 'desc' => 'Subsidi pupuk dan benih untuk petani %s.'],
            ],
            'Pariwisata' => [
                ['title' => 'Pengembangan Destinasi Wisata %s', 'budget_range' => [100000000000, 500000000000], 'desc' => 'Pengembangan infrastruktur pariwisata dan promosi wisata %s.'],
                ['title' => 'Desa Wisata Digital %s', 'budget_range' => [50000000000, 200000000000], 'desc' => 'Memberdayakan desa wisata dengan teknologi digital di %s.'],
            ],
            'Teknologi' => [
                ['title' => 'Smart City %s', 'budget_range' => [200000000000, 1000000000000], 'desc' => 'Transformasi %s menjadi kota pintar dengan IoT dan AI.'],
                ['title' => 'e-Government Terintegrasi %s', 'budget_range' => [50000000000, 200000000000], 'desc' => 'Digitalisasi semua layanan pemerintah di %s.'],
            ],
        ];

        $statuses = ['proposed', 'active', 'completed', 'rejected'];
        $nodes = collect();
        $allPolicies = collect();

        // Create nodes for each province
        foreach ($provinces as $i => $province) {
            $node = Node::create([
                'name' => 'Pusat Kebijakan Provinsi ' . $province['name'],
                'institution' => $province['institution'] . ' - Fakultas Ilmu Sosial & Politik',
                'country' => 'Indonesia',
                'lat' => $province['lat'],
                'lng' => $province['lng'],
                'user_id' => $users->random()->id,
            ]);
            $nodes->push($node);

            // Create Atlas entries for each node
            $indicators = [
                'Indeks Demokrasi',
                'Tingkat Partisipasi Pemilih',
                'Transparansi Anggaran',
                'Akuntabilitas Publik',
                'Kebebasan Sipil',
                'Efektivitas Pemerintahan',
            ];

            foreach ($indicators as $indicator) {
                AtlasEntry::create([
                    'node_id' => $node->id,
                    'indicator' => $indicator,
                    'score' => rand(50, 95),
                    'source' => 'Badan Pusat Statistik - ' . $province['name'],
                ]);
            }

            // Create 2-4 policies per province
            $numPolicies = rand(2, 4);
            $usedCategories = [];

            for ($j = 0; $j < $numPolicies; $j++) {
                $category = array_rand($policyTemplates);

                // Avoid duplicate categories
                while (in_array($category, $usedCategories) && count($usedCategories) < count($policyTemplates)) {
                    $category = array_rand($policyTemplates);
                }
                $usedCategories[] = $category;

                $templates = $policyTemplates[$category];
                $template = $templates[array_rand($templates)];

                $budget = rand($template['budget_range'][0], $template['budget_range'][1]);
                $status = $statuses[array_rand($statuses)];

                // Avoid rejected status too often
                if (rand(1, 10) > 2) {
                    $status = ['proposed', 'active', 'completed'][array_rand(['proposed', 'active', 'completed'])];
                }

                $policy = Policy::create([
                    'node_id' => $node->id,
                    'title' => sprintf($template['title'], $province['name']),
                    'description' => sprintf($template['desc'], $province['name']),
                    'category' => $category,
                    'budget' => $budget,
                    'implementation_date' => now()->addMonths(rand(1, 24))->format('Y-m-d'),
                    'status' => $status,
                    'budget_breakdown' => $this->generateBudgetBreakdown($budget, $category),
                ]);

                $allPolicies->push($policy);

                // Create 5-15 reviews per policy
                $numReviews = rand(5, 15);
                $reviewers = $users->shuffle()->take($numReviews);

                foreach ($reviewers as $reviewer) {
                    $score = $this->generateRealisticScore($category, $status);
                    $sentiment = $score >= 70 ? 'positive' : ($score >= 40 ? 'neutral' : 'negative');

                    PolicyReview::create([
                        'policy_id' => $policy->id,
                        'user_id' => $reviewer->id,
                        'score' => $score,
                        'comment' => $this->generateComment($score, $category, $province['name']),
                        'sentiment' => $sentiment,
                    ]);
                }

                // Generate AI recommendation
                $this->generateAiRecommendation($policy);
            }
        }

        // Create policy history for major provinces
        $majorProvinces = ['DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'Sumatera Utara', 'Sulawesi Selatan'];
        foreach ($nodes as $node) {
            $provinceName = str_replace('Pusat Kebijakan Provinsi ', '', $node->name);
            if (in_array($provinceName, $majorProvinces)) {
                for ($year = 2019; $year <= 2023; $year++) {
                    PolicyHistory::create([
                        'node_id' => $node->id,
                        'title' => 'Program Pembangunan ' . $provinceName . ' Tahun ' . $year,
                        'description' => 'Program pembangunan komprehensif untuk meningkatkan kesejahteraan masyarakat ' . $provinceName . '.',
                        'year' => $year,
                        'budget' => rand(1000000000000, 10000000000000),
                        'outcome' => ['successful', 'partially_successful', 'successful', 'successful'][rand(0, 3)],
                        'impact_summary' => 'Berhasil meningkatkan berbagai indikator pembangunan di ' . $provinceName . '.',
                    ]);
                }
            }
        }

        // Create Labs for discussion
        $labTopics = [
            ['title' => 'Forum Ekonomi Digital Indonesia', 'desc' => 'Diskusi kebijakan ekonomi digital dan startup nation.'],
            ['title' => 'Forum Lingkungan Hidup Berkelanjutan', 'desc' => 'Pembahasan kebijakan lingkungan dan perubahan iklim.'],
            ['title' => 'Forum Pendidikan Masa Depan', 'desc' => 'Diskusi transformasi pendidikan di era digital.'],
            ['title' => 'Forum Kesehatan Nasional', 'desc' => 'Pembahasan kebijakan kesehatan dan ketahanan pandemi.'],
            ['title' => 'Forum Anti-Korupsi', 'desc' => 'Diskusi strategi pemberantasan korupsi di Indonesia.'],
        ];

        foreach ($labTopics as $topic) {
            $lab = Lab::create([
                'node_id' => $nodes->random()->id,
                'user_id' => $users->random()->id,
                'title' => $topic['title'],
                'description' => $topic['desc'],
            ]);

            // Create posts
            for ($k = 0; $k < rand(5, 10); $k++) {
                LabPost::create([
                    'lab_id' => $lab->id,
                    'user_id' => $users->random()->id,
                    'content' => 'Pendapat konstruktif tentang ' . $topic['title'] . '. Kita perlu memperhatikan berbagai aspek untuk mencapai hasil optimal.',
                ]);
            }
        }

        // Create simulation results - lebih banyak data simulasi
        $indicators = ['Indeks Demokrasi', 'Tingkat Partisipasi Pemilih', 'Transparansi Anggaran', 'Akuntabilitas Publik', 'Kebebasan Sipil', 'Efektivitas Pemerintahan'];

        foreach ($nodes as $node) {
            // 1-3 simulasi per node
            $numSimulations = rand(1, 3);
            for ($sim = 0; $sim < $numSimulations; $sim++) {
                $indicator = $indicators[array_rand($indicators)];
                $oldScore = rand(50, 85);
                $changePercent = rand(-15, 25);
                $newScore = min(100, max(0, $oldScore + ($oldScore * $changePercent / 100)));

                SimulationResult::create([
                    'node_id' => $node->id,
                    'user_id' => $users->random()->id,
                    'indicator' => $indicator,
                    'old_score' => $oldScore,
                    'new_score' => round($newScore, 1),
                    'change_percent' => $changePercent,
                ]);
            }
        }

        // Create audit logs
        foreach (range(1, 20) as $i) {
            AuditLog::create([
                'user_id' => $users->random()->id,
                'action' => ['create', 'update', 'delete', 'review'][rand(0, 3)],
                'table_name' => ['policies', 'nodes', 'labs'][rand(0, 2)],
            ]);
        }
    }

    private function generateBudgetBreakdown(int $budget, string $category): array
    {
        $breakdowns = [
            'Infrastruktur' => ['konstruksi' => 0.6, 'material' => 0.25, 'pengawasan' => 0.15],
            'Pendidikan' => ['beasiswa' => 0.5, 'fasilitas' => 0.3, 'operasional' => 0.2],
            'Kesehatan' => ['pembangunan' => 0.5, 'peralatan' => 0.35, 'sdm' => 0.15],
            'Lingkungan' => ['program' => 0.6, 'monitoring' => 0.25, 'sosialisasi' => 0.15],
            'Ekonomi' => ['modal' => 0.5, 'pelatihan' => 0.3, 'pendampingan' => 0.2],
            'Pertanian' => ['infrastruktur' => 0.5, 'subsidi' => 0.35, 'penyuluhan' => 0.15],
            'Pariwisata' => ['infrastruktur' => 0.5, 'promosi' => 0.3, 'pelatihan' => 0.2],
            'Teknologi' => ['Hardware' => 0.4, 'Software' => 0.35, 'SDM' => 0.25],
        ];

        $breakdown = $breakdowns[$category] ?? ['umum' => 1.0];
        $result = [];

        foreach ($breakdown as $key => $ratio) {
            $result[$key] = (int) ($budget * $ratio);
        }

        return $result;
    }

    private function generateRealisticScore(string $category, string $status): int
    {
        // Base score tergantung kategori
        $baseScores = [
            'Pendidikan' => 75,
            'Kesehatan' => 70,
            'Infrastruktur' => 68,
            'Lingkungan' => 60,
            'Ekonomi' => 65,
            'Pertanian' => 70,
            'Pariwisata' => 72,
            'Teknologi' => 75,
        ];

        $base = $baseScores[$category] ?? 65;

        // Adjustment berdasarkan status
        if ($status === 'completed') {
            $base += rand(5, 15);
        } elseif ($status === 'rejected') {
            $base -= rand(20, 35);
        }

        // Add random variation
        $score = $base + rand(-15, 15);

        return max(10, min(100, $score));
    }

    private function generateComment(int $score, string $category, string $province): string
    {
        if ($score >= 85) {
            $comments = [
                "Sangat mendukung kebijakan ini untuk kemajuan $province!",
                "Kebijakan yang sangat baik, semoga terlaksana dengan baik.",
                "Ini yang dibutuhkan masyarakat $province, luar biasa!",
                "Apresiasi tinggi untuk program ini, sangat bermanfaat.",
            ];
        } elseif ($score >= 70) {
            $comments = [
                "Bagus, tapi perlu memperhatikan aspek keberlanjutan.",
                "Setuju dengan kebijakan ini, semoga implementasinya lancar.",
                "Cukup baik, tapi perlu transparansi dalam pelaksanaan.",
                "Mendukung, namun perlu pengawasan ketat agar tidak ada korupsi.",
            ];
        } elseif ($score >= 50) {
            $comments = [
                "Ada potensi bagus, tapi masih perlu perbaikan dalam beberapa aspek.",
                "Perlu dikaji ulang beberapa poin yang kurang jelas.",
                "Konsepnya cukup baik, tapi implementasinya perlu diperjelas.",
                "Netral. Saya butuh lebih banyak informasi tentang dampaknya.",
            ];
        } else {
            $comments = [
                "Tidak setuju dengan kebijakan ini, terlalu memberatkan masyarakat.",
                "Kebijakan ini perlu ditinjau ulang, dampak negatifnya terlalu besar.",
                "Saya menolak kebijakan ini, ada cara lain yang lebih baik.",
                "Ini bukan prioritas yang tepat untuk $province saat ini.",
            ];
        }

        return $comments[array_rand($comments)];
    }

    private function generateAiRecommendation(Policy $policy): void
    {
        $reviews = $policy->reviews;

        if ($reviews->count() < 1) {
            return;
        }

        $averageScore = round($reviews->avg('score'), 1);
        $totalReviews = $reviews->count();

        // Count sentiments
        $sentiments = [
            'positive' => $reviews->where('sentiment', 'positive')->count(),
            'neutral' => $reviews->where('sentiment', 'neutral')->count(),
            'negative' => $reviews->where('sentiment', 'negative')->count(),
        ];

        // Determine recommendation
        if ($averageScore >= 91) {
            $recommendation = 'approve';
        } elseif ($averageScore >= 50) {
            $recommendation = 'revise';
        } else {
            $recommendation = 'reject';
        }

        // Collect common criticisms from negative reviews
        $negativeComments = $reviews->where('sentiment', 'negative')->pluck('comment')->toArray();
        $criticisms = [];
        $keywords = ['korupsi', 'transparansi', 'mahal', 'tidak efektif', 'terlalu', 'kurang', 'perlu', 'masalah'];

        foreach ($negativeComments as $comment) {
            foreach ($keywords as $keyword) {
                if (stripos($comment, $keyword) !== false) {
                    $criticisms[] = ucfirst($keyword);
                }
            }
        }
        $criticisms = array_unique($criticisms);

        // Generate AI analysis text
        $analysisText = $this->generateAnalysisText($policy, $averageScore, $sentiments, $recommendation);

        AiRecommendation::create([
            'policy_id' => $policy->id,
            'average_score' => $averageScore,
            'total_reviews' => $totalReviews,
            'recommendation' => $recommendation,
            'ai_analysis' => $analysisText,
            'sentiment_breakdown' => $sentiments,
            'common_criticisms' => array_values($criticisms),
            'analyzed_at' => now(),
        ]);
    }

    private function generateAnalysisText(Policy $policy, float $averageScore, array $sentiments, string $recommendation): string
    {
        $status = match ($recommendation) {
            'approve' => '✅ DISETUJUI',
            'revise' => '⚠️ PERLU REVISI',
            'reject' => '❌ DITOLAK',
        };

        $text = "📊 ANALISIS AI - KEBIJAKAN: {$policy->title}\n\n";
        $text .= "Status Rekomendasi: {$status}\n";
        $text .= "Skor Rata-rata: {$averageScore}%\n\n";

        $text .= "📈 Breakdown Sentimen:\n";
        $text .= "• Positif: {$sentiments['positive']} responden\n";
        $text .= "• Netral: {$sentiments['neutral']} responden\n";
        $text .= "• Negatif: {$sentiments['negative']} responden\n\n";

        if ($recommendation === 'approve') {
            $text .= "✅ KESIMPULAN:\n";
            $text .= "Kebijakan ini mendapat dukungan kuat dari masyarakat dengan skor di atas 91%. ";
            $text .= "Mayoritas responden memberikan tanggapan positif. ";
            $text .= "Rekomendasi: LANJUTKAN implementasi dengan tetap memperhatikan masukan dari masyarakat.";
        } elseif ($recommendation === 'revise') {
            $text .= "⚠️ KESIMPULAN:\n";
            $text .= "Kebijakan ini mendapat dukungan moderat namun belum mencapai konsensus. ";
            $text .= "Diperlukan revisi untuk mengatasi kekhawatiran masyarakat. ";
            $text .= "Rekomendasi: REVISI kebijakan berdasarkan masukan, terutama dari responden dengan kritik konstruktif.";
        } else {
            $text .= "❌ KESIMPULAN:\n";
            $text .= "Kebijakan ini mendapat penolakan signifikan dari masyarakat. ";
            $text .= "Skor rata-rata di bawah 50% menunjukkan kekhawatiran serius. ";
            $text .= "Rekomendasi: TINJAU ULANG secara menyeluruh atau pertimbangkan alternatif kebijakan lain.";
        }

        return $text;
    }
}
