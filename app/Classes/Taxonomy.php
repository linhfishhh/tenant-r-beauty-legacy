<?php

namespace App\Classes;

use App\Events\Taxonomy\TaxonomyCreated;
use App\Events\Taxonomy\TaxonomyCreating;
use App\Events\Taxonomy\TaxonomyDeleted;
use App\Events\Taxonomy\TaxonomyDeleting;
use App\Events\Taxonomy\TaxonomyRetrieved;
use App\Events\Taxonomy\TaxonomySaved;
use App\Events\Taxonomy\TaxonomySaving;
use App\Events\Taxonomy\TaxonomyUpdated;
use App\Events\Taxonomy\TaxonomyUpdating;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use ReflectionClass;
use Yajra\DataTables\EloquentDataTable;

/**
 * App\Classes\Taxonomy
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $language
 * @property int|null $parent_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property PostType[]|Collection $posts
 * @property Taxonomy[]|Collection $children
 * @property PostTaxRel[]|Collection $relations
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy[] whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy[] whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy[] whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy[] whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy[] whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy[] whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy[] whereUpdatedAt($value)
 * @mixin \Eloquent
 */
abstract class Taxonomy extends Model
{
    use Notifiable;

	protected $dates = [
		'created_at',
		'updated_at',
	];

	protected $dispatchesEvents = [
		'retrieved' => TaxonomyRetrieved::class,
		'creating' => TaxonomyCreating::class,
		'created' => TaxonomyCreated::class,
		'updating' => TaxonomyUpdating::class,
		'updated' => TaxonomyUpdated::class,
		'saving' => TaxonomySaving::class,
		'saved' => TaxonomySaved::class,
		'deleting' => TaxonomyDeleting::class,
		'deleted' => TaxonomyDeleted::class,
	];

    /**
     * @param Builder $query
     * @return Builder mixed
     */
    public static function dataTableQuery($query){
        return $query;
    }

    /**
     * @param EloquentDataTable $table
     * @return EloquentDataTable
     */
    public static function dataTable($table){
        return $table;
    }


    /**
     * @param Builder $query
     * @return Builder
     */
    public static function dataTableFilter($query){
        return $query;
    }

    /**
     * @param JsonResponse $data
     * @return JsonResponse
     */
    public static function dataTableViewData($data){
        return $data;
    }

    /**
     * @param Request $request
     * @param Taxonomy $tax_before_save
     */
    public static function beforeStoreData($request, $tax_before_save){

    }

    /**
     * @param Request $request
     * @param Taxonomy $tax_after_save
     */
    public static function afterStoreData($request, $tax_after_save){

    }

    /**
     * @param Request $request
     * @param Taxonomy $tax_before_save
     */
    public static function beforeUpdateData($request, $tax_before_save){
    }

    /**
     * @param Request $request
     * @param Taxonomy $tax_after_save
     */
    public static function afterUpdateData($request, $tax_after_save){

    }


    public static function getStoreRules(array $rules){
        return $rules;
    }

    public static function getStoreMessages(array $messages){
        return $messages;
    }

    public static function getUpdateRules(array $rules){
        return $rules;
    }

    public static function getUpdateMessages(array $messages){
        return $messages;
    }

    public static function getFileCatID(){
        /** @var Taxonomy  $class */
        $class = get_called_class();
        return 'taxonomy_' . $class::getTaxSlug();
    }

    public static function getFileCatIDS(){
        $rs = [];
        return $rs;
    }


    public static function getPublicIndexQuery(){
        /** @var Taxonomy  $class */
        $class = get_called_class();
        $rs = $class::orderBy('id')->where('language', '=', app()->getLocale());
        return $rs;
    }

    public static function getPublicIndexUrl(){
        /** @var Taxonomy  $class */
        $class = get_called_class();
        if(!$class::isPublic()){
            return false;
        }
        /** @var PostType $post_type */
        $post_type = $class::getPostType();
        return route($class::getPublicIndexRouteName());
    }

    /**
     * @param string $term_slug
     * @return bool|string
     */
    public static function getPublicDetailUrl($term_slug){
        /** @var Taxonomy  $class */
        $class = get_called_class();
        if(!$class::isPublic()){
            return false;
        }
        /** @var PostType $post_type */
        $post_type = $class::getPostType();
        return route($class::getPublicDetailRouteName(), ['term_slug' => $term_slug]);
    }

    public function getUrl(){
        return $this::getPublicDetailUrl($this->slug);
    }

    public static function getPublicDetailQuery($term_slug){
        /** @var Taxonomy  $class */
        $class = get_called_class();
        $rs = $class::whereSlug($term_slug);
        /** @var PostType $post_type */
        $post_type = $class::getPostType();
        if(me() && me()->hasPermission($post_type::getPreviewPermissionID())){
            return $rs;
        }
        $rs->where('language', '=', app()->getLocale());
        return $rs;
    }

    public static function hasIndex(){
        return 1;
    }

	/**
	 * @return Taxonomy
	 */
	function getClass(){
		return get_called_class();
	}

	function posts(){
		/** @var Taxonomy $class */
		$class = get_called_class();
		/** @var PostTaxRel $rel */
		$rel = $class::getPostTaxRel();
		$post_type = $class::getPostType();
		$rs = $this->hasManyThrough( $post_type, $rel, 'term_id', 'id', 'id','post_id');
		return $rs;
	}

	function publicPosts(){
        /** @var Taxonomy $class */
        $class = get_called_class();
        /** @var PostType $post_type */
        $post_type = $class::getPostType();
	    $rs = $this->posts();
        if(me() && me()->hasPermission($post_type::getPreviewPermissionID())){
            return $rs;
        }
	    $rs
            ->where('published', '=', 1)->whereDate(
            'published_at',
            '<=', Carbon::now())
            ->orderBy(
                'published_at',
                'desc');
	    return $rs;
    }

	function relations(){
		/** @var Taxonomy $class */
		$class = get_called_class();
		return $this->hasMany( $class::getPostTaxRel(),'term_id', 'id');
	}

	function children(){
		/** @var Taxonomy $class */
		$class = get_called_class();
		return $this->hasMany( $class,'parent_id','id');
	}

	function nestedChildren(){
		/** @var Taxonomy $class */
		$children = $this->children;
		if($children){
			foreach ($children as $child){
				$child->nestedChildren();
			}
		}
		return $children;
	}

    abstract public static function getPostType():String;
    abstract public static function getPostTaxRel():String;
    abstract public static function isHierarchy():bool;
    abstract public static function isSingle():bool;
    abstract public static function getMenuTitle():string;
    abstract public static function getTaxSlug():string;
    abstract public static function getSingular():string;
    abstract public static function getPlural():string;
    abstract public static function getMenuIcon():string;
    abstract public static function getMenuOrder():int;
    abstract public static function getDBTable():string;

    public function getTable()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return $class::getDBTable();
    }

    public static function getPublicSlug(){
        return get_called_class()::getTaxSlug();
    }

    public static function isPublic(){
        return 1;
    }

    public static function getIndexPublicRouteUri(){
        return '';
    }

    public static function getDetailPublicRouteUri(){
        return '{post_type_public_slug}/{taxonomy_public_slug}/{term_slug}';
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

    public static function getShowInAdminTable(){
		return true;
	}

    public static function validationRules(){

    }

	public static function validationMessages(){

	}

	//region view
	public static function getIndexView($view_data = []){
		return view('backend.pages.taxonomy.index', $view_data);
	}
	public static function getEditView($view_data = []){
		return view('backend.pages.taxonomy.edit', $view_data);
	}
	//endregion

	//region menu
	public static function getMenuSlug(){
        /** @var Taxonomy $my_class */
        $my_class = get_called_class();
        /** @var PostType $post_type_class */
        $post_type_class = $my_class::getPostType();
        return 'content.'.$post_type_class::getTypeSlug().'.'.$my_class::getTaxSlug();
    }
	//endregion

	//region permissions
	public static function getPermissionGroupID(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return 'post_type_' . $my_class::getTaxSlug();
	}

	public static function getPermissionGroup(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		/** @var PostType $my_post_type */
		$my_post_type = $my_class::getPostType();
		return new PermissionGroup(
			$my_class::getPermissionGroupID(),
			$my_post_type::getSingular().'/'.$my_class::getSingular(),
			$my_class::getMenuIcon(),
			$my_post_type::getMenuOrder() +  ($my_class::getMenuOrder()/1000000000)
		);
	}

	public static function getManagePermissionID(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return 'manage_taxonomy_' . $my_class::getTaxSlug();
	}

	public static function getManagePermission(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return new Permission(
			$my_class::getManagePermissionID(),
			__('Quản lý :plural', ['plural'=>mb_strtolower( $my_class::getPlural())]),
			$my_class::getPermissionGroupID(),
			$my_class::getMenuOrder()
		);
	}
	//endregion

	//region routes
	public static function getBackendIndexRouteName(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return 'post_type.'.$my_class::getTaxSlug().'.index';
	}

	public static function getBackendCreateRouteName(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return 'taxonomy.'.$my_class::getTaxSlug().'.create';
	}

	public static function getBackendStoreRouteName(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return 'taxonomy.'.$my_class::getTaxSlug().'.store';
	}

	public static function getBackendEditRouteName(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return 'taxonomy.'.$my_class::getTaxSlug().'.edit';
	}

	public static function getBackendUpdateRouteName(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return 'taxonomy.'.$my_class::getTaxSlug().'.update';
	}

	public static function getBackendDestroyRouteName(){
		/** @var Taxonomy $my_class */
		$my_class = get_called_class();
		return 'taxonomy.'.$my_class::getTaxSlug().'.destroy';
	}
	//endregion

    public static function getThemeIndexViewName(){
        /** @var Taxonomy $my_class */
        $my_class = get_called_class();
        /** @var PostType $post_type */
        $post_type = $my_class::getPostType();
	    $name = "post_type.{$post_type::getTypeSlug()}.taxonomy.{$my_class::getTaxSlug()}.index";
	    $view_name = Theme::getViewName($name);
	    if($view_name){
	        return $view_name;
        }
        return false;
    }

    public static function getThemeIndexView($data = []){
        /** @var Taxonomy $my_class */
        $my_class = get_called_class();
	    $view_name = $my_class::getThemeIndexViewName();
	    if(!$view_name){
	        return false;
        }
	    return view($view_name, $data);
    }

    /**
     * @param Taxonomy $term
     * @return bool|string
     */
    public static function getThemeTermViewName($term){
        /** @var Taxonomy $my_class */
        $my_class = get_called_class();
        /** @var PostType $post_type */
        $post_type = $my_class::getPostType();

        $name = "post_type.{$post_type::getTypeSlug()}.taxonomy.{$my_class::getTaxSlug()}.term_".$term->id;
        $view_name = Theme::getViewName($name);
        if($view_name){
            return $view_name;
        }

        $name = "post_type.{$post_type::getTypeSlug()}.taxonomy.{$my_class::getTaxSlug()}.term_".$term->slug;
        $view_name = Theme::getViewName($name);
        if($view_name){
            return $view_name;
        }

        $name = "post_type.{$post_type::getTypeSlug()}.taxonomy.{$my_class::getTaxSlug()}.term";
        $view_name = Theme::getViewName($name);
        if($view_name){
            return $view_name;
        }
        return false;
    }

    /**
     * @param Taxonomy $term
     * @param array $data
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function getThemeTermView($term, $data = []){
        /** @var Taxonomy $my_class */
        $my_class = get_called_class();
        $view_name = $my_class::getThemeTermViewName($term);
        if(!$view_name){
            return false;
        }
        return view($view_name, $data);
    }

    public static function getPublicIndexRouteName(){
        /** @var Taxonomy $my_class */
        $my_class = get_called_class();
        /** @var PostType $post_type */
        $post_type = $my_class::getPostType();
        return "frontend.term.index.{$post_type::getTypeSlug()}.{$my_class::getTaxSlug()}";
    }

    public static function getPublicDetailRouteName(){
        /** @var Taxonomy $my_class */
        $my_class = get_called_class();
        /** @var PostType $post_type */
        $post_type = $my_class::getPostType();
        return "frontend.term.detail.{$post_type::getTypeSlug()}.{$my_class::getTaxSlug()}";
    }
}
