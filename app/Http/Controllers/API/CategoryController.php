<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'category_types' => Category::CATEGORY_TYPES,
            'category' => request()->user()->categories
        ]);
    }

    /**
     * Store a newly created category in db.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|integer|between:0,1',
            'name' => 'required|string|min:3|max:50',
        ]);

        $request->user()
            ->categories()
            ->create($request->only(['type', 'name']));

        return response()->json(['success' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return Category::where('user_id', Auth::id())->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'type' => 'required|integer|between:0,1',
            'name' => 'required|string|min:3|max:50',
        ]);

        $category = Category::where('user_id', Auth::id())->findOrFail($id);
        $category->update($request->only(['type', 'name']));
        return response()->json(['success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = Category::where('user_id', Auth::id())->findOrFail($id);
        $category->delete();
        return response()->json(['success' => true], 200);
    }
}
