"use strict";
let isRtl = window.Helpers.isRtl(), isDarkStyle = window.Helpers.isDarkStyle(), menu, animate, isHorizontalLayout = !1;
document.getElementById("layout-menu") && (isHorizontalLayout = document.getElementById("layout-menu").classList.contains("menu-horizontal")), function () {
    function e() {
        var e = document.querySelector(".layout-page");
        e && (0 < window.pageYOffset ? e.classList.add("window-scrolled") : e.classList.remove("window-scrolled"))
    }

    "undefined" != typeof Waves && (Waves.init(), Waves.attach(".btn[class*='btn-']:not(.position-relative):not([class*='btn-outline-']):not([class*='btn-label-'])", ["waves-light"]), Waves.attach("[class*='btn-outline-']:not(.position-relative)"), Waves.attach("[class*='btn-label-']:not(.position-relative)"), Waves.attach(".pagination .page-item .page-link"), Waves.attach(".dropdown-menu .dropdown-item"), Waves.attach(".light-style .list-group .list-group-item-action"), Waves.attach(".dark-style .list-group .list-group-item-action", ["waves-light"]), Waves.attach(".nav-tabs:not(.nav-tabs-widget) .nav-item .nav-link"), Waves.attach(".nav-pills .nav-item .nav-link", ["waves-light"]), Waves.attach(".menu-vertical .menu-item .menu-link.menu-toggle")), setTimeout(() => {
        e()
    }, 200), window.onscroll = function () {
        e()
    }, document.querySelectorAll("#layout-menu").forEach(function (e) {
        menu = new Menu(e, {
            orientation: isHorizontalLayout ? "horizontal" : "vertical",
            closeChildren: !!isHorizontalLayout,
            showDropdownOnHover: localStorage.getItem("templateCustomizer-" + templateName + "--ShowDropdownOnHover") ? "true" === localStorage.getItem("templateCustomizer-" + templateName + "--ShowDropdownOnHover") : void 0 === window.templateCustomizer || window.templateCustomizer.settings.defaultShowDropdownOnHover
        }), window.Helpers.scrollToActive(animate = !1), window.Helpers.mainMenu = menu
    }), document.querySelectorAll(".layout-menu-toggle").forEach(e => {
        e.addEventListener("click", e => {
            if (e.preventDefault(), window.Helpers.toggleCollapsed(), config.enableMenuLocalStorage && !window.Helpers.isSmallScreen()) try {
                localStorage.setItem("templateCustomizer-" + templateName + "--LayoutCollapsed", String(window.Helpers.isCollapsed()))
            } catch (e) {
            }
        })
    }), window.Helpers.swipeIn(".drag-target", function (e) {
        window.Helpers.setCollapsed(!1)
    }), window.Helpers.swipeOut("#layout-menu", function (e) {
        window.Helpers.isSmallScreen() && window.Helpers.setCollapsed(!0)
    });
    let t = document.getElementsByClassName("menu-inner"), a = document.getElementsByClassName("menu-inner-shadow")[0];
    0 < t.length && a && t[0].addEventListener("ps-scroll-y", function () {
        this.querySelector(".ps__thumb-y").offsetTop ? a.style.display = "block" : a.style.display = "none"
    });
    var n = document.querySelector(".style-switcher-toggle");

    function o(a) {
        [].slice.call(document.querySelectorAll("[data-app-" + a + "-img]")).map(function (e) {
            var t = e.getAttribute("data-app-" + a + "-img");
            e.src = assetsPath + "img/" + t
        })
    }

    function l() {
        var e = document.querySelectorAll("[data-i18n]"),
            t = document.querySelector('.dropdown-item[data-language="' + i18next.language + '"]');
        t && t.click(), e.forEach(function (e) {
            e.innerHTML = i18next.t(e.dataset.i18n)
        })
    }

    n = document.querySelector(".dropdown-notifications-all");

    function i(e) {
        "show.bs.collapse" == e.type || "show.bs.collapse" == e.type ? e.target.closest(".accordion-item").classList.add("active") : e.target.closest(".accordion-item").classList.remove("active")
    }

    const r = document.querySelectorAll(".dropdown-notifications-read");
    n && n.addEventListener("click", e => {
        r.forEach(e => {
            e.closest(".dropdown-notifications-item").classList.add("marked-as-read")
        })
    }), r && r.forEach(t => {
        t.addEventListener("click", e => {
            t.closest(".dropdown-notifications-item").classList.toggle("marked-as-read")
        })
    }), document.querySelectorAll(".dropdown-notifications-archive").forEach(t => {
        t.addEventListener("click", e => {
            t.closest(".dropdown-notifications-item").remove()
        })
    }), [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (e) {
        return new bootstrap.Tooltip(e)
    });
    [].slice.call(document.querySelectorAll(".accordion")).map(function (e) {
        e.addEventListener("show.bs.collapse", i), e.addEventListener("hide.bs.collapse", i)
    });
    if (isRtl && Helpers._addClass("dropdown-menu-end", document.querySelectorAll("#layout-navbar .dropdown-menu")), window.Helpers.setAutoUpdate(!0), window.Helpers.initPasswordToggle(), window.Helpers.initSpeechToText(), window.Helpers.navTabsAnimation(), window.Helpers.initNavbarDropdownScrollbar(), window.addEventListener("resize", function (e) {
        window.innerWidth >= window.Helpers.LAYOUT_BREAKPOINT && document.querySelector(".search-input-wrapper") && (document.querySelector(".search-input-wrapper").classList.add("d-none"), document.querySelector(".search-input").value = ""), document.querySelector("[data-template^='horizontal-menu']") && setTimeout(function () {
            window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT ? document.getElementById("layout-menu") && document.getElementById("layout-menu").classList.contains("menu-horizontal") && menu.switchMenu("vertical") : document.getElementById("layout-menu") && document.getElementById("layout-menu").classList.contains("menu-vertical") && menu.switchMenu("horizontal")
        }, 100), window.Helpers.navTabsAnimation()
    }, !0), !isHorizontalLayout && !window.Helpers.isSmallScreen() && ("undefined" != typeof TemplateCustomizer && window.templateCustomizer.settings.defaultMenuCollapsed && window.Helpers.setCollapsed(!0, !1), "undefined" != typeof config) && config.enableMenuLocalStorage) try {
        null !== localStorage.getItem("templateCustomizer-" + templateName + "--LayoutCollapsed") && "false" !== localStorage.getItem("templateCustomizer-" + templateName + "--LayoutCollapsed") && window.Helpers.setCollapsed("true" === localStorage.getItem("templateCustomizer-" + templateName + "--LayoutCollapsed"), !1)
    } catch (e) {
    }
}();
