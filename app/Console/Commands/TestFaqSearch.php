<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Faq;

class TestFaqSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faq:test-search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test FAQ search accuracy with various queries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing FAQ Search Accuracy');
        $this->info('==========================');
        $this->newLine();

        // Test queries
        $testQueries = [
            "How to pay?",
            "Payment methods",
            "How much does it cost?",
            "Book appointment",
            "Cancel booking",
            "Office hours",
            "Contact info",
            "Church location",
            "Service cost",
            "Urgent service"
        ];

        foreach ($testQueries as $query) {
            $this->info("Query: \"$query\"");
            
            // Simulate the search logic
            $queryLower = strtolower(trim($query));
            $queryWords = $this->getQueryWords($queryLower);
            
            $faqs = Faq::active()->ordered()->get();
            
            $scoredFaqs = [];
            foreach ($faqs as $faq) {
                $score = $this->calculateScore($faq, $queryLower, $queryWords);
                if ($score > 0) {
                    $scoredFaqs[] = [
                        'faq' => $faq,
                        'score' => $score
                    ];
                }
            }
            
            usort($scoredFaqs, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });
            
            $topResults = array_slice($scoredFaqs, 0, 3);
            
            if (!empty($topResults) && $topResults[0]['score'] >= 2) {
                $this->line("✓ Found: " . $topResults[0]['faq']->question . " (Score: " . $topResults[0]['score'] . ")");
            } else {
                $this->error("✗ No relevant results found");
            }
            $this->newLine();
        }
    }

    private function getQueryWords(string $query): array
    {
        $stopWords = [
            'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by',
            'is', 'are', 'was', 'were', 'be', 'been', 'have', 'has', 'had', 'do', 'does', 'did',
            'will', 'would', 'could', 'should', 'may', 'might', 'can', 'this', 'that', 'these', 'those',
            'i', 'you', 'he', 'she', 'it', 'we', 'they', 'me', 'him', 'her', 'us', 'them',
            'my', 'your', 'his', 'her', 'its', 'our', 'their', 'how', 'what', 'when', 'where', 'why'
        ];
        
        $words = preg_split('/\s+/', strtolower($query));
        $words = array_map(function($word) {
            return trim($word, '.,!?;:');
        }, $words);
        
        return array_filter($words, function($word) use ($stopWords) {
            return strlen($word) >= 2 && !in_array($word, $stopWords);
        });
    }

    private function calculateScore($faq, string $query, array $queryWords): int
    {
        $score = 0;
        $question = strtolower($faq->question);
        $answer = strtolower($faq->answer);
        $keywords = $faq->keywords ? array_map('strtolower', $faq->keywords) : [];
        
        // Exact phrase match (highest priority)
        if (strpos($question, $query) !== false) {
            $score += 10;
        }
        if (strpos($answer, $query) !== false) {
            $score += 8;
        }
        
        // Word-by-word matching
        foreach ($queryWords as $word) {
            if (strlen($word) < 3) continue;
            
            if (strpos($question, $word) !== false) {
                $score += 3;
            }
            
            if (strpos($answer, $word) !== false) {
                $score += 2;
            }
            
            if (in_array($word, $keywords)) {
                $score += 4;
            }
        }
        
        // Special handling for common variations
        $variations = $this->getWordVariations($queryWords);
        foreach ($variations as $variation) {
            if (strpos($question, $variation) !== false) {
                $score += 2;
            }
            if (strpos($answer, $variation) !== false) {
                $score += 1;
            }
        }
        
        return $score;
    }

    private function getWordVariations(array $words): array
    {
        $variations = [];
        
        foreach ($words as $word) {
            $variations[] = $word;
            
            switch ($word) {
                case 'pay':
                    $variations[] = 'payment';
                    $variations[] = 'paying';
                    $variations[] = 'paid';
                    break;
                case 'book':
                    $variations[] = 'booking';
                    $variations[] = 'reserve';
                    $variations[] = 'reservation';
                    break;
                case 'service':
                    $variations[] = 'services';
                    break;
                case 'cancel':
                    $variations[] = 'cancellation';
                    $variations[] = 'cancelled';
                    break;
                case 'document':
                    $variations[] = 'documents';
                    break;
                case 'contact':
                    $variations[] = 'reach';
                    $variations[] = 'call';
                    break;
                case 'location':
                    $variations[] = 'address';
                    $variations[] = 'where';
                    break;
                case 'hour':
                    $variations[] = 'hours';
                    $variations[] = 'time';
                    break;
                case 'cost':
                    $variations[] = 'price';
                    $variations[] = 'fee';
                    break;
                case 'urgent':
                    $variations[] = 'emergency';
                    $variations[] = 'rush';
                    break;
                case 'blessing':
                    $variations[] = 'bless';
                    break;
                case 'mass':
                    $variations[] = 'masses';
                    break;
            }
        }
        
        return array_unique($variations);
    }
} 