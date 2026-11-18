## Réponses

# Palier 1 - Observer (ouvrir et lire)

1.  L'URL définie est la racine (la page d'accueil de base) qui retourne la vue (ici, il s'agit de la vue welcome) dans resources/views/welcome.blade.php.

2.  APP_ENV : local
    DB_CONNECTION : mariadb
    SESSION_DRIVER : database

3.  Les services définis sont fatih (anciennement laravel.test) et mariadb et fatih sert la page localhost, avec le port 80.

4.  Dans databse/migrations/, il y a 3 fichiers qui créer les tables users, password_reset_tokens, sessions, cache, cache_locks, jobs, job_batches, failed_jobs

La commande php artisan migrate:status (dans podman compose exec fatih bash)

# Palier 2 - Relier (suivre le fil entre deux fichiers)

5. Le premier fichier chargé est /../vendor/autoload.php, génèrer par Composer lors de l'installation des dépendances

6. Dans bootstrap/app.php, on retrouve les routes web(routes/web.php), commands (routes/console.php) et health (/up).

localhost:8080/up affiche "Application up, HTTP request received. Response rendered in 1294ms.

7. La commande php artisan route:list montre 4 routes :
    - web/php
    - 2 storage/{path} (un GET, un PUT)
    - up (health de la question 6)

8. Ligne 16 'name' => env('APP_NAME','Laravel') définit 'name'
   La fonction env() est utilisée pour lire la valeur et si elle était absente, elle utiliserais la deuxième valeur de la fonction 'Laravel'.

# Palier 3 : Raisonner (expliquer un choix du framework)

9.app/Models/ contient un seul modèle. Lequel ? À quelle table de la base correspond-il ? Comment Laravel fait-il le lien entre le nom de la classe et le nom de la table ?

10.  app/Http/Controllers/ contient un fichier. Est-il utilisé par la route / ? Si non, comment la route produit-elle quand même une réponse ?

11.  Ouvrez .gitignore. Pourquoi .env y figure-t-il alors que .env.example n'y figure pas ? Pourquoi vendor/ y figure-t-il ? Quelle commande un collègue devra-t-il lancer après avoir cloné votre dépôt pour reconstituer ce dossier ?
12.  Le dossier public/ est le seul exposé au serveur web. Quel problème de sécurité cela évite-t-il par rapport à un projet PHP « from scratch » où tous les fichiers sont à la racine du site ?

# Exercice 2

1. La ligne $game['title'] est devenu $game->title
2. @forelse est devenu @foreach
3. la fonction htmlspecialchars termine le return de la fonction e(...) (Illuminate/SUpport/helpers.php)
4. Avec {{ }}, on a un script alert('XSS') et avec {!! !!}, le navigateur est écrit en brut (risque attaque XSS)
5. Laravel compile à la dmeande au premier affichage et réutilise le fichier compilé tant qu'il n'y a pas eu de modification
6. Dans ->whereNumber('id'), trait est une classe et whereNumber = [0-9]+
7. assignExpressionToParameters appelle where(...) et lui passe un tableau qui associe id à [0-9]+
8. le trait apparait dans la ligne use ... en haut de la classe Route qui énumère les traits qu'elle utilise.
9.  la contraite est rangée dans $this->wheres sous la clé du nom du paramètre {$this->wheres['id'] = '[0-9]+'
10. whereAlpha ([a-zA-Z]+./ et whereAlphaNumeric ([a-zA-z0-9]+) sont les voisines de whereNumber. whereUuid et whereIn aussi passent assignExpressionToParameters avec leur règle regex.
11. whereNumber('id') est un raccourci de where('id','[0-9]+') donc 1 fonctionne et abc donne une erreur 404.
12. La fonction globale abort appelle app()->abort($code, $message, $headers) dans la méthode abort de l'objet Application.
13. elle lance NotFoundHttpException pour le code 404 et sinon elle lance juste une HttpException.
14. renderHttpException appelle registerErrorViewPaths() pour enregistrer les dossiers errors:: et getHttpExceptionView() pour trouver la vue d'erreur avant de produire la réponse. Pour l'erreur 404 le nom construit est errors::404
15. substr($view, 0; -2).'xx' transforme errors:404 en errors:4xx qui est tester si la première vue n'existe pas et sinon si elle n'existe pas non plus elle renvoie null et on a une page d'erreur de base de Laravel.
16. Le préfixe pointe vers resources/views/errors et les vues d'erreur livrées avec le framework. errors/404.blade.php l'emporte car son dossier est en premier dans la liste (config('view.paths'))
17. La méthode handleMatchedRoute de AbstractRouteCollection lance NotFoundHttpException avec le message The route could not be found, comme avec abort(404) donc le même chemin.
18. /game/999 affiche la page 403 avec abort(403) (page 403 default de Laravel/page Forbidden). La vue errors::403 est trouvée dans le dossier des vues du framework (pas dans resources/views/errors).
19. abort(404) délègue à Application::abort qui lance NotFoundHttpException. Le Handler (renderHttpException) la rattrape, enregistre les dossiers errors:: puis getHttpExceptionView construit errors::404.
20. La vue est trouvée en premier dans resources/views/errors donc c'est notr page WishFlix 404 qui s'affiche. Une URL sans route suit la meme fin avec la même exception lancée par AbstractRouteCollection::handleMatchedRoute.

# Exercice 03 :
1. $this->table ?? ... : comme Game ne définit pas de propriété $table, la valeur de gauche est null donc Laravel utilise la valeur de droite qui est le nom de la table calculé à partir du nom de la classe.
2. class_basename(App/Models/Game::class) renvoie "Game"
   Str::pluralStudly('Game') renvoie "Games"
   Str::snake('Games') renvoie "games"
   (new App\Models\Game)->getTable() renvoie "games"

3. Pour un modèle GameReview, le nom de table est game-reviews
4. protected $table = 'table_name'; car comme getTable renvoie $this->table quand il n'est pas null (question 1), la valeur définie l'emporte sur le calcul.
5. Dans belongsToMany, le test is_null(table) est vrai quand on pase pas de nom et laravel appelle $this->joiningTable($related, $instance) pour deviner le nom.
6. LE commentaire explique que la table pivot est formée des noms des deux modèles en snake case triés alphabétiquement et joints par "_". Les deux segments sont "platform" (modele lié) et "game" (modele courant).
7. sort($segments) range les deux segments alphabétiquement, on obtient ['game', 'platform'].
   implode('_', $segments) les joint avec "_" et strtolower les mets en minuscule. : game_platform.
8. Depuis Platform::games(); les segments avant le tri sont ['game','platform'] ou ['platform','game'] selon l'ordre dans lequel ils sont empilés. Après sort, on aura toujours ['game','platform']. Sans le tri, la relation chercherait game_platform et l'autre platform_game et ne trouveraient pas la même table. Avec le tri alphabétique, on garantit le même nom.
9. (new App\Models\Game)->joiningTable(App\Models\Platform::class) (avec un use avant si nécessaire) renvoei "game_platform"
10. Les segments sont user et game donc le nom attendu est game_user
11. Game n'a pas besoin de public $title car quand on lit une propriété qui n'existe pas dans l'objet, PHP appelle automatiquement __get($key) et donc title n'a pas besoin d'être déclaré. La méthode redirige la lecture vers getAttribute($key)
12. if ($this->hasAttribute(key)) trie les deux cas (colonne/relation) car dans le cas colonne : getAttributeValue($key) sinon cas relation : getRelationValue($key).
    les valerus des colonnes sont stockées dans le tableau $this->attributes du modèle.
13. $game->title : title est une colonne donc hasAttribute('title') est vrai et getAttribute va par getAttributeValue qui lit la valeur dans $attributes
    $game->category n'est pas une colonne, getAttribute passe par getRelationValue('category') qui finit par getRelationshipFromMethod.
14. La méthode category() du modèle Game : ligne $relation = $this->$method() l'appelle avec $method qui vaut 'category'.
15. SI la methode ne renvoie pas un objet Relation, Laravel lance une LogicException. Le message dit que la méthode doit renvoyer une instance de relation mais qu'elle a renvoyé autre autre chose et suggère d'avoir oublié le mot-clé return.
16. Avant la lecture de $g->category, relationLoaded('category') renvoie false et après il renvoie true. La lecture déclenche la requête et range le résultat dans le tableau $this->relations. (la ligne en question est $this->setRelation($method, $results) dans le tap de getRelationshipFromMethod.
17. On a 2 requêtes : une pour Game::first() et une pour cahrger la catégorie. La deuxième leture de $g->category n'ajoute rien car la relation est déjà en mémoire (getRElationValue voit relationLoaded('category') vrai et renvoie $this->relations['category'] sans requête.
18. Avec 20 jeux, on a 21 requêtes, car il y en a un en plus pour Game::all() (en plus des 20 jeux) pour lire $game->category.
19. Quand la vue lit $game->category, PHP ne trouve pas de propriété et appelle Model::__get qui délègue à getRelationValue qui regarde si la relation est déjà en mémoire et sinon appelle getRelationshipFromMethod qui elle même appelle category() de notre modèle, exécute la requête SQL avec getResults() et range le résultat dans $this->relations avec setRelation. Les lectures réutilisent la catégorie en mémoire sans nouvelle requête.

# Exercice 04 : 

On note 49 queries avec 
select *
from `categories`
where `categories`.`id` = 4
limit 1
---
select `platforms`.*,
  `game_platform`.`game_id` as `pivot_game_id`,
  `game_platform`.`platform_id` as `pivot_platform_id`
from `platforms`
  inner join `game_platform` on `platforms`.`id` = `game_platform`.`platform_id`
where `game_platform`.`game_id` = 5

qui se répète
et après modification, on se retrouve avec 11 queries, soit 38 de moins.
