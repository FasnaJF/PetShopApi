<?php

namespace App\Repositories\UserRepository;

use App\Repositories\BaseRepository;
use App\Models\User;
use Carbon\Carbon;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function getAll($sortBy = null)
    {
        return $this->model->paginate(10);
    }

    public function getAllUsers($request)
    {
        $limit = $request->input('limit') ? $request->input('limit') : null;
        $sortBy = $request->input('sortBy');
        $desc = ($request->input('desc') == 'true') ? 'DESC' : 'ASC';
        $sortBy = [$sortBy, $desc];
        $first_name = $request->input('first_name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $marketing = $request->input('marketing');
        $created_at = $request->input('created_at');

        $users = $this->model
            ->when($first_name, function ($query, $first_name) {
                return $query->where('first_name', $first_name);
            })
            ->when($email, function ($query, $email) {
                return $query->where('email', $email);
            })
            ->when($phone, function ($query, $phone) {
                return $query->where('phone_number', $phone);
            })
            ->when($address, function ($query, $address) {
                return $query->where('address', $address);
            })
            ->when($marketing, function ($query, $marketing) {
                return $query->where('is_marketing', $marketing);
            })
            ->when($created_at, function ($query, $created_at) {
                return $query->where('created_at', $created_at);
            })
            ->when($sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy[0],$sortBy[1]);
            })
            ->paginate($limit);

        return $users;
    }

}
