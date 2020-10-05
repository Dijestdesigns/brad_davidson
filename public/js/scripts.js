/*---LEFT BAR ACCORDION----*/
$(function() {
  $('#nav-accordion').dcAccordion({
    eventType: 'click',
    autoClose: true,
    saveState: true,
    disableLink: true,
    speed: 'slow',
    showCount: false,
    autoExpand: true,
    //        cookie: 'dcjq-accordion-1',
    classExpand: 'dcjq-current-parent'
  });
});

var Script = function() {


  //    sidebar dropdown menu auto scrolling

  jQuery('#sidebar .sub-menu > a').click(function() {
    var o = ($(this).offset());
    diff = 250 - o.top;
    if (diff > 0)
      $("#sidebar").scrollTo("-=" + Math.abs(diff), 500);
    else
      $("#sidebar").scrollTo("+=" + Math.abs(diff), 500);
  });



  //    sidebar toggle

  $(function() {
    function responsiveView() {
      var wSize = $(window).width();
      if (wSize <= 768) {
        $('#container').addClass('sidebar-close');
        $('#sidebar > ul').hide();
      }

      if (wSize > 768) {
        $('#container').removeClass('sidebar-close');
        $('#sidebar > ul').show();
      }
    }
    $(window).on('load', responsiveView);
    $(window).on('resize', responsiveView);
  });

  $('.fa-bars').click(function() {
    if ($('#sidebar > ul').is(":visible") === true) {
      $('#main-content').css({
        'margin-left': '0px'
      });
      $('#sidebar').css({
        'margin-left': '-210px'
      });
      $('#sidebar > ul').hide();
      $("#container").addClass("sidebar-closed");
    } else {
      $('#main-content').css({
        'margin-left': '210px'
      });
      $('#sidebar > ul').show();
      $('#sidebar').css({
        'margin-left': '0'
      });
      $("#container").removeClass("sidebar-closed");
    }
  });

  // custom scrollbar
  $("#sidebar").niceScroll({
    styler: "fb",
    cursorcolor: "#4ECDC4",
    cursorwidth: '3',
    cursorborderradius: '10px',
    background: '#404040',
    spacebarenabled: false,
    cursorborder: ''
  });

  //  $("html").niceScroll({styler:"fb",cursorcolor:"#4ECDC4", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});

  // widget tools

  jQuery('.panel .tools .fa-chevron-down').click(function() {
    var el = jQuery(this).parents(".panel").children(".panel-body");
    if (jQuery(this).hasClass("fa-chevron-down")) {
      jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
      el.slideUp(200);
    } else {
      jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
      el.slideDown(200);
    }
  });

  jQuery('.panel .tools .fa-times').click(function() {
    jQuery(this).parents(".panel").parent().remove();
  });


  //    tool tips

  $('.tooltips').tooltip();

  //    popovers

  $('.popovers').popover();



  // custom bar chart

  if ($(".custom-bar-chart")) {
    $(".bar").each(function() {
      var i = $(this).find(".value").html();
      $(this).find(".value").html("");
      $(this).find(".value").animate({
        height: i
      }, 2000)
    })
  }

}();

/**
 * Sets a CSS style on the selected element(s) with !important priority.
 * This supports camelCased CSS style property names and calling with an object 
 * like the jQuery `css()` method. 
 * Unlike jQuery's css() this does NOT work as a getter.
 * 
 * @param {string|Object<string, string>} name
 * @param {string|undefined} value
 */   
jQuery.fn.cssImportant = function(name, value) {
  const $this = this;
  const applyStyles = (n, v) => {
    // Convert style name from camelCase to dashed-case.
    const dashedName = n.replace(/(.)([A-Z])(.)/g, (str, m1, upper, m2) => {
      return m1 + "-" + upper.toLowerCase() + m2;
    }); 
    // Loop over each element in the selector and set the styles.
    $this.each(function(){
      this.style.setProperty(dashedName, v, 'important');
    });
  };
  // If called with the first parameter that is an object,
  // Loop over the entries in the object and apply those styles. 
  if(jQuery.isPlainObject(name)){
    for(const [n, v] of Object.entries(name)){
       applyStyles(n, v);
    }
  } else {
    // Otherwise called with style name and value.
    applyStyles(name, value);
  }
  // This is required for making jQuery plugin calls chainable.
  return $this;
};

jQuery(document).ready(function( $ ) {

  // Go to top
  $('.go-top').on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop : 0},500);
  });

    $(document).find("#folders").collapse('show');

    $(document).find('[id^=slider]').carousel('pause');

    setTimeout(function() {
        $(document).find(".md-header.btn-toolbar").find(".md-controls").append('<div class="pull-left"><button class="btn btn-primary btn-sm" style="margin-right: 5px;" type="submit"><i class="fa fa-save"></i></button><button class="saveDiary btn btn-primary btn-sm" style="margin-right: 5px;" title="Create New" data-title="Create New" type="button"><i class="fa fa-plus"></i></button><a href="#" class="deleteBtnDiary btn btn-danger btn-sm" data-confirm-message="Are you sure you want to delete this?" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a></div>');
        $(document).find(".md-control.md-control-fullscreen").on("click", function() {
            // $(this).css("visibility", "hidden");
        });
        $(document).find(".md-fullscreen-mode").find(".md-fullscreen-controls .exit-fullscreen").on("click", function() {
            // $(document).find(".md-control.md-control-fullscreen").css("visibility", "visible");
        });
        /*$(document).keyup(function(e) {
            if(e.key === "Escape") {
                // $(document).find(".md-control.md-control-fullscreen").css("visibility", "visible");
                // $(document).find(".md-fullscreen-mode").find(".md-fullscreen-controls .exit-fullscreen").click();
            }
        });*/

        $(document).find(".list-group-item").on("click", function() {
            let id          = $(this).data("id"),
                contentBook = $("#contentBook-" + id);

            if (contentBook && contentBook.length > 0) {
                $(document).find(".md-editor").cssImportant("display", "none");

                contentBook.parent().fadeIn(200, function() {
                    contentBook.parent().cssImportant("display", "block");

                    $(document).find("#currentId").val(id);
                });

                $(document).find(".page-aside .app-notebook-list .list-group li").removeClass("active");
                $(this).addClass("active");
            }
        });
    }, 2000);

    $(document).find(".app-notebook .page-main").fadeIn(200);
});

function getValue(element) {
    var price    = $("#item-price").val(),
        quantity = $("#item-quantity").val();

    $("#price-value").val(price * quantity);
}

function readURL(input) {
    if (input.files) {
        $('#preview-image').html("");

        $.each(input.files, function(index) {

          var reader = new FileReader();

          reader.onload = function(e) {
              $('#preview-image').append('<div class="col-md-4"><img src="' + e.target.result + '" style="width:100%;height: 100%;object-fit: cover;" /></div>');
          }

          reader.readAsDataURL(input.files[index]);
        });
    }
}

$("#imgUpload").change(function() {
    readURL(this);
});

let togglePassword = $(".togglePassword");
if (togglePassword) {
    togglePassword.on('click', function (e) {
        let passwordInput = $(this).next();

        if (passwordInput) {
            if (passwordInput.attr('type') == 'password') {
                passwordInput.attr('type', 'text');
            } else {
                passwordInput.attr('type', 'password');
            }
        }
    });
}
