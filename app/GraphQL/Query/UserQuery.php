<?php
namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use App\Models\User;


class UserQuery extends Query {

    protected $attributes = [
        'name' => 'User query',
        'description' => 'User model'
    ];

    public function type()
    {
        return GraphQL::type('user');
    }

    public function args()
    {
        return [
            'user_id' => ['type' => Type::int() ],
            'mobile' => ['type' => Type::string() ],
        ];
    }

    public function resolve($root, $args)
    {

        if(isset($args['user_id']))
            return User::where('user_id', $args['user_id'])->first();

        if(isset($args['mobile'])){
            return User::where('mobile', $args['mobile'])->first();
        }

    }

}
