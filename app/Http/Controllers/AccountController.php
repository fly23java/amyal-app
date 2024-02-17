<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\AccountUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Session;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {


        $accounts = Account::orderBy('id', 'DESC')->get();

        // dd($accountss);
        return view('accounts.index')->with(['accounts'=> $accounts]);

     
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request)
    {
     

        // dd($request->all());
        $validated = $request->validated();
     
        // Account::create($request->all());

        $account = Account::create([
            'name_arabic' => $request->name_arabic,
            'type' => $request->account_type,
            
        ]);
    
        // Create admin user for the account
        $account->users()->create([
            'name' => $request->name_arabic,
            'email' =>  $request->email,
            // 'account_id' => $account->id,
            'password' =>  Hash::make($request->password),
            'type' =>  'admin',
            'status' =>  $request->status,
           
        ]);
       
    return  redirect()->route('accounts.create')
                        ->with('success','account created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\accounts  $accounts
     * @return \Illuminate\Http\Response
     */
    public function show(Account $accounts)
    {
        return view('accounts.show',compact('accounts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\accounts  $accounts
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {

        Session::put('editableUser', $account->getAdminUser());

        return view('accounts.edit',compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\accounts  $accounts
     * @return \Illuminate\Http\Response
     */
    public function update(AccountUpdateRequest $request, account $account)
    {

    //    dd($accounts);
        $validated = $request->validated();
       
        $account = Account::update([
            'name_arabic' => $accounts->name_arabic,
            'type' => $accounts->account_type,
            
        ]);
    
        // Create admin user for the account
        $account->users()->update([
            'name' => $accounts->name_arabic,
            'email' =>  $accounts->email,
            // 'account_id' => $account->id,
            'password' =>  Hash::make($accounts->password),
            'type' =>  'admin',
            'status' =>  $accounts->status,
           
        ]);
      
        
      
        return  redirect()->route('accounts.index')
                        ->with('success','accounts updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\accounts  $accounts
     * @return \Illuminate\Http\Response
     */
    public function destroy(accounts $accounts)
    {
        $accounts->delete();
       
        return redirect()->route('accounts.index')
                        ->with('success','accounts deleted successfully');
    }
}
