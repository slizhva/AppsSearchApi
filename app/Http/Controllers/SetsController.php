<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

use App\Models\Set;

class SetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sets():Renderable
    {
        $user = Auth::user();
        $sets = Set::where('user', $user->id)->orderBy('id', 'desc')->get(['id', 'name'])->toArray();
        return view('recipes.sets', [
            'token' => $user->api_token,
            'dangerous_actions_key' => $user->dangerous_actions_key,
            'sets' => $sets,
        ]);
    }

    public function add(Request $request):RedirectResponse
    {
        $data = new Set;
        $data->name = $request->get('name');
        $data->user = Auth::id();
        $data->save();

        return redirect()->route('sets');
    }

    public function delete(Request $request):RedirectResponse
    {
        $user = Auth::user();
        if ($user['dangerous_actions_key'] !== $request->get('dangerous_actions_key')) {
            return redirect()->route('sets')->with('error', 'Error: Wrong dangerous action key.');
        }

        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1);

        if ($set->count() === 0) {
            return redirect()->route('sets')->with('error', 'Error: Data item not found.');
        }

        $set->delete();
        return redirect()->route('sets')->with('status', 'Success: The data item was deleted.');
    }

}
