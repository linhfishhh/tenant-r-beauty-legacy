<?php

namespace App\Classes;

use App\Events\PostType\PostCreated;
use App\Events\PostType\PostCreating;
use App\Events\PostType\PostDeleted;
use App\Events\PostType\PostDeleting;
use App\Events\PostType\PostRetrieved;
use App\Events\PostType\PostSaved;
use App\Events\PostType\PostSaving;
use App\Events\PostType\PostUpdated;
use App\Events\PostType\PostUpdating;
use App\UploadedFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Yajra\DataTables\EloquentDataTable;

;

/**
 * App\Classes\PostType
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property int $user_id
 * @property User $user
 * @property bool $published
 * @property bool|null $commentable
 * @property string $language
 * @property-read Comment[]|null $comments
 * @property-read Comment[]|null $public_comments
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $published_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role wherePublishedAt($value)
 * @mixin \Eloquent
 */
abstract class PostType extends Model
{
    use Notifiable, SoftDeletes;


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'published_at'
    ];

    protected $dispatchesEvents = [
        'retrieved' => PostRetrieved::class,
        'creating'  => PostCreating::class,
        'created'   => PostCreated::class,
        'updating'  => PostUpdating::class,
        'updated'   => PostUpdated::class,
        'saving'    => PostSaving::class,
        'saved'     => PostSaved::class,
        'deleting'  => PostDeleting::class,
        'deleted'   => PostDeleted::class,
    ];


    /**
     * @param Builder $query
     * @return Builder mixed
     */
    public static function dataTableQuery($query)
    {
        return $query;
    }

    /**
     * @param EloquentDataTable $table
     * @return EloquentDataTable
     */
    public static function dataTable($table)
    {
        return $table;
    }


    /**
     * @param Builder $query
     * @return Builder
     */
    public static function dataTableFilter($query)
    {
        return $query;
    }

    /**
     * @param JsonResponse $data
     * @return JsonResponse
     */
    public static function dataTableViewData($data)
    {
        return $data;
    }

    public static function getFileCatID()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return 'post_type_' . $class::getTypeSlug();
    }

    public static function getFileCatIDS(){
        $rs = [];
        return $rs;
    }

    /**
     * @param Request $request
     * @param PostType $post_before_save
     */
    public static function beforeStoreData(
        $request,
        $post_before_save
    ) {

    }

    /**
     * @param Request $request
     * @param PostType $post_after_save
     */
    public static function afterStoreData(
        $request,
        $post_after_save
    ) {

    }

    /**
     * @param Request $request
     * @param PostType $post_before_save
     */
    public static function beforeUpdateData(
        $request,
        $post_before_save
    ) {

    }

    /**
     * @param Request $request
     * @param PostType $post_after_save
     */
    public static function afterUpdateData(
        $request,
        $post_after_save
    ) {

    }


    public static function getStoreRules(array $rules)
    {
        return $rules;
    }

    public static function getStoreMessages(array $messages)
    {
        return $messages;
    }

    public static function getUpdateRules(array $rules)
    {
        return $rules;
    }

    public static function getUpdateMessages(array $messages)
    {
        return $messages;
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call(
        $method,
        $parameters
    ) {
        if (in_array(
            $method,
            [
                'increment',
                'decrement'
            ]
        )) {
            return $this->$method(
                ...
                $parameters
            );
        }
        $names = $this->getRelationNames();
        if (array_key_exists(
            $method,
            $names
        )) {
            /** @var PostType $class */
            $data = $names[$method];
            if ($data['is_rel']) {
                return $this->hasMany(
                    $data['rel'],
                    'post_id',
                    'id'
                );
            } else {
                return $this->hasManyThrough(
                    $data['taxonomy'],
                    $data['rel'],
                    'post_id',
                    'id',
                    'id',
                    'term_id'
                );
            }
        }

        return $this->newQuery()->$method(
            ...
            $parameters
        );
    }

    public static function getPublicIndexQuery()
    {
        /** @var PostType $class */
        $class = get_called_class();
        $rs = $class::wherePublished(1)->whereDate(
            'published_at',
            '<=',
            Carbon::now()
        )->orderBy(
                'published_at',
                'desc'
            )->where(
                'language',
                '=',
                app()->getLocale()
            );
        return $rs;
    }

    public static function hasIndex()
    {
        return 1;
    }

    public static function getPublicDetailQuery($post_slug)
    {
        /** @var PostType $class */
        $class = get_called_class();
        $rs = $class::whereSlug($post_slug);

        if (me() && me()->hasPermission($class::getPreviewPermissionID())) {
            return $rs;
        }
        $rs->whereDate(
            'published_at',
            '<=',
            Carbon::now()
        )->where(
                'published',
                '=',
                1
            )->where(
                'language',
                '=',
                app()->getLocale()
            );
        return $rs;
    }

    private function getRelationNames()
    {
        $rs = [];
        /** @var PostType $class */
        $class = get_called_class();
        $taxonomies = $class::getTaxonomies();
        foreach ($taxonomies as $taxonomy => $rel) {
            /** @var Taxonomy $taxonomy */
            /** @var PostTaxRel $rel */
            $rs[$taxonomy::getShortClass()] = [
                'taxonomy' => $taxonomy,
                'rel'      => $rel,
                'is_rel'   => 0
            ];
            $rs[$rel::getShortClass()] = [
                'taxonomy' => $taxonomy,
                'rel'      => $rel,
                'is_rel'   => 1
            ];
        }
        return $rs;
    }

    /**
     * @param Taxonomy|string $taxonomy
     *
     * @return bool
     */
    public static function hasTaxonomy($taxonomy)
    {
        /** @var PostType $class */
        $class = get_called_class();
        foreach ($class::getTaxonomies() as $tax => $rel) {
            if ($tax == $taxonomy) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param PostTaxRel|string $tax_rel
     *
     * @return bool
     */
    public static function hasTaxonomyRel($tax_rel)
    {
        /** @var PostType $class */
        $class = get_called_class();
        foreach ($class::getTaxonomies() as $tax => $rel) {
            if ($rel == $tax_rel) {
                return true;
            }
        }
        return false;
    }

    public static function getTaxPublicSlugs()
    {
        /** @var PostType $class */
        $class = get_called_class();
        $rs = [];
        /**
         * @var Taxonomy $taxonomy
         */
        foreach ($class::getTaxonomies() as $taxonomy => $rel) {
            $rs[] = $taxonomy::getPublicSlug();
        }
        return $rs;
    }

    function user()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return $this->hasOne(
            User::class,
            'id',
            'user_id'
        );
    }

    function attachments()
    {
        if (!$this::getAttachmentType()) {
            return null;
        }
        return $this->hasMany(
            $this::getAttachmentType(),
            'target_id',
            'id'
        );
    }

    function attachment_files()
    {
        if (!$this::getAttachmentType()) {
            return null;
        }
        return $this->hasManyThrough(
            UploadedFile::class,
            $this::getAttachmentType(),
            'file_id',
            'id',
            'id',
            'id'
        );
    }

    function saveAttachments(
        Request $request,
        $type,
        $field_name
    ) {
        if (!$this::getAttachmentType()) {
            return;
        }
        /** @var Attachment $attachment_type */
        $attachment_type = $this::getAttachmentType();
        $attachment_ids = $request->get(
            $field_name,
            []
        );
        if ($attachment_ids) {
            foreach ($attachment_ids as $attachment_id) {
                /** @var Attachment $attachment */
                $attachment = new $attachment_type();
                $attachment->target_id = $this->id;
                $attachment->file_id = $attachment_id;
                $attachment->type = $type;
                $attachment->save();
            }
        }
    }

    /**
     * @param null|string|array $type
     */
    function deleteAttachments($type = null)
    {
        if (!$this::getAttachmentType()) {
            return;
        }
        /** @var Attachment $attachment_type */
        $attachment_type = $this::getAttachmentType();
        $q = $attachment_type::where(
            'target_id',
            '=',
            $this->id
        );
        if ($type !== null) {
            if (is_array($type)) {
                $q->whereIn(
                    'type',
                    $type
                );
            } else {
                $q->where(
                    'type',
                    '=',
                    $type
                );
            }
        }
        try {
            $q->delete();
        } catch (\Exception $e) {
        }
    }

    /**
     * @param Attachment $attachment
     * @return bool
     */
    function checkAttachmentDownload($attachment)
    {
        return true;
    }

    function comments()
    {
        /** @var PostType $class */
        $class = get_called_class();
        if (!$class::getCommentType()) {
            return null;
        }
        /** @var Comment $comment_type */
        $comment_type = $class::getCommentType();
        return $this->hasMany(
            $comment_type,
            'target_id',
            'id'
        );
    }

    function public_comments(){
        return $this->comments()->where('published', '=', 1);
    }

    /**
     * @param Taxonomy|string $taxonomy
     * @param PostTaxRel|string $rel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function buildPostTaxQuery(
        $taxonomy,
        $rel
    ) {
        return $this->hasManyThrough(
            $taxonomy,
            $rel,
            'post_id',
            'id',
            'id',
            'term_id'
        );
    }

    /**
     * @param string|Taxonomy $taxonomy
     *
     * @return Collection
     */
    public function getTerms($taxonomy)
    {
        $rs = new Collection();
        if ($this::hasTaxonomy($taxonomy)) {
            $name = $taxonomy::getShortClass();
            $this->loadMissing($name);
            $rs = $this->$name;
        }
        return $rs;
    }

    /**
     * @param string|PostTaxRel $tax_rel
     *
     * @return Collection
     */
    public function getTermRelations($tax_rel)
    {
        $rs = new Collection();
        if ($this::hasTaxonomyRel($tax_rel)) {
            $name = $tax_rel::getShortClass();
            $this->loadMissing($name);
            $rs = $this->$name;
        }
        return $rs;
    }

    /**
     * @param string|Taxonomy $taxonomy
     */
    public function removeTerms($taxonomy)
    {
        if ($this::hasTaxonomy($taxonomy)) {
            /** @var PostTaxRel $name */
            $name = $taxonomy::getPostTaxRel();
            $name = $name::getShortClass();
            /** @var Builder $rel */
            $rel = $this->$name();
            $rel->delete();
        }
    }

    /**
     * @param PostTaxRel|string $rel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buildPostTaxRelQuery($rel)
    {
        return $this->hasMany(
            $rel,
            'post_id',
            'id'
        );
    }

    abstract public static function getTaxonomies(): array;

    abstract public static function getMenuTitle(): string;

    abstract public static function getMenuIndexTitle(): string;

    abstract public static function getTypeSlug(): string;

    abstract public static function getSingular(): string;

    abstract public static function getPlural(): string;

    abstract public static function getMenuIcon(): string;

    abstract public static function getMenuIndexIcon(): string;

    abstract public static function getMenuOrder(): int;

    abstract public static function getDBTable(): string;

    abstract public static function getCommentType(): string;

    abstract public static function getAttachmentType(): string;


    public static function getPublicSlug()
    {
        return get_called_class()::getTypeSlug();
    }

    public static function isPublic()
    {
        return 1;
    }

    /**
     * @param string $post_slug
     * @return bool|string
     */
    public static function getPublicDetailUrl($post_slug)
    {
        /** @var PostType $class */
        $class = get_called_class();
        if (!$class::isPublic()) {
            return false;
        }
        return route(
            $class::getPublicDetailRouteName(),
            ['post_slug' => $post_slug]
        );
    }

    public function getUrl()
    {
        return $this::getPublicDetailUrl($this->slug);
    }

    public static function getPublicIndexUrl(){
        /** @var PostType $class */
        $class = get_called_class();
        if (!$class::isPublic()) {
            return false;
        }
        return route($class::getPublicIndexRouteName());
    }

    public function getIndexUrl()
    {
        return $this::getPublicIndexUrl();
    }

    public static function showDashboardWidget()
    {
        return 1;
    }


    public static function getDashboardWidgetViewName()
    {
        return 'backend.includes.dashboard_widgets.post_stats';
    }

    public static function getDashboardWidgetViewData()
    {
        /** @var PostType $type */
        $type = get_called_class();
        $total = $type::whereDate(
            'published_at',
            '<=',
            Carbon::now()
        )->where(
            'published',
            '=',
            1
        )->count();
        $today = $type::whereDate(
            'published_at',
            '=',
            Carbon::today()
        )
            ->where(
            'published',
            '=',
            1
        )->count();
        $week = $type::whereBetween('published_at', [
            Carbon::parse('last monday')->startOfDay(),
            Carbon::parse('next friday')->endOfDay(),
        ])
            ->where(
                'published',
                '=',
                1
            )->count();
        $month = $type::whereBetween('published_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])
            ->where(
                'published',
                '=',
                1
            )->count();
        return [
            'post_type' => $type,
            'total'     => $total,
            'today' => $today,
            'week' => $week,
            'month' => $month
        ];
    }

    public static function getDashboardWidgetPermissions()
    {
        return [];
    }

    public static function getDashboardWidgetPermissionHasOne()
    {
        return 0;
    }

    public static function getDashboardWidgetOrder()
    {
        return 0;
    }

    public function getTable()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return $class::getDBTable();
    }

    //region view
    public static function getIndexView($view_data = [])
    {
        return view(
            'backend.pages.post.index',
            $view_data
        );
    }

    public static function getEditView($view_data = [])
    {
        return view(
            'backend.pages.post.edit',
            $view_data
        );
    }
    //endregion

    //region menu
    public static function getMenuSlug()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'content.' . $my_class::getTypeSlug();
    }

    public static function getMenuIndexSlug()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return $my_class::getMenuSlug() . '.index';
    }
    //endregion

    //region permissions
    public static function getPermissionGroupID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'post_type_' . $my_class::getTypeSlug();
    }

    public static function getPermissionGroup()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new PermissionGroup(
            $my_class::getPermissionGroupID(),
            $my_class::getSingular(),
            $my_class::getMenuIcon(),
            $my_class::getMenuOrder()
        );
    }

    public static function getPreviewPermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'preview_post_type_' . $my_class::getTypeSlug();
    }

    public static function getPreviewPermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getPreviewPermissionID(),
            __(
                'Xem :type chưa xuất bản hoặc tạm tắt',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            99
        );
    }

    public static function getCreatePermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'create_post_type_' . $my_class::getTypeSlug();
    }

    public static function getCreatePermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getCreatePermissionID(),
            __(
                'Tạo :type',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            0
        );
    }

    public static function getEditPermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'edit_post_type_' . $my_class::getTypeSlug();
    }

    public static function getEditPermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getEditPermissionID(),
            __(
                'Chỉnh sửa :type',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            1
        );
    }

    public static function getTrashPermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'trash_post_type_' . $my_class::getTypeSlug();
    }

    public static function getTrashPermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getTrashPermissionID(),
            __(
                'Tạm xóa :type',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            2
        );
    }

    public static function getDeletePermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'delete_post_type_' . $my_class::getTypeSlug();
    }

    public static function getDeletePermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getDeletePermissionID(),
            __(
                'Xóa vĩnh viễn :type',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            3
        );
    }

    public static function getPublishPermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'publish_post_type_' . $my_class::getTypeSlug();
    }

    public static function getPublishPermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getPublishPermissionID(),
            __(
                'Xuất bản :type',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            4
        );
    }

    public static function getAutoPublishPermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'auto_publish_post_type_' . $my_class::getTypeSlug();
    }

    public static function getAutoPublishPermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getAutoPublishPermissionID(),
            __(
                'Tự động xuất bản :type',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            5
        );
    }

    public static function getCatalogizePermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'catalogize_post_type_' . $my_class::getTypeSlug();
    }

    public static function getCatalogizePermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getCatalogizePermissionID(),
            __(
                'Phân loại :type',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            6
        );
    }

    public static function getManageGlobalPermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'manage_global_post_type_' . $my_class::getTypeSlug();
    }

    public static function getManageGlobalPermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getManageGlobalPermissionID(),
            __(
                'Quản lý :types của người khác',
                ['types' => mb_strtolower($my_class::getPlural())]
            ),
            $my_class::getPermissionGroupID(),
            7
        );
    }

    public static function getChangeAuthorPermissionID()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'change_author_post_type_' . $my_class::getTypeSlug();
    }

    public static function getChangeAuthorPermission()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return new Permission(
            $my_class::getChangeAuthorPermissionID(),
            __(
                'Chuyển tác giả :type',
                ['type' => mb_strtolower($my_class::getSingular())]
            ),
            $my_class::getPermissionGroupID(),
            8
        );
    }
    //endregion

    //region routes
    public static function getBackendIndexRouteName()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'post_type.' . $my_class::getTypeSlug() . '.index';
    }

    public static function getBackendCreateRouteName()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'post_type.' . $my_class::getTypeSlug() . '.create';
    }

    public static function getBackendStoreRouteName()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'post_type.' . $my_class::getTypeSlug() . '.store';
    }

    public static function getBackendEditRouteName()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'post_type.' . $my_class::getTypeSlug() . '.edit';
    }

    public static function getBackendUpdateRouteName()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'post_type.' . $my_class::getTypeSlug() . '.update';
    }

    public static function getBackendDestroyRouteName()
    {
        /** @var PostType $my_class */
        $my_class = get_called_class();
        return 'post_type.' . $my_class::getTypeSlug() . '.destroy';
    }

    public static function getItemPerPage()
    {
        /** @var PostType $class */
        $class = get_called_class();
        $items_per_page = getSetting(
            $class::getTypeSlug() . '_items_per_page',
            10
        );
        return $items_per_page;
    }

    public static function getThemeIndexViewName()
    {
        /** @var PostType $class */
        $class = get_called_class();
        $name = "post_type.{$class::getTypeSlug()}.index";
        $view_name = Theme::getViewName($name);
        if ($view_name) {
            return $view_name;
        }
        return false;
    }

    public static function getThemeIndexView($data = [])
    {
        /** @var PostType $class */
        $class = get_called_class();
        $view_name = $class::getThemeIndexViewName();
        if (!$view_name) {
            return false;
        }
        return view(
            $view_name,
            $data
        );
    }

    /**
     * @param PostType $post
     * @return bool|string
     */
    public static function getThemePostViewName($post)
    {
        /** @var PostType $class */
        $class = get_called_class();

        $name = "post_type.{$class::getTypeSlug()}.post_" . $post->id;
        $view_name = Theme::getViewName($name);
        if ($view_name) {
            return $view_name;
        }

        $name = "post_type.{$class::getTypeSlug()}.post_" . $post->slug;
        $view_name = Theme::getViewName($name);
        if ($view_name) {
            return $view_name;
        }

        $name = "post_type.{$class::getTypeSlug()}.post";
        $view_name = Theme::getViewName($name);
        if ($view_name) {
            return $view_name;
        }

        return false;
    }

    /**
     * @param PostType $post
     * @param array $data
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function getThemePostView(
        $post,
        $data = []
    ) {
        /** @var PostType $class */
        $class = get_called_class();
        $view_name = $class::getThemePostViewName($post);
        if (!$view_name) {
            return false;
        }
        return view(
            $view_name,
            $data
        );
    }

    public static function getPublicIndexRouteName()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return "frontend.post.index.{$class::getTypeSlug()}";
    }

    public static function getPublicDetailRouteName()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return "frontend.post.detail.{$class::getTypeSlug()}";
    }

    public static function isCommentSupported(){
        /** @var PostType $class */
        $class = get_called_class();
        return $class::getCommentType();
    }

    public function getCommentPermissionStatus(User $user){
        $rs = $this::isCommentSupported();
        return $rs != '';
    }



    public function getPublicCommentQuery(){
        /** @var PostType $class */
        $class = get_called_class();
        return $class::getPublicCommentQueryByID($this->id);
    }

    public static function getPublicCommentQueryByID($target_id){
        /** @var PostType $class */
        $class = get_called_class();
        if(!$class::isCommentSupported()){
            return false;
        }
        /** @var Comment $comment_type */
        $comment_type = $class::getCommentType();
        return $comment_type::getPublicCommentQueryByID($target_id);
    }

    /**
     * @param User|null $user
     * @return array
     */
    public function isAllowComment($user){
        /** @var PostType $class */
        $class = get_called_class();
        if(!$class::isCommentSupported()){
            return [
                'allow' => 0,
                'message' => __('Không hỗ trợ')
            ];
        }
        /** @var Comment $comment_type */
        $comment_type = $class::getCommentType();
        $rs = $comment_type::isAllowComment(
            $user,
            $this);
        return $rs;
    }

    /**
     * @param string $title
     * @param null|PostType $post
     */
    public function generateSlug(
        $title,
        $post = null
    ) {
        /** @var PostType $post_type */
        $post_type = static::class;
        $edit_mode = $post != null;
        $slug_temp = $title;
        if (!$slug_temp) {
            $slug_temp = $title;
        }
        $slug_temp = str_slug($slug_temp);
        $c = 1;
        $slug = $slug_temp;
        if ($edit_mode) {
            while ($post_type::whereSlug($slug)->whereKeyNot($post->id)->count() > 0) {
                $slug = $slug_temp . '-' . $c;
                $c++;
            }
        } else {
            while ($post_type::whereSlug($slug)->count() > 0) {
                $slug = $slug_temp . '-' . $c;
                $c++;
            }
        }
        $this->slug = $slug;
    }
}
