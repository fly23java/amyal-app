<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsTypesFormRequest;
use App\Models\GoodsType;
use Exception;

class GoodsTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the goods types.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $goodsTypes = GoodsType::with('parentgoodstype')->orderBy('id', 'DESC')->get();

        return view('goods_types.index', compact('goodsTypes'));
    }

    /**
     * Show the form for creating a new goods type.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $ParentGoodsTypes = GoodsType::pluck('name_arabic','id')->all();
        
        return view('goods_types.create', compact('ParentGoodsTypes'));
    }

    /**
     * Store a new goods type in the storage.
     *
     * @param App\Http\Requests\GoodsTypesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(GoodsTypesFormRequest $request)
    {
        
        $data = $request->getData();
        
        GoodsType::create($data);

        return redirect()->route('goods_types.goods_type.index')
            ->with('success_message', trans('goods_types.model_was_added'));
    }

    /**
     * Display the specified goods type.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $goodsType = GoodsType::with('parentgoodstype')->findOrFail($id);

        return view('goods_types.show', compact('goodsType'));
    }

    /**
     * Show the form for editing the specified goods type.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $goodsType = GoodsType::findOrFail($id);
        $ParentGoodsTypes = GoodsType::pluck('name_arabic','id')->all();

        return view('goods_types.edit', compact('goodsType','ParentGoodsTypes'));
    }

    /**
     * Update the specified goods type in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\GoodsTypesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, GoodsTypesFormRequest $request)
    {
        
        $data = $request->getData();
        
        $goodsType = GoodsType::findOrFail($id);
        $goodsType->update($data);

        return redirect()->route('goods_types.goods_type.index')
            ->with('success_message', trans('goods_types.model_was_updated'));  
    }

    /**
     * Remove the specified goods type from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $goodsType = GoodsType::findOrFail($id);
            $goodsType->delete();

            return redirect()->route('goods_types.goods_type.index')
                ->with('success_message', trans('goods_types.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('goods_types.unexpected_error')]);
        }
    }



}
