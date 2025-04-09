@extends('layouts.mentor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Thêm công việc mới</h1>
            <p class="text-muted mb-0">Tạo công việc mới cho thực tập sinh</p>
        </div>
        <a href="{{ route('mentor.tasks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('mentor.tasks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="assigned_by" value="{{ auth()->guard('mentor')->user()->mentor_id }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="project_name" class="form-label">Tên dự án</label>
                            <input type="text" 
                                   class="form-control @error('project_name') is-invalid @enderror" 
                                   id="project_name" 
                                   name="project_name" 
                                   value="{{ old('project_name', request()->get('project_name')) }}"
                                   placeholder="Nhập tên dự án">
                            @error('project_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="task_name" class="form-label">Tên công việc <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('task_name') is-invalid @enderror" 
                                   id="task_name" 
                                   name="task_name" 
                                   value="{{ old('task_name', request()->get('task_name')) }}" 
                                   required
                                   placeholder="Nhập tên công việc">
                            @error('task_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="intern_id" class="form-label">Thực tập sinh <span class="text-danger">*</span></label>
                            <select class="form-select @error('intern_id') is-invalid @enderror" 
                                    id="intern_id" 
                                    name="intern_id" 
                                    required>
                                <option value="">Chọn thực tập sinh</option>
                                @foreach($interns as $intern)
                                    <option value="{{ $intern->intern_id }}" 
                                            {{ old('intern_id', request()->get('intern_id')) == $intern->intern_id ? 'selected' : '' }}>
                                        {{ $intern->fullname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('intern_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="assigned_date" class="form-label">Ngày giao việc <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('assigned_date') is-invalid @enderror" 
                                   id="assigned_date" 
                                   name="assigned_date" 
                                   value="{{ old('assigned_date', date('Y-m-d')) }}" 
                                   required>
                            @error('assigned_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Hạn hoàn thành <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('deadline') is-invalid @enderror" 
                                   id="deadline" 
                                   name="deadline" 
                                   value="{{ old('deadline', date('Y-m-d')) }}" 
                                   required>
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="">Chọn trạng thái</option>
                                <option value="Chưa bắt đầu" {{ old('status') == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu</option>
                                <option value="Đang thực hiện" {{ old('status') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                                <option value="Đã hoàn thành" {{ old('status') == 'Đã hoàn thành' ? 'selected' : '' }}>Đã hoàn thành</option>
                                <option value="Bị hủy" {{ old('status') == 'Bị hủy' ? 'selected' : '' }}>Bị hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <input type="hidden" name="status" value="Chưa bắt đầu">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="attachments" class="form-label">File đính kèm</label>
                            <input type="file" 
                                   class="form-control @error('attachments') is-invalid @enderror" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar">
                            <small class="form-text text-muted">Cho phép các file: PDF, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR (Tối đa 10MB)</small>
                            @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="fileList" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="requirements" class="form-label">Yêu cầu công việc</label>
                    <textarea class="form-control @error('requirements') is-invalid @enderror" 
                              id="requirements" 
                              name="requirements" 
                              rows="4"
                              placeholder="Nhập yêu cầu công việc">{{ old('requirements') }}</textarea>
                    @error('requirements')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="resultFields" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="result" class="form-label">Kết quả</label>
                                <textarea class="form-control @error('result') is-invalid @enderror" 
                                          id="result" 
                                          name="result" 
                                          rows="3"
                                          placeholder="Nhập kết quả công việc">{{ old('result') }}</textarea>
                                @error('result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mentor_comment" class="form-label">Nhận xét của mentor</label>
                                <textarea class="form-control @error('mentor_comment') is-invalid @enderror" 
                                          id="mentor_comment" 
                                          name="mentor_comment" 
                                          rows="3"
                                          placeholder="Nhập nhận xét của bạn">{{ old('mentor_comment') }}</textarea>
                                @error('mentor_comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evaluation" class="form-label">Đánh giá</label>
                                <select class="form-select @error('evaluation') is-invalid @enderror" 
                                        id="evaluation" 
                                        name="evaluation">
                                    <option value="">Chọn đánh giá</option>
                                    <option value="Rất tốt" {{ old('evaluation') == 'Rất tốt' ? 'selected' : '' }}>Rất tốt</option>
                                    <option value="Tốt" {{ old('evaluation', 'Tốt') == 'Tốt' ? 'selected' : '' }}>Tốt</option>
                                    <option value="Trung bình" {{ old('evaluation') == 'Trung bình' ? 'selected' : '' }}>Trung bình</option>
                                    <option value="Kém" {{ old('evaluation') == 'Kém' ? 'selected' : '' }}>Kém</option>
                                </select>
                                @error('evaluation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Thêm mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-label {
    font-weight: 500;
    color: #495057;
}

.form-control:focus, .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-secondary {
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    background-color: #6b6d7d;
    border-color: #656776;
}

.text-danger {
    color: #e74a3b !important;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const resultFields = document.getElementById('resultFields');
    const fileInput = document.getElementById('attachments');
    const fileList = document.getElementById('fileList');

    function toggleResultFields() {
        if (statusSelect.value === 'Hoàn thành') {
            resultFields.style.display = 'block';
        } else {
            resultFields.style.display = 'none';
            document.getElementById('result').value = '';
            document.getElementById('mentor_comment').value = '';
            document.getElementById('evaluation').value = '';
        }
    }

    function updateFileList() {
        fileList.innerHTML = '';
        if (fileInput.files.length > 0) {
            const ul = document.createElement('ul');
            ul.className = 'list-group';
            
            Array.from(fileInput.files).forEach(file => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `
                    <span>${file.name}</span>
                    <span class="badge bg-primary rounded-pill">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                `;
                ul.appendChild(li);
            });
            
            fileList.appendChild(ul);
        }
    }

    statusSelect.addEventListener('change', toggleResultFields);
    fileInput.addEventListener('change', updateFileList);
    toggleResultFields();
});
</script>
@endpush
@endsection 