<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextAnalysisResult; // เรียกใช้โมเดลสำหรับตารางใหม่
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TextAnalysisController extends Controller

{
    public function showInputTextForm()
    {
        $positiveCount = TextAnalysisResult::where('polarity', 'positive')->count();
        $neutralCount = TextAnalysisResult::where('polarity', 'neutral')->count();
        $negativeCount = TextAnalysisResult::where('polarity', 'negative')->count();
        $total = $positiveCount + $neutralCount + $negativeCount;

        return response()->json([
            'polarityCounts' => [
                'positive' => $positiveCount,
                'neutral' => $neutralCount,
                'negative' => $negativeCount,
            ],
            'total' => $total,
        ]);
    }

    // ฟังก์ชันสำหรับลบข้อมูลทีละแถว
    public function delete($id)
    {
        // ค้นหาแถวโดย ID และลบ
        $result = TextAnalysisResult::find($id);

        if ($result) {
            $result->delete();
            return redirect()->route('input.text.form')->with('success', 'Analysis result deleted successfully.');
        }

        return redirect()->route('input.text.form')->with('error', 'Analysis result not found.');
    }
    // ฟังก์ชันสำหรับลบข้อมูลทั้งหมด
    public function deleteAll()
    {
        // ลบข้อมูลทั้งหมดในตาราง text_analysis_results
        TextAnalysisResult::truncate();

        // กลับไปยังหน้าหลักพร้อมกับข้อความยืนยัน
        return redirect()->route('input.text.form')->with('success', 'All analysis results have been deleted successfully.');
    }

    public function showInputForm()
    {
        // ดึงข้อมูลการวิเคราะห์ทั้งหมดจากฐานข้อมูลเพื่อแสดงในตาราง
        $results = TextAnalysisResult::all();
        return view('input-text', compact('results'));
    }

    public function processText(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $comment = $request->input('text');
        $apiKey = $request->get('api_key', env('AIFORTHAI_API_KEY'));

        $start_time = microtime(true); // เริ่มจับเวลา
        $polarity = 'unknown';
        $score = 'unknown';

        // ตรวจสอบข้อความที่ป้อนเข้า
        if (stripos($comment, 'เร็ว') !== false || stripos($comment, 'ไว') !== false) {
            $polarity = 'negative';
            $score = 999;
        } else {
            // เรียก API สำหรับการวิเคราะห์ความรู้สึก
            $response = $this->getSentimentWithRetry($comment, $apiKey);
            if ($response && is_array($response)) {
                $sentiment = isset($response['sentiment']) ? $response['sentiment'] : 'unknown';
                $score = isset($sentiment['score']) ? $sentiment['score'] : 'unknown';
                $polarity = isset($sentiment['polarity']) ? $sentiment['polarity'] : 'unknown';
                if ($score == 0) {
                    $polarity = 'neutral';
                }
            } else {
                Log::error("Failed to get valid response for comment: $comment. Response: " . print_r($response, true));
            }
        }

        $end_time = microtime(true); // จับเวลาสิ้นสุด
        $processing_time = $end_time - $start_time; // คำนวณเวลาที่ใช้ในการประมวลผล

        // สร้างข้อมูลผลลัพธ์และบันทึกลงฐานข้อมูล
        $result = TextAnalysisResult::create([
            'comment' => $comment,
            'score' => $score,
            'polarity' => $polarity,
            'processing_time' => $processing_time,
        ]);

        // ส่งผลลัพธ์ไปที่ view
        return redirect()->route('input.text.form');
    }

    private function getSentimentWithRetry($comment, $apiKey, $maxRetries = 5)
    {
        $retryCount = 0;
        $delay = 2; // หน่วงเวลาครั้งแรกเป็นวินาที

        while ($retryCount < $maxRetries) {
            try {
                $client = new Client();
                $response = $client->post('https://api.aiforthai.in.th/ssense', [
                    'form_params' => [
                        'text' => $comment,
                    ],
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Apikey' => $apiKey,
                    ],
                ]);

                $responseData = json_decode($response->getBody(), true);

                if ($responseData && (!isset($responseData['message']) || $responseData['message'] !== 'API rate limit exceeded')) {
                    return $responseData;
                }

                Log::warning("Rate limit exceeded for comment: $comment. Retrying in $delay seconds.");
            } catch (\Exception $e) {
                Log::error("Exception when calling API for comment: $comment. Error: " . $e->getMessage());
            }

            $retryCount++;
            sleep($delay);
            $delay *= 2; // เพิ่มเวลาหน่วงเป็นสองเท่าทุกครั้งที่เกิดข้อผิดพลาด
        }

        return false;
    }
}
