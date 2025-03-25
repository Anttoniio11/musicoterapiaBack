<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $audio['title'] }}</title>
</head>
<body>
    <h1>{{ $audio['title'] }}</h1>
    <p>ID: {{ $audio['id'] }}</p>
    <p>Descripción: {{ $audio['description'] ?? 'Sin descripción disponible' }}</p>

    <!-- Mostrar la imagen del audio -->
    @if(!empty($audio['image_file']))
        <img src="{{ $audio['image_file'] }}" alt="{{ $audio['title'] }}" style="width: 300px; height: auto;">
    @else
        <p>No hay imagen disponible para este audio.</p>
    @endif
    <br>

    <!-- Mostrar el archivo de audio -->
    @if(!empty($audio['audio_file']))
        <audio controls>
            <source src="{{ $audio['audio_file'] }}" type="audio/mpeg">
            Tu navegador no soporta la reproducción de audio.
        </audio>
    @else
        <p>No hay archivo de audio disponible.</p>
    @endif

    <!-- Mostrar el género del audio si existe -->
    @if(isset($audio['genre']))
        <p>Género: {{ $audio['genre']['name'] }}</p>
    @else
        <p>Este audio no tiene un género asociado.</p>
    @endif

    <!-- Mostrar el álbum del audio si existe -->
    @if(isset($audio['album']))
        <p>Álbum: {{ $audio['album']['title'] }}</p>
    @else
        <p>Este audio no está asociado a ningún álbum.</p>
    @endif

    <!-- Mostrar las playlists si existen -->
    @if(isset($audio['playlists']) && count($audio['playlists']) > 0)
        <h2>Playlists que contienen este audio:</h2>
        <ul>
            @foreach($audio['playlists'] as $playlist)
                <li>{{ $playlist['name'] }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay playlists asociadas a este audio.</p>
    @endif

    <!-- Mostrar los tags si existen -->
    @if(isset($audio['tags']) && count($audio['tags']) > 0)
        <h2>Tags:</h2>
        <ul>
            @foreach($audio['tags'] as $tag)
                <li>{{ $tag['name'] }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay tags asociados a este audio.</p>
    @endif

    <!-- Mostrar los likes si existen -->
    @if(isset($audio['likes']) && count($audio['likes']) > 0)
        <h2>Likes:</h2>
        <p>{{ count($audio['likes']) }} personas han dado like a este audio.</p>
    @else
        <p>Nadie ha dado like a este audio.</p>
    @endif

    <!-- Mostrar el historial de reproducciones si existe -->
    @if(isset($audio['histories']) && count($audio['histories']) > 0)
        <h2>Historial de reproducciones:</h2>
        <ul>
            @foreach($audio['histories'] as $history)
                <li>{{ $history['created_at'] }} - Reproducido por: {{ $history['user']['name'] ?? 'Anónimo' }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay historial de reproducciones para este audio.</p>
    @endif

</body>
</html>
