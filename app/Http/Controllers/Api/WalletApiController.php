<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Services\WalletService;
use App\Services\WalletPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class WalletApiController extends Controller
{
    protected $walletService;
    protected $paymentService;

    public function __construct(WalletService $walletService, WalletPaymentService $paymentService)
    {
        $this->middleware('auth:sanctum');
        $this->walletService = $walletService;
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $wallets = $user->wallets()
                ->with(['transactions' => function($query) {
                    $query->latest()->limit(5);
                }])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $wallets,
                'message' => 'تم جلب المحافظ بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب المحافظ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
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

            return response()->json([
                'success' => true,
                'data' => $wallet,
                'message' => 'تم إنشاء المحفظة بنجاح'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء المحفظة',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet): JsonResponse
    {
        try {
            $this->authorize('view', $wallet);

            $wallet->load([
                'transactions' => function($query) {
                    $query->with(['relatedWallet.user'])->latest()->limit(10);
                },
                'paymentMethods',
                'limits'
            ]);

            $balance = $this->walletService->getBalance($wallet);
            $statistics = $this->walletService->getStatistics($wallet);

            return response()->json([
                'success' => true,
                'data' => [
                    'wallet' => $wallet,
                    'balance' => $balance,
                    'statistics' => $statistics,
                ],
                'message' => 'تم جلب بيانات المحفظة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب بيانات المحفظة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deposit money to wallet
     */
    public function deposit(Request $request, Wallet $wallet): JsonResponse
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

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction' => $transaction,
                    'new_balance' => $wallet->fresh()->balance,
                ],
                'message' => 'تم الإيداع بنجاح'
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
    public function withdraw(Request $request, Wallet $wallet): JsonResponse
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

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction' => $transaction,
                    'new_balance' => $wallet->fresh()->balance,
                ],
                'message' => 'تم السحب بنجاح'
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
    public function transfer(Request $request, Wallet $fromWallet): JsonResponse
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

            $transactions = $this->walletService->transfer(
                $fromWallet,
                $toWallet,
                $request->amount,
                $request->description ?? 'تحويل بين المحافظ'
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'transactions' => $transactions,
                    'new_balance' => $fromWallet->fresh()->balance,
                ],
                'message' => 'تم التحويل بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get wallet balance
     */
    public function balance(Wallet $wallet): JsonResponse
    {
        try {
            $this->authorize('view', $wallet);

            $balance = $this->walletService->getBalance($wallet);

            return response()->json([
                'success' => true,
                'data' => $balance,
                'message' => 'تم جلب الرصيد بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب الرصيد',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wallet transactions
     */
    public function transactions(Request $request, Wallet $wallet): JsonResponse
    {
        try {
            $this->authorize('view', $wallet);

            $filters = $request->only([
                'type', 'category', 'status', 
                'date_from', 'date_to', 
                'amount_from', 'amount_to'
            ]);

            $transactions = $this->walletService->getTransactions($wallet, $filters, 20);

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'message' => 'تم جلب المعاملات بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب المعاملات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wallet statistics
     */
    public function statistics(Request $request, Wallet $wallet): JsonResponse
    {
        try {
            $this->authorize('view', $wallet);

            $period = $request->input('period', 'month');
            $statistics = $this->walletService->getStatistics($wallet, $period);

            return response()->json([
                'success' => true,
                'data' => $statistics,
                'message' => 'تم جلب الإحصائيات بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب الإحصائيات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify wallet PIN
     */
    public function verifyPin(Request $request, Wallet $wallet): JsonResponse
    {
        $this->authorize('view', $wallet);

        $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        try {
            $isValid = $wallet->verifyPin($request->pin);

            return response()->json([
                'success' => true,
                'data' => ['is_valid' => $isValid],
                'message' => $isValid ? 'رقم PIN صحيح' : 'رقم PIN غير صحيح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في التحقق من رقم PIN',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search wallets by wallet number
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'wallet_number' => 'required|string|min:3',
        ]);

        try {
            $wallets = Wallet::where('wallet_number', 'like', '%' . $request->wallet_number . '%')
                ->with(['user:id,name,email'])
                ->active()
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $wallets,
                'message' => 'تم البحث بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في البحث',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}