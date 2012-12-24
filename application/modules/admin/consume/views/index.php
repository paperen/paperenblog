<?php $this->load->module('admin/header/common/index'); ?>
<?php $this->load->module('admin/sidebar/common/index'); ?>
<!-- main -->
<div class="main span10">
	<h3><?php echo $year; ?>年<?php echo $month; ?>月 消费统计图表</h3>
	<hr>
	<form class="form-search well" method="get">
		<h4>查询条件</h4>
		<label>
		<select name="year" class="input-small">
			<?php foreach( $options_year as $single ) { ?>
			<option value="<?php echo $single; ?>"<?php if( $single == $year ){ ?> selected="true"<?php } ?>><?php echo $single; ?></option>
			<?php } ?>
		</select>
		年
		</label>
		<label>
		<select name="month" class="input-small">
			<?php foreach( $options_month as $single ) { ?>
			<option value="<?php echo $single; ?>"<?php if( $single == $month ){ ?> selected="true"<?php } ?>><?php echo $single; ?></option>
			<?php } ?>
		</select>
		月
		</label>
		<button type="submit" class="btn">查询</button>
	</form>
	<div class="row-fluid">
		<div class="span6">
			<h4>饼图</h4>
			<?php if( $pie_data ) { ?>
			<div><?php open_flash_chart_object( 400, 350, base_url("module/admin/consume/common/pie/{$year}/{$month}"), FALSE, base_url('js') . '/' ); ?>
			</div><hr>
			<?php } ?>
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>消费类别</th>
						<th>占百分比（%）</th>
						<th>总金额（元）</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if( $pie_data ) {
						foreach( $pie_data as $single ) { ?>
					<tr>
						<th><?php echo $single['type']; ?></th>
						<td><?php echo $single['percent']; ?></td>
						<td><?php echo $single['money']; ?></td>
					</tr>
					<?php
						}
					} else {
					?>
					<tr>
						<td colspan="3">
							<div class="alert alert-info">
								<h4>没有任何数据</h4>
							</div>
						</td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td colspan="2"><strong>总共</strong></td>
						<td><strong><?php echo $total; ?></strong></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="span6">
			<h4>柱状图</h4>
			<?php if( $bar_data ) { ?>
			<div><?php open_flash_chart_object( 400, 350, base_url("module/admin/consume/common/bar/{$year}/{$month}"), FALSE, base_url('js') . '/' ); ?></div><hr>
			<?php } ?>
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>日</th>
						<th>占百分比（%）</th>
						<th>总金额（元）</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if( $bar_data ) {
						foreach( $bar_data as $single ) {
					?>
					<tr>
						<th><?php echo $single['day']; ?></th>
						<td><?php echo $single['percent']; ?></td>
						<td><?php echo $single['money']; ?></td>
					</tr>
					<?php
						}
					} else {
					?>
					<tr>
						<td colspan="3">
							<div class="alert alert-info">
								<h4>没有任何数据</h4>
							</div>
						</td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td colspan="2"><strong>总共</strong></td>
						<td><strong><?php echo $total; ?></strong></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- main -->
<?php $this->load->module('admin/footer/common/index'); ?>