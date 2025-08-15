<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::paginate(10);
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required'
        ]);

        Tag::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.tags.index')->with('swal-success', 'تگ با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {

        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
         $request->validate([
            'name' => 'required'
        ]);

        $tag->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.tags.index')->with('swal-success', 'تگ با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
