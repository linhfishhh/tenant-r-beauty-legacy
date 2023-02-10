---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://thegioitoc.vn/_html/docs/collection.json)
<!-- END_INFO -->

#Account

Những request này yêu cầu token ở header
<!-- START_458e02eaae3b771d0d51049892b3dde7 -->
## Đổi mật khẩu*

Đổi mật khẩu đăng nhập hiện tại

> Example request:

```bash
curl -X POST "http://thegioitoc.vn/api/manager/account/change-password" \
-H "Accept: application/json" \
    -d "old_password"="sunt" \
    -d "new_password"="sunt" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://thegioitoc.vn/api/manager/account/change-password",
    "method": "POST",
    "data": {
        "old_password": "sunt",
        "new_password": "sunt"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
"true"
```

### HTTP Request
`POST api/manager/account/change-password`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    old_password | string |  required  | 
    new_password | string |  required  | Minimum: `6` Must match this regular expression: `/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/`

<!-- END_458e02eaae3b771d0d51049892b3dde7 -->

#Authentication

Những request này không yêu cầu token ở header
<!-- START_67882e62fab916565b529e72b8ab499d -->
## Login

Đăng nhập trả về token<br/>
Tham số <strong>username</strong> có thể là <i>email</i> hoặc <i>số điện thoại</i>

> Example request:

```bash
curl -X GET "http://thegioitoc.vn/api/manager/auth/login" \
-H "Accept: application/json" \
    -d "email_phone"="deserunt" \
    -d "password"="deserunt" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://thegioitoc.vn/api/manager/auth/login",
    "method": "GET",
    "data": {
        "email_phone": "deserunt",
        "password": "deserunt"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjJkZDQ0ZDYxOWI5YTZmYjY5N2QwYzJmOGQwYmVhZGExMGE3MWU2ZjM3Y2EyMTEyNjRlZGFkYjYzZmMwMjMzZWYwYWRmZTNlOGVhYzQ0NTM1In0.eyJhdWQiOiIxIiwianRpIjoiMmRkNDRkNjE5YjlhNmZiNjk3ZDBjMmY4ZDBiZWFkYTEwYTcxZTZmMzdjYTIxMTI2NGVkYWRiNjNmYzAyMzNlZjBhZGZlM2U4ZWFjNDQ1MzUiLCJpYXQiOjE1MzQ5NDcxMTMsIm5iZiI6MTUzNDk0NzExMywiZXhwIjoxNTY2NDgzMTEzLCJzdWIiOiI3NyIsInNjb3BlcyI6WyJjdXN0b21lciJdfQ.Tvv5vbxy-rkQrBq2sQFx5nfJT40vPpFJNrIC01k62zy0PQvUJdregoiLFp4tJfPfFvPnYWRb9b3ovXYyGmb-5du5cme0JV1wpjNQYZlpVMni_CfTUVg2JMqP62tylW_oI7nM1WK25xr2FOP1IrzqpLeYqsjwG0VideLgX2imsv-ZcHdi10c90ejPT4uCvnwvrhCR6gY9fPOApjJGhdJ6tJxa-gUP42LH0JdBrfgK45XAEKs_bXbgS5wwnR2b3R8PWX8_pCaxz0fBlna7M7X8BLGHY9kPWBStOfjocEFIJyH20gHSHYKViCgPK-i3gFVA5SA17mGNBUCYJQA3PGiEkVXM6V8td4VLJD-NUxXSvc7vWaESuXv2poE7g2tO90aFiyenXNmIJlGRqaFXLoevodIi78CYjjQMzv-TqUE7CfBsPpzIe32gcoopunecwKsuKNLgT6t5zsAYUp1pKMOA3WYnpJ4iu1x0AVvzckg549pfvVLPJktB5epZf11Araxs7nme9oIiBqwuPDFItlWh3LxtoC7-ajkstQQxFtO6K-dA2DHnEwBJPJbHbTnEhSDJDSdd2baZbF12Zz07tiHMxcB1Jtm7kb_zOWdxcpiNenYPd1R0EOi1aUC-aXa7qEypbj-Tt6LZajAw9eWg-OjASf_QhPHR5vu2-7qsxgUPBJc"
```

### HTTP Request
`GET api/manager/auth/login`

`HEAD api/manager/auth/login`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email_phone | string |  required  | 
    password | string |  required  | 

<!-- END_67882e62fab916565b529e72b8ab499d -->

<!-- START_a9e71091acc658ab0d9cc7660c60d109 -->
## Tạo lại mật khẩu

Khởi tạo lại mật khẩu sử dụng mã xác thực sms<br/>

> Example request:

```bash
curl -X GET "http://thegioitoc.vn/api/manager/auth/reset-password" \
-H "Accept: application/json" \
    -d "email_phone"="quod" \
    -d "sms_verify_code"="7953095" \
    -d "new_password"="quod" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://thegioitoc.vn/api/manager/auth/reset-password",
    "method": "GET",
    "data": {
        "email_phone": "quod",
        "sms_verify_code": 7953095,
        "new_password": "quod"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
"true|false"
```

### HTTP Request
`GET api/manager/auth/reset-password`

`HEAD api/manager/auth/reset-password`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email_phone | string |  optional  | 
    sms_verify_code | numeric |  required  | Must have a length between `100000` and `999999`
    new_password | string |  required  | Minimum: `6` Must match this regular expression: `/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/`

<!-- END_a9e71091acc658ab0d9cc7660c60d109 -->

#SMS Verify

Những request này không yêu cầu token ở header
<!-- START_59188d66c6a7e7fc39346bf986024393 -->
## Yêu cầu mã xác thực mới

Yêu cầu mã xác thực gửi qua sms<br/>
Tham số <strong>username</strong> có thể là <i>email</i> hoặc <i>số điện thoại</i>

> Example request:

```bash
curl -X GET "http://thegioitoc.vn/api/manager/sms-verify/new" \
-H "Accept: application/json" \
    -d "email_phone"="omnis" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://thegioitoc.vn/api/manager/sms-verify/new",
    "method": "GET",
    "data": {
        "email_phone": "omnis"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
"true"
```

### HTTP Request
`GET api/manager/sms-verify/new`

`HEAD api/manager/sms-verify/new`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email_phone | string |  required  | 

<!-- END_59188d66c6a7e7fc39346bf986024393 -->

<!-- START_1a79494997a53b1a28976cb1d3e20498 -->
## Kiểm tra mã xác thực

Kiểm tra mã xác thực nhận được qua sms

> Example request:

```bash
curl -X GET "http://thegioitoc.vn/api/manager/sms-verify/check" \
-H "Accept: application/json" \
    -d "sms_verify_code"="535863" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://thegioitoc.vn/api/manager/sms-verify/check",
    "method": "GET",
    "data": {
        "sms_verify_code": 535863
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
"true|false"
```

### HTTP Request
`GET api/manager/sms-verify/check`

`HEAD api/manager/sms-verify/check`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    sms_verify_code | numeric |  required  | Must have a length between `100000` and `999999`

<!-- END_1a79494997a53b1a28976cb1d3e20498 -->

#Salon

Những request này yêu cầu token ở header
<!-- START_52ddc63efd38ba249d272735cba7d8fb -->
## Lấy thông tin ngắn gọn*

Thông tin ngắn gọn của salon: ảnh đại diện, tên salon

> Example request:

```bash
curl -X GET "http://thegioitoc.vn/api/manager/salon/short-info" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://thegioitoc.vn/api/manager/salon/short-info",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
"{\n \"name\": \"Salon Tấn Can\",\n \"avatar\": \"http:\/\/mysite.com\/xyz.jpg\"\n}"
```

### HTTP Request
`GET api/manager/salon/short-info`

`HEAD api/manager/salon/short-info`


<!-- END_52ddc63efd38ba249d272735cba7d8fb -->

