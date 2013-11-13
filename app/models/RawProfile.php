<?php

class RawProfile extends Eloquent {
    protected $table = "profile_raw";
    protected $guarded = array('id','created_at');

    static public function findLatest()
    {
        return self::groupBy('username')->orderBy('created_at')->get();
    }
}