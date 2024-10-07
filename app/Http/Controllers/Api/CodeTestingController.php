<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CodeExecutionResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CodeTestingController extends Controller
{
    /**
     * Code execution method
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeExecutionResult(Request $request): JsonResponse
    {
        // Validatsiya
        $request->validate([
            'user_id' => 'required|string',
            'task_id' => 'required|integer', // Task ID majburiy
            'language' => 'required|string',
            'version' => 'required|string',
            'code' => 'required|string',
            'code_length' => 'nullable|integer',
            'output' => 'required|string',
            'error' => 'nullable|string',
            'execution_time' => 'nullable|integer',
            'memory_used' => 'nullable|integer',
        ]);

        // Natijalarni saqlash
        $result = new CodeExecutionResult();
        $result->user_id = $request->input('user_id');
        $result->task_id = $request->input('task_id'); // Task ID saqlanadi
        $result->language = $request->input('language');
        $result->version = $request->input('version');
        $result->code = $request->input('code');
        $result->code_length = strlen($request->input('code')); // Kod uzunligi
        $result->output = $request->input('output');
        $result->error = $request->input('error', null);
        $result->execution_time = $request->input('execution_time');
        $result->memory_used = $request->input('memory_used');
        $result->save();

        return response()->json(['message' => 'Execution result saved successfully'], 201);
    }

    /**
     * Code execution result
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function executeCode(Request $request): JsonResponse
    {
        // Validatsiya
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
            'task_id' => 'required|integer' // Task ID
        ]);

        // Piston API'ga so'rov jo'natish
        $response = Http::post('http://localhost:2000/api/v2/execute', [
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

        // Javobni qaytarish va natijalarni saqlash
        if ($response->successful()) {
            // Kod uzunligini hisoblash
            $code = implode("\n", array_column($request->input('files'), 'content'));
            $codeLength = strlen($code);

            // Natijalarni saqlash
            $resultData = $response->json();
            $this->storeExecutionResult(new Request([
                'user_id' => $request->input('user_id'), // Foydalanuvchi ID
                'task_id' => $request->input('task_id'), // Task ID
                'language' => $resultData['language'],
                'version' => $resultData['version'],
                'code' => $code, // Kod
                'code_length' => $codeLength, // Kod uzunligi
                'output' => $resultData['run']['output'],
                'error' => $resultData['run']['stderr'] ?? null,
                'execution_time' => $resultData['run']['time'] ?? null,
                'memory_used' => $resultData['run']['memory'] ?? null,
            ]));

            return response()->json($resultData);
        }

        return response()->json(['error' => 'Code execution failed'], $response->status());
    }
}
