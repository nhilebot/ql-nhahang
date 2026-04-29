<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function testAI()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'HTTP-Referer' => 'http://localhost',
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'openchat/openchat-7b',
            'messages' => [
                ['role' => 'user', 'content' => 'Xin chào']
            ]
        ]);

        return $response->json();
    }

    // 🔥 HÀM QUAN TRỌNG CHO CHATBOX
    public function ask(Request $request)
    {
        $message = $request->input('message');

        $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
    'HTTP-Referer' => 'http://localhost',
    'X-Title' => 'Chatbot Nha Hang',
    'Content-Type' => 'application/json',
])->post('https://openrouter.ai/api/v1/chat/completions', [
    'model' => 'openchat/openchat-7b',
    'messages' => [
        ['role' => 'user', 'content' => $message]
    ]
]);

        $data = $response->json();

        // ✅ trả đúng format cho frontend
        if (isset($data['choices'][0]['message']['content'])) {
            return response()->json([
                'reply' => $data['choices'][0]['message']['content']
            ]);
        }

        // ❗ fallback nếu lỗi
        return response()->json($data);
    }
}