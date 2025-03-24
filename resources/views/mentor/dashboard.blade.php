@extends('layouts.mentor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Thông tin Mentor</h5>
                    <p class="card-text">
                        <strong>Tên:</strong> {{ $mentor->mentor_name }}<br>
                        <strong>Phòng ban:</strong> {{ $mentor->department }}<br>
                        <strong>Chức vụ:</strong> {{ $mentor->position }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Thực tập sinh</h5>
                    <p class="card-text">
                        <strong>Số lượng TTS:</strong> {{ $mentor->interns->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Công việc</h5>
                    <p class="card-text">
                        <strong>Công việc đã giao:</strong> {{ $mentor->assignedTasks->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Danh sách Thực tập sinh</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mentor->interns as $intern)
                                <tr>
                                    <td>{{ $intern->fullname }}</td>
                                    <td>{{ $intern->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $intern->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ $intern->status === 'active' ? 'Đang thực tập' : 'Đã kết thúc' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Công việc đã giao</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên công việc</th>
                                    <th>Người thực hiện</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mentor->assignedTasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->intern->fullname }}</td>
                                    <td>
                                        <span class="badge bg-{{ $task->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ $task->status === 'completed' ? 'Hoàn thành' : 'Đang thực hiện' }}
                                        </span>
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