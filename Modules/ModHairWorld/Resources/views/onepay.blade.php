<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
            .result{
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
            }
            .result-inner {
                display: table;
                width: 100%;
                height: 100%;
            }
            .result-wrapper{
                display: table-cell;
                vertical-align: middle;
                text-align: center;
                padding-left: 15px;
                padding-right: 15px;
            }
            .error-button{
                display: inline-block;
                line-height: 50px;
                padding-left: 30px;
                padding-right: 30px;
                background-color: black;
                color: white;
                margin-bottom: 15px;
                text-decoration: none;
                border-radius: 5px;
                width: 180px;
            }
            .error-message{
                margin-bottom: 30px;
            }

    </style>
</head>
<body>
    <div class="result">
       <div class="result-inner">
          <div class="result-wrapper">
              @if($success)
                  <div class="result-text">
                      Giao dịch thành công xin vui lòng chờ...
                  </div>
              @else
                  <div class="result-text">
                      <div class="error-message">Giao dịch thất bại <br/>("{!! $message !!}")</div>
                      <div class="error-buttons">
                          @if($cancel)
                              <div><a class="error-button cancel" href="{{$cancel}}">Huỷ đơn đặt chỗ</a></div>
                          @endif
                          @if($retry)
                              <div><a class="error-button" href="{{$retry}}">Thử lại</a></div>
                          @endif
                      </div>
                  </div>
              @endif
          </div>
       </div>
    </div>
</body>
</html>
