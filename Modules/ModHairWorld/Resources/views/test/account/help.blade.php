@enqueueCSS('help-page', getThemeAssetUrl('libs/styles/help.css'), 'account-menu')
@extends(getThemeViewName('test.account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Trợ giúp</div>
        <div class="content-body">
            <div id="help-list" class="help-list">
                @php
                    $items = [
                        [
                            'title' => 'Làm sao để đặt được chỗ khi tôi chưa đăng ký đăng nhập?',
                        ],
                        [
                            'title' => 'Làm sao để được cắt tóc khi đã đặt chỗ qua hệ thống?',
                        ],
                        [
                            'title' => 'Tôi có được quyền hủy bỏ đặt chỗ khi tôi đã lỡ đặt rồi không',
                        ],
                        [
                            'title' => 'Khi có thông báo tài khoản của bạn chưa được xác minh thì phải làm gì?',
                        ],
                    ];
                @endphp
                @foreach($items as $k=>$item)
                    <div class="item">
                        <div class="question">
                            <div class="collapsed" data-toggle="collapse" data-target="#collapse-{!! $k !!}" aria-expanded="false">
                                {!! $item['title'] !!}
                            </div>
                        </div>
                        <div id="collapse-{!! $k !!}" class="collapse" data-parent="#help-list">
                            <div class="answer common-content-block">
                                Điều 59 dự thảo Luật Phòng chống tham nhũng (sửa đổi) đưa ra 2 phương án xử lý tài sản, thu nhập kê khai không trung thực, không giải trình một cách hợp lý là áp thuế suất 45%, hoặc xử phạt vi phạm hành chính với mức phạt tiền bằng 45% giá trị của phần tài sản, thu nhập chênh lệch. Cả 2 phương án này đều không loại trừ trách nhiệm hình sự nếu các cơ quan chức năng chứng minh tài sản đó do phạm tội mà có.
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection