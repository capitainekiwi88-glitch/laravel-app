@extends('layouts.app')

@section('title', 'Créer un jeu')

@section('content')
    <h1>Créer un jeu</h1>

    <form method="POST" action="{{ route('games.store') }}">
        <div>
            <label for="title">Titre :</label>
            <input id="title" type="text" name="title">
        </div>

        <div>
            <label for="synopsis">Synopsis :</label>
            <textarea id="synopsis" name="synopsis"></textarea>
        </div>

        <div>
            <label for="release_year">Année de sortie :</label>
            <input id="release_year" type="number" name="release_year">
        </div>

        <div>
            <label for="rating">Note :</label>
            <input id="rating" type="number" name="rating" step="0.1">
        </div>

        <div>
            <label for="playtime_hours">Temps de jeu (heures) :</label>
            <input id="playtime_hours" type="number" name="playtime_hours">
        </div>

        <div>
            <label for="cover_url">Image de couverture :</label>
            <input id="cover_url" type="text" name="cover_url">
        </div>

        <div>
            <label for="status">Statut :</label>
            <select id="status" name="status">
                <option value="released">Disponible</option>
                <option value="upcoming">Bientôt</option>
                <option value="early_access">Accès anticipé</option>
            </select>
        </div>

        <div>
            <label for="category_id">Catégorie :</label>
            <select id="category_id" name="category_id">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <fieldset>
            <legend>Plateformes :</legend>
            @foreach ($platforms as $platform)
                <label>
                    <input type="checkbox" name="platform_ids[]" value="{{ $platform->id }}">
                    {{ $platform->name }}
                </label>
            @endforeach
        </fieldset>

        <div>
            <label for="featured">À la une :</label>
            <input id="featured" type="checkbox" name="featured" value="1">
        </div>

        <button type="submit">Créer le jeu</button>
    </form>
@endsection