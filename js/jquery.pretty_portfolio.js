// Adapted from: http://jqueryfordesigners.com/coda-slider-effect/
jQuery(document).ready(function()
{
  var panels = jQuery('#slider .scrollContainer > div');
  var container = jQuery('#slider .scrollContainer');
  var horizontal = true;

  if (horizontal)
  {
    panels.css({'float' : 'left', 'position' : 'relative' });
    container.css('width', panels[0].offsetWidth * panels.length);
  }

  var scroll = jQuery('#slider .scroll').css('overflow', 'hidden');
  scroll
    .before('<img class="scrollButtons left" src="/wp-content/plugins/pretty_portfolio/images/scroll_left.png" />')
    .after('<img class="scrollButtons right" src="/wp-content/plugins/pretty_portfolio/images/scroll_right.png" />');

  function selectNav() {
    jQuery(this)
      .parents('ul:first')
        .find('a')
          .removeClass('selected')
        .end()
      .end()
      .addClass('selected');
  }

  jQuery('#slider .navigation').find('a').click(selectNav);

  function trigger(data)
  {
    var el = jQuery('#slider .navigation').find('a[href$="' + data.id + '"]').get(0);
    selectNav.call(el);
  }

  if (window.location.hash) {
    trigger({ id : window.location.hash.substr(1) });
  } else {
    jQuery('ul.navigation a:first').click();
  }

  var offset = parseInt((horizontal ? container.css('paddingTop') : container.css('paddingLeft')) || 0) * -1;
  var scrollOptions = {
    target: scroll, 
    items: panels,
    navigation: '.navigation a',
    prev: 'img.left', 
    next: 'img.right',
    axis: 'xy',
    onAfter: trigger,
    offset: offset,
    duration: 750,
    easing: 'expoinout'
  };

  jQuery('#slider').serialScroll(scrollOptions);
  jQuery.localScroll(scrollOptions);
  jQuery.localScroll.hash(scrollOptions);
  
  jQuery('a.lightbox').lightBox({
   imageLoading:     PLUGIN_PATH+'/images/lightbox-ico-loading.gif',
   imageBtnPrev:     PLUGIN_PATH+'/images/lightbox-btn-prev.gif',
   imageBtnNext:     PLUGIN_PATH+'/images/lightbox-btn-next.gif',
   imageBtnClose:    PLUGIN_PATH+'/images/lightbox-btn-close.gif',
   imageBlank:       PLUGIN_PATH+'/images/lightbox-blank.gif'
  });
});
