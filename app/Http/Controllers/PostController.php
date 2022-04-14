<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Website;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, $id = NULL)
    {
        $data = $request->json()->all();
        $post = new Post;
        if (key_exists('name', $data))
        {
            $post->name = $data['name'];
        }
        else
        {
            return response(['error' => 'Name not found'], 404);
        }
        if (key_exists('description', $data))
        {
            $post->description = $data['description'];
        }
        else
        {
            return response(['error' => 'Description not found'], 404);
        }
        if (is_null($id))
        {
            return response(['error' => 'Website ID not found'], 404);
        }
        else
        {
            $post->website_id = $id;
        }
        $website = Website::where('id', $id)->first();
        if (is_null($website)){
            return response(['error' => 'Website not found for provided Website ID']);
        }
        $post->save();
        return response()->json([
            'response' => "A post has been successfully created",
        ], 200);
    }
}
