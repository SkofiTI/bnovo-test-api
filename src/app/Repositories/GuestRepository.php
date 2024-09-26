<?php

namespace App\Repositories;

use App\Interfaces\GuestRepositoryInterface;
use App\Models\Guest;

class GuestRepository implements GuestRepositoryInterface
{
    public function list()
    {
        return Guest::all();
    }

    public function getById($id)
    {
        return Guest::findOrFail($id);
    }

    public function store(array $data)
    {
        return Guest::create($data);
    }

    public function update(array $data, $id)
    {
        $guest = Guest::findOrFail($id);

        $guest->first_name = $data['first_name'] ?? $guest->first_name;
        $guest->second_name = $data['second_name'] ?? $guest->second_name;
        $guest->email = $data['email'] ?? $guest->email;
        $guest->phone = $data['phone'] ?? $guest->phone;
        $guest->country = $data['country'] ?? $guest->country;

        $guest->save();

        return $guest;
    }

    public function delete($id)
    {
        Guest::destroy($id);
    }
}
