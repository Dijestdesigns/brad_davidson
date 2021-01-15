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
        $(document).find(".md-header.btn-toolbar").find(".md-controls").append('<div class="pull-left"><button class="btn btn-primary btn-sm" style="margin-right: 5px;" type="submit"><i class="fa fa-save"></i> Save</button><button class="saveNotes btn btn-primary btn-sm" style="margin-right: 5px;" title="Create New" data-title="Create New" type="button"><i class="fa fa-plus"></i> New</button><a href="#" class="deleteBtnNotes btn btn-danger btn-sm" data-confirm-message="Are you sure you want to delete this?" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a></div>');
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

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    setTimeout(function() {
        if (typeof calendarDatas !== typeof undefined) {
          let myOptions = {
              header: {
                  left: null,
                  center: "prev,title,next",
                  right: "month,agendaWeek,agendaDay"
              },
              defaultDate: ((typeof selectedDate !== typeof undefined) ? selectedDate : yyyy + '-' + mm + '-' + dd),
              allDaySlot: true,
              firstHour: 8,
              slotMinutes: 30,
              timeFormat: 'HH:mm:ss',
              selectable: !0,
              selectHelper: !0,
              slotLabelFormat:'HH:mm:ss',
              select: function(event) {
                  $("#addNewEvent").modal("show"),
                  $("#starts").datetimepicker("update", moment(event._i).format("YYYY-MM-DD HH:mm:ss"));
              },
              editable: !1,
              eventLimit: !0,
              windowResize: function(view) {
                  var width = $(window).outerWidth()
                    , options = Object.assign({}, myOptions);
                  options.events = view.calendar.clientEvents(),
                  options.aspectRatio = width < 667 ? .5 : 1.35,
                  $("#calendar").fullCalendar("destroy"),
                  $("#calendar").fullCalendar(options)
              },
              eventClick: function(event) {
                  var color = event.backgroundColor ? event.backgroundColor : "blue";
                  $("#editEname").val(event.title),
                  event.start ? $("#editStarts").datetimepicker("update", event.start._i) : $("#editStarts").datetimepicker("update", ""),
                  event.end ? $("#editEnds").datetimepicker("update", event.end._i) : $("#editEnds").datetimepicker("update", ""),
                  $("#editColor [type=radio]").each(function() {
                      var $this = $(this)
                        , _value = $this.data("color").split("|");
                      _value[0] === color ? $this.prop("checked", !0) : $this.prop("checked", !1)
                  }),
                  $("#editRepeats").val(event.repeats),
                  $("#calendarId").val(event.calendarId),
                  $("#editNewEvent").modal("show").one("hidden.bs.modal", function(e) {
                      event.title = $("#editEname").val();
                      if ($("#editColor [type=radio]:checked").length > 0) {
                          var color = $("#editColor [type=radio]:checked").data("color").split("|");
                          color = color[0]
                      } else {
                          color = "blue";
                      }
                      event.backgroundColor = color,
                      event.borderColor = color,
                      event.start = new Date($("#editStarts").data("datetimepicker").getDate()),
                      event.end = new Date($("#editEnds").data("datetimepicker").getDate());
                      // $("#calendar").fullCalendar("updateEvent", event);
                  });
              },
              events: calendarDatas,
              droppable: !0,
              eventMouseover: function(calEvent, jsEvent) {
                  var tooltip = '<div class="tooltipevent" style="padding: 5px;width:auto;height:auto;background:yellow;position:absolute;z-index:10001;">' + ((calEvent.start != null) ? moment(calEvent.start._i).format('HH:mm:ss') : "") + " " + ((calEvent.end != null) ? moment(calEvent.end._i).format('HH:mm:ss') : "") + " <br />" + calEvent.title + '</div>';
                  var $tooltip = $(tooltip).appendTo('body');

                  $(this).mouseover(function(e) {
                      $(this).css('z-index', 10000);
                      $tooltip.fadeIn('500');
                      $tooltip.fadeTo('10', 1.9);
                  }).mousemove(function(e) {
                      $tooltip.css('top', e.pageY + 10);
                      $tooltip.css('left', e.pageX + 20);
                  });
              },
              eventMouseout: function(calEvent, jsEvent) {
                  $(this).css('z-index', 8);
                  $('.tooltipevent').remove();
              }
          };

          try {
              var calendar = $('#calendar').fullCalendar(myOptions);
          } catch(e) {}
        }
    }, 1000);

    // $(document).find('.datetimepicker').datetimepicker();
    $(document).find('input.datetimepicker').focus(function() {
        $(this).datetimepicker({autoclose: true});

        $(document).find('input.datetimepicker').datetimepicker('hide');
        $(this).datetimepicker('show');

        $(this).on('changeDate', function(ev) {
            $(this).datetimepicker('hide');
        });
    });

    (function($) {
        $.fn.datepickerFunctions = function () {
            let datesDisabled = (typeof $(this).data('disabled-dates') !== typeof undefined) ? $(this).data('disabled-dates').split(',') : [];

            $(this).datetimepicker({autoclose: true, minView: 2, format: 'yyyy-mm-dd', datesDisabled: datesDisabled});

            // $(document).find('input.datepicker').datetimepicker('hide');
            $(this).datetimepicker('show');

            $(this).on('changeDate', function(ev) {
                if ($.fn.setTotalDays) {
                    $(this).setTotalDays();
                }
                $(this).datetimepicker('hide');
            });
        };
    })(jQuery);

    $(document).find('input.datepicker').focus(function() {
        $(this).datepickerFunctions();
    });

    $(document).find("#plus-notes").on("click", function() {
        let clone         = $("#row-notes").clone()
                                           .prop("id", "new-notes")
                                           .find("#plus-notes").prop("id", "minus-notes").end()
                                           .find("#minus-notes i").removeClass("fa-plus").addClass("fa-trash").end()
                                           .find("input").val("").datetimepicker({autoclose: true, minView: 2, format: 'yyyy-mm-dd'}).end()
                                           .find("textarea").val("").end(),
            clonnedNotes = $("#cloned-notes");

        if (clonnedNotes) {
            clonnedNotes.before(clone);
        }
    });

    $(document).on("click", "#minus-notes", function() {
        let self = $(this),
            div  = self.parents("div#new-notes").get(0);

        if (div) {
            div.remove();
        }
    });

    if ($("#showtime").length > 0) {
        setTimeout(function() {
            $.backstretch("img/brad_lock_screen_dark.jpg", {
                speed: 500
            });
            getTime();
          }, 400);
    }

    $(document).find(".nav-tabs li").on("click", function() {
        let self = $(this);

        self.parent("ul").find("li").each(function() {
            $(document).find(".tab-panel.active").removeClass("active");
            self.addClass("active");

            let hashId = self.find("a").attr('href');

            window.location.hash = hashId;

            $(document).find(".tab-pane.active").removeClass("active");
            $(document).find(hashId).addClass("active");
        });
    });

    var locationHash = window.location.hash;
    if (locationHash != null) {
        if (locationHash == '#overview' || locationHash == '#edit') {
            $(document).find("a[href='"+locationHash+"']").parent("li").click();
        } else if (locationHash == '#loginModel') {
            $(document).find(".loginModel").click();
        }
    }

    $('#loginModel').on('hidden.bs.modal', function () {
        window.location.hash = '';
    });

    chatBodyScroll();

    $("#menu-toggle").on('click', function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
  $(".checkall").on('click', function () {
    var status = $(this).attr('checked');
    $(this).parent().parent().parent().find('input').attr('checked', !status);
  });
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();
  $('.popover-dismiss').popover({
    trigger: 'focus'
  });
  $(".deleteBtn").on('click', function () {
    var form = $(this).parent();
    form = (form && form.length > 1) ? form[0] : form;
    var message = $(this).attr('data-confirm-message');
    bootbox.confirm({
      message: message,
      buttons: {
        confirm: {
          className: 'btn-primary'
        },
        cancel: {
          className: 'btn-danger'
        }
      },
      locale: window.appLocale,
      callback: function callback(result) {
        if (result) form.submit();
      }
    });
  });
  $(".confirmBtn").on('click', function (event) {
    event.preventDefault();
    var message = $(this).attr('data-confirm-message');
    var url = $(this).attr('href');
    bootbox.confirm({
      message: message,
      buttons: {
        confirm: {
          className: 'btn-primary'
        },
        cancel: {
          className: 'btn-danger'
        }
      },
      locale: window.appLocale,
      callback: function callback(result) {
        if (result) window.location = url;
      }
    });
  });
  

  $(".changeQuantity").on('click', function (event) {
    event.preventDefault();
    var title = $(this).attr('data-title'),
        value = $(this).attr('data-value'),
        form  = $(this).parent();

    bootbox.prompt({
      title: title,
      centerVertical: true,
      inputType: 'number',
      value: value,
      callback: function(result){ 
          if (result) {
            form.find("#qty").val(result);

            form.submit();
          }
      }
    });
  });

  setTimeout(function() {
    $(document).find(".saveNotes").on('click', function (event) {
      event.preventDefault();
      var form = $(this).parents('form:first');

      form.find("#newNotes").val('');

      bootbox.prompt({
        title: "Create New",
        centerVertical: true,
        inputType: 'text',
        placeholder: "Name of note",
        callback: function(result){
            if (result) {
              form.find("#newNotes").val(result);

              form.submit();
            }
        }
      });
    });

    $(".deleteBtnNotes").on('click', function (event) {
      event.preventDefault();
      var form = $(this).parents('form:first');
      var message = $(this).attr('data-confirm-message');

      form.find("#deletedId").val('');

      bootbox.confirm({
        message: message,
        buttons: {
          confirm: {
            className: 'btn-primary'
          },
          cancel: {
            className: 'btn-danger'
          }
        },
        locale: window.appLocale,
        callback: function callback(result) {
          if (result) {
            let id = $("#currentId").val();

            form.find("#deletedId").val(id);

            form.submit();
          }
        }
      });
    });

    $(".deleteBtnCalendar").on('click', function (event) {
      event.preventDefault();
      var form = $(this).parents('form:first');
      var message = $(this).attr('data-confirm-message');

      form.find("#isDelete").val('');

      bootbox.confirm({
        message: message,
        buttons: {
          confirm: {
            className: 'btn-primary'
          },
          cancel: {
            className: 'btn-danger'
          }
        },
        locale: window.appLocale,
        callback: function callback(result) {
          if (result) {
            form.find("#isDelete").val('true');

            form.submit();
          }
        }
      });
    });
  }, 3000);

  $("select[name=MAIL_DRIVER]").change(function () {
    var val = $(this).val();

    if (val == 'smtp') {
      $("#smtp_fields").removeClass('d-none');
    } else {
      $("#smtp_fields").addClass('d-none');
    }
  });
  $(".social-auth-provider").on('click', function () {
    var name = $(this).attr('name');
    var checked = $(this).is(':checked');

    if (checked) {
      $("#" + name + "-settings").removeClass('d-none');
    } else {
      $("#" + name + "-settings").addClass('d-none');
    }
  });
  $(".toEmails").on('click', function () {
    var html = $($(this).data('html')).clone().prop("class", "");
    var form = $($(this).data('html'));

    bootbox.confirm({
      message: html,
      buttons: {
        confirm: {
          className: 'btn-primary'
        },
        cancel: {
          className: 'btn-danger'
        }
      },
      locale: window.appLocale,
      callback: function callback(result) {
        if (result) {
          form.submit();
        }
      }
    });
  });
  $(".view-details").on('click', function () {
    let id   = $(this).data('id'),
        url  = $(this).data('url'),
        html = $("#view-details-" + id).html();

    bootbox.dialog({
      message: html,
      size: 'large',
      buttons: {
        edit: {
          className: 'btn-primary',
          callback: function() {
            window.location.href = url;
          }
        },
        cancel: {
          className: 'btn-danger'
        }
      },
      locale: window.appLocale
    });
  });
  $(".moveItem").on('click', function () {
      event.preventDefault();

      var form    = $(this).parent(),
          form    = (form && form.length > 1) ? form[0] : form,
          message = $("." + $(this).attr('data-html')).clone().removeClass("d-none");

      var dialog = bootbox.dialog({
          message: message,
          callback: function callback(result) {
              if (result) form.submit();
          }
      });
  });

  $(".support").on('click', function () {
      event.preventDefault();

      var form    = $(this).parent(),
          form    = (form && form.length > 1) ? form[0] : form,
          message = $("." + $(this).attr('data-html')).clone().removeClass("d-none");

      var dialog = bootbox.dialog({
          message: message,
          callback: function callback(result) {
              if (result) form.submit();
          }
      });
  });

  $(".createCoachings").on('click', function () {
      event.preventDefault();

      var form    = $(this).parent(),
          form    = (form && form.length > 1) ? form[0] : form,
          id      = $(this).attr('data-id'),
          message = $("." + $(this).attr('data-html')).clone().removeClass("d-none");

      var dialog = bootbox.dialog({
          message: message,
          callback: function callback(result) {
              if (result) form.submit();
          }
      });

      $(document).find('input.datepicker').focus(function() {
          $(this).datepickerFunctions();
      });

      (function($) {
          $.fn.setTotalDays = function () {
              let start     = $(document).find('.coachings-create-model-' + id).not('.d-none').find('input.started_at-' + id),
                  completed = $(document).find('.coachings-create-model-' + id).not('.d-none').find('input.finished_at-' + id);

              if (start.val() && completed.val() && start.val().length > 0 && completed.val().length > 0) {
                  $(document).find('.coachings-create-model-' + id).not('.d-none').find('input.total_days-' + id).val(datediff(parseDate(start.val(), completed.val())) + 1);
              } else {
                  $(document).find('.coachings-create-model-' + id).not('.d-none').find('input.total_days-' + id).val(0);
              }
          }
      })(jQuery);
  });

  $(".updateCoachings").on('click', function () {
      event.preventDefault();

      var form    = $(this).parent(),
          form    = (form && form.length > 1) ? form[0] : form,
          id      = $(this).attr('data-id'),
          message = $("." + $(this).attr('data-html')).clone().removeClass("d-none");

      var dialog = bootbox.dialog({
          message: message,
          callback: function callback(result) {
              if (result) form.submit();
          }
      });
  });

  $(".showLogBtn").on('click', function () {
      event.preventDefault();

      var message = $("." + $(this).attr('data-html')).clone().removeClass("d-none");

      var dialog = bootbox.dialog({
          message: message
      });
  });

  $(document).on("click", ".close-model", function() {
      bootbox.hideAll();
  });

  $(".addChatGroups").on('click', function (event) {
    event.preventDefault();

    var title = $(this).attr('data-title'),
        form    = $(this).parent(),
        form    = (form && form.length > 1) ? form[0] : form,
        message = $("." + $(this).attr('data-html')).clone().removeClass("d-none");

      var dialog = bootbox.dialog({
          title: title,
          message: message,
          callback: function callback(result) {
              if (result) form.submit();
          }
      });
  });

  $(".addChatUsers").on('click', function (event) {
    event.preventDefault();

    var title = $(this).attr('data-title'),
        form    = $(this).parent(),
        form    = (form && form.length > 1) ? form[0] : form,
        message = $("." + $(this).attr('data-html')).clone().removeClass("d-none");

      var dialog = bootbox.dialog({
          title: title,
          message: message,
          callback: function callback(result) {
              if (result) form.submit();
          }
      });
  });

  $(".editChatGroups").on('click', function (event) {
    event.preventDefault();

    var title = $(this).attr('data-title'),
        form    = $(this).parent(),
        form    = (form && form.length > 1) ? form[0] : form,
        message = $("." + $(this).attr('data-html')).clone().removeClass("d-none");

      var dialog = bootbox.dialog({
          title: title,
          message: message,
          callback: function callback(result) {
              if (result) form.submit();
          }
      });
  });

    $(document).find("input[name='is_daily']").on("click", function() {
        let self  = $(this),
            value = self.val(),
            customDaysDiv = $("#custom_days");

        if (customDaysDiv) {
            if (value == '0') {
                customDaysDiv.fadeIn(200);
            } else {
                customDaysDiv.fadeOut(200);
            }
        }
    });

    $(document).on('click', '.panel-heading span.clickable', function(e){
        var $this = $(this);

        if(!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });

    $(document).on('click', '.tablinks', function(e) {
        var $this = $(this),
            tab   = $this.data('id'),
            week  = $this.data('week')
            day   = $this.data('day');

        $(document).find('.tablinks').removeClass('active');
        $this.addClass('active');

        $(document).find('div[class*="tasks-'+week+'"]').fadeOut(10);
        $(document).find('.' + tab + '-' + week + '-' + day).fadeIn(200);
    });

    if ($(document).find(".emojis") && $(document).find(".emojis").length > 0) {
        setTimeout(function() {
            $(document).find(".emojis").emojioneArea();

            $(document).find(".emojis").each(function() {
                let self = $(this);

                self[0].emojioneArea.on("emojibtn.click", function(btn, event) {
                    self[0].emojioneArea.hidePicker();
                });
            });
        }, 800);
    }

    if ($(document).find("#profile_photo") && $(document).find("#profile_photo").length > 0) {
        $(document).find("#profile_photo").croppie('destroy');

        var $profilePhoto = $(document).find("#profile_photo").croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'circle'
            },
            boundary: {
                width: 300,
                height: 300
            }
        });

        $(document).find("#imgProfileUpload").on('change', function () {
            let reader = new FileReader(),
                self   = $(this);

            reader.onload = function (e) {
                $profilePhoto.croppie('bind', {
                    url: e.target.result
                }).then(function() {});
            }

            reader.readAsDataURL(this.files[0]);

            reader.addEventListener("load", function(e) {
                $(document).find("#imgProfileIconUpload").val(e.target.result);
            });

            $("." + self.attr('data-html')).modal('show');
        });

        $('.profile-photo-save').on('click', function () {
            $profilePhoto.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $(document).find("#imgProfileIconUpload").val(resp);
            });
        });
    }
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

function readURLProfile(input) {
    if (input.files) {
        $('#preview-profile-image').html("");

        $.each(input.files, function(index) {

          var reader = new FileReader();

          reader.onload = function(e) {
              $('#preview-profile-image').append('<div class="col-md-4"><img src="' + e.target.result + '" style="width:100%;height: 100%;object-fit: cover;" /></div>');
          }

          reader.readAsDataURL(input.files[index]);
        });
    }
}

function getTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    // add a zero in front of numbers<10
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('showtime').innerHTML = h + ":" + m + ":" + s;
    t = setTimeout(function() {
      getTime()
    }, 500);
}

function checkTime(i) {
    if (i < 10) {
      i = "0" + i;
    }

    return i;
}

function chatBodyScroll()
{
    $(document).find('.chat-body').animate({
        scrollTop: ($('.chat-body .group-rom:last').length > 0) ? $('.chat-body .group-rom:last').position().top : false
    }, 'slow');
}

// new Date("dateString") is browser-dependent and discouraged, so we'll write
// a simple parse function for U.S. date format (which does no error checking)
function parseDate(start, end) {
    return new Date(end) - new Date(start);
}

function datediff(diffInMs) {
    // Take the difference between the dates and divide by milliseconds per day.
    // Round to nearest whole number to deal with DST.
    return Math.round((diffInMs)/(1000*60*60*24)) || 0;
}

$("#imgUpload").change(function() {
    readURL(this);
});

$("#imgProfileUpload").change(function() {
    readURLProfile(this);
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
