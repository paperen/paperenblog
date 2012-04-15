<?php $this->load->module( 'header/common', array( 'about', '关于博客' ) ); ?>
<div class="span9 main">

	<div class="about">
		<div class="row-fluid">
			<div class="span2 about-attr">
				<a href="#" class="thumbnail"><img src="http://localhost/ci-paperen/image/paperen.jpg" alt=""></a>
				<ul class="vote">
					<li>文章 <strong><a href="#">65</a></strong></li>
				</ul>
			</div>
			<div class="span10 about-entry">
				<h2>Paperen <i class="icon-user"></i></h2>
				<div class="span3">
					<h3>職業</h3>
					<ul>
						<li><a href="#">PHP Coder</a></li>
					</ul>
				</div>
				<div class="span4">
					<h3>聯繫我</h3>
					<p>paperenXgmail.com <small>[X=@]</small></p>
				</div>
				<div class="span3">
					<h3>出沒領域</h3>
					<ul>
						<li><a href="#">新浪微博</a></li>
						<li><a href="#">豆瓣</a></li>
					</ul>
				</div>
				<div class="span11">
					<h3>自我介紹</h3>
					<pre class="prettyprint linenums"><span style="color: #000000">
<span style="color: #0000BB">&lt;?php<br></span><span style="color: #007700">class&nbsp;</span><span style="color: #0000BB">Paperen&nbsp;</span><span style="color: #007700">extends&nbsp;</span><span style="color: #0000BB">Chinese<br></span><span style="color: #007700">{<br>&nbsp;&nbsp;&nbsp;&nbsp;function&nbsp;</span><span style="color: #0000BB">From</span><span style="color: #007700">()&nbsp;{<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return&nbsp;</span><span style="color: #DD0000">'广东新会'</span><span style="color: #007700">;<br>&nbsp;&nbsp;&nbsp;&nbsp;}<br>&nbsp;&nbsp;&nbsp;&nbsp;function&nbsp;</span><span style="color: #0000BB">Skills</span><span style="color: #007700">()&nbsp;{<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return&nbsp;</span><span style="color: #DD0000">'PHP,JQuery,CSS'</span><span style="color: #007700">;<br>&nbsp;&nbsp;&nbsp;&nbsp;}<br>&nbsp;&nbsp;&nbsp;&nbsp;function&nbsp;</span><span style="color: #0000BB">Hobby</span><span style="color: #007700">()&nbsp;{<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return&nbsp;</span><span style="color: #DD0000">'足球,吉他,音乐,WEB前端设计'</span><span style="color: #007700">;<br>&nbsp;&nbsp;&nbsp;&nbsp;}<br>&nbsp;&nbsp;&nbsp;&nbsp;function&nbsp;</span><span style="color: #0000BB">Graduation</span><span style="color: #007700">()&nbsp;{<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return&nbsp;</span><span style="color: #DD0000">'新会一中,青岛理工'</span><span style="color: #007700">;<br>&nbsp;&nbsp;&nbsp;&nbsp;}<br>&nbsp;&nbsp;&nbsp;&nbsp;function&nbsp;</span><span style="color: #0000BB">Tags</span><span style="color: #007700">()&nbsp;{<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return&nbsp;</span><span style="color: #DD0000">'80后,宅'</span><span style="color: #007700">;<br>&nbsp;&nbsp;&nbsp;&nbsp;}<br>}<br></span><span style="color: #0000BB">$paperen&nbsp;</span><span style="color: #007700">=&nbsp;new&nbsp;</span><span style="color: #0000BB">Paperen</span><span style="color: #007700">();<br></span><span style="color: #0000BB">$paperen</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">sayHello</span><span style="color: #007700">();<br></span><span style="color: #0000BB">?&gt;</span>
</span>
					</pre>
				</div>
			</div>
			<div class="c"></div>

		</div>
	</div>

</div>
<script>
$(function(){
	prettyPrint();
});
</script>
<?php $this->load->module( 'sidebar/common', array( ) ); ?>
<?php $this->load->module( 'footer/common', array( ) ); ?>