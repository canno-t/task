<?php

namespace App\Entities\Task;

use App\Models\Assignments;
use App\Models\Task;
use Illuminate\Support\Str;

class SubTaskEntity implements Taskentitiy
{
    private static $tasktype_id = 1;//zamienic na pobieranie z bazy??

    private $id;

    private array $users;
    public function __construct(private $name, private $description, private $author_id, private $finish_date, private $dominant_task_id){
    }

    public static function fromArray(array $request){
        return new self(
            $request['name'],
            $request['description'],
            $request['author_id'],
            $request['finish_date'],
            $request['dominant_task_id'],
        );
    }

    public function toArray(){
        return [
            'name'=>$this->name,
            'description'=>$this->description,
            'author_id'=>$this->author_id,
            'finish_date'=>$this->finish_date,
            'task_type_id'=>self::$tasktype_id,
            'dominant_task_id'=>$this->dominant_task_id,
            'uuid'=>Str::uuid()
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
        if(empty($this->users)){
            return Assignments::where('task_id', $this->dominant_task_id)->get()->pluck('user_id');
        }
        return $this->users;
    }

    public function setUsers(array $users){
        $this->users = $users;
    }
}
