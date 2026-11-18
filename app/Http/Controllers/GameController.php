<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $featured = Game::where('featured', true)->first();
        $topRated = Game::orderByDesc('rating')->limit(6)->get();

        $games = Game::query()
            ->when($request->query('category'), fn ($query, $id) =>
                $query->where('category_id', $id))
            ->get();

        return view('home', [
            'games' => $games,
            'featured' => $featured,
            'topRated' => $topRated,
            'categories' => Category::all(),
        ]);
    }

    public function show(int $id)
    {
        return view('game-detail', ['game' => Game::findOrFail($id)]);
    }
}