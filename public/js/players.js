var selectAllCheckbox = document.getElementById('select-all');

if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('click', function() {
        var isChecked = this.checked;

        document.querySelectorAll('input[type="checkbox"]').forEach(function(e) {
            e.checked = isChecked;
        });
    });
}
