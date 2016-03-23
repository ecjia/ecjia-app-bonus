<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
ecjia.admin.link_user.init();
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

<div class="row-fluid list-page">
	<div class="span12">
		<!-- 按用户等级发放红包 -->	
		<div class="row-fluid ">
			<div class="choose_list span12">
				<form class="form-horizontal " action="{$form_action}" method="post" name="userRankForm"  >
					<div class="control-group">
						<span><strong>{$lang.senduserrank}</strong></span>
					</div>
					<div class="control-group">
						<label><span>{t}会员级别：{/t}</span></label>
						<div>
						<select name="rank_id">
								<option value="">{$lang.select_rank}</option>
								<!-- {html_options options=$ranklist selected=$smarty.get.rank_id} -->
							</select>
						
						</div>
					</div>
					<div class="control-group">
						<label>
						<span class="p_t3"><input type="checkbox" name="validated_email" value="1"></span>
						<span>{$lang.validated_email}</span></label>
					</div>
					<div class="control-group formSep m_b5">
						<label><button class="btn btn-gebo" type="submit">{t}确定发放红包{/t}</button></label>
					</div>
					<input type="hidden" name="act" value="send_by_user" />
					<input type="hidden" name="id" value="{$id}" />
				</form>
			</div>
		</div>
			
		<form class="form-horizontal" action='{$form_user_action}' method="post" name="theForm">
			<div class="tab-content">
				<fieldset>
					<div class="control-group"><strong>{t}按指定用户发放红包{/t}</strong></div>
					<div class="control-group choose_list" id="search_user"  data-url="{url path='bonus/admin/search_users'}">
						<input type="text" name="keyword" placeholder="请输入用户名的关键字" />
						<a class="btn" data-toggle="searchuser"><!-- {$lang.button_search} --></a><br>
						<span class="help-block m_t5">{t}搜索要发放此类型红包的用户展示在左侧区域中，点击左侧列表中选项，用户即可进入右侧发放红包区域。您还可以在右侧编辑将发放红包的用户。{/t}</span>
					</div>
					<div class="control-group draggable">
						<div class="ms-container " id="ms-custom-navigation">
							<div class="ms-selectable">
								<div class="search-header">
									<input class="span12" id="ms-search" type="text" placeholder="{t}筛选搜索到的用户信息{/t}" autocomplete="off">
								</div>
								<ul class="ms-list nav-list-ready">
									<li class="ms-elem-selectable disabled"><span>暂无信息</span></li>
								</ul>
							</div>
							<div class="ms-selection">
								<div class="custom-header custom-header-align">给下列用户发放红包</div>
								<ul class="ms-list nav-list-content">
								</ul>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<p class="ecjiaf-tac">
				<button class="btn btn-gebo" type="submit">{t}确定发放红包{/t}</button>
				<input type="hidden" id="bonus_type_id" value="{$id}" />
			<p>
		</form>
	</div>
</div>
<!-- {/block} -->