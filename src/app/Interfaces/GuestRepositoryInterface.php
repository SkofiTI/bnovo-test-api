<?php

namespace App\Interfaces;

interface GuestRepositoryInterface
{
    public function list();
    public function getById($id);
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
