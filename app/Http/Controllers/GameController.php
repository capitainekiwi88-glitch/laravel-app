<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class GameController extends Controller
{
    public function index(Request $request)
    {
        $featured = Game::where('featured', true)->first();
        $topRated = Game::orderByDesc('rating')->limit(6)->with(['category', 'platforms'])->get();

        $games = Game::query()
            ->when($request->query('category'), fn($query, $id) =>
                $query->where('category_id', $id))

            ->when($request->query('search'), fn($query, $search) =>
                $query->where('title', 'like', '%' . $search . '%'))

            ->with(['category', 'platforms'])
            ->paginate(9);

        $totalGames = Game::count();
        $filteredCount = $games->count();
        return view('home', [
            'games' => $games,
            'featured' => $featured,
            'topRated' => $topRated,
            'categories' => Category::withCount('games')->get(),
            'totalGames' => $totalGames,
            'filteredCount' => $filteredCount
        ]);
    }

    public function show(int $id)
    {
        return view('game-detail', ['game' => Game::findOrFail($id)]);
    }
    public function create()
    {
        return view('games.create', [
            'categories' => Category::all(),
            'platforms' => Platform::all()
        ]);
    }
    
    public function store(Request $request): RedirectResponse
    {
    $data = $request->only([
        'title',
        'synopsis',
        'release_year',
        'rating',
        'playtime_hours',
        'cover_url',
        'status',
        'category_id',
        'featured',
    ]);

    $game = Game::create($data);
    $game->platforms()->sync($request->input('platform_ids', []));

    return redirect()
        ->route('games.show', $game)
        ->with('success', 'Jeu créé !');
}
}