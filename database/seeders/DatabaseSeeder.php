<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Node;
use App\Models\AtlasEntry;
use App\Models\Lab;
use App\Models\LabPost;
use App\Models\SimulationResult;
use App\Models\AuditLog;
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
}
