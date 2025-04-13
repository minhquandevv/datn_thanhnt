@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="text-center mt-5">
        <div class="error mx-auto" data-text="404">404</div>
        <p class="lead text-gray-800 mb-4">Không tìm thấy trang</p>
        <p class="text-gray-500 mb-0">Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
        <hr>
        <a href="{{ url('/') }}" class="btn btn-primary">&larr; Quay về trang chủ</a>
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
</style>
@endsection