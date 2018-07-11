<?php
namespace App\GraphQL\Mutation;

use App\Models\Role;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginUserMutation extends Mutation{

    protected $attributes = [
        'name' => 'loginUser'
    ];


    public function type()
    {
        return GraphQL::type('token');
    }


    public function args()
    {
        return [
            'username' => ['type' => Type::nonNull(Type::string())],
            'password' => ['type' => Type::nonNull(Type::string())]
        ];
    }


    public function resolve($root, $args)
    {

//        dd(1);
        $credentials = [ 'email'=>$args['username'], 'password'=> $args['password'] ];

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

//        $role = Role::create(['name'=>'EXE', 'display_name'=> 'Exe']);

        $user = JWTAuth::toUser($token);

        return [  'id'=> $user->id, 'token' => $token, 'role'=> $user->roles->last() ? $user->roles->last()->name : null, 'name'=> $user->name, 'mobile' => $user->mobile,'email' => $user->email, 'today'=> date('Y-m-d'), 'user'=> $user ];

    }


}
