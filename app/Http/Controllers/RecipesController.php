<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Recipe;
use App\Models\Set;

class RecipesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function recipes(Request $request):Renderable
    {
        $user = Auth::user();
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', $user->id)
            ->limit(1)
            ->get(['id', 'name'])
            ->toArray()[0];

        $recipes = Recipe::where('set', $set['id'])
            ->orderBy('id', 'desc')
            ->get(['id', 'code', 'value'])
            ->toArray();

        return view('recipes.recipes', [
            'token' => $user->api_token,
            'dangerous_actions_key' => $user->dangerous_actions_key,
            'set' => $set,
            'recipes' => $recipes,
        ]);
    }

    public function add(Request $request):RedirectResponse
    {
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        $recipe = new Recipe;
        $recipe->set = $set['id'];
        $recipe->code = $request->get('code');
        $recipe->value = $request->get('value');
        $recipe->save();

        return redirect()->route('recipe', (int)$set['id']);
    }

    public function delete(Request $request):RedirectResponse
    {
        if (empty($request->get('recipe_id'))) {
            return redirect()->route('recipe', (int)$request->route('set_id'))->with('error', 'Error: Data item not found.');
        }

        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        $recipe = Recipe
            ::where('id', $request->get('recipe_id'))
            ->where('set', $set['id'])
            ->limit(1);

        if ($recipe->count() === 0) {
            return redirect()->route('recipe', (int)$set['id'])->with('error', 'Error: Data item not found.');
        }

        $recipe->delete();
        return redirect()->route('recipe', (int)$set['id'])->with('status', 'Success: The data item was deleted.');
    }

    public function update(Request $request):RedirectResponse
    {
        if (empty($request->get('recipe_id'))) {
            $this->add($request);
            return redirect()->route('recipes', $request->route('set_id'));
        }

        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        $recipe = Recipe
            ::where('id', $request->get('recipe_id'))
            ->where('set', $set['id']);
        if (empty($request->get('value'))) {
            $recipe->delete();
        } else {
            $recipe->update([
                'code' => $request->get('code'),
                'value' => $request->get('value'),
            ]);
        }

        return redirect()->route('recipe', $set['id']);
    }
}
