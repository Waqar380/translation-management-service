<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller 
{

    public function index(Request $request): JsonResponse 
    {

        $query = Translation::query();
        
        if ($request->has('content')) {
            $query->where('content', 'LIKE', "%{$request->input('content')}%");
        }
        if ($request->has('tags')) {
            $tags = explode(',', $request->input('tags'));
            $query->whereJsonContains('tags', $tags);
        }
        if ($request->has('key')) {
            $query->where('key', 'LIKE', "%{$request->input('key')}%");
        }
        
        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'data' => $query->orderBy('id', 'desc')->paginate(10),
        ],  JsonResponse::HTTP_OK);
    }

    public function store(Request $request): JsonResponse 
    {
        $validator = Validator::make($request->all(), [
            'locale' => 'required|string|max:10',
            'key' => 'required|string',
            'content' => 'required|string',
            'context' => 'required|in:mobile,web,desktop',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $translation = Translation::create($request->all());
        
        return response()->json([
            'code' => JsonResponse::HTTP_CREATED,
            'data' => $translation,
            'message' => 'Translation created successfully'
        ],  JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        
        $validator = Validator::make($request->all(), [
            'locale' => 'sometimes|string|max:10',
            'key' => 'sometimes|string',
            'content' => 'sometimes|string',
            'context' => 'sometimes|in:mobile,web,desktop',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $translation = Translation::findOrFail($id);
        $translation->update($request->all());
        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'data' => $translation,
            'message' => 'Translation updated successfully'
        ],  JsonResponse::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse 
    {
        Translation::findOrFail($id);
        Translation::destroy($id);
        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'message' => 'Translation deleted successfully'
        ], JsonResponse::HTTP_OK);
    }
}