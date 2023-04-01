<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Set;
use App\Models\Recipe;

class RecipesApiController extends Controller
{

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

        $recipes = [];
        foreach ($recipesRaw as $recipeRaw) {
            $values = preg_split("/\r\n|\n|\r/", $recipeRaw['value']);

            if ($values) { // TODO: compare value with requested data
                $recipes[] = $recipeRaw['code'];
            }
        }

        return response()->json([
            'status' => true,
            'value' => $recipes,
        ]);
    }
}
