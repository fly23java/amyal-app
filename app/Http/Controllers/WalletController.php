<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\User;
use App\Services\WalletService;
use App\Services\WalletPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class WalletController extends Controller
{
    protected $walletService;
    protected $paymentService;

    public function __construct(WalletService $walletService, WalletPaymentService $paymentService)
    {
        $this->middleware('auth');
        $this->walletService = $walletService;
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $wallets = $user->wallets()->with(['transactions' => function($query) {
            $query->latest()->limit(5);
        }])->get();

        return view('wallets.index', compact('wallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $walletTypes = [
            'main' => 'المحفظة الرئيسية',
            'savings' => 'محفظة الادخار',
            'business' => 'محفظة الأعمال',
        ];

        $currencies = [
            'SAR' => 'ريال سعودي',
            'USD' => 'دولار أمريكي',
            'EUR' => 'يورو',
        ];

        return view('wallets.create', compact('walletTypes', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'wallet_type' => 'required|in:main,savings,business',
            'currency' => 'required|in:SAR,USD,EUR',
        ]);

        try {
            $wallet = $this->walletService->createWallet(
                Auth::user(),
                $request->wallet_type,
                $request->currency
            );

            Toastr::success('تم إنشاء المحفظة بنجاح');
            return redirect()->route('wallets.show', $wallet);

        } catch (\Exception $e) {
            Toastr::error('فشل في إنشاء المحفظة: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        $this->authorize('view', $wallet);

        $wallet->load([
            'transactions' => function($query) {
                $query->with(['relatedWallet.user'])->latest()->limit(20);
            },
            'paymentMethods' => function($query) {
                $query->where('is_verified', true);
            },
            'limits' => function($query) {
                $query->active()->currentPeriod();
            }
        ]);

        $balance = $this->walletService->getBalance($wallet);
        $statistics = $this->walletService->getStatistics($wallet);

        return view('wallets.show', compact('wallet', 'balance', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        return view('wallets.edit', compact('wallet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        $request->validate([
            'settings.notifications' => 'boolean',
            'settings.auto_backup' => 'boolean',
            'settings.transaction_alerts' => 'boolean',
        ]);

        try {
            $settings = $wallet->settings ?? [];
            $settings = array_merge($settings, $request->input('settings', []));

            $wallet->update(['settings' => $settings]);

            Toastr::success('تم تحديث إعدادات المحفظة بنجاح');
            return redirect()->route('wallets.show', $wallet);

        } catch (\Exception $e) {
            Toastr::error('فشل في تحديث المحفظة: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        $this->authorize('delete', $wallet);

        try {
            if ($wallet->balance > 0) {
                Toastr::error('لا يمكن حذف محفظة تحتوي على رصيد');
                return back();
            }

            $wallet->delete();

            Toastr::success('تم حذف المحفظة بنجاح');
            return redirect()->route('wallets.index');

        } catch (\Exception $e) {
            Toastr::error('فشل في حذف المحفظة: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * Deposit money to wallet
     */
    public function deposit(Request $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        $request->validate([
            'amount' => 'required|numeric|min:1|max:50000',
            'payment_method' => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $transaction = $this->walletService->deposit(
                $wallet,
                $request->amount,
                $request->payment_method,
                null,
                ['description' => $request->description]
            );

            Toastr::success('تم الإيداع بنجاح');
            return response()->json([
                'success' => true,
                'message' => 'تم الإيداع بنجاح',
                'transaction' => $transaction,
                'new_balance' => $wallet->fresh()->balance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Withdraw money from wallet
     */
    public function withdraw(Request $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $wallet->available_balance,
            'payment_method' => 'required|string',
            'description' => 'nullable|string|max:255',
            'pin' => 'required|string|size:4',
        ]);

        try {
            if (!$wallet->verifyPin($request->pin)) {
                throw new \Exception('رقم PIN غير صحيح');
            }

            $transaction = $this->walletService->withdraw(
                $wallet,
                $request->amount,
                $request->payment_method,
                null,
                ['description' => $request->description]
            );

            Toastr::success('تم السحب بنجاح');
            return response()->json([
                'success' => true,
                'message' => 'تم السحب بنجاح',
                'transaction' => $transaction,
                'new_balance' => $wallet->fresh()->balance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Transfer money between wallets
     */
    public function transfer(Request $request, Wallet $fromWallet)
    {
        $this->authorize('update', $fromWallet);

        $request->validate([
            'to_wallet_number' => 'required|string|exists:wallets,wallet_number',
            'amount' => 'required|numeric|min:1|max:' . $fromWallet->available_balance,
            'description' => 'nullable|string|max:255',
            'pin' => 'required|string|size:4',
        ]);

        try {
            if (!$fromWallet->verifyPin($request->pin)) {
                throw new \Exception('رقم PIN غير صحيح');
            }

            $toWallet = Wallet::where('wallet_number', $request->to_wallet_number)->first();
            
            if ($toWallet->user_id === $fromWallet->user_id) {
                throw new \Exception('لا يمكن التحويل إلى محفظتك الخاصة');
            }

            $transactions = $this->walletService->transfer(
                $fromWallet,
                $toWallet,
                $request->amount,
                $request->description ?? 'تحويل بين المحافظ'
            );

            Toastr::success('تم التحويل بنجاح');
            return response()->json([
                'success' => true,
                'message' => 'تم التحويل بنجاح',
                'transactions' => $transactions,
                'new_balance' => $fromWallet->fresh()->balance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Set wallet PIN
     */
    public function setPin(Request $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        $request->validate([
            'pin' => 'required|string|size:4|regex:/^[0-9]{4}$/',
            'pin_confirmation' => 'required|string|same:pin',
        ]);

        try {
            $wallet->setPin($request->pin);

            Toastr::success('تم تعيين رقم PIN بنجاح');
            return response()->json([
                'success' => true,
                'message' => 'تم تعيين رقم PIN بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في تعيين رقم PIN'
            ], 422);
        }
    }

    /**
     * Freeze wallet
     */
    public function freeze(Request $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            $wallet->freeze($request->reason);

            Toastr::success('تم تجميد المحفظة بنجاح');
            return back();

        } catch (\Exception $e) {
            Toastr::error('فشل في تجميد المحفظة');
            return back();
        }
    }

    /**
     * Unfreeze wallet
     */
    public function unfreeze(Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        try {
            $wallet->unfreeze();

            Toastr::success('تم إلغاء تجميد المحفظة بنجاح');
            return back();

        } catch (\Exception $e) {
            Toastr::error('فشل في إلغاء تجميد المحفظة');
            return back();
        }
    }

    /**
     * Get wallet transactions with filters
     */
    public function transactions(Request $request, Wallet $wallet)
    {
        $this->authorize('view', $wallet);

        $filters = $request->only([
            'type', 'category', 'status', 
            'date_from', 'date_to', 
            'amount_from', 'amount_to'
        ]);

        $transactions = $this->walletService->getTransactions($wallet, $filters, 20);

        if ($request->ajax()) {
            return response()->json([
                'transactions' => $transactions->items(),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'total' => $transactions->total(),
                ]
            ]);
        }

        return view('wallets.transactions', compact('wallet', 'transactions', 'filters'));
    }

    /**
     * Export wallet transactions
     */
    public function exportTransactions(Request $request, Wallet $wallet)
    {
        $this->authorize('view', $wallet);

        $filters = $request->only([
            'type', 'category', 'status', 
            'date_from', 'date_to'
        ]);

        // This would generate and download a CSV/Excel file
        // Implementation depends on your preferred export library

        Toastr::success('سيتم إرسال الملف إلى بريدك الإلكتروني قريباً');
        return back();
    }

    /**
     * Get wallet QR code for payments
     */
    public function qrCode(Wallet $wallet)
    {
        $this->authorize('view', $wallet);

        $qrData = [
            'type' => 'wallet_payment',
            'wallet_number' => $wallet->wallet_number,
            'user_name' => $wallet->user->name,
            'currency' => $wallet->currency,
        ];

        // Generate QR code (you'll need a QR code library)
        $qrCodeData = base64_encode(json_encode($qrData));

        return response()->json([
            'qr_code_data' => $qrCodeData,
            'wallet_number' => $wallet->wallet_number,
        ]);
    }
}