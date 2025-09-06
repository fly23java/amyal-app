@extends('layouts.app')

@section('title', 'الصفحة غير موجودة')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        الصفحة غير موجودة
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="error-code mb-4">
                        <h1 class="display-1 text-warning">404</h1>
                    </div>
                    
                    <h3 class="mb-3">عذراً، الصفحة التي تبحث عنها غير موجودة</h3>
                    
                    <p class="lead mb-4">
                        قد تكون الصفحة قد تم نقلها أو حذفها أو أن الرابط غير صحيح.
                    </p>
                    
                    <div class="mb-4">
                        <img src="{{ asset('images/404-illustration.svg') }}" 
                             alt="Page Not Found" 
                             class="img-fluid"
                             style="max-width: 300px;"
                             onerror="this.style.display='none'">
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i>
                            العودة للرئيسية
                        </a>
                        
                        <button onclick="history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            العودة للخلف
                        </button>
                    </div>
                    
                    <hr class="my-4">
                    
                    <p class="text-muted">
                        إذا كنت تعتقد أن هذا خطأ، يرجى 
                        <a href="mailto:support@example.com">التواصل مع الدعم التقني</a>
                    </p>
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
</style>
@endsection