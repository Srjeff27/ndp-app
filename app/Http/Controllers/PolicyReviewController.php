<?php

namespace App\Http\Controllers;

use App\Models\AiRecommendation;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyReviewController extends Controller
{
    /**
     * Store or update a policy review
     */
    public function store(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Analisis sentimen sederhana berdasarkan skor
        $sentiment = $this->analyzeSentiment($validated['comment'] ?? '', $validated['score']);

        // Update or create review
        $review = $policy->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'score' => $validated['score'],
                'comment' => $validated['comment'],
                'sentiment' => $sentiment,
            ]
        );

        // Generate AI recommendation jika sudah ada minimal 3 review
        if ($policy->reviews()->count() >= 3) {
            $this->generateAiRecommendation($policy);
        }

        return redirect()->back()->with('success', 'Penilaian Anda berhasil disimpan');
    }

    /**
     * Analyze sentiment based on comment and score
     */
    private function analyzeSentiment(string $comment, int $score): string
    {
        // Kata-kata positif dan negatif dalam bahasa Indonesia
        $positiveWords = ['bagus', 'baik', 'setuju', 'mendukung', 'sangat', 'luar biasa', 'hebat', 'mantap', 'oke', 'sip'];
        $negativeWords = ['buruk', 'jelek', 'tidak', 'jangan', 'tolak', 'menolak', 'gagal', 'salah', 'kurang'];

        $commentLower = strtolower($comment);
        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($positiveWords as $word) {
            if (str_contains($commentLower, $word)) {
                $positiveCount++;
            }
        }

        foreach ($negativeWords as $word) {
            if (str_contains($commentLower, $word)) {
                $negativeCount++;
            }
        }

        // Kombinasi skor dan analisis kata
        if ($score >= 75 && $positiveCount > $negativeCount) {
            return 'positive';
        } elseif ($score < 50 || $negativeCount > $positiveCount) {
            return 'negative';
        }

        return 'neutral';
    }

    /**
     * Generate AI recommendation for a policy
     */
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

        // Kritik yang sering muncul (ambil 5 teratas)
        $commonCriticisms = $reviews
            ->whereNotNull('comment')
            ->sortByDesc('created_at')
            ->take(5)
            ->pluck('comment')
            ->toArray();

        // Tentukan rekomendasi
        $recommendation = $avgScore >= 91 ? 'approve' : ($avgScore >= 50 ? 'revise' : 'reject');

        // Generate AI analysis text
        $aiAnalysis = $this->generateAnalysisText($avgScore, $totalReviews, $sentimentBreakdown, $recommendation);

        // Update or create AI recommendation
        AiRecommendation::updateOrCreate(
            ['policy_id' => $policy->id],
            [
                'average_score' => round($avgScore, 2),
                'total_reviews' => $totalReviews,
                'recommendation' => $recommendation,
                'ai_analysis' => $aiAnalysis,
                'sentiment_breakdown' => $sentimentBreakdown,
                'common_criticisms' => $commonCriticisms,
                'analyzed_at' => now(),
            ]
        );
    }

    /**
     * Generate AI analysis text based on data
     */
    private function generateAnalysisText(float $avgScore, int $totalReviews, array $sentiment, string $recommendation): string
    {
        $positivePercent = $totalReviews > 0 ? round(($sentiment['positive'] / $totalReviews) * 100) : 0;
        $negativePercent = $totalReviews > 0 ? round(($sentiment['negative'] / $totalReviews) * 100) : 0;

        if ($recommendation === 'approve') {
            return "✅ REKOMENDASI: LANJUTKAN IMPLEMENTASI\n\n".
                "Kebijakan ini mendapat dukungan kuat dari masyarakat dengan skor rata-rata {$avgScore}% dari {$totalReviews} penilaian. ".
                "{$positivePercent}% responden memberikan sentimen positif. ".
                "Masyarakat menilai kebijakan ini layak untuk dilaksanakan.\n\n".
                'Saran: Lanjutkan dengan perencanaan detail dan pastikan transparansi dalam implementasi.';
        } elseif ($recommendation === 'revise') {
            return "⚠️ REKOMENDASI: REVISI DIPERLUKAN\n\n".
                "Kebijakan ini mendapat respon campuran dengan skor rata-rata {$avgScore}% dari {$totalReviews} penilaian. ".
                "{$negativePercent}% responden memberikan sentimen negatif. ".
                "Diperlukan perbaikan untuk meningkatkan dukungan masyarakat.\n\n".
                'Saran: Tinjau kembali aspek-aspek yang dikritik masyarakat dan lakukan dialog publik untuk mencari solusi bersama.';
        } else {
            return "❌ REKOMENDASI: TINJAU ULANG ATAU BATALKAN\n\n".
                "Kebijakan ini mendapat penolakan dari masyarakat dengan skor rata-rata {$avgScore}% dari {$totalReviews} penilaian. ".
                "{$negativePercent}% responden memberikan sentimen negatif. ".
                "Mayoritas masyarakat tidak mendukung kebijakan ini.\n\n".
                'Saran: Pertimbangkan untuk membatalkan atau merancang ulang kebijakan dengan pendekatan yang berbeda. '.
                'Lakukan kajian mendalam tentang kebutuhan dan aspirasi masyarakat.';
        }
    }
}
