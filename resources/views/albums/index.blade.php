<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Álbumes</title>
</head>
<body>
    <h1>Lista de Álbumes</h1>

    @if(isset($albums) && count($albums) > 0)
        <ul>
            @foreach($albums as $album)
                <li>
                

                    <!-- Mostrar el título del álbum y enlace para ver detalles -->
                    <h2><a href="{{ route('album.show', $album['id']) }}">{{ $album['title'] }}</a></h2>
                    
                </li>
            @endforeach
        </ul>
    @else
        <p>No hay álbumes disponibles.</p>
    @endif
</body>
</html>
