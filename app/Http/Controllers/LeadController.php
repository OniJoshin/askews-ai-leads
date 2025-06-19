<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class LeadController extends Controller
{
    public function showForm()
    {
        return view('pages.form');
    }

    public function submitForm(Request $request)
    {
        $data = $request->only(['name', 'email', 'type', 'department', 'message', 'quote', 'affordability']);

        // Simulate SharpSpring lead creation
        Http::fake([
            'https://sharpspring.local/api/leads' => Http::response(['status' => 'received'], 200),
        ]);

        $conversation = [
            ['role' => 'user', 'message' => $data['message']],
        ];

        $response = $this->generateAIResponse($conversation, $data);

        $conversation[] = ['role' => 'assistant', 'message' => $response];
        session([
            'conversation' => $conversation,
            'context' => [
                'type' => $data['type'],
                'department' => $data['department'],
                'name' => $data['name'],
                'email' => $data['email'],
            ]
        ]);        

        return view('pages.conversation', compact('data', 'response', 'conversation'))->with('showProgress', true);
    }


    public function handleReply(Request $request)
    {
        $conversation = session('conversation', []);
        $reply = $request->input('reply');

        $conversation[] = ['role' => 'user', 'message' => $reply];
        $context = session('context', []);

        $response = $this->generateAIResponse($conversation, $context);

        $conversation[] = ['role' => 'assistant', 'message' => $response];
        session(['conversation' => $conversation]);

        return view('pages.conversation', compact('conversation', 'response'))->with('showProgress', false);
    }

    private function generateAIResponse(array $conversation, array $data = []): string
    {
        $systemPrompt = "You are a helpful legal assistant for Askews Legal LLP using UK English.

        You are responding on behalf of the " . ($data['department'] ?? 'Legal') . " department to a " . ($data['type'] ?? 'client') . ".

        The client's name is: " . ($data['name'] ?? '[unknown]') . ".
        Use the client's first name when responding, if known.
        The client's email address is already known, so do not ask for it again.

        Only ask for any missing details necessary to book a consultation — such as their phone number or a brief case summary, if not yet provided.

        When the client has provided enough info, respond with:

        \"You're now ready to book a consultation. Please use the link below to choose a time that works for you:
        https://askewslegal.co.uk/consultation-booking\"

        If the client's message is sensitive or very personal, add:
        \"If you'd prefer not to talk to the AI, you can contact us directly by phone or email.\"

        Always sign off as: Askews Legal LLP Team.
        ";


        // Build message history
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($conversation as $entry) {
            $messages[] = [
                'role' => $entry['role'],
                'content' => $entry['message'],
            ];
        }

        // For first message, inject extra context if available
        if (!empty($data)) {
            $messages[] = [
                'role' => 'user',
                'content' => "Client type: " . ucfirst($data['type']) . "\n"
                        . "Department: " . $data['department'] . "\n"
                        . "Message: " . $data['message']
            ];
        }

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4', // or 'gpt-3.5-turbo'
                    'messages' => $messages,
                    'max_tokens' => 500,
                ]);

            if ($response->successful()) {
                return $response->json('choices')[0]['message']['content'] ?? 'Empty response.';
            }

            \Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return 'OpenAI API error: ' . $response->status();
        } catch (\Exception $e) {
            \Log::error('OpenAI Exception: ' . $e->getMessage());
            return 'Exception: ' . $e->getMessage();
        }
    }




}
