<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WishFlix - @yield('title')</title>
    @vite(['resources/css/app.css'])
    @yield('styles')
    <link
      href="https://cdn.jsdelivr.net/npm/daisyui@5"
      rel="stylesheet"
      type="text/css"
    />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body>
    <div class="wishflix-shell">
      @include('partials.nav')

      <main class="wishflix-main" id="contenu-principal">
        @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="flash">{{ session('error') }}</div>
        @endif
        @yield('content')
      </main>

      <footer class="wishflix-footer" aria-label="Pied de page">
        <p class="wishflix-footer__text">WishFlix - Gaming Catalog</p>
      </footer>
    </div>
  </body>
</html>