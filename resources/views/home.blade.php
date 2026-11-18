@extends('layouts.app')

@section('title', 'Accueil')
@section('styles')
    @vite('resources/css/home.css')
@endsection
@section('content')

    <section class="hero wishflix-hero" aria-labelledby="hero-title">
        <figure class="wishflix-hero__backdrop">
            <img src="https://via.assets.so/game.png?id=1&q=95&w=1600&h=900&fit=cover"
                alt="Image de couverture de The Witcher 3" width="1600" height="900" />
        </figure>
        <div class="wishflix-hero__veil" aria-hidden="true"></div>

        <div class="hero-content wishflix-hero__content">
            <div class="wishflix-hero__text">
                <p class="wishflix-hero__kicker">Nouveaute WishFlix</p>
                <div class="wishflix-hero__meta">
                    <span class="badge badge-primary">A la une</span>
                    <span class="badge badge-ghost">RPG</span>
                    <span class="badge badge-outline">PC, PS5, Xbox</span>
                </div>

                <h1 id="hero-title" class="wishflix-hero__title">
                    The Witcher 3
                </h1>
                <p class="wishflix-hero__description">
                    Incarnez Geralt de Riv, un chasseur de monstres professionnel,
                    dans un monde ouvert fantastique rempli de quêtes épiques et de
                    choix moraux difficiles.
                </p>
                <p class="wishflix-hero__details">2015 | 4.8/5 | 100h de jeu</p>

                <div class="wishflix-hero__actions">
                    <a class="btn btn-primary btn-lg" href="game-detail.html">Voir la fiche</a>
                    <button class="btn btn-outline btn-lg">
                        Ajouter a la wishlist
                    </button>
                </div>
            </div>
        </div>
    </section>
    @if ($featured)
        <section class="wish-home__featured" aria-label="Jeu à la une">
            <article class="card movie-card game-card">
                <figure class="game-card__cover">
                    <img src="{{ $featured->cover_url }}" alt="Affiche de {{ $featured->title }}" width="300"
                        height="450" />
                </figure>
                <div class="card-body game-card__body">
                    <span class="badge badge-primary">À la une</span>
                    <h2 class="card-title game-card__title">{{ $featured->title }}</h2>
                    <div class="game-card__rating">
                        <span class="game-card__rating-value">{{ $featured->rating }}</span>
                        <span class="game-card__rating-max">/5</span>
                    </div>
                    <a class="btn btn-sm btn-ghost" href="{{ route('games.show', $featured->id) }}">Details</a>
                </div>
            </article>
        </section>
    @endif

    <section class="wish-home__top" aria-label="Mieux notés">
        <h2>Mieux notés</h2>
        <ol>
            @foreach ($topRated as $game)
                <li>
                    <a href="{{ route('games.show', $game->id) }}">{{ $game->title }}</a>
                    ({{ $game->rating }}/5)
                </li>
            @endforeach
        </ol>
    </section>
    <section class="wish-section" aria-labelledby="catalogue-title">
        <header class="wish-section__header">
            <div>
                <h2 class="wish-section__title" id="catalogue-title">
                    Catalogue
                </h2>
                <p class="wish-section__subtitle">
                    Filtres, navigation et cards dynamiques
                </p>
            </div>
            <div class="wish-section__actions">
                <div class="wish-home__filters">


                    <form method="GET" action="{{ route('home') }}">
                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher un jeu">
                        <select name="category" class="select select-bordered select-category">
                            <option value="">Toutes les catégories </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }} - ({{ $category->games_count }})
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-sm" type="submit">Filtrer</button>
                    </form>

                    <button class="btn btn-secondary">
                        Disponibles uniquement
                    </button>
                </div>
            </div>
        </header>

        <div class="wish-section__stats">
            <div class="stats shadow wish-home__stats" role="group" aria-label="Statistiques catalogue">
                <div class="stat">
                    <div class="stat-title">Jeux disponibles</div>
                    <div class="stat-value text-primary">{{ $totalGames }}</div>
                </div>
                @if($filteredCount !== $totalGames)
                <div class="stat">
                    <div class="stat-title">Resultats filtres</div>

                    <div class="stat-value text-secondary">{{ $filteredCount }}</div>
                </div>
                @endif
                <div class="stat">
                    <div class="stat-title">Ma wishlist</div>
                    <div class="stat-value text-accent">3</div>
                </div>
            </div>
        </div>

        <div class="wish-section__content">
            <div class="wish-home__grid">
                @forelse ($games as $game)
                    <article class="card movie-card game-card" aria-label="Jeu {{ $game->title }}">
                        <figure class="game-card__cover">
                            <img src="{{ $game->cover_url }}" alt="Affiche de {{ $game->title }}" width="300"
                                height="450" />
                        </figure>

                        <div class="card-body game-card__body">
                            <div class="game-card__badges">
                                <span class="badge badge-secondary">{{ $game->category->name }}</span>
                                <span class="badge badge-success">Disponible</span>
                            </div>

                            <h3 class="card-title game-card__title">{{ $game->title }}</h3>
                            <p class="game-card__meta">
                                {{ $game->release_year }} - {{ $game->platforms->pluck('name')->join(', ') }}
                            </p>

                            <div class="game-card__rating" aria-label="Note moyenne">
                                <span class="game-card__rating-value">{{ $game->rating }}</span>
                                <span class="game-card__rating-max">/5</span>
                            </div>

                            <p class="game-card__synopsis">{{ $game->synopsis }}</p>

                            <div class="card-actions game-card__actions">
                                <a class="btn btn-sm btn-ghost" href="{{ route('games.show', $game->id) }}"
                                    aria-label="Voir les details de {{ $game->title }}">
                                    Details
                                </a>
                                <button class="btn btn-sm btn-outline">Wishlist</button>
                            </div>
                        </div>
                    </article>
                @empty
                    <p>Aucun jeu pour le moment.</p>
                @endforelse
            </div>
        </div>
        </div>
    </section>
@endsection
