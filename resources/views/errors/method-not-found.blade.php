@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="text-center mt-5">
        <div class="error mx-auto" data-text="500">500</div>
        <p class="lead text-gray-800 mb-4">Method Not Found</p>
        <p class="text-gray-500 mb-0">Có vẻ như chức năng này đang được xây dựng...</p>
        <hr>
        <div class="mb-4">
            <h5 class="text-danger">Chi tiết lỗi:</h5>
            <p>{{ $exception->getMessage() }}</p>
        </div>
        <a href="{{ route('login') }}">&larr; Quay về trang chủ</a>
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