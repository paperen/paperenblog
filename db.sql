-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 03 月 29 日 13:13
-- 服务器版本: 5.0.83
-- PHP 版本: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `simblog`
--

-- --------------------------------------------------------

--
-- 表的结构 `attachment`
--

CREATE TABLE IF NOT EXISTS `attachment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `path` varchar(30) NOT NULL,
  `suffix` varchar(10) NOT NULL COMMENT '后缀',
  `size` double(6,1) unsigned NOT NULL default '0.0',
  `isimage` tinyint(1) unsigned NOT NULL default '1',
  `isthumbnail` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否是文章代表图像',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `attachment`
--


-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `category` varchar(30) NOT NULL COMMENT '类别名',
  `pid` mediumint(8) NOT NULL default '0' COMMENT '父级ID',
  `ispublic` tinyint(1) NOT NULL default '1' COMMENT '是否可见',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章类别' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `category`, `pid`, `ispublic`) VALUES
(1, '吹水', 0, 1),
(2, 'PHP', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `postid` int(10) unsigned NOT NULL COMMENT '文章ID',
  `userid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `author` varchar(20) default NULL COMMENT '评论人昵称',
  `authoremail` varchar(30) default NULL COMMENT '评论人email',
  `authorurl` varchar(50) default NULL COMMENT '评论人网站',
  `commenttime` int(10) unsigned NOT NULL COMMENT '评论时间戳',
  `ispublic` tinyint(1) unsigned NOT NULL default '1' COMMENT '是否通过审核',
  `content` text NOT NULL COMMENT '评论内容',
  `pid` int(10) unsigned NOT NULL default '0' COMMENT '父级ID',
  `isneednotice` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否需要有回复时邮件通知',
  `authorip` int(10) unsigned NOT NULL COMMENT '评论人IP',
  PRIMARY KEY  (`id`),
  KEY `postid` (`postid`,`userid`,`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='评论表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `comment`
--

INSERT INTO `comment` (`id`, `postid`, `userid`, `author`, `authoremail`, `authorurl`, `commenttime`, `ispublic`, `content`, `pid`, `isneednotice`, `authorip`) VALUES
(1, 1, 0, '肥仔聪', 'fatboy@163.com', 'fatboy.com', 1257091200, 1, '<strong>我们理工</strong>类的面试应该都要解答一些实际而又基础的问题吧，如果问我为什么污水口的盖是圆的话，我真不太会了…', 0, 0, 3071236922),
(2, 2, 0, 'mcc', '307133793@qq.com', '', 1260794169, 1, '虽然我专业不是计算机的，但是我对web开发发面还是很感兴趣的，对于兴趣嘛，社会总是残酷的，我觉得我们', 0, 0, 3051216922),
(3, 1, 2, '', 'paperen@gmail.com', 'iamlze.cn', 1261883250, 1, '嗯…应该是与判断邮箱正确性的正则有关…算了，懒得改了，忽略你的邮箱', 1, 0, 3071234922);

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `key` varchar(20) NOT NULL COMMENT '配置项',
  `value` tinytext NOT NULL COMMENT '配置',
  PRIMARY KEY  (`id`),
  KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `config`
--


-- --------------------------------------------------------

--
-- 表的结构 `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL COMMENT '链接名',
  `url` varchar(50) NOT NULL COMMENT 'URL',
  `email` varchar(35) default NULL COMMENT '邮箱',
  `image` int(10) unsigned NOT NULL default '0' COMMENT '链接图片',
  `order` mediumint(8) unsigned NOT NULL default '0' COMMENT '排序',
  `meta` tinytext COMMENT '说明',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `image` (`image`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='链接表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `link`
--

INSERT INTO `link` (`id`, `name`, `url`, `email`, `image`, `order`, `meta`) VALUES
(1, 'catman的博客', 'catman.c.blog.163.com', 'catman@163.com', 0, 0, '有为的青年'),
(2, '流风大侠', 'www.stormcn.cn', 'stormcn@163.com', 0, 0, '电脑报的刘版主');

-- --------------------------------------------------------

--
-- 表的结构 `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `urltitle` varchar(50) NOT NULL COMMENT 'URL标题',
  `categoryid` mediumint(8) unsigned NOT NULL COMMENT '文章类别ID',
  `content` text NOT NULL COMMENT '文章内容',
  `authorid` mediumint(8) unsigned NOT NULL COMMENT '作者ID',
  `ispublic` tinyint(1) unsigned NOT NULL default '1' COMMENT '是否已经发布',
  `click` smallint(6) unsigned NOT NULL default '0' COMMENT '浏览数',
  `good` smallint(4) unsigned NOT NULL default '0' COMMENT '顶次数',
  `bad` smallint(4) unsigned NOT NULL default '0' COMMENT '踩次数',
  `savetime` int(10) unsigned NOT NULL COMMENT '保存时间戳',
  `posttime` int(10) unsigned NOT NULL COMMENT '发布时间戳',
  `isdraft` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否是草稿',
  PRIMARY KEY  (`id`),
  KEY `categoryid` (`categoryid`),
  KEY `urltitle` (`urltitle`),
  KEY `author` (`authorid`),
  KEY `ispublic` (`ispublic`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `post`
--

INSERT INTO `post` (`id`, `title`, `urltitle`, `categoryid`, `content`, `authorid`, `ispublic`, `click`, `good`, `bad`, `savetime`, `posttime`, `isdraft`) VALUES
(1, 'I am Paperen', 'i-am-paperen', 1, '<P>今天终于组建了paper的博客，因为目前整个博客的功能都是自己编的，所以安全性方面还是个未知数，如果某位仁兄发现了我这个博客哪处有BUG的话，麻烦给我发个邮件:)。</P>\r\n<P>对于paper的博客，我是不会考虑能获得什么盈利的了，肯定不会插入那些广告什么的来打乱自己网页的美观，我觉得开源真的很好，而任何东西一有商业的介入，一谈到钱就没了味道，我就是想用这样一个空间来锻炼一下自己，而重要的是可以与大家进行分享，包括自己的经验，包括自己生活上的。</P><P>“保持网络上的自己与现实的我一致性”，这句话是我在某个个人网站看到的，也是我比较赞同的一句话。虽然生活中我不太喜欢跟陌生人交流，虽然我是个内向的人，但是在这里我更愿意跟大家交流。?</P><!--more-->\r\n<P>不过目前这个博客还是略显幼稚，功能很基本，代码很片面……当然一开始是想直接用WordPress算了，这个功能强大的php博客（当然也是开源的），但是既然自己都把博客的代码写好，就放出来呗，东西都是慢慢改善的啦，我相信WordPress一开始的时候也是像我现在这个博客这样，功能不会太强大的吧……（顺便自我安慰一下）在此再声明一下，此博客上除一些转载的文章外其他文章都是我paperen写的啦，希望如果有想转载的跟我说声就行，不说也勉强没所谓……哈哈，因为我觉得被大家认同就是很很很幸福的事情了。还有 不要乱发广告 啦，不要 乱留网址 ，如果想要友情链接的话，email我吧，我很欢迎。</P>\r\n<P>慢慢完善，慢慢进步……正如我在today motto中写的那句：要学的东西还是很多很多，要走的路还是很长很长~~</P>\r\n<P>最后：欢迎来到paper的博客！</P>', 2, 1, 16, 1, 0, 1256464078, 1332349063, 0),
(2, '第一次面试', 'first-interview', 1, '<P>第一次面试经历真的挺丢人的……打算三点过去的，但是谁知道在我睡午觉的时候，那个公司的经理打电话来了，我还傻傻的说我刚才在睡觉（那天真的冻傻了……），还没过去就给别人一个不好的印象……看来自己习惯没有约束太久了……</P>\r\n<P>冲冲忙忙地赶过去，连电梯都玩我，15楼按不上，没法只能在14楼下……差不多3点终于到了，经理让我坐会，自己留意一下环境，他们的办公室是一个个房间形式的，有压迫感，不太宽敞，讲真不是很喜欢这种环境，但是没法，我是来面试的而已。</P><!--more-->\r\n<P>经理问了我些问题，关于自己的水平与优势，当然也苦笑一下刚才自己午觉的事情……之后就给我一个小任务，给他们网站后台添加上管理友情链接的功能，自我感觉不太难就直接上手了，不到五点就ok了，交作业后，经理就问了我学习上时间的事情，还有说了一下公司上下班的时间，明天就开始上班吧，自己不能以正常员工的身份，月薪最好只有9百多。之后我就走了，心情也不是特别的高兴，心里说：“哎！明天就不自由了……”，说实在还有些不习惯的。</P>\r\n<P>也想不到第一份工作就这样应聘上了，没太多的感觉，社会，工作，生活，人生……还是很漫长漫长的。</P>', 2, 1, 25, 0, 0, 1257091256, 1331349063, 0);

-- --------------------------------------------------------

--
-- 表的结构 `post_tag`
--

CREATE TABLE IF NOT EXISTS `post_tag` (
  `postid` int(10) unsigned NOT NULL COMMENT '文章ID',
  `tagid` int(10) unsigned NOT NULL COMMENT '标签ID',
  KEY `postid` (`postid`,`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章标签关联表';

--
-- 转存表中的数据 `post_tag`
--

INSERT INTO `post_tag` (`postid`, `tagid`) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(2, 5);

-- --------------------------------------------------------

--
-- 表的结构 `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` varchar(15) NOT NULL COMMENT '标签',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='标签' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `tag`
--

INSERT INTO `tag` (`id`, `tag`) VALUES
(1, 'paperen'),
(2, '博客'),
(3, '面试'),
(4, '第一次'),
(5, '生活');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(25) NOT NULL COMMENT '帐号',
  `password` char(32) NOT NULL COMMENT '密码',
  `lastlogin` int(10) unsigned NOT NULL COMMENT '最近登录时间戳',
  `lastip` int(10) unsigned NOT NULL COMMENT '最近登录IP',
  `identity` enum('admin','author','reader') NOT NULL default 'reader' COMMENT '身份',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `lastlogin`, `lastip`, `identity`) VALUES
(1, '管理员', '64a69ea9e9df5a1f24c8e5abf926b4eb', 1289792105, 3071236922, 'admin'),
(2, 'paperen', '64a69ea9e9df5a1f24c8e5abf926b4eb', 1289792105, 3071236922, 'author');
