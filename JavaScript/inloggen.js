function verbergFacturatie() {
    var checkBox = document.getElementById("checkFacturatie");
    var input = document.getElementById("facturatie");
    if (checkBox.checked == true) {
        input.style.display = "none";
    } else {
        input.style.display = "block";
    }
}

document.addEventListener('DOMContentLoaded', function () {

    const inputs = document.querySelectorAll('.toggle_required');
    const toggleCheckbox = document.getElementById('checkFacturatie');

    function toggleRequired() {
        const checkboxState = !toggleCheckbox.checked;
        inputs.forEach(input => {
            input.required = checkboxState;
        });
        verbergFacturatie();
    }

    toggleCheckbox.addEventListener('change', toggleRequired);

    toggleRequired();
});