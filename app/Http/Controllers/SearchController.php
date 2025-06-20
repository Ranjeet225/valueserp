<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SearchController extends Controller
{
    public function index()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        // dd(phpinfo());        
        $request->validate([
            'queries'   => 'required|array|min:1|max:5',
            'queries.*' => 'nullable|string|min:2|max:100',
        ], [
            'queries.required'        => 'Please enter at least 1 search query.',
            'queries.min'             => 'Enter at least 1 valid query.',
            'queries.max'             => 'You can enter a maximum of 5 queries.',
            'queries.*.string'        => 'Each query must be a valid string.',
            'queries.*.min'           => 'Each query must be at least 2 characters.',
            'queries.*.max'           => 'Each query must be at most 100 characters.',
        ]);
        $results   = [];
        $apiErrors = [];  
        foreach ($request->queries as $q) {
            try {
                // $res = Http::timeout(10)
                //         ->retry(2, 1000)
                //         ->get('https://api.valueserp.com/search', [
                //             'q'       => $q,
                //             'api_key' => config('services.valueserp.api_key'),
                //             'engine'  => 'google',
                //         ]);
            $res = Http::withOptions([
                        'verify' => false
                    ])->get('https://api.valueserp.com/search', [
                        'q' => $q,
                        'api_key' => config('services.valueserp.api_key'),
                        'engine' => 'google',
                    ]);
                if ($res->successful()) {
                    $data = $res->json();
                    if (! empty($data['organic_results'])) {
                        foreach ($data['organic_results'] as $item) {
                            $results[] = [
                                'query'   => $q,
                                'title'   => $item['title']   ?? 'No title',
                                'link'    => $item['link']    ?? 'No link',
                                'snippet' => $item['snippet'] ?? 'No snippet',
                            ];
                        }
                    } else {
                        $apiErrors[] = "No results for “{$q}”.";
                    }
                } else {
                    $apiErrors[] = "API error for “{$q}” (status: {$res->status()}).";
                }
            } catch (\Exception $e) {
                $apiErrors[] = "Exception for “{$q}”: " . $e->getMessage();
            }
        }
        Session::put('csv_data', $results);
        return view('search', [
            'results'   => $results,
            'apiErrors' => $apiErrors,
        ]);
    }


    public function exportCSV()
    {
        $data = Session::get('csv_data', []);
        if (empty($data)) {
            return redirect('/')
                   ->withErrors(['Nothing to export. Search first.']);
        }

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=search_results.csv',
        ];
        $cols = ['Query','Title','Link','Snippet'];

        $callback = fn() => tap(fopen('php://output','w'), function($f) use($cols,$data){
            fputcsv($f, $cols);
            foreach($data as $row) {
                fputcsv($f, $row);
            }
            fclose($f);
        });

        return new StreamedResponse($callback,200,$headers);
    }
}
