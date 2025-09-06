@extends('layouts.app')

@section('title', 'محافظي الإلكترونية')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">
                                <i class="fas fa-wallet"></i>
                                محافظي الإلكترونية
                            </h2>
                            <p class="mb-0">إدارة شاملة لجميع محافظك الإلكترونية</p>
                        </div>
                        <div>
                            <a href="{{ route('wallets.create') }}" class="btn btn-light">
                                <i class="fas fa-plus"></i>
                                إنشاء محفظة جديدة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallets Overview -->
    <div class="row">
        @forelse($wallets as $wallet)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 wallet-card {{ $wallet->status === 'active' ? 'border-success' : 'border-warning' }}">
                <div class="card-header bg-{{ $wallet->status_color }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-wallet"></i>
                            {{ $wallet->type_display }}
                        </h6>
                        <span class="badge badge-light">{{ $wallet->currency }}</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Wallet Number -->
                    <div class="text-center mb-3">
                        <small class="text-muted">رقم المحفظة</small>
                        <h6 class="font-monospace">{{ $wallet->wallet_number }}</h6>
                    </div>

                    <!-- Balance -->
                    <div class="text-center mb-3">
                        <small class="text-muted">الرصيد المتاح</small>
                        <h4 class="text-{{ $wallet->status_color }} mb-0">
                            {{ number_format($wallet->available_balance, 2) }}
                            <small>{{ $wallet->currency }}</small>
                        </h4>
                        
                        @if($wallet->pending_balance > 0)
                        <small class="text-warning">
                            ({{ number_format($wallet->pending_balance, 2) }} معلق)
                        </small>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="text-center mb-3">
                        <span class="badge badge-{{ $wallet->status_color }} badge-pill">
                            {{ $wallet->status === 'active' ? 'نشطة' : 'معطلة' }}
                        </span>
                        
                        @if($wallet->is_verified)
                        <span class="badge badge-success badge-pill">
                            <i class="fas fa-check-circle"></i>
                            موثقة
                        </span>
                        @else
                        <span class="badge badge-warning badge-pill">
                            <i class="fas fa-exclamation-circle"></i>
                            غير موثقة
                        </span>
                        @endif
                    </div>

                    <!-- Recent Transactions -->
                    @if($wallet->transactions->count() > 0)
                    <div class="mb-3">
                        <small class="text-muted">آخر المعاملات</small>
                        <div class="list-group list-group-flush">
                            @foreach($wallet->transactions->take(3) as $transaction)
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-{{ $transaction->status_color }}">
                                            {{ $transaction->category_display }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }}
                                        {{ number_format($transaction->amount, 2) }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('wallets.show', $wallet) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                            عرض
                        </a>
                        <button class="btn btn-outline-success btn-sm" onclick="showDepositModal({{ $wallet->id }})">
                            <i class="fas fa-plus"></i>
                            إيداع
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="showWithdrawModal({{ $wallet->id }})">
                            <i class="fas fa-minus"></i>
                            سحب
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="showTransferModal({{ $wallet->id }})">
                            <i class="fas fa-exchange-alt"></i>
                            تحويل
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-wallet fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد محافظ إلكترونية</h4>
                    <p class="text-muted mb-4">ابدأ بإنشاء محفظتك الأولى للاستفادة من خدماتنا</p>
                    <a href="{{ route('wallets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إنشاء محفظة جديدة
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Quick Stats -->
    @if($wallets->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar"></i>
                        ملخص المحافظ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $wallets->count() }}</h4>
                                <small class="text-muted">إجمالي المحافظ</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ number_format($wallets->sum('balance'), 2) }}</h4>
                                <small class="text-muted">إجمالي الرصيد (ر.س)</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $wallets->where('is_verified', true)->count() }}</h4>
                                <small class="text-muted">محافظ موثقة</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $wallets->where('status', 'active')->count() }}</h4>
                                <small class="text-muted">محافظ نشطة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modals -->
@include('wallets.modals.deposit')
@include('wallets.modals.withdraw')
@include('wallets.modals.transfer')

@endsection

@section('styles')
<style>
.wallet-card {
    transition: transform 0.2s;
    border-radius: 15px;
    overflow: hidden;
}

.wallet-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.font-monospace {
    font-family: 'Courier New', monospace;
    font-weight: bold;
}

.badge-pill {
    border-radius: 50px;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #eee;
}

.list-group-item:last-child {
    border-bottom: none;
}

.btn-group .btn {
    flex: 1;
}
</style>
@endsection

@section('scripts')
<script>
function showDepositModal(walletId) {
    $('#depositModal').modal('show');
    $('#depositForm').data('wallet-id', walletId);
}

function showWithdrawModal(walletId) {
    $('#withdrawModal').modal('show');
    $('#withdrawForm').data('wallet-id', walletId);
}

function showTransferModal(walletId) {
    $('#transferModal').modal('show');
    $('#transferForm').data('wallet-id', walletId);
}

// Auto-refresh balances every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>
@endsection