@extends('layouts.mentor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa công việc</h1>
            <p class="text-muted mb-0">Cập nhật thông tin công việc</p>
        </div>
        <a href="{{ route('mentor.tasks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('mentor.tasks.update', $task->task_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="project_name" class="form-label">Tên dự án</label>
                            <input type="text" 
                                   class="form-control @error('project_name') is-invalid @enderror" 
                                   id="project_name" 
                                   name="project_name" 
                                   value="{{ old('project_name', $task->project_name) }}">
                            @error('project_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="task_name" class="form-label">Tên công việc</label>
                            <input type="text" 
                                   class="form-control @error('task_name') is-invalid @enderror" 
                                   id="task_name" 
                                   name="task_name" 
                                   value="{{ old('task_name', $task->task_name) }}" 
                                   required>
                            @error('task_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="intern_id" class="form-label">Thực tập sinh</label>
                            <select class="form-select @error('intern_id') is-invalid @enderror" 
                                    id="intern_id" 
                                    name="intern_id" 
                                    required>
                                <option value="">Chọn thực tập sinh</option>
                                @foreach($interns as $intern)
                                    <option value="{{ $intern->intern_id }}" 
                                            {{ old('intern_id', $task->intern_id) == $intern->intern_id ? 'selected' : '' }}>
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
                            <label for="assigned_date" class="form-label">Ngày giao việc</label>
                            <input type="date" 
                                   class="form-control @error('assigned_date') is-invalid @enderror" 
                                   id="assigned_date" 
                                   name="assigned_date" 
                                   value="{{ old('assigned_date', $task->assigned_date) }}" 
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
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="">Chọn trạng thái</option>
                                <option value="Chưa bắt đầu" {{ old('status', $task->status) == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu</option>
                                <option value="Đang thực hiện" {{ old('status', $task->status) == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                                <option value="Hoàn thành" {{ old('status', $task->status) == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="Trễ hạn" {{ old('status', $task->status) == 'Trễ hạn' ? 'selected' : '' }}>Trễ hạn</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="attachment" class="form-label">File đính kèm</label>
                            <input type="file" 
                                   class="form-control @error('attachment') is-invalid @enderror" 
                                   id="attachment" 
                                   name="attachment">
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($task->attachment)
                                <div class="mt-2">
                                    <p class="mb-0">{{ basename($task->attachment) }}</p>
                                    <a href="{{ asset('' . $task->attachment) }}" target="_blank" class="text-primary">
                                        <i class="bi bi-file-earmark"></i> Xem file hiện tại
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="requirements" class="form-label">Yêu cầu công việc</label>
                    <textarea class="form-control @error('requirements') is-invalid @enderror" 
                              id="requirements" 
                              name="requirements" 
                              rows="4">{{ old('requirements', $task->requirements) }}</textarea>
                    @error('requirements')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($task->status === 'Hoàn thành')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="result" class="form-label">Kết quả</label>
                            <textarea class="form-control @error('result') is-invalid @enderror" 
                                      id="result" 
                                      name="result" 
                                      rows="3">{{ old('result', $task->result) }}</textarea>
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
                                      rows="3">{{ old('mentor_comment', $task->mentor_comment) }}</textarea>
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
                                <option value="Rất tốt" {{ old('evaluation', $task->evaluation) == 'Rất tốt' ? 'selected' : '' }}>Rất tốt</option>
                                <option value="Tốt" {{ old('evaluation', $task->evaluation) == 'Tốt' ? 'selected' : '' }}>Tốt</option>
                                <option value="Trung bình" {{ old('evaluation', $task->evaluation) == 'Trung bình' ? 'selected' : '' }}>Trung bình</option>
                                <option value="Kém" {{ old('evaluation', $task->evaluation) == 'Kém' ? 'selected' : '' }}>Kém</option>
                            </select>
                            @error('evaluation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Cập nhật
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
</style>
@endsection 