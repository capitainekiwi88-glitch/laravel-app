<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

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
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'synopsis' => ['required', 'string'],
            'release_year' => ['required', 'integer', 'between:1970,2100'],
            'rating' => ['required', 'numeric', 'between:0,10'],
            'playtime_hours' => ['required', 'integer', 'min:0'],
            'cover_url' => ['required', 'string', 'url'],
            'status' => ['required', 'string', Rule::in(['released', 'incoming', 'early_access'])],
            'category_id' => ['required', 'exists:categories,id'],
            'platform_ids' => ['nullable', 'array'],
            'platform_ids.*' => ['integer', 'exists:platforms,id'],
            'featured' => ['boolean'],
        ]);

        $game = Game::create(Arr::except($validated, ['platform_ids']));
        $game->platforms()->sync($validated['platform_ids'] ?? []);

        return redirect()
            ->route('games.show', $game)
            ->with('success', 'Jeu créé !');
    }
}