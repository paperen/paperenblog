<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'module/post/common/fragment';
$route['404_override'] = '';

// 列排列
$route['column'] = 'module/post/common/display_set/column';

// 行排列
$route['row'] = 'module/post/common/display_set/row';

// 分頁
$route['page/(:num)'] = 'module/post/common/fragment/$1';

// 404
$route['404'] = 'module/post/common/not_found';

// 頂
$route['post/(:num)/ding'] = 'module/post/common/feedback/$1/ding';
// 踩
$route['post/(:num)/cai'] = 'module/post/common/feedback/$1/cai';
// 文章
$route['post/(:any)'] = 'module/post/common/single/$1';

// 發表評論
$route['comment'] = 'module/comment/common/add';
$route['commenttoken'] = 'module/comment/common/token';

// 文件
$route['file/(:num)'] = 'module/file/common/get/$1';

// 關於
$route['about'] = 'module/about/common/index';

// 作者
$route['author/(:any)'] = 'module/about/common/author/$1';

// 归档
$route['archive'] = 'module/post/common/archive';
$route['archive/category'] = 'module/post/common/archive_category';
$route['category/(:any)'] = 'module/post/common/archive_by_category/$1';
$route['category/(:any)/page/(:num)'] = 'module/post/common/archive_by_category/$1/$2';
$route['archive/(:num)'] = 'module/post/common/archive_by_year/$1';
$route['archive/(:num)/page/(:num)'] = 'module/post/common/archive_by_year/$1/$2';
$route['archive/(:num)/(:num)'] = 'module/post/common/archive_by_month/$1/$2';
$route['archive/(:num)/(:num)/page/(:num)'] = 'module/post/common/archive_by_month/$1/$2/$3';
$route['archive/(:num)/(:num)/(:num)'] = 'module/post/common/archive_by_day/$1/$2/$3';
$route['archive/(:num)/(:num)/(:num)/page/(:num)'] = 'module/post/common/archive_by_day/$1/$2/$3/$4';

// 標籤
$route['tag'] = 'module/tag/common/index';
$route['tag/(:any)'] = 'module/post/common/archive_by_tag/$1';
$route['tag/(:any)/page/(:num)'] = 'module/post/common/archive_by_tag/$1/$2';

// RSS
$route['rss'] = 'module/post/common/rss';

// admin
$route['logout'] = 'module/admin/user/common/logout';
$route['user_setting'] = 'module/admin/user/common/setting';
$route['manage'] = 'module/admin/user/common/panel';
$route['login'] = 'module/admin/login/common';

/* End of file routes.php */
/* Location: ./application/config/routes.php */