
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Podcasts</title>
</head>
<body>
    <h1>Podcasts</h1>

    <!-- Mostrar la lista de títulos de podcasts -->
    @if(isset($podcasts) && count($podcasts) > 0)
        <ul>
            @foreach($podcasts as $podcast)
                <li>
                    <a href="{{ route('podcasts.show',$podcast) }}">{{ $podcast['title'] }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No se encontraron podcasts.</p>
    @endif
</body>
</html>
