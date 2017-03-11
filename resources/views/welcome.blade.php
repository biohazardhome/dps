<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DPS Map</title>

        <link href="/css/preloader.css" rel="stylesheet" type="text/css">

        <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
        <script src="/js/jquery.1.11.js" type="text/javascript"></script>
        <script src="/js/jquery-functions.js" type="text/javascript"></script>

        <script src="/js/map.js" type="text/javascript"></script>
        <script src="/js/map-placemark.js" type="text/javascript"></script>
        
        <script src="/js/api/api.js" type="text/javascript"></script>
        <script src="/js/api/user.js" type="text/javascript"></script>
        <script src="/js/api/placemark.js" type="text/javascript"></script>
        <script src="/js/api/placemark-type.js" type="text/javascript"></script>
        
        <script src="/js/templates/form.js" type="text/javascript"></script>
        <script src="/js/templates/placemark.js" type="text/javascript"></script>
        
        <script src="/js/role.js" type="text/javascript"></script>
        <script src="/js/policy/placemark.js" type="text/javascript"></script>
        
        <style>
            html, body, #map {
                width: 100%; height: 100%; padding: 0; margin: 0;
            }

            .preloader {
                z-index: 1;
            }

            .preloader .cp-spinner {
                /*margin-top: 45%;*/
                position: relative;
                top: 45%;
            }
        </style>
        <script>

            var placemarks = [];
            var placemarkId = 0;

            


            $.ajaxSetup({
                /*headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }*/
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            var user;
            var userPromise = UserApi.auth(function(data) {
                user = data;
                // console.log(user);
            });

            var placemarkTypes;
            var placemarkTypesPromise = PlacemarkTypeApi.all(function(data) {
                placemarkTypes = data;
                // console.log('placemark-type', data);
            });
            
            
            ymaps.ready(init);

            function init() {

                var policy = new PlacemarkPolicy(user);

                var geolocation = ymaps.geolocation,
                    myMap = Map.create({
                        center: [50.08964612340766, 45.402043221301966],
                        zoom: 13
                    });
                    /*myMap = new ymaps.Map('map', {
                        // center: [55, 34],
                        center: [50.08964612340766, 45.402043221301966],
                        zoom: 13
                    }, {
                        searchControlProvider: 'yandex#search'
                    });*/

                PlacemarkMap.init(myMap, policy, '{{ csrf_field() }}');       

                Promise.all([userPromise, placemarkTypesPromise]).then(function() {
                    PlacemarkMap.render();
                    PlacemarkMap.refresh();
                });

                navigator.geolocation.getCurrentPosition(function(position) {
                    var location = [position.coords.latitude, position.coords.longitude];

                    var myGeoObject = new ymaps.GeoObject({
                        // Описание геометрии.
                        geometry: {
                            type: 'Point',
                            coordinates: location
                        },
                        // Свойства.
                        properties: {
                            // Контент метки.
                            iconContent: 'Я тащусь',
                            hintContent: 'Ну давай уже тащи'
                        }
                    }, {
                        // Опции.
                        // Иконка метки будет растягиваться под размер ее содержимого.
                        preset: 'islands#blackStretchyIcon',
                        // Метку можно перемещать.
                        draggable: true
                    });

                    // console.log(location);

                    myMap.setCenter(location);

                    myMap.geoObjects.add(myGeoObject)
                });
            }
        </script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <!-- <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
        
            .full-height {
                height: 100vh;
            }
        
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }
        
            .position-ref {
                position: relative;
            }
        
            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
        
            .content {
                text-align: center;
            }
        
            .title {
                font-size: 84px;
            }
        
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
        
            .m-b-md {
                margin-bottom: 30px;
            }
        </style> -->
    </head>
    <body>
        <!-- <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif
        
            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>
        
                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div> -->

        <div id="map">
            <div class="preloader">
                <div class="cp-spinner cp-round"></div>
            </div>
        </div>

    </body>
</html>
