$(document).ready(function () {
    $('#disableButton').css('cursor', 'not-allowed');
});

$('body').on('change', '.productsCheckBox', function () {
    var checkboxes = document.querySelectorAll('.productsCheckBox');

    if (checkboxes.length == $('.productsCheckBox:checked').length) {
        document.getElementById('singleCheckbox').checked = true;
    } else {
        document.getElementById('singleCheckbox').checked = false;
    }

    if ($('.checkBox:checked').length > 0) {
        $('#disableButton').prop('disabled', false);
        $('#disableButton').css('cursor', 'pointer');
    } else {
        $('#disableButton').css('cursor', 'not-allowed');
        $('#disableButton').prop('disabled', true);
    }
});

function checkAll(ele) {
    var checkboxes = document.getElementsByTagName('input');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }
}
