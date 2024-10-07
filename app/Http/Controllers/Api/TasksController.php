<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\TaskInputOutput;
use App\Models\Tasks;
use App\Models\Test;
use App\Models\TestInputOutput;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TasksController extends BaseController
{
    public function tasks(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $tasks = Tasks::all();
        return view('tasks',compact('tasks'));
    }
    /**
     * List
     * @param  array
     * @return JsonResponse
     */

    public function index(): JsonResponse
    {
        $tasks = Tasks::all();
        return $this->sendResponse($tasks,"Barcha tasklar yuborildi");
    }

    /**
     * Create
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'condition' => 'nullable|string',
            'input' => 'nullable|string',
            'output' => 'nullable|string',
            'note' => 'nullable|string',
            'time' => 'required|integer|min:0',
            'memory' => 'required|integer|min:0',
            'difficulty' => 'required|integer|min:0',
            'user_id' => 'required|exists:users,id',
        ]);

        $data = $request->all();
        $data['key'] = Str::uuid();
        $task = Tasks::create($data);

        foreach ($request->get('sample_test') as $sample) {
            TaskInputOutput::create([
                "task_id"   => $task->id,
                "input"     => $sample['input'] ?? "",
                "output"    => $sample['output'] ?? ""
            ]);
        }


        $task['sample'] = $task->sample;

        return $this->sendResponse(array($task), "Task muvaffaqiyatli yaratildi.");
    }

    /**
     * Show
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $task = Tasks::find($id);
        $task['sample'] = $task->sample;

        return $this->sendResponse(array($task), "Task malumotlari jo'natildi.");
    }

    /**
     * Update
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */

    public function update(Request $request,int $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'condition' => 'nullable|string',
            'input' => 'nullable|string',
            'output' => 'nullable|string',
            'note' => 'nullable|string',
            'time' => 'required|integer|min:0',
            'memory' => 'required|integer|min:0',
            'difficulty' => 'required|integer|min:0',
            'user_id' => 'required|exists:users,id',
        ]);

        $data = $request->all();

        $task = Tasks::find($id);

        if ($task) {
            TaskInputOutput::where('task_id',$task->id)->delete();

            foreach ($request->get('sample_test') as $sample) {
                TaskInputOutput::create([
                    "task_id"   => $task->id,
                    "input"     => $sample['input'] ?? "",
                    "output"    => $sample['output'] ?? ""
                ]);
            }

            $task->update($data);
            $task['sample'] = $task->sample;

            return $this->sendResponse(array($task), "Task muvaffaqiyatli yangilandi.");
        }

        else {
            return $this->sendError("Bunday masala mavjud emas");
        }
    }

    /**
     * Delete
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $task = Tasks::find($id);
        if ($task) {
            $task->delete();
            return $this->sendResponse([],"Task o'chirildi.");
        }
        return $this->sendError("Bunday masala saqlanmagan");
    }

    /**
     * Tasks test file store
     *
     * @param Request $request
     * @param int $taskId
     * @throws Exception
     */
    public function storeTestFile(Request $request, int $taskId)
    {
        $file = $request->file('test_file');
        $filePath = $file->store('tests');

        $test = Test::create([
            'task_id' => $taskId,
            'file_path' => $filePath
        ]);

        $this->processTestFile($test, $filePath);
    }

    /**
     * @throws Exception
     */
    public function processTestFile(Test $test, $filePath)
    {
        $zip = new \ZipArchive;
        if ($zip->open(storage_path('app/' . $filePath)) === TRUE) {
            $extractPath = storage_path('app/tests/'.$test->id);
            $zip->extractTo($extractPath);
            $zip->close();

            $inputFiles = glob($extractPath . '/*.in');
            $outputFiles = glob($extractPath . '/*.out');

            $inputs = [];
            $outputs = [];

            foreach ($inputFiles as $inputFile) {
                $testName = pathinfo($inputFile, PATHINFO_FILENAME);
                $outputFile = $extractPath . '/' . $testName . '.out';

                if (!file_exists($outputFile)) {
                    throw new Exception("Matching output file for {$testName}.in is missing");
                }

                $inputContent = file_get_contents($inputFile);
                $outputContent = file_get_contents($outputFile);

                TestInputOutput::create([
                    'test_id' => $test->id,
                    'input' => trim($inputContent),
                    'output' => trim($outputContent)
                ]);
            }
        } else {
            throw new Exception('Failed to open the zip file.');
        }
    }

}
