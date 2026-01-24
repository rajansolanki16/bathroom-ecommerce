<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PayU Payment</title>
</head>

<body>
    <form action="{{ $url }}" method="POST" id="payu_form">
        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}"><br>
        @endforeach
    </form>

    <script>
        document.getElementById('payu_form').submit();
    </script>
    </script>
</body>

</html>