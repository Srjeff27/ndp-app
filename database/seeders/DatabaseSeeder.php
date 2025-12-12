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
            'email' => 'admin@ndp.test',
            'password' => bcrypt('password'),
        ]);
        $globalAdmin->assignRole('global_admin');

        $nodeAdmin1 = User::factory()->create([
            'name' => 'Prof. Ahmad Rizki',
            'email' => 'node@ndp.test',
            'password' => bcrypt('password'),
        ]);
        $nodeAdmin1->assignRole('node_admin');

        $nodeAdmin2 = User::factory()->create([
            'name' => 'Dr. Maria Santos',
            'email' => 'maria@ndp.test',
            'password' => bcrypt('password'),
        ]);
        $nodeAdmin2->assignRole('node_admin');

        $student1 = User::factory()->create([
            'name' => 'Alex Chen',
            'email' => 'student@ndp.test',
            'password' => bcrypt('password'),
        ]);
        $student1->assignRole('student');

        $student2 = User::factory()->create([
            'name' => 'Priya Sharma',
            'email' => 'priya@ndp.test',
            'password' => bcrypt('password'),
        ]);
        $student2->assignRole('student');

        $student3 = User::factory()->create([
            'name' => 'Carlos Rodriguez',
            'email' => 'carlos@ndp.test',
            'password' => bcrypt('password'),
        ]);
        $student3->assignRole('student');

        // Create governance nodes
        $node1 = Node::create([
            'name' => 'Jakarta Governance Lab',
            'institution' => 'University of Indonesia - Political Science',
            'country' => 'Indonesia',
            'lat' => -6.2088,
            'lng' => 106.8456,
            'user_id' => $nodeAdmin1->id,
        ]);

        $node2 = Node::create([
            'name' => 'Manila Democracy Center',
            'institution' => 'University of the Philippines - Public Administration',
            'country' => 'Philippines',
            'lat' => 14.5995,
            'lng' => 120.9842,
            'user_id' => $nodeAdmin2->id,
        ]);

        $node3 = Node::create([
            'name' => 'Singapore Policy Institute',
            'institution' => 'National University of Singapore',
            'country' => 'Singapore',
            'lat' => 1.3521,
            'lng' => 103.8198,
            'user_id' => $globalAdmin->id,
        ]);

        $node4 = Node::create([
            'name' => 'Bangkok Civic Research Hub',
            'institution' => 'Chulalongkorn University - Political Science',
            'country' => 'Thailand',
            'lat' => 13.7563,
            'lng' => 100.5018,
            'user_id' => $nodeAdmin1->id,
        ]);

        $node5 = Node::create([
            'name' => 'Kuala Lumpur Democracy Lab',
            'institution' => 'University of Malaya',
            'country' => 'Malaysia',
            'lat' => 3.1390,
            'lng' => 101.6869,
            'user_id' => $nodeAdmin2->id,
        ]);

        // Create atlas entries (governance indicators)
        $indicators = [
            'Government Transparency',
            'Civic Participation',
            'Electoral Integrity',
            'Freedom of Expression',
            'Rule of Law',
            'Anti-Corruption Measures',
            'Digital Democracy',
            'Public Accountability',
        ];

        foreach ([$node1, $node2, $node3, $node4, $node5] as $node) {
            foreach ($indicators as $indicator) {
                AtlasEntry::create([
                    'node_id' => $node->id,
                    'indicator' => $indicator,
                    'score' => rand(60, 95),
                    'source' => 'World Governance Index 2024',
                    'meta' => json_encode([
                        'year' => 2024,
                        'methodology' => 'Expert Survey',
                        'sample_size' => rand(500, 2000),
                    ]),
                ]);
            }
        }

        // Create Civic Labs discussions
        $lab1 = Lab::create([
            'title' => 'Improving Digital Voting Systems',
            'description' => 'How can we leverage blockchain and cryptographic technologies to create more secure and transparent digital voting systems? This discussion explores the technical, legal, and social challenges of implementing e-voting in democratic processes.',
            'node_id' => $node1->id,
            'user_id' => $globalAdmin->id,
        ]);

        $lab2 = Lab::create([
            'title' => 'Combating Misinformation in Elections',
            'description' => 'What strategies can governments and civil society adopt to counter the spread of misinformation during electoral campaigns while preserving freedom of speech? Let\'s discuss fact-checking mechanisms, media literacy programs, and regulatory frameworks.',
            'node_id' => $node2->id,
            'user_id' => $nodeAdmin1->id,
        ]);

        $lab3 = Lab::create([
            'title' => 'Youth Participation in Policy Making',
            'description' => 'How can we create meaningful opportunities for young people to participate in policy-making processes? This discussion focuses on innovative engagement models, digital platforms, and institutional reforms.',
            'node_id' => $node3->id,
            'user_id' => $student1->id,
        ]);

        $lab4 = Lab::create([
            'title' => 'Transparency in Public Procurement',
            'description' => 'What are the best practices for ensuring transparency and accountability in government procurement processes? Let\'s explore open contracting standards, whistleblower protection, and digital monitoring tools.',
            'node_id' => null,
            'user_id' => $nodeAdmin2->id,
        ]);

        $lab5 = Lab::create([
            'title' => 'Climate Democracy and Citizen Assemblies',
            'description' => 'How can citizen assemblies and participatory budgeting be used to address climate change at the local level? This discussion examines case studies from around the world and explores scalability challenges.',
            'node_id' => $node4->id,
            'user_id' => $student2->id,
        ]);

        // Create lab posts (comments/discussions)
        LabPost::create([
            'lab_id' => $lab1->id,
            'user_id' => $student1->id,
            'content' => 'I think blockchain-based voting could be a game-changer, but we need to address the digital divide first. Not everyone has equal access to technology.',
        ]);

        LabPost::create([
            'lab_id' => $lab1->id,
            'user_id' => $nodeAdmin1->id,
            'content' => 'Great point! Estonia has been pioneering e-voting since 2005. Their experience shows that robust digital identity infrastructure is crucial. We should study their model.',
        ]);

        LabPost::create([
            'lab_id' => $lab1->id,
            'user_id' => $student2->id,
            'content' => 'Security is my main concern. What happens if the system is hacked? Paper ballots have a physical audit trail. How do we ensure the same level of verifiability with digital systems?',
        ]);

        LabPost::create([
            'lab_id' => $lab2->id,
            'user_id' => $globalAdmin->id,
            'content' => 'Media literacy education should start in schools. Finland has successfully integrated critical thinking about information sources into their curriculum.',
        ]);

        LabPost::create([
            'lab_id' => $lab2->id,
            'user_id' => $student3->id,
            'content' => 'I agree, but we also need real-time fact-checking during campaigns. Social media platforms should be held accountable for viral misinformation.',
        ]);

        LabPost::create([
            'lab_id' => $lab3->id,
            'user_id' => $nodeAdmin2->id,
            'content' => 'Youth councils at the municipal level have shown promising results in several European cities. They give young people direct input into budget allocation.',
        ]);

        LabPost::create([
            'lab_id' => $lab3->id,
            'user_id' => $student1->id,
            'content' => 'Digital platforms like Decidim (used in Barcelona) are excellent tools for participatory democracy. They make it easy for young people to propose and vote on policies.',
        ]);

        LabPost::create([
            'lab_id' => $lab4->id,
            'user_id' => $nodeAdmin1->id,
            'content' => 'The Open Contracting Data Standard (OCDS) is a great framework. It makes procurement data machine-readable and easier to analyze for corruption patterns.',
        ]);

        LabPost::create([
            'lab_id' => $lab5->id,
            'user_id' => $student2->id,
            'content' => 'Paris has successfully used citizen assemblies to develop climate action plans. The key is ensuring diverse representation and providing participants with expert resources.',
        ]);

        LabPost::create([
            'lab_id' => $lab5->id,
            'user_id' => $globalAdmin->id,
            'content' => 'Participatory budgeting in Porto Alegre, Brazil, is another inspiring example. Citizens directly decide how to allocate portions of municipal budgets to climate initiatives.',
        ]);

        // Create simulation results
        SimulationResult::create([
            'node_id' => $node1->id,
            'user_id' => $globalAdmin->id,
            'indicator' => 'Government Transparency',
            'old_score' => 75.0,
            'new_score' => 82.5,
            'change_percent' => 10,
        ]);

        SimulationResult::create([
            'node_id' => $node1->id,
            'user_id' => $student1->id,
            'indicator' => 'Digital Democracy',
            'old_score' => 68.0,
            'new_score' => 74.8,
            'change_percent' => 10,
        ]);

        SimulationResult::create([
            'node_id' => $node2->id,
            'user_id' => $nodeAdmin1->id,
            'indicator' => 'Civic Participation',
            'old_score' => 72.0,
            'new_score' => 64.8,
            'change_percent' => -10,
        ]);

        SimulationResult::create([
            'node_id' => $node3->id,
            'user_id' => $student2->id,
            'indicator' => 'Electoral Integrity',
            'old_score' => 88.0,
            'new_score' => 92.4,
            'change_percent' => 5,
        ]);

        SimulationResult::create([
            'node_id' => $node4->id,
            'user_id' => $nodeAdmin2->id,
            'indicator' => 'Anti-Corruption Measures',
            'old_score' => 65.0,
            'new_score' => 71.5,
            'change_percent' => 10,
        ]);

        SimulationResult::create([
            'node_id' => $node5->id,
            'user_id' => $student3->id,
            'indicator' => 'Public Accountability',
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
            'after' => json_encode(['name' => 'Singapore Policy Institute']),
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
