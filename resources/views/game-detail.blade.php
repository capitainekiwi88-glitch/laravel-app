@extends('layouts.app')

@section('title', $game->title)

@section('styles')
    @vite('resources/css/game-detail.css')
@endsection

@section('content')
    <section class="wish-detail">
        <figure class="wish-detail__backdrop">
            <img src="{{ str_replace(['w=300', 'h=450'], ['w=1600', 'h=900'], $game->cover_url) }}"
                alt="Image de couverture de {{ $game->title }}" width="1600" height="900" />
        </figure>

        <div class="wish-detail__veil" aria-hidden="true"></div>

        <div class="wish-detail__content">
            <a class="btn btn-ghost btn-sm" href="{{ route('home') }}">Retour au catalogue</a>

            <p class="wish-detail__kicker">Fiche jeu</p>
            <h1 class="wish-detail__title">{{ $game->title }}</h1>

            <div class="wish-detail__meta">
                <span class="badge badge-primary">{{ $game->category->name }}</span>
                <span class="badge badge-outline">{{ $game->release_year }}</span>
                <span class="badge badge-ghost">{{ $game->platforms->pluck('name')->join(', ') }}</span>
            </div>

            <p class="wish-detail__description">
                {{ $game->synopsis }}
            </p>

            <ul class="wish-detail__facts" aria-label="Informations sur le jeu">
                <li>
                    <strong>Note:</strong>
                    {{ $game->rating }}/5
                </li>
                <li>
                    <strong>Duree:</strong>
                    {{ $game->playtime_hours }}h de jeu
                </li>
                <li>
                    <strong>Etat:</strong>
                    {{ $game->status }}
                </li>
                <li>
                    <strong>Plateformes:</strong>
                    @forelse ($game->platforms as $platform)
                        {{ $platform->name }}@if (!$loop->last)
                            ,
                        @endif
                    @empty
                        Non renseigné
                    @endforelse
                </li>
            </ul>
            <div class="wish-detail__actions">
                <button class="btn btn-secondary">Ajouter a la wishlist</button>
                <a class="btn btn-outline" href="{{ route('wishlist') }}">Voir ma wishlist</a>

                <a class="btn btn-primary" href="{{ route('games.edit', $game) }}">Modifier</a>

                <form method="POST" action="{{ route('games.destroy', $game) }}" onsubmit="return confirm('Supprimer ce jeu ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error">Supprimer</button>
                </form>
            </div>
        </div>
    </section>
@endsection