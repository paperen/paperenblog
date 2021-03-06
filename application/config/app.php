<?php

/**
 * 2012-3-18 17:02:00
 * 应用配置
 * @author paperen<paperen@gmail.com>
 * @link http://iamlze.cn
 * @version 0.0
 * @package paperenblog
 * @subpackage
 */

// 主题
$config['theme'] = 'paperen';
// 站点名
$config['sitename'] = 'paperen博客';
// 站點描述
$config['description'] = '這是paperen的博客，對了，本站不完全支持IE6、7（並非歧視）';
// 每页显示条数
$config['per_page'] = 8;
// 上傳根目錄
$config['upload_path'] = './upload/';
// 評論直接發佈
$config['comment_ispublic'] = TRUE;
// 評論需要郵件提醒
$config['comment_isneednotice'] = TRUE;
// 博客使用郵箱
$config['blog_email'] = 'paperen@163.com';

// ICP
$config['ICP'] = 'http://www.miibeian.gov.cn/';
// 微博
$config['weibo'] = 'http://www.weibo.com/paperen';

// 微博授权
$config['weibo_akey'] = 'xxxxx';
$config['weibo_skey'] = 'xxxxx';
$config['weibo_callback'] = 'http://iamlze.cn/weibo_callback';

// end of app