<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
	protected $table = 'matches';
	public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'result','log_path'
	];
}