<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        $data = Post::all();
        return Inertia::render('Posts', ['data' => $data]);
    }

    /*
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required'],
            'body' => ['required'],
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ])->validate();

        $image_path = '';
        
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }

        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $image_path
        ]);

        return redirect()->back()->with('message', 'Post Created Successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required'],
            'body' => ['required'],
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ])->validate();

        if ($request->has('id')) {

            $updateData = [
                'title' => $request->title,
                'body' => $request->body,
            ];
            
            if ($request->hasFile('image')) {
                $updateData['image'] = $request->file('image')->store('image', 'public');
            }

            Post::find($request->input('id'))->update($updateData);
            return redirect()->back()->with('message', 'Post Updated Successfully.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            Post::find($request->input('id'))->delete();
            return redirect()->back();
        }
    }
}
