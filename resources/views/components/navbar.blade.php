<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Hangman</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ Route::currentRouteNamed('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('dashboard')}}">Начало </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteNamed('select_category') || Route::currentRouteNamed('start_game') ? 'active' : '' }}" href="{{route('select_category')}}">Играй </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteNamed('statistic') ? 'active' : '' }}" href="{{route('statistic')}}">Статистики </a>
            </li>
        </ul>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">изход</button>
        </form>
    </div>
</nav>
