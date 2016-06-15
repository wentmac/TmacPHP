    <style type="text/css">
    	.closed{
    		width: auto;
    	} 	
    	.closed #slider_bar{
    		display: inline;
    	}
    	#open_menu{display: none;}
    </style>
    
    	
    <!-- sidebar start -->
    <div class="admin-sidebar am-offcanvas closed" id="admin-offcanvas">
    	<div id="open_menu"  style="background-color: #fff;">
    		<a class="am-btn am-padding-sm am-btn-success" href="#" title="展开菜单">
    			<i class="am-icon-fw am-icon-angle-double-right"></i></a>
    	</div>
      <div id="slider_bar" class="am-offcanvas-bar admin-offcanvas-bar">
        <ul class="am-list admin-sidebar-list">
          <li><a href="#" id="close_menu"><span class="am-icon-fw am-icon-list-ul am-text-warning"></span><span class="am-text-warning">收起菜单</span><span class="am-icon-angle-double-left am-text-warning am-icon-fw am-fr am-margin-right-sm"></span></li></a>
          <!--{loop $menu_array $k $sec1}-->
            <!--{if count($sec1[subname])>0}-->
              <li class="admin-parent">
                <a class="am-cf" data-am-collapse="{target: '#collapse-nav-{$k}'}"><span class="{$sec1[class]}"></span>{$sec1[title]}<span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav-{$k}">
                    <!--{loop $sec1[subname] $kk $sec2}-->
                    <li><a href="{$sec2[linktype]}" class="am-cf"><span class="{$sec2[class]}"></span>{$sec2[nametype]}</a></li>
                    <!--{/loop}-->                    
                </ul>
              </li>            
            <!--{else}-->
            <li><a href="{$sec1[link]}"><span class="{$sec1[class]}"></span>{$sec1[title]}</a></li>
            <!--{/if}-->          
          <!--{/loop}-->
        </ul>        
        <div class="am-panel am-panel-default admin-sidebar-panel">
          <div class="am-panel-bd">
            <p><span class="am-icon-tag"></span> 最新消息</p>
            <p>云端产品库已经发布！
              <br></p>
          </div>
        </div>
      </div>
    </div>
    <!-- sidebar end -->