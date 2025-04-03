use Illuminate\Support\Facades\Gate;

public function boot()
{
    Gate::define('admin', function ($user) {
        return $user->role === 'admin';
    });
}