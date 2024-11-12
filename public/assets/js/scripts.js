//Loader

$(window).on("load", function () {
    $("#status").fadeOut(), $("#preloader").delay(350).fadeOut("slow")
})
function preloader() {
    $('#preloader > #status').fadeIn();
    $("#preloader").fadeIn();
}

function preloaderOff() {
    $('#preloader > #status').fadeOut();
    $("#preloader").fadeOut();
}

$(document).on('submit', 'form:not(".noLoader")', function () {
    preloader();
});

$(document).on('click', 'showLoader', function () {
    preloader();
});

// Initialize Select2 | Popover
$('[data-toggle="select2"]').select2();

"use strict";
[].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function (e) {
    return new bootstrap.Popover(e)
});

// Initialize Clipboard
let clipboard = new ClipboardJS('.btn');

clipboard.on('success', function (e) {
    $(e.trigger).tooltip('hide')
        .attr('data-bs-original-title', 'Copied')
        .tooltip('show');
    setTimeout(function () {
        $(e.trigger).tooltip('hide');
        $(e.trigger).tooltip('hide')
            .attr('data-bs-original-title', 'Click to Copy')
    }, 1000);
});

$("#datetime-datepicker").flatpickr({
    enableTime: !0,
    dateFormat: "Y-m-d H:i"
})
// Filter Form
window.shouldExport = false;
$('#filterForm').on('submit', function () {
    if (shouldExport) {
        let filter = {};

        $('#filterForm input[type=text], #filterForm input[type=number], #filterForm input[type=date], #filterForm select').each(function (i, el) {
            let input = $(el);
            let propertyName = input.prop('name');

            if (input.val()) {
                filter[propertyName] = input.val();
            }
        });

        window.location = route(route().current(), {...route().params, filter: filter, "export": 'csv'});
    }

    dataTable.draw();

    return false;
});

// DataTable default config
$.extend(true, $.fn.dataTable.defaults, {
    searching: false,
    ordering: false,
    orderCellsTop: true,
    fixedHeader: true,
    processing: true,
    serverSide: true,
    ajax: {
        data: function (data) {
            delete data.columns;
            delete data.search;
            data['filter'] = {};

            $('#filterForm input[type=text], #filterForm input[type=number], #filterForm input[type=date], #filterForm select').each(function (i, el) {
                let input = $(el);
                let propertyName = input.prop('name');

                if (input.val()) {
                    data['filter'][propertyName] = input.val();
                }
            });
        },
    },
    drawCallback: function (settings) {
        let pageInfo = dataTable.page.info();
        let totalPages = pageInfo.pages;

        let html = `
            <div class="dataTables_length">
                <label>
                    Go to Page
                    <select name="datatable_page_list" id="dataTablePageList" class="custom-select custom-select-sm form-control form-control-sm">`;

        for (let count = 1; count <= totalPages; count++) {
            let pageNumber = count - 1;
            let selected = (parseInt(pageInfo.page) === parseInt(pageNumber)) ? 'selected' : '';

            html += `<option value="${pageNumber}" ${selected}>${count}</option>`;
        }

        html += `
                    </select>
                    of ${totalPages}
                </label>
            </div>`;

        $('.dataTablesPageSelect').html(html);
    },
    dom: `
            <'row'
                <'col-sm-12 col-md-6 col-6'l>
                <'col-sm-12 col-md-6 col-6 text-right dataTablesPageSelect'>
            >
            <'row'
                <'col-sm-12 col-12'tr>
            >
            <'row'
                <'col-sm-12 col-md-5 col-12'i>
                <'col-sm-12 col-md-7 col-12 dataTables_pager'p>
            >
        `,
    pageLength: 10,
    searchDelay: 1000,
    lengthMenu: [
        10, 25, 50, 100, 250
    ]
});

$('body').on('change', '#dataTablePageList', function () {
    dataTable.page(
        parseInt(this.value)
    ).draw('page');
});


// Member Name auto display by code
window.getMemberName = function (el) {
    let code = el.val();
    if (el.parents('.form-floating').length) {
        el = el.parents('.form-floating').first();
    }

    if (code.length >= 6) {
        $.get({
            url: route('members.show', code),
            success: function (res) {
                el.siblings('.memberName').remove();
                el.after(
                    '<span class="help-block memberName text-primary font-weight-bold">' + res.user.name + '</span>'
                );
            },
            error: function (res) {
                el.siblings('.memberName').remove();
                if (res.status === 404) {
                    el.after(
                        '<span class="help-block memberName text-danger font-weight-bold">Member not found</span>'
                    );
                } else {
                    el.after(
                        '<span class="help-block memberName text-danger font-weight-bold">Something went wrong, please try again.</span>'
                    );
                }
            }
        });
    } else {
        el.siblings('.memberName').remove();
        if (code.length > 0) {
            el.after(
                '<span class="help-block memberName text-danger font-weight-bold">Member not found</span>'
            );
        }
    }
};

$('.memberCodeInput').each(function () {
    let el = $(this);
    if (el.val()) {
        getMemberName(el);
    }
});

$('.memberCodeInput').on('input', function () {
    getMemberName($(this));
});

function max_length(obj, e, max) {
    e = e || event;
    max = max;
    console.log(e.keyCode);
    if (e.keyCode === 13) {
        event.preventDefault();
    }
    if (obj.value.length >= max && e.keyCode > 46) {
        return false;
    }
    return true;
}

$(".resrtictZero").on("input", function () {
    if (/^0/.test(this.value)) {
        this.value = this.value.replace(/^0/, "")
    }
});

Ladda.bind('button[type="submit"]:not([name="filter"],[name="export"])');

function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}

$('ul.menu-inner').find('li > a.menu-link').each((index, element) => {
    let el = $(element);

    if (el.attr('href') === window.location.href) {
        el.parent().addClass('active');

        if ($('html').attr('data-template') === 'vertical-menu-template-bordered') {
            // Attribute exists, do something with it
            el.parents('.menu-sub').each((_, parent) => {
                $(parent).parent().addClass('open');
            });
        }
    } else {
        el.parent().removeClass('active');
    }
});
