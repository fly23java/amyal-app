<!-- Set PIN Modal -->
<div class="modal fade" id="setPinModal" tabindex="-1" aria-labelledby="setPinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="setPinModalLabel">
                    <i class="fas fa-lock"></i>
                    تعيين رقم PIN
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="setPinForm" onsubmit="setWalletPin(event)">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>حماية محفظتك برقم PIN</h5>
                        <p class="text-muted">
                            رقم PIN يحمي محفظتك من الوصول غير المصرح به
                        </p>
                    </div>

                    <!-- PIN Input -->
                    <div class="mb-3">
                        <label for="newPin" class="form-label">
                            <i class="fas fa-key"></i>
                            رقم PIN الجديد
                        </label>
                        <input type="password" 
                               class="form-control text-center pin-input" 
                               id="newPin" 
                               name="pin" 
                               maxlength="4" 
                               pattern="[0-9]{4}" 
                               placeholder="****"
                               required>
                        <small class="form-text text-muted">
                            أدخل 4 أرقام فقط
                        </small>
                    </div>

                    <!-- PIN Confirmation -->
                    <div class="mb-3">
                        <label for="confirmPin" class="form-label">
                            <i class="fas fa-key"></i>
                            تأكيد رقم PIN
                        </label>
                        <input type="password" 
                               class="form-control text-center pin-input" 
                               id="confirmPin" 
                               name="pin_confirmation" 
                               maxlength="4" 
                               pattern="[0-9]{4}" 
                               placeholder="****"
                               required>
                    </div>

                    <!-- PIN Strength Indicator -->
                    <div id="pinStrength" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">قوة رقم PIN:</small>
                            <small id="pinStrengthText" class="text-muted">ضعيف</small>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div id="pinStrengthBar" class="progress-bar" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- PIN Guidelines -->
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-lightbulb me-2 mt-1"></i>
                            <div>
                                <strong>نصائح لرقم PIN آمن:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>تجنب الأرقام المتسلسلة (1234, 4321)</li>
                                    <li>تجنب تكرار الأرقام (1111, 2222)</li>
                                    <li>تجنب تاريخ ميلادك أو أرقام شخصية</li>
                                    <li>استخدم أرقام عشوائية</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>تنبيه هام:</strong>
                                <p class="mb-0 mt-1">
                                    لا تشارك رقم PIN مع أي شخص آخر. سيتم طلب هذا الرقم لتأكيد المعاملات الحساسة.
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
                    <button type="submit" class="btn btn-primary" id="setPinSubmitBtn" disabled>
                        <i class="fas fa-check"></i>
                        تعيين رقم PIN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setWalletPin(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const walletId = currentWalletId;
    
    $.ajax({
        url: `/wallets/${walletId}/set-pin`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#setPinModal').modal('hide');
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

// PIN input formatting and validation
$('.pin-input').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length > 4) {
        this.value = this.value.slice(0, 4);
    }
    
    validatePinForm();
    if (this.id === 'newPin') {
        checkPinStrength(this.value);
    }
});

function validatePinForm() {
    const pin = $('#newPin').val();
    const confirmPin = $('#confirmPin').val();
    const isValid = pin.length === 4 && confirmPin.length === 4 && pin === confirmPin;
    
    $('#setPinSubmitBtn').prop('disabled', !isValid);
    
    if (confirmPin.length === 4 && pin !== confirmPin) {
        $('#confirmPin').addClass('is-invalid');
        toastr.error('أرقام PIN غير متطابقة');
    } else {
        $('#confirmPin').removeClass('is-invalid');
    }
}

function checkPinStrength(pin) {
    if (pin.length < 4) {
        $('#pinStrength').hide();
        return;
    }
    
    $('#pinStrength').show();
    
    let strength = 0;
    let strengthText = 'ضعيف جداً';
    let strengthColor = 'danger';
    
    // Check for sequential numbers
    const sequential = ['0123', '1234', '2345', '3456', '4567', '5678', '6789', '9876', '8765', '7654', '6543', '5432', '4321', '3210'];
    const repeated = /^(\d)\1{3}$/.test(pin);
    
    if (!sequential.includes(pin) && !repeated) {
        strength = 50;
        strengthText = 'متوسط';
        strengthColor = 'warning';
        
        // Check for more randomness
        const digits = pin.split('');
        const uniqueDigits = [...new Set(digits)];
        
        if (uniqueDigits.length >= 3) {
            strength = 75;
            strengthText = 'جيد';
            strengthColor = 'info';
        }
        
        if (uniqueDigits.length === 4) {
            strength = 100;
            strengthText = 'ممتاز';
            strengthColor = 'success';
        }
    }
    
    $('#pinStrengthBar').removeClass('bg-danger bg-warning bg-info bg-success')
                       .addClass('bg-' + strengthColor)
                       .css('width', strength + '%');
    $('#pinStrengthText').text(strengthText)
                        .removeClass('text-danger text-warning text-info text-success')
                        .addClass('text-' + strengthColor);
}

// Reset form when modal is hidden
$('#setPinModal').on('hidden.bs.modal', function() {
    $('#setPinForm')[0].reset();
    $('#pinStrength').hide();
    $('#setPinSubmitBtn').prop('disabled', true);
    $('.pin-input').removeClass('is-invalid');
});
</script>