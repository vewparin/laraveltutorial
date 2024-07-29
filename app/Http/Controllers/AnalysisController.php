<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalysisResult;

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
}
