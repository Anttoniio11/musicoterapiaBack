<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $genre['name'] }}</title>
</head>
<body>
    <h1>{{ $genre['name'] }}</h1>
    <p>ID: {{ $genre['id'] }}</p>
    <p>Descripción: {{ $genre['description'] ?? 'Sin descripción disponible' }}</p>

    <!-- Mostrar la imagen almacenada en Cloudinary -->
    @if(!empty($genre['image_path']))
        <img src="{{ $genre['image_path'] }}" alt="{{ $genre['name'] }}" style="width: 300px; height: auto;">
    @else
        <p>No hay imagen disponible para este género.</p>
    @endif

    <!-- Mostrar los audios relacionados con el género -->
    @if(isset($genre['audios']) && count($genre['audios']) > 0)
        <h2>Audios en este género:</h2>
        <ul>
            @foreach($genre['audios'] as $audio)
                <li>{{ $audio['title'] }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay audios disponibles para este género.</p>
    @endif


</body>
</html>
