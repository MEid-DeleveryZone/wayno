<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogApiRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Log request details
        Log::channel('apilog')->info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        $response = $next($request);

        // Log response details with proper JSON handling
        $responseData = [
            'status' => $response->status(),
        ];

		// Include originating request details in the response log
		$responseData['request'] = [
			'method' => $request->method(),
			'url' => $request->fullUrl(),
			'ip' => $request->ip(),
			'headers' => $request->headers->all(),
			'body' => $request->all(),
		];

        // Check if response is JSON and handle accordingly
        if (method_exists($response, 'getContent')) {
            $content = $response->getContent();
            
            // Check if response is JSON
            if ($response->headers->get('Content-Type') && 
                str_contains($response->headers->get('Content-Type'), 'application/json')) {
                
                // Try to decode JSON for better logging
                $decodedContent = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $responseData['content'] = $decodedContent;
                } else {
                    // If JSON decode fails, log as string
                    $responseData['content'] = $content;
                }
            } else {
                // For non-JSON responses, check if content is too long
                if (strlen($content) > 1000) {
                    $responseData['content'] = substr($content, 0, 1000) . '... (truncated)';
                } else {
                    $responseData['content'] = $content;
                }
            }
        } else {
            $responseData['content'] = 'Streamed or Binary Content';
        }

        Log::channel('apilog')->info('API Response', $responseData);

        return $response;
    }
}

