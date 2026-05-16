import './helpers/filters.js';
import './helpers/forms.js';

$(function() {
    $(document).on('show.bs.modal', '#modal-benef-form', e => {
        if ($(e.relatedTarget).data('action') === 'edit') {
            $('#benef-raw-name').prop('disabled', 'disabled');
        } else {
            $('#benef-raw-name').prop('disabled', '');
        }
    });
});
