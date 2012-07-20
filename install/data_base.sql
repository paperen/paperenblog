--
-- 表的结构 `attachment`
--

CREATE TABLE IF NOT EXISTS `attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `path` varchar(50) NOT NULL,
  `suffix` varchar(10) NOT NULL COMMENT '后缀',
  `size` double(6,1) unsigned NOT NULL DEFAULT '0.0',
  `isimage` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `isthumbnail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是文章代表图像',
  `addtime` int(10) unsigned NOT NULL COMMENT '上传时间戳',
  `userid` mediumint(8) unsigned NOT NULL COMMENT '上传人ID',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='附件表';

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL COMMENT '类别名',
  `pid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `pidlevel` varchar(50) NOT NULL DEFAULT '0-' COMMENT '层级树',
  `ispublic` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可见',
  `userid` mediumint(8) unsigned NOT NULL COMMENT '用户ID',
  `isdefault` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是默认类别',
  PRIMARY KEY (`id`),
  UNIQUE KEY `category` (`category`),
  KEY `pid` (`pid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章类别';

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `postid` int(10) unsigned NOT NULL COMMENT '文章ID',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `author` varchar(20) NOT NULL DEFAULT '0' COMMENT '评论人昵称',
  `email` varchar(30) NOT NULL DEFAULT '0' COMMENT '评论人email',
  `url` varchar(50) NOT NULL DEFAULT '0' COMMENT '评论人网站',
  `commenttime` int(10) unsigned NOT NULL COMMENT '评论时间戳',
  `ispublic` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否通过审核',
  `content` text NOT NULL COMMENT '评论内容',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `isneednotice` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要有回复时邮件通知',
  `ip` int(10) unsigned NOT NULL COMMENT '评论人IP',
  PRIMARY KEY (`id`),
  KEY `postid` (`postid`,`userid`,`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='评论表';

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(20) NOT NULL COMMENT '配置项',
  `name` varchar(10) NOT NULL COMMENT '名称',
  `value` tinytext NOT NULL COMMENT '配置',
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='配置表';

-- --------------------------------------------------------

--
-- 表的结构 `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '链接名',
  `url` varchar(50) NOT NULL COMMENT 'URL',
  `email` varchar(35) NOT NULL DEFAULT '0' COMMENT '邮箱',
  `image` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '链接图片',
  `order` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `meta` varchar(255) NOT NULL DEFAULT '0' COMMENT '说明',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `image` (`image`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='链接表';

-- --------------------------------------------------------

--
-- 表的结构 `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `urltitle` varchar(50) NOT NULL COMMENT 'URL标题',
  `categoryid` mediumint(8) unsigned NOT NULL COMMENT '文章类别ID',
  `content` text NOT NULL COMMENT '文章内容',
  `authorid` mediumint(8) unsigned NOT NULL COMMENT '作者ID',
  `ispublic` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否已经发布',
  `click` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数',
  `good` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '顶次数',
  `bad` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '踩次数',
  `savetime` int(10) unsigned NOT NULL COMMENT '保存时间戳',
  `posttime` int(10) unsigned NOT NULL COMMENT '发布时间戳',
  `isdraft` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是草稿',
  `istrash` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否垃圾',
  PRIMARY KEY (`id`),
  KEY `categoryid` (`categoryid`),
  KEY `urltitle` (`urltitle`),
  KEY `ispublic` (`ispublic`),
  KEY `authorid` (`authorid`),
  KEY `istrash` (`istrash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表';

-- --------------------------------------------------------

--
-- 表的结构 `post_attachment`
--

CREATE TABLE IF NOT EXISTS `post_attachment` (
  `postid` int(10) unsigned NOT NULL COMMENT '文章ID',
  `attachmentid` int(10) unsigned NOT NULL COMMENT '附件ID',
  KEY `postid` (`postid`),
  KEY `attachmentid` (`attachmentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章附件关联表';

-- --------------------------------------------------------

--
-- 表的结构 `post_tag`
--

CREATE TABLE IF NOT EXISTS `post_tag` (
  `postid` int(10) unsigned NOT NULL COMMENT '文章ID',
  `tagid` int(10) unsigned NOT NULL COMMENT '标签ID',
  KEY `postid` (`postid`,`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章标签关联表';

-- --------------------------------------------------------

--
-- 表的结构 `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(15) NOT NULL COMMENT '标签',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='标签';

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL COMMENT '帐号',
  `url` varchar(50) NOT NULL DEFAULT '0' COMMENT '个人站点',
  `email` varchar(35) NOT NULL COMMENT '邮箱',
  `password` char(32) NOT NULL COMMENT '密码',
  `lastlogin` int(10) unsigned NOT NULL COMMENT '最近登录时间戳',
  `lastip` int(10) unsigned NOT NULL COMMENT '最近登录IP',
  `identity` enum('admin','author','reader') NOT NULL DEFAULT 'reader' COMMENT '身份',
  `role` mediumint(8) unsigned NOT NULL COMMENT '权限制权限值（2的幂）',
  `token` varchar(32) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表';