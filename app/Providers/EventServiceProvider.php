<?php

namespace App\Providers;

use App\Events\BackendDashboardWidgetRegister;
use App\Events\BackendMenuItemCheckActive;
use App\Events\BackendMenuItemRegister;
use App\Events\BackendSettingPageRegister;
use App\Events\Comment\CommentDeleted;
use App\Events\DefineContent;
use App\Events\FileCategoryRegister;
use App\Events\FileTypeGroupRegister;
use App\Events\FileTypeRegister;
use App\Events\Menu\MenuDeleted;
use App\Events\MenuTypeRegister;
use App\Events\PermissionRegister;
use App\Events\PostType\PostDeleted;
use App\Events\PostType\PostUpdated;
use App\Events\Role\RoleDeleted;
use App\Events\Sidebar\SidebarDeleted;
use App\Events\SiteURLChanged;
use App\Events\Taxonomy\TaxonomyDeleted;
use App\Events\Taxonomy\TaxonomyUpdated;
use App\Events\ThumbnailSizeRegister;
use App\Events\UploadedFile\UploadedFileCreated;
use App\Events\UploadedFile\UploadedFileDeleted;
use App\Events\User\UserDeleted;
use App\Events\WidgetTypeRegister;
use App\Listeners\BackendDashboardWidgetRegisterDefault;
use App\Listeners\BackendMenuItemCheckActiveDefault;
use App\Listeners\BackendMenuItemRegiterDefault;
use App\Listeners\BackendSettingPageRegisterDefault;
use App\Listeners\CommentDeletedDefault;
use App\Listeners\MenuDeletedDefault;
use App\Listeners\MenuTypeRegisterDefault;
use App\Listeners\PermissionRegisterDefault;
use App\Listeners\PostDeletedDefault;
use App\Listeners\PostUpdatedDefault;
use App\Listeners\RoleDeletedDefault;
use App\Listeners\SidebarDeletedDefault;
use App\Listeners\SiteURLChangedDefault;
use App\Listeners\TaxonomyDeletedDefault;
use App\Listeners\TaxonomyUpdatedDefault;
use App\Listeners\UploadedFileCreatedDefault;
use App\Listeners\UploadedFileDeletedDefault;
use App\Listeners\UserDeletedDefault;
use App\Listeners\WidgetTypeRegisterDefault;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [];

	public function listens() {
		$this->listen = array_merge( $this->listen, $this->listenDefaultEvents() );
		$this->listen = array_merge( $this->listen, $this->listenUserEvents() );
		$this->listen = array_merge( $this->listen, $this->listenRoleEvents() );
		$this->listen = array_merge( $this->listen, $this->listenMenuEvents() );
		$this->listen = array_merge( $this->listen, $this->listenSidebarEvents() );
		$this->listen = array_merge( $this->listen, $this->listenBackendMenuEvents() );
		$this->listen = array_merge( $this->listen, $this->listenTaxonomyEvents() );
		$this->listen = array_merge( $this->listen, $this->listenPostTypeEvents() );
        $this->listen = array_merge( $this->listen, $this->listenUploadedFileEvents() );
        $this->listen = array_merge( $this->listen, $this->listenCommentEvents() );
		return parent::listens();
	}

	private function listenDefaultEvents() {
		return [
			BackendMenuItemRegister::class => [ BackendMenuItemRegiterDefault::class, ],
			PermissionRegister::class      => [ PermissionRegisterDefault::class, ],
			DefineContent::class           => [],
			ThumbnailSizeRegister::class        => [],
			FileTypeGroupRegister::class        => [],
            FileCategoryRegister::class => [],
            BackendDashboardWidgetRegister::class => [BackendDashboardWidgetRegisterDefault::class],
            BackendSettingPageRegister::class => [BackendSettingPageRegisterDefault::class],
			MenuTypeRegister::class        => [ MenuTypeRegisterDefault::class ],
			WidgetTypeRegister::class      => [ WidgetTypeRegisterDefault::class ],
            SiteURLChanged::class => [SiteURLChangedDefault::class]
		];
	}

	private function listenTaxonomyEvents() {
		return [
			TaxonomyDeleted::class => [ TaxonomyDeletedDefault::class ],
			TaxonomyUpdated::class => [ TaxonomyUpdatedDefault::class ],
		];
	}

    private function listenCommentEvents() {
        return [
            CommentDeleted::class => [ CommentDeletedDefault::class ],
        ];
    }

	private function listenPostTypeEvents() {
		return [
			PostDeleted::class => [ PostDeletedDefault::class ],
			PostUpdated::class => [ PostUpdatedDefault::class ],
		];
	}

    private function listenUploadedFileEvents() {
        return [
            UploadedFileCreated::class => [UploadedFileCreatedDefault::class],
            UploadedFileDeleted::class => [UploadedFileDeletedDefault::class],
        ];
    }

	private function listenRoleEvents() {
		return [ RoleDeleted::class => [ RoleDeletedDefault::class ] ];
	}

	private function listenUserEvents() {
		return [
		    UserDeleted::class => [UserDeletedDefault::class]
        ];
	}

	private function listenMenuEvents() {
		return [ MenuDeleted::class => [ MenuDeletedDefault::class ] ];
	}

	private function listenSidebarEvents() {
		return [ SidebarDeleted::class => [ SidebarDeletedDefault::class ] ];
	}

	private function listenBackendMenuEvents() {
		return [ BackendMenuItemCheckActive::class => [ BackendMenuItemCheckActiveDefault::class ] ];
	}

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot() {
		parent::boot();
	}
}
