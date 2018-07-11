<?php

namespace App\GraphQL\Type;


use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType{

    protected $attributes = [
        'name' => 'User',
        'description' => 'A user' // optional
    ];

    public function fields()
    {
        return [
            'id' => ['type' => Type::nonNull(Type::string()) ],
            'name' => ['type' => Type::string() ],
            'email' => ['type' => Type::string() ],
            'mobile' => ['type' => Type::string() ],
            'male' => ['type' => Type::boolean() ],
            'created_at' => ['type' => Type::string() ],
            'updated_at' => ['type' => Type::string() ],
        ];
    }


    // If you want to resolve the field yourself, you can declare a method
    // with the following format resolve[FIELD_NAME]Field()
    protected function resolveNameField($root, $args)
    {
        return strtolower($root->name);
    }

}
