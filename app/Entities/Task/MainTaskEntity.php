<?php

namespace App\Entities\Task;

class MainTaskEntity implements Taskentitiy
{
    private static $tasktype_id = 2;//zamienic na pobieranie z bazy??
    private array $users;

    private $id;

    public function __construct(private $name, private $description, private $author_id, private $finish_date, array $users=[]){
        $this->users = array_values($users);
    }

    public static function fromArray(array $request){
        return new self(
            $request['name'],
            $request['description'],
            $request['author_id'],
            $request['finish_date'],
            array_values($request['assigned_users']??[])
        );
    }

    public function toArray(){
        return [
            'name'=>$this->name,
            'description'=>$this->description,
            'author_id'=>$this->author_id,
            'finish_date'=>$this->finish_date,
            'task_type_id'=>self::$tasktype_id,
            'users'=>$this->users
        ];
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsers()
    {
        return $this->users;
    }
}
