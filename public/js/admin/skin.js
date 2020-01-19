/**
 * skin
 * @author hsu Lay
 * @since 2020-1-10
 */
(function () {
    'use strict';

    let obj = {

        //当前
        current: {
            skin: '',
            sidebarSkin: '',
            linkSkin: '',
            size: '',
            fixedSidebar: true,
        },

        //main-header
        mainHeader: $('.main-header'),

        //sidebar
        sidebar: {
            container: $('.main-sidebar')
        },

        //drawer panel
        drawerPanel: {
            container: $('.drawerPanel-container'),

            //skin-type
            skinType: $('.drawerPanel-container .setting-drawer-index-item'),

            //skin-bg
            skinBg: $('.drawerPanel-container .drawer-item .bg'),

            //font size font-size
            fontSize: $('.drawerPanel-container .drawer-item .font-size select'),

            //fixed sidebar
            fixedSidebar: $('.drawerPanel-container .drawer-item .fixed-siderbar input'),
        }
    };

    //skin-type
    obj.drawerPanel.skinType.on('click', function () {
        if ($(this).find('.skin-type').hasClass('show')) {
            return;
        } else {
            $(this).parent().find('.show').removeClass('show');
            $(this).find('.skin-type').addClass('show');
        }

        if ($(this).hasClass('light')) {
            obj.current.sidebarSkin = 'light';
            obj.sidebar.container.removeClass('sidebar-dark-').addClass(`sidebar-${obj.current.sidebarSkin}-`);
        } else {
            obj.current.sidebarSkin = 'dark';
            obj.sidebar.container.removeClass('sidebar-light-').addClass(`sidebar-${obj.current.sidebarSkin}-`);
        }

        saveSkin();
    });

    //skin-bg
    obj.drawerPanel.skinBg.on('click', function () {
        let d = $(this).data('value');
        if (obj.current.skin !== d) {
            //main Header
            obj.mainHeader.removeClass(`navbar-${obj.current.skin}`).addClass(`navbar-${d}`);

            //a link
            $('body').removeClass(`accent-${obj.current.skin}`).addClass(`accent-${d}`);

            obj.current.skin = d;
            obj.current.linkSkin = d;
            saveSkin();
        }
    });

    //font size
    obj.drawerPanel.fontSize.on('change', function () {
        let v = $(this).val();
        if (obj.current.size !== v) {
            $('body').removeClass(`text-${obj.current.size}`).addClass(`text-${v}`);

            obj.current.size = `${v}`;
            saveSkin();
        }
    });

    //font size
    obj.drawerPanel.fixedSidebar.on('change', function () {
        let v = $(this).is(':checked');
        if (obj.current.fixedSidebar !== v) {
            v ? obj.sidebar.container.addClass(`fixed`) : obj.sidebar.container.removeClass(`fixed`);

            obj.current.fixedSidebar = v;
            saveSkin();
        }
    });


    /**
     * init
     */
    function init() {
        let current = localStorage.getItem('current');

        //default
        if (!current) {
            current = obj.current;

            let defaultSkin = 'primary';
            let defaultSize = 'md';
            let skinType = 'dark';
            let fixedSidebar = true;
            current.skin = current.linkSkin = defaultSkin;
            current.size = defaultSize;
            current.sidebarSkin = skinType;
            current.fixedSidebar = fixedSidebar;

            obj.current = current;

            saveSkin();
        } else {
            obj.current = current = JSON.parse(current);
        }

        obj.drawerPanel.skinType.each(function(){
            if($(this).hasClass(current.sidebarSkin)){
                $(this).find('.skin-type').addClass('show');
            }
        });

        obj.drawerPanel.fontSize.find("option[value="+current.size+"]").selected();

        obj.sidebar.container.addClass(`sidebar-${current.sidebarSkin}-`);

        obj.mainHeader.addClass(`navbar-${current.skin}`);

        if(!current.fixedSidebar){
            obj.sidebar.container.removeClass('fixed');
            obj.drawerPanel.fixedSidebar.attr("checked", false);
        }

        $('body').addClass(`accent-${current.linkSkin} text-${current.size}`);
    }

    /**
     * saveSkin
     */
    function saveSkin() {
        let current = obj.current;
        localStorage.setItem('current', JSON.stringify(current));
    }

    init();

})();