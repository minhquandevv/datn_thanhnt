@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="text-center mt-5">
        <div class="error mx-auto" data-text="500">500</div>
        <p class="lead text-gray-800 mb-4">Internal Server Error</p>
        <p class="text-gray-500 mb-0">Đã xảy ra lỗi trong quá trình xử lý yêu cầu của bạn.</p>
        <hr>
        @if(config('app.debug'))
        <div class="error-details mb-4">
            <h5 class="text-danger">Chi tiết lỗi:</h5>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Error Message</h6>
                </div>
                <div class="card-body text-left">
                    <code>{{ $exception->getMessage() }}</code>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Error Location</h6>
                </div>
                <div class="card-body text-left">
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 20%">File</th>
                            <td>{{ $exception->getFile() }}</td>
                        </tr>
                        <tr>
                            <th>Line</th>
                            <td>{{ $exception->getLine() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif
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
    
    .error-details {
        max-width: 800px;
        margin: 0 auto;
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