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

        .candidate-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .candidate-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-4">
        @auth('candidate')
            @php
                $candidate = Auth::guard('candidate')->user();
                $hasApplied = $jobOffer->applications()->where('candidate_id', $candidate->id)->exists();
            @endphp

            <div class="candidate-info mb-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        @if($candidate->url_avatar)
                            <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" alt="Avatar" class="candidate-avatar">
                        @else
                            <div class="candidate-avatar bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle">
                                {{ substr($candidate->fullname, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <h4 class="text-danger mb-3">{{ $candidate->fullname }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="bi bi-envelope"></i> {{ $candidate->email }}</p>
                                <p><i class="bi bi-telephone"></i> {{ $candidate->phone_number }}</p>
                                <p><i class="bi bi-geo-alt"></i> {{ $candidate->address }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="bi bi-briefcase"></i> {{ $candidate->experience_year }} năm kinh nghiệm</p>
                                @if($candidate->position)
                                    <p><i class="bi bi-person-badge"></i> {{ $candidate->position }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($candidate->skills->count() > 0)
                    <div class="mt-3">
                        <h6 class="text-danger">Kỹ năng</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($candidate->skills as $skill)
                                <span class="badge bg-secondary">{{ $skill->skill_name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endauth

        <div class="card shadow-lg p-4 border-0 rounded-4 bg-white">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                <h2 class="fw-bold text-danger"><i class="bi bi-briefcase me-2"></i>{{ $jobOffer->job_name }}</h2>
                @if($isAdmin)
                    <a href="{{ route('admin.job-offers.edit', $jobOffer->id) }}" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa tin tuyển dụng
                    </a>
                @elseif(Auth::guard('candidate')->check())
                    @if($hasApplied)
                        <button class="btn btn-secondary fw-bold px-4 py-2 rounded-pill shadow-sm" disabled>
                            ĐÃ ỨNG TUYỂN <i class="bi bi-check-circle"></i>
                        </button>
                    @else
                        <button class="btn btn-danger fw-bold px-4 py-2 rounded-pill shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#applyJobModal">
                            ỨNG TUYỂN NGAY <i class="bi bi-arrow-right"></i>
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-danger fw-bold px-4 py-2 rounded-pill shadow-sm">
                        ĐĂNG NHẬP ĐỂ ỨNG TUYỂN <i class="bi bi-arrow-right"></i>
                    </a>
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
    <div class="modal fade" id="applyJobModal" tabindex="-1" aria-labelledby="applyJobModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="applyJobModalLabel">Ứng tuyển: {{ $jobOffer->job_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('job_applications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="job_offer_id" value="{{ $jobOffer->id }}">
                        <input type="hidden" name="candidate_id" value="{{ Auth::guard('candidate')->id() }}">

                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">Thư xin việc</label>
                            <textarea class="form-control @error('cover_letter') is-invalid @enderror" 
                                id="cover_letter" name="cover_letter" rows="5" required>{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cv" class="form-label">CV của bạn</label>
                            <input type="file" class="form-control @error('cv') is-invalid @enderror" 
                                id="cv" name="cv" accept=".pdf,.doc,.docx">
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Bạn có thể tải lên CV mới hoặc sử dụng CV đã lưu</small>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 fw-bold">Gửi ứng tuyển</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
