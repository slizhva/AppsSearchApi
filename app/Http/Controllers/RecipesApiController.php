<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Set;
use App\Models\Recipe;

class RecipesApiController extends Controller
{

    private function getSearchData(Request $request):array {
        $type = $request->get('type');
        $values = $request->get('values');
        if (empty($type)) {
            $type = json_decode($request->get('type'), true);
            $values = json_decode($request->get('values'), true);
        }
        if (empty($type)) {
            $type = json_decode($request->getContent(), true)['type'] ?? null;
            $values = json_decode($request->getContent(), true)['values'] ?? null;
        }
        return [$type, $values];
    }
    public function get(Request $request):JsonResponse
    {
        $user = User
            ::where('api_token', $request->route('token'))
            ->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'wrong token',
            ]);
        }

        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', $user->id)
            ->limit(1)
            ->get(['id', 'name'])
            ->toArray()[0];

        $recipesRaw = Recipe::where('set', $set['id'])
            ->get(['code', 'value'])
            ->toArray();

        [$searchType, $searchValues] = $this->getSearchData($request);
        $recipes = [];
        foreach ($recipesRaw as $recipeRaw) {
            $recipeValues = preg_split("/\r\n|\n|\r/", $recipeRaw['value']);

            if (strtolower($searchType) === 'all') {
                if (!array_diff($recipeValues, $searchValues)) {
                    $recipes[] = $recipeRaw['code'];
                }
                continue;
            }
            if (strtolower($searchType) === 'any') {
                if (!empty(array_intersect($searchValues, $recipeValues))) {
                    $recipes[] = $recipeRaw['code'];
                }
            }
        }

        return response()->json([
            'status' => true,
            'value' => $recipes,
        ]);
    }
}
