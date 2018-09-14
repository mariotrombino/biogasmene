/*! AdminLTE app.js
 * ================
 * Main JS application file for AdminLTE v2.3.2 This file
 * should be included in all pages. It controls some layout
 * options and implements exclusive AdminLTE plugins.
 *
 * @Author  Almsaeed Studio
 * @Support <http://www.almsaeedstudio.com>
 * @Email   <support@almsaeedstudio.com>
 * @version 2.3.2
 * @license MIT <http://opensource.org/licenses/MIT>
 */

//Make sure jQuery has been loaded before app.js
if (typeof jQuery === "undefined") {
  throw new Error("AdminLTE requires jQuery");
}

/* AdminLTE
 *
 * @type Object
 * @description $.AdminLTE is the main object for the template's app.
 *              It's used for implementing functions and options related
 *              to the template. Keeping everything wrapped in an object
 *              prevents conflict with other plugins and is a better
 *              way to organize our code.
 */
$.AdminLTE = {};

/* --------------------
 * - AdminLTE Options -
 * --------------------
 * Modify these options to suit your implementation
 */
$.AdminLTE.options = {
  //Add slimscroll to navbar menus
  //This requires you to load the slimscroll plugin
  //in every page before app.js
  navbarMenuSlimscroll: true,
  navbarMenuSlimscrollWidth: "3px", //The width of the scroll bar
  navbarMenuHeight: "200px", //The height of the inner menu
  //General animation speed for JS animated elements such as box collapse/expand and
  //sidebar treeview slide up/down. This options accepts an integer as milliseconds,
  //'fast', 'normal', or 'slow'
  animationSpeed: 500,
  //Sidebar push menu toggle button selector
  sidebarToggleSelector: "[data-toggle='offcanvas']",
  //Activate sidebar push menu
  sidebarPushMenu: true,
  //Activate sidebar slimscroll if the fixed layout is set (requires SlimScroll Plugin)
  sidebarSlimScroll: true,
  //Enable sidebar expand on hover effect for sidebar mini
  //This option is forced to true if both the fixed layout and sidebar mini
  //are used together
  sidebarExpandOnHover: false,
  //BoxRefresh Plugin
  enableBoxRefresh: true,
  //Bootstrap.js tooltip
  enableBSToppltip: true,
  BSTooltipSelector: "[data-toggle='tooltip']",
  //Enable Fast Click. Fastclick.js creates a more
  //native touch experience with touch devices. If you
  //choose to enable the plugin, make sure you load the script
  //before AdminLTE's app.js
  enableFastclick: true,
  //Control Sidebar Options
  enableControlSidebar: true,
  controlSidebarOptions: {
    //Which button should trigger the open/close event
    toggleBtnSelector: "[data-toggle='control-sidebar']",
    //The sidebar selector
    selector: ".control-sidebar",
    //Enable slide over content
    slide: true
  },
  //Box Widget Plugin. Enable this plugin
  //to allow boxes to be collapsed and/or removed
  enableBoxWidget: true,
  //Box Widget plugin options
  boxWidgetOptions: {
    boxWidgetIcons: {
      //Collapse icon
      collapse: 'fa-minus',
      //Open icon
      open: 'fa-plus',
      //Remove icon
      remove: 'fa-times'
    },
    boxWidgetSelectors: {
      //Remove button selector
      remove: '[data-widget="remove"]',
      //Collapse button selector
      collapse: '[data-widget="collapse"]'
    }
  },
  //Direct Chat plugin options
  directChat: {
    //Enable direct chat by default
    enable: true,
    //The button to open and close the chat contacts pane
    contactToggleSelector: '[data-widget="chat-pane-toggle"]'
  },
  //Define the set of colors to use globally around the website
  colors: {
    lightBlue: "#3c8dbc",
    red: "#f56954",
    green: "#00a65a",
    aqua: "#00c0ef",
    yellow: "#f39c12",
    blue: "#0073b7",
    navy: "#001F3F",
    teal: "#39CCCC",
    olive: "#3D9970",
    lime: "#01FF70",
    orange: "#FF851B",
    fuchsia: "#F012BE",
    purple: "#8E24AA",
    maroon: "#D81B60",
    black: "#222222",
    gray: "#d2d6de"
  },
  //The standard screen sizes that bootstrap uses.
  //If you change these in the variables.less file, change
  //them here too.
  screenSizes: {
    xs: 480,
    sm: 768,
    md: 992,
    lg: 1200
  }
};

/* ------------------
 * - Implementation -
 * ------------------
 * The next block of code implements AdminLTE's
 * functions and plugins as specified by the
 * options above.
 */
$(function () {
  "use strict";

  //Fix for IE page transitions
  $("body").removeClass("hold-transition");

  //Extend options if external options exist
  if (typeof AdminLTEOptions !== "undefined") {
    $.extend(true,
        $.AdminLTE.options,
        AdminLTEOptions);
  }

  //Easy access to options
  var o = $.AdminLTE.options;

  //Set up the object
  _init();

  //Activate the layout maker
  $.AdminLTE.layout.activate();

  //Enable sidebar tree view controls
  $.AdminLTE.tree('.sidebar');

  //Enable control sidebar
  if (o.enableControlSidebar) {
    $.AdminLTE.controlSidebar.activate();
  }

  //Add slimscroll to navbar dropdown
  if (o.navbarMenuSlimscroll && typeof $.fn.slimscroll != 'undefined') {
    $(".navbar .menu").slimscroll({
      height: o.navbarMenuHeight,
      alwaysVisible: false,
      size: o.navbarMenuSlimscrollWidth
    }).css("width", "100%");
  }

  //Activate sidebar push menu
  if (o.sidebarPushMenu) {
    $.AdminLTE.pushMenu.activate(o.sidebarToggleSelector);
  }

  //Activate Bootstrap tooltip
  if (o.enableBSToppltip) {
    $('body').tooltip({
      selector: o.BSTooltipSelector
    });
  }

  //Activate box widget
  if (o.enableBoxWidget) {
    $.AdminLTE.boxWidget.activate();
  }

  //Activate fast click
  if (o.enableFastclick && typeof FastClick != 'undefined') {
    FastClick.attach(document.body);
  }

  //Activate direct chat widget
  if (o.directChat.enable) {
    $(document).on('click', o.directChat.contactToggleSelector, function () {
      var box = $(this).parents('.direct-chat').first();
      box.toggleClass('direct-chat-contacts-open');
    });
  }

  /*
   * INITIALIZE BUTTON TOGGLE
   * ------------------------
   */
  $('.btn-group[data-toggle="btn-toggle"]').each(function () {
    var group = $(this);
    $(this).find(".btn").on('click', function (e) {
      group.find(".btn.active").removeClass("active");
      $(this).addClass("active");
      e.preventDefault();
    });

  });
});

/* ----------------------------------
 * - Initialize the AdminLTE Object -
 * ----------------------------------
 * All AdminLTE functions are implemented below.
 */
function _init() {
  'use strict';
  /* Layout
   * ======
   * Fixes the layout height in case min-height fails.
   *
   * @type Object
   * @usage $.AdminLTE.layout.activate()
   *        $.AdminLTE.layout.fix()
   *        $.AdminLTE.layout.fixSidebar()
   */
  $.AdminLTE.layout = {
    activate: function () {
      var _this = this;
      _this.fix();
      _this.fixSidebar();
      $(window, ".wrapper").resize(function () {
        _this.fix();
        _this.fixSidebar();
      });
    },
    fix: function () {
      //Get window height and the wrapper height
      var neg = $('.main-header').outerHeight() + $('.main-footer').outerHeight();
      var window_height = $(window).height();
      var sidebar_height = $(".sidebar").height();
      //Set the min-height of the content and sidebar based on the
      //the height of the document.
      if ($("body").hasClass("fixed")) {
        $(".content-wrapper, .right-side").css('min-height', window_height - $('.main-footer').outerHeight());
      } else {
        var postSetWidth;
        if (window_height >= sidebar_height) {
          $(".content-wrapper, .right-side").css('min-height', window_height - neg);
          postSetWidth = window_height - neg;
        } else {
          $(".content-wrapper, .right-side").css('min-height', sidebar_height);
          postSetWidth = sidebar_height;
        }

        //Fix for the control sidebar height
        var controlSidebar = $($.AdminLTE.options.controlSidebarOptions.selector);
        if (typeof controlSidebar !== "undefined") {
          if (controlSidebar.height() > postSetWidth)
            $(".content-wrapper, .right-side").css('min-height', controlSidebar.height());
        }

      }
    },
    fixSidebar: function () {
      //Make sure the body tag has the .fixed class
      if (!$("body").hasClass("fixed")) {
        if (typeof $.fn.slimScroll != 'undefined') {
          $(".sidebar").slimScroll({destroy: true}).height("auto");
        }
        return;
      } else if (typeof $.fn.slimScroll == 'undefined' && window.console) {
        window.console.error("Error: the fixed layout requires the slimscroll plugin!");
      }
      //Enable slimscroll for fixed layout
      if ($.AdminLTE.options.sidebarSlimScroll) {
        if (typeof $.fn.slimScroll != 'undefined') {
          //Destroy if it exists
          $(".sidebar").slimScroll({destroy: true}).height("auto");
          //Add slimscroll
          $(".sidebar").slimscroll({
            height: ($(window).height() - $(".main-header").height()) + "px",
            color: "rgba(0,0,0,0.2)",
            size: "3px"
          });
        }
      }
    }
  };

  /* PushMenu()
   * ==========
   * Adds the push menu functionality to the sidebar.
   *
   * @type Function
   * @usage: $.AdminLTE.pushMenu("[data-toggle='offcanvas']")
   */
  $.AdminLTE.pushMenu = {
    activate: function (toggleBtn) {
      //Get the screen sizes
      var screenSizes = $.AdminLTE.options.screenSizes;

      //Enable sidebar toggle
      $(document).on('click', toggleBtn, function (e) {
        e.preventDefault();

        //Enable sidebar push menu
        if ($(window).width() > (screenSizes.sm - 1)) {
          if ($("body").hasClass('sidebar-collapse')) {
            $("body").removeClass('sidebar-collapse').trigger('expanded.pushMenu');
          } else {
            $("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
          }
        }
        //Handle sidebar push menu for small screens
        else {
          if ($("body").hasClass('sidebar-open')) {
            $("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');
          } else {
            $("body").addClass('sidebar-open').trigger('expanded.pushMenu');
          }
        }
      });

      $(".content-wrapper").click(function () {
        //Enable hide menu when clicking on the content-wrapper on small screens
        if ($(window).width() <= (screenSizes.sm - 1) && $("body").hasClass("sidebar-open")) {
          $("body").removeClass('sidebar-open');
        }
      });

      //Enable expand on hover for sidebar mini
      if ($.AdminLTE.options.sidebarExpandOnHover
          || ($('body').hasClass('fixed')
          && $('body').hasClass('sidebar-mini'))) {
        this.expandOnHover();
      }
    },
    expandOnHover: function () {
      var _this = this;
      var screenWidth = $.AdminLTE.options.screenSizes.sm - 1;
      //Expand sidebar on hover
      $('.main-sidebar').hover(function () {
        if ($('body').hasClass('sidebar-mini')
            && $("body").hasClass('sidebar-collapse')
            && $(window).width() > screenWidth) {
          _this.expand();
        }
      }, function () {
        if ($('body').hasClass('sidebar-mini')
            && $('body').hasClass('sidebar-expanded-on-hover')
            && $(window).width() > screenWidth) {
          _this.collapse();
        }
      });
    },
    expand: function () {
      $("body").removeClass('sidebar-collapse').addClass('sidebar-expanded-on-hover');
    },
    collapse: function () {
      if ($('body').hasClass('sidebar-expanded-on-hover')) {
        $('body').removeClass('sidebar-expanded-on-hover').addClass('sidebar-collapse');
      }
    }
  };

  /* Tree()
   * ======
   * Converts the sidebar into a multilevel
   * tree view menu.
   *
   * @type Function
   * @Usage: $.AdminLTE.tree('.sidebar')
   */
  $.AdminLTE.tree = function (menu) {
    var _this = this;
    var animationSpeed = $.AdminLTE.options.animationSpeed;
    $(menu).on('click', 'li a', function (e) {
      //Get the clicked link and the next element
      var $this = $(this);
      var checkElement = $this.next();

      //Check if the next element is a menu and is visible
      if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible')) && (!$('body').hasClass('sidebar-collapse'))) {
        //Close the menu
        checkElement.slideUp(animationSpeed, function () {
          checkElement.removeClass('menu-open');
          //Fix the layout in case the sidebar stretches over the height of the window
          //_this.layout.fix();
        });
        checkElement.parent("li").removeClass("active");
      }
      //If the menu is not visible
      else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
        //Get the parent menu
        var parent = $this.parents('ul').first();
        //Close all open menus within the parent
        var ul = parent.find('ul:visible').slideUp(animationSpeed);
        //Remove the menu-open class from the parent
        ul.removeClass('menu-open');
        //Get the parent li
        var parent_li = $this.parent("li");

        //Open the target menu and add the menu-open class
        checkElement.slideDown(animationSpeed, function () {
          //Add the class active to the parent li
          checkElement.addClass('menu-open');
          parent.find('li.active').removeClass('active');
          parent_li.addClass('active');
          //Fix the layout in case the sidebar stretches over the height of the window
          _this.layout.fix();
        });
      }
      //if this isn't a link, prevent the page from being redirected
      if (checkElement.is('.treeview-menu')) {
        e.preventDefault();
      }
    });
  };

  /* ControlSidebar
   * ==============
   * Adds functionality to the right sidebar
   *
   * @type Object
   * @usage $.AdminLTE.controlSidebar.activate(options)
   */
  $.AdminLTE.controlSidebar = {
    //instantiate the object
    activate: function () {
      //Get the object
      var _this = this;
      //Update options
      var o = $.AdminLTE.options.controlSidebarOptions;
      //Get the sidebar
      var sidebar = $(o.selector);
      //The toggle button
      var btn = $(o.toggleBtnSelector);

      //Listen to the click event
      btn.on('click', function (e) {
        e.preventDefault();
        //If the sidebar is not open
        if (!sidebar.hasClass('control-sidebar-open')
            && !$('body').hasClass('control-sidebar-open')) {
          //Open the sidebar
          _this.open(sidebar, o.slide);
        } else {
          _this.close(sidebar, o.slide);
        }
      });

      //If the body has a boxed layout, fix the sidebar bg position
      var bg = $(".control-sidebar-bg");
      _this._fix(bg);

      //If the body has a fixed layout, make the control sidebar fixed
      if ($('body').hasClass('fixed')) {
        _this._fixForFixed(sidebar);
      } else {
        //If the content height is less than the sidebar's height, force max height
        if ($('.content-wrapper, .right-side').height() < sidebar.height()) {
          _this._fixForContent(sidebar);
        }
      }
    },
    //Open the control sidebar
    open: function (sidebar, slide) {
      //Slide over content
      if (slide) {
        sidebar.addClass('control-sidebar-open');
      } else {
        //Push the content by adding the open class to the body instead
        //of the sidebar itself
        $('body').addClass('control-sidebar-open');
      }
    },
    //Close the control sidebar
    close: function (sidebar, slide) {
      if (slide) {
        sidebar.removeClass('control-sidebar-open');
      } else {
        $('body').removeClass('control-sidebar-open');
      }
    },
    _fix: function (sidebar) {
      var _this = this;
      if ($("body").hasClass('layout-boxed')) {
        sidebar.css('position', 'absolute');
        sidebar.height($(".wrapper").height());
        $(window).resize(function () {
          _this._fix(sidebar);
        });
      } else {
        sidebar.css({
          'position': 'fixed',
          'height': 'auto'
        });
      }
    },
    _fixForFixed: function (sidebar) {
      sidebar.css({
        'position': 'fixed',
        'max-height': '100%',
        'overflow': 'auto',
        'padding-bottom': '50px'
      });
    },
    _fixForContent: function (sidebar) {
      $(".content-wrapper, .right-side").css('min-height', sidebar.height());
    }
  };

  /* BoxWidget
   * =========
   * BoxWidget is a plugin to handle collapsing and
   * removing boxes from the screen.
   *
   * @type Object
   * @usage $.AdminLTE.boxWidget.activate()
   *        Set all your options in the main $.AdminLTE.options object
   */
  $.AdminLTE.boxWidget = {
    selectors: $.AdminLTE.options.boxWidgetOptions.boxWidgetSelectors,
    icons: $.AdminLTE.options.boxWidgetOptions.boxWidgetIcons,
    animationSpeed: $.AdminLTE.options.animationSpeed,
    activate: function (_box) {
      var _this = this;
      if (!_box) {
        _box = document; // activate all boxes per default
      }
      //Listen for collapse event triggers
      $(_box).on('click', _this.selectors.collapse, function (e) {
        e.preventDefault();
        _this.collapse($(this));
      });

      //Listen for remove event triggers
      $(_box).on('click', _this.selectors.remove, function (e) {
        e.preventDefault();
        _this.remove($(this));
      });
    },
    collapse: function (element) {
      var _this = this;
      //Find the box parent
      var box = element.parents(".box").first();
      //Find the body and the footer
      var box_content = box.find("> .box-body, > .box-footer, > form  >.box-body, > form > .box-footer");
      if (!box.hasClass("collapsed-box")) {
        //Convert minus into plus
        element.children(":first")
            .removeClass(_this.icons.collapse)
            .addClass(_this.icons.open);
        //Hide the content
        box_content.slideUp(_this.animationSpeed, function () {
          box.addClass("collapsed-box");
        });
      } else {
        //Convert plus into minus
        element.children(":first")
            .removeClass(_this.icons.open)
            .addClass(_this.icons.collapse);
        //Show the content
        box_content.slideDown(_this.animationSpeed, function () {
          box.removeClass("collapsed-box");
        });
      }
    },
    remove: function (element) {
      //Find the box parent
      var box = element.parents(".box").first();
      box.slideUp(this.animationSpeed);
    }
  };
}

/* ------------------
 * - Custom Plugins -
 * ------------------
 * All custom plugins are defined below.
 */

/*
 * BOX REFRESH BUTTON
 * ------------------
 * This is a custom plugin to use with the component BOX. It allows you to add
 * a refresh button to the box. It converts the box's state to a loading state.
 *
 * @type plugin
 * @usage $("#box-widget").boxRefresh( options );
 */
(function ($) {

  "use strict";

  $.fn.boxRefresh = function (options) {

    // Render options
    var settings = $.extend({
      //Refresh button selector
      trigger: ".refresh-btn",
      //File source to be loaded (e.g: ajax/src.php)
      source: "",
      //Callbacks
      onLoadStart: function (box) {
        return box;
      }, //Right after the button has been clicked
      onLoadDone: function (box) {
        return box;
      } //When the source has been loaded

    }, options);

    //The overlay
    var overlay = $('<div class="overlay"><div class="fa fa-refresh fa-spin"></div></div>');

    return this.each(function () {
      //if a source is specified
      if (settings.source === "") {
        if (window.console) {
          window.console.log("Please specify a source first - boxRefresh()");
        }
        return;
      }
      //the box
      var box = $(this);
      //the button
      var rBtn = box.find(settings.trigger).first();

      //On trigger click
      rBtn.on('click', function (e) {
        e.preventDefault();
        //Add loading overlay
        start(box);

        //Perform ajax call
        box.find(".box-body").load(settings.source, function () {
          done(box);
        });
      });
    });

    function start(box) {
      //Add overlay and loading img
      box.append(overlay);

      settings.onLoadStart.call(box);
    }

    function done(box) {
      //Remove overlay and loading img
      box.find(overlay).remove();

      settings.onLoadDone.call(box);
    }

  };

})(jQuery);

 /*
 * EXPLICIT BOX CONTROLS
 * -----------------------
 * This is a custom plugin to use with the component BOX. It allows you to activate
 * a box inserted in the DOM after the app.js was loaded, toggle and remove box.
 *
 * @type plugin
 * @usage $("#box-widget").activateBox();
 * @usage $("#box-widget").toggleBox();
 * @usage $("#box-widget").removeBox();
 */
(function ($) {

  'use strict';

  $.fn.activateBox = function () {
    $.AdminLTE.boxWidget.activate(this);
  };

  $.fn.toggleBox = function(){
    var button = $($.AdminLTE.boxWidget.selectors.collapse, this);
    $.AdminLTE.boxWidget.collapse(button);
  };

  $.fn.removeBox = function(){
    var button = $($.AdminLTE.boxWidget.selectors.remove, this);
    $.AdminLTE.boxWidget.remove(button);
  };

})(jQuery);

/*
 * TODO LIST CUSTOM PLUGIN
 * -----------------------
 * This plugin depends on iCheck plugin for checkbox and radio inputs
 *
 * @type plugin
 * @usage $("#todo-widget").todolist( options );
 */
(function ($) {

  'use strict';

  $.fn.todolist = function (options) {
    // Render options
    var settings = $.extend({
      //When the user checks the input
      onCheck: function (ele) {
        return ele;
      },
      //When the user unchecks the input
      onUncheck: function (ele) {
        return ele;
      }
    }, options);

    return this.each(function () {

      if (typeof $.fn.iCheck != 'undefined') {
        $('input', this).on('ifChecked', function () {
          var ele = $(this).parents("li").first();
          ele.toggleClass("done");
          settings.onCheck.call(ele);
        });

        $('input', this).on('ifUnchecked', function () {
          var ele = $(this).parents("li").first();
          ele.toggleClass("done");
          settings.onUncheck.call(ele);
        });
      } else {
        $('input', this).on('change', function () {
          var ele = $(this).parents("li").first();
          ele.toggleClass("done");
          if ($('input', ele).is(":checked")) {
            settings.onCheck.call(ele);
          } else {
            settings.onUncheck.call(ele);
          }
        });
      }
    });
  };
/* ================= Custom Methods ================= */

if(typeof String.prototype.hashCode !== 'function') {
  String.prototype.hashCode = function() {
      var hash = 0;
      if (this.length == 0) return hash;
      for (i = 0; i < this.length; i++) {
          char = this.charCodeAt(i);
          hash = ((hash<<5)-hash)+char;
          hash = hash & hash; // Convert to 32bit integer
      }
      return hash;
  };
}
if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
      return this.replace(/^\s+|\s+$/g, '');
  };
}
if (typeof String.prototype.startsWith != 'function') {
  // see below for better implementation!
  String.prototype.startsWith = function (str){
      return this.indexOf(str) == 0;
  };
}
if (typeof String.prototype.ucfirst != 'function') {
  // see below for better implementation!
  String.prototype.ucfirst = function (){
      return this.charAt(0).toUpperCase() + this.slice(1);
  };
}

if (typeof String.prototype.endsWith != 'function') {
  // see below for better implementation!
  String.prototype.endsWith = function (pattern){
      var d = this.length - pattern.length;
      return d >= 0 && this.lastIndexOf(pattern) === d;
  };
}

/* ================= Fancy Notifications ================= */
(function($) {
  'use strict';
  var Notification = function(container, options) {
      var self = this;
      self.container = $(container);
      self.notification = $('<div class="pgn push-on-sidebar-open"></div>');
      self.options = $.extend(true, {}, $.fn.pgNotification.defaults, options);
      if (!self.container.find('.pgn-wrapper[data-position=' + this.options.position + ']').length) {
          self.wrapper = $('<div class="pgn-wrapper" data-position="' + this.options.position + '"></div>');
          self.container.append(self.wrapper);
      } else {
          self.wrapper = $('.pgn-wrapper[data-position=' + this.options.position + ']');
      }
      self.alert = $('<div class="alert"></div>');
      self.alert.addClass('alert-' + self.options.type);
      if (self.options.style == 'bar') {
          new BarNotification();
      } else if (self.options.style == 'flip') {
          new FlipNotification();
      } else if (self.options.style == 'circle') {
          new CircleNotification();
      } else if (self.options.style == 'simple') {
          new SimpleNotification();
      } else {
          new SimpleNotification();
      }

      function SimpleNotification() {
          self.notification.addClass('pgn-simple');
          self.alert.append(self.options.message);
          if (self.options.showClose) {
              var close = $('<button type="button" class="close" data-dismiss="alert"></button>').append('<span aria-hidden="true">&times;</span>').append('<span class="sr-only">Close</span>');
              self.alert.prepend(close);
          }
      }

      function BarNotification() {
          self.notification.addClass('pgn-bar');
          self.alert.append('<span>' + self.options.message + '</span>');
          self.alert.addClass('alert-' + self.options.type);
          if (self.options.showClose) {
              var close = $('<button type="button" class="close" data-dismiss="alert"></button>').append('<span aria-hidden="true">&times;</span>').append('<span class="sr-only">Close</span>');
              self.alert.prepend(close);
          }
      }

      function CircleNotification() {
          self.notification.addClass('pgn-circle');
          var table = '<div>';
          if (self.options.thumbnail) {
              table += '<div class="pgn-thumbnail"><div>' + self.options.thumbnail + '</div></div>';
          }
          table += '<div class="pgn-message"><div>';
          if (self.options.title) {
              table += '<p class="bold">' + self.options.title + '</p>';
          }
          table += '<p>' + self.options.message + '</p></div></div>';
          table += '</div>';
          if (self.options.showClose) {
              table += '<button type="button" class="close" data-dismiss="alert">';
              table += '<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>';
              table += '</button>';
          }
          self.alert.append(table);
          self.alert.after('<div class="clearfix"></div>');
      }

      function FlipNotification() {
          self.notification.addClass('pgn-flip');
          self.alert.append("<span>" + self.options.message + "</span>");
          if (self.options.showClose) {
              var close = $('<button type="button" class="close" data-dismiss="alert"></button>').append('<span aria-hidden="true">&times;</span>').append('<span class="sr-only">Close</span>');
              self.alert.prepend(close);
          }
      }
      self.notification.append(self.alert);
      self.alert.on('closed.bs.alert', function() {
          self.notification.remove();
          self.options.onClosed();
      });
      return this;
  };
  Notification.VERSION = "1.0.0";
  Notification.prototype.show = function() {
      this.wrapper.prepend(this.notification);
      this.options.onShown();
      if (this.options.timeout != 0) {
          var _this = this;
          setTimeout(function() {
              this.notification.fadeOut("slow", function() {
                  $(this).remove();
                  _this.options.onClosed();
              });
          }.bind(this), this.options.timeout);
      }
  };
  $.fn.pgNotification = function(options) {
      return new Notification(this, options);
  };
  $.fn.pgNotification.defaults = {
      style: 'simple',
      message: null,
      position: 'top-right',
      type: 'info',
      showClose: true,
      timeout: 4000,
      onShown: function() {},
      onClosed: function() {}
  }
})(window.jQuery);

$(document).ready(function() {
/* ================= Toggle Switch - Checkbox ================= */
  $(".Switch").click(function() {
  $(this).hasClass("On") ? ($(this).parent().find("input:checkbox").attr("checked", !0), $(this).removeClass("On").addClass("Off")) : ($(this).parent().find("input:checkbox").attr("checked", !1), $(this).removeClass("Off").addClass("On"))
}), $(".Switch").each(function() {
  $(this).parent().find("input:checkbox").length && ($(this).parent().find("input:checkbox").hasClass("show") || $(this).parent().find("input:checkbox").hide(), $(this).parent().find("input:checkbox").is(":checked") ? $(this).removeClass("On").addClass("Off") : $(this).removeClass("Off").addClass("On"))
});
  
  /* ================= HTML ================= */
$(".htmlbox").wysihtml5();
  
  /* ================= Default Select2 ================= */
  $("[rel=select2]").select2({
      
  });
  $("[rel=taginput]").select2({
      tags: true,
      tokenSeparators: [',']
  });

  // Null Value for Dropdown
  $(".null_dd").on("click", function(event) {
      var cb = $(this).find('.cb_null_dd');
  if(cb.is(":checked")) {
    cb.prop("checked", !1);
    cb.attr("checked", !1);
    $(this).parent().prev().find('select').select2("enable");
  } else {
    cb.prop("checked", !0);
    cb.attr("checked", !0);
    $(this).parent().prev().find('select').select2("enable", false);
  }
  });
  
  /* ================= bootstrap-datetimepicker ================= */
$(".input-group.date").datetimepicker({
      format: 'DD/MM/YYYY',
      locale: 'it'
  });

$(".input-group.datetime").datetimepicker({
      format: 'DD/MM/YYYY LT',
      sideBySide: true
  });

  // Null Value for Date + Datetime
$(".input-group-addon.null_date").on("click", function(event) {
      var cb = $(this).find('.cb_null_date');
  if(cb.is(":checked")) {
    cb.prop("checked", !1);
    cb.attr("checked", !1);
    $(this).prev().prev().prop('readonly', !1);
  } else {
    cb.prop("checked", !0);
    cb.attr("checked", !0);
    $(this).prev().prev().prop('readonly', !0);
  }
  });

  /* ================= stickyTabs ================= */
$('.nav-tabs').stickyTabs();
  
  /* ================= Validate Unique Fields ================= */
  jQuery.validator.addMethod("data-rule-unique", function(value, element) {
      value = value.trim();
      
      var isAllowed = false;
      var bsurl = $("body").attr('bsurl');
      var field_id = element.getAttribute('field_id');
      var _token = $("input[name=_token_"+field_id+"]").val();
      var adminRoute = element.getAttribute('adminRoute');
  var isEdit = element.getAttribute('isEdit');
  var row_id = element.getAttribute('row_id');
      
      if(value != '' && bsurl != "") {
          $.ajax({
      url: bsurl+"/"+adminRoute+"/check_unique_val/"+field_id,
      type:"POST",
      async: false,
      data:{
                  'field_value': value,
                  '_token': _token,
        'isEdit': isEdit,
        'row_id': row_id
              },
      success: function(data) {
                  // console.log(data);
                  if(data.exists == true) {
                      isAllowed = false;
                  } else {
                      isAllowed = true;
                  }
      }
    });
  }
  return isAllowed;
  }, 'This value exists in database.');
});

/* ================= File Manager ================= */
var bsurl = $('body').attr("bsurl");
var adminRoute = $('body').attr("adminRoute");
var cntFiles = null;
var fm_dropzone = null;
$(document).ready(function() {
  function showLAFM(type, selector) {
      $("#image_selecter_origin_type").val(type);
      $("#image_selecter_origin").val(selector);
      
      $("#fm").modal('show');
      
      loadFMFiles();
  }
  function getLI(upload) {
      var image = '';
      if($.inArray(upload.extension, ["jpg", "jpeg", "png", "gif", "bmp"]) > -1) {
          image = '<img src="'+bsurl+'/files/'+upload.hash+'/'+upload.name+'?s=130">';
      } else {
          switch (upload.extension) {
              case "pdf":
                  image = '<i class="fa fa-file-pdf-o"></i>';
                  break;
              default:
                  image = '<i class="fa fa-file-text-o"></i>';
                  break;
          }
      }
      return '<li><a class="fm_file_sel" data-toggle="tooltip" data-placement="top" title="'+upload.name+'" upload=\''+JSON.stringify(upload)+'\'>'+image+'</a></li>';
  }
  function loadFMFiles() {
      // load uploaded files
      $.ajax({
          dataType: 'json',
          url: $('body').attr("bsurl")+"/"+adminRoute+"/uploaded_files",
          success: function ( json ) {
              console.log(json);
              cntFiles = json.uploads;
              $(".fm_file_selector ul").empty();
              if(cntFiles.length) {
                  for (var index = 0; index < cntFiles.length; index++) {
                      var element = cntFiles[index];
                      var li = getLI(element);
                      $(".fm_file_selector ul").append(li);
                  }
              } else {
                  $(".fm_file_selector ul").html("<div class='text-center text-danger' style='margin-top:40px;'>No Files</div>");
              }
          }
      });
  }
  // $(".input-group.file input").on("blur", function() {
  //     if($(this).val().startsWith("http")) {
  //         $(this).next(".preview").css({
  //             "display": "block",
  //             "background-image": "url('"+$(this).val()+"')",
  //             "background-size": "cover"
  //         });
  //     } else {
  //         $(this).next(".preview").css({
  //             "display": "block",
  //             "background-image": "url('"+bsurl+"/"+$(this).val()+"')",
  //             "background-size": "cover"
  //         });
  //     }
  // });
  $("#fm input[type=search]").keyup(function () {
      var sstring = $(this).val().trim();
      console.log(sstring);
      if(sstring != "") {
          $(".fm_file_selector ul").empty();
          for (var index = 0; index < cntFiles.length; index++) {
              var upload = cntFiles[index];
              if(upload.name.toUpperCase().includes(sstring.toUpperCase())) {
                  $(".fm_file_selector ul").append(getLI(upload));
              }
          }
      } else {
          loadFMFiles();
      }
  });
  $(".btn_upload_image").on("click", function() {
      showLAFM("image", $(this).attr("selecter"));
  });

  $(".btn_upload_file").on("click", function() {
      showLAFM("file", $(this).attr("selecter"));
  });

  $(".btn_upload_files").on("click", function() {
      showLAFM("files", $(this).attr("selecter"));
  });
  
  fm_dropzone = new Dropzone("#fm_dropzone", {
      maxFilesize: 2,
      acceptedFiles: "image/*,application/pdf",
      init: function() {
          this.on("complete", function(file) {
              this.removeFile(file);
          });
          this.on("success", function(file) {
              console.log("addedfile");
              console.log(file);
              loadFMFiles();
          });
      }
  });

  $(".uploaded_image i.fa.fa-times").on("click", function() {
      $(this).parent().children("img").attr("src", "");
      $(this).parent().addClass("hide");
      $(this).parent().prev().removeClass("hide");
      $(this).parent().prev().prev().val("0");
  });

  $(".uploaded_file i.fa.fa-times").on("click", function(e) {
      $(this).parent().attr("href", "");
      $(this).parent().addClass("hide");
      $(this).parent().prev().removeClass("hide");
      $(this).parent().prev().prev().val("0");
      e.preventDefault();
  });

  $(".uploaded_file2 i.fa.fa-times").on("click", function(e) {
      var upload_id = $(this).parent().attr("upload_id");
      var $hiddenFIDs = $(this).parent().parent().prev();
      
      var hiddenFIDs = JSON.parse($hiddenFIDs.val());
      var hiddenFIDs2 = [];
      for (var key in hiddenFIDs) {
          if (hiddenFIDs.hasOwnProperty(key)) {
              var element = hiddenFIDs[key];
              if(element != upload_id) {
                  hiddenFIDs2.push(element);
              }
          }
      }
      $hiddenFIDs.val(JSON.stringify(hiddenFIDs2));
      $(this).parent().remove();
      e.preventDefault();
  });
  
  $("body").on("click", ".fm_file_sel", function() {
      type = $("#image_selecter_origin_type").val();
      upload = JSON.parse($(this).attr("upload"));
      console.log("upload sel: "+upload+" type: "+type);
      if(type == "image") {
          $hinput = $("input[name="+$("#image_selecter_origin").val()+"]");
          $hinput.val(upload.id);

          $hinput.next("a").addClass("hide");
          $hinput.next("a").next(".uploaded_image").removeClass("hide");
          $hinput.next("a").next(".uploaded_image").children("img").attr("src", bsurl+'/files/'+upload.hash+'/'+upload.name+"?s=150");
      } else if(type == "file") {
          $hinput = $("input[name="+$("#image_selecter_origin").val()+"]");
          $hinput.val(upload.id);

          $hinput.next("a").addClass("hide");
          $hinput.next("a").next(".uploaded_file").removeClass("hide");
          $hinput.next("a").next(".uploaded_file").attr("href", bsurl+'/files/'+upload.hash+'/'+upload.name);
      } else if(type == "files") {
          $hinput = $("input[name="+$("#image_selecter_origin").val()+"]");
          
          var hiddenFIDs = JSON.parse($hinput.val());
          // check if upload_id exists in array
          var upload_id_exists = false;
          for (var key in hiddenFIDs) {
              if (hiddenFIDs.hasOwnProperty(key)) {
                  var element = hiddenFIDs[key];
                  if(element == upload.id) {
                      upload_id_exists = true;
                  }
              }
          }
          if(!upload_id_exists) {
              hiddenFIDs.push(upload.id);
          }
          $hinput.val(JSON.stringify(hiddenFIDs));
          var fileImage = "";
          if(upload.extension == "jpg" || upload.extension == "png" || upload.extension == "gif" || upload.extension == "jpeg") {
              fileImage = "<img src='"+bsurl+"/files/"+upload.hash+"/"+upload.name+"?s=90'>";
          } else {
              fileImage = "<i class='fa fa-file-o'></i>";
          }
          $hinput.next("div.uploaded_files").append("<a class='uploaded_file2' upload_id='"+upload.id+"' target='_blank' href='"+bsurl+"/files/"+upload.hash+"/"+upload.name+"'>"+fileImage+"<i title='Remove File' class='fa fa-times'></i></a>");
      }
      $("#fm").modal('hide');
  });
});
}(jQuery));
