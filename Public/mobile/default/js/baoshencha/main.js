/**
 * Created by zhang on 2017/3/22.
 */
//requirejs.config();

require.config ({
    //By default load any module IDs from js/lib
    baseUrl: base_v + 'js/baoshencha/',
    //urlArgs: "v=" + version,
    //except, if the module ID starts with "app",
    //load it from the js/app directory. paths
    //config is relative to the baseUrl, and
    //never includes a ".js" extension since
    //the paths config could be for a directory.
    paths: {
        "zepto": [static_url + 'js/zepto.min'],
        "jquery": [static_url + 'js/zepto.min'],
        'fastclick': '//cdn.bootcss.com/fastclick/1.0.6/fastclick.min'
    },
    /**
     * 有些库不是AMD兼容的，这时就需要指定shim属性的值。shim可以理解成“垫片”，用来帮助require.js加载非AMD规范的库。
     * 理论上，require.js加载的模块，必须是按照AMD规范、用define()函数定义的模块。但是实际上，虽然已经有一部分流行的函数库（比如jQuery）符合AMD规范，更多的库并不符合。那么，require.js是否能够加载非规范的模块呢？
     回答是可以的。
     这样的模块在用require()加载之前，要先用require.config()方法，定义它们的一些特征。
     举例来说，underscore和backbone这两个库，都没有采用AMD规范编写。如果要加载它们的话，必须先定义它们的特征。

     require.config()接受一个配置对象，这个对象除了有前面说过的paths属性之外，还有一个shim属性，专门用来配置不兼容的模块。具体来说，每个模块要定义（1）exports值（输出的变量名），表明这个模块外部调用时的名称；（2）deps数组，表明该模块的依赖性。

     *
     shim: {
        "backbone": {
            deps: ["underscore"],
            exports: "Backbone"
        },
        "underscore": {
            exports: "_"
        }
    }
     */
    shim: {
        'zepto': {
            exports: '$'
        },
        'jquery': {
            exports: '$'
        },
    }
});

require([
    'jquery',
    'fastclick'
], function ($, fastclick) {
    $(document).ready(function () {
        var attachFastClick = require('fastclick');
        attachFastClick.attach(document.body);


        /**
        //返回后页面刷新
        reload.init();
        //左边的菜单 事件绑定
        common.init();
        //检测是否今天领取过
        stock.check_free_currency();
        stock.save_free_currency();

        //图表初始化，数据初始化
        trend.init();

        //行情交易相关初始化｛绑定｝
        trade.init();
        trade.expected_payment();
        //取待结算交易列表 移到stock_reload.init()成功后再执行
        //stock_dialog.init();
         */
    });
});