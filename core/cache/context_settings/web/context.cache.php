<?php  return array (
  'config' => 
  array (
  ),
  'resourceMap' => 
  array (
    0 => 
    array (
      0 => 1,
      1 => 21,
      2 => 23,
      3 => 24,
    ),
  ),
  'webLinkMap' => 
  array (
  ),
  'eventMap' => 
  array (
    'OnChunkFormPrerender' => 
    array (
      1 => '1',
    ),
    'OnDocFormPrerender' => 
    array (
      2 => '2',
      1 => '1',
    ),
    'OnFileEditFormPrerender' => 
    array (
      1 => '1',
    ),
    'OnFileManagerUpload' => 
    array (
      4 => '4',
    ),
    'OnPluginFormPrerender' => 
    array (
      1 => '1',
    ),
    'OnRichTextBrowserInit' => 
    array (
      1 => '1',
    ),
    'OnRichTextEditorRegister' => 
    array (
      1 => '1',
    ),
    'OnSnipFormPrerender' => 
    array (
      1 => '1',
    ),
    'OnTempFormPrerender' => 
    array (
      1 => '1',
    ),
    'OnTVInputPropertiesList' => 
    array (
      2 => '2',
    ),
    'OnTVInputRenderList' => 
    array (
      2 => '2',
    ),
  ),
  'pluginCache' => 
  array (
    1 => 
    array (
      'id' => '1',
      'source' => '0',
      'property_preprocess' => '1',
      'name' => 'TinymceWrapper',
      'description' => 'Survive upgrades! Duplicate the Chunks, retain their original names with an added SUFFIX. Create dedicated PropertySet properties. Default properties will be overridden.
Include no script tags in any of the chunks.
Please be awesome!',
      'editor_type' => '0',
      'category' => '1',
      'cache_type' => '0',
      'plugincode' => '/*TinymceWrapper transforms all MODX native and non-native textareas (introtext, description, content, RichTVs, File/Image TVs, Quick Update/Create, MIGX TVs etc etc)

plugin fires at
OnRichTextEditorRegister
OnDocFormPrerender 

(optionally)
OnTempFormPrerender,OnSnipFormPrerender,OnChunkFormPrerender,OnPluginFormPrerender,OnFileEditFormPrerender

OnManagerPageInit
  FOR ALL ROUND ENJOYMENT THROUGH OUT THE MANAGER
  1. for (quick update/create resources & elements (for Ace or CodeMirror),
  2. for top nav media link for elFinder, Responsive FileManager and Roxy custom file browsers,


-------------------Roadmap:
-Create more beautiful themes;
-Look for more ways to be awesome;
---------------------------

Dedicated to those who have cried
---------------------------

http://www.leofec.com/modx-revolution/
-donshakespeare in the MODx forum
To God, almighty, be all the glory.
*/

$modx->getService(\'error\',\'error.modError\', \'\', \'\');
$modxEventName = $modx->event->name;
//let us tell System Settings that we have a new RTEditor
if ($modxEventName == \'OnRichTextEditorRegister\') {
  $modx->event->output(\'TinymceWrapper\');
  return;
}

//let us get MODx browser callback ready to fire
if ($modxEventName == \'OnRichTextBrowserInit\' && $autoFileBrowser == \'modxNativeBrowser\') {
 $modx->controller->addJavascript(MODX_ASSETS_URL.\'components/tinymcewrapper/browserConnectors/browser.js\');
  $modx->event->output(\'twBrowserCallback\');
  return;
}

//whether the user has RTE enabled in System Settings
$useEditor = $modx->getOption(\'use_editor\');
//is our awesome wrapper the set one?
$whichEditor = $modx->getOption(\'which_editor\');
$whichElementEditor = $modx->getOption(\'which_element_editor\');
//whether the user has RTE set to default for all resources in System Settings
$richtext_default = $modx->getOption(\'richtext_default\');

$sp = $scriptProperties;
//let\'s grab a few things from our plugin\'s defualt properties property set
$activate = $modx->getOption(\'activateTinyMCE\', $sp);
$activateAceOrCodeMirror = $modx->getOption(\'activateAceOrCodeMirror\', $sp);
$useAceOrCodeMirrorEveryWhere = $modx->getOption(\'useAceOrCodeMirrorEveryWhere\', $sp);
$useAceOrCodeMirrorOnElementsFiles = $modx->getOption(\'useAceOrCodeMirrorOnElementsFiles\', $sp);
$useAceOrCodeMirrorOnResources = $modx->getOption(\'useAceOrCodeMirrorOnResources\', $sp);
$activateAceOrCodeMirrorOnNewResource = $modx->getOption(\'activateAceOrCodeMirrorOnNewResource\', $sp);
$activateAceOrCodeMirrorOnRichText = $modx->getOption(\'activateAceOrCodeMirrorOnRichText\', $sp);
$AceTHEME = $modx->getOption(\'AceTHEME\', $sp);
$AceSrc = $modx->getOption(\'AceSrc\', $sp);
$CodeMirrorTHEME = $modx->getOption(\'CodeMirrorTHEME\', $sp);
$CodeMirrorSrc = $modx->getOption(\'CodeMirrorSrc\', $sp);
$jQuerySrc = $modx->getOption(\'jQuery\', $sp);
$tinySrc = $modx->getOption(\'tinySrc\', $sp);
$newResources = $modx->getOption(\'newResources\', $sp);
$introtext = $modx->getOption(\'Introtext\', $sp);
$intro = \'\';
$description = $modx->getOption(\'Description\', $sp);
$desc = \'\';
$content = $modx->getOption(\'Content\', $sp);
$con = \'\';
$tvs = $modx->getOption(\'TVs\', $sp);
$tvAddict = $modx->getOption(\'tvAddict\', $sp);
$tvSuperAddict = $modx->getOption(\'tvSuperAddict\', $sp);
$autoFileBrowser = $modx->getOption(\'autoFileBrowser\', $sp);
$browserTopNAVsubtext = $modx->getOption(\'browserTopNAVsubtext\', $sp);
$fileImageTVs = $modx->getOption(\'fileImageTVs\', $sp);
$browserTVs = \'\';
$disable = $modx->getOption(\'disableEnableCheckbox\', $sp);
//if a suffix is entered, all the chunks in use must have the same suffix. (e.g. TinymceWrapperIntrotext-suff, TinymceWrapperDescription-suff,TinymceWrapperContent-suff,TinymceWrapperTvs-suff)
$suffix = $modx->getOption(\'chunkSuffix\', $sp);
$fileManagerTopNavLink = $modx->getOption(\'fileManagerTopNavLink\', $sp);
$fileManagerTopNavModal = $modx->getOption(\'fileManagerTopNavModal\', $sp);
$fileManagerTopNavModalSkin = $modx->getOption(\'fileManagerTopNavModalSkin\', $sp);
$fileManagerTopNavModalSkin = $fileManagerTopNavModalSkin ? : \'""\';

//grab file browser options
$modxNativeBrowserRTEurl = $modx->getOption(\'modxNativeBrowserRTEurl\', $sp);
$modxNativeBrowserRTEtitle = $modx->getOption(\'modxNativeBrowserRTEtitle\', $sp);
$modxNativeBrowserTopNAVpresent = $modx->getOption(\'modxNativeBrowserTopNAVpresent\', $sp);
$modxNativeBrowserQuirkMode = $modx->getOption(\'modxNativeBrowserQuirkMode\', $sp);

$replaceDefaultFileImageTVbutton = $modx->getOption(\'replaceDefaultFileImageTVbutton\', $sp) ? : 0;

$elFinderBrowserRTEurl = $modx->getOption(\'elFinderBrowserRTEurl\', $sp);
$elFinderBrowserRTEtitle = $modx->getOption(\'elFinderBrowserRTEtitle\', $sp);
$elFinderBrowserTopNAVurl = $modx->getOption(\'elFinderBrowserTopNAVurl\', $sp);
$elFinderBrowserTopNAVtitle = $modx->getOption(\'elFinderBrowserTopNAVtitle\', $sp);
$elFinderBrowserSHORTtitle = $modx->getOption(\'elFinderBrowserSHORTtitle\', $sp);

$responsivefilemanagerBrowserRTEurl = $modx->getOption(\'responsivefilemanagerBrowserRTEurl\', $sp);
$responsivefilemanagerBrowserRTEtitle = $modx->getOption(\'responsivefilemanagerBrowserRTEtitle\', $sp);
$responsivefilemanagerBrowserTopNAVurl = $modx->getOption(\'responsivefilemanagerBrowserTopNAVurl\', $sp);
$responsivefilemanagerBrowserTopNAVtitle = $modx->getOption(\'responsivefilemanagerBrowserTopNAVtitle\', $sp);
$responsivefilemanagerBrowserSHORTtitle = $modx->getOption(\'responsivefilemanagerBrowserSHORTtitle\', $sp);

$roxyFilemanBrowserRTEtitle = $modx->getOption(\'roxyFilemanBrowserRTEtitle\', $sp);
$roxyFilemanBrowserRTEurl = $modx->getOption(\'roxyFilemanBrowserRTEurl\', $sp);
$roxyFilemanBrowserTopNAVurl = $modx->getOption(\'roxyFilemanBrowserTopNAVurl\', $sp);
$roxyFilemanBrowserTopNAVtitle = $modx->getOption(\'roxyFilemanBrowserTopNAVtitle\', $sp);
$roxyFilemanBrowserSHORTtitle = $modx->getOption(\'roxyFilemanBrowserSHORTtitle\', $sp);

//grab gallery settings
$enableImageGallery = $modx->getOption(\'enableImageGallery\', $sp);
$tinyJSONGalleryTABtitle = $modx->getOption(\'tinyJSONGalleryTABtitle\', $sp) ? : "JSON Image Gallery";
$tinyJSONGalleryTABposition = $modx->getOption(\'tinyJSONGalleryTABposition\', $sp) ? : 0;
$imageGalleryIDs = $modx->getOption(\'imageGalleryIDs\', $sp);
$galleryChunkNameKey = $modx->getOption(\'galleryChunkNameKey\', $sp);
$TinyJSONGalleryTV = $modx->getOption(\'TinyJSONGalleryTV\', $sp) ?:"TinyJSONGalleryTV";
$galleryJSfile = $modx->getOption(\'galleryJSfile\', $sp);

//grab 3rd party TinyMCE inits
$customJS = $modx->getOption(\'customJS\', $sp);
$customJSchunks = $modx->getOption(\'customJSchunks\', $sp);

//this eliminates clutter and confusion: reusuable config is the way of the past and the future - code here will be put in placeholder [[+commonTinyMCECode]]
//apply comma here, not in the chunk calling it --na na, make user leave trailing comma in commonCode Chunk

if ($enableImageGallery == 1) {
  if ($modxEventName == \'OnChunkFormPrerender\' || $modxEventName == \'OnDocFormPrerender\') {
    $galleryIsGolden = 0;
    if ($modxEventName == \'OnChunkFormPrerender\') {
      if($id){
        $thisChunkId = $id;
        $imageGalleryIDsTrue = \'\';
        if($imageGalleryIDs){
          $imageGalleryIDs = preg_replace(\'/\\s+/\', \'\', $imageGalleryIDs);
          $imageGalleryIDs = preg_replace("/[^a-z0-9,-_]+/i", \' \', $imageGalleryIDs);
          $imageGalleryIDs = explode(\',\', $imageGalleryIDs);
          if(in_array($thisChunkId, $imageGalleryIDs)) {
            $imageGalleryIDsTrue = 1;
          }
        }
        $chS = $modx->getObject("modChunk", $thisChunkId);
        $ch = $chS->get(\'name\');
        $chunkGalleryVal = $chS->get(\'content\');
        // if(in_array($thisChunkId, $imageGalleryIDs) || substr($ch, 0, strlen($galleryChunkNameKey)) === $galleryChunkNameKey && $enableImageGallery) {
        if($imageGalleryIDsTrue || strpos($ch, $galleryChunkNameKey) !== false && $enableImageGallery) {
          $modx->regClientStartupHTMLBlock(\'
            <script type="text/javascript">
              var extjsPanelTabs = "modx-chunk-tabs";
              var textareaForJSON = "modx-chunk-snippet";
              var textareaForJSONbk = "modx-chunk-snippet";
              var tinyJSONGalleryGalButtons = "#modx-action-buttons .x-toolbar-left-row";
              var tvChunkGalleryVal = \'.json_encode($chunkGalleryVal).\';
              var backendOrfrontendGallery = "backend";
              var tinyJSONGalleryTABtitle = "\'.$tinyJSONGalleryTABtitle.\'";
              var tinyJSONGalleryTABposition = \'.$tinyJSONGalleryTABposition.\';
              var modxGalleryAssetsUrl = MODx.config.assets_url;
              var galleryBackUpRTEskin = \'.$fileManagerTopNavModalSkin.\';
            </script>
          \');
          $galleryIsGolden = 1;
        }
      }
    }
    if ($modxEventName == \'OnDocFormPrerender\' && $id) {
      if($tvName = $modx->getObject(\'modTemplateVar\', array(\'name\' =>$TinyJSONGalleryTV))){
        $tvId = $tvName->get(\'id\');
        $tvGalleryVal = $resource->getTVValue($tvId);
        // $tvTemplateId = $modx->getObject(\'modTemplateVarTemplate\', array("tmplvarid" => $tvId))->get("templateid");
        if($resourceTemplateId = $resource->get("template")){
          if($tvTemplateId = $modx->getObject(\'modTemplateVarTemplate\', array("tmplvarid" => $tvId)) ){
            $tvTemplateId = $tvTemplateId->get("templateid");
            if ($tvGalleryVal || $tvGalleryVal == \'\'){
              if ($tvTemplateId == $resourceTemplateId) {
                $modx->regClientStartupHTMLBlock(\'
                  <script type="text/javascript">
                    var extjsPanelTabs = "modx-resource-tabs";
                    var textareaForJSON = "tv\'.$tvId.\'";
                    var textareaForJSONbk = "tv\'.$tvId.\'";
                    var tinyJSONGalleryGalButtons = "#modx-action-buttons .x-toolbar-left-row";
                    var tvChunkGalleryVal = \'.json_encode($tvGalleryVal).\';
                    var backendOrfrontendGallery = "backend";
                    var tinyJSONGalleryTABtitle = "\'.$tinyJSONGalleryTABtitle.\'";
                    var tinyJSONGalleryTABposition = \'.$tinyJSONGalleryTABposition.\';
                    var modxGalleryAssetsUrl = MODx.config.assets_url;
                    var galleryBackUpRTEskin = \'.$fileManagerTopNavModalSkin.\';
                  </script>
                \');
                $galleryIsGolden = 1;
              }
            }
          }
        }//////
      }
    }
    if($galleryIsGolden == 1){
      if($galleryJSfile){
        $modx->regClientStartupHTMLBlock("<script src=\'" . $galleryJSfile . "\'\'></script>");
      }
      else{
        $modx->regClientStartupHTMLBlock("<script src=\'" . MODX_ASSETS_URL . "components/tinymcewrapper/gallery/js/TinyJSONGallery.js\'></script>");
      }
    }
  }
}


if ($modxEventName == \'OnManagerPageInit\' || $modxEventName == \'OnDocFormPrerender\') {
  $commonCode = $modx->getChunk(\'TinymceWrapperCommonCode\' . $suffix);
  $commonCode = $commonCode ? $commonCode : \'\';
}

//Quick and dirty hack to allow any and all other 3rd party Extras use TinyMCE


if ($modxEventName == \'OnManagerPageInit\' && $customJS && $customJSchunks) {
  function listArray($thisList){
    $thisList = preg_replace(\'/\\s+/\', \'\', $thisList);
    $thisList = preg_replace("/[^a-z0-9,-_]+/i", \' \', $thisList);
    $thisList = explode(\',\', $thisList);
    return $thisList;
  }
  $getCustomJSchunks = "";
  $customJSchunk = listarray($customJSchunks);
  $i = 0;
  foreach ($customJSchunk as $c) {
    $customJSchunk[$i] = $modx->getChunk(\'TinymceWrapper\'.$c.$suffix)."\\n";
    $getCustomJSchunks.= $customJSchunk[$i];
    $i++;
  }
  $modx->regClientStartupHTMLBlock("<script>" . $getCustomJSchunks . "</script>");
}

//when TinyMCE is temporarily disabled, any new edit is updated before saving
$autoSaveTextAreas = \'
  function autoSaveTextAreas(selectorId){
    setTimeout(function(){
      $("#"+selectorId).on("change", function() {
        tinyMCE.get(selectorId).setContent($("#"+selectorId).val())
        // console.log(selectorId+" has been updated");//debug stuff
      })
    },500)
   }
\';

//let\'s setup some functions and file select callbacks for our supported file browsers
switch ($autoFileBrowser) {
  case \'modxNativeBrowser\':
    $browserRTEurl = $modxNativeBrowserRTEurl;
    $browserRTEtitle = $modxNativeBrowserRTEtitle;
    break;
  case \'elFinderBrowser\':
    $browserRTEurl = \'"\'.$elFinderBrowserRTEurl.\'"\';
    $browserRTEtitle = $elFinderBrowserRTEtitle;
    $browserTopNAVurl = \'\\\'\'.$elFinderBrowserTopNAVurl.\'\\\'\';
    $browserTopNAVtitle = $elFinderBrowserTopNAVtitle;
    $browserShortTitle = $elFinderBrowserSHORTtitle;
    break;
  case \'responsivefilemanagerBrowser\':
    $browserRTEtitle = $responsivefilemanagerBrowserRTEtitle;
    $browserRTEurl = $responsivefilemanagerBrowserRTEurl;
    $browserTopNAVurl = $responsivefilemanagerBrowserTopNAVurl;
    $browserTopNAVtitle = $responsivefilemanagerBrowserTopNAVtitle;
    $browserShortTitle = $responsivefilemanagerBrowserSHORTtitle;
    break;
  case \'roxyFilemanBrowser\':
    $browserRTEtitle = $roxyFilemanBrowserRTEtitle;
    $browserRTEurl = $roxyFilemanBrowserRTEurl;
    $browserTopNAVurl = $roxyFilemanBrowserTopNAVurl;
    $browserTopNAVtitle = $roxyFilemanBrowserTopNAVtitle;
    $browserShortTitle = $roxyFilemanBrowserSHORTtitle;
    break;
}


if ($autoFileBrowser == \'responsivefilemanagerBrowser\') {
  $browserFunctions=\'
    function responsive_filemanager_callback(field_id){
      thisFieldVal = $("#"+field_id).val();
      thisFieldNum = field_id.split("er");
      $("input#tv"+thisFieldNum[1]).val(thisFieldVal);
      $("#tv-image-preview-"+thisFieldNum[1]+" img").attr("title","preview by native MODx Image Browser");
      $("#"+field_id).parents(".modx-tv").find(".twImagePreview").hide().attr("src",thisFieldVal).insertBefore("#tv-image-preview-"+thisFieldNum[1]).fadeIn("slow");
      tinyMCE.activeEditor.windowManager.close();
    }
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      resp = \'.$browserRTEurl.\';
      if (resp.indexOf("?") < 0) {
        resp += "?field_id=" + field_name;
      }
      else {
        resp += "&field_id=" + field_name;
      }
      // console.log(resp); //debug stuff
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: resp,
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2
      }, {
        // oninsert: function(url) {
        //   alert("rte") //debug
        //   win.document.getElementById(field_name).value = url;
        // }
      });
    return false;
      }
  \';
}
elseif ($autoFileBrowser == \'roxyFilemanBrowser\') {
  $browserFunctions=\'
    autoFileBrowser = \'.$autoFileBrowser.\';
      function \'.$autoFileBrowser.\'(field_name, url, type, win) {
        roxyFileman = \'.$browserRTEurl.\';
        if (roxyFileman.indexOf("?") < 0) {
          roxyFileman += "?type=" + type;
        }
        else {
          roxyFileman += "&type=" + type;
        }
        roxyFileman += "&input=" + field_name + "&value=" + win.document.getElementById(field_name).value;
        if(tinyMCE.activeEditor.settings.language){
          roxyFileman += "&langCode=" + tinyMCE.activeEditor.settings.language;
        }
        tinyMCE.activeEditor.windowManager.open({
          title: "\'.$browserRTEtitle.\'",
          url: roxyFileman,
          plugins: "media",
          width : window.innerWidth / 1.2,
          height : window.innerHeight / 1.2
        }, {
          oninsert: function(url) {
            win.document.getElementById(field_name).value = url;
          }
        });
      return false;
      }
  \';
}
//thanks to Denis 
elseif ($autoFileBrowser == \'modxNativeBrowser\' && $modxNativeBrowserQuirkMode) {
  $modx->regClientStartupHTMLBlock("<style>.modx-browser {z-index: 99999!important;}</style>");
  $browserFunctions=\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      var path = url.substring(0,url.lastIndexOf("/")+1);
      var w = MODx.load({
        xtype: "modx-browser",
        multiple: true,
        //If there is no path, use default
        openTo: path || \'.$modxNativeBrowserRTEurl.\',
        listeners: {
          "select": {fn:function(data) {
            win.document.getElementById(field_name).value = data.relativeUrl;
            MODx.fireEvent("select",data);
          },scope:this}
        }
      });
      w.show();
    }
  \';
}
elseif ($autoFileBrowser == \'modxNativeBrowser\') {
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: \'.$browserRTEurl.\',
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2,
        classes: "twAutoBrowser",
        onPostRender: function(){
          $(".mce-twAutoBrowser iframe").attr("id","twAutoBrowser").load(function(){
            var checkRTEbuttons = setInterval(function() {
              if ($("#twAutoBrowser").contents().find(".modx-browser-rte-buttons").length) {
                $("#twAutoBrowser").contents().find(".modx-browser-rte-buttons").hide();
                clearInterval(checkRTEbuttons);
              }
            }, 50);
          })
        },
      }, {
        oninsert: function(url) {
          win.document.getElementById(field_name).value = url;
        }
      });
    return false;
    }
  \';
}
else{
  $browserFunctions =\'
    autoFileBrowser = \'.$autoFileBrowser.\';
    function \'.$autoFileBrowser.\'(field_name, url, type, win) {
      tinyMCE.activeEditor.windowManager.open({
        title: "\'.$browserRTEtitle.\'",
        url: \'.$browserRTEurl.\',
        width : window.innerWidth / 1.2,
        height : window.innerHeight / 1.2
      }, {
        oninsert: function(url) {
          win.document.getElementById(field_name).value = url;
        }
      });
    return false;
    }
  \';
}

//what happens when you click the enable/disable checkbox
//also for MIGX TVs

$enableDisableTinyClick = \'
  function tinyTVcheck(editor) {
    
    if(tinymce.get(editor) && !tinyMCE.get(editor).getParam("twExoticMarkdownEditor",false)){
      autoSaveTextAreas(editor);
      if($("input[data-tiny="+editor+"]").is(":checked")){
        tinymce.get(editor).hide();
        $("input[data-tiny="+editor+"]").attr("title","Show TinyMCE");
      }
      else{
        tinymce.get(editor).show();
        $("input[data-tiny="+editor+"]").trigger("change").attr("title","Temporarily Hide TinyMCE");
      }
    }
    else{
      $("input[data-tiny="+editor+"]").remove();
      if(typeof tinymce !== "undefined"){
        tinymce.activeEditor.windowManager.alert("Not applicable here");
      }
      else{
        alert("Not applicable here");
      }
    }
  }
  // $(".tinyTVcheck").on("mouseup",function() {
  //   autoSaveTextAreas($(this).attr("data-tiny"));
  //   if (this.checked) {
  //     tinymce.get($(this).attr("data-tiny")).hide();
  //     $(this).trigger("change").attr("title","Enable TinyMCE");
  //   }
  //   else{
  //     tinymce.get($(this).attr("data-tiny")).show();
  //     $(this).trigger("change").attr("title","Disable TinyMCE");
  //   }
  //   });
\';

//lock the below to this event, to prevent spill over
if ($modxEventName == \'OnDocFormPrerender\') {
  $enableDisableTiny = \'\';
  //is the enable/disable TinyMCE option selected? If so, let\'s create all the buttons at once; this will be split later on. This is good for TVs that have default content, and user wishes to revert. Disable TinyMCE, then revert.
  //there are two $("#ta") below; don\'t ask me why the Articles\' Container/Child are has own thing going own here
  if ($disable == 1) {
  //prepend is better than append - you\'ll see!!!
    $enableDisableTiny = \'
    $("#modx-resource-introtext").parent().parent().prepend("<input data-tiny=\\\'modx-resource-introtext\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"modx-resource-introtext\\")\\\' />&nbsp;&nbsp;&nbsp;");@
    $("#modx-resource-description").parent().parent().prepend("<input data-tiny=\\\'modx-resource-description\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"modx-resource-description\\")\\\' />&nbsp;&nbsp;&nbsp;");@
    $("#ta").parents("#modx-resource-content").find(".x-panel-header-text").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"ta\\")\\\' />&nbsp;&nbsp;&nbsp;");
    if($("#articles-box-publishing-information").length){
      $("#ta").parents(".contentblocks_replacement").find("label[for=ta]").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' class=\\\'tinyTVcheck\\\' onmouseup=\\\'tinyTVcheck(\\"ta\\")\\\' />&nbsp;&nbsp;&nbsp;");
    }
    if($("#modx-resource-tabs__articles-tab-template").length){
      $("#modx-resource-header").append("<p id=\\\'tinyArtAlert\\\' style=\\\'width:70%;margin:0 auto;background-color:#32AB9A;padding:10px;border-radius:10px;color:white;text-align:center;\\\'><b>TinymceWrapper Raw Code Protection:</b><br>Check this Articles Container > Template [Tab] > Content, before saving.<br>Unchecking the box will not only disable but remove TinyMCE, thus protecting your code</p>");
      $("#ta").parent().parent().find("label[for=ta]").prepend("<input data-tiny=\\\'ta\\\' checked=\\\'checked\\\' title=\\\'Remove TinyMCE \\\' type=\\\'checkbox\\\' class=\\\'tinyTVchecky\\\' onmouseup=\\\'javascript:tinymce.get(\\"ta\\").remove();$(this).remove();$(\\"#tinyArtAlert\\").fadeOut().remove();\\\' />&nbsp;&nbsp;&nbsp;");
    }
  \';
  //let\'s split the enable/disable checkboxes so that they don\'t appear randomly or unexpectedly
  $enableDisableTiny = explode("@", $enableDisableTiny);
  }

  //All TVs are here nicely placed independent of strict conditions, just in case we want to activate TVS even when RTE is disabled for a particular resource
  if ($tvs == 1) {
    $tvsChunk = $modx->getChunk(\'TinymceWrapperTVs\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
    if ($tvsChunk) {
      //let\'s remove the checkboxes that enables/disables TinyMCE for the TVs
      //let\'s allow the TV reset button to work through TinyMCE, either enabled or disabled - textareas are updated .on change + the delay is neccesary since we are pseudo binding to existing click event
      if ($disable == 1) {
        $richTv = \'
          if($(".modx-richtext").length){
            $(".modx-richtext").css({"overflow": "auto", "width": "100%", "min-height": "100px", "resize": "vertical"});
            function updateReset(updateR){
              if(tinymce.get(updateR)){
                setTimeout(function(){
                  tinyMCE.get(updateR).setContent($("#"+updateR).val());
                  // console.log(updateR+" has been updated");//debug stuff
                },200)
              }
            }
            $.each($(".modx-richtext"), function() {
              var updateR = $(this).attr("id");
              $(this).parents(".modx-tv").find(".modx-tv-reset").attr("data-tiny",this.id).on("click", function(){
                updateReset($(this).attr("data-tiny"));
              });
              $(this).parent().parent().prepend("<input data-tiny=\\\'" + this.id + "\\\' checked=\\\'checked\\\' title=\\\'Temporarily Hide TinyMCE\\\' type=\\\'checkbox\\\' onmouseup=\\\'tinyTVcheck(\\""+this.id+"\\")\\\' />");
            });
            setTimeout(function(){
              \' . $tvsChunk . \'
            },1000);
          }
        \';
      } 
      else {
        $richTv = \'
          if($(".modx-richtext").length){
            function updateReset(updateR){
              setTimeout(function(){
                tinyMCE.get(updateR).setContent($("#"+updateR).val());
                // console.log(updateR+" has been updated");//debug stuff
              },200)
            }
            $.each($(".modx-richtext"), function() {
              var updateR = $(this).attr("id");
              $(this).parents(".modx-tv").find(".modx-tv-reset").attr("data-tiny",this.id).on("click", function(){
                updateReset($(this).attr("data-tiny"));
              });
            });
            setTimeout(function(){
              \' . $tvsChunk . \'
            },1000);
          }
        \';
      }
    }
  }
  if ($fileImageTVs == 1) {
    /*
    - append hidden input#tinyFileImageBrowser to the body so that we have at least one active editor, in case the user has disabled TinyMCE for all other textareas and TVs
    - do some magic: create the respective image and file twBrowser buttons with appropriate properties when the page is really ready
    - create rudimentary image prev something similar to the native MODx\' file browser
    - init hidden #tinyFileImageBrowser
    - Create tinymce #tinyFileImageBrowser on condition; give a definite CSS theme (only when one is not already loaded) to avoid overriding issues. Allow cross-browser amiability by setting to inline:true
    - add twBrowser menu button to MODx Media drop down - depends on the option fileImageTvs
    - NOTE - Roxy don\'t have a callback...no preview 
    */
    $browserTVs = \'
      function imageFileTVpop(field_name, url, type, win) {
        thisUrl = \'.$browserRTEurl.\';
        if (thisUrl.indexOf("dialog") > 0) {
            thisUrl = thisUrl.replace("popup=1", "popup=0");
          if (thisUrl.indexOf("?") < 0) {
            thisUrl += "?field_id="+field_name;
          }
          else {
            thisUrl += "&field_id="+field_name;
          }
        }
        if (thisUrl.indexOf("fileman") > 0) {
          if (thisUrl.indexOf("?") < 0) {
            thisUrl += "?type=" + type;
          }
          else {
            thisUrl += "&type=" + type;
          }
          thisUrl += "&input=" + field_name + "&value=" + document.getElementById(field_name).value;
        }

        tinyMCE.activeEditor.windowManager.open({
          title: "\'.$browserRTEtitle.\'",
          url: thisUrl,
          width : window.innerWidth / 1.2,
          height : window.innerHeight / 1.2
        }, {
          oninsert: function(url) {
            $("#"+field_name).val(url);
            thisFieldNum = field_name.split("er");
            $("input#tv"+thisFieldNum[1]).val(url);
            $("#tv-image-preview-"+thisFieldNum[1]+" img").hide().attr({"src":url, "title":"preview by \'.$browserShortTitle.\'"}).fadeIn();
            // $("#"+field_name).parents(".modx-tv").find(".twImagePreview").hide().attr("src",url).insertBefore("#tv-image-preview-"+thisFieldNum[1]).fadeIn("slow");
            tinyMCE.activeEditor.windowManager.close();
          }
        });
      return false;
      }
      Ext.onReady(function(){
        replaceDefaultFileImageTVbutton = \'.$replaceDefaultFileImageTVbutton.\';
        setTimeout(function(){
          if(!$("#tinyFileImageBrowser").length){
            $("body").append("<input id=\\\'tinyFileImageBrowser\\\' type=\\\'hidden\\\' value=\\\'\\\' />");
          }
           $("input[id^=tvbrowser]").each(function(){
              fileOrImage = $(this).parents(".modx-tv").find(".x-form-file-trigger").attr("id");
              if($("#"+fileOrImage).length){
                twImageFileOnClick = "imageFileTVpop($(this).attr(\\\'data-tiny\\\'))";
                twImageFileBtn = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;(all&nbsp;files)&nbsp;\\\';
                twImageFileBtnTitle = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;All-File&nbsp;Browser&nbsp;\\\';
                twImageClass = \\\'twImageFileBtnClass x-form-trigger x-form-file-trigger\\\';
                twImagePreview = "";
              }
              else{
                twImageFileOnClick = "imageFileTVpop($(this).attr(\\\'data-tiny\\\'))";
                twImageFileBtn = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;(images)&nbsp;\\\';
                twImageFileBtnTitle = \\\'&nbsp;\'.$browserShortTitle.\'&nbsp;Image-Only&nbsp;Browser&nbsp;\\\';
                twImagePreview = "<img class=\\\'twImagePreview\\\' title=\\\'preview by \'.$browserShortTitle.\' Image Browser\\\' src=\\\'\\\' style=\\\'width:100px;display:none;\\\' />";
                twImageClass = \\\'twImageFileBtnClass x-form-trigger x-form-image-trigger\\\';
              }
              if(replaceDefaultFileImageTVbutton == 1){
                $(this).parents(".modx-tv").find(".x-form-trigger").replaceWith("<div class=\\\'"+twImageClass+"\\\' data-tiny="+this.id+"  title="+twImageFileBtnTitle+" onclick="+twImageFileOnClick+"></div>"+twImagePreview);
              }
              else{
                $(this).parents(".x-form-item")
                .find(".modx-tv-caption")
                .parent()
                .prepend("<input class=\\\'twImageFileBtnClass x-form-field-wrap x-form-trigger\\\' data-tiny="+this.id+" type=\\\'button\\\' value="+twImageFileBtn+" title="+twImageFileBtnTitle+" onclick="+twImageFileOnClick+">"+twImagePreview);
              }
              if(tinymce.editors.length < 1){
                tinymce.init({
                  selector: "#tinyFileImageBrowser",
                  skin_url: \'.$fileManagerTopNavModalSkin.\',
                  inline:true,
                  forced_root_block : "",
                  force_br_newlines : false,
                  force_p_newlines : false
                })
              }
           })
        },1000);
      })
    \';
    if($autoFileBrowser ==\'modxNativeBrowser\'){
      $browserTVs = \'\';
    }
  }
}

//if user selects the option to activate Ace / CodeMirror, we save him/her the trip of heading to System Settings - is this being too officious or intrusive?
if ($activateAceOrCodeMirror) {
  $fireEditor = 0;
  if ($whichElementEditor !== \'TinymceWrapper\') {
    $whichEl = $modx->getObject(\'modSystemSetting\', \'which_element_editor\');
    $whichEl->set(\'value\', \'TinymceWrapper\');
    $whichEl->save();
  }
  $onlyElementsFiles = \'OnTempFormPrerender,OnSnipFormPrerender,OnChunkFormPrerender,OnPluginFormPrerender,OnFileEditFormPrerender\';
  $onlyElementsFiles = explode(\',\', $onlyElementsFiles);
  $twGetResourceContentType = "";
  // experimental - OnManagerPageInit or other ... would have been good but...
  if($useAceOrCodeMirrorEveryWhere){
    $updateResource = "resource/update";
    $newResource = "resource/create";
    $updateCreateElement = "element/";
    $updateCreateFile = "system/file/";
    $fireEditor = 1;

    // existing or new elements or files
    if (isset($_GET["a"]) && !$useAceOrCodeMirrorOnElementsFiles && (strpos($_GET["a"], $updateCreateElement) !== false || strpos($_GET["a"], $updateCreateFile) !== false)) {
        $fireEditor = 0;
    }

    // existing resource with RT value
    if (isset($_GET["a"], $_GET["id"]) && strpos($_GET["a"], $updateResource) !== false) {
      $twGetResourceContentType = $modx->getObject("modResource",$_GET["id"])->getOne(\'ContentType\')->get(\'mime_type\');
      if (!$activateAceOrCodeMirrorOnRichText && $modx->getObject("modResource",$_GET["id"])->get(\'richtext\')) {
        $fireEditor = 0;
      }
      if (!$useAceOrCodeMirrorOnResources) {
        $fireEditor = 0;
      }
    }
    // new resource with no RT value
    // if (isset($_GET["a"],$_GET["id"]) && $_GET["id"] == 0 && strpos($_GET["a"], $newResource) !== false) {
    if (isset($_GET["a"]) && strpos($_GET["a"], $newResource) !== false) { //expand criteria for Collection Extra and oter weird stuff
      if (!$activateAceOrCodeMirrorOnNewResource) {
        $fireEditor = 0;
      }
      // new resource with no RT value but System settings default RT value
      if ($activateAceOrCodeMirrorOnNewResource && $richtext_default == 1) {
        $fireEditor = 0;
      }
      if (!$useAceOrCodeMirrorOnResources) {
        $fireEditor = 0;
      }
    }
  }

  if($useAceOrCodeMirrorOnElementsFiles && in_array($modxEventName, $onlyElementsFiles)){
    $fireEditor = 1;
  }

  if($useAceOrCodeMirrorOnResources && $modxEventName == "OnDocFormPrerender"){
    $fireEditor = 1;
    // existing resource has contenttype
    if($id){
      $twGetResourceContentType = $resource->getOne(\'ContentType\')->get(\'mime_type\');
    }
    // existing resource with RT value
    if (!$activateAceOrCodeMirrorOnRichText && $id && $resource->get(\'richtext\')) {
      $fireEditor = 0;
    }
    // new resource with no RT value
    if (!$activateAceOrCodeMirrorOnNewResource && !$id) {
      $fireEditor = 0;
    }
    // new resource with no RT value but System settings default RT value
    if ($activateAceOrCodeMirrorOnNewResource && !$id && $richtext_default == 1) {
      $fireEditor = 0;
    }
    if ($useAceOrCodeMirrorEveryWhere) {
      $fireEditor = 0;
    }
  }

  //make sure that this never fires twice, once at OnManagerPageInit and other events.
  if($fireEditor == 1){
    if ($activateTinyMCE !== 1 && $tvSuperAddict !== 1 && $jQuerySrc) {
      $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
    }
    if ($activateAceOrCodeMirror == "Ace") {
      $editorOutput= $modx->getChunk(\'TinymceWrapperAce\'.$chunkSuffix, array(\'AceSrc\' => $AceSrc, \'AceTHEME\' => $AceTHEME));
      $modx->regClientStartupScript($AceSrc.\'ace.js\');
    }
    else{
      $editorOutput= $modx->getChunk(\'TinymceWrapperCodeMirror\'.$chunkSuffix, array(\'CodeMirrorSrc\' => $CodeMirrorSrc, \'CodeMirrorTHEME\' => $CodeMirrorTHEME));
      $modx->regClientStartupScript($CodeMirrorSrc.\'codemirror.min.js\');
    }
    $exportVariables = \'
      twGetResourceContentType = "\'.$twGetResourceContentType.\'";
      fileManagerTopNavModalSkin = \'.$fileManagerTopNavModalSkin.\';
    \';
    $modx->regClientStartupHTMLBlock("<script>" . $exportVariables . $editorOutput . "</script>");
  }
}

//if user selects the option to activate this wrapper, we save him/her the trip of heading to System Settings - is this being too officious or intrusive?
if ($activateTinyMCE == 1) {
  if ($useEditor !== 1 || $whichEditor !== \'TinymceWrapper\') {
    $use = $modx->getObject(\'modSystemSetting\', \'use_editor\');
    $use->set(\'value\', 1);
    $use->save();
    $which = $modx->getObject(\'modSystemSetting\', \'which_editor\');
    $which->set(\'value\', \'TinymceWrapper\');
    $which->save();
  }
  //leave all elements alone - attack only resources
  if ($modxEventName == \'OnDocFormPrerender\') {
    //check if user wants to load TinyMCE for New Resources
      $loadTiny = 0; //default
    if($id && $resource->get(\'richtext\')) { //existing resource with RTE clicked
      $loadTiny = 1;
      }
    if($loadTiny == 0 && $newResources == 1 && $richtext_default == 1 && !$id) {
      $loadTiny = 1;
    }

    if ($loadTiny == 1) {
      //should we load jQuery?
      if ($jQuerySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
      }
      //should we load TinyMCE (from CDN or assets folder)?
      if ($tinySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
      }
      //let\'s init introtext, description and content textareas only if user has specified so in this plugin\'s properties
      if ($introtext == 1) {
        $introChunk = $modx->getChunk(\'TinymceWrapperIntrotext\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($introChunk) {
          $intro = $enableDisableTiny[0] . $introChunk;
        }
      }
      if ($description == 1) {
        $descChunk = $modx->getChunk(\'TinymceWrapperDescription\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($descChunk) {
          $desc = $enableDisableTiny[1] . $descChunk;
        }
      }
      if ($content == 1) {
        $conChunk = $modx->getChunk(\'TinymceWrapperContent\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));
        if ($conChunk) {
          $con = $enableDisableTiny[2] . $conChunk;
        }
      }
      //all textareas depend on whether the Resource is Rich Text-enabled. We use so many IFs to protect against error
      //any and all Rich TVs + File and Image TVs will now be initiated
      //Now let\'s do the real init of all textareas
      //seems Ext.onReady is better than setTimeout, delay of 400
      $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $intro . $desc . $con . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
    }
    //let\'s see if the person wants TVs activated even when RTE is disabled for the Resource.
    elseif ($id && !$resource->get(\'richtext\')) {
        if ($tvAddict == 1) {
          if ($jQuerySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
          }
          if ($tinySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
          }
          $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
        }
    }
  }
}
else{
  if ($modxEventName == \'OnDocFormPrerender\') {
        if ($tvSuperAddict == 1) {
          if ($jQuerySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
          }
          if ($tinySrc) {
            $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
          }
          $modx->regClientStartupHTMLBlock("<script>" . $enableDisableTinyClick . $browserFunctions . $autoSaveTextAreas . $browserTVs . "Ext.onReady(function () {" . $richTv . "},this,{delay:".$addTinyMCEloadDelay."});</script>");
        }
  }
}

if ($modxEventName == \'OnManagerPageInit\' || $modxEventName == \'OnDocFormPrerender\') {
  $mediaPopUp =\'\';
  if ($fileManagerTopNavLink == 1 && $autoFileBrowser !== \'modxNativeBrowser\') {
    // inject file browser link to Manager Top Nav Media dropdown
    // if modxNativeBrowserTopNAVpresent is set to NO, remove native link in topNAV only when native browser is not in use
    $mediaPopUp = \'
      var fileManagerTopNavModal = "\'.$fileManagerTopNavModal.\'";
      fileManagerTopNavModalSkin = \'.$fileManagerTopNavModalSkin.\';
      Ext.onReady(function(){
        if (typeof tinyMCE !== "undefined" && fileManagerTopNavModal == "1") {
          $("body").append("<div id=\\\'tinyTopNAV\\\' style=\\\'display:none!important;border:0!important;outline:0!important;width:0;height:0;\\\'></div>");
          if(tinymce.editors.length < 1){
            tinymce.init({
              selector: "#tinyTopNAV",
              skin_url: \'.$fileManagerTopNavModalSkin.\',
              inline:true,
              forced_root_block : "",
              force_br_newlines : false,
              force_p_newlines : false
            })
          }
        }
      },this,{delay: 50})
      function mediaPopup(url){
        if ($("#tinyTopNAV").length) {
          tinyMCE.activeEditor.windowManager.open({
            title: "\'.$browserRTEtitle.\'",
            url: url,
            width : window.innerWidth / 1.2,
            height : window.innerHeight / 1.2
          },
          {
            oninsert: function(e) {
              e.preventDefault()
              return false;
          }
          })
        }
        else{
          var w = 880;
          var h = 570;
          var l = Math.floor((screen.width-w)/2);
          var t = Math.floor((screen.height-h)/2);
          var win = window.open(url, "", "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
        }
      }

      taskCounter = 0;
      var linkCheck = setInterval(function(){
        //requires no jQuery or TinyMCE - will work even if activateTinyMCE is false
        var fileBrowserBro = document.getElementById("file_browser");
        if(fileBrowserBro){
          var browserName = "\'.$autoFileBrowser.\'";
          var modxNativeBrowserTopNAVpresent = "\'.$modxNativeBrowserTopNAVpresent.\'";
          if(modxNativeBrowserTopNAVpresent !== "1" && browserName !== "modxNativeBrowser"){
            fileBrowserBro.style.display = "none";
          }
          taskCounter++;
          fileBrowserBro.insertAdjacentHTML( "beforebegin", "<li id=\\"tinymcewrapperTopNav\\"><a href=\\"javascript:;\\" onclick=\\"mediaPopup(\'.$browserTopNAVurl.\')\\">\'.$browserTopNAVtitle.\'<span class=\\"description\\">\'.$browserTopNAVsubtext.\'</span></a></li>");
        }
        if(taskCounter = 1)
          {clearInterval(linkCheck);
          }
      },1000);
    \';
     $modx->regClientStartupHTMLBlock("<script>" . $mediaPopUp . "</script>");
  }

  //let\'s catch only the textarea[content] when it is created. You can use livejquery or arrive.js if you like
  //make it non-obstrusive - mouseenter seems better than mouseout - works when modal pops and cursor is already on the textarea

  $quickUpdateCreate = $modx->getOption(\'quickUpdateCreate\', $sp);
  $quick = \'\';
  $quickChunk = $modx->getChunk(\'TinymceWrapperQuickUpdate\' . $suffix, array(\'commonTinyMCECode\'=>$commonCode));

  if ($quickChunk) {
    $quick = $quickChunk;
  }
  if ($quickUpdateCreate == 1){
    //do not load these twice when resources are being edited
    if ($modxEventName == \'OnManagerPageInit\') {
      if ($jQuerySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $jQuerySrc . "\'></script>");
      }
      if ($tinySrc) {
        $modx->regClientStartupHTMLBlock("<script src=\'" . $tinySrc . "\'></script>");
      }
    }

    $quickUpdateTinyMCE = \'
      removeCodeMirror = 0;
      $(document).on("mouseenter", ".modx-window", function () {
        var tinyContent = $(this).find("textarea[name=content]");
        quickyId = "#"+tinyContent.attr("id");
        dataTiny = tinyContent.attr("id");
        // if ($(this).has("textarea[name=content]").length){//will catch Quick edit files from server
        if ($(this).has("input[name=published]").length && $(this).has("textarea[name=content]").length){
          if ($(this).has(".tinyEn").length){
          }
          else{
          // tinyContent.parent().parent().find("label").prepend("<button class=\\\'tinyEn x-form-field-wrap x-form-trigger\\\' onclick=enableTiny(quickyId,dataTiny)>Edit with TinyMCE?</button>&nbsp;&nbsp;&nbsp;");
          $(this).find(".x-toolbar-left-row").prepend("<p onclick=enableTiny(quickyId,dataTiny) class=\\\'x-btn x-btn-small x-btn-icon-small-left x-btn-noicon\\\' unselectable=\\\'on\\\'><em><button class=\\\'tinyEn x-btn-text\\\'>Edit with TinyMCE</button></em></p>");
          $(this).find(".tinyEn").attr("data-tiny",dataTiny);
          // $(this).find("button:contains(\\\'Close\\\')").first().attr("data-tiny","close-"+dataTiny);
          // $(this).find("button:contains(\\\'Save\\\')").first().attr("data-tiny","save-"+dataTiny);
          }
        }
      // })
      // .on("mouseout", tinymce.activeEditor, function () {
        // if(tinymce.editors.length > 1){}
        // if (tinyMCE.activeEditor !== null){
        //   if(tinyMCE.activeEditor.isHidden() != true){
        //     tinyMCE.activeEditor.save();
        //     javascript:console.log("saved");
        //   }
        // }
      });
      function enableTiny(quickyId,id){
        if($(quickyId).is(":visible")){
          enableTinyInit(quickyId);
          var id = dataTiny;
          $("button[data-tiny=\\\'"+id+"\\\']").html("Disable TinyMCE").parent().parent().attr("onclick","disTiny(dataTiny)");
        }
      }
      function disTiny(dataTiny){
        var dataTiny = dataTiny;
        tinymce.get(dataTiny).hide();
        $("button[data-tiny=\\\'"+dataTiny+"\\\']").html("Enable TinyMCE").parent().parent().attr("onclick","enTiny(dataTiny)");
        removeCodeMirror = 0;
        $(quickyId).parents(".modx-window").find(".CodeMirror, div.coder").remove();
        $(quickyId).parents(".modx-window").find(".ace_editor, div.coder").remove();
      }
      function enTiny(dataTiny){
        if($(quickyId).is(":visible")){
          $(quickyId).fadeIn().parents(".modx-window").find(".CodeMirror, div.coder").remove();
          $(quickyId).fadeIn().parents(".modx-window").find(".ace_editor, div.coder").remove();
          removeCodeMirror = 1;
          var dataTiny = dataTiny;
          tinymce.get(dataTiny).show();
          $("button[data-tiny=\\\'"+dataTiny+"\\\']").html("Disable TinyMCE").parent().parent().attr("onclick","disTiny(dataTiny)");
        }
      }
      function enableTinyInit(quickyId){
        $(quickyId).fadeIn().parents(".modx-window").find(".CodeMirror, div.coder").remove();
        $(quickyId).fadeIn().parents(".modx-window").find(".ace_editor, div.coder").remove();
        removeCodeMirror = 1;
        $(quickyId).parents(".modx-window").find(".x-tab-panel-body.x-tab-panel-body-top").css({"overflow":"hidden","overflow-y":"auto"});
        \' .$quick. \'
      }
      \';
    $modx->regClientStartupHTMLBlock("<script>" . $browserFunctions . $quickUpdateTinyMCE . "</script>");
  }
}',
      'locked' => '0',
      'properties' => 'a:59:{s:15:"activateTinyMCE";a:7:{s:4:"name";s:15:"activateTinyMCE";s:4:"desc";s:260:"To use TinyMCE, this has to be set to Yes; this plugin will then disable whatever RTE you might have used before now.If set to false, with tvSuperAddict you can use the custom file browsers for your File/Image TVs, and also use TinyMCE(CDN) for RichTextareaTVs";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:19:"addTinyMCEloadDelay";a:7:{s:4:"name";s:19:"addTinyMCEloadDelay";s:4:"desc";s:174:"Default: 0. When using with an Extra that produces textareas on the fly, you might need a delay. 2100 works with Lingua. The longer the delay, the badder the user experience.";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:11:"chunkSuffix";a:7:{s:4:"name";s:11:"chunkSuffix";s:4:"desc";s:280:"This plugin will create six chunks for you; it will not override them once created, but you were better off duplicating them.
PLEASE simply add a suffix (_test or -su) to your new name.
TinymceWrapperIntrotext becomes TinymceWrapperIntrotext-test or TinymceWrapperIntrotext-suffix";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:21:"disableEnableCheckbox";a:7:{s:4:"name";s:21:"disableEnableCheckbox";s:4:"desc";s:111:"Do you want a checkbox to appear before every TinyMCE textarea, to quickly disable/enable a particular TinyMCE?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:21:"fileManagerTopNavLink";a:7:{s:4:"name";s:21:"fileManagerTopNavLink";s:4:"desc";s:245:"Add custom File Manager link to Manager Top Nav > Media drop-down menu (Vanilla JS, no jQuery or TinyMCE loaded).
This will work whether you are using RTE or not, that is, even if activateTinyMCE is set to false; wherever you are in the Manager.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:22:"fileManagerTopNavModal";a:7:{s:4:"name";s:22:"fileManagerTopNavModal";s:4:"desc";s:55:"If you want the custom file browser to pop into a modal";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:26:"fileManagerTopNavModalSkin";a:7:{s:4:"name";s:26:"fileManagerTopNavModalSkin";s:4:"desc";s:77:"Bear in mind, this skin can affect your RTEs, so make the skin calls the same";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:76:"MODx.config.assets_url+"components/tinymcewrapper/tinymceskins/modxPericles"";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:6:"jQuery";a:7:{s:4:"name";s:6:"jQuery";s:4:"desc";s:118:"This plugin requires jQuery in the order that it is loaded. Leave blank if you already have it running in the Manager.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:43:"https://code.jquery.com/jquery-2.1.3.min.js";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:12:"newResources";a:7:{s:4:"name";s:12:"newResources";s:4:"desc";s:173:"If you set richtext_default in System Settings, new resources will have the RTE clicked automatically.
Do you want TinyMCE to load also, automatically, for the new resource?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:7:"tinySrc";a:7:{s:4:"name";s:7:"tinySrc";s:4:"desc";s:68:"You may use either TinyMCE CDN or TinyMCE located on your own folder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:34:"//cdn.tinymce.com/4/tinymce.min.js";s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:8:"tvAddict";a:7:{s:4:"name";s:8:"tvAddict";s:4:"desc";s:187:"Do you want your TVs (Rich/File/Image) to be wrapperjacked by this plugin even if you have RTE disabled for the particular resource? This will work even in the Articles Extra (hopefully!)";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:13:"tvSuperAddict";a:7:{s:4:"name";s:13:"tvSuperAddict";s:4:"desc";s:202:"Even though you have another RTE in use (that is, you have set activateTinyMCE to false), you can still use the custom filebrowsers for your File/Image TVs, and also use TinyMCE(CDN) for RichTextareaTVs";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:18:"00 Editor Settings";}s:17:"TinyJSONGalleryTV";a:7:{s:4:"name";s:17:"TinyJSONGalleryTV";s:4:"desc";s:98:"TV to use to transform any Resource into a Gallery. Default is TinyJSONGalleryTV -- Type: Textarea";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:17:"TinyJSONGalleryTV";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:18:"enableImageGallery";a:7:{s:4:"name";s:18:"enableImageGallery";s:4:"desc";s:129:"Presently incomapatible with Image+ and Gallery Extra. Hopefully, either party will resolve the issue before the millennium ends.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:19:"galleryChunkNameKey";a:7:{s:4:"name";s:19:"galleryChunkNameKey";s:4:"desc";s:68:"Any Chunk name containing this keyword will be turned into a Gallery";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:9:"myGallery";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:13:"galleryJSfile";a:7:{s:4:"name";s:13:"galleryJSfile";s:4:"desc";s:156:"absolute url to custom file that controls the gallery; if empty, default file will be used = /assets/components/tinymcewrapper/gallery/js/TinyJSONGallery.js";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:15:"imageGalleryIDs";a:7:{s:4:"name";s:15:"imageGalleryIDs";s:4:"desc";s:91:"Comma-separated list of chunk id. Any Chunk whose id is here will be turned into a Gallery.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"0,0,0";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:26:"tinyJSONGalleryTABposition";a:7:{s:4:"name";s:26:"tinyJSONGalleryTABposition";s:4:"desc";s:65:"By default, the Gallery tsb comes first. 0 = first.... 1 , 2 , 10";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:1:"0";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:23:"tinyJSONGalleryTABtitle";a:7:{s:4:"name";s:23:"tinyJSONGalleryTABtitle";s:4:"desc";s:47:"The title on the tab in your resource or chunk.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"JSON Image Gallery";s:7:"lexicon";N;s:4:"area";s:16:"00 Image Gallery";}s:8:"customJS";a:7:{s:4:"name";s:8:"customJS";s:4:"desc";s:200:"For running custom JavaScript in your Manager. Use scenario: any and all other 3rd party MODx Extras within which you wish to use TinyMCE. Or just about any other textarea you find in the MODX Manager";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:33:"00 Textareas to transform - et al";}s:14:"customJSchunks";a:7:{s:4:"name";s:14:"customJSchunks";s:4:"desc";s:327:"Comma-separated list of any 3rd party MODX Extras you wish to be infused with TinyMCE. E.G: Gallery,ContentBlocks,ETC,ETC... Then create the corresponding chunk - TinymceWrapperContentBlocks. These chunks also are affected by the chunkSuffix setting. You can use with just about any other textarea you find in the MODX Manager.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:33:"00 Textareas to transform - et al";}s:6:"AceSrc";a:7:{s:4:"name";s:6:"AceSrc";s:4:"desc";s:74:"Toss in latest Ace CDN or local url...never be outdated again! Hurray..!!!";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:49:"https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.4/";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:8:"AceTHEME";a:7:{s:4:"name";s:8:"AceTHEME";s:4:"desc";s:60:"35 themes to work with (BRIGHT and DARK) knock yourself out!";s:4:"type";s:4:"list";s:7:"options";a:36:{i:0;a:2:{s:4:"text";s:7:"(empty)";s:5:"value";s:0:"";}i:1;a:2:{s:4:"text";s:8:"ambiance";s:5:"value";s:8:"ambiance";}i:2;a:2:{s:4:"text";s:5:"chaos";s:5:"value";s:5:"chaos";}i:3;a:2:{s:4:"text";s:6:"chrome";s:5:"value";s:6:"chrome";}i:4;a:2:{s:4:"text";s:6:"clouds";s:5:"value";s:6:"clouds";}i:5;a:2:{s:4:"text";s:15:"clouds_midnight";s:5:"value";s:15:"clouds_midnight";}i:6;a:2:{s:4:"text";s:6:"cobalt";s:5:"value";s:6:"cobalt";}i:7;a:2:{s:4:"text";s:14:"crimson_editor";s:5:"value";s:14:"crimson_editor";}i:8;a:2:{s:4:"text";s:4:"dawn";s:5:"value";s:4:"dawn";}i:9;a:2:{s:4:"text";s:11:"dreamweaver";s:5:"value";s:11:"dreamweaver";}i:10;a:2:{s:4:"text";s:7:"eclipse";s:5:"value";s:7:"eclipse";}i:11;a:2:{s:4:"text";s:6:"github";s:5:"value";s:6:"github";}i:12;a:2:{s:4:"text";s:7:"gruvbox";s:5:"value";s:7:"gruvbox";}i:13;a:2:{s:4:"text";s:12:"idle_fingers";s:5:"value";s:12:"idle_fingers";}i:14;a:2:{s:4:"text";s:8:"iplastic";s:5:"value";s:8:"iplastic";}i:15;a:2:{s:4:"text";s:11:"katzenmilch";s:5:"value";s:11:"katzenmilch";}i:16;a:2:{s:4:"text";s:8:"kr_theme";s:5:"value";s:8:"kr_theme";}i:17;a:2:{s:4:"text";s:6:"kuroir";s:5:"value";s:6:"kuroir";}i:18;a:2:{s:4:"text";s:9:"merbivore";s:5:"value";s:9:"merbivore";}i:19;a:2:{s:4:"text";s:14:"merbivore_soft";s:5:"value";s:14:"merbivore_soft";}i:20;a:2:{s:4:"text";s:15:"mono_industrial";s:5:"value";s:15:"mono_industrial";}i:21;a:2:{s:4:"text";s:7:"monokai";s:5:"value";s:7:"monokai";}i:22;a:2:{s:4:"text";s:14:"pastel_on_dark";s:5:"value";s:14:"pastel_on_dark";}i:23;a:2:{s:4:"text";s:14:"solarized_dark";s:5:"value";s:14:"solarized_dark";}i:24;a:2:{s:4:"text";s:15:"solarized_light";s:5:"value";s:15:"solarized_light";}i:25;a:2:{s:4:"text";s:9:"sqlserver";s:5:"value";s:9:"sqlserver";}i:26;a:2:{s:4:"text";s:8:"terminal";s:5:"value";s:8:"terminal";}i:27;a:2:{s:4:"text";s:8:"textmate";s:5:"value";s:8:"textmate";}i:28;a:2:{s:4:"text";s:8:"tomorrow";s:5:"value";s:8:"tomorrow";}i:29;a:2:{s:4:"text";s:14:"tomorrow_night";s:5:"value";s:14:"tomorrow_night";}i:30;a:2:{s:4:"text";s:19:"tomorrow_night_blue";s:5:"value";s:19:"tomorrow_night_blue";}i:31;a:2:{s:4:"text";s:21:"tomorrow_night_bright";s:5:"value";s:21:"tomorrow_night_bright";}i:32;a:2:{s:4:"text";s:23:"tomorrow_night_eighties";s:5:"value";s:23:"tomorrow_night_eighties";}i:33;a:2:{s:4:"text";s:8:"twilight";s:5:"value";s:8:"twilight";}i:34;a:2:{s:4:"text";s:11:"vibrant_ink";s:5:"value";s:11:"vibrant_ink";}i:35;a:2:{s:4:"text";s:5:"xcode";s:5:"value";s:5:"xcode";}}s:5:"value";s:6:"chrome";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:13:"CodeMirrorSrc";a:7:{s:4:"name";s:13:"CodeMirrorSrc";s:4:"desc";s:81:"Toss in latest CodeMirror CDN or local url...never be outdated again! Hurray..!!!";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:57:"https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:15:"CodeMirrorTHEME";a:7:{s:4:"name";s:15:"CodeMirrorTHEME";s:4:"desc";s:73:"45 themes to work with, knock yourself out! Surely there is one you like?";s:4:"type";s:4:"list";s:7:"options";a:46:{i:0;a:2:{s:4:"text";s:7:"(empty)";s:5:"value";s:0:"";}i:1;a:2:{s:4:"text";s:8:"3024-day";s:5:"value";s:8:"3024-day";}i:2;a:2:{s:4:"text";s:10:"3024-night";s:5:"value";s:10:"3024-night";}i:3;a:2:{s:4:"text";s:6:"abcdef";s:5:"value";s:6:"abcdef";}i:4;a:2:{s:4:"text";s:15:"ambiance-mobile";s:5:"value";s:15:"ambiance-mobile";}i:5;a:2:{s:4:"text";s:8:"ambiance";s:5:"value";s:8:"ambiance";}i:6;a:2:{s:4:"text";s:11:"base16-dark";s:5:"value";s:11:"base16-dark";}i:7;a:2:{s:4:"text";s:12:"base16-light";s:5:"value";s:12:"base16-light";}i:8;a:2:{s:4:"text";s:6:"bespin";s:5:"value";s:6:"bespin";}i:9;a:2:{s:4:"text";s:10:"blackboard";s:5:"value";s:10:"blackboard";}i:10;a:2:{s:4:"text";s:6:"cobalt";s:5:"value";s:6:"cobalt";}i:11;a:2:{s:4:"text";s:10:"colorforth";s:5:"value";s:10:"colorforth";}i:12;a:2:{s:4:"text";s:7:"dracula";s:5:"value";s:7:"dracula";}i:13;a:2:{s:4:"text";s:7:"eclipse";s:5:"value";s:7:"eclipse";}i:14;a:2:{s:4:"text";s:7:"elegant";s:5:"value";s:7:"elegant";}i:15;a:2:{s:4:"text";s:11:"erlang-dark";s:5:"value";s:11:"erlang-dark";}i:16;a:2:{s:4:"text";s:9:"hopscotch";s:5:"value";s:9:"hopscotch";}i:17;a:2:{s:4:"text";s:8:"icecoder";s:5:"value";s:8:"icecoder";}i:18;a:2:{s:4:"text";s:7:"isotope";s:5:"value";s:7:"isotope";}i:19;a:2:{s:4:"text";s:11:"lesser-dark";s:5:"value";s:11:"lesser-dark";}i:20;a:2:{s:4:"text";s:9:"liquibyte";s:5:"value";s:9:"liquibyte";}i:21;a:2:{s:4:"text";s:8:"material";s:5:"value";s:8:"material";}i:22;a:2:{s:4:"text";s:3:"mbo";s:5:"value";s:3:"mbo";}i:23;a:2:{s:4:"text";s:8:"mdn-like";s:5:"value";s:8:"mdn-like";}i:24;a:2:{s:4:"text";s:8:"midnight";s:5:"value";s:8:"midnight";}i:25;a:2:{s:4:"text";s:7:"monokai";s:5:"value";s:7:"monokai";}i:26;a:2:{s:4:"text";s:4:"neat";s:5:"value";s:4:"neat";}i:27;a:2:{s:4:"text";s:3:"neo";s:5:"value";s:3:"neo";}i:28;a:2:{s:4:"text";s:5:"night";s:5:"value";s:5:"night";}i:29;a:2:{s:4:"text";s:12:"paraiso-dark";s:5:"value";s:12:"paraiso-dark";}i:30;a:2:{s:4:"text";s:13:"paraiso-light";s:5:"value";s:13:"paraiso-light";}i:31;a:2:{s:4:"text";s:14:"pastel-on-dark";s:5:"value";s:14:"pastel-on-dark";}i:32;a:2:{s:4:"text";s:10:"railscasts";s:5:"value";s:10:"railscasts";}i:33;a:2:{s:4:"text";s:8:"rubyblue";s:5:"value";s:8:"rubyblue";}i:34;a:2:{s:4:"text";s:4:"seti";s:5:"value";s:4:"seti";}i:35;a:2:{s:4:"text";s:9:"solarized";s:5:"value";s:9:"solarized";}i:36;a:2:{s:4:"text";s:10:"the-matrix";s:5:"value";s:10:"the-matrix";}i:37;a:2:{s:4:"text";s:21:"tomorrow-night-bright";s:5:"value";s:21:"tomorrow-night-bright";}i:38;a:2:{s:4:"text";s:23:"tomorrow-night-eighties";s:5:"value";s:23:"tomorrow-night-eighties";}i:39;a:2:{s:4:"text";s:4:"ttcn";s:5:"value";s:4:"ttcn";}i:40;a:2:{s:4:"text";s:8:"twilight";s:5:"value";s:8:"twilight";}i:41;a:2:{s:4:"text";s:11:"vibrant-ink";s:5:"value";s:11:"vibrant-ink";}i:42;a:2:{s:4:"text";s:7:"xq-dark";s:5:"value";s:7:"xq-dark";}i:43;a:2:{s:4:"text";s:8:"xq-light";s:5:"value";s:8:"xq-light";}i:44;a:2:{s:4:"text";s:4:"yeti";s:5:"value";s:4:"yeti";}i:45;a:2:{s:4:"text";s:7:"zenburn";s:5:"value";s:7:"zenburn";}}s:5:"value";s:7:"elegant";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:23:"activateAceOrCodeMirror";a:7:{s:4:"name";s:23:"activateAceOrCodeMirror";s:4:"desc";s:528:"If not set to NONE, this plugin will set TinymceWrapper as default element code editor, and thus use Ace or CodeMirror for whatever file/element textareas (including quick update/create) that you specify in the TinymceWrapperCodeMirror chunk. This takes the chunkSuffix as well. Please set this well inorder not to conflict with TinyMCE RTE. And yes, you can use TinyMCE RTE and Ace or CodeMirror same time, one for content, the other for TVs or quick update...have fun! This is also compatible with twCodeMirror.js and twAce.js";s:4:"type";s:4:"list";s:7:"options";a:3:{i:0;a:2:{s:4:"text";s:3:"Ace";s:5:"value";s:3:"Ace";}i:1;a:2:{s:4:"text";s:10:"CodeMirror";s:5:"value";s:10:"CodeMirror";}i:2;a:2:{s:4:"text";s:4:"none";s:5:"value";s:0:"";}}s:5:"value";s:3:"Ace";s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:36:"activateAceOrCodeMirrorOnNewResource";a:7:{s:4:"name";s:36:"activateAceOrCodeMirrorOnNewResource";s:4:"desc";s:90:"New Resources have the option of a code editor. Respects activateAceOrCodeMirrorOnRichText";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:33:"activateAceOrCodeMirrorOnRichText";a:7:{s:4:"name";s:33:"activateAceOrCodeMirrorOnRichText";s:4:"desc";s:171:"Prevent Ace or CodeMirror from ever firing when Rich Text is turned on for a particular resource. Respects activateAceOrCodeMirrorOnNewResource and system richtext_default";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:28:"useAceOrCodeMirrorEveryWhere";a:7:{s:4:"name";s:28:"useAceOrCodeMirrorEveryWhere";s:4:"desc";s:368:"Experimental - Fires at OnManagerPageInit. Works Manager-wide. No need to be editing a MODX resource or element to load Ace or CodeMirror. You can be at the Dashboard or CMP to use Code Editor- comes in handy when doing Quick Update/Create outside of Resource and elements/files. This option respects useAceOrCodeMirrorOnResources and useAceOrCodeMirrorOnElementsFiles";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:33:"useAceOrCodeMirrorOnElementsFiles";a:7:{s:4:"name";s:33:"useAceOrCodeMirrorOnElementsFiles";s:4:"desc";s:72:"Activate Manager pages of Chunks, Snippets, Plugins, Templates and Files";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:29:"useAceOrCodeMirrorOnResources";a:7:{s:4:"name";s:29:"useAceOrCodeMirrorOnResources";s:4:"desc";s:41:"You can turn this on or off for Resources";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:17:"01 Ace-CodeMirror";}s:7:"Content";a:7:{s:4:"name";s:7:"Content";s:4:"desc";s:36:"Transform Resource Content textarea?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:2:{i:0;a:2:{s:4:"text";s:3:"Yes";s:5:"value";s:3:"Yes";}i:1;a:2:{s:4:"text";s:2:"No";s:5:"value";s:2:"No";}}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:11:"Description";a:7:{s:4:"name";s:11:"Description";s:4:"desc";s:31:"Transform Description textarea?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:2:{i:0;a:2:{s:4:"text";s:3:"Yes";s:5:"value";s:3:"Yes";}i:1;a:2:{s:4:"text";s:2:"No";s:5:"value";s:2:"No";}}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:9:"Introtext";a:7:{s:4:"name";s:9:"Introtext";s:4:"desc";s:29:"Transform Introtext textarea?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:2:{i:0;a:2:{s:4:"text";s:3:"Yes";s:5:"value";s:3:"Yes";}i:1;a:2:{s:4:"text";s:2:"No";s:5:"value";s:2:"No";}}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:3:"TVs";a:7:{s:4:"name";s:3:"TVs";s:4:"desc";s:28:"Transform Rich TVs textarea?";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:12:"fileImageTVs";a:7:{s:4:"name";s:12:"fileImageTVs";s:4:"desc";s:137:"You will be able to use elFinder,  Responsive FileManager, or the other custom browsers to input data in your File and Image TVs, hurray!";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:17:"quickUpdateCreate";a:7:{s:4:"name";s:17:"quickUpdateCreate";s:4:"desc";s:162:"Use TinyMCE to edit/create as many resources as you want at the same time, in the same browser window, thanks to MODX Quick Update/Create and TinyMCE flexibility.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:25:"01 Textareas to transform";}s:15:"autoFileBrowser";a:7:{s:4:"name";s:15:"autoFileBrowser";s:4:"desc";s:48:"Please select which awesome file browser to use.";s:4:"type";s:4:"list";s:7:"options";a:4:{i:0;a:2:{s:4:"text";s:17:"modxNativeBrowser";s:5:"value";s:17:"modxNativeBrowser";}i:1;a:2:{s:4:"text";s:15:"elFinderBrowser";s:5:"value";s:15:"elFinderBrowser";}i:2;a:2:{s:4:"text";s:28:"responsivefilemanagerBrowser";s:5:"value";s:28:"responsivefilemanagerBrowser";}i:3;a:2:{s:4:"text";s:18:"roxyFilemanBrowser";s:5:"value";s:18:"roxyFilemanBrowser";}}s:5:"value";s:15:"elFinderBrowser";s:7:"lexicon";N;s:4:"area";s:17:"02 Browser Config";}s:20:"browserTopNAVsubtext";a:7:{s:4:"name";s:20:"browserTopNAVsubtext";s:4:"desc";s:55:"Slogan to appear in your Manager Top Nav dropdown menu.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"Wonderfully Manage Media";s:7:"lexicon";N;s:4:"area";s:17:"02 Browser Config";}s:31:"replaceDefaultFileImageTVbutton";a:7:{s:4:"name";s:31:"replaceDefaultFileImageTVbutton";s:4:"desc";s:86:"When using a custom browser, you may suppress MODX native browser file/image TV button";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";N;s:4:"area";s:17:"02 Browser Config";}s:23:"elFinderBrowserRTEtitle";a:7:{s:4:"name";s:23:"elFinderBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"elFinder Awesome Browser";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:21:"elFinderBrowserRTEurl";a:7:{s:4:"name";s:21:"elFinderBrowserRTEurl";s:4:"desc";s:83:"Something like elfinder.html?unlocked=1&amp;rememberLastDir=1&amp;defaultView=icons";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:233:"[[~[[TinymceWrapperGRF? &amp;knownField=`pagetitle` &amp;kF=`pagetitle` &amp;kFv=`tw_elfinder_browser` &amp;gNuFv=`id`]]? &amp;scheme=`full` &amp;rememberLastDir=`1` &amp;defaultView=`icons` &amp;unlocked=`1` &amp;theme=`windows10`]]";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:25:"elFinderBrowserSHORTtitle";a:7:{s:4:"name";s:25:"elFinderBrowserSHORTtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:8:"elFinder";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:26:"elFinderBrowserTopNAVtitle";a:7:{s:4:"name";s:26:"elFinderBrowserTopNAVtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:21:"elFinder File Browser";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:24:"elFinderBrowserTopNAVurl";a:7:{s:4:"name";s:24:"elFinderBrowserTopNAVurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:233:"[[~[[TinymceWrapperGRF? &amp;knownField=`pagetitle` &amp;kF=`pagetitle` &amp;kFv=`tw_elfinder_browser` &amp;gNuFv=`id`]]? &amp;scheme=`full` &amp;rememberLastDir=`1` &amp;defaultView=`icons` &amp;unlocked=`1` &amp;theme=`windows10`]]";s:7:"lexicon";N;s:4:"area";s:22:"03 Browsers - elFinder";}s:26:"modxNativeBrowserQuirkMode";a:7:{s:4:"name";s:26:"modxNativeBrowserQuirkMode";s:4:"desc";s:71:"Load MODX File Browser the native way - thanks to Denis from dyranov.ru";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:26:"04 Browsers - MODx Browser";}s:25:"modxNativeBrowserRTEtitle";a:7:{s:4:"name";s:25:"modxNativeBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"MODx Native File Browser";s:7:"lexicon";N;s:4:"area";s:26:"04 Browsers - MODx Browser";}s:23:"modxNativeBrowserRTEurl";a:7:{s:4:"name";s:23:"modxNativeBrowserRTEurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:123:"MODx.config["manager_url"] + "index.php?a=" + MODx.action["browser"] + "&amp;source=" + MODx.config["default_media_source"]";s:7:"lexicon";N;s:4:"area";s:26:"04 Browsers - MODx Browser";}s:30:"modxNativeBrowserTopNAVpresent";a:7:{s:4:"name";s:30:"modxNativeBrowserTopNAVpresent";s:4:"desc";s:129:"If YES, the MODX native browser link will always show in the Top Nav. If NO, it will only show if a custom browser is not in use.";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";N;s:4:"area";s:26:"04 Browsers - MODx Browser";}s:36:"responsivefilemanagerBrowserRTEtitle";a:7:{s:4:"name";s:36:"responsivefilemanagerBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:22:"Responsive FileManager";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:34:"responsivefilemanagerBrowserRTEurl";a:7:{s:4:"name";s:34:"responsivefilemanagerBrowserRTEurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:103:"MODx.config.assets_url+"components/tinymcewrapper/responsivefilemanager/filemanager/dialog.php?xtype=1"";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:38:"responsivefilemanagerBrowserSHORTtitle";a:7:{s:4:"name";s:38:"responsivefilemanagerBrowserSHORTtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:3:"RFM";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:39:"responsivefilemanagerBrowserTopNAVtitle";a:7:{s:4:"name";s:39:"responsivefilemanagerBrowserTopNAVtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:22:"Responsive FileManager";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:37:"responsivefilemanagerBrowserTopNAVurl";a:7:{s:4:"name";s:37:"responsivefilemanagerBrowserTopNAVurl";s:4:"desc";s:23:"Has no ?popup parameter";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:105:"MODx.config.assets_url+\\\'components/tinymcewrapper/responsivefilemanager/filemanager/dialog.php?xtype=0\\\'";s:7:"lexicon";N;s:4:"area";s:36:"05 Browsers - Responsive FileManager";}s:26:"roxyFilemanBrowserRTEtitle";a:7:{s:4:"name";s:26:"roxyFilemanBrowserRTEtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"Roxy Fileman";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}s:24:"roxyFilemanBrowserRTEurl";a:7:{s:4:"name";s:24:"roxyFilemanBrowserRTEurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:72:"MODx.config.assets_url+"components/tinymcewrapper/roxy/fileman/roxy.php"";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}s:28:"roxyFilemanBrowserSHORTtitle";a:7:{s:4:"name";s:28:"roxyFilemanBrowserSHORTtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:4:"Roxy";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}s:29:"roxyFilemanBrowserTopNAVtitle";a:7:{s:4:"name";s:29:"roxyFilemanBrowserTopNAVtitle";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"Roxy Fileman";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}s:27:"roxyFilemanBrowserTopNAVurl";a:7:{s:4:"name";s:27:"roxyFilemanBrowserTopNAVurl";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:74:"MODx.config.assets_url+\\\'components/tinymcewrapper/roxy/fileman/roxy.php\\\'";s:7:"lexicon";N;s:4:"area";s:26:"06 Browsers - Roxy Fileman";}}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    2 => 
    array (
      'id' => '2',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'MIGX',
      'description' => '',
      'editor_type' => '0',
      'category' => '16',
      'cache_type' => '0',
      'plugincode' => '$corePath = $modx->getOption(\'migx.core_path\',null,$modx->getOption(\'core_path\').\'components/migx/\');
$assetsUrl = $modx->getOption(\'migx.assets_url\', null, $modx->getOption(\'assets_url\') . \'components/migx/\');
switch ($modx->event->name) {
    case \'OnTVInputRenderList\':
        $modx->event->output($corePath.\'elements/tv/input/\');
        break;
    case \'OnTVInputPropertiesList\':
        $modx->event->output($corePath.\'elements/tv/inputoptions/\');
        break;

        case \'OnDocFormPrerender\':
        $modx->controller->addCss($assetsUrl.\'css/mgr.css\');
        break; 
 
    /*          
    case \'OnTVOutputRenderList\':
        $modx->event->output($corePath.\'elements/tv/output/\');
        break;
    case \'OnTVOutputRenderPropertiesList\':
        $modx->event->output($corePath.\'elements/tv/properties/\');
        break;
    
    case \'OnDocFormPrerender\':
        $assetsUrl = $modx->getOption(\'colorpicker.assets_url\',null,$modx->getOption(\'assets_url\').\'components/colorpicker/\'); 
        $modx->regClientStartupHTMLBlock(\'<script type="text/javascript">
        Ext.onReady(function() {
            
        });
        </script>\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorPicker.js\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorMenu.js\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorPickerField.js\');		
        $modx->regClientCSS($assetsUrl.\'resources/css/colorpicker.css\');
        break;
     */
}
return;',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
    4 => 
    array (
      'id' => '4',
      'source' => '0',
      'property_preprocess' => '0',
      'name' => 'migxResizeOnUpload',
      'description' => '',
      'editor_type' => '0',
      'category' => '16',
      'cache_type' => '0',
      'plugincode' => '/**
 * migxResizeOnUpload Plugin
 *
 * Events: OnFileManagerUpload
 * Author: Bruno Perner <b.perner@gmx.de>
 * Modified to read multiple configs from mediasource-property
 * 
 * First Author: Vasiliy Naumkin <bezumkin@yandex.ru>
 * Required: PhpThumbOf snippet for resizing images
 * 
 * Example: mediasource - property \'resizeConfig\':
 * [{"alias":"origin","w":"500","h":"500","far":1},{"alias":"thumb","w":"150","h":"150","far":1}]
 */

if ($modx->event->name != \'OnFileManagerUpload\') {
    return;
}


$file = $modx->event->params[\'files\'][\'file\'];
$directory = $modx->event->params[\'directory\'];

if ($file[\'error\'] != 0) {
    return;
}

$name = $file[\'name\'];
//$extensions = explode(\',\', $modx->getOption(\'upload_images\'));

$source = $modx->event->params[\'source\'];

if ($source instanceof modMediaSource) {
    //$dirTree = $modx->getOption(\'dirtree\', $_REQUEST, \'\');
    //$modx->setPlaceholder(\'docid\', $resource_id);
    $source->initialize();
    $basePath = str_replace(\'/./\', \'/\', $source->getBasePath());
    //$cachepath = $cachepath . $dirTree;
    $baseUrl = $modx->getOption(\'site_url\') . $source->getBaseUrl();
    //$baseUrl = $baseUrl . $dirTree;
    $sourceProperties = $source->getPropertyList();

    //echo \'<pre>\' . print_r($sourceProperties, 1) . \'</pre>\';
    //$allowedExtensions = $modx->getOption(\'allowedFileTypes\', $sourceProperties, \'\');
    //$allowedExtensions = empty($allowedExtensions) ? \'jpg,jpeg,png,gif\' : $allowedExtensions;
    //$maxFilesizeMb = $modx->getOption(\'maxFilesizeMb\', $sourceProperties, \'8\');
    //$maxFiles = $modx->getOption(\'maxFiles\', $sourceProperties, \'0\');
    //$thumbX = $modx->getOption(\'thumbX\', $sourceProperties, \'100\');
    //$thumbY = $modx->getOption(\'thumbY\', $sourceProperties, \'100\');
    $resizeConfigs = $modx->getOption(\'resizeConfigs\', $sourceProperties, \'\');
    $resizeConfigs = $modx->fromJson($resizeConfigs);
    $thumbscontainer = $modx->getOption(\'thumbscontainer\', $sourceProperties, \'thumbs/\');
    $imageExtensions = $modx->getOption(\'imageExtensions\', $sourceProperties, \'jpg,jpeg,png,gif,JPG\');
    $imageExtensions = explode(\',\', $imageExtensions);
    //$uniqueFilenames = $modx->getOption(\'uniqueFilenames\', $sourceProperties, false);
    //$onImageUpload = $modx->getOption(\'onImageUpload\', $sourceProperties, \'\');
    //$onImageRemove = $modx->getOption(\'onImageRemove\', $sourceProperties, \'\');
    $cleanalias = $modx->getOption(\'cleanFilename\', $sourceProperties, false);

}

if (is_array($resizeConfigs) && count($resizeConfigs) > 0) {
    foreach ($resizeConfigs as $rc) {
        if (isset($rc[\'alias\'])) {
            $filePath = $basePath . $directory;
            $filePath = str_replace(\'//\',\'/\',$filePath);
            if ($rc[\'alias\'] == \'origin\') {
                $thumbPath = $filePath;
            } else {
                $thumbPath = $filePath . $rc[\'alias\'] . \'/\';
                $permissions = octdec(\'0\' . (int)($modx->getOption(\'new_folder_permissions\', null, \'755\', true)));
                if (!@mkdir($thumbPath, $permissions, true)) {
                    $modx->log(MODX_LOG_LEVEL_ERROR, sprintf(\'[migxResourceMediaPath]: could not create directory %s).\', $thumbPath));
                } else {
                    chmod($thumbPath, $permissions);
                }

            }


            $filename = $filePath . $name;
            $thumbname = $thumbPath . $name;
            $ext = substr(strrchr($name, \'.\'), 1);
            if (in_array($ext, $imageExtensions)) {
                $sizes = getimagesize($filename);
                echo $sizes[0]; 
                //$format = substr($sizes[\'mime\'], 6);
                if ($sizes[0] > $rc[\'w\'] || $sizes[1] > $rc[\'h\']) {
                    if ($sizes[0] < $rc[\'w\']) {
                        $rc[\'w\'] = $sizes[0];
                    }
                    if ($sizes[1] < $rc[\'h\']) {
                        $rc[\'h\'] = $sizes[1];
                    }
                    $type = $sizes[0] > $sizes[1] ? \'landscape\' : \'portrait\';
                    if (isset($rc[\'far\']) && $rc[\'far\'] == \'1\' && isset($rc[\'w\']) && isset($rc[\'h\'])) {
                        if ($type = \'landscape\') {
                            unset($rc[\'h\']);
                        }else {
                            unset($rc[\'w\']);
                        }
                    }

                    $options = \'\';
                    foreach ($rc as $k => $v) {
                        if ($k != \'alias\') {
                            $options .= \'&\' . $k . \'=\' . $v;
                        }
                    }
                    $resized = $modx->runSnippet(\'phpthumbof\', array(\'input\' => $filePath . $name, \'options\' => $options));
                    rename(MODX_BASE_PATH . substr($resized, 1), $thumbname);
                }
            }


        }
    }
}',
      'locked' => '0',
      'properties' => 'a:0:{}',
      'disabled' => '0',
      'moduleguid' => '',
      'static' => '0',
      'static_file' => '',
    ),
  ),
  'policies' => 
  array (
    'modAccessContext' => 
    array (
      'web' => 
      array (
        0 => 
        array (
          'principal' => 0,
          'authority' => 9999,
          'policy' => 
          array (
            'load' => true,
          ),
        ),
        1 => 
        array (
          'principal' => 1,
          'authority' => 0,
          'policy' => 
          array (
            'about' => true,
            'access_permissions' => true,
            'actions' => true,
            'change_password' => true,
            'change_profile' => true,
            'charsets' => true,
            'class_map' => true,
            'components' => true,
            'content_types' => true,
            'countries' => true,
            'create' => true,
            'credits' => true,
            'customize_forms' => true,
            'dashboards' => true,
            'database' => true,
            'database_truncate' => true,
            'delete_category' => true,
            'delete_chunk' => true,
            'delete_context' => true,
            'delete_document' => true,
            'delete_eventlog' => true,
            'delete_plugin' => true,
            'delete_propertyset' => true,
            'delete_role' => true,
            'delete_snippet' => true,
            'delete_template' => true,
            'delete_tv' => true,
            'delete_user' => true,
            'directory_chmod' => true,
            'directory_create' => true,
            'directory_list' => true,
            'directory_remove' => true,
            'directory_update' => true,
            'edit_category' => true,
            'edit_chunk' => true,
            'edit_context' => true,
            'edit_document' => true,
            'edit_locked' => true,
            'edit_plugin' => true,
            'edit_propertyset' => true,
            'edit_role' => true,
            'edit_snippet' => true,
            'edit_template' => true,
            'edit_tv' => true,
            'edit_user' => true,
            'element_tree' => true,
            'empty_cache' => true,
            'error_log_erase' => true,
            'error_log_view' => true,
            'export_static' => true,
            'file_create' => true,
            'file_list' => true,
            'file_manager' => true,
            'file_remove' => true,
            'file_tree' => true,
            'file_update' => true,
            'file_upload' => true,
            'file_unpack' => true,
            'file_view' => true,
            'flush_sessions' => true,
            'frames' => true,
            'help' => true,
            'home' => true,
            'import_static' => true,
            'languages' => true,
            'lexicons' => true,
            'list' => true,
            'load' => true,
            'logout' => true,
            'logs' => true,
            'menus' => true,
            'menu_reports' => true,
            'menu_security' => true,
            'menu_site' => true,
            'menu_support' => true,
            'menu_system' => true,
            'menu_tools' => true,
            'menu_user' => true,
            'messages' => true,
            'namespaces' => true,
            'new_category' => true,
            'new_chunk' => true,
            'new_context' => true,
            'new_document' => true,
            'new_document_in_root' => true,
            'new_plugin' => true,
            'new_propertyset' => true,
            'new_role' => true,
            'new_snippet' => true,
            'new_static_resource' => true,
            'new_symlink' => true,
            'new_template' => true,
            'new_tv' => true,
            'new_user' => true,
            'new_weblink' => true,
            'packages' => true,
            'policy_delete' => true,
            'policy_edit' => true,
            'policy_new' => true,
            'policy_save' => true,
            'policy_template_delete' => true,
            'policy_template_edit' => true,
            'policy_template_new' => true,
            'policy_template_save' => true,
            'policy_template_view' => true,
            'policy_view' => true,
            'property_sets' => true,
            'providers' => true,
            'publish_document' => true,
            'purge_deleted' => true,
            'remove' => true,
            'remove_locks' => true,
            'resource_duplicate' => true,
            'resourcegroup_delete' => true,
            'resourcegroup_edit' => true,
            'resourcegroup_new' => true,
            'resourcegroup_resource_edit' => true,
            'resourcegroup_resource_list' => true,
            'resourcegroup_save' => true,
            'resourcegroup_view' => true,
            'resource_quick_create' => true,
            'resource_quick_update' => true,
            'resource_tree' => true,
            'save' => true,
            'save_category' => true,
            'save_chunk' => true,
            'save_context' => true,
            'save_document' => true,
            'save_plugin' => true,
            'save_propertyset' => true,
            'save_role' => true,
            'save_snippet' => true,
            'save_template' => true,
            'save_tv' => true,
            'save_user' => true,
            'search' => true,
            'settings' => true,
            'sources' => true,
            'source_delete' => true,
            'source_edit' => true,
            'source_save' => true,
            'source_view' => true,
            'steal_locks' => true,
            'tree_show_element_ids' => true,
            'tree_show_resource_ids' => true,
            'undelete_document' => true,
            'unlock_element_properties' => true,
            'unpublish_document' => true,
            'usergroup_delete' => true,
            'usergroup_edit' => true,
            'usergroup_new' => true,
            'usergroup_save' => true,
            'usergroup_user_edit' => true,
            'usergroup_user_list' => true,
            'usergroup_view' => true,
            'view' => true,
            'view_category' => true,
            'view_chunk' => true,
            'view_context' => true,
            'view_document' => true,
            'view_element' => true,
            'view_eventlog' => true,
            'view_offline' => true,
            'view_plugin' => true,
            'view_propertyset' => true,
            'view_role' => true,
            'view_snippet' => true,
            'view_sysinfo' => true,
            'view_template' => true,
            'view_tv' => true,
            'view_unpublished' => true,
            'view_user' => true,
            'workspaces' => true,
          ),
        ),
        2 => 
        array (
          'principal' => 2,
          'authority' => 9999,
          'policy' => 
          array (
            'load' => true,
            'list' => true,
            'view' => true,
            'save' => true,
            'remove' => true,
            'copy' => true,
            'view_unpublished' => true,
          ),
        ),
      ),
    ),
  ),
);