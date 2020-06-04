# API

| 接口 | 方法 | 分组 | 说明 |
| ----- | ----- | ---- | ----- |
| [/oauth2/token](#api-1-12) | POST | OAuth | 获取access_token |
| [/api/user](#api-2-21) | GET | 用户 | 获取用户信息 |
| [/api/user](#api-2-22) | POST | 用户 | 用户注册 |
| [/api/user](#api-2-23) | PUT | 用户 | 修改用户信息 |
| [/api/user/role](#api-2-24) | GET | 用户 | 获取用户角色 |
| [/api/user/permission](#api-2-25) | GET | 用户 | 获取用户权限 |
| [/api/user/rolePermission](#api-2-26) | GET | 用户 | 获取用户角色和权限 |
| [/api/sms/code](#api-5-51) | POST | 短信 | 发送验证码 |
| [/api/sms/code](#api-5-52) | PUT | 短信 | 验证验证码 |
| [/api/email](#api-6-61) | POST | 邮件 | 发送邮件 |
| [/api/file](#api-7-71) | POST | 文件 | 上传文件 |
| [/api/log](#api-8-81) | POST | 日志 | 记录日志 |

## 0. 返回码

| 返回码 | 说明 |
| ----- | ----- |
| 0 | 成功 |
| 1 | 失败 |
| 1000 | 错误请求 |
| 1001 | 未授权 |
| 1003 | 禁止访问 |
| 1004 | 未找到资源 |

## 1. 登陆

登陆采用 OAuth2 协议，该协议为用户资源的授权提供了一个安全的、开放而又简易的标准。OAuth2 允许第三方开发者在用户授权的前提下访问在用户在 UCenter 存储的各种信息。

*注：关于OAuth2 的详细介绍参考: [理解OAuth 2.0](http://www.ruanyifeng.com/blog/2014/05/oauth_2_0.html)*

![图片](/packages/flatdoc/images/ucenter-oauth2.0.png)


### 1.1 统一身份认证

+ 将用户引导到 《账号中心》登录页面上。如下链接：

```
https://ucenter.xxxxxxx.com/oauth2/authorize?
client_id={client_id}
&redirect_uri={redirect_uri}
[&scope={scope}]
&response_type=code
&from=xxx
```
+ 如用户未登录《账号中心》，跳转到授权登录页面

![图片](/packages/flatdoc/images/ucenter-oauth-login.png?v=0.2)

+ 用户授权通过，《账号中心》 会将**授权码**回传给应用在 《账号中心》 注册的回调地址`（http://xxx.com/callback?code=xxx）`，应用直接获取授权码 `code` 即可。

+ 应用向 《账号中心》的 Token Endpoint 发送请求：

```
https://ucenter.xxxxxxx.com/oauth2/token?
client_id={client_id}
&client_secret={client_secret}
&grant_type=authorization_code
&redirect_uri={redirect_uri}
&code={code}
```

**Response:（点击代码展开）**
```js
{
    "token_type": "Bearer",
    "expires_in": 7200,
    "access_token": "AynyRZKKskMBs4ONjOHUecgAyM2rqpvToo0QTXPA",
    "refresh_token": "mcQNthVcEJpn09MObyxXerv4tiQq9I2z85NAe2ye"
}
```

如果请求异常返回格式如下：

```js
{
    "error" => "invalid_request",
    "message" => "error message",
    "hint" => "Cannot decrypt the authorization code"
}
```



+ 获取到`access_token `  就可以使用`access_token` 访问受保护的资源了

  `https://ucenter.xxxxxxx.com/api/user?access_token={access_token}`

### 1.2 授权模式

* 客户端必须得到用户的授权(authorization_grant)，才能获得令牌(access_token)。《账号中心》提供三种授权方式:

##### 1.2.1 授权码模式(authorization_code)
* **统一身份认证**中用到的就是授权码模式，开发者填写对应的`client_id`, `client_secret`, `redirect_uri`即可调用统一身份认证，
* 上述**1.1**即使用的是授权码模式。

##### 1.2.3 密码模式(password)
* 密码模式用于非PC或WAP端的身份认证，如：APP登陆。
* *处于用户信息安全方面考虑密码模式暂不对外开放*

`POST /oauth2/token`

| 参数 | 类型 | 必填 | 说明 |
| ----- | ----- | ---- | ----- |
| client_id | string | Y | client_id |
| client_secret | string | Y | client_secret |
| grant_type | string | Y | 值 = password |
| username | string | Y | 用户名/手机号/邮箱 |
| password | string | Y | 密码 |

**Response:（点击代码展开）**
```js
{
    "token_type": "Bearer",
    "expires_in": 7200,
    "access_token": "AynyRZKKskMBs4ONjOHUecgAyM2rqpvToo0QTXPA",
    "refresh_token": "mcQNthVcEJpn09MObyxXerv4tiQq9I2z85NAe2ye"
}
```

##### 1.2.4 客户端模式(client_credentials)
* 客户端模式用于未登陆时的接口授权，此时只能访问不需要登陆的接口，如：注册时的发送验证码，一些公共接口会使用客户端模式。

`POST /oauth2/token`

| 参数 | 类型 | 必填 | 说明 |
| ----- | ----- | ---- | ----- |
| client_id | string | Y | client_id |
| client_secret | string | Y | client_secret |
| grant_type | string | Y | 值 = client_credentials |

**Response:（点击代码展开）**
```js
{
    "token_type": "Bearer",
    "expires_in": 7200,
    "access_token": "AynyRZKKskMBs4ONjOHUecgAyM2rqpvToo0QTXPA"
}
```

## 2. 用户

### 2.1 获取用户信息
`GET   /api/user`

| 参数 | 类型 | 必填 | 说明 |
| ----- | ----- | ---- | ----- |
| acess_token | string | Y | access_token |

**Response:（点击代码展开）**
```js
{
    "code": 0,
    "message": "获取用户信息成功",
    "data": {
          "id" => '用户ID',
          "username" => "用户名",
          "nickname" => "昵称",
          "email" => "邮箱",
          "phone" => "电话",
          "description" => "账号描述",
          "user_type" => "用户类型",
          "created_at" => "账号创建时间",
          "birth" => "生日",
          "school_id" => "所在学校id，没有即不会出现该字段",
          "school_name" => "所在学校名称",
          "curr_grade" => "当前所在年级"
        }
    }
}
```

### 2.2 用户注册
`POST   /api/user`

| 参数 | 类型 | 必填 | 说明 |示例值 |
| ----- | ----- | ---- | ----- | ----- |
| grade_num | string | Y | 年级 |7|
| id_card | string | Y | 身份证号 |44010019930711508X|
| name | string | Y | 真实姓名 |秦白萱|
| password | string | Y | 密码 |123456|
| password1 | string | Y | 手机号 |18202890000|
| school_id | string | Y | 学校id,通过学校接口获取学校 |13696|
| user_type | string | Y | 用户类型 1.学生 2.教师 |1|

**Response:（点击代码展开）**
```js
{
    "code": 0,
    "message": "操作成功",
    "data":null
}
```
### 2.3 修改用户信息

### 2.4 获取用户角色

### 2.5 获取用户权限

### 2.6 获取用户角色和权限


## 3. 角色

## 4. 权限

## 5. 短信

### 5.1 发送验证码

### 5.2 验证验证码



## 6. 邮件

### 6.1 发送邮件


## 7. 文件

### 7.1 上传文件

## 8. 日志

### 8.1 记录日志

