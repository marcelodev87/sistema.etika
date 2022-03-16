<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaryAddress extends Model
{
    protected $guarded = [];

    public function getAddress()
    {
        $str = $this->street;

        if ($this->complement) {
            $str .= ' (' . $this->complement . ')';
        }

        if ($this->street_number != "") {
            $str .= ', ' . $this->street_number;
        }


        if ($this->neighborhood) {
            $str .= ', ' . $this->neighborhood;
        }

        if ($this->city) {
            $str .= ', ' . $this->city;
        }

        if ($this->state) {
            $str .= ' - ' . $this->state;
        }

        if ($this->zip) {
            $str .= ' - ' . $this->zip;
        }

        return $str;

    }


}
