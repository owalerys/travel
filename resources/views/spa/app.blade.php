<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ mix('/css/spa/spa.css') }}" rel="stylesheet">

    <title>AgentRef</title>
</head>
<body>
<div id="app"></div>
<script src="{{ mix('/js/spa/spa.js') }}" type="text/javascript"></script>
</body>
</html>
