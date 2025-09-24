<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    //
    public function show()
    {
        $users = $this->userService->getUsers();

        return view('aboutus', [
            'users' => $users,
        ]);
    }

    public function showAgentDetails(int $id, Request $request)
    {
        $user = $this->userService->getUserById($id);

        return view('agentdetails', [
            'user' => $user,
        ]);
    }
}
