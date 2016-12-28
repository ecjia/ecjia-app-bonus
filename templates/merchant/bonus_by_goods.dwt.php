<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.link_goods.init();
</script>
<!-- {/block} -->
<!-- {block name="home-content"} -->
<div class="row">
	<div class="col-lg-12">
		<h2 class="page-header">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
			<a  class="btn  btn-primary data-pjax" href="{$action_link.href}" id="sticky_a" style="float:right;margin-top:-3px;">
				<i class="fa fa-reply"></i> {$action_link.text}
			</a>
		<!-- {/if} -->
		</h2>
	</div>
</div>

<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <div class="panel-body">
                <div class="form">
                    <form class="cmxform form-horizontal tasi-form" action="{$form_action}" method="post" name="theForm">
                        <div class="tab-content">
                            <fieldset>
                                <div class="form-group choose_lists" data-url="{url path='goods/merchant/get_goods_list'}">
                                    <div class="col-lg-2">
                                        <select name="cat_id"  class="form-control chzn-single">
                                        <option value="0">{lang key='system::system.all_category'}{$cat_list}</option>
                                        </select>
                                    </div>
<!--                                     <div class="col-lg-2"> -->
<!--                                         <select name="brand_id"  class="mem-rk chzn-single select-brand"> -->
<!--                                         <option value="0">{lang key='system::system.all_brand'}{html_options options=$brand_list}</option> -->
<!--                                         </select> -->
<!--                                     </div> -->
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text" name="keyword" />
                                    </div>

                                    <a class="btn btn-primary" data-toggle="searchGoods"><i class='fa fa-search'></i> <!-- {lang key='system::system.button_search'} --></a>
                                    <div class="col-lg-12">
                                        <span class="help-block m_t5">{t}搜索要发放此类型红包的商品展示在左侧区域中，点击左侧列表中选项，商品即可进入右侧发放红包区域。您还可以在右侧编辑将发放红包的商品。{/t}</span>
                                    </div>

                                </div>
                                <div class="form-group draggable">
                                    <div class="col-lg-12">
                                        <div class="ms-container " id="ms-custom-navigation">
                                            <div class="ms-selectable">
                                                <div class="search-header">
                                                    <input class="form-control" id="ms-search" type="text" placeholder="{t}筛选搜索到的商品信息{/t}" autocomplete="off">
                                                </div>
                                                <ul class="ms-list nav-list-ready">
                                                    <li class="ms-elem-selectable disabled"><span>暂无内容</span></li>
                                                </ul>
                                            </div>
                                            <div class="ms-selection">
                                                <div class="custom-header custom-header-align">发放此类型红包的商品</div>
                                                <ul class="ms-list nav-list-content">
                                                    <!-- {foreach from=$goods_list item=goods} -->
                                                    <li class="ms-elem-selection">
                                                        <input type="hidden" value="{$goods.goods_id}" name="article_id[]" />
                                                        <!-- {$goods.goods_name} -->
                                                        <span class="edit-list"><i class="fa fa-minus-circle ecjiafc-red del"></i></span>
                                                    </li>
                                                    <!-- {/foreach} -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <div class="ecjiaf-tac">
                                <button class="btn btn-info" type="submit">{t}确定发放红包{/t}</button>
                                <input type="hidden" id="bonus_type_id" value="{$bonus_type_id}" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- {/block} -->
