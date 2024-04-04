<?php

namespace App\Entities\Task;

interface Taskentitiy
{
    public static function fromArray(array $request);

    public function toArray();

    public function setId(int $id);

    public function getUsers();

    public function getId();
}
