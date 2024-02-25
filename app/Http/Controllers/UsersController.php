<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersFormRequest;
use App\Http\Requests\UsersUpdateFormRequest;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Exception;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with('account')->orderBy('id', 'DESC')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Accounts = Account::pluck('name_arabic','id')->all();
        
        return view('users.create', compact('Accounts'));
    }

    /**
     * Store a new user in the storage.
     *
     * @param App\Http\Requests\UsersFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(UsersFormRequest $request)
    {
        
        $data = $request->getData();
        

       
        User::create([
                "name" => $data['name'],
                "email" => $data['email'],
                "password" => Hash::make($data['password']),
                "birth_date" => $data['birth_date'],
                "account_id" => $data['account_id'],
                "type" => $data['type'],
                "status" => $data['status'],
        ]);

        return redirect()->route('users.user.index')
            ->with('success_message', trans('users.model_was_added'));
    }

    /**
     * Display the specified user.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::with('account')->findOrFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $Accounts = Account::pluck('name_arabic','id')->all();

        return view('users.edit', compact('user','Accounts'));
    }

    /**
     * Update the specified user in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\UsersFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        // $data = $request->getData();

        request()->validate([
            'name' => 'required|string|min:1|max:255',
            'email' => ['required', 'string', 'max:500',
                 Rule::unique('users', 'email')
                ->ignore($id)],
            'password' => 'nullable|string|min:1|max:255',
            'birth_date' => 'nullable|string|min:0',
            'account_id' => 'required|numeric|min:0',
            'type' => 'required',
            'status' => 'required',
        ]);
        
        $user = User::findOrFail($id);
       
        // dd($request->all());

        $user->update([
            "name" => $request->name,
            "email" => $request->email,
           
            "birth_date" =>$request->birth_date,
            "account_id" =>$request->account_id,
            "type" =>$request->type,
            "status" => $request->status,
        ]);
        if($request->password){
            $user->update([
                "password" => Hash::make($request->password),
              ]);
        }

        return redirect()->route('users.user.index')
            ->with('success_message', trans('users.model_was_updated'));  
    }

    /**
     * Remove the specified user from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.user.index')
                ->with('success_message', trans('users.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('users.unexpected_error')]);
        }
    }



}
