<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CustomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('showdata')) {
            $columns = ['id', 'name', 'email', 'created_at'];
            $length = $request->input('length') ?? 10;
            $column = $request->input('column');
            $search_input = $request->input('search');
            $query = User::select('id', 'name', 'email', 'created_at');
            if ($search_input) {
                $query->where(function ($query) use ($search_input) {
                    $query->where('name', 'like', '%' . $search_input . '%')
                        ->orWhere('email', 'like', '%' . $search_input . '%')
                        ->orWhere('created_at', 'like', '%' . $search_input . '%');
                });
            }
            $users = $query->paginate($length);
            return ['data' => $users];
        } else {
            return Inertia::render('Users/Custom');
        }
    }

    public function deleteUser(User $user)
    {
        if ($user) {
            $user->delete();
        }
        return 'user deleted';
    }
}
