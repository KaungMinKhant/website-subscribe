<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\WebUser;
use App\Models\Website;
use Illuminate\Http\Request;

class WebUserController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->json()->all();
        $web_user = new WebUser;
        if (key_exists('name', $data))
        {
            $web_user->name = $data['name'];
        }
        else
        {
            return response(['error' => 'Name not found'], 404);
        }
        if (key_exists('email', $data))
        {
            $web_user->email = $data['email'];
        }
        else
        {
            return response(['error' => 'Email not found'], 404);
        }
        $web_user->save();
        return response()->json([
            'response' => "A user has been successfully created",
        ], 200);
    }


    public function subscribe(Request $request, $user_id = NULL, $website_id = NULL)
    {
        if (is_null($user_id))
        {
            return response(['error' => 'User ID not found'], 404);
        }
        if (is_null($website_id))
        {
            return response(['error' => 'Website ID not found'], 404);
        }
        $user = WebUser::where('id', $user_id)->first();
        if (is_null($user))
        {
            return response(['error' => 'User not found for provided User ID'], 404);
        }
        $website = Website::where('id', $website_id)->first();
        if (is_null($website))
        {
            return response(['error' => 'Website not found for provided Website ID'], 404);
        }
        if (is_null($user->subscribed_websites))
        {
            $user->subscribed_websites = array((int)$website_id);
            $user->save();
        }
        else {
            $subscribed_websites = json_decode($user->subscribed_websites, TRUE);
            if (in_array($website_id, $subscribed_websites))
            {
                return response(['error' => 'Website already subscribed'], 404);
            }
            array_push($subscribed_websites, (int)$website_id);
            $user->subscribed_websites = $subscribed_websites;
            $user->save();
        }
        return response()->json([
            'response' => "A website has been successfully subscribed",
        ], 200);
    }


    public function send_emails(Request $request)
    {
        $users = WebUser::all();
        foreach ($users as $user)
        {
            $subscribed_websites = json_decode($user->subscribed_websites, TRUE);
            foreach ($subscribed_websites as $subscribed_website)
            {
                $posts = Post::where('website_id', $subscribed_website)->get();   
                foreach ($posts as $post){
                    if (is_null($user->seen_posts))
                    {                       
                        $user->seen_posts = array((int)$post->id);
                        $user->save();
                    }
                    else {
                        if (is_array($user->seen_posts))
                        {
                            $seen_posts = $user->seen_posts;
                        }
                        else
                        {
                            $seen_posts = json_decode($user->seen_posts, TRUE);
                        }
                        if (in_array($post->id, $seen_posts))
                        {
                            
                        }
                        else 
                        {
                            array_push($seen_posts, (int)$post->id);
                            $user->seen_posts = $seen_posts;
                            $user->save();
                        }
                    }
                }
            }
        }
        return response()->json([
            'response' => "Emails has been sent.",
        ], 200);
    }
}
