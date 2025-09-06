<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="transferModalLabel">
                    <i class="fas fa-exchange-alt"></i>
                    تحويل بين المحافظ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="transferForm" onsubmit="processTransfer(event)">
                <div class="modal-body">
                    <!-- Available Balance Display -->
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-wallet"></i>
                                الرصيد المتاح للتحويل:
                            </span>
                            <strong class="h5 mb-0">
                                {{ number_format($balance['available_balance'] ?? 0, 2) }} {{ $wallet->currency ?? 'ر.س' }}
                            </strong>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Recipient Wallet -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="toWalletNumber" class="form-label">
                                    <i class="fas fa-user"></i>
                                    رقم محفظة المستلم
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="toWalletNumber" 
                                           name="to_wallet_number" 
                                           placeholder="W000000000000000000"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="searchWallet()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">
                                    أدخل رقم المحفظة المكون من 18 رقم
                                </small>
                            </div>
                            
                            <!-- Recipient Info -->
                            <div id="recipientInfo" class="alert alert-success" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-check me-2"></i>
                                    <div>
                                        <strong>المستلم:</strong> <span id="recipientName"></span>
                                        <br>
                                        <small class="text-muted">المحفظة: <span id="recipientWallet"></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Input -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="transferAmount" class="form-label">
                                    <i class="fas fa-money-bill"></i>
                                    مبلغ التحويل
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="transferAmount" 
                                           name="amount" 
                                           min="1" 
                                           max="{{ $balance['available_balance'] ?? 0 }}" 
                                           step="0.01" 
                                           required>
                                    <span class="input-group-text">ر.س</span>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                            onclick="setTransferAmount(100)">100</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                            onclick="setTransferAmount(500)">500</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                            onclick="setTransferAmount(1000)">1000</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="setTransferAmount({{ $balance['available_balance'] ?? 0 }})">الكل</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PIN Input -->
                    <div class="mb-3">
                        <label for="transferPin" class="form-label">
                            <i class="fas fa-lock"></i>
                            رقم PIN للتأكيد
                        </label>
                        <input type="password" 
                               class="form-control text-center" 
                               id="transferPin" 
                               name="pin" 
                               maxlength="4" 
                               pattern="[0-9]{4}" 
                               placeholder="****"
                               required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="transferDescription" class="form-label">
                            <i class="fas fa-comment"></i>
                            الوصف (اختياري)
                        </label>
                        <textarea class="form-control" 
                                  id="transferDescription" 
                                  name="description" 
                                  rows="2" 
                                  placeholder="أدخل وصف للتحويل..."></textarea>
                    </div>

                    <!-- Fee Calculation Display -->
                    <div id="transferFeeCalculation" class="alert alert-light" style="display: none;">
                        <div class="row">
                            <div class="col-4">
                                <strong>المبلغ:</strong> <span id="transferDisplayAmount">0.00</span> ر.س
                            </div>
                            <div class="col-4">
                                <strong>الرسوم:</strong> <span id="transferDisplayFee">0.00</span> ر.س
                            </div>
                            <div class="col-4">
                                <strong>المبلغ المُحول:</strong> <span id="transferDisplayNet" class="text-success">0.00</span> ر.س
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Limits Warning -->
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>حدود التحويل:</strong>
                                <ul class="mb-0 mt-1">
                                    <li>الحد الأقصى للتحويل الواحد: 10,000 ر.س</li>
                                    <li>الحد الأقصى اليومي: 25,000 ر.س</li>
                                    <li>الحد الأقصى الشهري: 100,000 ر.س</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-info" id="transferSubmitBtn" disabled>
                        <i class="fas fa-paper-plane"></i>
                        تأكيد التحويل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function processTransfer(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const walletId = $('#transferForm').data('wallet-id') || currentWalletId;
    
    // Double confirmation for transfers
    const amount = $('#transferAmount').val();
    const recipient = $('#recipientName').text();
    
    if (!confirm(`هل أنت متأكد من تحويل ${amount} ر.س إلى ${recipient}؟`)) {
        return;
    }
    
    $.ajax({
        url: `/wallets/${walletId}/transfer`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#transferModal').modal('hide');
                toastr.success(response.message);
                location.reload();
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            toastr.error(response.message || 'حدث خطأ في العملية');
        }
    });
}

function searchWallet() {
    const walletNumber = $('#toWalletNumber').val();
    
    if (walletNumber.length < 10) {
        toastr.warning('أدخل رقم محفظة صحيح');
        return;
    }
    
    $.ajax({
        url: `/api/wallets/search`,
        type: 'GET',
        data: { wallet_number: walletNumber },
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('api_token')
        },
        success: function(response) {
            if (response.success && response.data.length > 0) {
                const wallet = response.data[0];
                $('#recipientName').text(wallet.user.name);
                $('#recipientWallet').text(wallet.wallet_number);
                $('#recipientInfo').show();
                $('#transferSubmitBtn').prop('disabled', false);
            } else {
                $('#recipientInfo').hide();
                $('#transferSubmitBtn').prop('disabled', true);
                toastr.error('لم يتم العثور على المحفظة');
            }
        },
        error: function() {
            toastr.error('خطأ في البحث عن المحفظة');
        }
    });
}

function setTransferAmount(amount) {
    $('#transferAmount').val(amount).trigger('change');
}

// Calculate transfer fees
$('#transferAmount').on('change', function() {
    calculateTransferFee();
});

function calculateTransferFee() {
    const amount = parseFloat($('#transferAmount').val()) || 0;
    
    if (amount > 0) {
        // Transfer fee: 0.5% with min 2 SAR, max 25 SAR
        const feeRate = 0.005;
        const minFee = 2;
        const maxFee = 25;
        
        let fee = amount * feeRate;
        fee = Math.max(minFee, Math.min(maxFee, fee));
        
        const netAmount = amount - fee;
        
        $('#transferDisplayAmount').text(amount.toFixed(2));
        $('#transferDisplayFee').text(fee.toFixed(2));
        $('#transferDisplayNet').text(netAmount.toFixed(2));
        $('#transferFeeCalculation').show();
    } else {
        $('#transferFeeCalculation').hide();
    }
}

// Format PIN input
$('#transferPin').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length > 4) {
        this.value = this.value.slice(0, 4);
    }
});

// Reset form when modal is hidden
$('#transferModal').on('hidden.bs.modal', function() {
    $('#recipientInfo').hide();
    $('#transferSubmitBtn').prop('disabled', true);
    $('#transferFeeCalculation').hide();
    $('#transferForm')[0].reset();
});
</script>