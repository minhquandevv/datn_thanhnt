@extends('layouts.admin')

@section('title', 'View Not Found')

@section('content')
<div class="container-fluid">
    <div class="text-center mt-5">
        <div class="error mx-auto" data-text="404">404</div>
        <p class="lead text-gray-800 mb-4">Không tìm thấy giao diện</p>
        <p class="text-gray-500 mb-0">Giao diện bạn đang tìm kiếm chưa được tạo hoặc đã bị di chuyển.</p>
        
        <hr class="my-4">
        
        @if(config('app.debug'))
        <div class="error-details mb-4">
            <h5 class="text-danger">Chi tiết lỗi:</h5>
            <div class="card shadow mb-4 mx-auto" style="max-width: 800px;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông báo lỗi</h6>
                </div>
                <div class="card-body text-left">
                    <code>{{ $exception->getMessage() }}</code>
                </div>
            </div>
            
            <div class="alert alert-info mx-auto" style="max-width: 800px;">
                <i class="fas fa-info-circle mr-1"></i> <strong>Thông báo cho developer:</strong> 
                <p class="mb-0 mt-2">Vui lòng kiểm tra tên view được gọi trong controller và đảm bảo file view đã được tạo tại đường dẫn tương ứng.</p>
            </div>
        </div>
        @endif
        
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left mr-1"></i> Quay về trang chủ
        </a>
    </div>
</div>

<style>
    .error {
        color: #5a5c69;
        font-size: 7rem;
        position: relative;
        line-height: 1;
        width: 12.5rem;
        margin: 0 auto 1rem;
    }
    
    .error:before {
        content: attr(data-text);
        position: absolute;
        left: -2px;
        top: 0;
        text-shadow: 1px 0 #e74a3b;
        opacity: 0.7;
    }
    
    .error:after {
        content: attr(data-text);
        position: absolute;
        left: 2px;
        top: 0;
        text-shadow: -1px 0 #4e73df;
        opacity: 0.7;
    }
    
    code {
        background-color: #f8f9fc;
        padding: 0.2rem 0.4rem;
        border-radius: 0.2rem;
        color: #e83e8c;
        word-break: break-word;
        white-space: pre-wrap;
    }
</style>
@endsection