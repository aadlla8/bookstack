<?php

namespace BookStack;
use Illuminate\Database\Eloquent\Model;

class DataImport  extends Model
{
    protected $table = 'data_import';
    protected $primaryKey = 'id';
    protected $fillable = ['page_title', 'page_content', 'chapter', 'book', 'shelf'];
   
}
