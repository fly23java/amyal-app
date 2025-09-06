@extends('layouts.app')

@section('title', 'خطأ في الخادم')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-server"></i>
                        خطأ في الخادم
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="error-code mb-4">
                        <h1 class="display-1 text-danger">500</h1>
                    </div>
                    
                    <h3 class="mb-3">عذراً، حدث خطأ في الخادم</h3>
                    
                    <p class="lead mb-4">
                        نواجه مشكلة تقنية مؤقتة. يرجى المحاولة مرة أخرى بعد قليل.
                    </p>
                    
                    <div class="mb-4">
                        <img src="{{ asset('images/500-illustration.svg') }}" 
                             alt="Server Error" 
                             class="img-fluid"
                             style="max-width: 300px;"
                             onerror="this.style.display='none'">
                    </div>
                    
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i>
                        تم إشعار فريق التطوير بهذه المشكلة وسيتم حلها في أقرب وقت ممكن.
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i>
                            العودة للرئيسية
                        </a>
                        
                        <button onclick="window.location.reload()" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i>
                            إعادة المحاولة
                        </button>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="fas fa-clock text-muted fa-2x mb-2"></i>
                            <h6>وقت الخطأ</h6>
                            <small class="text-muted">{{ now()->format('Y-m-d H:i:s') }}</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-code text-muted fa-2x mb-2"></i>
                            <h6>رمز الخطأ</h6>
                            <small class="text-muted">HTTP 500</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-envelope text-muted fa-2x mb-2"></i>
                            <h6>الدعم التقني</h6>
                            <small class="text-muted">
                                <a href="mailto:support@example.com">support@example.com</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-code h1 {
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
}

.btn {
    padding: 10px 20px;
    margin: 0 5px;
}

.alert {
    border: none;
    border-radius: 10px;
}
</style>
@endsection