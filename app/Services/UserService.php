<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 26.05.20
 * Time: 11:49
 */

namespace App\Services;
use App\Models\User;

class UserService
{

    private function prepareUser(User $user, $data)
    {
        $data['name'] = trim($data['name']);
        $data['dev_id'] = trim($data['dev_id']);

        $user->fill($data);
    }

    public function store(array $data)
    {

        $user = new User();

        $this->prepareUser($user,$data);
        $user->save();

        return $user->id;
    }

    public function update(User $user, $data)
    {
        $this->prepareUser($user, $data);
        $user->save();

        return $user->id;
    }

    public function delete(int $id)
    {
        return User::destroy($id);
    }

}