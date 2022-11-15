const filterForm = $('#filter-form');
initFilter();

function initFilter() {
    $('.select2').select2();

    new ClipboardJS('.clipboard-btn', {
        text: function (trigger) {
            return trigger.getAttribute('href');
        }
    });

    filterForm.submit(function () {
        filterForm.find("input").filter(function () {
            if (this.type === 'submit' || this.type === 'button')
                return false;

            if (this.value === '')
                return true;
        }).attr("disabled", "disabled");
        return true; // ensure form still submits
    });

    $(".single-project-trigger").click(function (e) {
        $('#single-project iframe').attr('src', e.currentTarget.dataset.href);
        $('#single-project').modal('show');
    });
}
