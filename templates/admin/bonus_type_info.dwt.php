<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.bonus_info_edit.type_info_init();
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

<div class="row-fluid edit-page">
	<div class="span12">
		<form method="post" class="form-horizontal" action="{$form_action}" name="typeInfoForm" data-edit-url="{RC_Uri::url('bonus/admin/edit')}" >
			<fieldset>
				<div>
					<input type="hidden" name="type_id" value="{$bonus_arr.type_id}" />
					<input type="hidden" name="send_type" id="send_type" value="{$bonus_arr.send_type}" />
					<input type="hidden" name="old_typename" value="{$bonus_arr.type_name}" />
				</div>
				<div class="control-group formSep">
					<label class="control-label">{$lang.type_name}：</label>
					<div class="controls">
						<input type='text' name='type_name' maxlength="30" value="{$bonus_arr.type_name}" size='20' />
						<span class="input-must">{$lang.require_field}</span>
					</div>
				</div>
				<div class="control-group formSep">
					<label class="control-label">{$lang.type_money}：</label>
					<div class="controls">
						<input type="text" name="type_money" value="{$bonus_arr.type_money}" size="20" />
						<span class="input-must">{$lang.require_field}</span>                
					</div>
				</div>  
				<div class="control-group formSep">
					<label class="control-label">{$lang.min_goods_amount}：</label>
					<div class="controls">
						<input name="min_goods_amount" type="text" id="min_goods_amount" value="{$bonus_arr.min_goods_amount}" size="20" />
						<span class="input-must">{$lang.require_field}</span>
						<span class="help-block">{$lang.notice_min_goods_amount}</span>
					</div>
				</div> 
				<div class="control-group formSep">
					<label class="control-label">{$lang.send_method}：</label>
					<div class="controls chk_radio">
						<input type="radio" name="send_type" value="0" {if $bonus_arr.send_type eq 0} checked="true" {/if} onClick="javascript:ecjia.admin.bonus_info_edit.type_info_showunit(0)"  />{$lang.send_by[0]}
						<input type="radio" name="send_type" value="3" {if $bonus_arr.send_type eq 3} checked="true" {/if} onClick="javascript:ecjia.admin.bonus_info_edit.type_info_showunit(3)"  />{$lang.send_by[3]}   
						<input type="radio" name="send_type" value="1" {if $bonus_arr.send_type eq 1} checked="true" {/if} onClick="javascript:ecjia.admin.bonus_info_edit.type_info_showunit(1)"  />{$lang.send_by[1]}
						<input type="radio" name="send_type" value="2" {if $bonus_arr.send_type eq 2} checked="true" {/if} onClick="javascript:ecjia.admin.bonus_info_edit.type_info_showunit(2)"  />{$lang.send_by[2]}
						
					</div>
				</div>
				<div class="control-group formSep" id="min_amount_div" {if $bonus_arr.send_type neq 2} style="display:none" {/if}>
					<label class="control-label">{$lang.min_amount}：</label>
					<div class="controls promote_date">
						<div class="">
							<input name="min_amount" type="text" id="min_amount" size="20" value='{$bonus_arr.min_amount}' /><br>
							<span class="help-block">{$lang.order_money_notic}</span>
						</div>
					</div>
				</div> 
				<div class="control-group formSep" id="start" {if $bonus_arr.send_type eq 0 || $bonus_arr.send_type eq 3} style="display:none" {/if}>
					<label class="control-label">{$lang.send_startdate}：</label>
					<div class="controls promote_date">
						<div class="input-append">
							<input class="date" name="send_start_date" type="text" id="send_start_date" size="22" value='{$bonus_arr.send_start_date}'  />
						</div>
						<br>
						<span class="help-block">{$lang.send_startdate_notic}</span>
					</div>
				</div>  
				<div class="control-group formSep" id="end" {if $bonus_arr.send_type eq 0 || $bonus_arr.send_type eq 3} style="display:none" {/if}>
					<label class="control-label">{$lang.send_enddate}：</label>
					<div class="controls promote_date">
						<div class="input-append">
							<input class="date" name="send_end_date" type="text"  id="send_end_date" size="22"  value='{$bonus_arr.send_end_date}'  />
						</div>
					</div>
				</div>
				<div class="edit-page control-group formSep">
					<label class="control-label">{$lang.usage_type}：</label>
					<div class="controls">
						<select name="bonus_type" id="type_id">
							<option value="0" {if $bonus_arr.usebonus_type eq 0}selected="selected"{/if}>{t}自主使用{/t}</option>
							<option value="1" {if $bonus_arr.usebonus_type eq 1}selected="selected"{/if}>{t}全场使用{/t}</option>
				        </select>
<!-- 			        	<input class="f_r w70" name="bonus_type_ext" type="text" id="bonus_type_ext" value="{$bonus_arr.bonus_type_ext}" /> -->
					</div>
				</div>
				<div class="control-group formSep">
					<label class="control-label">{$lang.use_startdate}：</label>
					<div class="controls promote_date">
						<div class="input-append date">
							<input class="date" name="use_start_date" type="text" class="date" id="use_start_date" size="22" value='{$bonus_arr.use_start_date}'  />
						</div><br>
						<span class="help-block" >{$lang.use_startdate_notic}</span>
					</div>  
				</div>  
				<div class="control-group formSep">
					<label class="control-label">{$lang.use_enddate}：</label>
					<div class="controls promote_date">
						<div class="input-append date">
							<input class="date" name="use_end_date" type="text" class="date" id="use_end_date" size="22" value='{$bonus_arr.use_end_date}'  />
						</div>	
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<!-- {if $bonus_arr.type_id eq ''} -->
							<button class="btn btn-gebo" type="submit">{$lang.button_submit}</button>
						<!-- {else} -->
							<button class="btn btn-gebo" type="submit">{t}更新{/t}</button>
						<!-- {/if} -->
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- {/block} -->