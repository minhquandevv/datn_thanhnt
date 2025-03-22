@extends('layouts.app')

@section('content')
    <style>
        .nav-pills .nav-link {
            background-color: #e9ecef;
            color: #495057;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .nav-pills .nav-link.active, .nav-pills .nav-link:hover {
            background-color: #dc3545;
            color: white;
        }

        .tab-content {
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .job-info-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="container mt-4">
        <div class="card shadow-lg p-4 border-0 rounded-4 bg-white">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                <h2 class="fw-bold text-danger"><i class="bi bi-briefcase me-2"></i>{{ $jobOffer->job_name }}</h2>
                @if ($hasApplied)
                    <button class="btn btn-secondary fw-bold px-4 py-2 rounded-pill shadow-sm" disabled>
                        ĐÃ ỨNG TUYỂN <i class="bi bi-check-circle"></i>
                    </button>
                @else
                    <button class="btn btn-danger fw-bold px-4 py-2 rounded-pill shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#applyJobModal">
                        ỨNG TUYỂN NGAY <i class="bi bi-arrow-right"></i>
                    </button>
                @endif
            </div>
            <p class="text-muted mt-2"><i class="bi bi-building me-1"></i> {{ $jobOffer->company->title }}</p>
            <p class="text-muted">
                <i class="bi bi-geo-alt me-1"></i> {{ $jobOffer->company->location }} &nbsp;
                <i class="bi bi-calendar me-1"></i> Ngày hết hạn:
                {{ \Carbon\Carbon::parse($jobOffer->expiration_date)->format('d/m/Y') }}
            </p>

            <ul class="nav nav-pills mb-3" id="jobTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-2 rounded-pill" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button" role="tab">
                        <i class="bi bi-file-text"></i> Chi tiết
                    </button>
                </li>
                <span class="text-muted mx-1"></span>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2 rounded-pill" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab">
                        <i class="bi bi-buildings"></i> Thông tin công ty
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3 p-3 border rounded bg-white shadow">
                <div class="tab-pane fade show active" id="detail" role="tabpanel">
                    <p class="text-muted">{{ $jobOffer->job_detail }}</p>
                </div>
                <div class="tab-pane fade" id="company" role="tabpanel">
                    <div class="job-info-card">
                        <h5 class="text-danger fw-bold"><i class="bi bi-building"></i> {{ $jobOffer->company->title }}</h5>
                        <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $jobOffer->company->location }}</p>
                        <p class="mb-1"><i class="bi bi-file-text"></i> {{ $jobOffer->company->description }}</p>
                        <p class="mb-1"><i class="bi bi-people"></i> Nhân viên: {{ $jobOffer->company->amount_staff }}</p>
                        <img src="{{ filter_var($jobOffer->company->image_company, FILTER_VALIDATE_URL) ? $jobOffer->company->image_company : asset('storage/' . $jobOffer->company->image_company) }}"
                            alt="Company image" class="img-fluid rounded shadow-sm mt-3">
                    </div>
                </div>
            </div>

            <h4 class="mt-4 text-danger fw-bold">MÔ TẢ CÔNG VIỆC</h4>
            <ul class="list-group list-group-flush">
                @foreach (explode("\n", $jobOffer->job_description) as $description)
                    <li class="list-group-item bg-light"><i class="bi bi-check-circle-fill text-success me-2"></i>{{ $description }}</li>
                @endforeach
            </ul>

            <h4 class="mt-4 text-danger fw-bold">YÊU CẦU</h4>
            <ul class="list-group list-group-numbered">
                @foreach (explode("\n", $jobOffer->job_requirement) as $requirement)
                    <li class="list-group-item bg-light"><i class="bi bi-exclamation-circle text-warning me-2"></i>{{ $requirement }}</li>
                @endforeach
            </ul>

            <h4 class="mt-4 text-danger fw-bold">QUYỀN LỢI</h4>
            <ul class="list-group">
                @foreach ($jobOffer->benefits as $benefit)
                    <li class="list-group-item bg-light"><i class="bi bi-gift text-primary me-2"></i>{{ $benefit->title }} - {{ $benefit->description }}</li>
                @endforeach
            </ul>

            <h4 class="mt-4 text-danger fw-bold">KỸ NĂNG YÊU CẦU</h4>
            <ul class="list-inline">
                @foreach ($jobOffer->skills as $skill)
                    <li class="list-inline-item badge bg-secondary p-2 shadow-sm">{{ $skill->name }}</li>
                @endforeach
            </ul>

            <div class="mt-4 d-flex gap-3">
                <button class="btn btn-danger px-4 py-2 shadow-sm">ỨNG TUYỂN NGAY <i class="bi bi-arrow-right"></i></button>
                <button class="btn btn-secondary px-4 py-2 shadow-sm">LƯU TIN <i class="bi bi-bookmark"></i></button>
            </div>

            <div class="mt-4">
                <h5 class="text-danger">Chia sẻ tin này</h5>
                <a href="#" class="btn btn-outline-primary me-2 shadow-sm"><i class="bi bi-link"></i> Copy link</a>
                <a href="#" class="btn btn-outline-primary shadow-sm"><i class="bi bi-facebook"></i> Facebook</a>
            </div>
        </div>
    </div>

    <!-- Modal ỨNG TUYỂN -->
    <div class="modal fade" id="applyJobModal" tabindex="-1" aria-labelledby="applyJobModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="applyJobModalLabel">Ứng tuyển: {{ $jobOffer->job_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job_applications.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_offer_id" value="{{ $jobOffer->id }}">

                    <div class="mb-3">
                        <label for="applicant_name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="applicant_name" name="applicant_name"
                            required value="{{ old('applicant_name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="applicant_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="applicant_email" name="applicant_email"
                            required value="{{ old('applicant_email') }}">
                    </div>

                    <div class="mb-3">
                        <label for="applicant_phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="applicant_phone" name="applicant_phone"
                            required value="{{ old('applicant_phone') }}">
                    </div>

                    <div class="mb-3">
                        <label for="applicant_cv" class="form-label">Tải lên CV</label>
                        <input type="file" class="form-control" id="applicant_cv" name="cv" accept=".pdf"
                            required>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 fw-bold">Gửi ứng tuyển</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
