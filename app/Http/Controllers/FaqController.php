<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    /**
     * Handle chatbot query and return relevant answers
     */
    public function chat(Request $request): JsonResponse
    {
        $query = $request->input('query', '');
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a question or query.',
                'suggestions' => $this->getSuggestions()
            ]);
        }

        // Search for relevant FAQs
        $results = $this->searchFaqs($query);
        
        if ($results->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'I couldn\'t find a specific answer to your question. You may send a <a href="/support/tickets/create" class="text-blue-600 underline">support ticket</a> so we can assist you.',
                'message_is_html' => true,
                'suggestions' => [],
                'query' => $query
            ]);
        }

        return response()->json([
            'success' => true,
            'results' => $results,
            'query' => $query
        ]);
    }

    /**
     * Get FAQ suggestions for the chatbot
     */
    public function getSuggestions(): JsonResponse
    {
        $suggestions = [
            [
                'category' => 'Booking',
                'questions' => [
                    'How do I book a service?',
                    'What documents do I need for booking?',
                    'How much does a service cost?',
                    'Can I cancel my booking?'
                ]
            ],
            [
                'category' => 'Services',
                'questions' => [
                    'What services are available?',
                    'What are the service schedules?',
                    'Do you offer urgent services?'
                ]
            ],
            [
                'category' => 'General',
                'questions' => [
                    'What are your office hours?',
                    'How can I contact the parish?',
                    'Where is the church located?'
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Search FAQs based on query
     */
    private function searchFaqs(string $query): \Illuminate\Database\Eloquent\Collection
    {
        $query = strtolower(trim($query));
        
        // First, try exact keyword matches
        $exactMatches = Faq::active()
            ->where(function($q) use ($query) {
                $q->whereRaw('LOWER(question) LIKE ?', ['%' . $query . '%'])
                  ->orWhereRaw('LOWER(answer) LIKE ?', ['%' . $query . '%'])
                  ->orWhereJsonContains('keywords', $query);
            })
            ->ordered()
            ->limit(3)
            ->get();

        if ($exactMatches->isNotEmpty()) {
            return $exactMatches;
        }

        // If no exact matches, try partial matches
        $words = explode(' ', $query);
        $partialMatches = Faq::active()
            ->where(function($q) use ($words) {
                foreach ($words as $word) {
                    if (strlen($word) > 2) { // Only search for words longer than 2 characters
                        $q->where(function($subQ) use ($word) {
                            $subQ->whereRaw('LOWER(question) LIKE ?', ['%' . $word . '%'])
                                 ->orWhereRaw('LOWER(answer) LIKE ?', ['%' . $word . '%']);
                        });
                    }
                }
            })
            ->ordered()
            ->limit(3)
            ->get();

        return $partialMatches;
    }

    /**
     * Get FAQ categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = Faq::active()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Get FAQs by category
     */
    public function getByCategory(Request $request): JsonResponse
    {
        $category = $request->input('category', 'general');
        
        $faqs = Faq::active()
            ->byCategory($category)
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'category' => $category,
            'faqs' => $faqs
        ]);
    }

    /**
     * Get all active FAQs for the chatbot
     */
    public function getAll(): JsonResponse
    {
        $faqs = Faq::active()
            ->ordered()
            ->get(['id', 'question', 'answer', 'category']);

        return response()->json([
            'success' => true,
            'faqs' => $faqs
        ]);
    }
} 