import $ from "jquery";

$('[anchor-to] a').click(function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    url = url + '#' + $(this).closest('[anchor-to]').attr('anchor-to');
    window.location.href = url;
});
