var PlacemarkTemplate = {

    storeEl: $('.placemark-store'),

    init: function() {

    },

	balloonCreate: function(coords, types, token) {
        return this.form(types, token, 'store', coords);

		/*return '<form class="placemark-form placemark-store" action="/placemark/store" method="POST">\
            <input type="hidden" name="coords" value="'+ coords +'">\
            '+ token +'\
            '+ select +'\
            <br>\
            <input type="text" name="name" value="">\
            <br>\
            <input type="text" name="description" value="">\
            <br>\
            <button type="submit">Отправить</button>\
        </form>';*/
	},

    balloonEdit: function(placemark, types, token) {
        return this.form(types, token, 'update', placemark.coords, placemark, placemark.id);
        /*
        return '<form class="placemark-form placemark-update" data-id="'+ placemark.id +'" action="/placemark/store" method="POST">\
            <input type="hidden" name="coords" value="'+ coords +'">\
            '+ token +'\
            '+ select +'\
            <br>\
            <input type="text" name="name" value="'+ placemark.name +'">\
            <br>\
            <input type="text" name="description" value="'+ placemark.description +'">\
            <br>\
            <button type="submit">Отправить</button>\
        </form>';*/
    },

    form: function(types, token, type, coords, placemark) {
        var id = '',
            name = '',
            description = '',
            select,
            selected;

        if (placemark) {
            id = placemark.id,
            name = placemark.name,
            description = placemark.description;

            if (!coords) {
                coords = placemark.coords;
            }

            coords = JSON.stringify(coords);

            /*if (type === 'create') {
                url = 'store';
            }*/
        }

        if (type === 'update') {
            selected = placemark.type_id;
        }

        select = Form.select('type_id', types, selected);

        return '<form class="placemark-form placemark-'+ type +'"  data-id="'+ id +'" action="/placemark/'+ type +'" method="POST">\
            <input type="hidden" name="coords" value="'+ coords +'">\
            '+ token +'\
            '+ select +'\
            <br>\
            <input type="text" name="name" value="'+ name +'">\
            <br>\
            <input type="text" name="description" value="'+ description +'">\
            <br>\
            <button type="submit">Отправить</button>\
        </form>';
    },

	balloonInfo: function(item, policy) {
		/*return '\
            Тип: '+ item.type.name +'<br>\
            Название: '+ item.name +'<br>\
            Создатель: '+ item.creater +'<br>\  
        ';*/

        var name = item.name || '',
            description = item.description || '',
            panel = this.balloonPanel(item, policy);

        return '\
            '+ panel +'\
            ID: '+ item.id +'<br>\
            Тип: '+ item.type.name +'<br>\
            Название: '+ name +'<br>\
            Описание: '+ description +'<br>\
        ';
	},

    balloonPanel: function(item, policy) {
        var panel = '\
            <div class="placemark-panel">\
                <a class="placemark-edit-show" data-id="'+ item.id +'" href="/placemark/edit/'+ item.id +'">Редактировать</a><br>\
                <a href="/placemark/delete/'+ item.id +'">Удалить</a><br>\
            </div>\
        ';

        return policy.universal(item) ? panel : '';
    },

    

}