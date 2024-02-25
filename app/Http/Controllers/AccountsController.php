<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Exception;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the accounts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $accounts = Account::orderBy('id', 'DESC')->get();

        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new account.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('accounts.create');
    }

    /**
     * Store a new account in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $this->getData($request);
        
        Account::create($data);

        return redirect()->route('accounts.account.index')
            ->with('success_message', 'Account was successfully added.');
    }

    /**
     * Display the specified account.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $account = Account::findOrFail($id);

        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified account.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        

        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified account in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        // dd($request->all());
        $data = $this->getData($request);
        
        $account = Account::findOrFail($id);
       
        $account->update([
            "name_arabic" => $data['name_arabic'],
            "name_english" => $data['name_english'],
            "cr_number" => $data['cr_number'],
            "bank" => $data['bank'],
            "iban" => $data['iban'],
            "account_number" => $data['account_number'],
            "tax_number" => $data['tax_number'],
            "tax_value" => $data['tax_value'],
            "type" =>  $data['type'],
        ]);

        return redirect()->route('accounts.account.index')
            ->with('success_message', 'Account was successfully updated.');  
    }

    /**
     * Remove the specified account from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $account = Account::findOrFail($id);
            $account->delete();

            return redirect()->route('accounts.account.index')
                ->with('success_message', 'Account was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
                'name_arabic' => 'required|string|min:1|max:255',
            'name_english' => 'nullable|string|min:0|max:255',
            
            'bank' => 'nullable|string|min:0|max:255',
            'cr_number' => 'nullable|string|min:0|max:255',
            'iban' => 'nullable|string|min:0|max:255',
            'account_number' => 'nullable|string|min:0|max:255',
           
            'tax_number' => 'nullable|numeric|string|min:0|max:255',
            'tax_value' => 'nullable|string|min:0|max:255',
            'type' => 'required', 
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}
