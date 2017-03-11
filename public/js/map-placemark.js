var PlacemarkMap = {

    TIMEOUT_REFRESH: 5 * 60 * 1000,

    map: null,
    policy: null,
    csrfToken: null,
    placemarkId: 0,

    init: function(map, policy, token) {
        this.policy = policy;
        this.map = map;
        this.csrfToken = token;

        this.addButtonCreatePlacemark();

        Map.addButton('Обновить', function() {
            this.render();
        }.bind(this));
    },

    render: function() {
        var preloaderEl = $('.preloader');

        preloaderEl.show();
        
        PlacemarkApi.all(function(placemarks) {

            this.splitOnClusters(placemarks);


            /*var objects = this.prepareData(placemarks),
                clusterer = Map.clusterer();

            clusterer.options.set({
                gridSize: 80,
                clusterDisableClickZoom: true
            });

            cluster.options.set({
                clusterIcons: [{
                    // href: 'images/cat.png',
                    href: this.icon(placemarks, placemarkTypes),
                    size: [40, 40],
                    offset: [-20, -20]
                }],
            });

            clusterer.add(objects);

            this.map.geoObjects.add(clusterer);

            this.map.setBounds(clusterer.getBounds(), {
                checkZoomRange: true
            });*/

            // console.log('all');

            preloaderEl.hide();
        }.bind(this));
    },

    splitOnClusters: function(placemarks) {
        var placemarksGroups = this.groupByType(placemarks);
        // console.log(placemarksGroups);

        for (var index in placemarksGroups) {
            var placemarksGroup = placemarksGroups[index];

            // console.log(placemarksGroup, index)

            var objects = this.prepareData(placemarksGroup);

            clusterer = this.clusterer(objects, parseInt(index, 10));

            this.map.geoObjects.add(clusterer);

            /*this.map.setBounds(clusterer.getBounds(), {
                checkZoomRange: true
            });*/
        }
    },

    clusterer: function(objects, typeId) {
        var clusterer = Map.clusterer();

        clusterer.options.set({
            gridSize: 80,
            clusterDisableClickZoom: true
        });

        /*clusterer.options.set({
            clusterIcons: [{
                // href: 'images/cat.png',
                href: this.icon(typeId, placemarkTypes),
                size: [40, 40],
                offset: [-20, -20]
            }],
        });*/

        clusterer.add(objects);

        return clusterer;
    },

    groupByType: function(placemarks) {
        var items = {};

        for (var index in placemarks) {
            var placemark = placemarks[index];

            if (!items[placemark.type_id]) {
                items[placemark.type_id] = [];
            }
            items[placemark.type_id].push(placemark);
        }

        return items;
    },

    refresh: function() {
        setInterval(function() {
            this.render();
        }.bind(this), this.TIMEOUT_REFRESH);
    },

    openBalloon: function(coords, header, body, fn) {
        this.map.balloon.open(coords, {
            contentHeader: header,
            contentBody: body,
            // contentFooter:'<sup>Щелкните еще раз</sup>'
        }).then(function() {
            fn();
        });
    },

    onBalloonOpen: function() {
        this.map.balloon.events.add('open', function() {
            this.onShowEdit();
            // console.log('balloon open');
        }.bind(this));
    },

    onShowEdit: function() {
        $('.placemark-panel .placemark-edit-show').on('click', function(e) {
            var id = $(e.target).data('id');
            
            PlacemarkApi.find(id, function(placemark) {
                var header = 'Редактировать метку',
                    body = PlacemarkTemplate.balloonEdit(placemark, placemarkTypes, this.csrfToken);

                this.openBalloon(placemark.coords, header, body, function() {
                    this.placemarkUpdate(id);
                }.bind(this));

            }.bind(this));

           return false;
        }.bind(this));
    },

    placemarkAction: function(fn) {
        var formEl = $('.placemark-form');

        formEl.find('button[type="submit"]').on('click', function(e) {
            var data = formEl.serializeObject();

            fn(data);
            
            e.preventDefault();
            return false;
        });
    },

    placemarkStore: function() {
        this.placemarkAction(function(data) {
            PlacemarkApi.store(data, function() {
                console.log('success created placemark');
                this.map.balloon.close();                               
            }.bind(this));
        }.bind(this));
    },

    placemarkUpdate: function(id) {
        this.placemarkAction(function(data) {
            PlacemarkApi.update(id, data, function() {
                console.log('success update placemark'+ id);
                this.map.balloon.close();
            }.bind(this));
        }.bind(this));
    },

    addPlacemark: function(coords, id, body, content, header, footer, data) {
        var placemark = new ymaps.Placemark(coords, {
            // Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
            /*balloonContentHeader: "Балун метки",
            balloonContentBody: "Содержимое <em>балуна</em> метки",
            balloonContentFooter: "Подвал",
            hintContent: "Хинт метки"*/

            balloonContentHeader: header,
            balloonContentBody: body,
            balloonContentFooter: footer,
            hintContent: content,
            iconContent: content,
            id: id, // для получения id
        }, {
            draggable: true,
            preset: 'islands#violetStretchyIcon',
            // Необходимо указать данный тип макета.
            iconLayout: 'default#image',
            // Своё изображение иконки метки.
            // iconImageHref: 'img/icon/police.png',
            iconImageHref: this.icon(data.type_id, placemarkTypes),
            // Размеры метки.
            iconImageSize: [60, 60],
            // Смещение левого верхнего угла иконки относительно
            // её "ножки" (точки привязки).
            iconImageOffset: [-30, -30]
        });

        placemark.events.add('dragend', function(e) {
            if (!user && !this.policy.universal()) return false;
            /*var target =  e.get('target'),
                type = e.get('type');

            console.log(target.getGeoObjects, target, type)*/
            
            var id = placemark.properties.get('id');
                coords = JSON.stringify(placemark.geometry.getCoordinates());

            PlacemarkApi.updateCoords(id, {coords: coords}, function(data) { // move
                console.log('success store placemark move', data);
            });
        });

        placemark.events.add('balloonopen', function() {
            this.onShowEdit();
        }.bind(this));

        return placemark;
    },

    prepareData: function (items) {
        var geoObjects = [];

        items.forEach(function(item) {
            var balloon = PlacemarkTemplate.balloonInfo(item, this.policy);
            var placemark = this.addPlacemark(item.coords, item.id, balloon, item.type.name/* +' | '+ item.name*/, item.type.name, null, item);
            
            geoObjects.push(placemark);
        }.bind(this));

        return geoObjects;
    },

    addButtonCreatePlacemark: function() {
        // console.log(!this.policy.universal(), !user, user,  Boolean(user));
        if (!user && !this.policy.universal()) return null;

        /*var createButton = new ymaps.control.Button("Создать метку");
        createButton.events.add('click', this.handlerButtonCreatePlacmark.bind(this));

        this.map.controls.add(createButton, {float: 'right'});*/

        var button = Map.addButton('Создать метку');

        button.events.add('click', function(e) {
            this.handlerButtonCreatePlacmark(e);
        }.bind(this));

        /*button.events.add('deselect', function() {
            this.map.events.remove('click', this.handlerCreatePlacmark.bind(this));
        }.bind(this));*/
    },

    handlerButtonCreatePlacmark: function(e) {
        var myMap = this.map;

        myMap.events.once('click', this.handlerCreatePlacmark.bind(this));
    },

    handlerCreatePlacmark: function(e) {
        var coords = e.get('coords'),
            header = 'Создать метку',
            body = PlacemarkTemplate.balloonCreate(JSON.stringify(coords), placemarkTypes, this.csrfToken);

        this.openBalloon(coords, header, body, function() {
            this.placemarkStore();
        }.bind(this));

        var placemark = this.addPlacemark(coords, null, this.placemarkId, this.placemarkId +' текст');
        myMap.geoObjects.add(placemark);
        this.placemarkId++;
    },

    // icon: function(placemark, placemarkTypes) {
    icon: function(placemarkTypeId, placemarkTypes) {
        var type;
        
        for (var index in placemarkTypes) {
            // console.log(placemarkTypes[index].id, placemarkTypeId)
            // if (placemarkTypes[index].id === placemark.type_id) {
            if (placemarkTypes[index].id === placemarkTypeId) {
                type = placemarkTypes[index];
            }
        }
        return 'img/icon/'+ type.slug +'.png';
    },

    getPolicy: function() {
        return this.policy;
    },

};




