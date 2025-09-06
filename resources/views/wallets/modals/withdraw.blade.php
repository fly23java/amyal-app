<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="withdrawModalLabel">
                    <i class="fas fa-minus-circle"></i>
                    سحب من المحفظة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="withdrawForm" onsubmit="processWithdraw(event)">
                <div class="modal-body">
                    <!-- Available Balance Display -->
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-wallet"></i>
                                الرصيد المتاح للسحب:
                            </span>
                            <strong class="h5 mb-0">
                                {{ number_format($balance['available_balance'] ?? 0, 2) }} {{ $wallet->currency ?? 'ر.س' }}
                            </strong>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Amount Input -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="withdrawAmount" class="form-label">
                                    <i class="fas fa-money-bill"></i>
                                    مبلغ السحب
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="withdrawAmount" 
                                           name="amount" 
                                           min="1" 
                                           max="{{ $balance['available_balance'] ?? 0 }}" 
                                           step="0.01" 
                                           required>
                                    <span class="input-group-text">ر.س</span>
                                </div>
                                <small class="form-text text-muted">
                                    الحد الأدنى: 1 ر.س | الحد الأقصى: {{ number_format($balance['available_balance'] ?? 0, 2) }} ر.س
                                </small>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="withdrawPaymentMethod" class="form-label">
                                    <i class="fas fa-university"></i>
                                    طريقة الاستلام
                                </label>
                                <select class="form-select" id="withdrawPaymentMethod" name="payment_method" required>
                                    <option value="">اختر طريقة الاستلام</option>
                                    <option value="bank_transfer">تحويل بنكي</option>
                                    <option value="stc_pay">STC Pay</option>
                                    <option value="cash">نقد</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- PIN Input -->
                    <div class="mb-3">
                        <label for="withdrawPin" class="form-label">
                            <i class="fas fa-lock"></i>
                            رقم PIN للتأكيد
                        </label>
                        <input type="password" 
                               class="form-control text-center" 
                               id="withdrawPin" 
                               name="pin" 
                               maxlength="4" 
                               pattern="[0-9]{4}" 
                               placeholder="****"
                               required>
                        <small class="form-text text-muted">
                            أدخل رقم PIN المكون من 4 أرقام
                        </small>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="withdrawDescription" class="form-label">
                            <i class="fas fa-comment"></i>
                            الوصف (اختياري)
                        </label>
                        <textarea class="form-control" 
                                  id="withdrawDescription" 
                                  name="description" 
                                  rows="2" 
                                  placeholder="أدخل وصف للمعاملة..."></textarea>
                    </div>

                    <!-- Fee Information -->
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>رسوم السحب:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>التحويل البنكي: 1% (حد أدنى 5 ر.س، حد أقصى 50 ر.س)</li>
                                    <li>STC Pay: 2% (حد أدنى 3 ر.س، حد أقصى 25 ر.س)</li>
                                    <li>النقد: 3% (حد أدنى 10 ر.س، حد أقصى 100 ر.س)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Calculation Display -->
                    <div id="withdrawFeeCalculation" class="alert alert-light" style="display: none;">
                        <div class="row">
                            <div class="col-4">
                                <strong>المبلغ:</strong> <span id="withdrawDisplayAmount">0.00</span> ر.س
                            </div>
                            <div class="col-4">
                                <strong>الرسوم:</strong> <span id="withdrawDisplayFee">0.00</span> ر.س
                            </div>
                            <div class="col-4">
                                <strong>المبلغ الصافي:</strong> <span id="withdrawDisplayNet" class="text-success">0.00</span> ر.س
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt me-2"></i>
                            <div>
                                <strong>تنبيه أمني:</strong>
                                <p class="mb-0 mt-1">
                                    تأكد من أن طريقة الاستلام تخصك شخصياً. لن نكون مسؤولين عن أي عمليات احتيال.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-check"></i>
                        تأكيد السحب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function processWithdraw(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const walletId = $('#withdrawForm').data('wallet-id') || currentWalletId;
    
    // Confirm withdrawal
    if (!confirm('هل أنت متأكد من عملية السحب؟')) {
        return;
    }
    
    $.ajax({
        url: `/wallets/${walletId}/withdraw`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#withdrawModal').modal('hide');
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

// Calculate withdrawal fees
$('#withdrawAmount, #withdrawPaymentMethod').on('change', function() {
    calculateWithdrawFee();
});

function calculateWithdrawFee() {
    const amount = parseFloat($('#withdrawAmount').val()) || 0;
    const paymentMethod = $('#withdrawPaymentMethod').val();
    
    if (amount > 0 && paymentMethod) {
        const feeRates = {
            'bank_transfer': 0.01,
            'stc_pay': 0.02,
            'cash': 0.03
        };
        
        const minFees = {
            'bank_transfer': 5,
            'stc_pay': 3,
            'cash': 10
        };
        
        const maxFees = {
            'bank_transfer': 50,
            'stc_pay': 25,
            'cash': 100
        };
        
        const feeRate = feeRates[paymentMethod] || 0;
        const minFee = minFees[paymentMethod] || 0;
        const maxFee = maxFees[paymentMethod] || 0;
        
        let fee = amount * feeRate;
        fee = Math.max(minFee, Math.min(maxFee, fee));
        
        const netAmount = amount - fee;
        
        $('#withdrawDisplayAmount').text(amount.toFixed(2));
        $('#withdrawDisplayFee').text(fee.toFixed(2));
        $('#withdrawDisplayNet').text(netAmount.toFixed(2));
        $('#withdrawFeeCalculation').show();
    } else {
        $('#withdrawFeeCalculation').hide();
    }
}

// Format PIN input
$('#withdrawPin').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length > 4) {
        this.value = this.value.slice(0, 4);
    }
});
</script>