const adaptive = {
    mobileMenu: '#mobileMenu',
    firstMenuElement: '#mobileMenu .categories_hor.dropdown a',
    secondMenu: '.dropdown-menu',
    secondMenuElement: '.dropdown-menu .b-main-menu__parent',

    activeClass: 'active',

    init() {
        const _this = this;

        $(document).ready(function () {
            _this.events();
        });
    },

    events() {
        const _this = this;

        $(_this.mobileMenu).mmenu({
            "slidingSubmenus": false,
            "counters": true
        });

        const api = $(_this.mobileMenu).data('mmenu');

        api.bind('openPanel:start', function (e) {
        });
    },
};

adaptive.init();