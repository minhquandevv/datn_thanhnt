@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-red: #D40000;
            --secondary-red: #ff1a1a;
            --dark: #1a1a1a;
            --light-red: rgba(212, 0, 0, 0.1);
        }

        .nav-pills .nav-link {
            background-color: var(--light-red);
            color: var(--dark);
            transition: all 0.3s ease;
            font-weight: 600;
            border-radius: 30px;
            padding: 0.8rem 2rem;
            margin: 0 0.5rem;
        }

        .nav-pills .nav-link.active,
        .nav-pills .nav-link:hover {
            background-color: var(--primary-red);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(212, 0, 0, 0.2);
        }

        .nav-pills .nav-link i {
            margin-right: 0.5rem;
        }

        .tab-content {
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .job-info-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .job-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
        }

        .candidate-info {
            background: linear-gradient(145deg, white, #f8f9fa);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .candidate-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-red);
            box-shadow: 0 4px 15px rgba(212, 0, 0, 0.2);
        }

        .list-group-item {
            border: none;
            background: var(--light-red);
            margin-bottom: 0.5rem;
            border-radius: 10px !important;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            transform: translateX(5px);
            background: rgba(212, 0, 0, 0.15);
        }

        .badge {
            padding: 0.8rem 1.2rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .badge.bg-secondary {
            background: var(--light-red) !important;
            color: var(--primary-red);
        }

        .btn-red {
            background: var(--primary-red);
            border-color: var(--primary-red);
            color: white;
            border-radius: 30px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 0, 0, 0.2);
        }

        .btn-red:hover {
            background: var(--secondary-red);
            border-color: var(--secondary-red);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 0, 0, 0.3);
        }

        .btn-outline-red {
            color: var(--primary-red);
            border-color: var(--primary-red);
            border-radius: 30px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-red:hover {
            background: var(--primary-red);
            border-color: var(--primary-red);
            color: white;
            transform: translateY(-2px);
        }

        .section-title {
            color: var(--primary-red);
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-left: 1rem;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-red);
            border-radius: 2px;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            border-bottom: 2px solid var(--light-red);
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 0.2rem rgba(212, 0, 0, 0.1);
        }

        .alert {
            border-radius: 12px;
            padding: 1rem 1.5rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .share-buttons .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .share-buttons .btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .nav-pills .nav-link {
                padding: 0.6rem 1rem;
                margin: 0 0.3rem;
                font-size: 0.9rem;
            }

            .candidate-avatar {
                width: 100px;
                height: 100px;
            }

            .badge {
                padding: 0.6rem 1rem;
                font-size: 0.8rem;
            }
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tìm phần tử chứa job offers
                const jobOffersSection = document.querySelector('.card.shadow-lg');
                if (jobOffersSection) {
                    // Scroll đến phần tử với hiệu ứng mượt
                    jobOffersSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        </script>
    @endif

    @if (session('error'))
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
                        @if ($candidate->url_avatar)
                            <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" alt="Avatar" class="candidate-avatar">
                        @else
                            <div
                                class="candidate-avatar bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle">
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
                        </div>
                    </div>
                </div>

                @if ($candidate->skills->count() > 0)
                    <div class="mt-3">
                        <h6 class="text-danger">Kỹ năng</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($candidate->skills as $skill)
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
                @if ($isAdmin)
                    <a href="{{ route('admin.job-offers.edit', $jobOffer->id) }}"
                        class="btn btn-danger fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa tin tuyển dụng
                    </a>
                @elseif(Auth::guard('candidate')->check())
                    @if ($hasApplied)
                        <button class="btn btn-secondary fw-bold px-4 py-2 rounded-pill shadow-sm" disabled>
                            ĐÃ ỨNG TUYỂN <i class="bi bi-check-circle"></i>
                        </button>
                    @elseif($jobOffer->job_quantity <= 0)
                        <button class="btn btn-secondary fw-bold px-4 py-2 rounded-pill shadow-sm" disabled>
                            ĐÃ ĐỦ ỨNG VIÊN <i class="bi bi-x-circle"></i>
                        </button>
                    @elseif(!\Carbon\Carbon::parse($jobOffer->expiration_date)->isFuture())
                        <button class="btn btn-secondary fw-bold px-4 py-2 rounded-pill shadow-sm" disabled>
                            ĐÃ HẾT HẠN <i class="bi bi-clock"></i>
                        </button>
                    @else
                        <button class="btn btn-danger fw-bold px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#applyJobModal">
                            ỨNG TUYỂN NGAY <i class="bi bi-arrow-right"></i>
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-danger fw-bold px-4 py-2 rounded-pill shadow-sm">
                        ĐĂNG NHẬP ĐỂ ỨNG TUYỂN <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
            </div>
            <p class="text-muted mt-2"><i class="bi bi-building me-1"></i>
                {{ $jobOffer->department ? $jobOffer->department->name : 'Chưa phân công' }}</p>
            <p class="text-muted">
                <i class="bi bi-calendar me-1"></i> Ngày hết hạn:
                {{ \Carbon\Carbon::parse($jobOffer->expiration_date)->format('d/m/Y') }}
            </p>

            <h4 class="mt-4 text-danger fw-bold">MÔ TẢ CÔNG VIỆC</h4>
            <ul class="list-group list-group-flush">
                @foreach (explode("\n", $jobOffer->job_description) as $description)
                    <li class="list-group-item bg-light"><i
                            class="bi bi-check-circle-fill text-success me-2"></i>{{ $description }}</li>
                @endforeach
            </ul>

            <h4 class="mt-4 text-danger fw-bold">YÊU CẦU</h4>
            <ul class="list-group list-group-numbered">
                @foreach (explode("\n", $jobOffer->job_requirement) as $requirement)
                    <li class="list-group-item bg-light"><i
                            class="bi bi-exclamation-circle text-warning me-2"></i>{{ $requirement }}</li>
                @endforeach
            </ul>

            <h4 class="mt-4 text-danger fw-bold">QUYỀN LỢI</h4>
            <ul class="list-group">
                @foreach ($jobOffer->benefits as $benefit)
                    <li class="list-group-item bg-light"><i class="bi bi-gift text-primary me-2"></i>{{ $benefit->title }}
                        - {{ $benefit->description }}</li>
                @endforeach
            </ul>

            <h4 class="mt-4 text-danger fw-bold">KỸ NĂNG YÊU CẦU</h4>
            <ul class="list-inline">
                @foreach ($jobOffer->skills as $skill)
                    <li class="list-inline-item badge bg-secondary p-2 shadow-sm">{{ $skill->name }}</li>
                @endforeach
            </ul>


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

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                    id="fullname" name="fullname"
                                    value="{{ old('fullname', Auth::guard('candidate')->check() ? Auth::guard('candidate')->user()->fullname : '') }}"
                                    required>
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="university_id" class="form-label">Trường học</label>
                                <select class="form-select @error('university_id') is-invalid @enderror"
                                    id="university_id" name="university_id" required>
                                    <option value="">Chọn trường học</option>
                                    @foreach (\App\Models\University::orderBy('name')->get() as $university)
                                        <option value="{{ $university->university_id }}"
                                            {{ old('university_id', Auth::guard('candidate')->check() ? Auth::guard('candidate')->user()->university_id : '') == $university->university_id ? 'selected' : '' }}>
                                            {{ $university->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('university_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                    id="phone_number" name="phone_number"
                                    value="{{ old('phone_number', Auth::guard('candidate')->check() ? Auth::guard('candidate')->user()->phone_number : '') }}"
                                    required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email"
                                    value="{{ old('email', Auth::guard('candidate')->check() ? Auth::guard('candidate')->user()->email : '') }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">Thư xin việc</label>
                            <textarea class="form-control @error('cover_letter') is-invalid @enderror" id="cover_letter" name="cover_letter"
                                rows="5" required>{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cv" class="form-label">CV của bạn</label>
                            <input type="file" class="form-control @error('cv') is-invalid @enderror" id="cv"
                                name="cv" accept=".pdf,.doc,.docx">
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
