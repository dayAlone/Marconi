</div>
<div class="scroll-fix"></div>
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-sm-3 col-sm-4 col-md-3">
        <div class="copyright">© 2014 ООО «Мегатрон» </div>
      </div>
      <div class="col-sm-3 col-md-2">
        <div class="contacts"><span>Москва,  ул. адмирала макарова, 8 <br></span><a href="mailto:info@fmarconi.ru" class="contacts_link">info@fmarconi.ru</a></div>
      </div>
      <div class="col-sm-2">
        <div class="map"><a href="#">карта сайта</a></div>
      </div>
      <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
        <?php
              $APPLICATION->IncludeComponent("bitrix:menu", "social", 
              array(
                  "ALLOW_MULTI_SELECT" => "Y",
                  "MENU_CACHE_TYPE"    => "A",
                  "ROOT_MENU_TYPE"     => "social",
                  "MAX_LEVEL"          => "1",
                  ),
              false);
          ?>
      </div>
      <div class="col-md-3 col-lg-3 visible-md-block visible-lg-block"><a href="http://radia.ru" target="_blank" class="radia"><svg width="107" height="108" viewBox="0 0 107 108" xmlns="http://www.w3.org/2000/svg"><path d="M96.25 92.925c-4.47-6.435-25.285-36.877-25.285-36.877 6.12-2.36 10.922-5.878 14.412-10.548 3.488-4.67 5.234-9.85 5.234-15.54 0-4.292-.832-8.213-2.494-11.755-1.666-3.543-4.082-6.603-7.246-9.18-3.17-2.576-7.006-4.562-11.516-5.958C54.33-1.586 32.944 1.09 17.32 1.027 12.198 1.007 8.556.902 3.713.23c-.762-.106-2.2-.57-3 .23-.99.99-.865 1.93-.134 3.023 1.99 2.987 3.41 5.08 5.377 8.047 2.02 3.05 5.59 3.56 8.92 3.792 4.52.313 9.04.31 13.57.222 2.67-.053 5.336-.157 8.003-.277 6.075-.276 12.23-.623 18.27.3 8.01 1.228 14.663 6.41 13.96 15.147-.438 5.418-2.494 10.398-7.05 13.495-5.76 3.91-12.785 4.07-19.49 4.07-3.84 0-4.062-.15-8.164-.57-.95-.098-1.855.126-2.185 1.05-.403 1.13-.086 1.46.71 2.62.675.983 4.135 6.18 5.18 7.703.936 1.364 2.637 1.965 4.143 2.41C51.16 64.26 56.69 70.45 61.89 78.353c2.65 4.028 5.16 8.143 7.9 12.108 1.516 2.197 3.102 4.346 4.81 6.395 3.967 4.753 9.185 6.884 15.025 8.455 4.668 1.255 9.81 1.66 13.926 1.75 4.324.094 3.69-2.655 2.05-3.895-3.733-2.825-6.25-5.77-9.35-10.24" id="Imported-Layers" fill="#fff" fill-rule="evenodd"/></svg>
          <div class="radia__content">разработка сайта <br>radia interactive</div></a></div>
    </div>
  </div>
</footer>

<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>

    <div class="pswp__scroll-wrap">

        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>


                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>
<?$APPLICATION->ShowViewContent('footer');?>
</body>
</html>