<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$u = User::where('email', 'admin@example.com')->first();
if (! $u) {
    echo "NOT_FOUND";
    exit(0);
}

$roleModel = null;
if ($u->role_id) {
    $rm = $u->roleModel()->first();
    if ($rm) $roleModel = ['id' => $rm->id, 'name' => $rm->name];
}

$data = [
    'id' => $u->id,
    'email' => $u->email,
    'role_id' => $u->role_id ?? null,
    'role_accessor' => $u->role ?? null,
    'role_model' => $roleModel,
    'isAdmin' => method_exists($u, 'isAdmin') ? ($u->isAdmin() ? 'yes' : 'no') : 'no-method',
];

echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
