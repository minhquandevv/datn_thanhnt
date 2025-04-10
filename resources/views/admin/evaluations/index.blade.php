@extends('layouts.admin')

@section('title', 'Đánh giá công việc')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-danger fw-bold mb-2">
                <i class="bi bi-graph-up me-2"></i>ĐÁNH GIÁ CHẤT LƯỢNG THỰC TẬP SINH
            </h1>
        </div>
    </div>
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 opacity-75">Tổng số TTS</h6>
                            <h3 class="card-title mb-0 fw-bold">{{ count($internEvaluations) }}</h3>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-success text-white h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 opacity-75">Xuất sắc</h6>
                            <h3 class="card-title mb-0 fw-bold">{{ collect($internEvaluations)->where('rating', 'Xuất sắc')->count() }}</h3>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="bi bi-star-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-info text-white h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 opacity-75">Tốt</h6>
                            <h3 class="card-title mb-0 fw-bold">{{ collect($internEvaluations)->where('rating', 'Tốt')->count() }}</h3>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="bi bi-hand-thumbs-up-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-warning text-white h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 opacity-75">Trung bình</h6>
                            <h3 class="card-title mb-0 fw-bold">{{ collect($internEvaluations)->where('rating', 'Trung bình')->count() }}</h3>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="bi bi-arrow-up-circle-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Biểu đồ tròn -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title text-danger mb-0">
                        <i class="bi bi-pie-chart-fill me-2"></i>
                        Phân bố đánh giá
                    </h5>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px">
                        <canvas id="evaluationChart"></canvas>
                    </div>
                    <hr class="my-3">
                    <div class="text-muted small">
                        <div class="d-flex justify-content-center gap-3">
                            <span><i class="bi bi-circle-fill text-success me-1"></i>Xuất sắc (≥9.0)</span>
                            <span><i class="bi bi-circle-fill text-info me-1"></i>Tốt (8.0-8.9)</span>
                            <span><i class="bi bi-circle-fill text-warning me-1"></i>Trung bình (5.0-7.9)</span>
                            <span><i class="bi bi-circle-fill text-danger me-1"></i>Kém (<5.0)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ cột trường đại học -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title text-danger mb-0">
                        <i class="bi bi-bar-chart-fill me-2"></i>
                        Phân bố theo trường đại học
                    </h5>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px">
                        <canvas id="universityChart"></canvas>
                    </div>
                    <hr class="my-3">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Chiều cao của mỗi cột thể hiện số lượng thực tập sinh của trường tương ứng
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ điểm trung bình theo trường -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title text-danger mb-0">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        Điểm trung bình theo trường đại học
                    </h5>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px">
                        <canvas id="universityScoreChart"></canvas>
                    </div>
                    <hr class="my-3">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Kích thước phần tròn thể hiện số lượng thực tập sinh của trường tương ứng
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ radar theo vị trí -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title text-danger mb-0">
                        <i class="bi bi-radar me-2"></i>
                        Chất lượng TTS theo vị trí tuyển dụng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px">
                        <canvas id="positionRadarChart"></canvas>
                    </div>
                    <hr class="my-3">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Càng xa tâm điểm thì điểm càng cao (thang điểm từ 0-10)
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ điểm trung bình theo phòng ban -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title text-danger mb-0">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        Điểm TB theo Phòng ban
                    </h5>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px">
                        <canvas id="departmentScoreChart"></canvas>
                    </div>
                    <hr class="my-3">
                    <div class="text-muted small">
                        <div class="d-flex justify-content-center gap-3">
                            <span><i class="bi bi-circle-fill text-danger me-1"></i>Điểm trung bình</span>
                            <span><i class="bi bi-arrow-up text-success me-1"></i>Xu hướng tăng</span>
                            <span><i class="bi bi-arrow-down text-danger me-1"></i>Xu hướng giảm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ phân bố xếp loại theo trường -->
        <div class="col-md-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title text-danger mb-0">
                        <i class="bi bi-bar-chart-steps me-2"></i>
                        Phân bố xếp loại sinh viên theo trường đại học
                    </h5>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 300px">
                        <canvas id="universityRatingChart"></canvas>
                    </div>
                    <hr class="my-3">
                    <div class="text-muted small">
                        <div class="d-flex justify-content-center gap-3">
                            <span><i class="bi bi-square-fill text-success me-1"></i>Xuất sắc</span>
                            <span><i class="bi bi-square-fill text-info me-1"></i>Tốt</span>
                            <span><i class="bi bi-square-fill text-warning me-1"></i>Trung bình</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng thống kê -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-danger mb-0">
                            <i class="bi bi-table me-2"></i>
                            Chi tiết đánh giá
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Xuất Excel
                            </button>
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Xuất PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-3 py-2">Thực tập sinh</th>
                                    <th class="border-0 px-3 py-2">Trường đại học</th>
                                    <th class="border-0 px-3 py-2 text-center">Số task</th>
                                    <th class="border-0 px-3 py-2 text-center">Điểm TB</th>
                                    <th class="border-0 px-3 py-2 text-center">Xếp loại</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($internEvaluations as $evaluation)
                                <tr>
                                    <td class="px-3 py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <span class="avatar-text">{{ substr($evaluation['name'], 0, 1) }}</span>
                                            </div>
                                            {{ $evaluation['name'] }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-2">{{ $evaluation['university'] }}</td>
                                    <td class="px-3 py-2 text-center">{{ $evaluation['task_count'] }}</td>
                                    <td class="px-3 py-2 text-center fw-bold">{{ number_format($evaluation['average_score'], 1) }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @switch($evaluation['rating'])
                                            @case('Xuất sắc')
                                                <span class="badge bg-success-subtle text-success">Xuất sắc</span>
                                                @break
                                            @case('Tốt')
                                                <span class="badge bg-info-subtle text-info">Tốt</span>
                                                @break
                                            @case('Trung bình')
                                                <span class="badge bg-warning-subtle text-warning">Trung bình</span>
                                                @break
                                            @case('Kém')
                                                <span class="badge bg-danger-subtle text-danger">Kém</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', 'Helvetica', 'Arial', sans-serif";
    Chart.defaults.font.size = 13;
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0,0,0,0.8)';
    Chart.defaults.plugins.tooltip.titleColor = '#fff';
    Chart.defaults.plugins.tooltip.bodyColor = '#fff';
    Chart.defaults.plugins.tooltip.borderColor = 'rgba(255,255,255,0.1)';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.displayColors = false;

    // Biểu đồ tròn đánh giá
    const ctx = document.getElementById('evaluationChart').getContext('2d');
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.data,
                backgroundColor: chartData.colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Biểu đồ cột trường đại học
    const universityCtx = document.getElementById('universityChart').getContext('2d');
    const universityData = @json($universityChartData);

    new Chart(universityCtx, {
        type: 'bar',
        data: {
            labels: universityData.labels,
            datasets: [{
                label: 'Số lượng thực tập sinh',
                data: universityData.data,
                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                borderColor: 'rgb(220, 53, 69)',
                borderWidth: 1,
                borderRadius: 4,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Biểu đồ điểm trung bình theo trường
    const universityScoreCtx = document.getElementById('universityScoreChart').getContext('2d');
    new Chart(universityScoreCtx, {
        type: 'doughnut',
        data: {
            labels: @json($universityScoreData['labels']),
            datasets: [{
                data: @json($universityScoreData['data']),
                backgroundColor: @json($universityScoreData['colors']),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });

    // Biểu đồ radar theo vị trí
    const positionCtx = document.getElementById('positionRadarChart').getContext('2d');
    const positionData = @json($positionChartData);

    new Chart(positionCtx, {
        type: 'radar',
        data: {
            labels: positionData.labels,
            datasets: [{
                label: 'Điểm trung bình',
                data: positionData.data,
                fill: true,
                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                borderColor: 'rgb(220, 53, 69)',
                pointBackgroundColor: 'rgb(220, 53, 69)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(220, 53, 69)',
                borderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: {
                        display: true,
                        color: 'rgba(0,0,0,0.1)'
                    },
                    suggestedMin: 0,
                    suggestedMax: 10,
                    ticks: {
                        stepSize: 2,
                        backdropColor: 'transparent'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    pointLabels: {
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Biểu đồ đường điểm trung bình theo phòng ban
    const departmentScoreCtx = document.getElementById('departmentScoreChart').getContext('2d');
    const departmentData = @json($departmentScoreData);

    new Chart(departmentScoreCtx, {
        type: 'line',
        data: {
            labels: departmentData.labels,
            datasets: [{
                label: 'Điểm trung bình',
                data: departmentData.data,
                fill: {
                    target: 'origin',
                    above: 'rgba(220, 53, 69, 0.1)',
                },
                borderColor: 'rgb(220, 53, 69)',
                borderWidth: 2,
                pointBackgroundColor: 'rgb(220, 53, 69)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    ticks: {
                        stepSize: 2
                    },
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return `Điểm TB: ${context.parsed.y.toFixed(1)}`;
                        }
                    }
                }
            }
        }
    });

    // Biểu đồ phân bố xếp loại theo trường
    const universityRatingCtx = document.getElementById('universityRatingChart').getContext('2d');
    const universityRatingData = @json($universityRatingData);

    new Chart(universityRatingCtx, {
        type: 'bar',
        data: {
            labels: universityRatingData.labels,
            datasets: [
                {
                    label: 'Xuất sắc',
                    data: universityRatingData.excellent,
                    backgroundColor: '#198754',
                    borderColor: '#198754',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.8
                },
                {
                    label: 'Tốt',
                    data: universityRatingData.good,
                    backgroundColor: '#0dcaf0',
                    borderColor: '#0dcaf0',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.8
                },
                {
                    label: 'Trung bình',
                    data: universityRatingData.average,
                    backgroundColor: '#ffc107',
                    borderColor: '#ffc107',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'rectRounded',
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw} sinh viên`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
:root {
    --danger-rgb: 220, 53, 69;
    --success-rgb: 25, 135, 84;
    --info-rgb: 13, 202, 240;
    --warning-rgb: 255, 193, 7;
}

.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card-header {
    border-top-left-radius: 12px !important;
    border-top-right-radius: 12px !important;
}

.bg-gradient-danger {
    background: linear-gradient(45deg, rgb(var(--danger-rgb)), rgb(var(--danger-rgb), 0.8));
}

.bg-gradient-success {
    background: linear-gradient(45deg, rgb(var(--success-rgb)), rgb(var(--success-rgb), 0.8));
}

.bg-gradient-info {
    background: linear-gradient(45deg, rgb(var(--info-rgb)), rgb(var(--info-rgb), 0.8));
}

.bg-gradient-warning {
    background: linear-gradient(45deg, rgb(var(--warning-rgb)), rgb(var(--warning-rgb), 0.8));
}

.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table th {
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 6px;
    font-size: 12px;
}

.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #dc3545;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-text {
    color: white;
    font-weight: 500;
    font-size: 14px;
    text-transform: uppercase;
}

.breadcrumb {
    font-size: 14px;
}

.breadcrumb-item a {
    color: #6c757d;
}

.breadcrumb-item.active {
    color: #dc3545;
}

.btn-outline-danger {
    border-width: 2px;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
    transform: translateY(-2px);
}

canvas {
    min-height: 300px;
}
</style>
@endpush 