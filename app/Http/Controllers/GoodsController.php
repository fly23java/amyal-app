<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsFormRequest;
use App\Models\Account;
use App\Models\Goods;
use App\Models\GoodsType;
use App\Models\Unit;
use Exception;

class GoodsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the goods.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $goodsObjects = Goods::with('goodstype','unit','account')->orderBy('id', 'DESC')->get();

        return view('goods.index', compact('goodsObjects'));
    }

    /**
     * Show the form for creating a new goods.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $GoodsTypes = GoodsType::pluck('name_arabic','id')->all();
$Units = Unit::pluck('name_arabic','id')->all();
$Accounts = Account::pluck('name_arabic','id')->all();
        
        return view('goods.create', compact('GoodsTypes','Units','Accounts'));
    }

    /**
     * Store a new goods in the storage.
     *
     * @param App\Http\Requests\GoodsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(GoodsFormRequest $request)
    {
        
        $data = $request->getData();
        
        Goods::create($data);

        return redirect()->route('goods.goods.index')
            ->with('success_message', trans('goods.model_was_added'));
    }

    /**
     * Display the specified goods.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $goods = Goods::with('goodstype','unit','account')->findOrFail($id);

        return view('goods.show', compact('goods'));
    }

    /**
     * Show the form for editing the specified goods.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $goods = Goods::findOrFail($id);
        $GoodsTypes = GoodsType::pluck('name_arabic','id')->all();
        $Units = Unit::pluck('name_arabic','id')->all();
        $Accounts = Account::pluck('name_arabic','id')->all();

        return view('goods.edit', compact('goods','GoodsTypes','Units','Accounts'));
    }

    /**
     * Update the specified goods in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\GoodsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, GoodsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $goods = Goods::findOrFail($id);
        $goods->update($data);

        return redirect()->route('goods.goods.index')
            ->with('success_message', trans('goods.model_was_updated'));  
    }

    /**
     * Remove the specified goods from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $goods = Goods::findOrFail($id);
            $goods->delete();

            return redirect()->route('goods.goods.index')
                ->with('success_message', trans('goods.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('goods.unexpected_error')]);
        }
    }



}
