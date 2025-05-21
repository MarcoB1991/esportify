<footer style="margin-top: 50px; padding: 20px; background-color: #f0f0f0; text-align: center;">
    <p>&copy; <?php echo date('Y'); ?> Esportify - Marco Bertello - All rights reserved</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const startDateInput = document.getElementById('startDateInput');
    const organizerInput = document.getElementById('organizerInput');
    const container = document.getElementById('eventContainer');
    const resetBtn = document.getElementById('resetBtn');

    function loadEvents() {
        const params = new URLSearchParams();
        if (searchInput.value) params.append('search', searchInput.value);
        if (startDateInput.value) params.append('start_date', startDateInput.value);
        if (organizerInput.value) params.append('organizer_id', organizerInput.value);

        fetch('event_list.php?' + params.toString())
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
            });
    }

    loadEvents();

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        loadEvents();
    });

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        startDateInput.value = '';
        organizerInput.value = '';
        loadEvents();
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
