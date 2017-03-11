
var Map = {

    map: null,

	create: function(options, id) {
        var optionsDefault = {center: [50.08964612340766, 45.402043221301966], zoom: 13};

		id = id || 'map';
        // console.log(id);
        // options = options || optionsDefault;
		options = $.extend(optionsDefault, options);

		return this.map = new ymaps.Map(id, options/*{
			center: [55.753994, 37.622093],
			zoom: 9
		}*/);
	},

	placemark: function(coordinate, data, options) {
		return new ymaps.Placemark(coordinate, this.placemarkData(data), {
			preset: 'islands#violetStretchyIcon',
			// hideIconOnBalloonOpen: false,
			// balloonCloseButton: false,
		});
	},

	geocode: function(request, options) {
		return ymaps.geocode(request, options);
	},

	clusterer: function() {
		var cluster = new ymaps.Clusterer({
            // Зададим массив, описывающий иконки кластеров разного размера.
            /*clusterIcons: [{
                href: 'images/cat.png',
                size: [40, 40],
                offset: [-20, -20]
            }],*/
            // Эта опция отвечает за размеры кластеров.
            // В данном случае для кластеров, содержащих до 100 элементов,
            // будет показываться маленькая иконка. Для остальных - большая.
            clusterNumbers: [10],
            // clusterIconContentLayout: null,
            /**
             * Через кластеризатор можно указать только стили кластеров,
             * стили для меток нужно назначать каждой метке отдельно.
             * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage.xml
             */
            preset: 'islands#invertedVioletClusterIcons',
            /**
             * Ставим true, если хотим кластеризовать только точки с одинаковыми координатами.
             */
            groupByCoordinates: false,
            clusterDisableClickZoom: true,
            clusterHideIconOnBalloonOpen: false,
            geoObjectHideIconOnBalloonOpen: false
        });



        return cluster;
	},

    addButton: function(title, callback, float) {
        float = float || 'right';

        var button = new ymaps.control.Button(title);

        if (callback) button.events.add('press', callback);

        this.map.controls.add(button, {float: float});

        return button;
    }
}