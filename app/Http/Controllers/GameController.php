<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
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

    public function store(StoreGameRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $game = Game::create(Arr::except($validated, ['platform_ids']));
        $game->platforms()->sync($validated['platform_ids'] ?? []);

        return redirect()
            ->route('games.show', $game)
            ->with('success', 'Jeu créé !');
    }

    public function edit(Game $game)
    {
        return view('games.edit', [
            'game' => $game->load('platforms'),
            'categories' => Category::all(),
            'platforms' => Platform::all(),
        ]);
    }

    public function update(UpdateGameRequest $request, Game $game): RedirectResponse
    {
        $validated = $request->validated();
        $game->update(Arr::except($validated, ['platform_ids']));
        $game->platforms()->sync($validated['platform_ids'] ?? []);

        return redirect()
            ->route('games.show', $game)
            ->with('success', 'Jeu mis à jour');
    }

    public function destroy(Game $game): RedirectResponse
    {
        $game->platforms()->detach();
        $game->delete();
        return redirect()
            ->route('home')
            ->with('success', 'Jeu supprimé');
    }


}