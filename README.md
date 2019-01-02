# yii2-api-base

### 一、文档。

1. [简介](docs/简介.md)
2. [接口规范](docs/接口规范.md)
2. [使用方式和Demo](docs/使用方式和Demo.md)
2. [小部件](docs/小部件.md)
2. [代码生成](docs/代码生成.md)
2. [错误处理](docs/错误处理.md)
2. [更新](docs/更新.md)

### 二、简介。

1. 功能
    1. 参照MVC思想封装了一种定义接口返回字段的方式，同时籍由此推广一下YII中列表和模型的返回格式[【参考Wiki】](https://c.ethercap.com/pages/viewpage.action?pageId=24019061)

### 三、命名空间

```
ethercap\apiBase
```

### 四、安装：

```
- composer require ethercap/apiBase:@dev
```

### 功能

- 接口规范
    - model和dataProvider的特定返回形式。
    - 提供block和非block的的错误处理形式，兼容sysMsg。
    - 可选择返是否回validator信息用于前端自动化校验。
- 代码生成
    - 完整的gii generator。

### 特点

- 为配置方便将配置集成在bootstrap中(_功能稳定后可以提供一个没有自动配置的版本_)。
- 接近YII2内部的render页面的调用方式。
- 和DetailView完成一致的Column写法
- 提供ListApi和DetailApi两个小部件用于构建接口的返回
- 支持当前规范的同时也兼容既有的接口（K=>V）的返回方式，按需使用

### 1月2日更新
- 修改sort的返回为当前生效的sorter
- 去掉links的默认配置
- 增加直接调用serializer获取model可用信息的的示例代码

