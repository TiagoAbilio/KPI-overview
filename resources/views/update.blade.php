<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T-Systems Brasil - SLA Update</title>
    <link href="{{ asset('css/estilo-sla.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/app.js') }}" defer></script>


</head>

<body>
    @include('components.hearder')
    <div class="container">
        <div class="container" style="margin-top: 30px; border: solid 1px red; height: 600px; width: 80%;">
            <update-incident :datatable="{{$result}}"></update-incident>
        </div>
    </div>

</body>

</html>