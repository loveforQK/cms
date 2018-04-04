# 简介
使用Yii2 基础版框架、bootstrap、百度编辑器等组件开发的CMS发布工具，模板数据结合渲染生成静态页面到html目录，站点域名可以直接指向到html目录。
# admin目录
存放yii2框架文件、后台管理模块的文件
# html目录
发布对外的站点资源目录
# 模板开发目录
admin/views/template（可根据yii框架模板目录设置调整）
# Nginx配置
```
server{
	listen 80;

	server_name 域名;

	root  /opt/htdocs/项目/html;

	index index.html index.htm;

	location /admin/assets {
		alias /opt/htdocs/项目/admin/web/assets;
	}

	location /admin/static {
		alias /opt/htdocs/项目/admin/web/static;
	}

	location /admin {
	     rewrite ^/(.*)$ /index.php/$1 last;
	}

	location /index.php {
	     root /opt/htdocs/项目/api/web;
	     include        fastcgi_params;
	     fastcgi_pass   127.0.0.1:9000;
	     fastcgi_index  index.php;
	     fastcgi_param  SCRIPT_FILENAME /opt/htdocs/项目/admin/web/index.php;
	}
}
```