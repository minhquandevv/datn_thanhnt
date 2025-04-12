@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $job->title }}</div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Mô tả công việc</h5>
                        <p>{{ $job->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5>Yêu cầu</h5>
                        <p>{{ $job->requirements }}</p>
                    </div>

                    <div class="mb-4">
                        <h5>Mức lương</h5>
                        <p>{{ $job->salary_range }}</p>
                    </div>

                    <div class="mb-4">
                        <h5>Địa điểm</h5>
                        <p>{{ $job->location }}</p>
                    </div>

                    @auth('candidate')
                        @php
                            $candidate = Auth::guard('candidate')->user();
                            $hasApplied = $job->applications()->where('candidate_id', $candidate->id)->exists();
                        @endphp

                        @if(!$hasApplied)
                            <form action="{{ route('jobs.apply', $job->id) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="form-group">
                                    <label for="cover_letter">Thư xin việc</label>
                                    <textarea name="cover_letter" id="cover_letter" class="form-control @error('cover_letter') is-invalid @enderror" rows="5">{{ old('cover_letter') }}</textarea>
                                    @error('cover_letter')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="resume">CV của bạn</label>
                                    <input type="file" name="resume" id="resume" class="form-control @error('resume') is-invalid @enderror">
                                    @error('resume')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Bạn có thể tải lên CV mới hoặc sử dụng CV đã lưu</small>
                                </div>

                                <button type="submit" class="btn btn-primary">Nộp đơn ứng tuyển</button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                Bạn đã nộp đơn ứng tuyển cho vị trí này.
                            </div>
                        @endif

                        <div class="card mt-4">
                            <div class="card-header">Thông tin ứng viên</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        @if($candidate->url_avatar)
                                            <img src="{{ asset('storage/' . $candidate->url_avatar) }}" alt="Avatar" class="img-fluid rounded">
                                        @else
                                            <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" class="img-fluid rounded">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h5>{{ $candidate->fullname }}</h5>
                                        <p><strong>Email:</strong> {{ $candidate->email }}</p>
                                        <p><strong>Số điện thoại:</strong> {{ $candidate->phone_number }}</p>
                                        <p><strong>Địa chỉ:</strong> {{ $candidate->address }}</p>
                                    </div>
                                </div>

                                @if($candidate->skills->count() > 0)
                                    <div class="mt-3">
                                        <h6>Kỹ năng</h6>
                                        <ul class="list-unstyled">
                                            @foreach($candidate->skills as $skill)
                                                <li>{{ $skill->skill_name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if($candidate->experience->count() > 0)
                                    <div class="mt-3">
                                        <h6>Kinh nghiệm làm việc</h6>
                                        <ul class="list-unstyled">
                                            @foreach($candidate->experience as $exp)
                                                <li>
                                                    <strong>{{ $exp->company_name }}</strong> - {{ $exp->position }}
                                                    <br>
                                                    {{ $exp->date_start->format('m/Y') }} - {{ $exp->date_end ? $exp->date_end->format('m/Y') : 'Hiện tại' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if($candidate->education->count() > 0)
                                    <div class="mt-3">
                                        <h6>Học vấn</h6>
                                        <ul class="list-unstyled">
                                            @foreach($candidate->education as $edu)
                                                <li>
                                                    <strong>{{ $edu->school_name }}</strong>
                                                    <br>
                                                    {{ $edu->level }} - {{ $edu->department }}
                                                    <br>
                                                    {{ $edu->graduate_date->format('Y') }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để nộp đơn ứng tuyển.
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 