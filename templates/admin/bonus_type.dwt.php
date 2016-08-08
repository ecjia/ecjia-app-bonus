<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.bonus_type.type_list_init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a  class="btn data-pjax" href="{$action_link.href}" id="sticky_a" style="float:right;margin-top:-3px;"><i class="fontello-icon-plus"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>

<div class="row-fluid batch" >
	<div class="choose_list">
		<!-- 筛选 -->
		<form class=" form-inline" action="{$search_action}"  method="post" name="searchForm">
			<div class="screen">
				<!-- 级别 -->
				<select name="bonustype_id" class="no_search w150"  id="select-bonustype">
					<option value=''  {if $bonustype.send_type eq '' } selected="true" {/if}>{t}所有发放类型{/t}</option>
					<option value='0' {if $bonustype.send_type eq '0'} selected="true" {/if}>{$lang.send_by[0]}</option>
					<option value='1' {if $bonustype.send_type eq '1'} selected="true" {/if}>{$lang.send_by[1]}</option>
					<option value='2' {if $bonustype.send_type eq '2'} selected="true" {/if}>{$lang.send_by[2]}</option>
					<option value='3' {if $bonustype.send_type eq '3'} selected="true" {/if}>{$lang.send_by[3]}</option>
				</select>
				<button class="btn screen-btn" type="button">{t}筛选{/t}</button>
			</div>
		</form>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<form method="post" name="listForm"  action="">
			<!-- start brand list -->
			<table class="table table-striped smpl_tbl dataTable table-hide-edit">
				<thead>
					<tr>
						<th>{$lang.type_name}</th>
						<th>{$lang.merchant_name}</th>
						<th>{$lang.send_type}</th>
						<th>{$lang.type_money}</th>
						<th>{$lang.min_amount}</th>
						<th>{$lang.send_count}</th>
						<th>{$lang.use_count}</th>
					</tr>
				</thead>
				<tbody>
					<!-- {foreach from=$type_list.item item=type} -->
					<tr>
						<td class="hide-edit-area hide_edit_area_bottom" >
							<span class="cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('bonus/admin/edit_type_name')}" data-name="type_name" data-pk="{$type.type_id}" data-title="编辑红包类型名称">{$type.type_name}</span>
							<br/>
							<div class="edit-list">
								<a class="data-pjax" href='{RC_Uri::url("bonus/admin/bonus_list","bonus_type={$type.type_id}")}' title="{t}查看红包{/t}">{t}查看红包{/t}</a>&nbsp;|&nbsp;
								<a class="data-pjax" href='{RC_Uri::url("bonus/admin/edit","type_id={$type.type_id}")}' title="{t}编辑{/t}">{t}编辑{/t}</a> &nbsp;|&nbsp;
								<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{t}您确定要删除红包类型[{$type.type_name}]吗？{/t}" href='{RC_Uri::url("bonus/admin/remove","id={$type.type_id}")}' title="{t}移除{/t}">{t}删除{/t}</a>
								{if $type.send_type neq 2 && $type.send_type neq 4}
								&nbsp;|&nbsp;<a class="data-pjax" href='{RC_Uri::url("bonus/admin/send","id={$type.type_id}&send_by={$type.send_type}")}' title="{t}发放{/t}">{t}发放红包{/t}</a>        
								{/if}
								{if $type.send_type eq 3}
								&nbsp;|&nbsp;<a href='{RC_Uri::url("bonus/admin/gen_excel","tid={$type.type_id}")}' title="{t}报表{/t}">{t}导出报表{/t}</a> 
								{/if}
							</div>
						</td> 
						<td>
							<!-- {if $type.user_bonus_type eq 2} -->
							<font style="color:#0e92d0;">{t}全场通用{/t}</font>
							<!-- {else}-->
							<font style="color:#F00;">{$type.user_bonus_type}</font>
							<!-- {/if} -->
						</td>
						<td>{$type.send_by}</td>
						<td>
							<span class="cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('bonus/admin/edit_type_money')}" data-name="type_money" data-pk="{$type.type_id}" data-title="编辑红包金额">{$type.type_money}</span>
						</td>
						<td>
							<span class="cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('bonus/admin/edit_min_amount')}" data-name="min_amount" data-pk="{$type.type_id}" title="编辑订单下限金额">{$type.min_amount}</span>
						</td>
						<td>{$type.send_count}</td>
						<td>{$type.use_count}</td>
					</tr>
					<!-- {foreachelse} -->
					<tr><td class="no-records" colspan="10">{$lang.no_records}</td></tr>
					<!-- {/foreach} -->
				</tbody>
			</table>
			<!-- end brand list -->
		</form>
	</div>
</div>
<!-- {$type_list.page} -->
<!-- {/block} -->