@extends('layouts.app')

@section('title', 'تفاصيل المحفظة - ' . $wallet->wallet_number)

@section('content')
<div class="container-fluid">
    <!-- Wallet Header -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="wallet-icon me-3">
                                    <i class="fas fa-wallet fa-3x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1">{{ $wallet->type_display }}</h3>
                                    <p class="mb-1 opacity-75">{{ $wallet->wallet_number }}</p>
                                    <small class="opacity-75">
                                        آخر معاملة: {{ $wallet->last_transaction_at?->diffForHumans() ?? 'لا توجد معاملات' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="mb-1">{{ number_format($balance['balance'], 2) }}</h4>
                                    <small class="opacity-75">الرصيد الحالي</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-1">{{ number_format($balance['available_balance'], 2) }}</h4>
                                    <small class="opacity-75">الرصيد المتاح</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-1">{{ $wallet->currency }}</h4>
                                    <small class="opacity-75">العملة</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-6 mb-2">
                            <button class="btn btn-success w-100" onclick="showDepositModal()">
                                <i class="fas fa-plus-circle"></i>
                                <br>إيداع
                            </button>
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <button class="btn btn-warning w-100" onclick="showWithdrawModal()">
                                <i class="fas fa-minus-circle"></i>
                                <br>سحب
                            </button>
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <button class="btn btn-info w-100" onclick="showTransferModal()">
                                <i class="fas fa-exchange-alt"></i>
                                <br>تحويل
                            </button>
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <button class="btn btn-secondary w-100" onclick="showQRModal()">
                                <i class="fas fa-qrcode"></i>
                                <br>QR كود
                            </button>
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <a href="{{ route('wallets.transactions', $wallet) }}" class="btn btn-dark w-100">
                                <i class="fas fa-history"></i>
                                <br>المعاملات
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <a href="{{ route('wallets.edit', $wallet) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-cog"></i>
                                <br>الإعدادات
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics and Limits -->
    <div class="row">
        <!-- Statistics -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line"></i>
                        إحصائيات المحفظة (هذا الشهر)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card text-center p-3 bg-light rounded">
                                <h4 class="text-success">{{ number_format($statistics['total_deposits'], 2) }}</h4>
                                <small class="text-muted">إجمالي الإيداعات</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center p-3 bg-light rounded">
                                <h4 class="text-danger">{{ number_format($statistics['total_withdrawals'], 2) }}</h4>
                                <small class="text-muted">إجمالي السحوبات</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center p-3 bg-light rounded">
                                <h4 class="text-info">{{ $statistics['total_transactions'] }}</h4>
                                <small class="text-muted">عدد المعاملات</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center p-3 bg-light rounded">
                                <h4 class="text-primary">{{ number_format($statistics['net_flow'], 2) }}</h4>
                                <small class="text-muted">صافي التدفق</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        آخر المعاملات
                    </h5>
                    <a href="{{ route('wallets.transactions', $wallet) }}" class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    @if($wallet->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>النوع</th>
                                    <th>الوصف</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wallet->transactions as $transaction)
                                <tr>
                                    <td>
                                        <small>{{ $transaction->created_at->format('d/m/Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->type === 'credit' ? 'success' : 'warning' }}">
                                            {{ $transaction->type_display }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $transaction->category_display }}</small>
                                    </td>
                                    <td>{{ $transaction->description ?? '-' }}</td>
                                    <td class="text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }}
                                        {{ $transaction->formatted_amount }}
                                        @if($transaction->fee > 0)
                                        <br>
                                        <small class="text-muted">رسوم: {{ number_format($transaction->fee, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->status_color }}">
                                            {{ $transaction->status_display }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد معاملات</h5>
                        <p class="text-muted">ابدأ أول معاملة في محفظتك</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Wallet Limits and Info -->
        <div class="col-md-4">
            <!-- Wallet Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        حالة المحفظة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>الحالة:</span>
                        <span class="badge badge-{{ $wallet->status_color }}">
                            {{ $wallet->status === 'active' ? 'نشطة' : 'معطلة' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>التوثيق:</span>
                        <span class="badge badge-{{ $wallet->is_verified ? 'success' : 'warning' }}">
                            {{ $wallet->is_verified ? 'موثقة' : 'غير موثقة' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>رقم PIN:</span>
                        <span class="badge badge-{{ $wallet->hasPinSet() ? 'success' : 'danger' }}">
                            {{ $wallet->hasPinSet() ? 'مُعيّن' : 'غير مُعيّن' }}
                        </span>
                    </div>
                    
                    @if(!$wallet->hasPinSet())
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm w-100" onclick="showSetPinModal()">
                            <i class="fas fa-lock"></i>
                            تعيين رقم PIN
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Wallet Limits -->
            @if($wallet->limits->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt"></i>
                        حدود المحفظة
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($wallet->limits as $limit)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">
                                {{ $limit->operation_display }} ({{ $limit->type_display }})
                            </small>
                            <small class="text-{{ $limit->status_color }}">
                                {{ number_format($limit->usage_percentage, 1) }}%
                            </small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-{{ $limit->status_color }}" 
                                 style="width: {{ $limit->usage_percentage }}%">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">
                                مُستخدم: {{ $limit->formatted_used_amount }}
                            </small>
                            <small class="text-muted">
                                الحد الأقصى: {{ $limit->formatted_limit_amount }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals -->
@include('wallets.modals.deposit')
@include('wallets.modals.withdraw')
@include('wallets.modals.transfer')
@include('wallets.modals.set-pin')
@include('wallets.modals.qr-code')

@endsection

@section('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.wallet-icon {
    opacity: 0.8;
}

.stat-card {
    border: 1px solid #e9ecef;
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.opacity-75 {
    opacity: 0.75;
}

.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.btn {
    border-radius: 10px;
}
</style>
@endsection

@section('scripts')
<script>
let currentWalletId = {{ $wallet->id }};

function showDepositModal() {
    $('#depositModal').modal('show');
}

function showWithdrawModal() {
    $('#withdrawModal').modal('show');
}

function showTransferModal() {
    $('#transferModal').modal('show');
}

function showSetPinModal() {
    $('#setPinModal').modal('show');
}

function showQRModal() {
    $('#qrCodeModal').modal('show');
    generateQRCode();
}

function generateQRCode() {
    $.ajax({
        url: `/wallets/${currentWalletId}/qr-code`,
        type: 'GET',
        success: function(response) {
            // Display QR code (you'll need a QR code library)
            $('#qrCodeContainer').html(`
                <div class="text-center">
                    <div class="qr-placeholder bg-light p-4 rounded">
                        <i class="fas fa-qrcode fa-5x text-muted"></i>
                        <p class="mt-2">QR Code: ${response.wallet_number}</p>
                    </div>
                </div>
            `);
        }
    });
}

// Real-time balance updates
function updateBalance() {
    $.ajax({
        url: `/api/wallets/${currentWalletId}/balance`,
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('api_token')
        },
        success: function(response) {
            if (response.success) {
                location.reload(); // Simple refresh for now
            }
        }
    });
}

// Update balance every 60 seconds
setInterval(updateBalance, 60000);
</script>
@endsection