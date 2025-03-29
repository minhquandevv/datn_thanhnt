@extends('layouts.admin')

@section('title', 'Chi tiết kế hoạch tuyển dụng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>

            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-building me-2"></i>                Chi tiết kế hoạch tuyển dụng

            </h1>
        </div>
        <div>
            <a href="{{ route('hr.recruitment-plans.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            @if($recruitmentPlan->status === 'draft')
                <a href="{{ route('hr.recruitment-plans.edit', $recruitmentPlan) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Chỉnh sửa
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="bi bi-info-circle me-2"></i>
                        Thông tin kế hoạch
                    </h6>
                    <span class="badge bg-{{ $recruitmentPlan->status === 'draft' ? 'secondary' : 
                        ($recruitmentPlan->status === 'pending' ? 'warning' : 
                        ($recruitmentPlan->status === 'approved' ? 'success' : 'danger')) }} px-3 py-2">
                        <i class="bi bi-{{ $recruitmentPlan->status === 'draft' ? 'file-earmark' : 
                            ($recruitmentPlan->status === 'pending' ? 'clock' : 
                            ($recruitmentPlan->status === 'approved' ? 'check-circle' : 'x-circle')) }} me-1"></i>
                        {{ $recruitmentPlan->status === 'draft' ? 'Nháp' : 
                           ($recruitmentPlan->status === 'pending' ? 'Chờ duyệt' : 
                           ($recruitmentPlan->status === 'approved' ? 'Đã duyệt' : 'Từ chối')) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-danger mb-3">
                            <i class="bi bi-bookmark me-2"></i>
                            Tên kế hoạch
                        </h5>
                        <p class="mb-0 text-gray-700">{{ $recruitmentPlan->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold text-danger mb-3">
                            <i class="bi bi-card-text me-2"></i>
                            Mô tả
                        </h5>
                        <p class="mb-0 text-gray-700">{{ $recruitmentPlan->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold text-danger mb-3">
                            <i class="bi bi-building me-2"></i>
                            Trường đại học
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($recruitmentPlan->universities as $university)
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    {{ $university->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold text-danger mb-3">
                            <i class="bi bi-briefcase me-2"></i>
                            Vị trí tuyển dụng
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Vị trí</th>
                                        <th class="text-center border-0">Số lượng</th>
                                        <th class="border-0">Yêu cầu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recruitmentPlan->positions as $position)
                                        <tr>
                                            <td class="text-gray-700">
                                                <i class="bi bi-person-badge me-2"></i>
                                                {{ $position->name }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary px-3 py-2">
                                                    {{ $position->quantity }}
                                                </span>
                                            </td>
                                            <td class="text-gray-700">{{ $position->requirements }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="bi bi-info-circle me-2"></i>
                        Thông tin bổ sung
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-danger mb-3">
                            <i class="bi bi-calendar me-2"></i>
                            Thời gian
                        </h6>
                        <div class="bg-light p-3 rounded">
                            <div class="mb-3">
                                <div class="d-flex align-items-center text-gray-700">
                                    <i class="bi bi-calendar-event text-primary me-2"></i>
                                    <div>
                                        <div class="small text-muted">Bắt đầu</div>
                                        <div>{{ $recruitmentPlan->start_date->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex align-items-center text-gray-700">
                                    <i class="bi bi-calendar-check text-primary me-2"></i>
                                    <div>
                                        <div class="small text-muted">Kết thúc</div>
                                        <div>{{ $recruitmentPlan->end_date->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-danger mb-3">
                            <i class="bi bi-person me-2"></i>
                            Người tạo
                        </h6>
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex align-items-center text-gray-700">
                                <i class="bi bi-person-circle text-danger me-2"></i>
                                {{ $recruitmentPlan->creator->name }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-danger mb-3">
                            <i class="bi bi-clock me-2"></i>
                            Ngày tạo
                        </h6>
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex align-items-center text-gray-700">
                                <i class="bi bi-clock-history text-primary me-2"></i>
                                {{ $recruitmentPlan->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    @if($recruitmentPlan->status === 'rejected')
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-danger mb-3">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Lý do từ chối
                            </h6>
                            <div class="bg-light p-3 rounded border-start border-danger border-4">
                                <div class="text-danger">
                                    {{ $recruitmentPlan->rejection_reason }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        @if($recruitmentPlan->status === 'draft')
                            <form action="{{ route('hr.recruitment-plans.submit', $recruitmentPlan) }}" 
                                  method="POST" 
                                  class="mb-2">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-success w-100 py-2"
                                        onclick="return confirm('Bạn có chắc chắn muốn nộp kế hoạch này để duyệt?');">
                                    <i class="bi bi-send me-2"></i>Nộp duyệt
                                </button>
                            </form>

                            <form action="{{ route('hr.recruitment-plans.destroy', $recruitmentPlan) }}" 
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger w-100 py-2"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa kế hoạch này?');">
                                    <i class="bi bi-trash me-2"></i>Xóa kế hoạch
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 