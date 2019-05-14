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
            "slidingSubmenus": true,
            "extensions": [
                "pagedim-black"
            ],
            "counters": true,
            "navbar": {
                title: false
            },
            "navbars": [
                {
                    "position": "bottom",
                    "content": [
                        "<a class='fa fa-envelope' href='#/'></a>",
                        "<a class='fa fa-twitter' href='#/'></a>",
                        "<a class='fa fa-facebook' href='#/'></a>"
                    ]
                }
            ],
            hooks: {
                "openPanel:start": function( $panel ) {
                    $(_this.mobileMenu).css('visibility', 'visible');
                }
            }
        });

        const api = $(_this.mobileMenu).data('mmenu');

        api.bind('openPanel:before', function (e) {
            console.log(e);
        });
    },
};

adaptive.init();