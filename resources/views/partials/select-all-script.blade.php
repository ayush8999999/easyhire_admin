<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectAll = document.querySelector('.select-all-checkbox');
    const checkboxes = document.querySelectorAll('.row-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    }
});
</script>
