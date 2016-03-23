<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.bonus_type.list_init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="btn data-pjax" href="{$action_link.href}" id="sticky_a" style="float:right;margin-top:-3px;"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>
<!-- 批量操作-->
<div class="row-fluid batch" >
	<div class="btn-group f_l m_r5">
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fontello-icon-cog"></i>{t}批量操作{/t}
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li><a class="remove" data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url='{url path="bonus/admin/batch" args="sel_action=remove&bonus_type_id=$bonus_type_id"}' data-msg="您确定要这么做吗？" data-noSelectMsg="请先选中要删除的红包！" data-name="checkboxes" href="javascript:;"><i class="fontello-icon-trash"></i>{t}删除红包{/t}</a></li>				      
			<!-- {if $show_mail} -->  
			<li><a class="send"   data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url='{url path="bonus/admin/batch" args="sel_action=send&bonus_type_id=$bonus_type_id"}' data-msg="您确定要这么做吗？" data-noSelectMsg="您确定要插入邮件发送对列的红包？" data-name="checkboxes" href="javascript:;"><i class="fontello-icon-right-hand"></i>{t}插入邮件发送队列{/t}</a></li>
			<!-- {/if} -->
		</ul>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<form method="post"  action="{$form_action}"  name="listForm" data-edit-url="{RC_Uri::url('bonus/admin/bonus_list')}">
			<div class="row-fluid">
				<table class="table table-striped smpl_tbl">
					<thead>
						<tr>
							<th class="table_checkbox">
								<input type="checkbox" data-toggle="selectall"  data-children=".checkbox"/>
							</th>
							<!-- {if $show_bonus_sn} -->
							<th>{$lang.bonus_sn}</th>
							<!-- {/if} -->
							<th>{$lang.bonus_type}</th>
							<th>{$lang.order_id}</th>
							<th>{$lang.user_id}</th>
							<th>{$lang.used_time}</th>
							<!-- {if $show_mail} -->  
							<th>{$lang.emailed}</th>
							<!-- {/if} -->
							<th>{$lang.handler}</th>
						</tr>    
					</thead>
					<tbody>
						<!--{foreach from=$bonus_list.item item=bonus} -->
						<tr>
							<td><span><input type="checkbox" name="checkboxes[]"  class="checkbox"  value="{$bonus.bonus_id}"/></span></td>
							<!-- {if $show_bonus_sn} -->
							<td>{$bonus.bonus_sn}</td>
							<!-- {/if} -->
							<td>{$bonus.type_name}</td>
							<td>{$bonus.order_sn}</td>
							<td>{if $bonus.email}<a href="{RC_Uri::url('user/admin/info',"id={$bonus.user_id}")}">{$bonus.user_name}</a>{else}{$bonus.user_name}{/if}</td>
							<td align="right">{$bonus.used_time}</td>
							<!-- {if $show_mail} -->
							<td align="center">{$bonus.emailed}</td>
							<!-- {/if} -->
							<td align="center">
								<a class="ajaxremove no-underline" data-toggle="ajaxremove" data-msg="{t}您确定要删除编号为[{$bonus.bonus_id}]红包吗？{/t}" href="{RC_Uri::url('bonus/admin/remove_bonus',"id={$bonus.bonus_id}")}" title="{t}移除{/t}"><i class="fontello-icon-trash"></i></a>
								{if $show_mail and $bonus.order_id eq 0 and $bonus.email}<a class="insert_mail_list no-underline" href="javascript:;" data-href="{RC_Uri::url('bonus/admin/send_mail',"bonus_id={$bonus.bonus_id}&bonus_type={$bonus_type_id}")}" title="{t}插入邮件发送队列{/t}"><i class="fontello-icon-right-hand"></i></a>{/if}
							</td>
						</tr>
						<!-- {foreachelse} -->
						<tr><td class="no-records" colspan="10">{$lang.no_records}</td></tr>
						<!-- {/foreach} -->
					</tbody>
				</table>
			</div>
		</form>
	</div>
</div>
<!-- {$bonus_list.page} -->
<!-- {/block} -->