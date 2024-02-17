<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethodsFormRequest;
use App\Models\PaymentMethod;
use Exception;

class PaymentMethodsController extends Controller
{

    /**
     * Display a listing of the payment methods.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('id', 'DESC')->get();

        return view('payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('payment_methods.create');
    }

    /**
     * Store a new payment method in the storage.
     *
     * @param App\Http\Requests\PaymentMethodsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(PaymentMethodsFormRequest $request)
    {
        
        $data = $request->getData();
        
        PaymentMethod::create($data);

        return redirect()->route('payment_methods.payment_method.index')
            ->with('success_message', trans('payment_methods.model_was_added'));
    }

    /**
     * Display the specified payment method.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return view('payment_methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        

        return view('payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\PaymentMethodsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, PaymentMethodsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update($data);

        return redirect()->route('payment_methods.payment_method.index')
            ->with('success_message', trans('payment_methods.model_was_updated'));  
    }

    /**
     * Remove the specified payment method from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($id);
            $paymentMethod->delete();

            return redirect()->route('payment_methods.payment_method.index')
                ->with('success_message', trans('payment_methods.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('payment_methods.unexpected_error')]);
        }
    }



}
