<?php

namespace App\Models;


use App\Models\Base\BaseModel;

class SentenceModel extends BaseModel
{
    protected $table = 'cherish_time.tb_sentence';
    protected $fillable = [
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'show_times' => 'integer',
    ];

    public function addNew($content, $author, $book)
    {
        $this->content = $content;
        $this->author = $author;
        $this->book = $book;

        $this->save();

        return $this->id;
    }

    public function getRand($perPage)
    {
        $modelList = self::orderByRaw('RAND()')
            ->take($perPage)
            ->get();

        foreach ($modelList as $model) {
            $model->increment('show_times');
            $model->save();
        }

        return $modelList;
    }
}