@php
/** @var \App\Classes\PostType $post_type */
/** @var \App\Classes\Taxonomy $taxonomy */
@endphp
<div class="mb-20">
    <h6 class="content-group text-semibold">
        <div class="text-uppercase">{!! $post_type::getSingular() !!}</div>
        <small class="display-block">{{__('Thống kê :post_type', ['post_type'=>$post_type::getSingular()])}}</small>
    </h6>
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-body panel-body-accent">
                <div class="media no-margin">
                    <div class="media-left media-middle">
                        <i class="{!! $post_type::getMenuIcon() !!} icon-3x text-danger-400"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="no-margin text-semibold">{!! sprintf('%02d', $total); !!}</h3>
                        <span class="text-uppercase text-size-mini text-muted">{{__(':post_type đã đăng', ['post_type'=>$post_type::getSingular()])}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-body panel-body-accent">
                <div class="media no-margin">
                    <div class="media-left media-middle">
                        <i class="icon-stats-bars icon-3x text-blue-400"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="no-margin text-semibold">{!! sprintf('%02d', $today); !!}</h3>
                        <span class="text-uppercase text-size-mini text-muted">{{__(':post_type đăng hôm nay', ['post_type'=>$post_type::getSingular()])}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-body panel-body-accent">
                <div class="media no-margin">
                    <div class="media-left media-middle">
                        <i class="icon-stats-bars2 icon-3x text-info-400"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="no-margin text-semibold">{!! sprintf('%02d', $week); !!}</h3>
                        <span class="text-uppercase text-size-mini text-muted">{{__(':post_type đăng tuần này', ['post_type'=>$post_type::getSingular()])}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel panel-body panel-body-accent">
                <div class="media no-margin">
                    <div class="media-left media-middle">
                        <i class="icon-stats-bars4 icon-3x text-info-400"></i>
                    </div>

                    <div class="media-body text-right">
                        <h3 class="no-margin text-semibold">{!! sprintf('%02d', $month); !!}</h3>
                        <span class="text-uppercase text-size-mini text-muted">{{__(':post_type đăng tháng này', ['post_type'=>$post_type::getSingular()])}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($post_type::getTaxonomies() as $taxonomy=>$rel)
            <div class="col-sm-6 col-md-3">
                <div class="panel panel-body panel-body-accent">
                    <div class="media no-margin">
                        <div class="media-left media-middle">
                            <i class="{!! $taxonomy::getMenuIcon() !!} icon-3x text-success"></i>
                        </div>

                        <div class="media-body text-right">
                            <h3 class="no-margin text-semibold">{!! sprintf('%02d', $taxonomy::count()); !!}</h3>
                            <span class="text-uppercase text-size-mini text-muted">{!! $taxonomy::getSingular() !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @if($post_type::isCommentSupported())
                <div class="col-sm-6 col-md-3">
                    <div class="panel panel-body panel-body-accent">
                        <div class="media no-margin">
                            <div class="media-left media-middle">
                                <i class="icon-comments icon-3x text-orange"></i>
                            </div>

                            <div class="media-body text-right">
                                <h3 class="no-margin text-semibold">{!! sprintf('%02d', $post_type::getCommentType()::count()); !!}</h3>
                                <span class="text-uppercase text-size-mini text-muted">{!! $post_type::getCommentType()::getSingular() !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
        @endif
    </div>
</div>