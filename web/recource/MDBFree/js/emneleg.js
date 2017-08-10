$('#burger-menu').click(function () {
    $(this).toggleClass('open');
    $('#search-tab').hide();
    $('#home-sub-menu').toggle();
    $('#search').children('i').removeClass('fa-remove').addClass('fa-search')
});

$('#search').click(function () {
    $('#home-sub-menu').hide();
    $('#search-tab').toggle();
    $('#burger-menu').removeClass('open');
    $(this).children('i').toggleClass('fa-remove');
});

$('#search-res').click(function () {
    $('#home-sub-menu').hide();
    $('#search-tab').toggle();
    $('#burger-menu').removeClass('open');
});

$('#searchremove').click(function () {
    $('#search-tab').hide();
});
