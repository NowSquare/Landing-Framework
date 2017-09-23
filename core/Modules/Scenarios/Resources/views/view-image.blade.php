<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta http-equiv="cleartype" content="on">

	<style type="text/css">
	html, body {
		height:100%;
	}
    body {
        background-image: url("{{ $image }}");
        background-repeat: no-repeat;
        background-position: center center;
        background-attachment: fixed;
<?php if (1==1) { ?>
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
<?php } ?>
        /* Small Devices, Tablets */
        @media only screen and (max-width : 1280px) {
            background-attachment:scroll;
        }
    }
    </style>

	<body>

	</body>
</html>