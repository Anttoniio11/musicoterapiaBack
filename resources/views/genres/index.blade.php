

<h1>Lista de Géneros</h1>
    <ul>
        @foreach($genres as $genre)
            <li>
                <a href="{{ route('genres.show', $genre['id']) }}">{{ $genre['name'] }}</a>
            </li>
        @endforeach
    </ul>