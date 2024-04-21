<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Karsco</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/images/logo.png" />
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&family=Marhey:wght@600;700&display=swap" rel="stylesheet">
    <style>
        *:not(.fa, i),
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            font-family: 'Marhey', cursive;
            color: #373757;
        }

        .invalid-feedback * {
            color: #dc3545;
        }

        * {
            font-size: 13px !important;
        }

        body {
            margin: 0;
            background: rgba(88, 115, 254, 0.04);
        }

        /* width */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;

        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .swal2-icon-content {
            font-size: 25px !important;
        }

        .swal2-styled {
            width: 54px;
            margin: 0 5px !important;
        }

        .swal2-styled.swal2-confirm {
            background-color: #373063 !important;
        }
        .btn:focus {
            border-color: none !important;
        }
    </style>
</head>

<body id="body" dir="ltr">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <div id="app">
        <app></app>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>