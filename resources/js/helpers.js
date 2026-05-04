export const spinner = () => {
    return '<div class="d-flex justify-content-center">' +
        '<div class="mt-5 spinner-border text-light" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
        '</div>' +
    '</div>';
}

export const formatSQLDate = (date_string) => {
    const expl_date = date_string.split('-');
    return `${expl_date[2]}/${expl_date[1]}/${expl_date[0]}`;
}

export const formSerializeObject = function (form_selector){
    const results = {},
        arr = $(form_selector).serializeArray();

    for (let i = 0, len = arr.length; i < len; i++) {
        const obj = arr[i];

        // Check if results have a property with given name
        if (results.hasOwnProperty(obj.name)) {
            // Check if given object is an array
            if (!results[obj.name].push) {
                results[obj.name] = [results[obj.name]];
            }
            results[obj.name].push(obj.value || '');
        } else {
            results[obj.name] = obj.value || '';
        }
    }

    return results;
}
