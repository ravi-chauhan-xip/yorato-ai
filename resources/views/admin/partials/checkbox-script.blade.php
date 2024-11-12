<script>
    $(document).ready(function () {
        $('#disableButton').css('cursor', 'not-allowed');
    });

    $('body').on('change', '.form-check-input', function () {
        if ($('.form-check-input:checked').length > 0) {
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
</script>
