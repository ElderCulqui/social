<?php

namespace App\Traits;

use Illuminate\Support\Str;

use App\Models\Like;
use App\Events\ModelLiked;
use App\Events\ModelUnLiked;

trait HasLikes
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    
    public function like()
    {
        $this->likes()->firstOrCreate([
            'user_id' => auth()->id()
        ]);

        ModelLiked::dispatch($this, auth()->user());
    }
    
    public function unlike()
    {
        $this->likes()->where([
            'user_id' => auth()->id()
        ])->delete();

        ModelUnLiked::dispatch($this);
    }

    public function isLiked()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function eventChannelName()
    {
        return strtolower(Str::plural(class_basename($this))) . '.' . $this->getKey() . '.likes';
    }

    abstract public function path();
}
