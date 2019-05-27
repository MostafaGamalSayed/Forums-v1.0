<?php
/**
 * Created by PhpStorm.
 * User: N5537
 * Date: 23-10-2018
 * Time: 3:02 AM
 */

namespace App;


trait RecordActivity
{

    /**
     * Boot the trait
     */
    protected static function bootRecordActivity()
    {
        if (auth()->guest()) return;

        foreach(static::getActivityEvents() as $event){
            static::$event(function($model) use ($event){
                $model->recordActivity($event);
            });
        }

        // delete all the activities of a deleted thread or reply
        static::deleting(function($model){
             $model->activity()->delete();
        });
    }


    /**
     * A model activities can be recorded
     *
     * @return mixed
     */
    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }


    /**
     * Create an activity record for the model event
     *
     * @param $event
     */
    protected function recordActivity($event)
    {
       $this->activity()->create([
           'user_id' => auth()->id(),
           'type' => $this->getActivityType($event)
       ]);
    }


    /**
     * Fetch all model events that require activity recording.
     *
     * @return array
     */
    protected static function getActivityEvents()
    {
        return ['created', 'deleted'];
    }


    /**
     * Get the type of the activity event
     *
     * @param $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $className = class_basename($this);
        return strtolower("{$event}_{$className}");
    }














}