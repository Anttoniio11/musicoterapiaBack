<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $album['title'] }}</title>
</head>
<body>
    <h1>{{ $album['title'] }}</h1>
    <p>ID: {{ $album['id'] }}</p>
    <p>Descripción: {{ $album['description'] ?? 'Sin descripción disponible' }}</p>

    <!-- Mostrar la imagen almacenada en Cloudinary -->
    @if(!empty($album['image_path']))
        <img src="{{ $album['image_path'] }}" alt="{{ $album['title'] }}" style="width: 300px; height: auto;">
    @else
        <p>No hay imagen disponible para este álbum.</p>
    @endif

    <!-- Mostrar los audios relacionados con el álbum -->
    @if(isset($album['audios']) && count($album['audios']) > 0)
        <h2>Audios en este álbum:</h2>
        <ul>
            @foreach($album['audios'] as $audio)
                <li>{{ $audio['title'] }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay audios disponibles para este álbum.</p>
    @endif
</body>
</html>
