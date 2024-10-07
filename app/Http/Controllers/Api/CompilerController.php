<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompilerController extends Controller
{
    /**
     * Get URl
     *
     * */
    public string $url;
    public function __construct()
    {
        $this->url = "http://api.sampc.uz/";
    }

    /**
     * Runtimes
     *
     * @return JsonResponse
     * */
    public function getRuntimes(): \Illuminate\Http\JsonResponse
    {
        $response = Http::get($this->url.'api/v2/runtimes');

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch runtimes'], $response->status());
    }

    /**
     * Execute Code
     *
     * @param Request $request
     * @return JsonResponse
     * */
    public function executeCode(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'language' => 'required|string',
            'version' => 'required|string',
            'files' => 'required|array',
            'files.*.name' => 'required|string',
            'files.*.content' => 'required|string',
            'stdin' => 'nullable|string',
            'args' => 'nullable|array',
            'run_timeout' => 'nullable|integer',
            'compile_timeout' => 'nullable|integer',
            'run_memory_limit' => 'nullable|integer',
            'compile_memory_limit' => 'nullable|integer',
        ]);

        $response = Http::post($this->url.'/api/v2/execute', [
            'language' => $request->input('language'),
            'version' => $request->input('version'),
            'files' => $request->input('files'),
            'stdin' => $request->input('stdin', ''),
            'args' => $request->input('args', []),
            'run_timeout' => $request->input('run_timeout', 3000),
            'compile_timeout' => $request->input('compile_timeout', 10000),
            'run_memory_limit' => $request->input('run_memory_limit', -1),
            'compile_memory_limit' => $request->input('compile_memory_limit', -1),
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Code execution failed'], $response->status());
    }

    /**
     * Get Packages
     *
     * @return JsonResponse
     * */
    public function getPackages(): \Illuminate\Http\JsonResponse
    {
        $response = Http::get($this->url.'api/v2/packages');

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch packages'], $response->status());
    }

    /**
     * Install Package
     *
     * @param Request $request
     * @return JsonResponse
     * */
    public function installPackage(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'language' => 'required|string',
            'version' => 'required|string',
        ]);

        $response = Http::post($this->url.'api/v2/packages', [
            'language' => $request->input('language'),
            'version' => $request->input('version'),
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to install package'], $response->status());
    }

    /**
     * Delete Package
     *
     * @param Request $request
     * @return JsonResponse
     * */
    public function deletePackage(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'language' => 'required|string',
            'version' => 'required|string',
        ]);

        $response = Http::delete($this->url.'api/v2/packages', [
            'language' => $request->input('language'),
            'version' => $request->input('version'),
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to delete package'], $response->status());
    }
}
