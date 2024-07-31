<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalysisResult;
use Illuminate\Support\Facades\Response;
class AnalysisController extends Controller
{
    public function saveResults(Request $request)
    {
        $results = $request->input('results');

        foreach ($results as $result) {
            AnalysisResult::create([
                'comment' => $result['comment'],
                'score' => $result['score'],
                'polarity' => $result['polarity']
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function downloadCSV(Request $request)
    {
        $results = session('results'); // Retrieve results from the session
        if (empty($results)) {
            return back()->with('error', 'No data available for download.');
        }

        $csvHeader = ['Comment', 'Score', 'Polarity'];
        $filename = 'analysis_results_' . date('Ymd_His') . '.csv';

        $callback = function () use ($results, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);

            foreach ($results as $result) {
                fputcsv($file, [
                    $result['comment'],
                    $result['score'],
                    $result['polarity']
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }
}
