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

    public function shop(Request $request)
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

        $categories = [
            ['id' => 1, 'name' => 'Action'],
            ['id' => 2, 'name' => 'Adventure'],
            ['id' => 3, 'name' => 'RPG']
        ];

        return view('shop.index', compact('games', 'categories'));
    }

    public function show($id)
    {
        
        $game = [
            'id' => $id,
            'name' => 'Black Myth: Wukong',
            'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681024/Game/673758fff46261230006323c_maxresdefault.jpg',
            'price' => 1290000,
            'publisher' => 'Game Science',
            'release_date' => '20/08/2024',
            'description' => "Black Myth: Wukong là một game nhập vai hành động bắt nguồn từ thần thoại Trung Hoa. Câu chuyện dựa trên Tây Du Ký, một trong Bốn tác phẩm kinh điển vĩ đại của văn học Trung Quốc. Bạn sẽ lên đường với tư cách là Người được định mệnh để dấn thân vào những thử thách và kỳ quan phía trước, để vén bức màn che giấu sự thật đằng sau huyền thoại về một vinh quang huy hoàng từ quá khứ.",
        ];

        $relatedGames = [
            [
                'id' => '1',
                'name' => 'Genshin Impact',
                'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681285/Game/67375a03496150f55b9e8306_Genshin-Impact-03.jpg',
                'price' => 0
            ],
            [
                'id' => '3',
                'name' => 'Elden Ring',
                'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681146/Game/67375979f46261230006323e_elden-ring-shadow-of-the-erdtree-02.jpg',
                'price' => 990000
            ]
        ];

        return view('games.show', compact('game', 'relatedGames'));
    }
}
