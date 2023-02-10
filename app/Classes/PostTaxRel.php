<?php

namespace App\Classes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use ReflectionClass;

/**
 * App\Classes\PostTaxRel
 *
 * @property int $id
 * @property int $post_id
 * @property int $term_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PostTaxRel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTaxRel[] wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTaxRel[] whereTermId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTaxRel[] whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTaxRel[] whereUpdatedAt($value)
 * @mixin \Eloquent
 */
abstract class PostTaxRel extends Model
{
    use Notifiable;

	protected $dates = [
		'created_at',
		'updated_at',
	];

    abstract public static function getTaxonomy():string ;
    abstract public static function getPostType():string ;
    abstract public static function getDBTable():string;

    public function getTable()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return $class::getDBTable();
    }

	/**
	 * @return string
	 */
	public static function getShortClass(){
		/** @var Taxonomy $class */
		$class = get_called_class();
		try {
			$reflect = new ReflectionClass( $class );
		} catch ( \ReflectionException $e ) {
		}

		return $reflect->getShortName();
	}

    function post(){
	    /** @var PostTaxRel $class */
	    $class = get_called_class();
	    /** @var PostType $post_type */
	    $post_type = $class::getPostType();
	    return $this->hasOne($post_type,'id', 'post_id');
    }

    function taxonomy(){
	    /** @var PostTaxRel $class */
	    $class = get_called_class();
	    /** @var Taxonomy $taxonomy */
	    $taxonomy = $class::getTaxonomy();
	    return $this->hasOne($taxonomy,'id', 'term_id');
    }
}
