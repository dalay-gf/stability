function getCookiem(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : '';
}
jQuery(document).ready(function() {
    jQuery('form[action="/ru/invite"]').submit(function() {
        var data = {};
        jQuery(this).find('input, textearea, select').each(function() {
            // добавим новое свойство к объекту $data
            // имя свойства – значение атрибута name элемента
            // значение свойства – значение свойство value элемента
            data[this.name] = jQuery(this).val();
        });
        var phone = data['submitted[phone_number]'];
        var email = data['submitted[e_mail]'];
        var title = "Заявка с giorgio-ferretti.it/ru/invite";
        var res = {
            leadName: title,

            email: email,
            phone: phone,

            roistat: getCookiem('roistat_visit'),

            fields: {
                TITLE: title,
                ASSIGNED_BY_ID: 129
            }
        };


        roistatGoal.reach(res);


    });


});
