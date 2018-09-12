<?php

namespace Forum\Traits;

use Forum\Activity;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        // static::created(function ($model) {
        //     $model->recordActivity('created');
        // });

        if (auth()->guest()) {
            return;
        }
        foreach (static::getActivitiesToRecord() as $event) {
            //var_dump($event);

            static::$event(function ($model) use ($event) {
                if ($event == 'deleting' || $event =="deleted") {
                    $model->deleteActivity();
                } else {
                    $model->recordActivity($event);
                }
            });
        }
    }
    public function deleteActivity()
    {
        $this->activity()->delete();
        //var_dump("this part works deleted  ".strtolower((new \ReflectionClass($this))->getShortName()).' with id '.$this->id);
    }
    public function recordActivity($event)
    {
        $this->activity()->create([
         'user_id' => auth()->id(),
         'type' => $this->getActivityType($event)

       ]);
        //   Activity::create([
      //   'user_id' => auth()->id(),
      //   'type' => $this->getActivityType($event),
      //   'subject_id' => $this->id,
      //   'subject_type' => get_class($this)
      // ]);
    }
    protected static function getActivitiesToRecord()
    {
        return ['created','deleted'];
    }
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function getActivityType($event)
    {
        return $event.'_'.strtolower((new \ReflectionClass($this))->getShortName());// returns the short name of class
    }
}
