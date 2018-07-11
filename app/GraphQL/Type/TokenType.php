<?php

namespace App\GraphQL\Type;


use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class TokenType extends GraphQLType{

    protected $attributes = [
        'name' => 'Token',
        'description' => 'JWT Token for authenticating users' // optional
    ];

    public function fields()
    {
        return [
            'token' => [
                'type' => Type::string(),
                'description' => 'The JWT token returned by JWTAuth class'
            ],
            'role' => ['type' => Type::string(),],
            'today' => ['type'=> Type::string() ],
            'user' => ['type'=> \GraphQL::type('user') ],
        ];
    }

}
