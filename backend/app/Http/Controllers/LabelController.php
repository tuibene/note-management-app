<?php
namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LabelController extends Controller
{
    public function index(Request $request)
    {
        try {
            $labels = $request->user()->labels;
            return response()->json($labels);
        } catch (\Exception $e) {
            Log::error('Label index failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);
            return response()->json([
                'message' => 'Failed to retrieve labels',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('LabelController::store called', [
                'input' => $request->all(),
                'user_id' => $request->user()->id,
            ]);
            $request->validate([
                'name' => 'required|string|max:255|unique:labels,name,NULL,id,user_id,' . $request->user()->id,
            ]);
            $label = $request->user()->labels()->create([
                'name' => $request->name,
            ]);
            Log::info('Label created', ['label' => $label]);
            return response()->json([
                'message' => 'Label created successfully',
                'label' => $label,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Label creation failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Failed to create label',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $name)
    {
        try {
            Log::info('LabelController::update called', [
                'name' => $name,
                'new_name' => $request->name,
                'user_id' => $request->user()->id,
            ]);
            $label = $request->user()->labels()->where('name', $name)->first();
            if (!$label) {
                Log::warning('Label not found', ['name' => $name, 'user_id' => $request->user()->id]);
                return response()->json([
                    'message' => 'Label not found',
                ], 404);
            }
            $request->validate([
                'name' => 'required|string|max:255|unique:labels,name,' . $label->id . ',id,user_id,' . $request->user()->id,
            ]);
            $label->update(['name' => $request->name]);
            Log::info('Label updated', ['label' => $label]);
            return response()->json([
                'message' => 'Label updated successfully',
                'label' => $label,
            ]);
        } catch (\Exception $e) {
            Log::error('Label update failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'name' => $name,
                'new_name' => $request->name,
            ]);
            return response()->json([
                'message' => 'Failed to update label',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $name)
    {
        try {
            $label = $request->user()->labels()->where('name', $name)->first();
            if (!$label) {
                Log::warning('Label not found', ['name' => $name, 'user_id' => $request->user()->id]);
                return response()->json([
                    'message' => 'Label not found',
                ], 404);
            }
            $label->delete();
            Log::info('Label deleted', ['name' => $name, 'user_id' => $request->user()->id]);
            return response()->json([
                'message' => 'Label deleted successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Label deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'name' => $name,
            ]);
            return response()->json([
                'message' => 'Failed to delete label',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}