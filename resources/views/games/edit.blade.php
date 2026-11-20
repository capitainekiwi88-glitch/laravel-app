@extends('layouts.app')

@section('title', 'Modifier un jeu')

@section('content')
    <h1>Modifier un jeu</h1>

    <form method="POST" action="{{ route('games.update', $game) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="title">Titre :</label>
            <input id="title" type="text" name="title" value="{{ old('title', $game->title) }}">
            @error('title')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="synopsis">Synopsis :</label>
            <textarea id="synopsis" name="synopsis">{{ old('synopsis', $game->synopsis) }}</textarea>
            @error('synopsis')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="release_year">Année de sortie :</label>
            <input id="release_year" type="number" name="release_year" value="{{ old('release_year', $game->release_year) }}">
            @error('release_year')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="rating">Note :</label>
            <input id="rating" type="number" name="rating" step="0.1" value="{{ old('rating', $game->rating) }}">
            @error('rating')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="playtime_hours">Temps de jeu (heures) :</label>
            <input id="playtime_hours" type="number" name="playtime_hours" value="{{ old('playtime_hours', $game->playtime_hours) }}">
            @error('playtime_hours')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="cover_url">Image de couverture :</label>
            <input id="cover_url" type="text" name="cover_url" value="{{ old('cover_url', $game->cover_url) }}">
            @error('cover_url')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status">Statut :</label>
            <select id="status" name="status">
                <option value="released" @selected(old('status', $game->status) === 'released')>Disponible</option>
                <option value="upcoming" @selected(old('status', $game->status) === 'upcoming')>Bientôt</option>
                <option value="early_access" @selected(old('status', $game->status) === 'early_access')>Accès anticipé</option>
            </select>
            @error('status')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category_id">Catégorie :</label>
            <select id="category_id" name="category_id">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $game->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <fieldset>
            <legend>Plateformes :</legend>
            @php
                $selectedPlatforms = old('platform_ids', $game->platforms->pluck('id')->toArray());
            @endphp
            @foreach ($platforms as $platform)
                <label>
                    <input type="checkbox" name="platform_ids[]" value="{{ $platform->id }}" @checked(in_array($platform->id, $selectedPlatforms))>
                    {{ $platform->name }}
                </label>
            @endforeach
            @error('platform_ids')
                <p class="error">{{ $message }}</p>
            @enderror
        </fieldset>

        <div>
            <label for="featured">À la une :</label>
            <input type="hidden" name="featured" value="0">
            <input id="featured" type="checkbox" name="featured" value="1" @checked(old('featured', $game->featured) == '1')>
            @error('featured')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Mettre à jour le jeu</button>
    </form>
@endsection