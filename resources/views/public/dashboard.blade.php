@extends('layouts.app')

@section('title', 'Trang chủ - Tuyển dụng')

@section('content')

    <!-- Danh sách tin tuyển dụng -->
    <div class="container mt-5">
        <h3 class="text-primary fw-bold">Tin tuyển dụng</h3>

        @if ($jobOffers->isEmpty())
            <p class="text-muted">Chưa có tin tuyển dụng nào.</p>
        @else
            @foreach ($jobOffers as $worker)
                <div class="job-listing p-3 border rounded shadow-sm mb-3">
                    <h5 class="fw-bold">{{ $worker->job_name }}</h5>
                    <p>{{ Str::limit($worker->job_detail, 100) }}</p>
                    <p class="text-muted mt-2">Hạn: {{ $worker->expiration_date }}</p>
                    <a href="{{ route('public.show', $worker->id) }}" class="btn btn-danger btn-sm">Xem chi tiết</a>
                </div>
            @endforeach
        @endif
    </div>
@endsection
