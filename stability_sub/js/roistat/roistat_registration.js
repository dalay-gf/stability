function getCookiem(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : '';
}
jQuery(document).ready(function() {
    jQuery('form[action="/ru/user/register"]').submit(function() {
        var data = {};
        jQuery(this).find('input, textearea, select').each(function() {
            // добавим новое свойство к объекту $data
            // имя свойства – значение атрибута name элемента
            // значение свойства – значение свойство value элемента
            data[this.name] = jQuery(this).val();
        });
        var username = data['field_firstname[und][0][value]'];       
        var email = data['mail'];
        var phone = data['field_telefon[und][0][value]'];
        var title = "Регистрация на giorgio-ferretti.it/ru/user/register";
        var registration = "registration";
        var res = {
            leadName: title,
            
            name: username,
            phone: phone,
            email: email,

            roistat: getCookiem('roistat_visit'),

            fields: {
                TITLE: title,
                ASSIGNED_BY_ID: 129,
                UF_CRM_1543328099: registration
            }
        };

        roistatGoal.reach(res);


    });


});
