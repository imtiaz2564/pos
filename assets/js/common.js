if ($.active > 0){
    $(document).ajaxStop(function(){
        $('#loader').fadeOut('fast');
    });
}else{
    $(document).ready(function(){
        $('#loader').fadeOut('fast');
    });
}
$(function(){
    $('#side-menu').metisMenu();

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().addClass('active').parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});

function initialize(){
    $('select').chosen();
    $('.datetime').datetimepicker({
        pick12HourFormat: false
    })
    $('.date').datetimepicker();
}
initialize();