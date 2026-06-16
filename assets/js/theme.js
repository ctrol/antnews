(function () {
  "use strict";

  var navToggle = document.querySelector(".nav-toggle");
  var nav = document.querySelector(".navbar-collapse");
  if (navToggle && nav) {
    navToggle.addEventListener("click", function () {
      var expanded = navToggle.getAttribute("aria-expanded") === "true";
      navToggle.setAttribute("aria-expanded", expanded ? "false" : "true");
      document.body.classList.toggle("navbar-open", !expanded);
    });
  }

  var topNews = document.querySelector("[data-antnews-top]");
  var closeTop = document.querySelector(".top-news-close");
  if (topNews && closeTop && window.sessionStorage) {
    if (sessionStorage.getItem("antnewsTopClosed") === "1") {
      topNews.style.display = "none";
      document.body.classList.add("top-news-hidden");
      document.documentElement.classList.add("top-news-hidden");
    }
    closeTop.addEventListener("click", function () {
      topNews.style.display = "none";
      document.body.classList.add("top-news-hidden");
      document.documentElement.classList.add("top-news-hidden");
      sessionStorage.setItem("antnewsTopClosed", "1");
    });
  }

  var topRotate = document.querySelector("[data-antnews-top-rotate]");
  if (topRotate) {
    var rotateItems = Array.prototype.slice.call(topRotate.querySelectorAll(".top-news-item"));
    if (rotateItems.length > 1) {
      var rotateCurrent = 0;
      setInterval(function () {
        rotateItems[rotateCurrent].classList.remove("active");
        rotateCurrent = (rotateCurrent + 1) % rotateItems.length;
        rotateItems[rotateCurrent].classList.add("active");
      }, 4000);
    }
  }

  document.querySelectorAll("[data-slider]").forEach(function (slider) {
    var items = Array.prototype.slice.call(slider.querySelectorAll(".slider-item"));
    var dots = slider.querySelector(".slider-dots");
    if (!items.length || !dots) return;
    items.forEach(function (_, index) {
      var button = document.createElement("button");
      button.type = "button";
      button.setAttribute("aria-label", "切换到第 " + (index + 1) + " 张");
      if (index === 0) button.className = "active";
      button.addEventListener("click", function () {
        show(index);
      });
      dots.appendChild(button);
    });
    var current = 0;
    var dotButtons = Array.prototype.slice.call(dots.querySelectorAll("button"));
    function show(index) {
      items[current].classList.remove("active");
      dotButtons[current].classList.remove("active");
      current = index;
      items[current].classList.add("active");
      dotButtons[current].classList.add("active");
    }
    if (items.length > 1) {
      setInterval(function () {
        show((current + 1) % items.length);
      }, 4800);
    }
  });

  document.querySelectorAll(".antnews-tab-nav").forEach(function (nav) {
    var group = nav.getAttribute("data-tabs");
    if (!group) return;
    var panels = document.querySelector('[data-tabs-panels="' + group + '"]');
    if (!panels) return;

    nav.addEventListener("click", function (event) {
      var btn = event.target.closest("[data-tab-target]");
      if (!btn || !nav.contains(btn)) return;
      event.preventDefault();
      var target = btn.getAttribute("data-tab-target");

      nav.querySelectorAll("[data-tab-target]").forEach(function (item) {
        item.classList.toggle("active", item === btn);
      });
      panels.querySelectorAll("[data-tab-panel]").forEach(function (panel) {
        panel.classList.toggle("active", panel.getAttribute("data-tab-panel") === target);
      });
    });
  });

  /* Mobile: toggle sub-menus on click */
  document.querySelectorAll(".menu-item-has-children > a").forEach(function (link) {
    link.addEventListener("click", function (e) {
      if (window.innerWidth > 991) return;
      var parent = link.parentElement;
      var sub = parent.querySelector(":scope > .sub-menu");
      if (!sub) return;
      var isOpen = parent.classList.contains("sub-open");
      /* Close siblings first */
      parent.parentElement.querySelectorAll(":scope > .menu-item-has-children.sub-open").forEach(function (sibling) {
        if (sibling !== parent) sibling.classList.remove("sub-open");
      });
      parent.classList.toggle("sub-open", !isOpen);
      if (!isOpen) e.preventDefault();
    });
  });
})();
