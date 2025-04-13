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

                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="attachments" class="form-label">File đính kèm</label>
                                    <input type="file" 
                                           class="form-control @error('attachments') is-invalid @enderror" 
                                           id="attachments" 
                                           name="attachments[]" 
                                           multiple
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar"
                                           onchange="showFileNames(this)">
                                    <small class="form-text text-muted">Cho phép các file: PDF, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR (Tối đa 10MB)</small>
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    <div id="fileList" class="mt-4">
                                        <div class="alert alert-info">Chưa có file nào được chọn</div>
                                    </div>
                                </div>
                            </div>
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

.file-list-container {
    margin-top: 0;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 10px;
    background-color: #f8f9fc;
    height: 100%;
}

.file-list-header {
    margin-bottom: 10px;
    color: #4e73df;
    font-size: 1rem;
}

.file-list {
    margin-bottom: 0;
    max-height: 200px;
    overflow-y: auto;
}

.file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    margin-bottom: 5px;
    border-radius: 0.25rem;
    background-color: #fff;
    border: 1px solid #e3e6f0;
}

.file-info {
    display: flex;
    align-items: center;
    flex: 1;
    overflow: hidden;
}

.file-name {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
    font-size: 0.9rem;
    color: #333;
}

/* Add styles for the delete button */
.btn-danger {
    background-color: #e74a3b;
    border-color: #e74a3b;
    color: white;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.btn-danger:hover {
    background-color: #d52a1a;
    border-color: #d52a1a;
}

.bi-x {
    font-weight: bold;
}
</style>

@push('scripts')
<script>
// Function to display file names - defined in global scope
function showFileNames(input) {
    const fileList = document.getElementById('fileList');
    
    if (input.files.length > 0) {
        let html = '<div class="card">';
        html += '<div class="card-header bg-primary text-white">';
        html += `<strong>Danh sách file đã chọn (${input.files.length})</strong>`;
        html += '</div>';
        html += '<div class="card-body">';
        html += '<ul class="list-group">';
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            html += '<li class="list-group-item d-flex justify-content-between align-items-center">';
            html += `<span>${file.name}</span>`;
            html += '<div>';
            html += `<span class="badge bg-primary rounded-pill me-2">${fileSize} MB</span>`;
            html += `<button type="button" class="btn btn-sm btn-danger" onclick="removeFile(${i})"><i class="bi bi-x"></i></button>`;
            html += '</div>';
            html += '</li>';
        }
        
        html += '</ul>';
        html += '</div>';
        html += '</div>';
        
        fileList.innerHTML = html;
    } else {
        fileList.innerHTML = '<div class="alert alert-info">Chưa có file nào được chọn</div>';
    }
}

// Function to remove a file from the input
function removeFile(index) {
    const fileInput = document.getElementById('attachments');
    
    // Create a new FileList without the selected file
    const dt = new DataTransfer();
    const { files } = fileInput;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    // Update the file input with the new FileList
    fileInput.files = dt.files;
    
    // Update the display
    showFileNames(fileInput);
}

document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const resultFields = document.getElementById('resultFields');

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
    
    if (statusSelect) {
        statusSelect.addEventListener('change', toggleResultFields);
        toggleResultFields();
    }
});
</script>
@endpush
@endsection 