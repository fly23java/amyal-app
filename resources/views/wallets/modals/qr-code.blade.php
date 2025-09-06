<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="qrCodeModalLabel">
                    <i class="fas fa-qrcode"></i>
                    QR كود المحفظة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="text-center">
                    <!-- QR Code Container -->
                    <div id="qrCodeContainer" class="mb-4">
                        <div class="qr-placeholder bg-light p-4 rounded">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p class="mt-2 text-muted">جاري إنشاء QR كود...</p>
                        </div>
                    </div>

                    <!-- Wallet Info -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">معلومات المحفظة</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">رقم المحفظة:</small>
                                    <br>
                                    <strong class="font-monospace">{{ $wallet->wallet_number ?? '' }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">اسم المالك:</small>
                                    <br>
                                    <strong>{{ auth()->user()->name }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Instructions -->
                    <div class="alert alert-info mt-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-mobile-alt me-2 mt-1"></i>
                            <div class="text-start">
                                <strong>كيفية الاستخدام:</strong>
                                <ol class="mb-0 mt-2">
                                    <li>اطلب من المرسل مسح QR كود</li>
                                    <li>سيظهر رقم محفظتك تلقائياً</li>
                                    <li>سيتم إدخال المبلغ وإرساله</li>
                                    <li>ستصلك إشعار فوري بالتحويل</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Share Options -->
                    <div class="mt-3">
                        <h6>مشاركة QR كود:</h6>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="shareQR('whatsapp')">
                                <i class="fab fa-whatsapp"></i>
                                واتساب
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="shareQR('telegram')">
                                <i class="fab fa-telegram"></i>
                                تليجرام
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="downloadQR()">
                                <i class="fas fa-download"></i>
                                تحميل
                            </button>
                            <button type="button" class="btn btn-outline-dark btn-sm" onclick="printQR()">
                                <i class="fas fa-print"></i>
                                طباعة
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        إغلاق
                    </button>
                    <button type="button" class="btn btn-primary" onclick="copyWalletNumber()">
                        <i class="fas fa-copy"></i>
                        نسخ رقم المحفظة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateQRCode() {
    const walletId = currentWalletId;
    
    $.ajax({
        url: `/wallets/${walletId}/qr-code`,
        type: 'GET',
        success: function(response) {
            // In a real implementation, you would use a QR code library like qrcode.js
            $('#qrCodeContainer').html(`
                <div class="qr-code-display">
                    <div class="bg-white p-4 rounded border">
                        <div class="qr-grid">
                            <!-- QR Code would be generated here -->
                            <i class="fas fa-qrcode fa-5x text-dark"></i>
                        </div>
                        <p class="mt-3 mb-0 small text-muted">
                            QR Code للمحفظة: ${response.wallet_number}
                        </p>
                    </div>
                </div>
            `);
        },
        error: function() {
            $('#qrCodeContainer').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    فشل في إنشاء QR كود
                </div>
            `);
        }
    });
}

function shareQR(platform) {
    const walletNumber = '{{ $wallet->wallet_number ?? "" }}';
    const userName = '{{ auth()->user()->name }}';
    const text = `مرحباً! يمكنك تحويل الأموال إلى محفظتي الإلكترونية:\n\nرقم المحفظة: ${walletNumber}\nاسم المالك: ${userName}\n\nشكراً لك!`;
    
    let shareUrl = '';
    
    switch(platform) {
        case 'whatsapp':
            shareUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
            break;
        case 'telegram':
            shareUrl = `https://t.me/share/url?text=${encodeURIComponent(text)}`;
            break;
    }
    
    if (shareUrl) {
        window.open(shareUrl, '_blank');
    }
}

function downloadQR() {
    // Implementation for downloading QR code as image
    toastr.info('ميزة التحميل ستكون متاحة قريباً');
}

function printQR() {
    // Implementation for printing QR code
    window.print();
}

function copyWalletNumber() {
    const walletNumber = '{{ $wallet->wallet_number ?? "" }}';
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(walletNumber).then(function() {
            toastr.success('تم نسخ رقم المحفظة');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = walletNumber;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        toastr.success('تم نسخ رقم المحفظة');
    }
}

// PIN input formatting
$('.pin-input').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length > 4) {
        this.value = this.value.slice(0, 4);
    }
});

// PIN strength checking
$('#newPin').on('input', function() {
    const pin = this.value;
    
    if (pin.length === 4) {
        checkPinStrength(pin);
        $('#pinStrength').show();
    } else {
        $('#pinStrength').hide();
    }
    
    validatePinMatch();
});

$('#confirmPin').on('input', function() {
    validatePinMatch();
});

function validatePinMatch() {
    const pin = $('#newPin').val();
    const confirmPin = $('#confirmPin').val();
    
    if (pin.length === 4 && confirmPin.length === 4) {
        if (pin === confirmPin) {
            $('#confirmPin').removeClass('is-invalid').addClass('is-valid');
            $('#setPinSubmitBtn').prop('disabled', false);
        } else {
            $('#confirmPin').removeClass('is-valid').addClass('is-invalid');
            $('#setPinSubmitBtn').prop('disabled', true);
        }
    } else {
        $('#confirmPin').removeClass('is-valid is-invalid');
        $('#setPinSubmitBtn').prop('disabled', true);
    }
}

function checkPinStrength(pin) {
    let strength = 0;
    let strengthText = 'ضعيف جداً';
    let strengthColor = 'danger';
    
    // Check for patterns
    const sequential = ['0123', '1234', '2345', '3456', '4567', '5678', '6789', '9876', '8765', '7654', '6543', '5432', '4321', '3210'];
    const repeated = /^(\d)\1{3}$/.test(pin);
    
    if (!sequential.includes(pin) && !repeated) {
        strength = 50;
        strengthText = 'متوسط';
        strengthColor = 'warning';
        
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
</script>

<style>
.pin-input {
    font-size: 1.5em;
    letter-spacing: 0.5em;
    font-weight: bold;
}

.qr-placeholder {
    min-height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.qr-grid {
    width: 150px;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    background: white;
}

.font-monospace {
    font-family: 'Courier New', monospace;
}

@media print {
    body * {
        visibility: hidden;
    }
    #qrCodeContainer, #qrCodeContainer * {
        visibility: visible;
    }
    #qrCodeContainer {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>