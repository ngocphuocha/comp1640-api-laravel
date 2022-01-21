<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
   public function givePermissionToUser($id): string
   {
//       return Permission::findById(1);
       $user = User::find($id);
       $user->assignRole(['writer']);
//       $user->givePermissionTo('edit articles');
//       $user->revokePermissionTo('edit articles');
       return (string) 'Give Permission Ok';
   }
}
