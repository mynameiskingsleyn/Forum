<?php

namespace Forum\Http\Controllers;

use Forum\Favorite;
use Forum\Reply;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favorite();
        if (request()->expectsJson()) {
            return response(["status"=>'favorited reply']);
        }
        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
        if (request()->expectsJson()) {
            return response(['status'=>'unfavorited reply']);
        }
        return back();
    }
}
