@extends('layouts.admin')

@section('title', 'Chi tiết tin tuyển dụng')

@section('content')
<div class="content-container">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title">
                    <i class="bi bi-briefcase"></i> 
                    <span class="editable" data-field="job_name" data-original="{{ $jobOffer->job_name }}">
                        {{ $jobOffer->job_name }}
                    </span>
                </h4>
                <div>
                    <div id="saveIndicator" class="text-success" style="display: none;">
                        <i class="bi bi-check-circle"></i> Đã lưu
                    </div>
                    <button class="btn btn-primary me-2" id="saveChanges" style="display: none;">
                        <i class="bi bi-save"></i> Lưu thay đổi
                    </button>
                    <form action="{{ route('admin.job-offers.destroy', $jobOffer->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <h5 class="text-primary">Thông tin công việc</h5>
                    <hr>
                    <div class="mb-3">
                        <strong>Chi tiết:</strong> 
                        <div class="editable form-control" data-field="job_detail" data-original="{{ $jobOffer->job_detail }}">
                            {{ $jobOffer->job_detail }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Mô tả công việc:</h6>
                        <div class="editable form-control" data-field="job_description" data-original="{{ $jobOffer->job_description }}" style="white-space: pre-line;">
                            {{ $jobOffer->job_description }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6>Yêu cầu:</h6>
                        <div class="editable form-control" data-field="job_requirement" data-original="{{ $jobOffer->job_requirement }}" style="white-space: pre-line;">
                            {{ $jobOffer->job_requirement }}
                        </div>
                    </div>

                    <h6 class="mt-4">Kỹ năng yêu cầu:</h6>
                    <div class="mb-4">
                        @foreach($jobOffer->skills as $skill)
                            <span class="badge bg-secondary me-2">{{ $skill->name }}</span>
                        @endforeach
                    </div>

                    <h6 class="mt-4">Quyền lợi:</h6>
                    <ul class="list-group list-group-flush">
                        @foreach($jobOffer->benefits as $benefit)
                            <li class="list-group-item">
                                <i class="bi bi-gift text-danger"></i>
                                <strong>{{ $benefit->title }}:</strong> {{ $benefit->description }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-primary">Thông tin công ty</h5>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-building"></i> Công ty:</label>
                                <select class="form-select editable" data-field="company_id" data-original="{{ $jobOffer->company_id }}">
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ $jobOffer->company_id == $company->id ? 'selected' : '' }}>
                                            {{ $company->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-geo-alt"></i> Địa điểm:</label>
                                <p>{{ $jobOffer->company->location }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-calendar"></i> Hạn nộp:</label>
                                <input type="date" class="form-control editable" data-field="expiration_date" 
                                    value="{{ $jobOffer->expiration_date }}" data-original="{{ $jobOffer->expiration_date }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editableElements = document.querySelectorAll('.editable');
    const saveIndicator = document.getElementById('saveIndicator');
    let saveTimeout;

    function showSaveIndicator() {
        saveIndicator.style.display = 'inline-block';
        setTimeout(() => {
            saveIndicator.style.display = 'none';
        }, 2000);
    }

    function saveChanges(field, value, originalValue) {
        // Nếu giá trị không thay đổi, không cần lưu
        if (value === originalValue) return;

        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            fetch('{{ route('admin.job-offers.update', $jobOffer->id) }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    [field]: value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSaveIndicator();
                    // Cập nhật giá trị gốc
                    element.dataset.original = value;
                } else {
                    alert('Có lỗi xảy ra khi lưu!');
                    // Khôi phục giá trị gốc nếu lỗi
                    element.innerText = element.dataset.original;
                }
            });
        }, 500); // Đợi 500ms sau khi người dùng ngừng gõ
    }

    editableElements.forEach(element => {
        if (!element.matches('select, input')) {
            element.contentEditable = true;
            
            // Style cho vùng có thể edit
            element.classList.add('form-control');
            element.style.minHeight = '38px';
            element.style.cursor = 'pointer';

            // Sự kiện blur (khi nhả chuột)
            element.addEventListener('blur', function() {
                const newValue = this.innerText;
                saveChanges(this.dataset.field, newValue, this.dataset.original);
            });
        } else {
            // Với select và input
            element.addEventListener('change', function() {
                const newValue = this.value;
                saveChanges(this.dataset.field, newValue, this.dataset.original);
            });
        }

        // Hiệu ứng hover
        element.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#f8f9fa';
        });

        element.addEventListener('mouseout', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>

<style>
.editable {
    transition: all 0.3s ease;
}
.editable:hover {
    border-color: #80bdff;
}
.editable:focus {
    background-color: #fff !important;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
#saveIndicator {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border-radius: 4px;
    z-index: 1000;
    animation: fadeInOut 2s ease;
}
@keyframes fadeInOut {
    0% { opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { opacity: 0; }
}
</style>
@endpush

@endsection 