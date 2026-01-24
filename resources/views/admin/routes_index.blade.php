<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>All Routes</title>
    </head>
    <body>
        <h1>All Routes</h1>

        <table border="1">
            <thead>
                <tr>
                    <th>Route Path</th>
                    <th>Route Name</th>
                    <th>Controller Action</th>
                    <th>Methods</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($routeDetails as $route)
                    <tr>
                        <td>{{ $route['uri'] }}</td>
                        <td>{{ $route['name'] }}</td>
                        <td>{{ $route['action'] }}</td>
                        <td>{{ implode(', ', $route['methods']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
