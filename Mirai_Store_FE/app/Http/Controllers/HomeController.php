<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        
        $games = [
            [
                'id' => 1,
                'name' => 'Genshin Impact',
                'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681285/Game/67375a03496150f55b9e8306_Genshin-Impact-03.jpg',
                'price' => 0,
                'category' => 'Action RPG'
            ],
            [
                'id' => 2,
                'name' => 'Black Myth: Wukong',
                'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681024/Game/673758fff46261230006323c_maxresdefault.jpg',
                'price' => 1290000,
                'category' => 'Action'
            ],
            [
                'id' => 3,
                'name' => 'Elden Ring',
                'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681146/Game/67375979f46261230006323e_elden-ring-shadow-of-the-erdtree-02.jpg',
                'price' => 990000,
                'category' => 'Soulslike'
            ]
        ];

        $recommendedGames = array_slice($games, 0, 2);

        return view('welcome', compact('games', 'recommendedGames'));
    }

    public function show($id)
    {
        return "Game Detail Page for ID: $id - To be connected with .NET API";
    }
}
