<?php

namespace App\Enums;

enum ActionEnum: string
{
    case CREATE = 'create';
    case DELETE = 'delete';
    case RECOVER = 'recover';
    case CART = 'cart';
    case REGISTER = 'register';
    case LOGIN = 'login';
    case UPDATE = 'update';
    case PASSWORD = 'password';
}
