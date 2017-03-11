<?php


?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	<script src="jquery.1.11.js" type="text/javascript"></script>
	<style>
        html, body, #map {
            width: 100%; height: 100%; padding: 0; margin: 0;
        }
    </style>
	<script>
		var placemarkId = 0;

		function addPlacemark(coords, body, content, header, footer) {
			return new ymaps.Placemark(coords/*[55.907228, 31.260503]*/, {
	            // Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
	            /*balloonContentHeader: "Балун метки",
	            balloonContentBody: "Содержимое <em>балуна</em> метки",
	            balloonContentFooter: "Подвал",
	            hintContent: "Хинт метки"*/
	            balloonContentHeader: header,
	            balloonContentBody: body,
	            balloonContentFooter: footer,
	            hintContent: content,
	        });
		}

		ymaps.ready(init);

			function init() {
			    var geolocation = ymaps.geolocation,
			        myMap = new ymaps.Map('map', {
			            // center: [55, 34],
			            center: [50.08964612340766, 45.402043221301966],
			            zoom: 13
			        }, {
			            searchControlProvider: 'yandex#search'
			        });



			    navigator.geolocation.getCurrentPosition(function(position) {
					var location = [position.coords.latitude, position.coords.longitude];

					var myGeoObject = new ymaps.GeoObject({
			            // Описание геометрии.
			            geometry: {
			                type: "Point",
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

					console.log(location);

					myMap.setCenter(location);

					myMap.geoObjects
				        .add(myGeoObject)
				});

				myMap.events.add('click', function (e) {
			        // if (!myMap.balloon.isOpen()) {
			            var coords = e.get('coords');

			            // console.log()
			            var coordsBalloon = coords.map((i) => {return i + 0.0003});

			            myMap.balloon.open(coordsBalloon, {
			                contentHeader:'Создать метку',
			                /*contentBody:'<p>Кто-то щелкнул по карте.</p>' +
			                    '<p>Координаты щелчка: ' + [
			                    coords[0].toPrecision(6),
			                    coords[1].toPrecision(6)
			                    ].join(', ') + '</p>',*/
			                contentBody:'<form class="placemark-add" action="/placemark-add" method="POST">\
			                	<!--<input type="hidden" name="coords" value="">-->\
								<select name="type">\
									<option value="">Машина ДПС</option>\
									<option value="">Авария</option>\
									<option value="">Шалавы))</option>\
								</select>\
								<br>\
								<input type="text" name="title" value="">\
								<br>\
								<input type="text" name="description" value="">\
								<br>\
								<button>Отправить</button>\
			                </form>',
			                contentFooter:'<sup>Щелкните еще раз</sup>'
			            }).then(function() {
			            	console.log($('.placemark-add').get(0))
			            });


			            // console.log(coords);
			            var placemark = addPlacemark(coords, placemarkId, placemarkId +' текст');
			            myMap.geoObjects.add(placemark);
			            placemarkId++;
			        /*}
			        else {
			            // myMap.balloon.close();
			        }*/
			    });




/*
			    // Сравним положение, вычисленное по ip пользователя и
			    // положение, вычисленное средствами браузера.
			    geolocation.get({
			        provider: 'yandex',
			        // provider: 'browser',
			        // mapStateAutoApply: true
			    }).then(function (result) {
			        // Красным цветом пометим положение, вычисленное через ip.
					console.log(result)
			        result.geoObjects.options.set('preset', 'islands#redCircleIcon');
			        
			        result.geoObjects.get(0).properties.set({
			            balloonContentBody: 'Мое местоположение'
			        });

			        // console.log(result.geoObjects.get(0).geometry._coordinates)
			        myMap.setCenter(result.geoObjects.get(0).geometry._coordinates)
			        myMap.geoObjects.add(result.geoObjects);
			    });

*/			    
			}
	</script>
</head>
<body>
	<div id="map"></div>
</body>
</html>
<!-- <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=k1QU4g3FxSrWYJLBBTc9fS_Rs8-fjmOq&amp;width=500&amp;height=400&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=true"></script> -->

