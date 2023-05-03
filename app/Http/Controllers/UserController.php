<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Collection;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{

    public function __invoke(Request $request)
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {

            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query->orWhere('name', 'LIKE', "%{$value}%")->orWhere('email', 'LIKE', "%{$value}%");
                });
            });
        });
        $users = QueryBuilder::for(User::class)
            ->defaultSort('id')
            ->allowedSorts(['id', 'name', 'email'])
            ->allowedFilters(['name', 'email', $globalSearch])
            ->paginate($request->perPage ?? 15)
            ->withQueryString();

        return Inertia::render('Users/Index', ['users' => $users])->table(function (InertiaTable $table) {

            // $table->searchInput(
            //     key: 'Search',
            //     defaultValue: 'Laravel'
            // );
            $table->withGlobalSearch('Search');

            $table->column('id', 'ID',  sortable: true, canBeHidden: false);
            $table->column('name', 'User Name',  sortable: true, canBeHidden: false);
            $table->column('email', 'Email Address',  sortable: true, canBeHidden: false);
            $table->column('created_at', 'Join Date', sortable: true, canBeHidden: false);

            // $table->selectFilter(
            //     key: 'language_code',
            //     options: [
            //         'en' => 'Engels',
            //         'nl' => 'Nederlands',
            //     ],
            //     label: 'Language',
            //     defaultValue: 'nl',
            //     noFilterOption: true,
            //     noFilterOptionLabel: 'All languages'
            // );

        });
    }
}
