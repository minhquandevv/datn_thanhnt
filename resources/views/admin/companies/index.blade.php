@extends('layouts.admin')

@section('title', 'Quản lý công ty')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý công ty</h1>
        <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
            <i class="bi bi-plus-lg me-2"></i>Thêm công ty mới
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">ID</th>
                            <th class="text-center" style="width: 100px;">Logo</th>
                            <th>Tên công ty</th>
                            <th>Địa điểm</th>
                            <th class="text-center" style="width: 150px;">Số nhân viên</th>
                            <th class="text-center" style="width: 120px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td class="text-center">{{ $company->id }}</td>
                                <td class="text-center">
                                    @if($company->image_company)
                                        <img src="{{ filter_var($company->image_company, FILTER_VALIDATE_URL) ? $company->image_company : asset('public/companies/' . $company->image_company) }}" 
                                             alt="{{ $company->title }}" 
                                             class="img-thumbnail rounded-circle shadow-sm" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-building text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $company->title }}</div>
                                    <small class="text-muted">{{ Str::limit($company->description, 50) }}</small>
                                </td>
                                <td>
                                    <i class="bi bi-geo-alt text-primary me-1"></i>
                                    {{ $company->location }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">
                                        <i class="bi bi-people me-1"></i>
                                        {{ $company->amount_staff ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" 
                                                style="width: 60px; height: 48px; display: flex; align-items: center; justify-content: center;"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editCompanyModal{{ $company->id }}"
                                                title="Chỉnh sửa">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </button>
                                        <form action="{{ route('admin.companies.destroy', $company) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    style="width: 60px; height: 48px; display: flex; align-items: center; justify-content: center;"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa công ty {{ $company->title }}?');"
                                                    title="Xóa">
                                                <i class="bi bi-trash3 fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editCompanyModal{{ $company->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title">
                                                <i class="bi bi-pencil-square me-2"></i>
                                                Chỉnh sửa công ty
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.companies.update', $company) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="title{{ $company->id }}" class="form-label">
                                                                <i class="bi bi-building me-1"></i>
                                                                Tên công ty <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text" 
                                                                   class="form-control @error('title') is-invalid @enderror" 
                                                                   id="title{{ $company->id }}" 
                                                                   name="title" 
                                                                   value="{{ old('title', $company->title) }}" 
                                                                   required>
                                                            @error('title')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="location{{ $company->id }}" class="form-label">
                                                                <i class="bi bi-geo-alt me-1"></i>
                                                                Địa điểm <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text" 
                                                                   class="form-control @error('location') is-invalid @enderror" 
                                                                   id="location{{ $company->id }}" 
                                                                   name="location" 
                                                                   value="{{ old('location', $company->location) }}" 
                                                                   required>
                                                            @error('location')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="amount_staff{{ $company->id }}" class="form-label">
                                                                <i class="bi bi-people me-1"></i>
                                                                Số nhân viên
                                                            </label>
                                                            <input type="number" 
                                                                   class="form-control @error('amount_staff') is-invalid @enderror" 
                                                                   id="amount_staff{{ $company->id }}" 
                                                                   name="amount_staff" 
                                                                   value="{{ old('amount_staff', $company->amount_staff) }}" 
                                                                   min="1">
                                                            @error('amount_staff')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="image_company{{ $company->id }}" class="form-label">
                                                                <i class="bi bi-image me-1"></i>
                                                                Logo công ty
                                                            </label>
                                                            @if($company->image_company)
                                                                <div class="mb-2">
                                                                    <img src="{{ filter_var($company->image_company, FILTER_VALIDATE_URL) ? $company->image_company : asset('public/companies/' . $company->image_company) }}" 
                                                                         alt="{{ $company->title }}" 
                                                                         class="img-thumbnail shadow-sm" 
                                                                         style="max-width: 150px;">
                                                                </div>
                                                            @endif
                                                            <input type="file" 
                                                                   class="form-control @error('image_company') is-invalid @enderror" 
                                                                   id="image_company{{ $company->id }}" 
                                                                   name="image_company" 
                                                                   accept="image/*">
                                                            @error('image_company')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <div class="form-text">Kích thước tối đa: 2MB. Định dạng: JPEG, PNG, JPG, GIF</div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="description{{ $company->id }}" class="form-label">
                                                                <i class="bi bi-text-paragraph me-1"></i>
                                                                Mô tả <span class="text-danger">*</span>
                                                            </label>
                                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                                      id="description{{ $company->id }}" 
                                                                      name="description" 
                                                                      rows="5" 
                                                                      required>{{ old('description', $company->description) }}</textarea>
                                                            @error('description')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle me-2"></i>Hủy
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-save me-2"></i>Cập nhật
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-building me-2"></i>
                                        Không có công ty nào.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $companies->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCompanyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>
                    Thêm công ty mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="bi bi-building me-1"></i>
                                    Tên công ty <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Địa điểm <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location') }}" 
                                       required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="amount_staff" class="form-label">
                                    <i class="bi bi-people me-1"></i>
                                    Số nhân viên
                                </label>
                                <input type="number" 
                                       class="form-control @error('amount_staff') is-invalid @enderror" 
                                       id="amount_staff" 
                                       name="amount_staff" 
                                       value="{{ old('amount_staff') }}" 
                                       min="1">
                                @error('amount_staff')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image_company" class="form-label">
                                    <i class="bi bi-image me-1"></i>
                                    Logo công ty
                                </label>
                                <input type="file" 
                                       class="form-control @error('image_company') is-invalid @enderror" 
                                       id="image_company" 
                                       name="image_company" 
                                       accept="image/*">
                                @error('image_company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kích thước tối đa: 2MB. Định dạng: JPEG, PNG, JPG, GIF</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="bi bi-text-paragraph me-1"></i>
                                    Mô tả <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="5" 
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Modal styles */
    .modal {
        transition: none !important;
    }
    
    .modal.fade .modal-dialog {
        transition: transform 0.2s ease-out !important;
        transform: scale(0.95);
    }
    
    .modal.show .modal-dialog {
        transform: none;
    }
    
    .modal-content {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .modal-backdrop {
        transition: opacity 0.2s linear !important;
    }
    
    .modal-header {
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.5rem;
        background-color: #f8f9fc;
    }
    
    .modal-footer {
        border-top: 1px solid #e3e6f0;
        padding: 1rem 1.5rem;
        background-color: #f8f9fc;
    }
    
    .modal-footer .btn {
        min-width: 100px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    /* Button styles */
    .btn-sm {
        width: 48px;
        height: 48px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        font-size: 1.25rem;
    }
    
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        box-shadow: 0 2px 4px rgba(78, 115, 223, 0.15);
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(78, 115, 223, 0.2);
    }
    
    .btn-danger {
        background-color: #e74a3b;
        border-color: #e74a3b;
        box-shadow: 0 2px 4px rgba(231, 74, 59, 0.15);
    }
    
    .btn-danger:hover {
        background-color: #be2617;
        border-color: #be2617;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(231, 74, 59, 0.2);
    }

    /* Table styles */
    .table > :not(caption) > * > * {
        padding: 1rem;
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tắt animation mặc định của Bootstrap modal
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            document.body.classList.add('modal-open');
            this.style.display = 'block';
            setTimeout(() => this.classList.add('show'), 10);
        });

        modal.addEventListener('hide.bs.modal', function() {
            this.classList.remove('show');
            setTimeout(() => {
                this.style.display = 'none';
                document.body.classList.remove('modal-open');
            }, 200);
        });
    });
});
</script>
@endpush

@endsection 