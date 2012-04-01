-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 04 月 01 日 13:13
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
  `addtime` int(10) unsigned NOT NULL COMMENT '上传时间戳',
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `post`
--

INSERT INTO `post` (`id`, `title`, `urltitle`, `categoryid`, `content`, `authorid`, `ispublic`, `click`, `good`, `bad`, `savetime`, `posttime`, `isdraft`) VALUES
(1, 'I am Paperen', 'i-am-paperen', 1, '<P>今天终于组建了paper的博客，因为目前整个博客的功能都是自己编的，所以安全性方面还是个未知数，如果某位仁兄发现了我这个博客哪处有BUG的话，麻烦给我发个邮件:)。</P>\r\n<P>对于paper的博客，我是不会考虑能获得什么盈利的了，肯定不会插入那些广告什么的来打乱自己网页的美观，我觉得开源真的很好，而任何东西一有商业的介入，一谈到钱就没了味道，我就是想用这样一个空间来锻炼一下自己，而重要的是可以与大家进行分享，包括自己的经验，包括自己生活上的。</P><P>“保持网络上的自己与现实的我一致性”，这句话是我在某个个人网站看到的，也是我比较赞同的一句话。虽然生活中我不太喜欢跟陌生人交流，虽然我是个内向的人，但是在这里我更愿意跟大家交流。?</P><!--more-->\r\n<P>不过目前这个博客还是略显幼稚，功能很基本，代码很片面……当然一开始是想直接用WordPress算了，这个功能强大的php博客（当然也是开源的），但是既然自己都把博客的代码写好，就放出来呗，东西都是慢慢改善的啦，我相信WordPress一开始的时候也是像我现在这个博客这样，功能不会太强大的吧……（顺便自我安慰一下）在此再声明一下，此博客上除一些转载的文章外其他文章都是我paperen写的啦，希望如果有想转载的跟我说声就行，不说也勉强没所谓……哈哈，因为我觉得被大家认同就是很很很幸福的事情了。还有 不要乱发广告 啦，不要 乱留网址 ，如果想要友情链接的话，email我吧，我很欢迎。</P>\r\n<P>慢慢完善，慢慢进步……正如我在today motto中写的那句：要学的东西还是很多很多，要走的路还是很长很长~~</P>\r\n<P>最后：欢迎来到paper的博客！</P>', 2, 1, 16, 1, 0, 1256464078, 1332349063, 0),
(2, '第一次面试', 'first-interview', 1, '<P>第一次面试经历真的挺丢人的……打算三点过去的，但是谁知道在我睡午觉的时候，那个公司的经理打电话来了，我还傻傻的说我刚才在睡觉（那天真的冻傻了……），还没过去就给别人一个不好的印象……看来自己习惯没有约束太久了……</P>\r\n<P>冲冲忙忙地赶过去，连电梯都玩我，15楼按不上，没法只能在14楼下……差不多3点终于到了，经理让我坐会，自己留意一下环境，他们的办公室是一个个房间形式的，有压迫感，不太宽敞，讲真不是很喜欢这种环境，但是没法，我是来面试的而已。</P><!--more-->\r\n<P>经理问了我些问题，关于自己的水平与优势，当然也苦笑一下刚才自己午觉的事情……之后就给我一个小任务，给他们网站后台添加上管理友情链接的功能，自我感觉不太难就直接上手了，不到五点就ok了，交作业后，经理就问了我学习上时间的事情，还有说了一下公司上下班的时间，明天就开始上班吧，自己不能以正常员工的身份，月薪最好只有9百多。之后我就走了，心情也不是特别的高兴，心里说：“哎！明天就不自由了……”，说实在还有些不习惯的。</P>\r\n<P>也想不到第一份工作就这样应聘上了，没太多的感觉，社会，工作，生活，人生……还是很漫长漫长的。</P>', 2, 1, 25, 0, 0, 1257091256, 1331349063, 0),
(3, '福尔摩斯第二部吐槽', 'sherlock-holmes-2-tucao', 1, '<p>盖·里奇携着他的福尔摩斯第二部终于来了！在翘首等待了两年之后，众多腐女小基都无法压抑内心的激动之情，纷纷大批杀向电影院，楼主作为唐尼大眼睛和裘德·洛的脑残粉，随着大军一起去围观基情。</p>\r\n<pre>//待归档列表\r\n$archiveList = Modules::run( ''doclist/archiveList'' );\r\n$data[''archiveList''] = $archiveList;\r\n\r\n//判断是否有录入权限\r\nif ( $this-&gt;permission-&gt;chkPermission(''input'') )\r\n{\r\n//录入入口\r\n$inputEnterance = Modules::run( ''entrance/input'' );\r\n$data[''inputEnterance''] = $inputEnterance;\r\n}\r\n</pre>\r\n<p>电影上映前，宣传造势就十分凶猛，预告片在上映前好几个月就一直在影院轮轴儿拼命播，各种海报横幅电子屏也是铺天盖地。在《新年前夜》里，纽约的时代广场上最闪耀的，居然是唐尼在海报上那张脸！宣传商是不是也是脑残粉啊，激动成这样，宣传大手笔啊！</p><!--more-->\r\n<p>撇开闪瞎钛金狗眼的基情，电影本身就已经非常优秀，悬疑情节设置精彩，剧情一直很引人入胜，129分钟不显得长。前段的赌场刺杀戏，动作戏份让人目不暇接，高速衔接眼花缭乱；火车上的枪战戏，智商和动作齐齐上阵，又不失幽默串场；而中段的爆破戏，更是用了慢速摄影，千金一掷做出来的视觉效果非常赞，超越《反抗军》的丛林枪战几千米，真是有史以来最漂亮的丛林枪战戏码！慢速爆破惊艳极了。而最后的瑞士雪山堡，那个景色真是漂亮，浮光掠影看到的白练般的瀑布，飞流直下三千尺的感觉瞬间出来了，电脑特效赞一个！单从摄影、剪辑、特效来说，该片完全满足一部娱乐大片应该给我们带来的期待！</p>\r\n\r\n<p>如果说第一部里面的基情还只是含含蓄蓄，第二部简直是公之于众啊！各种闪瞎眼！亮点频频，鉴定如下：</p>\r\n<p>1：从开场开始，我们就知道拦在华生和福尔摩斯之间的女人不会有好下场。尽管唐尼使尽浑身解数，你也救不了第三者啊！尽管第三者是美女Rachel McAdams，命运一样凄凉。之后的华生老婆更是个龙套，脸都没露几回，就被唐尼一脚踹下了火车，落得和裸体的Steven Fry同处一室的下场！而至于女主龙纹身劳米·拉佩斯，就更不用提了，哥哥最后没救着，也没抱得美男归，真是惨到家了。</p>\r\n<p>2：紧接着，唐尼就投向了洛的怀抱，只不过那个伪装术的服装，是不是在卖萌啊喂！小肚腩和双色装，大叔你以为你是Kill Bill里的乌玛·瑟曼啊！卖萌就卖萌，还要卖两次！最后片尾那一次，真是萌到骨子里！预示还有第三部呀喂！听到华生嘴里吐出"Oh how I''ve missed you Holmes!" 我的心早将这句听成了"Oh how I''ve missed you Ho~~ho~homo~"（我想死你了你个死基佬！）</p>\r\n<p>3：华生结婚这一场戏，更是展现了福尔摩斯这位腹黑攻的忠犬属性，（其实真的攻受不明，因为有时候明显又是个傲娇受！）婚前的单身派对结束后，竟然按时送着醉醺醺的新郎赶到了结婚现场！手拉着手儿一块儿走进礼堂，特别是看到新郎新娘幸福的瞬间，唐尼转身一回眸，那个眼神，分明是在泣血！泣血！</p>\r\n<p>4：估计是华生那句：Nobody wants to die alone（没人想孤独终老）刺激到了福尔摩斯的心，火车上，福尔摩斯终于忍不住内心的寂寞，决定抢亲，不仅男扮女装，踹走新娘，更是和华生相拥厮磨，上下其手。华生一把撕掉了福尔摩斯的衣服，直接扑进了他的双腿之中的温柔乡，哎呀那不是传教士姿么？几近全裸的唐尼邀请裘德洛躺在地上，和他一起赤裸共枕，哎呀那不是背入式么？我擦这尺度，让观众迎风鼻血三千米啊！确定电影分级正确吗？这真的不是GV么大丈夫？而当云雨过后，唐尼更是说，咱们这relationship（恋情）~~~裘德洛柳眉倒竖：relationship~~?! 唐尼更是没下限说：那就partnership（奸情）吧~~~鄙人当场绝倒！</p>\r\n<p>5：当唐尼终于劫走裘德洛，两人在马车上你侬我侬，这也就罢了，唐尼还非要质问裘德洛，喏，现在和我在一起，开心么？（这是多么严重的一种攀比行为啊！非要和华生老婆比高低排次序！）傲娇华生别扭：伦家不说嘛~~唐尼非要接着问，你就说你开心不开心嘛，开心不开心嘛！（喂你们昨晚到底是要有多爽？！）</p>\r\n<p>6：三个镜头彻底出卖两人的爱情，第一个是婚礼唐尼的那一回眸，第二个则是火车上洛的那句：你不能这么丢下我！悲痛欲绝宛若丧偶，自责苦痛不愿独活的感觉，一个镜头就出来了！裘德洛表演是要有多赞！第三个镜头则是最后唐尼纵身跳下瀑布，那慌乱之中的一个回眸，忽闪忽闪的眼睛似乎要溢出泪花，大眼睛唐尼你自重！那眼神似乎有千言万语，又似乎只化作了一句：爱人，珍重！</p>\r\n<p>所谓一眼万年，写到这里笔者不禁想欢呼：爱情万岁！</p>\r\n<p>7：本以为王尔德和波西的再次重逢一定会有强烈的化学反应，没想到果然那已经是过去式，华生和福尔摩斯的基情才是正途！我们才华横溢的Fry巨人居然在电影里露起了白花花的肚子（和下体）！给了华生老婆致命一击！这可怜的女娃娃估计从此对男体有了阴影。电影中的基情，在后期采访里记者也问到这一点，唐尼就直接说两人在宾馆里经常一起滚床单互穿彼此的衣服啊什么的都习惯了。瀑布汗。Steven Fry请目送两人直到婚礼殿堂吧！</p>\r\n<p>8：全片最最最最最萌的一段：瑞士雪山堡，唐尼伸手向裘德洛求共舞，裘德洛瞥了他一眼说："哼还以为你不准备邀人家呢！"-----又傲娇又老夫老妻好吗！紧接着唐尼问，"谁教你跳舞的啊？"裘德洛说，"你咯！"（画外音"死鬼！"）跳舞就跳舞吧，你们还公开讨论攻受问题，福尔摩斯对华生说"you should put your hands on my shoulder",（手搭我肩上吧亲爱的），华生则反诘说你咋不搭我肩上啊？天啊，你俩抢着当小攻男方啊，这是要让观众喷鼻血吗！！！真的不是我爱脑补，是你们直勾勾坦荡荡说情话啊！！！</p>\r\n<p>如果奥斯卡有年度最佳基友奖，年度最佳搞基奖，年度最爱搞基爱情片，我敢说非本片莫属！没有真枪实弹的GV情节，却胜似那些情节！一颦一笑都是爱，举手投足总是情！说的就是你俩！万人迷的一对好！基！友！</p>\r\n<p>PS：</p>\r\n<p>看到短评里有人说要腐女基佬们睁开狗眼不看基情看剧情，劳纸负责的告诉此人剧情很赞大家都看得见不用你来指导！倒是某些恐同犯不长眼非要看搞基片自虐！本来就很基还不让人说吗？！有人说盖里奇被麦当娜搞了终于江郎才尽了。尽尼玛！这部里面那些帅气的剪辑和眼花缭乱的动作编排你以为是天上掉下来的吗？！------------不吼这几句不爽！特地补上！</p>', 1, 1, 5, 3, 1, 1324862441, 1332560063, 0);

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

--
-- 转存表中的数据 `post_attachment`
--


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
(2, 5),
(3, 6),
(3, 7),
(3, 8);

-- --------------------------------------------------------

--
-- 表的结构 `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` varchar(15) NOT NULL COMMENT '标签',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='标签' AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `tag`
--

INSERT INTO `tag` (`id`, `tag`) VALUES
(1, 'paperen'),
(2, '博客'),
(3, '面试'),
(4, '第一次'),
(5, '生活'),
(6, '福尔摩斯'),
(7, '吐槽'),
(8, '影评');

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
