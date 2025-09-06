<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="depositModalLabel">
                    <i class="fas fa-plus-circle"></i>
                    إيداع في المحفظة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="depositForm" onsubmit="processDeposit(event)">
                <div class="modal-body">
                    <div class="row">
                        <!-- Amount Input -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="depositAmount" class="form-label">
                                    <i class="fas fa-money-bill"></i>
                                    مبلغ الإيداع
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="depositAmount" 
                                           name="amount" 
                                           min="1" 
                                           max="50000" 
                                           step="0.01" 
                                           required>
                                    <span class="input-group-text">ر.س</span>
                                </div>
                                <small class="form-text text-muted">
                                    الحد الأدنى: 1 ر.س | الحد الأقصى: 50,000 ر.س
                                </small>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="depositPaymentMethod" class="form-label">
                                    <i class="fas fa-credit-card"></i>
                                    طريقة الدفع
                                </label>
                                <select class="form-select" id="depositPaymentMethod" name="payment_method" required>
                                    <option value="">اختر طريقة الدفع</option>
                                    <option value="mada">مدى</option>
                                    <option value="visa">فيزا</option>
                                    <option value="mastercard">ماستركارد</option>
                                    <option value="stc_pay">STC Pay</option>
                                    <option value="bank_transfer">تحويل بنكي</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="depositDescription" class="form-label">
                            <i class="fas fa-comment"></i>
                            الوصف (اختياري)
                        </label>
                        <textarea class="form-control" 
                                  id="depositDescription" 
                                  name="description" 
                                  rows="2" 
                                  placeholder="أدخل وصف للمعاملة..."></textarea>
                    </div>

                    <!-- Fee Information -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>معلومات الرسوم:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>مدى: بدون رسوم</li>
                                    <li>فيزا/ماستركارد: 2.5% (حد أدنى 5 ر.س)</li>
                                    <li>STC Pay: 2% (حد أدنى 3 ر.س)</li>
                                    <li>التحويل البنكي: 1% (حد أدنى 2 ر.س)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Calculation Display -->
                    <div id="feeCalculation" class="alert alert-light" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <strong>المبلغ:</strong> <span id="displayAmount">0.00</span> ر.س
                            </div>
                            <div class="col-6">
                                <strong>الرسوم:</strong> <span id="displayFee">0.00</span> ر.س
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <strong>إجمالي المبلغ المطلوب:</strong> 
                            <span class="text-primary h5" id="displayTotal">0.00</span> ر.س
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i>
                        تأكيد الإيداع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function processDeposit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const walletId = $('#depositForm').data('wallet-id') || currentWalletId;
    
    $.ajax({
        url: `/wallets/${walletId}/deposit`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#depositModal').modal('hide');
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

// Calculate fees on amount/payment method change
$('#depositAmount, #depositPaymentMethod').on('change', function() {
    calculateDepositFee();
});

function calculateDepositFee() {
    const amount = parseFloat($('#depositAmount').val()) || 0;
    const paymentMethod = $('#depositPaymentMethod').val();
    
    if (amount > 0 && paymentMethod) {
        const feeRates = {
            'mada': 0,
            'visa': 0.025,
            'mastercard': 0.025,
            'stc_pay': 0.02,
            'bank_transfer': 0.01
        };
        
        const minFees = {
            'mada': 0,
            'visa': 5,
            'mastercard': 5,
            'stc_pay': 3,
            'bank_transfer': 2
        };
        
        const feeRate = feeRates[paymentMethod] || 0;
        const minFee = minFees[paymentMethod] || 0;
        const fee = Math.max(minFee, amount * feeRate);
        const total = amount + fee;
        
        $('#displayAmount').text(amount.toFixed(2));
        $('#displayFee').text(fee.toFixed(2));
        $('#displayTotal').text(total.toFixed(2));
        $('#feeCalculation').show();
    } else {
        $('#feeCalculation').hide();
    }
}
</script>