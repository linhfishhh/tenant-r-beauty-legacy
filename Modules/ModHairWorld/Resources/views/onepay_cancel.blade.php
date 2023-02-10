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
                  <div class="result-text">
                      Đang huỷ giao dịch, vui lòng chờ...
                  </div>
          </div>
       </div>
    </div>
</body>
</html>
