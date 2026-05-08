<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;

$users = User::where('firstname', 'LIKE', 'กิตติพัฒน์%')->get();
foreach ($users as $u) {
    echo "ID: {$u->id}, Name: {$u->fullname}, Emp Code: {$u->emp_code}, Role: {$u->role}\n";
}
