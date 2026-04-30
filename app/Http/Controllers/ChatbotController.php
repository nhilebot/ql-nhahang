<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected array $chatModels = [
        'gemini-2.5-flash',
        'gemma-3-1b-it',
    ];

    protected function generateContent(string $apiKey, array $payload): array
    {
        $lastData = [];

        foreach ($this->chatModels as $model) {
            $response = Http::timeout(25)
                ->acceptJson()
                ->asJson()
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $apiKey,
                    $payload
                );

            $data = $response->json();
            $reply = is_array($data) ? $this->parseGeminiReply($data) : null;

            if ($response->successful() && $reply !== null) {
                return [
                    'reply' => $reply,
                    'model' => $model,
                    'data' => $data,
                ];
            }

            $lastData = is_array($data) ? $data : ['error' => ['message' => 'Unknown Gemini response']];

            if (($lastData['error']['status'] ?? null) !== 'UNAVAILABLE') {
                break;
            }
        }

        return [
            'reply' => null,
            'model' => null,
            'data' => $lastData,
        ];
    }

    protected function buildGeminiContents(array $history, string $message): array
    {
        $contents = [];

        foreach ($history as $item) {
            $role = ($item['role'] ?? 'user') === 'model' ? 'model' : 'user';
            $text = trim((string) ($item['text'] ?? ''));

            if ($text === '') {
                continue;
            }

            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => $text],
                ],
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $message],
            ],
        ];

        return $contents;
    }

    protected function parseGeminiReply(array $data): ?string
    {
        $parts = $data['candidates'][0]['content']['parts'] ?? [];

        if (!is_array($parts) || $parts === []) {
            return null;
        }

        $texts = [];

        foreach ($parts as $part) {
            $text = trim((string) ($part['text'] ?? ''));

            if ($text !== '') {
                $texts[] = $text;
            }
        }

        return $texts === [] ? null : implode("\n", $texts);
    }

    protected function providerErrorReply(array $data, string $message): string
    {
        $status = $data['error']['status'] ?? null;

        if ($status === 'RESOURCE_EXHAUSTED') {
            return 'Gemini API đã nhận request nhưng tài khoản hiện đã hết quota hoặc chưa được cấp quota. Bạn kiểm tra billing, hạn mức hoặc thử lại sau.';
        }

        if ($status === 'PERMISSION_DENIED') {
            return 'Gemini API key chưa hợp lệ hoặc project chưa được cấp quyền dùng Gemini API. Bạn kiểm tra lại API key và cấu hình project trên Google AI Studio.';
        }

        return $this->fallbackReply($message);
    }

    protected function fallbackReply(string $message): string
    {
        $normalized = mb_strtolower(trim($message));

        if ($normalized === '') {
            return 'Bạn hãy nhập câu hỏi về thực đơn, đặt bàn, giờ mở cửa hoặc liên hệ để tôi hỗ trợ.';
        }

        if (str_contains($normalized, 'thuc don') || str_contains($normalized, 'thực đơn') || str_contains($normalized, 'menu') || str_contains($normalized, 'món')) {
            return 'Bạn có thể xem thực đơn tại trang Menu của nhà hàng. Nếu muốn, hãy nói tên món hoặc loại món để tôi gợi ý nhanh hơn.';
        }

        if (str_contains($normalized, 'dat ban') || str_contains($normalized, 'đặt bàn') || str_contains($normalized, 'reservation') || str_contains($normalized, 'book')) {
            return 'Để đặt bàn, bạn vào trang Đặt bàn, chọn thời gian, số lượng khách và điền thông tin liên hệ. Nếu cần, tôi có thể hướng dẫn từng bước.';
        }

        if (str_contains($normalized, 'gio mo cua') || str_contains($normalized, 'giờ mở cửa') || str_contains($normalized, 'mấy giờ') || str_contains($normalized, 'open')) {
            return 'Bạn vui lòng xem mục liên hệ hoặc phần thông tin trên trang chủ để kiểm tra giờ mở cửa mới nhất của nhà hàng.';
        }

        if (str_contains($normalized, 'lien he') || str_contains($normalized, 'liên hệ') || str_contains($normalized, 'so dien thoai') || str_contains($normalized, 'số điện thoại') || str_contains($normalized, 'dia chi') || str_contains($normalized, 'địa chỉ')) {
            return 'Bạn có thể xem thông tin liên hệ trực tiếp trên website của nhà hàng. Nếu cần, tôi có thể hướng dẫn bạn tới đúng trang liên hệ hoặc đặt bàn.';
        }

        return 'Hiện AI nâng cao chưa được cấu hình trên môi trường này, nhưng tôi vẫn có thể hỗ trợ các câu hỏi cơ bản về thực đơn, đặt bàn, giờ mở cửa và liên hệ.';
    }

    public function testAI()
    {
        $apiKey = trim((string) env('GEMINI_API_KEY'));

        if ($apiKey === '') {
            return response()->json([
                'error' => 'Thiếu GEMINI_API_KEY trong file .env',
            ], 500);
        }

        return response()->json(
            $this->generateContent($apiKey, [
                'systemInstruction' => [
                    'parts' => [
                        ['text' => 'Bạn là trợ lý AI của nhà hàng. Trả lời ngắn gọn, hữu ích, bằng tiếng Việt.'],
                    ],
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => 'Xin chào'],
                        ],
                    ],
                ],
            ])
        );
    }

    // 🔥 HÀM QUAN TRỌNG CHO CHATBOX
    public function ask(Request $request)
    {
        $message = trim((string) $request->input('message'));
        $history = $request->input('history', []);
        $apiKey = trim((string) env('GEMINI_API_KEY'));

        if ($message === '') {
            return response()->json([
                'reply' => $this->fallbackReply($message),
            ]);
        }

        if ($apiKey === '') {
            return response()->json([
                'reply' => $this->fallbackReply($message),
                'fallback' => true,
            ]);
        }

        $result = $this->generateContent($apiKey, [
            'systemInstruction' => [
                'parts' => [
                    [
                        'text' => 'Bạn là trợ lý AI của nhà hàng. Hãy trả lời bằng tiếng Việt, ngắn gọn, đúng trọng tâm. Ưu tiên hỗ trợ khách về thực đơn, đặt bàn, giờ mở cửa và liên hệ.',
                    ],
                ],
            ],
            'contents' => $this->buildGeminiContents(is_array($history) ? $history : [], $message),
        ]);

        if ($result['reply'] !== null) {
            return response()->json([
                'reply' => $result['reply'],
                'model' => $result['model'],
            ]);
        }

        return response()->json([
            'reply' => is_array($result['data']) ? $this->providerErrorReply($result['data'], $message) : $this->fallbackReply($message),
            'fallback' => true,
            'provider_error' => $result['data'],
        ]);
    }
}