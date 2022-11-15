$('.select2').select2();

new ClipboardJS('.clipboard-btn', {
    text: function (trigger) {
        return trigger.getAttribute('href');
    }
});

$(".single-blog-trigger").click(function (e) {
    $('#single-blog iframe').attr('src', e.currentTarget.dataset.href);
    $('#single-blog').modal('show');
});
