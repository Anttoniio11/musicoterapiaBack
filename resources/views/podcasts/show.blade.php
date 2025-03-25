<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $podcast['title'] }}</title>
</head>
<body>
    <h1>{{ $podcast['title'] }}</h1>
    <p>ID: {{ $podcast['id'] }}</p>
    <p>Descripción: {{ $podcast['description'] ?? 'Sin descripción disponible' }}</p>

    <!-- Mostrar la imagen del podcast -->
    @if(!empty($podcast['image_file']))
        <img src="{{ $podcast['image_file'] }}" alt="{{ $podcast['title'] }}" style="width: 300px; height: auto;">
    @else
        <p>No hay imagen disponible para este podcast.</p>
    @endif
    <br>

    <!-- Mostrar el archivo de video -->
    @if(!empty($podcast['video_file']))
        <video width="400" controls>
            <source src="{{ $podcast['video_file'] }}" type="video/mp4">
            Tu navegador no soporta la reproducción de video.
        </video>
    @else
        <p>No hay video disponible para este podcast.</p>
    @endif

    <!-- Mostrar el tiempo de duración -->
    <p>Duración: {{ $podcast['duration'] }} segundos</p>

    <!-- Mostrar las playlists si existen -->
    @if(isset($podcast['playlists']) && count($podcast['playlists']) > 0)
        <h2>Playlists que contienen este podcast:</h2>
        <ul>
            @foreach($podcast['playlists'] as $playlist)
                <li>{{ $playlist['name'] }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay playlists asociadas a este podcast.</p>
    @endif

    <!-- Mostrar los tags si existen -->
    @if(isset($podcast['tags']) && count($podcast['tags']) > 0)
        <h2>Tags:</h2>
        <ul>
            @foreach($podcast['tags'] as $tag)
                <li>{{ $tag['name'] }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay tags asociados a este podcast.</p>
    @endif

    <!-- Mostrar los likes si existen -->
    @if(isset($podcast['likes']) && count($podcast['likes']) > 0)
        <h2>Likes:</h2>
        <p>{{ count($podcast['likes']) }} personas han dado like a este podcast.</p>
    @else
        <p>Nadie ha dado like a este podcast.</p>
    @endif

    <!-- Mostrar el historial de reproducciones si existe -->
    @if(isset($podcast['histories']) && count($podcast['histories']) > 0)
        <h2>Historial de reproducciones:</h2>
        <ul>
            @foreach($podcast['histories'] as $history)
                <li>{{ $history['created_at'] }} - Reproducido por: {{ $history['user']['name'] ?? 'Anónimo' }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay historial de reproducciones para este podcast.</p>
    @endif
</body>
</html>
