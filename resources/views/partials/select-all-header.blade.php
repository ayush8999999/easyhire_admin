<input 
    type="checkbox" 
    id="select-all" 
    class="h-4 w-4 text-red-600 rounded border-gray-300 focus:ring-red-500"
>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.row-checkbox');

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Optional: Uncheck "Select All" if one is unchecked
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (!this.checked) {
                    selectAll.checked = false;
                }
            });
        });
    });
</script>