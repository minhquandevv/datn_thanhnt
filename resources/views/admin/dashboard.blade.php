@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-3">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-danger font-weight-bold">
            <i class="fas fa-tachometer-alt"></i> Bảng thống kê ứng viên
        </h1>
        <div>
            <button class="btn btn-sm btn-outline-primary mr-2" id="toggleFilters">
                <i class="fas fa-filter fa-sm"></i> {{ isset($dateRange) || isset($departments) || isset($positions) || isset($statuses) || isset($universities) ? 'Chỉnh sửa bộ lọc' : 'Hiển thị bộ lọc' }}
            </button>
            <a href="{{ route('admin.dashboard.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Xuất báo cáo
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card shadow-sm mb-3" id="filtersCard" style="{{ isset($dateRange) || isset($departments) || isset($positions) || isset($statuses) || isset($universities) ? '' : 'display: none;' }}">
        <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary mr-2">
                    <i class="fas fa-redo fa-sm"></i> Đặt lại
                </a>
                <button class="btn btn-sm btn-primary" id="applyFilters">
                    <i class="fas fa-check fa-sm"></i> Áp dụng
                </button>
            </div>
        </div>
        <div class="card-body p-3">
            <form id="dashboardFilters" method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row">
                    <!-- Date Range Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="dateRange" class="form-label font-weight-bold small">Khoảng thời gian</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control" id="dateRange" name="dateRange" placeholder="Chọn khoảng thời gian" value="{{ $dateRange ?? '' }}">
                        </div>
                    </div>
                    
                    <!-- Position Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="positions" class="form-label font-weight-bold small">Vị trí</label>
                        <div class="checkbox-filter positions-filter">
                            <div class="form-check">
                                <input class="form-check-input positions-select-all" type="checkbox" data-filter-group="positions">
                                <label class="form-check-label small">Tất cả vị trí</label>
                            </div>
                            @foreach($availablePositions as $pos)
                                <div class="form-check">
                                    <input class="form-check-input filter-checkbox" type="checkbox" name="positions[]" 
                                        value="{{ $pos }}" data-filter-group="positions"
                                        {{ in_array($pos, $positions ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label small">{{ $pos }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="statuses" class="form-label font-weight-bold small">Trạng thái</label>
                        <div class="checkbox-filter statuses-filter">
                            <div class="form-check">
                                <input class="form-check-input statuses-select-all" type="checkbox" data-filter-group="statuses">
                                <label class="form-check-label small">Tất cả trạng thái</label>
                            </div>
                            @php
                                $statusOptions = [
                                    'pending' => 'Chờ duyệt',
                                    'processing' => 'Đã tiếp nhận',
                                    'approved' => 'Đã duyệt',
                                    'rejected' => 'Từ chối'
                                ];
                            @endphp
                            @foreach($statusOptions as $value => $label)
                                <div class="form-check">
                                    <input class="form-check-input filter-checkbox" type="checkbox" name="statuses[]" 
                                        value="{{ $value }}" data-filter-group="statuses"
                                        {{ in_array($value, $statuses ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label small">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- University Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="universities" class="form-label font-weight-bold small">Trường đại học</label>
                        <div class="checkbox-filter universities-filter">
                            <div class="form-check">
                                <input class="form-check-input universities-select-all" type="checkbox" data-filter-group="universities">
                                <label class="form-check-label small">Tất cả trường</label>
                            </div>
                            @foreach($availableUniversities as $uni)
                                <div class="form-check">
                                    <input class="form-check-input filter-checkbox" type="checkbox" name="universities[]" 
                                        value="{{ $uni }}" data-filter-group="universities"
                                        {{ in_array($uni, $universities ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label small">{{ $uni }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Filters Display -->
    @if(isset($dateRange) || (isset($positions) && !empty($positions) && !in_array('all', $positions)) || (isset($statuses) && !empty($statuses) && !in_array('all', $statuses)) || (isset($universities) && !empty($universities) && !in_array('all', $universities)))
    <div class="card shadow-sm mb-3">
        <div class="card-header py-2" style="background-color: #e8f4ff;">
            <h6 class="m-0 font-weight-bold" style="color: #0d47a1;">Bộ lọc đang áp dụng</h6>
        </div>
        <div class="card-body p-2">
            <div id="filterBadges" class="d-flex flex-wrap">
                @if(isset($dateRange) && $dateRange)
                <div class="filter-badge mr-2 mb-1">
                    <span class="badge badge-primary p-2" style="background-color: #e3f2fd; color: #0d47a1;">
                        <i class="fas fa-calendar mr-1"></i> Khoảng thời gian: {{ $dateRange }}
                        <a href="{{ route('admin.dashboard', array_merge(request()->except('dateRange'), ['dateRange' => null])) }}" class="text-primary ml-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                </div>
                @endif
                
                @if(isset($positions) && is_array($positions) && !empty($positions) && !in_array('all', $positions))
                <div class="filter-badge mr-2 mb-1">
                    <span class="badge badge-success p-2" style="background-color: #e8f5e9; color: #1b5e20;">
                        <i class="fas fa-briefcase mr-1"></i> Vị trí: {{ implode(', ', $positions) }}
                        <a href="{{ route('admin.dashboard', array_merge(request()->except('positions'), ['positions' => ['all']])) }}" class="text-success ml-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                </div>
                @endif
                
                @if(isset($statuses) && is_array($statuses) && !empty($statuses) && !in_array('all', $statuses))
                <div class="filter-badge mr-2 mb-1">
                    <span class="badge badge-warning p-2" style="background-color: #fff3e0; color: #e65100;">
                        <i class="fas fa-tasks mr-1"></i> Trạng thái: 
                        @php
                            $statusLabels = [
                                'pending' => 'Chờ duyệt',
                                'processing' => 'Đã tiếp nhận',
                                'approved' => 'Đã duyệt',
                                'rejected' => 'Từ chối'
                            ];
                            $statusTexts = array_map(function($status) use ($statusLabels) {
                                return $statusLabels[$status] ?? $status;
                            }, $statuses);
                        @endphp
                        {{ implode(', ', $statusTexts) }}
                        <a href="{{ route('admin.dashboard', array_merge(request()->except('statuses'), ['statuses' => ['all']])) }}" class="text-warning ml-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                </div>
                @endif
                
                @if(isset($universities) && is_array($universities) && !empty($universities) && !in_array('all', $universities))
                <div class="filter-badge mr-2 mb-1">
                    <span class="badge badge-secondary p-2" style="background-color: #eceff1; color: #263238;">
                        <i class="fas fa-university mr-1"></i> Trường đại học: {{ implode(', ', $universities) }}
                        <a href="{{ route('admin.dashboard', array_merge(request()->except('universities'), ['universities' => ['all']])) }}" class="text-secondary ml-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <!-- Tổng số ứng viên Card -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body p-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số ứng viên</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $candidateCount }}</div>
                            @if($candidateGrowth != 0)
                            <div class="mt-1 small {{ $candidateGrowth > 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-{{ $candidateGrowth > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ abs($candidateGrowth) }}% so với tháng trước
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số đơn ứng tuyển Card -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body p-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tổng số đơn ứng tuyển</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applicationCount }}</div>
                            @if($applicationGrowth != 0)
                            <div class="mt-1 small {{ $applicationGrowth > 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-{{ $applicationGrowth > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ abs($applicationGrowth) }}% so với tháng trước
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đơn đang chờ duyệt Card -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body p-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Đơn đang chờ duyệt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingApplications }}</div>
                            @if($applicationCount > 0)
                            <div class="mt-1 small text-muted">
                                {{ round(($pendingApplications / $applicationCount) * 100) }}% tổng số đơn
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đơn đã duyệt Card -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body p-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Đơn đã duyệt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedApplications }}</div>
                            @if($applicationCount > 0)
                            <div class="mt-1 small text-muted">
                                {{ round(($approvedApplications / $applicationCount) * 100) }}% tổng số đơn
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm mb-3">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê đơn ứng tuyển</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy chọn biểu đồ:</div>
                            <a class="dropdown-item" href="#">Theo tháng</a>
                            <a class="dropdown-item" href="#">Theo quý</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Xuất báo cáo</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body p-2">
                    <div class="chart-area">
                        <canvas id="applicationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm mb-3">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tỷ lệ trạng thái đơn</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy chọn biểu đồ:</div>
                            <a class="dropdown-item" href="#">Theo tháng</a>
                            <a class="dropdown-item" href="#">Theo quý</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Xuất báo cáo</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body p-2">
                    <div class="chart-pie pt-2 pb-2">
                        <canvas id="statusPieChart"></canvas>
                    </div>
                    <div class="mt-2 text-center small">
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'processing' => 'primary',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                            
                            $statusLabels = [
                                'pending' => 'Chờ duyệt',
                                'processing' => 'Đã tiếp nhận',
                                'approved' => 'Đã duyệt',
                                'rejected' => 'Từ chối'
                            ];
                        @endphp
                        
                        @foreach($statusJobApplications as $statusItem)
                            @php
                                $status = strtolower(trim($statusItem['status']));
                                $color = $statusColors[$status] ?? 'secondary';
                                $label = $statusLabels[$status] ?? $statusItem['status'];
                            @endphp
                            <span class="mr-2">
                                <i class="fas fa-circle text-{{ $color }}"></i> <span style="color: #5a5c69; font-weight: 500;">{{ $label }} ({{ $statusItem['count'] }})</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Applications -->
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header py-2">
                    <h6 class="m-0 font-weight-bold text-primary">Đơn ứng tuyển mới nhất</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Ứng viên</th>
                                    <th>Vị trí</th>
                                    <th>Ngày nộp</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $application)
                                <tr>
                                    <td>{{ $application['candidate_name'] }}</td>
                                    <td>{{ $application['position'] }}</td>
                                    <td>{{ $application['applied_date'] }}</td>
                                    <td>
                                        @php
                                            $status = trim(strtolower($application['status']));
                                            $statusClass = match($status) {
                                                'approved' => 'success',
                                                'pending' => 'warning',
                                                'processing' => 'primary',
                                                'rejected' => 'danger',
                                                default => 'secondary'
                                            };
                                            
                                            $statusText = match($status) {
                                                'pending' => 'Chờ duyệt',
                                                'processing' => 'Đã tiếp nhận',
                                                'approved' => 'Đã duyệt',
                                                'rejected' => 'Từ chối',
                                                default => $application['status']
                                            };
                                        @endphp
                                        <span class="badge" style="font-weight: 500; border-radius: 16px; padding: 4px 8px; {{ 
                                                $statusClass === 'warning' ? 'background-color: #fff3cd; color: #856404;' : 
                                                ($statusClass === 'success' ? 'background-color: #d4edda; color: #155724;' : 
                                                ($statusClass === 'danger' ? 'background-color: #f8d7da; color: #721c24;' :
                                                'background-color: #cce5ff; color: #004085;')) 
                                            }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có đơn ứng tuyển nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Candidates -->
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm mb-3">
                <div class="card-header py-2">
                    <h6 class="m-0 font-weight-bold text-primary">Ứng viên mới nhất</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCandidates as $candidate)
                                <tr>
                                    <td>{{ $candidate['name'] }}</td>
                                    <td>{{ $candidate['email'] }}</td>
                                    <td>{{ $candidate['phone'] }}</td>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có ứng viên nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<style>
    /* Filter Card Styles */
    .filter-card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
        overflow: hidden;
    }
    
    .filter-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 10px 15px;
    }
    
    .filter-card .card-body {
        padding: 15px;
    }
    
    /* Checkbox Filter Styles */
    .checkbox-filter {
        max-height: 180px;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .checkbox-filter .form-check {
        margin-bottom: 6px;
        padding-left: 22px;
    }
    
    .checkbox-filter .form-check-input {
        margin-left: -22px;
    }
    
    .checkbox-filter .form-check-label {
        font-size: 0.85rem;
        color: #495057;
    }
    
    .checkbox-filter .form-check-input:checked + .form-check-label {
        font-weight: 600;
        color: #0d6efd;
    }
    
    /* Date Range Picker Styles */
    .daterangepicker {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .daterangepicker .ranges li.active {
        background-color: #0d6efd;
    }
    
    .daterangepicker .calendar-table {
        border-radius: 6px;
    }
    
    .daterangepicker td.active, 
    .daterangepicker td.active:hover {
        background-color: #0d6efd;
    }
    
    /* Filter Badge Styles */
    .filter-badge .badge {
        font-size: 0.8rem;
        font-weight: 500;
        padding: 6px 10px;
        margin-right: 6px;
        margin-bottom: 6px;
        border-radius: 16px;
    }
    
    .filter-badge .badge a {
        text-decoration: none;
        margin-left: 4px;
    }
    
    .filter-badge .badge a:hover {
        opacity: 0.8;
    }
    
    /* Toggle Filters Button */
    #toggleFilters {
        transition: all 0.3s ease;
    }
    
    #toggleFilters:hover {
        transform: translateY(-2px);
    }
    
    /* Apply Filters Button */
    #applyFilters {
        transition: all 0.3s ease;
    }
    
    #applyFilters:hover {
        transform: translateY(-2px);
    }
    
    /* Table Styles */
    .table-sm th, .table-sm td {
        padding: 0.3rem;
    }
    
    /* Card Styles */
    .card {
        border-radius: 6px;
    }
    
    .card-header {
        padding: 0.5rem 1rem;
    }
    
    /* Chart Styles */
    .chart-area {
        height: 300px;
    }
    
    .chart-pie {
        height: 250px;
    }
</style>
<script>
    $(document).ready(function() {
        // Initialize DateRangePicker
        $('#dateRange').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' to ',
                applyLabel: 'Áp dụng',
                cancelLabel: 'Hủy',
                fromLabel: 'Từ',
                toLabel: 'Đến',
                customRangeLabel: 'Tùy chọn',
                weekLabel: 'W',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                firstDay: 1
            },
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        });
        
        // Toggle Filters
        $('#toggleFilters').click(function() {
            $('#filtersCard').slideToggle();
        });
        
        // Apply Filters
        $('#applyFilters').click(function() {
            $('#dashboardFilters').submit();
        });
        
        // Select All Checkboxes
        $('.select-all').change(function() {
            var isChecked = $(this).prop('checked');
            var filterGroup = $(this).data('filter-group');
            $('.' + filterGroup + '-filter input[type="checkbox"]').prop('checked', isChecked);
        });
        
        // Update Select All when individual checkboxes change
        $('.filter-checkbox').change(function() {
            var filterGroup = $(this).data('filter-group');
            var totalCheckboxes = $('.' + filterGroup + '-filter input[type="checkbox"]').length;
            var checkedCheckboxes = $('.' + filterGroup + '-filter input[type="checkbox"]:checked').length;
            
            if (totalCheckboxes === checkedCheckboxes) {
                $('.' + filterGroup + '-select-all').prop('checked', true);
            } else {
                $('.' + filterGroup + '-select-all').prop('checked', false);
            }
        });
    });

    // Area Chart - Applications over time
    var ctx = document.getElementById("applicationsChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [{
                label: "Đơn ứng tuyển",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: {!! json_encode($monthlyApplications) !!},
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                            return value;
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + tooltipItem.yLabel;
                    }
                }
            }
        }
    });

    // Pie Chart - Application Status Distribution
    var ctx2 = document.getElementById("statusPieChart");
    var myPieChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusJobApplications->map(function($item) use ($statusLabels) {
                $status = strtolower(trim($item['status']));
                return $statusLabels[$status] ?? $item['status'];
            })) !!},
            datasets: [{
                data: {!! json_encode($statusJobApplications->pluck('count')) !!},
                backgroundColor: {!! json_encode($statusJobApplications->map(function($item) use ($statusColors) {
                    $status = strtolower(trim($item['status']));
                    switch($statusColors[$status] ?? 'secondary') {
                        case 'warning': return '#f6c23e';
                        case 'success': return '#1cc88a';
                        case 'danger': return '#e74a3b';
                        case 'primary': return '#4e73df';
                        default: return '#858796';
                    }
                })) !!},
                hoverBackgroundColor: {!! json_encode($statusJobApplications->map(function($item) use ($statusColors) {
                    $status = strtolower(trim($item['status']));
                    switch($statusColors[$status] ?? 'secondary') {
                        case 'warning': return '#d4a636';
                        case 'success': return '#17a673';
                        case 'danger': return '#c93a2d';
                        case 'primary': return '#2e59d9';
                        default: return '#6e707e';
                    }
                })) !!},
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                        return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                    }
                }
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            cutoutPercentage: 80,
        },
    });
</script>
@endsection