<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
        <h1>hello {{$mailData['employer']->name}}</h1>

        <p>Job title:{{$mailData['jobs']->title}}</p>

        <p>employe Details:</p>

        <p>name:{{$mailData['user']->name}}</p>
        <p>email:{{$mailData['user']->email}}</p>
        <p>mobile no:{{$mailData['user']->mobile}}</p>
</body>
</html>
