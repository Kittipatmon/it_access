<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $users = $this->userRepo->getPaginated(20, $search);
        return view('backend.users.index', compact('users', 'search'));
    }

    public function show($id)
    {
        $user = $this->userRepo->findById($id);
        if (!$user) abort(404);
        return view('backend.users.show', compact('user'));
    }

    public function toggleStatus($id)
    {
        $user = $this->userRepo->findById($id);
        if (!$user) abort(404);

        $this->userRepo->update($id, ['is_active' => !$user->is_active]);

        return redirect()->back()->with('success', 'ปรับปรุงสถานะผู้ใช้งานเรียบร้อยแล้ว');
    }
}
