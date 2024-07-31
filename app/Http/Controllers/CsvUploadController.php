<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\CsvUpload;
use App\Models\CsvData;
use League\Csv\Reader;
use GuzzleHttp\Client;
use App\Models\AnalysisResult;

class CsvUploadController extends Controller
{
    
    public function showUploadForm()
    {
        $uploads = CsvUpload::all();
        $csvData = CsvData::all();
        return view('upload', compact('uploads', 'csvData'));
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->store('uploads');

        $csvUpload = new CsvUpload();
        $csvUpload->file_name = $file->getClientOriginalName();
        $csvUpload->file_path = $filePath;
        $csvUpload->save();

        $csv = Reader::createFromPath(storage_path('app/' . $filePath), 'r');

        $header = ['column1', 'column2', 'column3'];
        $records = $csv->getRecords($header);

        foreach ($records as $record) {
            CsvData::create([
                'column1' => $record['column1'],
                'column2' => $record['column2'],
                'column3' => $record['column3'],
            ]);
        }

        return back()->with('success', 'File uploaded and data imported successfully')->with('file', $filePath);
    }

    public function deleteFile($id)
    {
        CsvData::where('id', $id)->delete();
        CsvUpload::where('id', $id)->delete();

        return back()->with('success', 'Data deleted successfully');
    }

    public function deleteAllFiles()
    {
        CsvData::truncate();
        CsvUpload::truncate();

        return back()->with('success', 'All data deleted successfully');
    }

    public function deleteAllResults()
    {
        // สมมติว่าชื่อโมเดลของคุณคือ AnalysisResult
        AnalysisResult::truncate();
    
        return redirect()->back()->with('results', 'All results have been deleted successfully.');
    }

    public function analyzeComments(Request $request)
    {
        ini_set('max_execution_time', 600); // เพิ่มเวลาประมวลผลสูงสุดเป็น 600 วินาที (10 นาที)

        $apiKey = env('AIFORTHAI_API_KEY');
        $comments = CsvData::pluck('column2')->toArray();

        $results = [];

        foreach ($comments as $comment) {
            $response = $this->getSentimentWithRetry($comment, $apiKey);
            if ($response && is_array($response)) {
                Log::info("Response for comment '$comment': " . json_encode($response));
                $sentiment = isset($response['sentiment']) ? $response['sentiment'] : 'unknown';
                $score = isset($sentiment['score']) ? $sentiment['score'] : 'unknown';
                $polarity = isset($sentiment['polarity']) ? $sentiment['polarity'] : 'unknown';
                if ($score == 0) { // Use == to allow type conversion
                    $polarity = 'neutral';
                }
            } else {
                Log::error("Failed to get valid response for comment: $comment. Response: " . print_r($response, true));
                $score = 'unknown';
                $polarity = 'unknown';
            }
            $results[] = [
                'comment' => $comment,
                'score' => $score,
                'polarity' => $polarity,
            ];
        }

        return redirect()->route('comments.analysis.results')->with('results', $results);
    }

    private function getSentiment($comment, $apiKey)
    {
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

        return json_decode($response->getBody(), true);
    }

    private function getSentimentWithRetry($comment, $apiKey, $maxRetries = 5)
    {
        $retryCount = 0;
        $delay = 2; // หน่วงเวลาครั้งแรกเป็นวินาที

        while ($retryCount < $maxRetries) {
            try {
                $response = $this->getSentiment($comment, $apiKey);

                if ($response && (!isset($response['message']) || $response['message'] !== 'API rate limit exceeded')) {
                    return $response;
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

    public function showAnalysisResults()
    {
        $results = session('results', []);

        return view('analysis-results', compact('results'));
    }
}
