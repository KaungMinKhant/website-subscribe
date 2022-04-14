<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->json()->all();
        $website = new Website;
        if (key_exists('name', $data))
        {
            $website->name = $data['name'];
        }
        else
        {
            return response(['error' => 'Name not found'], 404);
        }
        $website->save();
        return response()->json([
            'response' => "A website has been successfully created",
        ]);
    }
}
