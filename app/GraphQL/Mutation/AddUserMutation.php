<?php
namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AddUserMutation extends Mutation{

    protected $attributes = [
        'name' => 'addUser'
    ];

    public function type()
    {
        return GraphQL::type('token');
    }

    public function args()
    {
        return [
            'email' => ['type' => Type::nonNull(Type::string())],
            'name' => ['type' => Type::nonNull(Type::string())],
            'mobile' => ['type' => Type::nonNull(Type::string())],
            'password' => ['type' => Type::nonNull(Type::string())],
            'male' => ['type' => Type::boolean()],
        ];
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users' ]
        ];
    }


    public function resolve($root, $args)
    {

        $user = $args;
        $user['password'] = bcrypt($args['password']);
        $credentials = ['email'=>$args['email'], 'password'=> $args['password'] ];

        $user =  User::create($user);

//        $token = JWTAuth::attempt($credentials);
//        $user->api_token = $token;
//        $user->save();


        return [ 'id'=> $user->id, 'token'=> JWTAuth::fromUser($user), 'user'=> $user, 'today'=> date('Y-m-d') ];

    }


}
