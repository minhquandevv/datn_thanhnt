// CSS styles
const styles = `
.filter-card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.checkbox-filter {
    max-height: 200px;
    overflow-y: auto;
    padding: 0.5rem;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.checkbox-filter::-webkit-scrollbar {
    width: 6px;
}

.checkbox-filter::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.checkbox-filter::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.checkbox-filter::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.form-check {
    margin-bottom: 0.5rem;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.form-check-label {
    font-size: 0.875rem;
    color: #5a5c69;
}

.filter-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    margin: 0.25rem;
    background-color: #4e73df;
    color: white;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.filter-badge .remove-filter {
    margin-left: 0.5rem;
    cursor: pointer;
}

.daterangepicker {
    font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

.daterangepicker .calendar-table {
    background-color: white;
}

.daterangepicker td.active, 
.daterangepicker td.active:hover {
    background-color: #4e73df;
}

.daterangepicker .ranges li.active {
    background-color: #4e73df;
}
`;

// Add styles to document
const styleSheet = document.createElement("style");
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

// Initialize date range picker
$('#dateRange').daterangepicker({
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Xóa',
        applyLabel: 'Áp dụng',
        format: 'DD/MM/YYYY',
        separator: ' - ',
        daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
        monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']
    },
    ranges: {
        'Hôm nay': [moment(), moment()],
        'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 ngày qua': [moment().subtract(6, 'days'), moment()],
        '30 ngày qua': [moment().subtract(29, 'days'), moment()],
        'Tháng này': [moment().startOf('month'), moment().endOf('month')],
        'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
});

$('#dateRange').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
});

$('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

// Handle select all checkboxes
$('.departments-select-all, .positions-select-all, .statuses-select-all, .universities-select-all').on('change', function() {
    const group = $(this).data('filter-group');
    const isChecked = $(this).prop('checked');
    $(`.filter-checkbox[data-filter-group="${group}"]`).prop('checked', isChecked);
    updateFilterBadges();
});

// Handle individual checkboxes
$('.filter-checkbox').on('change', function() {
    const group = $(this).data('filter-group');
    const totalCheckboxes = $(`.filter-checkbox[data-filter-group="${group}"]`).length;
    const checkedCheckboxes = $(`.filter-checkbox[data-filter-group="${group}"]:checked`).length;
    
    $(`.${group}-select-all`).prop('checked', totalCheckboxes === checkedCheckboxes);
    updateFilterBadges();
});

// Update filter badges
function updateFilterBadges() {
    const filterContainer = $('#filterBadges');
    filterContainer.empty();

    // Date range badge
    const dateRange = $('#dateRange').val();
    if (dateRange) {
        filterContainer.append(`
            <span class="filter-badge">
                Thời gian: ${dateRange}
                <i class="fas fa-times remove-filter" data-filter="dateRange"></i>
            </span>
        `);
    }

    // Department badges
    $('.departments-filter .filter-checkbox:checked').each(function() {
        const value = $(this).val();
        const label = $(this).next('label').text();
        filterContainer.append(`
            <span class="filter-badge">
                Phòng ban: ${label}
                <i class="fas fa-times remove-filter" data-filter="departments" data-value="${value}"></i>
            </span>
        `);
    });

    // Position badges
    $('.positions-filter .filter-checkbox:checked').each(function() {
        const value = $(this).val();
        const label = $(this).next('label').text();
        filterContainer.append(`
            <span class="filter-badge">
                Vị trí: ${label}
                <i class="fas fa-times remove-filter" data-filter="positions" data-value="${value}"></i>
            </span>
        `);
    });

    // Status badges
    $('.statuses-filter .filter-checkbox:checked').each(function() {
        const value = $(this).val();
        const label = $(this).next('label').text();
        filterContainer.append(`
            <span class="filter-badge">
                Trạng thái: ${label}
                <i class="fas fa-times remove-filter" data-filter="statuses" data-value="${value}"></i>
            </span>
        `);
    });

    // University badges
    $('.universities-filter .filter-checkbox:checked').each(function() {
        const value = $(this).val();
        const label = $(this).next('label').text();
        filterContainer.append(`
            <span class="filter-badge">
                Trường: ${label}
                <i class="fas fa-times remove-filter" data-filter="universities" data-value="${value}"></i>
            </span>
        `);
    });
}

// Handle remove filter badge
$(document).on('click', '.remove-filter', function() {
    const filter = $(this).data('filter');
    const value = $(this).data('value');

    if (filter === 'dateRange') {
        $('#dateRange').val('');
    } else {
        $(`.filter-checkbox[data-filter-group="${filter}"][value="${value}"]`).prop('checked', false);
        const totalCheckboxes = $(`.filter-checkbox[data-filter-group="${filter}"]`).length;
        const checkedCheckboxes = $(`.filter-checkbox[data-filter-group="${filter}"]:checked`).length;
        $(`.${filter}-select-all`).prop('checked', totalCheckboxes === checkedCheckboxes);
    }

    updateFilterBadges();
    refreshDashboardData();
});

// Apply filters button
$('#applyFilters').on('click', function() {
    refreshDashboardData();
});

// Initialize filter badges
updateFilterBadges();

// Function to refresh dashboard data
function refreshDashboardData() {
    // Get filter values
    const dateRange = $('#dateRange').val();
    const departments = $('#departments').val();
    const positions = $('#positions').val();
    const statuses = $('#statuses').val();
    const universities = $('#universities').val();

    // Show loading state
    $('#dashboardStats').addClass('loading');
    $('#recentApplications').addClass('loading');

    // Make AJAX request
    $.ajax({
        url: '/admin/dashboard/refresh',
        method: 'GET',
        data: {
            dateRange: dateRange,
            departments: departments,
            positions: positions,
            statuses: statuses,
            universities: universities
        },
        success: function(response) {
            // Update statistics
            updateStatistics(response.statistics);
            
            // Update recent applications
            updateRecentApplications(response.recentApplications);
            
            // Remove loading state
            $('#dashboardStats').removeClass('loading');
            $('#recentApplications').removeClass('loading');
        },
        error: function(xhr) {
            console.error('Error refreshing dashboard data:', xhr);
            // Remove loading state
            $('#dashboardStats').removeClass('loading');
            $('#recentApplications').removeClass('loading');
            // Show error message
            alert('Error refreshing dashboard data. Please try again.');
        }
    });
}

// Function to update statistics
function updateStatistics(statistics) {
    // Update total candidates
    $('#totalCandidates').text(statistics.totalCandidates);
    
    // Update total applications
    $('#totalApplications').text(statistics.totalApplications);
    
    // Update applications by status
    $('#pendingApplications').text(statistics.pendingApplications);
    $('#reviewingApplications').text(statistics.reviewingApplications);
    $('#shortlistedApplications').text(statistics.shortlistedApplications);
    $('#rejectedApplications').text(statistics.rejectedApplications);
    
    // Update application status chart if it exists
    if (window.applicationStatusChart) {
        window.applicationStatusChart.data.datasets[0].data = [
            statistics.pendingApplications,
            statistics.reviewingApplications,
            statistics.shortlistedApplications,
            statistics.rejectedApplications
        ];
        window.applicationStatusChart.update();
    }
}

// Function to update recent applications
function updateRecentApplications(applications) {
    const tbody = $('#recentApplications tbody');
    tbody.empty();
    
    applications.forEach(function(application) {
        const row = `
            <tr>
                <td>${application.candidate_name}</td>
                <td>${application.position}</td>
                <td>${application.department}</td>
                <td>${application.status}</td>
                <td>${application.created_at}</td>
                <td>
                    <a href="/admin/applications/${application.id}" class="btn btn-sm btn-primary">View</a>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Add event listeners for filter changes
$(document).ready(function() {
    // Toggle filter panel visibility
    $('#toggleFilters').on('click', function() {
        // First, remove the inline style if it exists
        $('#filtersCard').css('display', '');
        // Then toggle with animation
        $('#filtersCard').slideToggle();
    });
    
    // Refresh data when filters change
    $('#dateRange, #departments, #positions, #statuses, #universities').on('change', function() {
        refreshDashboardData();
    });
    
    // Initial refresh
    refreshDashboardData();
    
    // Set up auto-refresh every 5 minutes
    setInterval(refreshDashboardData, 300000);
}); 