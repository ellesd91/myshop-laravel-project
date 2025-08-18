<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use function Laravel\Prompts\alert;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $c= Category::find(1);
        // dd($c->children);
        $categories = Category::latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories=Category::where('parent_id', 0)->get();
        $attributes=Attribute::all();


        return view('admin.categories.create', compact('parentCategories','attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'parent_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_is_filter_ids' => 'required',
            'variation_id' => 'required',



        ]);

        try {
            DB::beginTransaction();
          $category=Category::create([
            'name' => $request->name ,
            'slug' => $request->slug ,
            'parent_id' => $request->parent_id ,
            'is_active' => $request->is_active,
            'icon' => $request->icon,
            'description'=> $request->description,
        ]);

        foreach ($request->attribute_ids as $attributeId) {
            $attribute=Attribute::findOrFail($attributeId);
            $attribute->categories()->attach($category->id , [
                'is_filter' => in_array($attributeId, $request->attribute_is_filter_ids) ? 1 : 0,
                'is_variation' => $request->variation_id == $attributeId ? 1 : 0,
            ]);

       }

       DB::commit();
       return redirect()->route('admin.categories.index')
        ->with('swal-success', 'دسته بندی با موفقیت ایجاد شد.');


        } catch (\Throwable $ex) {
            DB::rollBack();
            return redirect()->back()->with('swal-error', 'مشکل در ایجاد دسته بندی');

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('parent_id', 0)->get();
        $attributes = Attribute::all();

         $categoryFilterableAttributeIds = $category->attributes()->wherePivot('is_filter', 1)->pluck('id')->toArray();
         $categoryVariationAttributeId = $category->attributes()->wherePivot('is_variation', 1)->first()->id ?? null;

    return view('admin.categories.edit', compact(
        'category',
        'parentCategories',
        'attributes',
        'categoryFilterableAttributeIds', // ارسال متغیر جدید به ویو
        'categoryVariationAttributeId'    // ارسال متغیر جدید به ویو
    ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id,
            'parent_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_is_filter_ids' => 'required',
            'variation_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' => $request->parent_id,
                'is_active' => $request->is_active,
                'icon' => $request->icon,
                'description' => $request->description,
            ]);

            $category->attributes()->detach();

            foreach ($request->attribute_ids as $attributeId) {
                $attribute = Attribute::findOrFail($attributeId);
                $attribute->categories()->attach($category->id, [
                    'is_filter' => in_array($attributeId, $request->attribute_is_filter_ids) ? 1 : 0,
                    'is_variation' => $request->variation_id == $attributeId ? 1 : 0,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('swal-success', 'دسته بندی با موفقیت ویرایش شد.');
        } catch (\Throwable $ex) {
            DB::rollBack();
            return redirect()->back()->with('swal-error', 'مشکل در ویرایش دسته بندی');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

   public function getCategoryAttributes(Category $category)
    {
        $attributes = $category->attributes()->wherePivot('is_variation', 0)->get();
        $variation  = $category->attributes()->wherePivot('is_variation', 1)->first();

        // نکته: کلید 'attrubtes' عمداً مثل ویدیو تایپ شده؛ JS همین را می‌خواند
        return ['attrubtes' => $attributes, 'variation' => $variation];
    }





}
