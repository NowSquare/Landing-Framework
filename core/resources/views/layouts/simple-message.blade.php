<!DOCTYPE html>
<html>
    <head>
        <title>{{ $msg }}</title>

        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #555;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
          
            .material-icons {
                font-size: 120px;
                margin-bottom: 0px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
<?php if (isset($icon)) { ?>
                <i class="material-icons">{{ $icon }}</i>
<?php } ?>
                <div class="title">{!! $msg !!}</div>
            </div>
        </div>
    </body>
</html>
